import Vue from 'vue';
import { plural } from '@/utils/helpers';
import { FIELD, ACTION } from '@/utils/constants';

Vue.filter('toupper', str => {
  if (!str) return '';
  str = str.toString();
  return str.toUpperCase();
});

Vue.filter('tolower', str => {
  if (!str) return '';
  str = str.toString();
  return str.toLowerCase();
});

Vue.filter('capitalize', str => {
  if (!str) return '';
  str = str.toString();
  return str.charAt(0).toUpperCase() + str.slice(1);
});
