import Vue from 'vue'
import VueRouter from 'vue-router'

import Unauthenticated from './views/Unauthenticated.vue'
import Authenticated from './views/Authenticated.vue'

import Home from './views/Home.vue'
import Warehouse from './views/Warehouse.vue'
//import Goods from './views/Goods'
import Type from './views/Type.vue'
import Categorypriceselling from './views/Categorypriceselling.vue'
import Category from './views/Category.vue'
import Attribute from './views/Attribute.vue'
import Unit from './views/Unit.vue'
import Source from './views/Source.vue'
import Goods from './views/Goods.vue'
import Goodscreate from './views/Goodscreate.vue'
import GoodsRack from './views/GoodsRack.vue'
import Cogs from './views/Cogs.vue'
import Rack from './views/Rack.vue'
import Supplier from './views/Supplier.vue'
import Customer from './views/Customer.vue'
import PODirect from './views/PODirect.vue'
import Periode from './views/Periode.vue'
import Division from './views/Division.vue'

import PurchaseOrder from './views/PurchaseOrder.vue'
import MaterialRequest from './views/MaterialRequest.vue'
import PurchaseRequest from './views/PurchaseRequest.vue'

import Login from './views/Login.vue'
import Logout from './views/Logout.vue'


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
            { path: '/master-data/type', component: Type},
            { path: '/master-data/category-price-selling', component: Categorypriceselling},
            { path: '/master-data/category', component: Category},
            { path: '/master-data/attribute', component: Attribute},
            { path: '/master-data/unit', component: Unit},
            { path: '/master-data/supplier', component: Supplier},
            { path: '/master-data/customer', component: Customer},
            { path: '/master-data/cogs', component: Cogs},
            { path: '/master-data/goods', component: Goods, exact: true },
            { path: '/master-data/goods/create', component: Goodscreate},
            { path: '/master-data/division', component: Division, exact: true },
            { path: '/master-data/periode', component: Periode, exact: true },
            { path: '/stock/goods/rack', component: GoodsRack },
            { path: '/stock/warehouse', component: Warehouse },
            { path: '/stock/rack', component: Rack},
            { path: '/source', component: Source},
            { path: '/purchase-order', component: PurchaseOrder },
            { path: '/purchase-order-direct', component: PODirect},
            { path: '/material-request', component: MaterialRequest },
            { path: '/purchase-request', component: PurchaseRequest },

            { path: '/logout', component: Logout },

            // 404 redirect to home
            { path: '*', redirect: '/' }
        ],
        meta: { requiresAuth: true }
    }


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
            if(!localStorage.getItem('token') || localStorage.getItem('token') === 'undefined') {
                next({ path: '/login', replace: true})
                return
            }
        } catch (err) {
            return
        }
    }

    // if logged in redirect to dashboard
    if(to.path === '/login') {
        if(localStorage.getItem('token') && localStorage.getItem('token') !== 'undefined') {
            next({ path: '/', replace: true})
            return
        }
    }

    next()
})

export default router;

