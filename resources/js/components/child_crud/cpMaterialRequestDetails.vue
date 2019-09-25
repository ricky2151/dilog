<template>
    <div class='bgwhite'>


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
        

        v-on:done='refresh_table()'
        ref="cpForm"

        ></cp-form>

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
        :prop_url_index='prop_list_filter? generate_url("material_request_details", "detail",prop_list_filter["id_selected"], info_table.plural_name) :  generate_url(info_table.table_name, "index")'
        :prop_filter='prop_list_filter'
        prop_get_additional_data='true'

        v-on:show_additional_data='fill_additional_data'
        v-on:action_clicked='action_change'
        ref="cpDatatable"

        ></cp-datatable>

        <!-- ================================ -->
    </div>
</template>

<script>
import mxCrudBasic from '../../mixin/mxCrudBasic';

export default {
    props: ['prop_list_filter'],
    data () {
        return {
            info_table:{},
            name_table:'material_request_details',
            search_data: null,
            additional_data:null,
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

