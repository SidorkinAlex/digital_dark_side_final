/* global listViewSearchIcon */

export const rand = function(min, max) {
  return Math.floor(min + Math.random() * (max - min + 1));
};

export const hexToRgba = function(hex, a) {
  return `rgba(${hex
    .substr(1)
    .match(/../g)
    .map(x => +`0x${x}`)},${a})`;
};

export const plural = function(str, type = 'year') {
  let text_forms =
    type === 'year' ? ['год', 'года', 'лет'] : ['минута', 'минуты', 'минут'];
  str = Math.abs(str) % 100;
  var n1 = str % 10;
  if (str > 10 && str < 20) {
    return `${str} ${text_forms[2]}`;
  }
  if (n1 > 1 && n1 < 5) {
    return `${str} ${text_forms[1]}`;
  }
  if (n1 == 1) {
    return `${str} ${text_forms[0]}`;
  }
  return `${str} ${text_forms[2]}`;
};

export const uniq = array => Array.from(new Set(array));

export const fullname = (...fio) => fio.filter(i => !!i.trim()).join(' ');

export const scrollToError = (fields, scroll_value = 0, elem = window) => {
  const firstErrorField = Object.keys(fields)[0];
  const stickyPanel = document.querySelector('.inline-buttons')
    ? document.querySelector('.inline-buttons').offsetHeight
    : 0;
  const fieldElem =
    document.querySelector(`[name="${firstErrorField}"]`) ||
    document.querySelector(`[data-name="${firstErrorField}"]`);
  const closestRow = fieldElem.closest('.el-form-item.row');

  if (elem.scrollY >= scroll_value) {
    elem.scroll(0, closestRow.offsetTop - stickyPanel);
  } else {
    elem.scroll(0, closestRow.offsetTop - stickyPanel * 2);
  }
};

export const showFilter = () => {
  listViewSearchIcon.toggleSearchDialog('latest');
  $('#searchDialog .nav-tabs .active').removeClass('active');
  $('#searchDialog .nav-tabs li')
    .first()
    .addClass('active');
  $('#searchDialog').modal('toggle');
};

export const sklonenieCandidates = (num, type) => {
  const n = Number(num);
  const str = type === 'string' ? num : String(num);
  const lastDigit = str[str.length - 1];

  if (lastDigit == 1 && n !== 11) {
    return 'кандидат';
  } else if (
    (n > 1 && n < 5) ||
    (n > 21 && (lastDigit == 2 || lastDigit == 3 || lastDigit == 4))
  ) {
    return 'кандидата';
  } else {
    return 'кандидатов';
  }
};

export const setContactData = (
  type,
  value = null,
  contacts = [],
  index = null
) => {
  let label = '';
  let icon = '';
  let link = value || '';
  let htmlType = 'text';
  const hasPhone = contacts.filter(
    (item, idx) => idx < index && item.value_type === 'phone'
  ).length;

  switch (type) {
    case 'phone':
      if (index !== null) {
        label =
          !contacts.length || !hasPhone
            ? 'Основной телефон'
            : 'Дополнительный телефон';
      } else {
        label = 'Телефон';
      }
      icon = ['fab', 'whatsapp-square'];
      link = value ? `https://wa.me/${value.replace(/\D/g, '')}` : '';
      htmlType = 'tel';
      break;

    case 'email':
      label = 'E-mail';
      icon = ['far', 'envelope'];
      link = value ? `mailto:${value}` : '';
      htmlType = 'email';
      break;

    case 'telegram':
      label = 'Telegram';
      icon = ['fab', 'telegram'];
      link = value ? `https://t.me/${value}` : '';
      break;

    case 'skype':
      label = 'Skype';
      icon = ['fab', 'skype'];
      link = value ? `skype:${value}?chat` : '';
      break;

    case 'facebook':
      label = 'Facebook';
      icon = ['fab', 'facebook'];
      htmlType = 'url';
      break;

    case 'freelance':
      label = 'Фриланс';
      icon = ['fas', 'laptop'];
      htmlType = 'url';
      break;

    case 'site':
      label = 'Сайт';
      icon = ['fas', 'globe'];
      htmlType = 'url';
      break;

    case 'linkedin':
      label = 'LinkedIn';
      icon = ['fab', 'linkedin-in'];
      htmlType = 'url';
      break;

    case 'livejournal':
      label = 'LiveJournal';
      icon = ['fas', 'pencil-alt'];
      htmlType = 'url';
      break;

    case 'moi_krug':
      label = 'Мой Круг';
      icon = ['fas', 'users'];
      htmlType = 'url';
      break;

    case 'icq':
      label = 'ICQ';
      icon = ['far', 'comment'];
      break;

    default:
      break;
  }

  return { label, icon, link, htmlType };
};

