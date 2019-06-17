<div>
    <v-container fluid>
        <h3>Customer</h3>
    </v-container>
</div>

<template>
    <div>

        

        <!-- POPUP CREATE EDIT -->
        <v-dialog v-model="dialog_createedit" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_createedit()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title v-html='id_data_edit == -1 ?"Add Customer":"Edit Customer"'></v-toolbar-title>

                </v-toolbar>
                <v-form v-model="valid" style='padding:30px' ref='formCreateEdit'>


                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.name' label="Name" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.no_hp' label="No HP" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.address' label="Address" required></v-text-field>

                    


                    <v-btn v-on:click='save_data()' >submit</v-btn>
                </v-form>
            </v-card>
        </v-dialog>

        <v-layout row class='bgwhite margintop10'>
            <v-flex xs6>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>person_pin</v-icon>
                    <h2 class='titledatatable'>Customers Data</h2>
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
            class="datatable"
        >
        <template v-slot:items="props">
            <td>{{ props.item.no }}</td>
            <td>{{ props.item.name }}</td>
            <td>{{ props.item.no_hp }}</td>
            <td>{{ props.item.address }}</td>

            <td>
                <div class="text-xs-left">
                    <v-menu offset-y>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          color="primary"
                          dark
                          v-on="on"
                          class='btnaction'
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
            name_table:'customers',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },


            action_items: ['Edit', 'Delete'],
            on:false,

            valid:null,
            dialog_createedit:false,
            
            id_data_edit:-1,

            input:{
                name:'',
                no_hp:'',
                address:'',
                

            },
            input_before_edit:null, //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'No HP', value: 'no_hp'},
                { text: 'Address', value: 'address'},
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
                this.delete_data(id);
            }
        },

        convert_data_input(tempobject)
        {

            
            this.input.name = tempobject.name;
            this.input.no_hp = tempobject.no_hp;
            this.input.address = tempobject.address;

            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
        },

        prepare_data_form()
        {
            const formData = new FormData();
            if(this.id_data_edit == -1) //jika add data
            {
                formData.append('name', this.input.name);
                formData.append('no_hp', this.input.no_hp);
                formData.append('address', this.input.address);


            }
            else //jika edit data
            {
                if(this.input.name != this.input_before_edit.name) 
                    formData.append('name', this.input.name);
                if(this.input.no_hp != this.input_before_edit.no_hp)
                    formData.append('no_hp', this.input.no_hp);
                if(this.input.address != this.input_before_edit.address)
                    formData.append('address', this.input.address);

            }
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },

        showTable(r) 
        {
            this.data_table = r.data.items.customers;
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

