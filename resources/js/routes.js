//file ini tidak terpakai
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


import PurchaseOrder from './views/PurchaseOrder.vue'

import MaterialRequest from './views/MaterialRequest.vue'

import Login from './views/Login.vue'
import Logout from './views/Logout.vue'



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
            { path: '/podirect', component: PODirect},
            { path: '/materialrequest', component: MaterialRequest},

            { path: '/purchaseorder', component: PurchaseOrder},

            { path: '/logout', component: Logout, },

            
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

export default routes;