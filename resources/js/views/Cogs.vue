<div>
    <v-container fluid>
        <h3>COGS</h3>
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
                    <v-toolbar-title v-html='id_data_edit == -1 ?"Add COGS":"Edit COGS"'></v-toolbar-title>

                </v-toolbar>
                <v-form v-model="valid" style='padding:30px' ref='formCreateEdit'>
                    <v-layout row>
                        <v-flex xs12>
                            <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.name'  label="Name" counter=191></v-text-field>
                        </v-flex>
                    </v-layout>

                    <v-layout row>
                        <v-flex xs12>
                            <v-text-field class="pa-2" :rules="this.$list_validation.numeric_req" v-model='input.nominal'  label="Nominal"></v-text-field>
                        </v-flex>
                    </v-layout>

                    <v-layout row>
                        <v-flex xs12>
                            <v-select class='pa-2' :rules="this.$list_validation.selectdata_req"  v-model='input.type_id' :items="ref_input.type" item-text='name' item-value='id' label="Select Type"></v-select>
                        </v-flex>
                    </v-layout>

                    <v-btn v-on:click='save_data()' >submit</v-btn>
                </v-form>
            </v-card>
        </v-dialog>


       

        <v-layout row class='bgwhite margintop10'>
            <v-flex xs6>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>monetization_on</v-icon>
                    <h2 class='titledatatable'>COGS Data</h2>
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
            :search='search_data'
            class="datatable"
        >
            <template v-slot:items="props">
                <td>{{ props.item.no }}</td>
                <td class="text-xs-right">{{ props.item.nominal }}</td>
                <td class="text-xs-right">{{ props.item.types.name }}</td>
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
export default {
    data () {
        return {
            
            name_table:'units',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },

            action_items: ['Edit', 'Delete'],

            valid:false,
            dialog_createedit:false,
            
            id_data_edit:-1,

            
            input_before_edit:{ //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
                
            },

            input:{
                nominal:'',
                name:'',
                type_id:'',
            },

            ref_input:
            {
                types:[
                    {id:6,name:'HPP Penjualan'},
                    {id:7,name:'HPP Pembelian'},
                ],
            },
            

            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Nominal', value: 'nominal', align:'right' },
                { text: 'Type', value: 'type', align:'right' },
                { text: 'Action', align:'left',width:'15%',sortable:false},
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
            this.input.nominal = tempobject.nominal;
            this.input.type_id = tempobject.type;
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
        },
        
        prepare_data_form()
        {
            const formData = new FormData();
            if(this.id_data_edit == -1) //jika add data
            {
                formData.append('name', this.input.name);
                formData.append('nominal', this.input.nominal);
                formData.append('type', this.input.type);
            }
            else //jika edit data
            {
                if(this.input.name != this.input_before_edit.name) 
                    formData.append('name', this.input.name);
                if(this.input.nominal != this.input_before_edit.nominal) 
                    formData.append('nominal', this.input.nominal);
                if(this.input.type != this.input_before_edit.type) 
                    formData.append('type', this.input.type);
                formData.append('_method','patch');
            }
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },

        showTable(r)
        {
            //console.log(r.data.items.goods[0]);

            this.cogs = r.data.items.cogs;
        },
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items[0].units);
            this.ref_input.types = r.data.items.types;
        },
        convert_data_input(tempobject)
        {

            this.input.name = tempobject.name;
            this.input.nominal = tempobject.nominal;
            this.input.type_id = tempobject.type_id
            this.input.cogs_components = temp_r.cogs_components;

            

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        
        get_master_data()
        {
            axios.get('/api/cogs/create', {
                params:{
                    token: localStorage.getItem('token')
                }
            },{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json'
                }
            }).then(r => this.fill_select_master_data(r))
            .catch(function (error)
            {
                if(error.response.status == 422)
                {
                    swal('Request Failed', 'Check your internet connection !', 'error');
                }
                else
                {
                    swal('Unkown Error', error.response.data , 'error');
                }
            });
        },
        get_data_before_edit(idx_edit)
        {
            var id_edit = this.cogs[idx_edit].id;
            axios.get('/api/cogs/' + id_edit + '/edit', {
                params:{
                    token: localStorage.getItem('token')
                }
            },{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json'
                }
            }).then(r=> {

                this.opendialog_createedit(idx_edit,r); //idx_edit bukan id_edit !
            })
            .catch(function (error)
            {
                if(error.response.status == 422)
                {
                    swal('Request Failed', 'Check your internet connection !', 'error');
                }
                else
                {
                    swal('Unkown Error', error.response.data , 'error');
                }
            });
        },




        //TESTING INPUT
        testing_input(){
            //manual :
            //this.thumbnail_filename
            //this.thumbnail_file
            //this.category_goods
            //this.attribute_goods
            //this.material_goods

            this.input.name = 'Meja Bundar';
            this.input.code = 'MB01';
            this.input.desc = 'Meja yang bentuknya bundar';
            this.input.margin = '100';
            this.input.value = '120';
            this.input.status = '140';
            this.input.last_buy_pricelist = '160';
            this.input.barcode_master = 'X123';
            this.input.avgprice_status = 1;
            this.input.tax = '180';
            this.input.unit_id = '1';
            this.input.cogs_id = '2';


        }


    },
    mounted(){
        
        this.get_data();
        this.get_master_data();
        //this.testing_input();

    },
}
</script>

