<template>
    <div class='bgwhite' style="height: 100%">
        <v-breadcrumbs divider=">" :items='computed_breadcrumbs' class='breadcrumbs' v-if="open_state!='cpAddMaterialRequest'">
            </v-breadcrumbs-item>
            <v-breadcrumbs-item
                slot="item"
                slot-scope="{ item }"
                exact
                :disabled='item.disabled'
                @click="item.disabled ? '' : open_component(item.cp)"
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
            :prop_response_attribute='data_detail.table_name'
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
            :prop_conditional_checklist='info_table.conditional_checklist'

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
            v-on:back='add_mr_done'
            ref='cpAddMaterialRequest'
            ></cp-add-material-request>
        </template>

         <template v-if="open_state=='cpPurchaseRequestAdd'">
            <cp-purchase-request-add
            :prop_list_filter='list_state["cpPurchaseRequestAdd"]'
            :prop_data='data_edit'
            ref='cpPurchaseRequestAdd'
            v-on:cancel='cancel_po_edit'
            v-on:done='done_po_edit'
            ></cp-purchase-request-add>
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
import mxCrudBasic from '../mixin/mxCrudBasic';
import cpPurchaseRequest from './../components/child_crud/cpPurchaseRequest.vue'
import cpAddMaterialRequest from './../components/child_crud/cpAddMaterialRequest.vue'
import cpPurchaseRequestAdd from './../components/child_crud/cpPurchaseRequestAdd.vue'
import cpMakePo from './../components/child_crud/cpMakePo.vue'


export default {
    components : {
        cpPurchaseRequest,
        cpAddMaterialRequest,
        cpPurchaseRequestAdd,
        cpMakePo
    },
    data () {
        return {
            info_table:{},
            data_edit : [],
            name_table:'material_requests',
            search_data: null,

            filter_by_user_value : '0',
            filter_by_user_ref : [],

            open_state : 'MaterialRequest',
            list_state : 
            {
                'MaterialRequest' : {},
                'cpPurchaseRequest' : {},
                'cpAddMaterialRequest' : {},
                'cpPurchaseRequestAdd' : {},
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
                {
                    text: 'Detail Purchase Request',
                    disabled: true,
                    cp: 'cpPurchaseRequestAdd',
                    before : 'MaterialRequest'
                },
                {
                    text: 'Make PO',
                    disabled: true,
                    cp: 'cpMakePo',
                    before : 'MaterialRequest'
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
            this.open_component('cpMakePo', null, null, '[use_same_text_note_level]');

            this.$nextTick(() => {
                this.$refs['cpMakePo'].id = id;
                this.$refs['cpMakePo'].fill_data(r.data.items);
            })


            
        },
        cancel_po_edit()
        {
            this.$router.replace('/purchase-request');
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
       


        add_mr_done()
        {

            var this_user_division = JSON.parse(localStorage.getItem('user')).division_id;

            if(this_user_division != 1)
            {
                this.$refs['cpAddMaterialRequest'].fill_master_data();
            }
            else
            {
                this.open_component('MaterialRequest');
            }
        },
        submit_checklist()
        {
            
            var filtered = this.$refs['cpDatatable'].get_checklisted();
            
            const formData = new FormData();
            var data_null = false;
            if(filtered.length == 0)
            {
                data_null = true;
            }
            
            
            if(data_null == false)
            {
                for(var i = 0;i<filtered.length;i++)
                {
                    formData.append('material_requests[' + i + '][id]', filtered[i].id);
                }

                axios.post(
                    '/api/purchaseRequests',
                        formData
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
                    this.open_component('cpPurchaseRequestAdd', null, null, r.data.items.purchase_request.code);
                    this.$refs['cpDatatable'].clear_checklisted();
                    this.$refs['cpHeader'].set_check_listing(false);
                    this.$refs['cpDatatable'].convert_to_checklist(false);
                    this.$nextTick(() => {
                        this.$refs['cpPurchaseRequestAdd'].fill_data(temp, r.data.items.purchase_request.id);
                        
                        swal("Good job!", "Recap Successfully !", "success");
                    })
                });

            }
            else
            {
                swal('Failed', "Please Select MR", "error");
            }
            

            
        },
        cancel_checklist()
        {
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
            else if(index == 2)
            {
                //list pr
                this.$router.replace('/purchase-request');
            }
            else if(index == 3)
            {
                //create po
            }
        },
        action_change(id,idx_action, data)
        {
            this.selected_data = data;
            if(idx_action == 0)
            {
                 //detail
                 this.opendialog_detail(id, 'cpDetailMaterialRequest', 'material_request_details');
            }
            else if(idx_action == 1)
            {
                 //po
            }
            
        },
        
        

    },
    mounted(){      
        this.info_table = this.database[this.name_table];
        var this_user_division = JSON.parse(localStorage.getItem('user')).division_id;
        if(this_user_division != 1)
        {
            this.open_component('cpAddMaterialRequest');
        }
        this.$refs['cpHeader'].selected_filter = 0;
    },
    mixins:[
        mxCrudBasic

    ],
    watch : 
    {
        filter_by_user_ref : function(val)
        {
            this.$refs['cpHeader'].selected_filter = 0;
        }
    }
}
</script>

