<template>
	<div>

		<!-- POPUP DETAIL STOCKOPNAME -->
        <v-dialog v-model="dialog_detailstockopname" width=900>
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
        </v-dialog>


		<v-card @click='opendialog_detailstockopname()' width=300>
	        <v-img
	          src=""
	          height="200px"
	        >
	        </v-img>

	        <v-card-title primary-title>
	        	<div>
	            	<div class="headline">Periode : {{this.input_stockopname.periode}}</div>
	            	<span class="grey--text">Status : {{this.input_stockopname.status}}</span>
				</div>
	        </v-card-title>

	        <v-card-actions>
				<v-btn flat><v-icon>print</v-icon></v-btn>
				<v-btn flat><v-icon>check</v-icon></v-btn>
				<v-btn flat><v-icon>edit</v-icon></v-btn>
				<v-btn flat><v-icon>delete</v-icon></v-btn>
			</v-card-actions>
		</v-card>
	</div>
</template>

<script>
import axios from 'axios'
export default {
	name: 'cpStockOpname',
	props:['prop_id_stockopname'],
	
	data(){
		return{
			dialog_detailstockopname:null,
			headers_popup_detailstockopname: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Current Stock', value:'current_stock' },
                { text: 'New Stock',  value:'new_stock'},
                { text: 'Notes', value: 'notes'},

            ],
			
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
		id_stockopname: function(){
			return this.prop_id_stockopname;
		},
	},
	methods:{
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
			axios.post('api/warehouses/stockOpnames/' + this.input_stockopname.id + '/setWaitings',
			{
				params : {
                    token: localStorage.getItem('token')
                }
			}).then((r) => {
				console.log('berhasil update status jadi waiting');
			});
		},
		get_data()
		{
			
		},

	},
	mounted(){
		this.get_data();
	},
}

</script>