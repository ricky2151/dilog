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
        :prop_idEditTable='prop_list_filter.id_selected ? ["rack", "racks", prop_list_filter.id_selected] : ""'
        

        v-on:done='refresh_table()'
        ref="cpForm"

        ></cp-form>

        <!-- ================================ -->



        <!-- HEADER DATATABLE -->
       <cp-header
       :prop_icon='info_table.icon'
       :prop_title='info_table.title'
       :prop_search_data='search_data'
       :prop_information='prop_additional_data ? prop_additional_data : ""'
       :prop_format_information='prop_format_additional_data ? prop_format_additional_data : ""'
       :prop_button_on_index='info_table.button_on_index'

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
        :prop_url_index='prop_list_filter? generate_url("racks", "detail",prop_list_filter["id_selected"], info_table.plural_name) :  generate_url(info_table.table_name, "index")'
        :prop_filter='prop_list_filter'
        :prop_custom_response_attribute='info_table.custom_response_attribute'

        v-on:action_clicked='action_change'
        ref="cpDatatable"

        ></cp-datatable>

        <!-- ================================ -->
    </div>
</template>

<script>
import mxCrudBasic from '../../mixin/mxCrudBasic';

export default {
    props: ['prop_list_filter', 'prop_format_additional_data', 'prop_additional_data'],
    data () {
        return {
            info_table:{},
            name_table:'goods_racks',
            search_data: null,
            
        }
    },
    methods: {
        button_index_clicked(index)
        {
            if(index == 0)
            {
                this.opendialog_createedit(-1);
            }
        },
        action_change(id,idx_action)
        {
            if(idx_action == 0)
            {
                this.opendialog_createedit(id);
            }
            else if(idx_action == 1)
            {
                this.delete_data(id);
            }
        },
        // showTable(r,id_goods_for_table)
        // {
        //     //process r agar dari id menjadi nama
        //     if(id_goods_for_table != "all")
        //     {

        //         for(var i = 0;i<r.data.items.goods_rack.length;i++)
        //         {
        //             if(r.data.items.goods_rack[i].id == id_goods_for_table)
        //             {
        //                 this.data_table.push(r.data.items.goods_rack[i]);
        //             }
        //         }
        //     }
        //     else
        //     {
        //         this.data_table = r.data.items.goods_rack;
                
        //     }

        // },

    },
    computed: {
    },
    mounted(){      
        
        this.info_table = this.database[this.name_table];
    },
    mixins:[
        mxCrudBasic
    ],
}
</script>

