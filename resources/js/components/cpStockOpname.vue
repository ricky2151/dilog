<template>
	<div>

		<!-- POPUP DETAIL STOCKOPNAME -->
        <v-dialog v-model="dialog_detailstockopname" width=900 persistent>
            <v-card >
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="save_detailstockOP()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Stock Opname</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px'>

                    
                    <v-data-table
                    disable-initial-sort
                    :headers="headers_popup_detailstockopname"
                    :items="data_detailstockopname"
                    class=""
                    >
                    <template v-slot:items="props">
                        <td>{{ props.index + 1 }}</td>
                        <td>{{ props.item.goods_name }}</td>
                        <td>{{ props.item.current_stock }}</td>
                        <td>
                        	<v-text-field :disabled='approve_edit == "Waiting"' v-model='props.item.new_stock' label="New Stock"></v-text-field>
                        </td>
                        <td>
                        	<v-text-field :disabled='approve_edit == "Waiting"' v-model='props.item.notes' label="Notes"></v-text-field>
                        </td>
                    </template>
                    </v-data-table>

                    <v-btn color='primary' v-show='approve_edit != "Waiting"' @click='save_detailstockOP'>Save</v-btn>
                </div>
            </v-card>
        </v-dialog>

        <!-- POPUP STOCKOPNAME -->
        <v-dialog v-model="dialog_stockopname" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_stockopname()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Dialog Stock Opname</v-toolbar-title>

                </v-toolbar>
                <div v-if='finish_loading'>
                	<div style='padding: 30px'>
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
					</div>

                    <div class='container' v-if='data_stockopname.length > 0' id='containerCardStockOpname' style='width: 600px;'>
                    	
                    		<div v-for='(data, index) in data_stockopname'  :key='index' class='cardStockOpname' >
                    			
								<v-card>
							        <v-img
							          src="https://vemafats.com/wp-content/uploads/2018/02/Screenshot_59.png"
							          height="200px"
							          @click='opendialog_detailstockopname(data.id, data.approve)'
							        >
							        </v-img>

							        <v-card-title primary-title>
							        	<div>
							            	<div class="headline">{{data.periode}}</div>
							            	<div class="headline">{{data.notes}}</div>
							            	<span class="grey--text">Status : {{data.approve }}</span>
										</div>
							        </v-card-title>

							        <v-card-actions style='width: 260px;'>
										<v-btn flat style='min-width: 0px !important;'><v-icon>print</v-icon></v-btn>
										<v-btn v-if='data.approve == "New"' @click='submit_to_waiting(data.id)' flat style='min-width: 0px !important;'><v-icon>check</v-icon></v-btn>
										<v-btn v-if='data.approve == "New"' @click='opendialog_detailstockopname(data.id,data.approve)' flat style='min-width: 0px !important;'><v-icon>edit</v-icon></v-btn>
										<v-btn v-if='data.approve == "New"' @click='delete_stockOP(data.id)'flat style='min-width: 0px !important;'><v-icon>delete</v-icon></v-btn>
									</v-card-actions>
								</v-card>
							</div>
						
						
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
			id_edit : -1,
			approve_edit : null,

			first_time_create_detailstockOP:false,
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
			data_detailstockopname:[],
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
		delete_stockOP(id)
		{
			axios.delete(
				'/api/warehouses/stockOpnames/' + id, 
				{
                params:{
                    token: localStorage.getItem('token')
                }
            },{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json'
                }
            })
            .then((r) => {
            	this.load_stockOP();
            })
			//api/warehouses/stockOpnames/2
		},
		opendialog_stockopname()
		{
			this.warehouse_id = this.prop_data_form.warehouse_id;
			this.load_stockOP();
			this.finish_loading = true;

			this.dialog_stockopname = true;

		},
		closedialog_stockopname()
		{
			this.dialog_stockopname = false;
		},
		async opendialog_detailstockopname(id,approve_id)
		{
			let r = await this.get_edit_detailstockOP(id);
			this.id_edit = id;
			this.approve_edit = approve_id;
			r = r.data.items.detail_stock_opnames;
			this.data_detailstockopname = [];
			for(var i =0;i<r.length;i++)
			{
				this.data_detailstockopname[i] = [];
				this.data_detailstockopname[i]['goods_id'] = r[i].goods_id;
				this.data_detailstockopname[i]['goods_name'] = r[i].goods_name;
				this.data_detailstockopname[i]['current_stock'] = r[i].current_stock;
				this.data_detailstockopname[i]['new_stock'] = r[i].new_stock;
				this.data_detailstockopname[i]['notes'] = r[i].notes;

				for(var j = 0;j<this.data_detailstockopname.length;j++)
            	{
            		this.data_detailstockopname[j].no = this.data_detailstockopname.length - j;
            	}

			}
			//aturannya kalau ngerequest create detailstockop, harus munculin popup detail stockOP
			this.first_time_create_detailstockOP = false;
			this.dialog_detailstockopname = true;


			
		},
		get_edit_detailstockOP(id)
		{
			try{
				var response = axios.get('/api/warehouses/stockOpnames/' + id + '/stockOpnamesDetails/edit', {
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
		async closedialog_detailstockopname()
		{
			//atturannya jika first_time_create_detailstockOP = true atau ini lagi pertama kali ngecreate stockOP,
			//maka saat di close, harus request update dengan DATA KOSONG, karena namanya close ya gak ada isi datanya
			if(this.first_time_create_detailstockOP == true)
			{
				for(var i = 0;i<this.data_detailstockopname.length;i++)
				{
					this.data_detailstockopname[i].new_stock = 0;
					this.data_detailstockopname[i].notes = '';
				}
				

				this.first_time_create_detailstockOP = false;
			}

			this.dialog_detailstockopname = false;
		},

		get_create_detailstockOP(id)
		{
			try{
				var response = axios.get('/api/warehouses/' + this.warehouse_id + '/stockOpnames/stockOpnamesDetails/create', {
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
		
		save_detailstockOP()
		{
			const formData = new FormData();

			var idxformdata = 0;
			for(var i = 0;i<this.data_detailstockopname.length;i++)
			{
				if(!this.data_detailstockopname[i].new_stock)
				{
					this.data_detailstockopname[i].new_stock = 0;
				}
				if(!this.data_detailstockopname[i].notes)
				{
					this.data_detailstockopname[i].notes = '-';
				}
				formData.append('detail_stock_opname[' + idxformdata + '][goods_id]', this.data_detailstockopname[i].goods_id);
				formData.append('detail_stock_opname[' + idxformdata + '][new_stock]', this.data_detailstockopname[i].new_stock);
				formData.append('detail_stock_opname[' + idxformdata + '][notes]', this.data_detailstockopname[i].notes);
				idxformdata+= 1;
			}
			
			var id_stockop = null;
			if(this.id_edit > - 1)
			{
				id_stockop = this.id_edit;
				formData.append("_method", "patch");
			}
			else
			{
				id_stockop = this.data_stockopname[0].id;
			}
			try{
				var response = axios.post(
					'/api/warehouses/stockOpnames/' + id_stockop + '/stockOpnamesDetails', 
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
	            this.closedialog_detailstockopname();
	            this.data_detailstockopname = [];
	            this.id_edit = -1;
	            return response;
			}
			catch (error)
        	{
        		console.log('error try catch : ' + error);
        	}

			
		},
		update_to_waiting(id)
		{
			try{
				var response = axios.post(
					'/api/warehouses/stockOpnames/' + id + '/setWaitings', 
					{},
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
	            
	            return response;
			}
			catch (error)
        	{
        		console.log('error try catch : ' + error);
        	}
		},
		save_stockOP()
		{
			const formData = new FormData();
			formData.append('periode', this.input.periode);
			formData.append('notes', this.input.notes);
			try{
				var response = axios.post(
					'/api/warehouses/' + this.warehouse_id + '/stockOpnames', 
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
				var response = axios.get('/api/warehouses/' + this.warehouse_id + '/stockOpnames', {
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

		async load_create_detailstockOP()
		{
			let r = await this.get_create_detailstockOP();
			r = r.data.items.detail_stock_opnames;
			this.data_detailstockopname = [];
			for(var i =0;i<r.length;i++)
			{
				this.data_detailstockopname[i] = [];
				this.data_detailstockopname[i]['goods_id'] = r[i].goods_id;
				this.data_detailstockopname[i]['goods_name'] = r[i].goods_name;
				this.data_detailstockopname[i]['current_stock'] = r[i].current_stock;
				this.data_detailstockopname[i]['new_stock'] = 0;
				this.data_detailstockopname[i]['notes'] = '-';

				for(var j = 0;j<this.data_detailstockopname.length;j++)
            	{
            		this.data_detailstockopname[j].no = this.data_detailstockopname.length - j;
            	}

			}

		},
		
		async add_stockOP()
		{
			let save_loading = await this.save_stockOP();
			this.first_time_create_detailstockOP = true;
			let create_loading = await this.load_create_detailstockOP();
			
			//aturannya kalau ngerequest create detailstockop, harus munculin popup detail stockOP
			this.first_time_create_detailstockOP = true;
			this.dialog_detailstockopname = true;
			
			this.load_stockOP();
			this.load_create_detailstockOP();


		},

		async submit_to_waiting(id)
		{
			let r = await this.update_to_waiting(id);
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
				this.data_stockopname[i]['id'] = r[i].id;
				this.data_stockopname[i]['periode'] = r[i].periode;
				this.data_stockopname[i]['notes'] = r[i].notes;



				var temp_approve = r[i].approve;
				if(temp_approve == '0')
				{
					this.data_stockopname[i]['approve'] = 'New';
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