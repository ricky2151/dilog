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
    

    
        <template v-if='open_state == "PODirect"'>

            <!-- LIST POPUP DETAIL -->
            <cp-detail 
             
            v-if='notNullObject(info_table.get_data_detail)'
            v-for='(data_detail,key,index) in info_table.get_data_detail'

            :prop_title='"Detail " + data_detail.title' 
            :prop_response_attribute='info_table.table_name'
            :prop_headers='data_detail.headers'
            :prop_columns='data_detail.single'
            :ref='"cpDetail"+ removeSpace(data_detail.title)'
            :key='key'

            ></cp-detail>
            <!----------------------->

            

            <!-- POPUP CREATE EDIT -->

            <cp-form 

            :prop_countStep='info_table.count_step' 
            :prop_editableEdit='info_table.editable_edit'
            :prop_editableAdd='info_table.editable_add'
            :prop_title='info_table.title'
            :prop_dataInfo='info_table.data'
            :prop_tableName='name_table'
            :prop_widthForm='info_table.widthForm'
            :prop_singularName='info_table.singular_name'
            
            :prop_input='generate_input(info_table.plural_name)'
            
            :prop_urlGetMasterData='info_table.request_master_data ? generate_url(info_table.plural_name, "create") : null'
            :prop_custom_formData='info_table.data.custom_formData'
            

            v-on:done='refresh_table()'
            ref="cpForm"

            ></cp-form>


            <!-- ================================ -->

            


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

        <template v-if="open_state=='cpPurchaseOrderDetails'">
            <cp-purchase-order-details
            :prop_list_filter='list_state["cpPurchaseOrderDetails"]'
            ></cp-purchase-order-details>
        </template>

        <template v-if="open_state=='cpPayment'">
            <cp-payment
            :prop_list_filter='list_state["cpPayment"]'
            >
            </cp-payment>
        </template>

        <cp-incoming-po ref='cpIncomingPo' v-on:done='done_submit_incoming'>
        </cp-incoming-po>

        

        <!-- ================================ -->
    </div>
    
</template>

<script>
import mxCrudBasic from '../mixin/mxCrudBasic';
import cpPurchaseOrderDetails from './../components/child_crud/cpPurchaseOrderDetails.vue'
import cpIncomingPo from './../components/child_crud/cpIncomingPo.vue'
import cpPayment from './cpPayment.vue'

export default {
    components : {
        cpPurchaseOrderDetails,
        cpIncomingPo,
        cpPayment,
    },
    data () {
        return {
            info_table:{},
            name_table:'purchase_orders',
            search_data: null,

            filter_by_user_value : '',
            filter_by_user_ref : [],

            open_state : 'PODirect',
            list_state : 
            {
                'PODirect' : {},
                'cpPurchaseOrderDetails' : {},
                'cpPayment' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'PO Direct',
                    disabled: false,
                    cp : 'PODirect',
                    before : null,
                },
                //level 2
                {
                    text: 'Detail PO',
                    disabled: true,
                    cp: 'cpPurchaseOrderDetails',
                    before : 'PODirect'
                },
                {
                    text: 'Payment',
                    disabled: true,
                    cp: 'cpPayment',
                    before : 'PODirect'
                },
            ],
        }
    },
    methods: {
        done_submit_incoming()
        {
            swal("Good job!", "Data Saved !", "success");
            this.refresh_table();
        },
        button_index_clicked(index)
        {
            if(index == 0)
            {
                this.opendialog_createedit(-1);
            }
        },
        approve_po(id)
        {
            const formData = new FormData();
            formData.append('_method', 'patch');
            formData.append('token', localStorage.getItem('token'));
            axios.post(
                'api/purchaseOrders/' + id + '/approve',
                formData,
                    {
                    'Accept': 'application/json',
                    'Content-type': 'application/json' //default
                    }
                ).then((r) => {
                    console.log(r);
                    if(r.data.error)
                    {
                        if(r.data.message['purchase order'][0] == "Can't approve PO because status is not submit")
                        {
                            swal("Failed to Approve", "Can't approve PO because status is not submit", "error");
                            //Can't approve PO because status is not submit
                        }
                    }
                    else
                    {
                        swal("Good job!", "Data Approved !", "success");        
                    }
                
                this.refresh_table();
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
        },
        action_change(id,idx_action, data)
        {
            this.selected_data = data;
            if(idx_action == 0)
            {
                 this.open_component('cpPurchaseOrderDetails', 'purchase_order', id);
            }
            else if(idx_action == 1)
            {
                 this.approve_po(id);
            }
            else if(idx_action == 2)
            {
                this.$refs['cpIncomingPo'].id_po = id;
                this.$refs['cpIncomingPo'].open_dialog();
            }
            else if(idx_action == 3)
            {
                if(data.status == 'Approve' || data.status == 'Finish')
                {
                    this.open_component('cpPayment', 'purchase_order', id);
                }
                else
                {
                    swal("Cannot Open Payment", "can't open payment because status is not approve/complete", "error");
                }
                
            }
            else if(idx_action == 4)
            {
                //history
                
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

