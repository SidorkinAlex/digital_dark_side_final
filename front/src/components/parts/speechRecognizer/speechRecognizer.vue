<template>
  <div>
    <el-button type="primary" @click="switchSpeechRecognition">
      {{ buttonText }}
    </el-button>
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
      // results: '',
      dictateService: null
    };
  },
  mounted() {
    this.dictateService = new DictateService(this.server, this.path);
  //   console.log(this.dictateService)
  },
  methods: {
    switchSpeechRecognition() {
      if (!this.dictateService.isInitialized()) {
        this.dictateService.init({
          server: this.server,
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
