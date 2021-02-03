import Vue from 'vue'
import ElementUI, { Message } from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import App from './App.vue'
import router from './router'
import VueLoading from 'vue-loading-template'
import mavonEditor from "mavon-editor";
import "mavon-editor/dist/css/index.css";
import './assets/iconfont/iconfont.css'

Vue.use(VueLoading, /** options **/)
Vue.use(mavonEditor)
Vue.use(ElementUI)
Vue.prototype.$message = Message

new Vue({
  router:router,
  el: '#app',
  render: h => h(App)
})