export const stagesLength = stages => {
  if (stages.hasOwnProperty('length')) {
    return stages.length;
  } else {
    return Object.keys(stages).length;
  }
};

export const stageWidth = (amount, width) => {
  const unit = width ? 'px' : '%';
  return (width || 100) / amount + unit;
};

export const stageLabel = stage => {
  const { name, count } = stage;
  return `${count} ${sklonenieCandidates(count)} на этапе "${name}"`;
};

export const editView = (module, id) => {
  return `/index.php?module=${module}&action=EditView&record=${id}`;
};

export const detailView = (module, id) => {
  return `/index.php?module=${module}&action=DetailView&record=${id}`;
};

export const listView = module => {
  return `/index.php?module=${module}&action=index`;
}

export const setStageIcon = stage => {
  return `/index.php?entryPoint=download&id=${stage.id}_stage_icon&type=HRPAC_SELECTION_STAGE`;
};

export const page = module => {
  return {
    isVacancy: module === 'HRPAC_VACANCY',
    isCandidate: module === 'HRPAC_CANDIDATES',
    isPanel: module === 'panel_Selection'
  };
};

export const addStageStyles = (module, stage, stages, condition /*,fullW*/) => {
  const { isCandidate, isVacancy, isPanel } = page(module);
  const styles = {};

  if (!isCandidate || (isCandidate && condition)) {
    styles.background = stage.color;
    styles.color = '#ffffff';
  }
  if (isPanel && condition) {
    styles.background = '#ffffff';
    styles.color = stage.color;
  }
  if (!isVacancy && !isPanel) {
    styles.width = stageWidth(stagesLength(stages) /*, fullW*/);
  }

  stage['styles'] = styles;

  return stage;
};

export const listUrlParams = (module, relateModule, id) => {
  return {
    module,
    relate_mdule: relateModule,
    relate_id: id,
    relate_mdule_link: 'hrpac_vacancy_hrpac_candidates_1',
    action: 'Popup',
    mode: 'MultiSelect',
    only_assigned: 1,
    jsqon_return: 1,
    to_pdf: true
  };
};

export const setUrlParam = (action, module) => {
  let param = '';
  [module, action].forEach(prop => {
    switch (prop) {
      case 'index':
        param += 'List';
        break;

      case 'Popup':
        param += 'Card';
        break;

      case 'HRPAC_CANDIDATES':
        param += 'C';
        break;

      case 'HRPAC_VACANCY':
        param += 'V';
        break;

      case 'HRPAC_VACANCY_TEMPLATE':
        param += 'T';
        break;

      default:
        break;
    }
  });
  return param;
};

export const setDateFormat = dateFormat => {
  if (dateFormat) {
    const separator = dateFormat.match(/[./-]/g)[0];
    const dateArray = dateFormat.replace(/%/g, '').split(separator);
    const format = dateArray.map(item => {
      item =
        item == 'Y' || item == 'YY'
          ? item.toLowerCase()
          : item == 'm' || item == 'mm'
          ? item.toUpperCase()
          : item.toLowerCase();
      if (item.length === 1) {
        return item == 'd' || item == 'M' ? item.repeat(2) : item.repeat(4);
      } else return item;
    });

    return format.join(separator);
  }
  return 'dd.MM.yyyy';
};

export const getVal = (source1 = {}, source2 = {}, name, defaultVal) => {
  return source1[name]
    ? source1[name].value
    : source2 && source2[name]
    ? source2[name.toUpperCase()]
    : defaultVal;
};

export const copyText = (self, id) => {
  let range;
  const elem = document.getElementById(id);

  if (document.selection) {
    // IE
    range = document.body.createTextRange();
    range.moveToElementText(elem);
    range.select();
  } else if (window.getSelection) {
    elem.select();
  }

  const copied = document.execCommand('copy');
  if (copied) {
    self.$message({
      offset: 100,
      showClose: true,
      message: 'Текст скопирован',
      type: 'success'
    });
  }
};
