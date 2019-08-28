

import { createLocalVue, mount, shallowMount } from '@vue/test-utils';

//import package
import vuetify from "vuetify";
import flushPromises from 'flush-promises'
import BabelPolyFill from "babel-polyfill"
import VueRouter from "vue-router"
import VueSwal from 'vue-swal'

//import resource untuk router
import router from "./../../router.js"

//iport component
import Unauthenticated from './../Unauthenticated.vue'
import Authenticated from './../Authenticated.vue'
import Login from './../Login.vue'
import Home from './../Home.vue'
import DilogApp from './../../DilogApp.vue'

window.axios = require('axios');

// import Warehouse from './../Warehouse.vue'

// import Type from './../Type.vue'
// import Categorypriceselling from './../Categorypriceselling.vue'
// import Category from './../Category.vue'
// import Attribute from './../Attribute.vue'
// import Unit from './../Unit.vue'
// import Source from './../Source.vue'
// import Goods from './../Goods.vue'
// import Goodscreate from './../Goodscreate.vue'
// import GoodsRack from './../GoodsRack.vue'
// import Cogs from './../Cogs.vue'
// import Rack from './../Rack.vue'
// import Supplier from './../Supplier.vue'
// import Customer from './../Customer.vue'
// import PODirect from './../PODirect.vue'

// import PurchaseOrder from './../PurchaseOrder.vue'



// import Vue from 'vue'


//1. buat localVue dan use2nya
const localVue = createLocalVue()
localVue.use(VueRouter);
localVue.use(BabelPolyFill);


//2. buat describenya
describe('Login', () => {
	//3. buat itnya
	it("renders a child component via routing", done => {
		// const router = new VueRouter(
		// 	[
		// 	    {
		// 	        path:'/login', name : "login", component: Unauthenticated,
		// 	        children: [
		// 	            { path: '/login', name : "login2", component: Login },
		// 	        ]
		// 	    },

		// 	    {
		// 	        path:'/', name : "home", component: Authenticated,
		// 	        children: [
		// 	            { path: '/', name : "home2", component: Home },
			            
		// 	        ],
		// 	        meta: { requiresAuth: true }
		// 	    }


		// 	]
		// )
		const wrapper = mount(Login, { localVue, router })
		
		//expect(wrapper.html()).toContain('Halo')
		//router.push("/login")
		//await flushPromises()
		//input email & password
		//console.log(wrapper.vm.coba);
		wrapper.setData({in_email : 'tvon@example.org', in_password : "password"});

		//window.location.assign = jest.fn() // Create a spy


		//klik button
		 //wrapper.find("#buttonsubmit").trigger('click');

		 //coba axios langsung
		axios.post('http://127.0.0.1:8000/api/auth/login',{
    				email:'tvon@example.org',
    				password:'password'
    			}).then(r => console.log(r));
		console.log('masuk');
		//cek html
		
		//expect(wrapper.html()).toContain('Halo')

		
	})
	
})