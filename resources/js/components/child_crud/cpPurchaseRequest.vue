<template>
    <div class='bgwhite'>
        <v-breadcrumbs divider=">" :items='breadcrumbs' class='breadcrumbs'>
            <v-breadcrumbs-item
                slot="item"
                slot-scope="{ item }"
                exact
                :class="{breadcrumbs_hidden : item.disabled}"
                @click="open_component(item.cp)"
                >

                {{ item.text }}
            </v-breadcrumbs-item>
        </v-breadcrumbs>
    

    
        <template v-if='open_state == "cpPurchaseRequest"'>

        	<!-- Add PR -->
        	<v-dialog v-model="dialog_add_pr" width=750>
	            <v-card>
	                <v-toolbar dark color="menu">
	                    <v-btn icon dark v-on:click="close_dialog()">
	                        <v-icon>close</v-icon>
	                    </v-btn>
	                    <v-toolbar-title>Add Purchase Order</v-toolbar-title>

	                </v-toolbar>
	                <div style='padding:30px'>
	                	<v-layout row>
		                	<v-flex xs10>
		                		<h2>Select Material Request :</h2>
		                	</v-flex>
		                	<v-flex xs2>
		                    	<v-btn color='blue'  dark @click='submit_recap'>Recap</v-btn>
		                	</v-flex>
	                	</v-layout>
	                   
	                    <v-data-table
	                    disable-initial-sort
	                    :headers="header_add_pr"
	                    :items="data_mr"
	                    class=""
	                    >
	                    <template v-slot:items="props">
	                    	<v-checkbox
							class='datatable_checklist'
				            v-model="props.item['checked']"
				            color="primary"
					         ></v-checkbox>
	                        <td>{{ props.index + 1 }}</td>
	                        <td>{{ props.item.no_mr }}</td>
	                        <td>{{ props.item.division }}</td>
	                        <td>{{ props.item.created_at }}</td>
	                    </template>
	                    </v-data-table>
	                </div>
	            </v-card>
	        </v-dialog>

            <!-- HEADER DATATABLE -->

           <cp-header
           :prop_icon='info_table.icon'
           :prop_title='info_table.title'
           :prop_search_data='search_data'
           :prop_filter_by_user_format='info_table.data.filter_by_user'
           :prop_filter_by_user_ref='filter_by_user_ref'
           :prop_button_on_index='info_table.button_on_index'

           v-on:button_index_clicked='button_index_clicked'
           v-on:search_change='search_data=$event'
           v-on:filter_by_user_change='fill_filter_by_user_value'
           v-on:add_clicked='opendialog_createedit(-1)'
           >
           </cp-header>

           <!-- ================================ -->


            
            <!-- DATATABLE -->

            <cp-datatable 
            v-if='info_table.data'

            :prop_header='info_table.data.headers'
            :prop_search_data='search_data'
            :prop_infoDatatable='info_table.data.datatable'
            :prop_action_items='info_table.actions'
            :prop_plural_name='info_table.plural_name'
            :prop_url_index='generate_url(info_table.table_name, "index")'
            :prop_filter='info_table.data.filter'
            :prop_format_filter_by_user='info_table.data.filter_by_user'
            :prop_filter_by_user_value='filter_by_user_value'

            v-on:response_filter_by_user_ref='fill_filter_by_user_ref'
            v-on:action_clicked='action_change'
            ref="cpDatatable"

            ></cp-datatable>

        </template>

        <template v-if="open_state=='cpPurchaseRequestEdit'">
            <cp-purchase-request-edit
            :prop_list_filter='list_state["cpPurchaseRequestEdit"]'
            :prop_data='data_edit'
            ref='cpPurchaseRequestEdit'
            v-on:cancel='cancel_po_edit'
            v-on:done='done_po_edit'
            ></cp-purchase-request-edit>
        </template>

        <template v-if="open_state=='cpMakePo'">
            <cp-make-po
            :prop_list_filter='list_state["cpMakePo"]'
            ref='cpMakePo'
            v-on:cancel='cancel_make_po'
            ></cp-make-po>
        </template>
        

        <!-- ================================ -->
    </div>
    
</template>

<script>
import mxCrudBasic from '../../mixin/mxCrudBasic';
import cpPurchaseRequestEdit from './cpPurchaseRequestEdit.vue'
import cpMakePo from './cpMakePo.vue'

