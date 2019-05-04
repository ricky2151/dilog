import Vue from 'vue'
import VueRouter from 'vue-router'

import Unauthenticated from './views/Unauthenticated'
import Authenticated from './views/Authenticated'

import Home from './views/Home'
import Warehouse from './views/Warehouse'
//import Goods from './views/Goods'
import Type from './views/Type'
import Categorypriceselling from './views/Categorypriceselling'
import Category from './views/Category'
import Attribute from './views/Attribute'
import Unit from './views/Unit'
import Source from './views/Source'
import Goods from './views/Goods'
import Goodscreate from './views/Goodscreate'

import Login from './views/Login'


Vue.use(VueRouter)

const routes = [
    {
        path:'/login', component: Unauthenticated,
        children: [
            { path: '/login', component: Login },
        ]
    },

    {
        path:'/', component: Authenticated,
        children: [
            { path: '/', component: Home },
            { path: '/warehouse', component: Warehouse },
            //{ path: '/goods', component: Goods },
            { path: '/type', component: Type},
            { path: '/categorypriceselling', component: Categorypriceselling},
            { path: '/category', component: Category},
            { path: '/attribute', component: Attribute},
            { path: '/unit', component: Unit},
            { path: '/source', component: Source},
            { path: '/goods', component: Goods },
            { path: '/goodscreate', component: Goodscreate}
        ],
    },


]

const router = new VueRouter({
    routes,
    hashbang: false,
    mode: 'history',
})

export default router;
