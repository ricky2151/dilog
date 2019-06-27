

<template>
    <div>
        
        <v-dialog v-model="dialog_createedit" fullscreen>
            <v-form v-model="valid" ref='formCreateEdit'>
                <v-card>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html='id_data_edit == -1 ?"Add Goods Rack":"Edit Goods Rack"'></v-toolbar-title>

                    </v-toolbar>
                    <v-stepper v-model="e6" vertical>

                        <!-- ==== STEPPER 1 ==== -->

                        <v-stepper-step :complete="e6 > 1" step="1" editable>
                            <h3>Goods Rack Data</h3>
                        </v-stepper-step>

                        <v-stepper-content step="1">
                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' :rules="this.$list_validation.selectdata_req"  v-model='input.goods_id' :items="ref_input.goods" item-text='name' item-value='id' label="Select Goods"></v-select>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' :rules="this.$list_validation.selectdata_req"  v-model='input.rack_id' :items="ref_input.racks" item-text='name' item-value='id' label="Select Rack"></v-select>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.numeric_req" v-model='input.stock' label="Stock" required></v-text-field>
                                </v-flex>
                            </v-layout>

                                
                            <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                                
                            
                        </v-stepper-content>

                        
                        
                        
                        <v-btn v-on:click='save_data()' >submit</v-btn>
                        
                        
                        
                    </v-stepper>
                </v-card>
            </v-form>
        </v-dialog>

        <v-layout row class='bgwhite margintop10'>
            <v-flex xs6>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>assignment_returned</v-icon>
                    <h2 class='titledatatable'>Goods Racks Data</h2>
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
            <td >{{ props.item.rack_name }}</td>
            <td>{{ props.item.goods_name }}</td>
            <td class="text-xs-right">{{ props.item.stock }}</td>
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
import mxCrudChildForm from '../mixin/mxCrudChildForm';
export default {
    name: 'cpGoodsRack',
    props:['prop_id_goods_for_table'],
    data () {
        return {
            
            name_table:'goodsracks',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },

            action_items: ['Edit', 'Delete'],

            valid:false,
            on:false,

            dialog_createedit:false,

            e6:1,

            id_data_edit:-1,

            
            input_before_edit:{ //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
                
            },
            input:{
                goods_id:'',
                rack_id:'',
                stock:'',
            },



            ref_input:
            {
                goods:[
                    {id:6,name:'meja'},
                    {id:7,name:'kursi'},
                ],
                racks:[
                    {id:6,name:'rack1'},
                    {id:7,name:'rack2'},
                ],
                //statis
                free:[
                    {value:1,name:'True'},
                    {value:0,name:'False'},
                ]
            },
            

            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Racks', value: 'racks'},
                { text: 'Goods', value: 'goods'},
                { text: 'Stock', value: 'stock', align:'right' },
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],

            data_table: [],
            search_data: null,

            
        }
    },
    computed: {
      id_goods_for_table: function() {
        return this.prop_id_goods_for_table;
      },
    },
    watch:{

    },
    methods: {
       

       
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items.goods);
            this.ref_input.goods = r.data.items.goods;
            this.ref_input.racks = r.data.items.racks;
            
        },
        convert_data_input_goodsrack(r)
        {
            var temp_r = r.data.items.goods_rack;
            this.input.goods_id = temp_r.goods_id;
            this.input.rack_id = temp_r.rack_id;
            this.input.stock = temp_r.stock;

            
            

            // for(var i = 0;i<temp_r.attribute_goods.length;i++)
            // {  
            //     this.input.attribute_goods.push({
            //         attribute:{
            //             id: temp_r.attribute_goods[i].id,
            //             name: temp_r.attribute_goods[i].name,
            //         },
            //         value:temp_r.attribute_goods[i].value,
            //     })
            // }
            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form_goodsrack()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.id_data_edit != -1) //jika sedang diedit
            {

                //data yang harus dikirim saat update :
                //1. data goodsrack yang BERUBAH SAJA
                //2. data price_sellings dan batch AKHIR 
                

                //step-step :
                //1. kirim data goodsrack yang berubah
                if(this.input.goods_id != this.input_before_edit.goods_id) formData.append('goods_id', this.input.goods_id);
                if(this.input.rack_id != this.input_before_edit.rack_id) formData.append('rack_id', this.input.rack_id);
                if(this.input.stock != this.input_before_edit.stock) formData.append('stock', this.input.stock);
                



                
                formData.append('_method', 'patch');

            }
            else //jika sedang add
            {

                //data-data yang harus dikirim : 
                //1. semua data goodsrack
                //2. semua data price selling dan batch

                //step-step : 
                //1. kirim data goodsrack
                formData.append('goods_id', this.input.goods_id);
                formData.append('rack_id', this.input.rack_id);
                formData.append('stock', this.input.stock);

            

            }

            
            

            //3. masukan token
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },
        
        showTable(r,id_goods_for_table)
        {
            //process r agar dari id menjadi nama
            if(id_goods_for_table != "all")
            {

                for(var i = 0;i<r.data.items.goods_rack.length;i++)
                {
                    if(r.data.items.goods_rack[i].id == id_goods_for_table)
                    {
                        this.data_table.push(r.data.items.goods_rack[i]);
                    }
                }
            }
            else
            {
                this.data_table = r.data.items.goods_rack;
                
            }

        },
        
       





        //TESTING INPUT
        testing_input(){
            //manual :
            


        }


    },
    mounted(){

        
        this.get_data(this.id_goods_for_table);
        this.get_master_data();
        //this.testing_input();

    },
    mixins:[
        mxCrudChildForm,
    ]
}
</script>


