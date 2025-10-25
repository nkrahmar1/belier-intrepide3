import axios from 'axios';
// Exemple minimal standard Laravel pour bootstrap.js

window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {
    console.error('Bootstrap JS non chargé', e);
}
// Axios pour requêtes HTTP
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
