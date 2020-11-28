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

export const editView = (module, id) => {
  return `/index.php?module=${module}&action=EditView&record=${id}`;
};

export const detailView = (module, id) => {
  return `/index.php?module=${module}&action=DetailView&record=${id}`;
};

export const listView = module => {
  return `/index.php?module=${module}&action=index`;
};

export const page = module => {
  return {
    isTask: module === 'DIGIT_TASK'
  };
};

export const addStageStyles = (module, stage) => {
  const { isTask } = page(module);
  const styles = {};

  if (isTask) {
    styles.background = stage.color;
    styles.color = '#ffffff';
  }

  stage['styles'] = styles;
  return stage;
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
