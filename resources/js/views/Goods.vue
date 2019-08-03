

<template>
    <div>
        
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
        
        
        

         <!-- POPUP STOCK CARD --> <!-- MASIH DITANYAKAN -->
        <!-- <v-dialog v-model="dialog_detailracks" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_detailracks()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Racks</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px'>

                    <v-text-field
                        v-model="popup_search_detailracks"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                      ></v-text-field>
                    <v-data-table
                    disable-initial-sort
                    :headers="headers_popup_detailracks"
                    :items="popup_detailracks"
                    :search="popup_search_detailracks"
                    class=""
                    >
                    <template v-slot:items="props">
                        <td>{{ props.index + 1 }}</td>
                        <td>{{ props.item.rack }}</td>
                        <td>{{ props.item.stock }}</td>
                    </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-dialog> -->

        
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
        :prop_urlGetMasterData='info_table.request_master_data ? generate_url(info_table.singular_name, "create") : null'


        v-on:done='refresh_table()'
        ref="cpForm"

        ></cp-form>
        

        <!-- ================================ -->

        <!-- HEADER DATATABLE -->
       <cp-header
       :prop_icon='info_table.icon'
       :prop_title='info_table.title'
       :prop_search_data='search_data'

       v-on:search_change='search_data=$event'
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

        v-on:action_clicked='action_change'
        ref="cpDatatable"

        ></cp-datatable>

        <!-- ================================ -->
    </div>
</template>

<script>
import mxCrudChildForm from '../mixin/mxCrudChildForm';


export default {

    data () {
        return {
            info_table:{},

            name_table:'goods',
            
            search_data: null,

            
        }
    },
    
    methods: {
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
                
                this.opendialog_detail(id_datatable, 'cpDetailRacks', 'racks');

            }
            else if(idx_action == 2)
            {
                this.opendialog_detail(id_datatable, 'cpDetailPriceSellings', 'sellingPrices');
            }
            else if(idx_action == 3)
            {
                //opendialog_detailstockcards(id_datatable);
            }
            else if(idx_action == 4)
            {
                this.opendialog_detail(id_datatable, 'cpDetailPricelists', 'pricelists');
            }
            else if(idx_action == 5)
            {

            }
            else if(idx_action == 6)
            {

                this.delete_data(id_datatable);
            }
            //this.action_selected[id_datatable] = null;
        },
        




    },
    mounted(){
        
        this.info_table = this.database[this.name_table];
        
        this.$refs['cpForm'].get_master_data();


    },
    mixins:[
        mxCrudChildForm,
    ],
}
</script>

