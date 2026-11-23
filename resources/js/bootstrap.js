import axios from 'axios';
window.axios = axios;

window.axios.default.header.commons['X-Requested-With'] = 'XMLHttpRequest';
