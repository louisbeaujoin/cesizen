// Importe Axios et le rend disponible globalement pour les requêtes AJAX
import axios from 'axios';
window.axios = axios;

// Ajoute l'en-tête X-Requested-With pour que Laravel reconnaisse les requêtes AJAX
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
