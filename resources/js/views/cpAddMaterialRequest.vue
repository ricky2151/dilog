<template>
	
	<div v-show='tab=="Home"'>
		<v-dialog v-model="dialog" width=750>
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


		<v-layout row>
			Home
			Profile
		</v-layout>

		<v-layout row>
			<v-flex xs8>
				Periode Selected
				<v-select
					label='Periode'
					v-model='periode_selected'
					:items='periode'
					:item-text='name'
					return-object
					class="pa-2"
					>
				</v-select>
			</v-flex>
			<v-flex xs4>
				<v-btn>Request Custom Goods</v-btn>
			</v-flex>
		</v-layout>

		<v-layout row>
			<v-flex xs8>
				<v-layout v-for='idxrow in static_data.index_row' row>
					<v-flex xs4 v-for='idxcolumn in static_data.column_row' :set='idxgoods=(page - (idxcolumn * idxrow)) * 6'>
						<div>
							<img :src='goods[idxgoods].thumbnail'/>
							<center><h4>{{goods[idxgoods].name}}</h4></center>
							<center><h4>{{goods[idxgoods].avg_price}}</h4></center>
							<center>
								<v-btn v-if='goods[idxgoods].qty == 0'>Add</v-btn>
								<div v-if='goods[idxgoods].qty > 0'>
									<div @click='change_qty_goods(idxgoods,-1)'>-</div>
									<div>goods[idxgoods].qty</div>
									<div @click='change_qty_goods(idxgoods,1)'>+</div>
								</div>
							</center>
						</div>
						((page - (idxcolumn * idxrow)) * 6)
					</v-flex>
				</v-layout>
			</v-flex>
			<v-flex xs4>
				<div v-for='(objgoods,index) in goods'>
					<div>{{objgoods.name}}</div>
					<br>
					<div>
						{{objgoods.qty}} x {{objgoods.avg_price}} = Rp. {{objgoods.subtotal}}
					</div>
					<br>
					<div>
						<v-btn @click='change_dialog_qty_goods(1,index)'>Edit</v-btn>
						<v-btn @click='change_qty_goods(index,null,0)'>Delete</v-btn>
					</div>
					<br>
					<div>
						Total : Rp. {{total}}
					</div>
					<br>
					<div>
						<v-btn @click='submit_mr'>Submit</v-btn>
					</div>
				</div>
			</v-flex>
		</v-layout>

	</div>

	<div v-show='tab=="Profile"'>
		<cp-detail 
        v-for='(data_detail,key,index) in info_table.get_data_detail'

        prop_title='Detail Material Request' 
        prop_response_attribute='detail_material_request'
        :prop_headers='profile.header_detail_mr'
        :prop_columns='profile.single_detail_mr'
        ref="cpDetailMr"
        ></cp-detail>
		<v-layout row>
			<v-flex xs2>
				<img :src='profile.image'/>
			</v-flex>
			<v-flex xs10>
				<div>Nama : {{profile.name}}</div>
				<div>Email : {{profile.email}}</div>
				<div>MR Count : {{profile.mr_count}}</div>
				<div>MR Total : {{profile.mr_total}}</div>
			</v-flex>
		</v-layout>

		<v-layout row>
			<v-data-table
            disable-initial-sort
            :headers="profile.header_profile"
            :items="profile.list_mr"
            >
            <template v-slot:items="props">
                <td>{{ props.item.index + 1 }}</td>
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
		</v-layout>

	</div>
</template>
<script>
	export default {
		data () {
			return {
				static_data : 
				{
					index_row : [1,2],
					column_row : [1,2,3],
				},

				tab : "Home",

				profile : 
				{
					header_detail_mr : [
						{ text : 'No', value:'no'},
						{ text : 'Name', value:'name'},
						{ text : 'Division', value:'division_name'},
						{ text : 'Created At', value:'created_at'},
						{ text : 'Status', value:'status'},
					],
					single_detail_mr : 
					{
						'id' : {show : false},
						'name' : {show : true},
						'division_name' : {show : true},
						'created_at' : {show : true},
						'status' : {show : true},
					},

					header_profile : [
						{ text : 'No', value:'no'},
						{ text : 'Code', value:'code'},
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
					list_mr : [],
					//list_mr : [
					// {
					// 	id : 1,
					// 	no_mr : '12',
					// 	total : 12,
					// 	created_at : '12 October 2019',
					// 	approved_by : 'ricky',
					// 	status : 'false',
					// }
					//]
				}

				dialog_edit_qty_goods : false,
				idx_edit_qty_goods : -1,
				input_quantity : 0,

				periode : [],
				//===contoh data====
				// periode : [
				// 	{
				// 		id : 1,
				// 		name : 'periode1'
				// 	},
				//	{
				// 		id : 2,
				// 		name : 'periode2'
				// 	},
				// ]
				periode_selected : {},
				//====contoh data====
				//periode_selected : 
				// {
				// 	id : 1,
				// 	name : 'periode1',
				// }
				page : 1,
				//====contoh data====
				//page : 2,
				goods : [],
				//===contoh data====
				// goods : [
				// 	{
				// 		id : 1,
				// 		name : 'barang1',
				// 		avg_price : '9000',
				//		thumbnail : 'goods/default.png',
				//		qty : 2, //(dari user)
				//		subtotal : 18000 //(auto pake watcher)
				// 	},
				// 	{
				// 		id : 2,
				// 		name : 'barang2',
				// 		avg_price : '10000',
				//		thumbnail : 'goods/default.png',
				//		qty : 0, //(dari user)
				//		subtotal : 0 //(auto pake watcher)
				// 	},
				// ]
				total : 0,
				//===contoh data====
				// total : 18000

			}
		},
		methods : 
		{
			open_detail(id,ref,last_url)
			{
				this.$refs['cpDetailMr'].url = ''// tanya tomas
	            this.$refs['cpDetailMr'][0].open_dialog();
			}
			submit_mr()
			{

			},
			submit_quantity()
			{
				this.goods[this.idx_edit_qty_goods].qty = this.input_quantity;
				this.idx_edit_qty_goods = -1;
				this.input_quantity = 0;
				this.change_dialog_qty_goods(false);
			},
			change_dialog_qty_goods(val, idx)
			{
				this.dialog_edit_qty_goods = val;
				if(val && idx)
				{
					this.idx_edit_qty_goods = idx;
				}
			},
			
			change_qty_goods(idx,val,endval)
			{
				if(!endval)
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
			}
		}
	}
</script>