<template>
    <div class='bgwhite' style="height: 100%">
        <v-breadcrumbs divider=">" :items='breadcrumbs' class='breadcrumbs' v-if="open_state!='cpAddMaterialRequest'">
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
    

    
        <template v-if='open_state == "MaterialRequest"'>

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

            

            

            


            <!-- HEADER DATATABLE -->

           <cp-header
           :prop_icon='info_table.icon'
           :prop_title='info_table.title'
           :prop_search_data='search_data'
           :prop_filter_by_user_format='info_table.data.filter_by_user'
           :prop_filter_by_user_ref='filter_by_user_ref'
           :prop_button_on_index='info_table.button_on_index'
           :prop_button_for_checklist='info_table.button_for_checklist'

           v-on:submit_checklist='submit_checklist'
           v-on:cancel_checklist='cancel_checklist'
           v-on:button_index_clicked='button_index_clicked'
           v-on:search_change='search_data=$event'
           v-on:filter_by_user_change='fill_filter_by_user_value'
           v-on:add_clicked='opendialog_createedit(-1)'
           ref='cpHeader'
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
            :prop_custom_response_attribute='info_table.custom_response_attribute'

            v-on:response_filter_by_user_ref='fill_filter_by_user_ref'
            v-on:action_clicked='action_change'
            ref="cpDatatable"

            ></cp-datatable>

        </template>

        <template v-if="open_state=='cpPurchaseRequest'">
            <cp-purchase-request
            :prop_list_filter='list_state["cpPurchaseRequest"]'
            ></cp-purchase-request>
        </template>

        <template v-if="open_state=='cpAddMaterialRequest'">
            <cp-add-material-request
            :prop_list_filter='list_state["cpAddMaterialRequest"]'
            v-on:back='open_component("MaterialRequest")'
            ></cp-add-material-request>
        </template>

        

        <!-- ================================ -->
    </div>
    
</template>

<script>
import mxCrudBasic from '../mixin/mxCrudBasic';
import cpPurchaseRequest from './../components/child_crud/cpPurchaseRequest.vue'
import cpAddMaterialRequest from './../components/child_crud/cpAddMaterialRequest.vue'


export default {
    components : {
        cpPurchaseRequest,
        cpAddMaterialRequest,
    },
    data () {
        return {
            info_table:{},
            name_table:'material_requests',
            search_data: null,

            filter_by_user_value : '',
            filter_by_user_ref : [],

            open_state : 'MaterialRequest',
            list_state : 
            {
                'MaterialRequest' : {},
                'cpPurchaseRequest' : {},
                'cpAddMaterialRequest' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'Material Request',
                    disabled: false,
                    cp : 'MaterialRequest',
                    before : null,
                },
                //level 2
                {
                    text: 'List Purchase Request',
                    disabled: true,
                    cp: 'cpPurchaseRequest',
                    before : 'MaterialRequest'
                },
                {
                    text: 'Add Material Request',
                    disabled: true,
                    cp: 'cpAddMaterialRequest',
                    before : 'MaterialRequest'
                },
            ],
        }
    },
    methods: {
        submit_checklist()
        {
            console.log('oke');
            //post mark complete
            //ambil nilai dari cpdatatable yang di checklist
            //===
            this.$refs['cpHeader'].set_check_listing(false);
            this.$refs['cpDatatable'].convert_to_checklist(false);
        },
        cancel_checklist()
        {
            console.log('oke');
            //post mark complete
            //ambil nilai dari cpdatatable yang di checklist
            //===
            this.$refs['cpHeader'].set_check_listing(false);
            this.$refs['cpDatatable'].convert_to_checklist(false);
        },
        button_index_clicked(index)
        {
            if(index == 0)
            {
                this.open_component('cpAddMaterialRequest');
            }
            else if(index  == 1)
            {
                //start checklisting
                this.$refs['cpHeader'].set_check_listing(true);
                this.$refs['cpDatatable'].convert_to_checklist(true);
            }
        },
        action_change(id,idx_action, data)
        {
            this.selected_data = data;
            if(idx_action == 0)
            {
                 //this.open_component('cpPurchaseOrderDetails', 'purchase_order', id);
            }
            else if(idx_action == 1)
            {
                 
            }
            else if(idx_action == 2)
            {
                // this.$refs['cpIncomingPo'].id_po = id;
                // this.$refs['cpIncomingPo'].open_dialog();
            }
            else if(idx_action == 3)
            {
                //payment
                
            }
            else if(idx_action == 4)
            {
                //history
                
            }
        },
        
        

    },
    mounted(){      
        this.info_table = this.database[this.name_table];
        var this_user_role = JSON.parse(localStorage.getItem('user')).role_id;
        if(this_user_role != 1)
        {
            this.open_component('cpAddMaterialRequest');
        }
    },
    mixins:[
        mxCrudBasic

    ],
}
</script>

