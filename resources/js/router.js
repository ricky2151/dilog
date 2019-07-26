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
import GoodsRack from './views/GoodsRack'
import Cogs from './views/Cogs'
import Rack from './views/Rack'
import Supplier from './views/Supplier'
import Customer from './views/Customer'
import Coba from './views/Coba'

import Login from './views/Login'
import Logout from './views/Logout'


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
            { path: '/goodsrack', component: GoodsRack },
            { path: '/goodscreate', component: Goodscreate},
            { path: '/cogs', component: Cogs},
            { path: '/rack', component: Rack},
            { path: '/supplier', component: Supplier},
            { path: '/customer', component: Customer},
            { path: '/logout', component: Logout, },

            { path: '/coba', component: Coba, },
        ],
        meta: { requiresAuth: true }
    },


]

const router = new VueRouter({
    routes,
    hashbang: false,
    mode: 'history',
})

router.beforeEach(async (to, from, next) => {
    // check if the route requires authentication and user is not logged in
    if (to.matched.some(route => route.meta.requiresAuth)) {
        try {
            if(!localStorage.getItem('token')) {
                next({ path: '/login', replace: true})
                return
            }
        } catch (err) {
            return
        }
    }

    // if logged in redirect to dashboard
    if(to.path === '/login') {
        if(localStorage.getItem('token')) {
            next({ path: '/', replace: true})
            return
        }
    }

    next()
})

export default router;
