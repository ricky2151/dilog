<div>
    <v-container fluid>
        <h3>Supplier</h3>
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
                    <v-toolbar-title v-html='id_data_edit == -1 ?"Add Supplier":"Edit Supplier"'></v-toolbar-title>

                </v-toolbar>
                <v-form v-model="valid" style='padding:30px' ref='formCreateEdit'>


                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.name_company' label="Name Company" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.name_owner' label="Name Owner" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.name_pic' label="Name PIC" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.name_sales' label="Name Sales" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.address' label="Address" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.no_telp_company' label="No Telp Company" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.no_telp_owner' label="No Telp Owner" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.email" v-model='input.email' label="Email" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.fax' label="Fax" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.npwp' label="NPWP" required></v-text-field>

                    <v-text-field :rules="this.$list_validation.max_req" v-model='input.no_rek' label="No Rekening" required></v-text-field>


                    <v-btn v-on:click='save_data()' >submit</v-btn>
                </v-form>
            </v-card>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>Supplier Data</v-toolbar-title>
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
            <td>{{ findDataById(props.item.id,true) }}</td>
            <td>{{ props.item.name_company }}</td>
            <td>{{ props.item.name_pic }}</td>
            <td>{{ props.item.address }}</td>

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
            name_table:'suppliers',
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
                name_company:'',
                name_owner:'',
                name_pic:'',
                name_sales:'',
                address:'',
                no_telp_company:'',
                no_telp_owner:'',
                email:'',
                fax:'',
                npwp:'',
                no_rek:'',

            },
            input_before_edit:null, //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name_company'},
                { text: 'Name PIC', value: 'name_pic'},
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

            
            this.input.name_company = tempobject.name_company;
            this.input.name_owner = tempobject.name_owner;
            this.input.name_pic = tempobject.name_pic;
            this.input.name_sales = tempobject.name_sales;
            this.input.address = tempobject.address;
            this.input.no_telp_company = tempobject.no_telp_company;
            this.input.no_telp_owner = tempobject.no_telp_owner;
            this.input.email = tempobject.email;
            this.input.fax = tempobject.fax;
            this.input.npwp = tempobject.npwp;
            this.input.no_rek = tempobject.no_rek;
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
        },

        prepare_data_form()
        {
            const formData = new FormData();
            if(this.id_data_edit == -1) //jika add data
            {
                formData.append('name_company', this.input.name_company);
                formData.append('name_owner', this.input.name_owner);
                formData.append('name_pic', this.input.name_pic);
                formData.append('name_sales', this.input.name_sales);
                formData.append('address', this.input.address);
                formData.append('no_telp_company', this.input.no_telp_company);
                formData.append('no_telp_owner', this.input.no_telp_owner);
                formData.append('email', this.input.email);
                formData.append('fax', this.input.fax);
                formData.append('npwp', this.input.npwp);
                formData.append('no_rek', this.input.no_rek);

            }
            else //jika edit data
            {
                if(this.input.name_company != this.input_before_edit.name_company) 
                    formData.append('name_company', this.input.name_company);
                if(this.input.name_owner != this.input_before_edit.name_owner)
                    formData.append('name_owner', this.input.name_owner);
                if(this.input.name_pic != this.input_before_edit.name_pic)
                    formData.append('name_pic', this.input.name_pic);
                if(this.input.name_sales != this.input_before_edit.name_sales)
                    formData.append('name_sales', this.input.name_sales);
                if(this.input.address != this.input_before_edit.address)
                    formData.append('address', this.input.address);
                if(this.input.no_telp_company != this.input_before_edit.no_telp_company)
                    formData.append('no_telp_company', this.input.no_telp_company);
                if(this.input.no_telp_owner != this.input_before_edit.no_telp_owner)
                    formData.append('no_telp_owner', this.input.no_telp_owner);
                if(this.input.email != this.input_before_edit.email)
                    formData.append('email', this.input.email);
                if(this.input.fax != this.input_before_edit.fax)
                    formData.append('fax', this.input.fax);
                if(this.input.npwp != this.input_before_edit.npwp)
                    formData.append('npwp', this.input.npwp);
                if(this.input.no_rek != this.input_before_edit.no_rek)
                    formData.append('no_rek', this.input.no_rek);
                formData.append('_method','patch');
            }
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },

        showTable(r) 
        {
            this.data_table = r.data.items.suppliers;
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

