
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

        <template v-if='open_state == "Warehouse"'>
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


            <!-- VERY CUSTOM POPUP DETAIL -->
            <!-- POPUP STOCK OPNAME -->
            
                            
            <!-- ================================ -->
            
            
            

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
            :prop_dataInfo='JSON.parse(JSON.stringify(info_table.data))'
            :prop_tableName='name_table'
            :prop_widthForm='info_table.widthForm'
            :prop_singularName='info_table.singular_name'
            
            :prop_input='generate_input(info_table.plural_name)'
            :prop_preview='generate_preview(info_table.plural_name)'
            :prop_urlGetMasterData='info_table.request_master_data ? generate_url(info_table.plural_name, "create") : null'
            :prop_urlMOCC='info_table.data.custom_component.cpMakeOrCopyChild.url'
            


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


            <!-- STOCK OPNAME -->
            <cp-stock-opname ref='cpStockOpname' :prop_data_form='form_stockopname'></cp-stock-opname>
            <!-- ========================= -->
        </template>
    </div>
</template>

<script>
import mxCrudChildForm from '../mixin/mxCrudChildForm';
import cpStockOpname from './../components/cpStockOpname.vue';


export default {
    components:{
        cpStockOpname,
    },

    data () {
        return {
            info_table:{},
            name_table:'warehouses',
            search_data: null,

            open_state : 'Warehouse',
            list_state : 
            {
                'Warehouse' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'Warehouse',
                    disabled: false,
                    cp : 'Warehouse',
                    before : null,
                },
                //level 2
                
            ],

            finish_mounted:false,
            input : [],
            temp : null,
            form_stockopname:[],
            
        }
    },
    
    methods: {
        button_index_clicked(index)
        {
            if(index == 0)
            {
                this.opendialog_createedit(-1);
                this.$refs['cpForm'].prop_dataInfo = JSON.parse(JSON.stringify(this.info_table.data))
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
                
                this.opendialog_detail(id_datatable, 'cpDetailRacks', 'racks');

            }
            else if(idx_action == 2)
            {
                this.opendialog_detail(id_datatable, 'cpDetailGoods', 'goods');
            }
            else if(idx_action == 3)
            {
                //stock opname
                this.opendialog_stockopname(id_datatable);
            }
            else if(idx_action == 4)
            {
                this.delete_data(id_datatable);
            }
            
        },
        opendialog_stockopname(id)
        {
            //isi form_stockopname dulu
            var obj = this.findDataById(id);
            this.form_stockopname.warehouse = obj.name;
            this.form_stockopname.user = JSON.parse(localStorage.getItem('user')).name;
            this.form_stockopname.warehouse_id = id;
            this.$refs['cpStockOpname'].opendialog_stockopname();

        },
        closedialog_stockopname()
        {
            this.dialog_stockopname = false;
        },




    },

    mixins:[
        mxCrudChildForm,
    ],
}
</script>

