

<template>
    <div class='bgwhite'>
        
       <v-breadcrumbs divider=">" :items='computed_breadcrumbs' class='breadcrumbs'>
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

        <template v-if='open_state == "Supplier"'>
            <!-- LIST POPUP DETAIL -->
            <cp-detail 
             
            v-if='notNullObject(info_table.get_data_detail)'
            v-for='(data_detail,key,index) in info_table.get_data_detail'

            :prop_title='"Detail " + data_detail.title' 
            :prop_response_attribute='info_table.key'
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
            :prop_tempInput='generate_temp_input(info_table.plural_name)'
            :prop_input='generate_input(info_table.plural_name)'
            :prop_preview='generate_preview(info_table.plural_name)'
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
            :prop_url_index='generate_url(info_table.table_name, "index")'
            :prop_filter='info_table.data.filter'

            v-on:action_clicked='action_change'
            ref="cpDatatable"

            ></cp-datatable>

            <!-- ================================ -->
        </template>
    </div>
</template>

<script>
import mxCrud from '../mixin/mxCrud';


export default {

    data () {
        return {
            info_table:{},

            name_table:'suppliers',
            
            search_data: null,

            open_state : 'Supplier',
            list_state : 
            {
                'Supplier' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'Supplier',
                    disabled: false,
                    cp : 'Supplier',
                    before : null,
                },
                //level 2
                
            ],
            
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
        action_change(id_datatable,idx_action)
        {
            
            //console.log('action_change');
            //console.log(this.action_selected);
            // console.log(this.action_selected == 'Rack');
            if(idx_action == 0)
            {
                this.opendialog_createedit(id_datatable);
            }
            else if(idx_action == 1)
            {
                this.delete_data(id_datatable);
            }
             
            
            
        },
        




    },
    mixins:[
        mxCrud,
    ],
}
</script>

