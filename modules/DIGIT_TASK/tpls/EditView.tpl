{* <task-edit :fields="fields" :mod="mod" :date-format="dateFormat"></task-edit> *}
<script src="recorder-worker.js"></script>
{* <speech-recognizer
  :server="server"
  :path="path"
></speech-recognizer>
<br> *}
<task-edit
  :fields="fields"
  :mod="mod"
  :date-format="dateFormat"
  :server="server"
  :path="path"
></task-edit>
<script>
  const fields = {$fields|@json_encode};
  const mod = {$MOD|@json_encode};
  const dateFormat = {$CALENDAR_DATEFORMAT|@json_encode};
  const WORKER_PATH = 'recorder-worker.js';
  const SERVER = 'wss://st-web.ru/socket/';
  console.log(fields, dateFormat);

  {literal}
  new Vue({
    el: "#content",
    data() {
      return {
        server: SERVER,
        path: WORKER_PATH,
        fields,
        mod,
        dateFormat: {
          date: dateFormat || 'm/d/Y',
          time: 'HH:mm'
        }
      }
    },
    components: {
      'speech': CRMSuite.default.SpeechRecognizer,
      'task-edit': CRMSuite.default.TaskEdit
      //'task': CRMSuite.default.TaskEdit,
    },
  });
  {/literal}
</script>