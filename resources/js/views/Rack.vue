<template>
    <div class='bgwhite' v-if='!open_goods'>

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
        :prop_filter='info_table.data.filter'

        v-on:action_clicked='action_change'
        ref="cpDatatable"

        ></cp-datatable>

        <!-- ================================ -->
    </div>
    <div v-else>

        <!-- CUSTOM COMPONENT -->
        <!-- UNTUK COMPONENT GOODS_RACK -->
        

        <v-breadcrumbs :items="breadcrumbs">
            <template v-slot:divider>
                <v-icon>chevron_right</v-icon>
            </template>
        </v-breadcrumbs>

        <cp-goods-rack  :prop_list_filter='filter'></cp-goods-rack>
        
        <!-- ================================ -->
        
        <!-- ================================ -->

    </div>
</template>

<script>
import mxCrudBasic from '../mixin/mxCrudBasic';
import cpGoodsRack from './../components/cpGoodsRack.vue'

export default {
    components : {
        cpGoodsRack
    },
    data () {
        return {
            info_table:{},
            name_table:'racks',
            search_data: null,
            open_goods : false,
            filter : [['goods', '-1']],
            breadcrumbs:[
                {
                    text: 'Racks',
                    disabled: false,
                    href: '/rack'
                },
                {
                    text: 'Goods Rack',
                    disabled: false,
                    href: '/goodsrack'
                }
            ],
        }
    },
    methods: {
        action_change(id,idx_action)
        {
            if(idx_action == 0)
            {
                this.opendialog_createedit(id);
            }
            else if(idx_action == 1)
            {
                
                this.opencomponent_goods(id);
            }
            else if(idx_action == 2)
            {
                this.delete_data(id);
            }
        },
        opencomponent_goods(id)
        {
            
            if(id != -1)
            {
                
                for(var i = 0;i<this.filter.length;i++)
                {
                    for(var j = 0;j<this.filter[i].length;j++)
                    {
                        var temp = this.filter[i][j];
                        if(temp == 'goods')
                        {
                            this.open_goods = true;
                        }
                    }
                }
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

