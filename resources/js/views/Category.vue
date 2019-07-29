<div>
    <v-container fluid>
        <h3>categories</h3>
    </v-container>
</div>

<template>
    <div>

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

        

        <!-- POPUP CREATE EDIT BARU -->
        <cp-form 

        
        :prop_title='info_table.table_name'
        prop_countStep='1'
        prop_editableEdit='true'
        prop_editableAdd='true'
        :prop_dataInfo='info_table.data'
        :prop_tableName='name_table'
        :prop_widthForm='info_table.widthForm'
        :prop_singularName='info_table.singular_name'
        v-on:done='get_data()'
        ref="cpForm"

        ></cp-form>

        <!-- ================================ -->

       


        <v-layout row class='bgwhite margintop10'>
            <v-flex xs6>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>category</v-icon>
                    <h2 class='titledatatable'>Categories Data</h2>
                    <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark class='btnadddata'>
                    Add Data
                </v-btn>
                </div>
                
            </v-flex>
            <v-flex xs12 class="text-xs-right">
                <v-text-field
                    class='d-inline-block searchdatatable'
                    v-model="search_data"
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-flex>
        </v-layout>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="data_table"
            :search="search_data"
            class='datatable'
        >
        <template v-slot:items="props" >
            <td>{{ props.item.no }}</td>
            <td>{{ props.item.name }}</td>

            <td>
                <div class="text-xs-left">
                    <v-menu offset-y>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          class='btnaction'
                          color="primary"
                          dark
                          v-on="on"
                        >
                          Action
                        </v-btn>
                      </template>
                      <v-list>
                        <v-list-tile
                          v-for="(item, index) in action_items"
                          :key="index"
                          v-on:click="action_change(props.item.id,index)"
                          
                        >
                          <v-list-tile-title>{{ item }}</v-list-tile-title>
                        </v-list-tile>
                      </v-list>
                    </v-menu>
                </div>
            </td>
        </template>
        </v-data-table>
    </div>
</template>

<script>
import axios from 'axios'
import mxCrudBasic from '../mixin/mxCrudBasic';

export default {
    data () {
        return {
            info_table:{},

            name_table:'categories',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },


            action_items: ['Edit','Goods', 'Delete'],
            on:false,

            valid:null,
            
            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Action', align:'left',sortable:false, width:'15%'},

            ],

           

            data_table:[],
            search_data: null,
            

            
        }
    },
    methods: {

        action_change(id,idx_action)
        {
            if(idx_action == 0)
            {
                
                this.opendialog_createedit(id)
            }
            else if(idx_action == 1)
            {
                this.opendialog_detail(id, 'cpDetailGoods', 'goods');
            }
            else if(idx_action == 2)
            {
                this.delete_data(id);
            }
        },


        

        showTable(r) 
        {
            this.data_table = r.data.items.categories;
        },
        

    },
    mounted(){
        this.get_data();
        this.name_table = "categories";
        this.info_table = this.database[this.name_table];

    },
    mixins:[
        mxCrudBasic
    ],
}
</script>

