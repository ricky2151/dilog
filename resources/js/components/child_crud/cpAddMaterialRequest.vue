<template>
	<div>

		<v-layout row >
			<v-toolbar
		      color='blue'
		      dark
		    >
		        <v-toolbar-items style='height: 30px'>
		        	<v-toolbar-title v-if='this_user_is_finance'><v-btn color='blue' dark depressed @click='$emit("back")'><h3>BACK</h3></v-btn></v-toolbar-title>
		          <v-toolbar-title><v-btn color='blue' dark depressed @click='change_tab("Home")'><h3>HOME</h3></v-btn></v-toolbar-title>
		          <v-toolbar-title v-if='profile_enable'><v-btn color='blue' dark depressed @click='change_tab("Profile")'><h3>PROFILE</h3></v-btn></v-toolbar-title>
		        </v-toolbar-items>
		  </v-toolbar>
		</v-layout>
		<div v-show='tab=="Home"' class='mr_div_container'>
			<v-dialog v-model="dialog_edit_qty_goods" width=750>
		        <v-card>
		            <v-toolbar dark color="menu">
		                <v-btn icon dark v-on:click="change_dialog_qty_goods(0)">
		                    <v-icon>close</v-icon>
		                </v-btn>
		                <v-toolbar-title>Input Quantity</v-toolbar-title>

		            </v-toolbar>
		            <div style='padding:30px'>
		                <v-text-field
						label='Quantity'
						v-model='input_quantity'
						class="pa-2"
						>
						</v-text-field>
						<v-btn @click='submit_quantity'>Save</v-btn>
		            </div>
		        </v-card>
		    </v-dialog>


			

			<v-layout row class='addmr_row'>
				<v-flex xs4>
					<div class='addmr_column'>
						<v-select
							v-if='periode'
							label='Select Periode'
							v-model='periode_selected'
							:items='periode'
							item-text='name'
							return-object
							class="pa-2"
							>
						</v-select>
					</div>
				</v-flex>
				<v-flex xs4>
				</v-flex>
				<v-flex xs4>
					<div class='addmr_column'>
						
					</div>
				</v-flex>
			</v-layout>

			<v-layout row class='addmr_row'>
				<!-- list goods -->
				<v-flex xs8>
					<v-layout v-for='(idxrow,index) in static_data.index_row' :key='index' row>
						<v-flex xs4 v-for='(idxcolumn, index) in static_data.column_row' :key='index' :set='idxgoods=convert_to_idx_goods(idxrow,idxcolumn,page)'>
							
							<div v-if='(idxgoods + 1) >= (((page - 1) * 6) + 1) && (idxgoods + 1) <= (((page - 1) * 6) + 6) && goods[idxgoods]'>
								<center class='addmr_goods'>
									<img :src='goods[idxgoods].thumbnail' height="150"/>
									<h3>{{goods[idxgoods].name}}</h3>
									<h3>Rp. {{goods[idxgoods].avg_price}}</h3>
									<v-btn color='rgb(0,0,0,0.1)' depressed dark v-if='goods[idxgoods].qty == 0' @click='change_qty_goods(convert_to_idx_goods(idxrow,idxcolumn,page),1)'><b>Add</b></v-btn>
									<div v-if='goods[idxgoods].qty > 0'>
										<v-btn color='rgb(0,0,0,0.1)' dark depressed class='btnplusminus' @click='change_qty_goods(convert_to_idx_goods(idxrow,idxcolumn,page),-1)'><b>-</b></v-btn>
										<div style="display:inline-block;"><h3>{{goods[idxgoods].qty}}</h3></div>
										<v-btn color='rgb(0,0,0,0.1)' dark depressed class='btnplusminus' @click='change_qty_goods(convert_to_idx_goods(idxrow,idxcolumn,page),1)'><b>+</b></v-btn>
									</div>

								</center>
							</div>
							<br><br>
						</v-flex>
					</v-layout>
					<center>
						<v-btn class='button-action' fab depressed small dark @click='change_page(-1)'><v-icon small>keyboard_arrow_left</v-icon></v-btn>
						<v-btn class='button-action' fab depressed small dark @click='change_page(1)'><v-icon small>keyboard_arrow_right</v-icon></v-btn>
					</center>
				</v-flex>

				<!-- summary selected goods -->
				<v-flex xs4 class='addmr_summary'>
					<div><h1 style="font-size:40px">Summary</h1></div><br><br><br>
					<div v-for='(objgoods,index) in goods' :key='index'>
						<template v-if='objgoods.qty > 0'>
							<div><h3>{{objgoods.name}}</h3></div>
							<div>
								<h3 style='display: inline-block;'>{{objgoods.qty}} x {{objgoods.avg_price}} = Rp. {{objgoods.subtotal}}</h3>
								<v-btn class='button-action' color="rgb(255, 0, 0, 0.0)" fab depressed small @click='change_dialog_qty_goods(1,index)'><v-icon small>edit</v-icon></v-btn>
								<v-btn class='button-action' color="rgb(255, 0, 0, 0.0)" fab depressed small @click='change_qty_goods(index,null,0)'><v-icon small>delete</v-icon></v-btn>
							</div>
							<br>
							
						</template>
					</div>
					<div>
						<hr>
						<h2>Total : Rp. {{total}}</h2>
					</div>
					<br>
					<div>
						<v-btn @click='submit_mr'>Submit</v-btn>
					</div>
				</v-flex>
			</v-layout>

		</div>

		<div v-show='tab=="Profile"' class='mr_div_container'>
			<cp-detail 
	        prop_title='Detail Material Request' 
	        prop_response_attribute='material_request_details'
	        :prop_headers='profile.header_detail_mr'
	        :prop_columns='profile.single_detail_mr'
	        ref="cpDetailMr"
	        ></cp-detail>
			<v-layout row style='padding:20px'>
				<v-flex xs1>
					<img src='/storage/profile.png' width="100" />
				</v-flex>
				<v-flex xs10 style='margin-left: 20px;'>
					<div><h3>Nama : {{profile.name}}</h3></div>
					<div><h3>Email : {{profile.email}}</h3></div>
					<div><h3>MR Count : {{profile.mr_count}}</h3></div>
					<div><h3>MR Total : {{profile.mr_total}}</h3></div>
				</v-flex>
			</v-layout>

			<v-layout row>
				<v-flex xs12>
					<v-data-table
		            disable-initial-sort
		            :headers="profile.header_profile"
		            :items="profile.list_mr"
		            >
		            <template v-slot:items="props">
		                <td>{{ props.index + 1 }}</td>
		                <td>{{ props.item.no_mr }}</td>
		                <td>{{ props.item.total }}</td>
		                <td>{{ props.item.created_at }}</td>
		                <td>{{ props.item.approved_by_user_name }}</td>
		                <td>{{ props.item.status }}</td>
		                <td>
		                	<div class="text-xs-left">
				                <v-menu offset-y>
				                  <template v-slot:activator="{ on }">
				                    <v-btn
				                      color="primary"
				                      dark
				                      v-on="on"
				                      class='btnaction'
				                    >
				                      Action
				                    </v-btn>
				                  </template>
				                  <v-list>
				                    <v-list-tile
				                      v-on:click="open_detail(props.item.id, 'material_request_details')"
				                    >
				                      <v-list-tile-title>Detail</v-list-tile-title>
				                    </v-list-tile>
				                  </v-list>
				                </v-menu>
				            </div>
		                </td>
		            </template>
		            </v-data-table>
	        	</v-flex>
			</v-layout>

		</div>
	</div>
