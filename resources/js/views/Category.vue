<div>
    <v-container fluid>
        <h3>categories</h3>
    </v-container>
</div>

<template>
    <div>

        <!-- POPUP DETAIL GOODS -->
        <v-dialog v-model="dialog_detailgoods" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_detailgoods()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Goods</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px'>
                    <v-text-field
                        v-model="popup_search_detailgoods"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                      ></v-text-field>
                    <v-data-table
                    disable-initial-sort
                    :headers="headers_popup_detailgoods"
                    :items="popup_detailgoods"
                    :search="popup_search_detailgoods"
                    class=""
                    >
                    <template v-slot:items="props">
                        <td>{{ props.index + 1 }}</td>
                        <td>{{ props.item.name }}</td>
                        <td>{{ props.item.stock }}</td>
                    </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-dialog>

        <!-- POPUP CREATE EDIT -->
        <v-dialog v-model="dialog_createedit" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_createedit()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title v-html='id_data_edit == -1 ?"Add Categories":"Edit Categories"'></v-toolbar-title>

                </v-toolbar>
                <v-form v-model="valid" style='padding:30px' ref='formCreateEdit'>
                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.name' label="Name" required></v-text-field>
                    <v-btn v-on:click='save_data()' >submit</v-btn>
                </v-form>
            </v-card>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>Categories Data</v-toolbar-title>
        </v-toolbar>
        <v-layout row class='bgwhite'>
            <v-flex xs3>
                <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark class='marginleft30'>
                    Add Data
                </v-btn>
            </v-flex>
            <v-flex xs12 class="text-xs-right">
                <v-text-field
                    class='marginhorizontal10 searchwidth d-inline-block'
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
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.index + 1 }}</td>
            <td>{{ props.item.name }}</td>

            <td>
                <div class="text-xs-left">
                    <v-menu offset-y>
                      <template v-slot:activator="{ on }">
                        <v-btn
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
            name_table:'categories',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },


            action_items: ['Edit','Goods', 'Delete'],
            on:false,

            valid:null,
            dialog_createedit:false,
            dialog_detailgoods:false,
            

            id_data_edit:-1,

            input:{
                name:'',    
            },
            input_before_edit:null, //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Action', align:'left',sortable:false, width:'15%'},

            ],

            headers_popup_detailgoods : [
                { text: 'No', value:'no'},
                { text: 'Goods', value:'name'},
                { text: 'Stock', value:'stock'},

            ],

            data_table:[],
            search_data: null,
            

            popup_detailgoods :
            [
                {
                    name:'meja',
                    stock:12,
                },
                {
                    name:'kursi',
                    stock:13,
                },
                {
                    name:'indomie',
                    stock:10,
                },
            ],
            popup_search_detailgoods:null,
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
                this.opendialog_detailgoods(id);
            }
            else if(idx_action == 2)
            {
                this.delete_data(id);
            }
        },

        opendialog_detailgoods(id_edit)
        {
            this.dialog_detailgoods = true;
            var temp_popup = this.get_popup_detail(id_edit, 'goods');
            if(temp_popup != null)
            {
                this.popup_detailgoods = temp_popup.categories;    
            }
            
            
        },

        closedialog_detailgoods(){ 
            this.dialog_detailgoods = false;
        },

        convert_data_input(tempobject)
        {
            this.input.name = tempobject.name;
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
        },

        prepare_data_form()
        {
            const formData = new FormData();
            if(this.id_data_edit == -1) //jika add data
            {
                formData.append('name', this.input.name);
            }
            else //jika edit data
            {
                if(this.input.name != this.input_before_edit.name) 
                    formData.append('name', this.input.name);
                formData.append('_method','patch');
            }
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },

        showTable(r) 
        {
            this.data_table = r.data.items.categories;
        },
        

    },
    mounted(){
        this.get_data();

    },
    mixins:[
        mxCrudBasic
    ],
}
</script>