export default {
    components : {
        cpPurchaseRequestEdit,
        cpMakePo
    },
    data () {
        return {
            info_table:{},
            name_table:'purchase_requests',
            search_data: null,

            dialog_add_pr : false,
            header_add_pr : [
            	{ text: '', value:'checklist'},
            	{ text: 'No', value:'no'},
            	{ text: 'No MR', value:'no_mr'},
            	{ text: 'Division', value:'division'},
            	{ text: 'Created At', value:'created_at'},
            ],
            data_mr : [],
            data_edit : [],

            filter_by_user_value : '',
            filter_by_user_ref : [],

            open_state : 'cpPurchaseRequest',
            list_state : 
            {
                'cpPurchaseRequest' : {},
                'cpPurchaseRequestEdit' : {},
                'cpMakePo' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'Purchase Request',
                    disabled: false,
                    cp : 'cpPurchaseRequest',
                    before : null,
                },
                //level 2
                {
                    text: 'Edit Purchase Request',
                    disabled: true,
                    cp: 'cpPurchaseRequestEdit',
                    before : 'cpPurchaseRequest'
                },
                {
                    text: 'Make PO',
                    disabled: true,
                    cp: 'cpMakePo',
                    before : 'cpPurchaseRequest'
                },
            ],
        }
    },
    methods: {
    	cancel_make_po()
    	{
    		this.open_component('cpPurchaseRequest');
    	},
    	done_po_edit(r,id)
    	{
    		this.open_component('cpMakePo');

    		this.$nextTick(() => {
            	this.$refs['cpMakePo'].id = id;
                this.$refs['cpMakePo'].fill_data(r.data.items);
		  	})


    		
    	},
    	cancel_po_edit()
    	{
    		this.open_component('cpPurchaseRequest');
    	},
    	prepare_data_submit_recap()
    	{
    		const formData = new FormData();
    		var idxformdata = 0;
    		for(var i =0;i<this.data_mr.length;i++)
    		{
    			if(this.data_mr[i].checked)
    			{
    				formData.append('material_requests[' + idxformdata + '][id]', this.data_mr[i].id);
    				idxformdata+= 1;
    			}
    		}
    		return formData;

    	},
    	submit_recap()
    	{
    		//api
    		 axios.post(
            	'api/purchaseRequests',
            		this.prepare_data_submit_recap()
            	 ,{
	                headers: {
	                    'Accept': 'application/json',
	                    'Content-type': 'application/json'
	                },
	                params : 
	                {
	                	'token' : localStorage.getItem('token')
	                }
	            }).then((r)=> {
	            var temp = r.data.items.rekaps;
	            for(var i = 0;i<temp.length;i++)
	            {
	            	temp[i].no = i + 1;
	            }
                this.open_component('cpPurchaseRequestEdit');
                this.$nextTick(() => {
	                this.$refs['cpPurchaseRequestEdit'].fill_data(temp);
	                swal("Good job!", "Recap Successfully !", "success");
			  	})
            });
    	},
    	close_dialog_add_pr()
        {
            this.dialog_add_pr = false;
        },
        async open_dialog_add_pr()
        {
            
            
            this.dialog_add_pr = true;
            let r = await this.get_data_mr();
            //difilter berdasarkan periode aktif
            var temp_r = JSON.parse(JSON.stringify(r.data.items.material_requests));
            var result_r = [];
            for(var i = 0;i<temp_r.length;i++)
            {
            	if(temp_r[i].periode_id == r.data.items.periode_active.id)
            	{
            		result_r.push(temp_r[i]);
            	}
            }
            this.data_mr = JSON.parse(JSON.stringify(result_r));

        },
         //for data
        get_data_mr()
        {
        	try{
	            var response = axios.get('api/purchaseRequests/create', {
	                params:{
	                    token: localStorage.getItem('token')
	                }
	            },{
	                headers: {
	                    'Accept': 'application/json',
	                    'Content-type': 'application/json'
	                }
	            });

	            return response
            	

        	}
        	catch (error)
        	{
        		console.log('error try catch : ' + error);
        		result_data = false;
        	}
        	

        },

        button_index_clicked(index)
        {
            if(index == 0)
            {
                this.open_dialog_add_pr(-1);
            }
        },
        action_change(id,idx_action, data)
        {
            this.selected_data = data;
            if(idx_action == 0)
            {
            	//api
	    		 axios.get(
	            	'api/purchaseRequests/' + id + '/rekaps',
            		{
            			params : 
		                {
		                	'token' : localStorage.getItem('token')
		                }
		            },
		            {
		                headers: {
		                    'Accept': 'application/json',
		                    'Content-type': 'application/json'
		                },
		                
		            }).then((r)=> {
		            var temp = r.data.items.rekaps;
		            for(var i = 0;i<temp.length;i++)
		            {
		            	temp[i].no = i + 1;
		            }
	                this.open_component('cpPurchaseRequestEdit');
	                this.$nextTick(() => {
		                this.$refs['cpPurchaseRequestEdit'].fill_data(temp);
		                
				  	})
	            });
                 //this.open_component('cpPurchaseRequestEdit', 'purchase_request', id);
            }
            else if(idx_action == 1)
            {
            	//api
	    		 axios.get(
	            	'api/purchaseRequests/' + id + '/purchaseRequestDetailsToPurchaseOrder',
            		{
            			params : 
		                {
		                	'token' : localStorage.getItem('token')
		                }
		            },
		            {
		                headers: {
		                    'Accept': 'application/json',
		                    'Content-type': 'application/json'
		                },
		                
		            }).then((r)=> {
		            var temp = r.data.items;
	                this.open_component('cpMakePo');
	                this.$nextTick(() => {
	                	this.$refs['cpMakePo'].id = id;
		                this.$refs['cpMakePo'].fill_data(temp);
				  	})
	            });
            }
            else if(idx_action == 2)
            {
                this.delete_data(id_datatable);
            }
            
        },
        
        

    },
    mounted(){      
        this.info_table = this.database[this.name_table];
    },
    mixins:[
        mxCrudBasic
    ],
}
</script>

