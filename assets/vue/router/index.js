import Vue from 'vue';
import VueRouter from 'vue-router';
import Index from "../views/Index";
import Mutation from "../views/Mutation";

Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  routes: [
    { name: 'Home', path: '/', component: Index },
    { name: 'Schedule', path: '/add', component: Mutation },
    { path: '*', redirect: '/' }
  ],
});