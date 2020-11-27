<template>
  <div>
    <el-button type="primary" id="btnStart" @click="switchSpeechRecognition">
      {{ buttonText }}
    </el-button>
    <!-- <el-form>
      <el-form-item class="row">
        <textarea
          ref="results"
          name="results"
          v-model="textData"
          readonly
        ></textarea>
      </el-form-item>
    </el-form> -->
    <div v-html="textData"></div>
  </div>
</template>

<script>
import { DictateService } from './dictate-service';
export default {
  props: {
    fields: Object,
    mod: Object,
    path: String,
    server: String
  },
  data() {
    return {
      buttonText: 'Распознать речь',
      textDataBase: '',
      textData: '',
      results: '',
      dictateService: null
    };
  },
  mounted() {
    // let workerScript = document.createElement('script');
    // workerScript.setAttribute('src', './recorder-worker.js');
    // workerScript.setAttribute('type', 'text/javascript');
    // document.body.appendChild(workerScript);

    this.dictateService = new DictateService(this.server, this.path);
    console.log(this.dictateService)
  },
  methods: {
    switchSpeechRecognition() {
      if (!this.dictateService.isInitialized()) {
        this.dictateService.init({
          server: this.server, //'wss://api.alphacephei.com/asr/ru/',
          onResults: hyp => {
            console.log(123, hyp);

            this.textDataBase = this.textDataBase + hyp + '\n';
            this.textData = this.textDataBase;
            // this.$refs.results.$el.scrollTop = this.$refs.results.$el.scrollHeight;
          },
          onPartialResults: hyp => {
            console.log('w', hyp);

            this.textData = this.textDataBase + hyp;
          },
          onError: (code, data) => {
            console.log(code, data);
          },
          onEvent: (code, data) => {
            console.log(code, data);
          }
        });
        this.buttonText = 'Stop Recognition';
      } else if (this.dictateService.isRunning()) {
        this.dictateService.resume();
        this.buttonText = 'Stop Recognition';
      } else {
        this.dictateService.pause();
        this.buttonText = 'Start Recognition';
      }
    }
  }
};
</script>
