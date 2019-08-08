<template>
	<div>

		<!-- POPUP DETAIL STOCKOPNAME -->
        <!-- <v-dialog v-model="dialog_detailstockopname" width=900>
            <v-card >
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_detailstockopname()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Stock Opname</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px'>

                    
                    <v-data-table
                    disable-initial-sort
                    :headers="headers_popup_detailstockopname"
                    :items="input_stockopname.detail_stockopname"
                    class=""
                    >
                    <template v-slot:items="props">
                        <td>{{ props.index + 1 }}</td>
                        <td>{{ props.item.goods_name }}</td>
                        <td>{{ props.item.stock }}</td>
                        <td>
                        	<v-text-field :rules='this.$list_validation.numeric' v-model='input_stockopname.detail_stockopname[props.index].new_stock' label="New Stock"></v-text-field>
                        </td>
                        <td>
                        	<v-text-field :rules='this.$list_validation.numeric' v-model='input_stockopname.detail_stockopname[props.index].notes' label="Notes"></v-text-field>
                        </td>
                    </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-dialog> -->

        <!-- POPUP STOCKOPNAME -->
        <v-dialog v-model="dialog_stockopname" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_stockopname()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Dialog Stock Opname</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px' v-if='finish_loading'>

                	<v-menu
                      ref="menu_date"
                      v-model="menu_date"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="input.periode"
                          label="Date"
                          hint="MM/DD/YYYY format"
                          persistent-hint
                          prepend-icon="event"
                          @blur="date = input.periode"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="input.periode" no-title @input="menu_date = false"></v-date-picker>
                    </v-menu>

                    
                    <v-form ref='form'>
                    	<v-text-field :rules='this.$list_validation.max_req' disabled :value='prop_data_form.user' label="User" counter=191></v-text-field>

                    	<v-text-field :rules='this.$list_validation.max_req' disabled :value='prop_data_form.warehouse' label="Warehouse" counter=191></v-text-field>

                    	<v-text-field :rules='this.$list_validation.max_req' v-model='input.notes' label="Notes" counter=191></v-text-field>
                	</v-form>

                    <v-btn color='primary' @click='add_stockOP'>Add</v-btn>

                    <div class='container' vif='data_stockopname.length > 0'>
                    	
						<v-card  v-for='(data, index) in data_stockopname' @click='opendialog_detailstockopname()' width=300  :key='index'>
					        <v-img
					          src=""
					          height="200px"
					        >
					        </v-img>

					        <v-card-title primary-title>
					        	<div>
					            	<div class="headline">{{data.periode}}</div>
					            	<div class="headline">{{data.notes}}</div>
					            	<span class="grey--text">Status : {{data.approve }}</span>
								</div>
					        </v-card-title>

					        <v-card-actions>
								<v-btn flat style='min-width: 0px !important;'><v-icon>print</v-icon></v-btn>
								<v-btn flat style='min-width: 0px !important;'><v-icon>check</v-icon></v-btn>
								<v-btn flat style='min-width: 0px !important;'><v-icon>edit</v-icon></v-btn>
								<v-btn flat style='min-width: 0px !important;'><v-icon>delete</v-icon></v-btn>
							</v-card-actions>
						</v-card>
						
                	</div>
            	</div>
        	</v-card>
    	</v-dialog>
	</div>
</template>

<script>
import axios from 'axios'
export default {
	props:['prop_data_form'],
	
	data(){
		return{
			menu_date : null,

			finish_loading : false,

			dialog_stockopname:null,
			dialog_detailstockopname:null,
			warehouse_id : null,
			headers_popup_detailstockopname: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Current Stock', value:'current_stock' },
                { text: 'New Stock',  value:'new_stock'},
                { text: 'Notes', value: 'notes'},

            ],
			input:[],
			data_stockopname:[],
			input_stockopname:
			{
				id:null,
				periode : null,
				status : null,
				user : 
				{
					id : null,
					name : null,
				},
				warehouse : 
				{
					id : null,
					name : null,
				},
				notes : null,

				detail_stockopname:[],

			}
		}
	},
	computed: {
	},
	methods:{
		opendialog_stockopname()
		{
			this.warehouse_id = this.prop_data_form.warehouse_id;
			this.load_stockOP();
			this.finish_loading = true;

			this.dialog_stockopname = true;

		},
		closedialog_stopopname()
		{
			this.dialog_stockopname = false;
		},
		opendialog_detailstockopname()
		{
			this.dialog_detailstockopname = true;
		},
		closedialog_detailstockopname()
		{
			this.dialog_detailstockopname = false;
		},
		set_status(status)
		{
			// axios.post('api/warehouses/stockOpnames/' + this.input_stockopname.id + '/setWaitings',
			// {
			// 	params : {
   //                  token: localStorage.getItem('token')
   //              }
			// }).then((r) => {
			// 	console.log('berhasil update status jadi waiting');
			// });
		},
		get_data()
		{
			
		},


		save_stockOP()
		{
			const formData = new FormData();
			formData.append('periode', this.input.periode);
			formData.append('notes', this.input.notes);
			try{
				var response = axios.post(
					'api/warehouses/' + this.warehouse_id + '/stockOpnames', 
					formData,
					{
	                params:{
	                    token: localStorage.getItem('token')
	                }
	            },{
	                headers: {
	                    'Accept': 'application/json',
	                    'Content-type': 'application/json'
	                }
	            });
	            this.input.periode = null;
	            this.input.notes = null;
	            this.$refs['form'].resetValidation();
	            return response;
			}
			catch (error)
        	{
        		console.log('error try catch : ' + error);
        	}
		},


		get_stockOP()
		{
			try{
				var response = axios.get('api/warehouses/' + this.warehouse_id + '/stockOpnames', {
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
        		console.log('error try catch : ' + error);
        	}
		},

		async add_stockOP()
		{
			let r = await this.save_stockOP();
			this.load_stockOP();

		},
		async load_stockOP()
		{
			let r = await this.get_stockOP();
			r = r.data.items.stock_opnames;
			this.data_stockopname = [];
			for(var i =0;i<r.length;i++)
			{
				this.data_stockopname[i] = [];
				this.data_stockopname[i]['periode'] = r[i].periode;
				this.data_stockopname[i]['notes'] = r[i].notes;



				var temp_approve = r[i].approve;
				if(temp_approve == '0')
				{
					this.data_stockopname[i]['approve'] = 'Declined';
				}
				else if(temp_approve == '1')
				{
					this.data_stockopname[i]['approve'] = 'Waiting';
				}
				else if(temp_approve == '2')
				{
					this.data_stockopname[i]['approve'] = 'Approved';
				}
				
			}
			console.log('cek');
			console.log(this.data_stockopname[0].periode);
		},


		//dummy
		dummy_store_details()
		{
			return {
				error : false
			};
		},

	},
	mounted(){
		
	},
}

</script>