</template>
<script>
import cpDetail from './../popup/cpDetail.vue'
	export default {
		components : {
			cpDetail,
		},
		data () {
			return {
				static_data : 
				{

					index_row : [1,2],
					column_row : [1,2,3],
				},

				tab : "Home",
				profile_enable : true,
				this_user_is_finance : true,

				profile : 
				{
					header_detail_mr : [
						{ text : 'No', value:'no'},
						{ text : 'Goods', value:'goods_name'},
						{ text : 'Qty', value:'qty'},
						{ text : 'Total', value:'total'},
						{ text : 'Status', value:'status'},
					],
					single_detail_mr : 
					{
						'id' : {show : false},
						'goods_name' : {show : true},
						'qty' : {show : true},
						'total' : {show : true},
						'status' : {show : true},
					},

					header_profile : [
						{ text : 'No', value:'no'},
						{ text : 'No MR', value:'no_mr'},
						{ text : 'Total', value:'total'},
						{ text : 'Created At', value:'created_at'},
						{ text : 'Approved By', value:'approved_by_user_name'},
						{ text : 'Status', value:'status'},
						{ text: 'Action', value:'action',sortable:false, width:'15%'},
					],
					image : null,
					name : null,
					email : null,
					mr_count : null,
					mr_total : null,
					// list_mr : [],
					list_mr : [
					// {
					// 	id : 1,
					// 	no_mr : '12',
					// 	total : 12,
					// 	created_at : '12 October 2019',
					// 	approved_by_user_name : 'ricky',
					// 	status : 'false',
					// }
					]
				},

				dialog_edit_qty_goods : false,
				idx_edit_qty_goods : -1,
				input_quantity : 0,

				// periode : [],
				//===contoh data====
				periode : [
					{
						id : 1,
						name : 'periode1'
					},
					{
						id : 2,
						name : 'periode2'
					},
				],
				//periode_selected : {},
				//====contoh data====
				periode_selected : 
				{
					id : 1,
					name : 'periode1',
				},
				page : 1,
				//====contoh data====
				//page : 2,
				// goods : [],
				//===contoh data====
				goods : [
					{
						id : 1,
						name : 'Barang Satu',
						avg_price : '9000',
						thumbnail : 'storage/goods/default.png',
						qty : 2, //(dari user)
						subtotal : 18000 //(auto pake watcher)
					},
					{
						id : 2,
						name : 'Barang Dua',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 3,
						name : 'Barang Tiga',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 4,
						name : 'Barang Empat',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 5,
						name : 'Barang Lima',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 6,
						name : 'Barang Enam',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 7,
						name : 'Barang Tuju',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 8,
						name : 'Barang Delapan',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 9,
						name : 'Barang Sembilan',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 10,
						name : 'Barang Sepuluh',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
					{
						id : 11,
						name : 'Barang Sebelas',
						avg_price : '10000',
						thumbnail : 'storage/goods/default.png',
						qty : 0, //(dari user)
						subtotal : 0 //(auto pake watcher)
					},
				],
				total : 0,
				//===contoh data====
				// total : 18000

			}
		},
		methods : 
		{
			change_tab(val)
			{
				this.tab = val;
			},
			clear_input()
			{
				this.goods = [];
				this.page =  1;
				this.total = 0;
				this.periode = [];
				this.dialog_edit_qty_goods = false;
				this.idx_edit_qty_goods = -1;
				this.input_quantity = 0;
			},
			convert_to_idx_goods(idxrow,idxcolumn,page)
			{
				return (((idxcolumn + ((idxrow - 1) * this.static_data.column_row.length))  + (6* (page - 1))) - 1);
			},
			open_detail(id,ref,last_url)
			{
				this.$refs['cpDetailMr'].url = 'api/materialRequests/' + id + '/materialRequestDetails'
	            this.$refs['cpDetailMr'].open_dialog();
			},
			submit_mr()
			{
				const formData = new FormData();
				formData.append('token', localStorage.getItem('token'));
				var idxformdata = 0;
				for(var i = 0;i<this.goods.length;i++)
				{
					if(this.goods[i].qty > 0)
					{
						formData.append('material_request_details[' + idxformdata + '][goods_id]', this.goods[i].id);
						formData.append('material_request_details[' + idxformdata + '][qty]', this.goods[i].qty);
						formData.append('material_request_details[' + idxformdata + '][notes]', 'penting bos');
						formData.append('material_request_details[' + idxformdata + '][total]', this.goods[i].subtotal);
						idxformdata += 1;
					}
				}
				if(idxformdata > 0) //jika formdata tidak kosong
				{
					axios.post(
	                	'api/materialRequests',
	                	formData,
			                	{
			                'Accept': 'application/json',
			                'Content-type': 'application/json' //default
			            }).then((r) => {
	                	this.clear_input();
	                	//this.total = 0;
	                    this.$emit('back');
	                    swal("Good job!", "Data saved !", "success");
	                    
	                    
	                    
	                }).catch(function (error)
	                {
	                    
	                    if(error.response.status == 422)
	                    {
	                        swal('Request Failed', 'Check your internet connection !', 'error');
	                    }
	                    else
	                    {
	                        swal('Unkown Error', error.response.data , 'error');
	                    }
	                });
				}

			},
			submit_quantity()
			{
				this.change_qty_goods(this.idx_edit_qty_goods,null,this.input_quantity);
				this.idx_edit_qty_goods = -1;
				this.input_quantity = 0;
				this.change_dialog_qty_goods(false);
			},
			change_page(val)
			{
				if(!(this.page + val < 1 || this.page + val > Math.ceil(this.goods.length / 6)))
				{
					this.page = this.page + val;
				}
			},
			change_dialog_qty_goods(val, idx)
			{
				this.dialog_edit_qty_goods = val;
				if(val)
				{
					this.input_quantity = parseInt(JSON.parse(JSON.stringify(this.goods[idx].qty)));
					this.idx_edit_qty_goods = idx;
				}
				
			},
			
			change_qty_goods(idx,val,endval)
			{
				
				if(endval == null)
				{
					this.goods[idx].qty += val;	
				}
				else
				{
					this.goods[idx].qty = endval;
				}
				
				this.goods[idx].subtotal = this.goods[idx].qty * this.goods[idx].avg_price;
				var total = 0;
				for(var i = 0;i<this.goods.length;i++)
				{
					total += this.goods[i].subtotal;
				}
				this.total = total;
			},

			get_data_goods()
			{
				try{
                    var response = axios.get('api/materialRequests/create', {
                        params:{
                            token: localStorage.getItem('token')
                        }
                    },{
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json'
                        }
                    });

                    return response;
                    

                }
                catch (error)
                {
                    
                    result_data = false;
                }
			},
			get_data_index() //datatable
			{
				try{
                    var response = axios.get('api/materialRequests', {
                        params:{
                            token: localStorage.getItem('token')
                        }
                    },{
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json'
                        }
                    });

                    return response;
                    

                }
                catch (error)
                {
                    
                    result_data = false;
                }
			},
			get_data_profile()
			{
				try{
                    var response = axios.get('api/materialRequests/users/profile', {
                        params:{
                            token: localStorage.getItem('token')
                        }
                    },{
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json'
                        }
                    });

                    return response;
                    

                }
                catch (error)
                {
                    
                    result_data = false;
                }
			},
			async fill_master_data()
			{
				//halaman home
				let r_goods = await this.get_data_goods();
				r_goods = r_goods.data.items;
				this.goods = r_goods.goods;
				for(var i =0;i<this.goods.length;i++)
				{
					this.goods[i].qty = 0;
					this.goods[i].subtotal = 0;
					this.goods[i].thumbnail = 'storage/' + this.goods[i].thumbnail;
				}
				this.periode = r_goods.periodes;
				this.periode_selected = this.periode[this.periode.length - 1];

				//halaman profile
				var temp_user = JSON.parse(localStorage.getItem('user'));
				this.profile.name = temp_user.name;
				this.profile.email = temp_user.email;

				let r_profile = await this.get_data_profile();
				r_profile = r_profile.data.items;
				if(r_profile.user_info) 
				{
					this.profile.mr_count = r_profile.user_info.count_mr;
					this.profile.mr_total = r_profile.user_info.total_mr;
				}

				this.profile.list_mr = r_profile.material_requests;
				
			}
		},
		mounted()
		{
			if(JSON.parse(localStorage.getItem("user")).division_id != 1)
			{
				this.this_user_is_finance = false;
			}
			this.fill_master_data();
		}
	}
</script>