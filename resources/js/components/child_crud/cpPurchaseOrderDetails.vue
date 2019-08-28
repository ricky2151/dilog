<template>
    <div class='bgwhite'>

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

        
        

        

        <!-- ================================ -->



        <!-- HEADER DATATABLE -->
       <cp-header
       :prop_icon='info_table.icon'
       :prop_title='info_table.title'
       :prop_search_data='search_data'
       :prop_button_on_index='info_table.button_on_index'
       :prop_information='additional_data ? additional_data : ""'
       :prop_format_information='info_table.format_additional_data ? info_table.format_additional_data : ""'

       v-on:button_index_clicked='button_index_clicked'
       v-on:search_change='search_data=$event'
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
        :prop_url_index='prop_list_filter? generate_url("purchase_orders", "detail",prop_list_filter["id_selected"], info_table.plural_name) :  generate_url(info_table.table_name, "index")'
        :prop_filter='prop_list_filter'
        prop_get_additional_data='true'

        v-on:show_additional_data='fill_additional_data'
        v-on:action_clicked='action_change'
        ref="cpDatatable"

        ></cp-datatable>

        <cp-add-edit-detail-po ref='cpAddEditDetailPo' :prop_purchase_order_id='additional_data ? additional_data.id : ""' v-on:done='refresh_table()'></cp-add-edit-detail-po>
        <!-- ================================ -->
    </div>
</template>

<script>
import mxCrudBasic from '../../mixin/mxCrudBasic';
import cpAddEditDetailPo from './cpAddEditDetailPo.vue'

export default {
    components : {
        cpAddEditDetailPo
    },
    props: ['prop_list_filter'],
    data () {
        return {
            info_table:{},
            name_table:'purchase_order_details',
            search_data: null,
            additional_data:null,
        }
    },
    methods: {
        submit_data()
        {
            const formData = new FormData();
            formData.append('_method', 'patch');
            formData.append('token', localStorage.getItem('token'));
            axios.post(
                'api/purchaseOrders/' + this.additional_data.id + '/submit',
                formData,
                    {
                    'Accept': 'application/json',
                    'Content-type': 'application/json' //default
                    }
                ).then((r) => {
                swal("Good job!", "Data Submited !", "success");
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
        button_index_clicked(index)
        {
            if(this.additional_data.status != "1")
            {

            }
            else
            {
                if(index == 0)
                {
                    this.$refs['cpAddEditDetailPo'].open_dialog(-1);
                    //this.opendialog_createedit(-1);
                }
                else if(index == 1)
                {
                    //submit
                    this.submit_data();
                }

            }
        },
        action_change(id,idx_action)
        {
            if(this.additional_data.status != "1")
            {
                
            }
            else
            {
                if(idx_action == 0)
                {
                    this.$refs['cpAddEditDetailPo'].open_dialog(id);
                    //this.opendialog_createedit(id);
                }
                else if(idx_action == 1)
                {
                    
                }
                else if(idx_action == 2)
                {
                    this.delete_data(id);
                }
                
            }
        },

        after_fill_additional_data()
        {
            if(this.additional_data.status != "1")
            {
                //conditional khusus component ini
                //jika status != new, maka tidak bisa submit/add/edit/delete
                
                this.info_table.actions = ['Revision'];
                this.info_table.button_on_index = ["Incoming", "Print"];
            }
        }

    },
    computed: {
    },
    mounted(){      
        this.info_table = JSON.parse(JSON.stringify(this.database[this.name_table])); //harusnya semau begini
        
        
        
    },
    mixins:[
        mxCrudBasic
    ],
}
</script>

