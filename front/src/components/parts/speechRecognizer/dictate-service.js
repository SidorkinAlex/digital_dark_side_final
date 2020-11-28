export class DictateService {
  constructor(server, path) {
    // Defaults
    this.SERVER = server;
    // Send blocks 4 x per second as recommended in the server doc.
    this.INTERVAL = 250;
    // Path to worker javascript
    this.WORKER_PATH = path;

    // Error codes (mostly following Android error names and codes)
    this.ERR_NETWORK = 2;
    this.ERR_AUDIO = 3;
    this.ERR_SERVER = 4;
    this.ERR_CLIENT = 5;

    // Event codes
    this.MSG_WAITING_MICROPHONE = 1;
    this.MSG_MEDIA_STREAM_CREATED = 2;
    this.MSG_INIT_RECORDER = 3;
    this.MSG_RECORDING = 4;
    this.MSG_SEND = 5;
    this.MSG_SEND_EMPTY = 6;
    this.MSG_SEND_EOS = 7;
    this.MSG_WEB_SOCKET = 8;
    this.MSG_WEB_SOCKET_OPEN = 9;
    this.MSG_WEB_SOCKET_CLOSE = 10;
    this.MSG_STOP = 11;
    this.MSG_SERVER_CHANGED = 12;
  }

  init(cfg) {
    this.config = cfg || {};
    this.config.server = this.config.server || this.SERVER;
    this.config.audioSourceId = this.config.audioSourceId;
    this.config.interval = this.config.interval || this.INTERVAL;
    this.config.onReadyForSpeech =
      this.config.onReadyForSpeech || function() {};
    this.config.onEndOfSpeech = this.config.onEndOfSpeech || function() {};
    this.config.onResults = this.config.onResults || function() {};
    this.config.onPartialResults =
      this.config.onPartialResults || function() {};
    this.config.onEndOfSession = this.config.onEndOfSession || function() {};
    this.config.onEvent = this.config.onEvent || function() {};
    this.config.onError = this.config.onError || function() {};

    this.paused = true;

    var audioSourceConstraints = {};
    this.config.onEvent(
      this.MSG_WAITING_MICROPHONE,
      'Waiting for approval to access your microphone ...'
    );

    try {
      window.AudioContext = window.AudioContext || window.webkitAudioContext;
      navigator.getUserMedia =
        navigator.getUserMedia || navigator.webkitGetUserMedia;
      this.audioContext = new AudioContext();

      if (navigator.getUserMedia) {
        if (this.config.audioSourceId) {
          audioSourceConstraints.audio = {
            optional: [{ sourceId: this.config.audioSourceId }]
          };
        } else {
          audioSourceConstraints.audio = true;
        }
        navigator.getUserMedia(
          audioSourceConstraints,
          this.startUserMedia.bind(this),
          function(e) {
            this.config.onError(
              this.ERR_CLIENT,
              'No live audio input in this browser: ' + e
            );
          }
        );
      } else {
        this.config.onError(this.ERR_CLIENT, 'No user media support');
      }
    } catch (e) {
      // Firefox 24: TypeError: AudioContext is not a constructor
      // Set media.webaudio.enabled = true (in about:this.config) to fix this.
      this.config.onError(
        this.ERR_CLIENT,
        'Error initializing Web Audio browser: ' + e + ' ' + e.stack
      );
    }

    try {
      this.createWebSocket();
    } catch (e) {
      this.config.onError(
        this.ERR_CLIENT,
        'No web socket support in this browser!' + e + ' ' + e.stack
      );
    }
  }

  isInitialized() {
    return this.ws != null;
  }

  pause() {
    this.paused = true;
  }

  resume() {
    this.paused = false;
  }

  isRunning() {
    return this.paused;
  }

  // Cancel everything without waiting on the server
  cancel() {
    // Stop the regular sending of audio (if present)
    clearInterval(this.intervalKey);
    if (this.worker) {
      this.pause();
      this.clearWorker();
      this.config.onEvent(this.MSG_STOP, 'Stopped recording');
    }
    if (this.ws) {
      this.ws.close();
      this.ws = null;
    }
  }

  startUserMedia(stream) {
    var input = this.audioContext.createMediaStreamSource(stream);
    this.config.onEvent(this.MSG_MEDIA_STREAM_CREATED, 'Media stream created');
    //Firefox loses the audio input stream every five seconds
    // To fix added the input to window.source
    window.source = input;

    // make the analyser available in window context
    window.userSpeechAnalyser = this.audioContext.createAnalyser();
    input.connect(window.userSpeechAnalyser);

    this.initWorker(input);
    this.config.onEvent(this.MSG_INIT_RECORDER, 'Recorder initialized');
  }

  socketSend(blob) {
    if (this.paused) return;
    if (this.ws) {
      var state = this.ws.readyState;
      if (state == 1) {
        // If blob is an audio blob
        if (blob instanceof Blob) {
          if (blob.size > 0) {
            this.ws.send(blob);
            this.config.onEvent(
              this.MSG_SEND,
              'Send: blob: ' + blob.type + ', ' + blob.size + ', ' + blob
            );
          } else {
            this.config.onEvent(
              this.MSG_SEND_EMPTY,
              'Send: blob: ' + blob.type + ', EMPTY'
            );
          }
          // Otherwise it's the EOS tag (string)
        } else {
          this.ws.send(blob);
          // this.config.onEvent(this.MSG_SEND_EOS, 'Send tag: ' + blob);
        }
      } else {
        this.config.onError(
          this.ERR_NETWORK,
          'WebSocket: readyState!=1: ' + state + ': failed to send: ' + blob
        );
      }
    } else {
      this.config.onError(
        this.ERR_CLIENT,
        'No web socket connection: failed to send: ' + blob
      );
    }
  }

  createWebSocket() {
    this.ws = new WebSocket(this.config.server);

    this.ws.onmessage = e => {
      var data = e.data;
      this.config.onEvent(this.MSG_WEB_SOCKET, data, e);
      if (data instanceof Object && !(data instanceof Blob)) {
        this.config.onError(
          this.ERR_SERVER,
          'WebSocket: onEvent: got Object that is not a Blob'
        );
      } else if (data instanceof Blob) {
        this.config.onError(this.ERR_SERVER, 'WebSocket: got Blob');
      } else {
        var res = JSON.parse(data);
        if (res.continue) {
          // do nothing
        } else if (res.partial) {
          this.config.onPartialResults(res.partial);
        } else if (res.text) {
          this.config.onResults(res.text);
        }
      }
    };

    // Start recording only if the socket becomes open
    this.ws.onopen = () => {
      this.intervalKey = setInterval(() => {
        this.exportWorkerData();
      }, this.config.interval);

      // Start recording
      this.resume();
      this.config.onReadyForSpeech();
      this.config.onEvent(
        this.MSG_WEB_SOCKET_OPEN,
        'Opened the socket successfully'
      );
    };

    // This can happen if the blob was too big
    // E.g. "Frame size of 65580 bytes exceeds maximum accepted frame size"
    // Status codes
    // http://tools.ietf.org/html/rfc6455#section-7.4.1
    // 1005:
    // 1006:
    this.ws.onclose = e => {
      // var code = e.code;
      // var reason = e.reason;
      // var wasClean = e.wasClean;
      // The server closes the connection (only?)
      // when its endpointer triggers.
      this.config.onEndOfSession();
      this.config.onEvent(
        this.MSG_WEB_SOCKET_CLOSE,
        e.code + '/' + e.reason + '/' + e.wasClean
      );
    };

    this.ws.onerror = e => {
      var data = e.data;
      this.config.onError(this.ERR_NETWORK, data);
    };
  }

  initWorker(source) {
    var node = source.context.createScriptProcessor(4096, 1, 1);
    this.worker = new Worker(this.WORKER_PATH);

    this.worker.onmessage = e => {
      if (this.paused) return;

      var blob = e.data;
      this.socketSend(blob);
    };

    node.onaudioprocess = e => {
      if (this.paused) return;

      this.worker.postMessage({
        command: 'record',
        buffer: [e.inputBuffer.getChannelData(0)]
      });
    };

    this.worker.postMessage({
      command: 'init',
      config: {
        sampleRate: source.context.sampleRate
      }
    });

    source.connect(node);
    node.connect(source.context.destination); //TODO: this should not be necessary (try to remove it)
  }

  clearWorker() {
    this.worker.postMessage({ command: 'clear' });
  }

  exportWorkerData() {
    // this.worker.postMessage({ command: 'exportData' });
    this.worker.postMessage({ command: 'exportWAV' });
  }
}
