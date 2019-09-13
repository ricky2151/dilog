<template>
	<div v-if='dialog'>
		<v-dialog v-model='dialog' width=900>
			<v-card>
				<v-toolbar dark color='menu'>
					<v-btn icon dark v-on:click='close_dialog()'>
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title>SPBM</v-toolbar-title>
				</v-toolbar>
				<div>

					<v-layout row style='font-size: 16px;margin: 10px 4px'>
			        	<v-flex xs3 class='marginleft30'>
			        		<b >No PO :</b>
			        	</v-flex>
			        	<v-flex xs5>
			        		<b>{{ purchase_order.no_po }}</b>
			        	</v-flex>
			        </v-layout>

			        <v-layout row style='font-size: 16px;margin: 10px 4px'>
			        	<v-flex xs3 class='marginleft30'>
			        		<b>Type :</b>
			        	</v-flex>
			        	<v-flex xs5>
			        		<b>{{ purchase_order.po_type_name }}</b>
			        	</v-flex>
			        </v-layout>

			        <v-layout row style='font-size: 16px;margin: 10px 4px'>
			        	<v-flex xs3 class='marginleft30' style='padding-top: 20px'>
			        		<b>Target Warehouse : </b>
			        	</v-flex>
			        	<v-flex xs5>
			        		<v-select
								label='Select Warehouse'
								v-model='input.warehouse'

								:items='warehouses'
								item-text='name'
								return-object

								class="pa-2"
								>
							</v-select>
			        	</v-flex>
			        </v-layout>

			        <v-layout row style='font-size: 16px;margin: 10px 4px'>
			        	<v-flex xs3 class='marginleft30' style='padding-top: 20px'>
			        		<b>Delivery Order : </b>
			        	</v-flex>
			        	<v-flex xs5>
			        		<v-text-field
								label='Delivery Order'
								v-model='input.delivery_order'
								class="pa-2"
								>
							</v-text-field>
			        	</v-flex>
			        </v-layout>

			        <v-data-table
			        	
			            disable-initial-sort
			            :headers="headers_goods"
			            :items="input.goods"
			            class="datatable"
			        >
			        	
				        	
						<template v-slot:items="props">
					    	
						        <td>{{ props.item.no }}</td>
						        <td>{{ props.item.goods_name }}</td>
						        <td>{{ props.item.order_quantity }}</td>
						        <td>{{ props.item.have_arrived }}</td>
						        <td>
						        	<v-text-field
										label='Incoming'
										v-model='input.goods[props.index].incoming'
										class="pa-2"
										>
									</v-text-field>
						        </td>
						        <td>
						        	<v-select
						        		v-if='input.warehouse'
										label='Select Rack'
										v-model='input.goods[props.index].rack'

										:items='input.warehouse.racks'
										item-text='name'
										return-object

										class="pa-2"
										>
									</v-select>
						        </td>
						    
						</template>
			        </v-data-table>

			        <v-layout row>
			        	<v-flex xs3 class='marginleft30'>
			        		<b>% Arrival before submit : </b>
			        	</v-flex>
			        	<v-flex xs5>
			        		<b>{{ percent_goods }}%</b>
			        	</v-flex>
			        </v-layout>

			        <v-layout row>
			        	<v-flex xs3 class='marginleft30'>
			        		<b>% Arrival After submit : </b>
			        	</v-flex>
			        	<v-flex xs5>
			        		<b>{{percent_after_goods}}%</b>
			        	</v-flex>
			        </v-layout>

			        <v-btn v-on:click='submit' dark color='menu' style='margin: 20px 30px' class='marginright25'>Submit SPBM</v-btn>

			         <v-layout row style='font-size: 16px;margin-top:50px'>
			        	<v-flex xs3 class='marginleft30' style='padding-top: 20px'>
			        		<b>History Incoming : </b>
			        	</v-flex>
			        	
			        </v-layout>

			         <v-data-table
			            disable-initial-sort
			            :headers="headers_history"
			            :items="history_detail_spbm_po"
			            class="datatable"
			        >
			        	
				        	
						<template v-slot:items="props">
					    	
						        <td>{{ props.index + 1 }}</td>
						        <td>{{ props.item.delivery_order_no }}</td>
						        <td>{{ props.item.goods_name }}</td>
						        <td>{{ props.item.have_arrived }}</td>
						        <td>{{ props.item.warehouse_name }}</td>
						        <td>{{ props.item.rack_name }}</td>
						    
						</template>
			        </v-data-table>

				</div>
			</v-card>
		</v-dialog>
	</div>
