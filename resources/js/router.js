import Vue from 'vue'
import Router from 'vue-router'


Vue.use(Router);



import firstPage from './components/pages/myFirstPage.vue'
import secondPage from './components/pages/mySecondPage.vue'
import hook from './components/pages/basic/hooks.vue'
import method from './components/pages/basic/methods.vue'

// project importer
import home from './components/pages/home.vue'
import tags from './admin/pages/tags.vue'
import adminusrs from './admin/pages/adminusers.vue'
import category from './admin/pages/category.vue'
import login from './admin/pages/login.vue'
import role from './admin/pages/role.vue'
import assignRole from './admin/pages/assignRole.vue'
import create_blogs from './admin/pages/createBlog.vue'

// vuex project importer
import usecomp from './vuex/usecomp.vue'

const routes = [
    // project Routes starts
    {
        path: '/',
        component: home,
        name: '/',
    },

    {
        path: '/tags',
        component: tags,
        name: 'tags',
    },

    {
        path: '/category',
        component: category,
        name: 'category',
    },

    {
        path: '/adminusers',
        component: adminusrs,
        name: 'adminusers',
    },

    {
        path: '/login',
        component: login,
        name: 'login',
    },

    {
        path: '/role',
        component: role,
        name: 'role',
    },

    {
        path: '/assignRole',
        component: assignRole,
        name: 'assignRole',
    },

    {
        path: '/create_blogs',
        component: create_blogs,
        name: 'create_blogs',
    },

    // project Routes ends
    // vuex Routes starts

    {
        path: '/usecomp',
        component: usecomp,
    },


    // vuex Routes ends


    {
        path: '/my-new-vue-route',
        component: firstPage,
    },

    {
        path: '/my-second-vue-route',
        component: secondPage,
    },
    // vue hooks

    {
        path: '/vue-hooks',
        component: hook,
    },

    // methods
    {
        path: '/vue-methods',
        component: method,
    },
];


export default new Router({
    mode: 'history',
    routes
})
