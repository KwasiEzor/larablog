import axios from "axios";
window.axios = axios;
import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