</template>
<script>
	import axios from 'axios'
	export default {
		
		data() {
			return {
				dialog:false,

				headers_goods: [
					{ text: 'No', value:'no'},
    				{ text: 'Goods', value:'goods_name'},
    				{ text: 'Order QTY', value:'order_quantity'},
    				{ text: 'Have Arrived', value:'have_arrived'},
    				{ text: 'Incoming', value:'incoming'},
    				{ text: 'Target Rack', value:'target_rack'},
    				
				],

				headers_history: [
					{ text: 'No', value:'no'},
    				{ text: 'Delivery Order', value:'delivery_order'},
    				{ text: 'Goods', value:'goods_name'},
    				{ text: 'Have Arrived', value:'have_arrived'},
    				{ text: 'Warehouse', value:'warehouse_name'},
    				{ text: 'Rack', value:'rack_name'},
    				
				],

				id_po : null,
				percent_goods : 0,
				percent_after_goods : 0,

				purchase_order : [],
				input : {
					warehouse:null,
					delivery_order:null,
					goods:null,
				},
				warehouses : [],
				history_detail_spbm_po : [],
			}

		},
		methods : {
			submit()
			{
				const formData = new FormData();
				formData.append('purchase_order_id', this.purchase_order.id);
				formData.append('delivery_order_no', this.input.delivery_order);
				formData.append('warehouse_id', this.input.warehouse.id);
				for(var i = 0;i<this.input.goods.length;i++)
				{
					formData.append('spbm_details[' + i + '][goods_id]' , this.input.goods[i].goods_id);
					formData.append('spbm_details[' + i + '][qty]' , this.input.goods[i].incoming);
					formData.append('spbm_details[' + i + '][rack_id]' , this.input.goods[i].rack.id);
					formData.append('spbm_details[' + i + '][notes]' , 'bagus sekali');
					
				}
				axios.post('/api/spbms', formData, {
                    params:{
                        token:localStorage.getItem('token'),
                        purchase_order_id:this.id_po,
                    }
                })
                .then((r) => {
                	//clear data
                	this.input.warehouse = null;
                	this.input.delivery_order = null;
                	for(var i = 0;i<this.input.goods.length;i++)
                	{
                		this.input.goods[i].incoming = 0;
                		this.input.goods[i].rack = null;
                	}
                	this.dialog = false;
                	this.$emit('done');
                })
                .catch(error => {
                	
                	this.input.warehouse = null;
                	this.input.delivery_order = null;
                	for(var i = 0;i<this.input.goods.length;i++)
                	{
                		this.input.goods[i].incoming = 0;
                		this.input.goods[i].rack = null;
                	}
                	this.dialog = false;
                	swal('SPBM Cannot Submited', 'Cant open because PO status is not approve/complete', 'error');
                	
                });
			},
			open_dialog()
			{
				this.get_data();
				
					
				

				
			},
			close_dialog()
			{
				this.dialog = false;
			},
			get_data()
			{
				var url = "/api/spbms/create";
				
                axios.get(url, {
                    params:{
                        token:localStorage.getItem('token'),
                        purchase_order_id:this.id_po,
                    }
                },{
                    headers: {
                        'Accept': 'application/json',
                        'Content-type': 'application/json'
                    }
                })
                .then(r => {
                	
                	this.fill_data(r);
                })
                .catch(error => {

                	swal('SPBM Is Not Avaiable', "Can't open because PO status is not approve/complete", 'error');
                	
                });


                    
                    

                
			},
			fill_data(r)
			{
				
				
				
				r = r.data.items;
				this.purchase_order = r.purchase_order;
				this.warehouses = r.warehouses;
				this.percent_goods = r.percent_goods;
				this.history_detail_spbm_po = r.history_detail_spbm_po;

				this.input.goods = r.goods;

				for(var i=0;i<this.input.goods.length;i++)
				{
					this.input.goods[i].no = (i + 1);
				}
			
				this.dialog = true;	

				


			}
		},
		watch : {
			'input.goods' : {
				handler : function(newVal,oldVal){
					//cek input incoming dari user, benar atau salah
					for(var i = 0;i<this.input.goods.length;i++)
					{
						if(parseInt(this.input.goods[i].incoming) + parseInt(this.input.goods[i].have_arrived)  > parseInt(this.input.goods[i].order_quantity))
						{

							this.input.goods[i].incoming = 0;
						}
					}

					//set nilai % arrival before submit
					var total_have_arrived_plus_incoming = 0;
					var total_order_qty = 0;
					for(var i = 0;i<this.input.goods.length;i++)
					{
						total_have_arrived_plus_incoming += (parseInt(this.input.goods[i].have_arrived) + parseInt(this.input.goods[i].incoming));
						total_order_qty += parseInt(this.input.goods[i].order_quantity);
					}
					if(total_have_arrived_plus_incoming && total_order_qty)
					{
						this.percent_after_goods = Math.floor((total_have_arrived_plus_incoming / total_order_qty) * 100);

					}
					else
					{
						this.percent_after_goods = 0;
					}
				},
				deep:true,
			}
		}
	}
</script>