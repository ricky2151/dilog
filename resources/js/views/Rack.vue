<div>
    <v-container fluid>
        <h3>Rack</h3>
    </v-container>
</div>

<template>
    <div>
        
        <v-dialog v-model="dialog_createedit" fullscreen>
            <v-form v-model="valid" ref='formCreateEdit' class='fixfullscreen'>
                <v-card class='fixfullscreen'>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html='id_data_edit == -1 ?"Add Rack":"Edit Rack"'></v-toolbar-title>

                    </v-toolbar>
                    <v-stepper v-model="e6" vertical>

                        <!-- ==== STEPPER 1 ==== -->

                        <v-stepper-step :complete="e6 > 1" step="1" editable>
                            <h3>Racks Data</h3>
                        </v-stepper-step>

                        <v-stepper-content step="1">
                            
                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.name'  label="Name" counter=191></v-text-field>
                                </v-flex>
                            </v-layout>
                            
                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' :rules="this.$list_validation.selectdata_req" v-model='input.warehouse_id' :items="ref_input.warehouses" item-text='name' item-value='id' label="Select Warehouse"></v-select>
                                </v-flex>
                            </v-layout>

                            <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                                
                            
                        </v-stepper-content>

                        <!-- ==== STEPPER 2 ==== -->

                        <v-stepper-step :complete="e6 > 2" step="2" editable><h3>Goods Rack</h3></v-stepper-step>

                        <v-stepper-content step="2">
                            <v-select v-model='temp_input.goods_rack.goods' :items="ref_input.goods" item-text='name' return-object label="Select Goods"></v-select>

                            <v-text-field v-model="temp_input.goods_rack.stock" label="Stock" required></v-text-field>
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_goods_rack != -1' color="red" dark v-on:click='table_goods().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_goods().save()' v-html='temp_input.id_edit_goods_rack == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>Goods Rack Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'Goods', value:'goods'},
                                {text:'Stock',value:'stock',align:'right'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.goods_rack"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.goods.name }}</td>
                                    <td class="text-xs-right">{{ props.item.stock }}</td>
                                    <td>
                                        <v-btn class='button-action' v-on:click='table_goods().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_goods().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>

                            
                        </v-stepper-content>

                        
                        
                        <v-btn v-on:click='save_rack()' >submit</v-btn>
                        
                        
                        
                    </v-stepper>
                </v-card>
            </v-form>
        </v-dialog>


        <!-- UNTUK COMPONENT GOODS_RACK -->
        <div v-if="id_goods_for_table != 'all' && component_goodsrack">

            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <v-icon>chevron_right</v-icon>
                </template>
            </v-breadcrumbs>

            <cp-goods-rack  :prop_id_goods_for_table='id_goods_for_table'></cp-goods-rack>
        </div>
        


        <div v-if="!component_goodsrack">
            <v-layout row class='bgwhite margintop10'>
            <v-flex xs6>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>dns</v-icon>
                    <h2 class='titledatatable'>Racks Data</h2>
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
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.warehouse_name }}</td>
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
    </div>
</template>

<script>
import axios from 'axios'
import cpGoodsRack from './../components/cpGoodsRack.vue'
import mxCrudChildForm from '../mixin/mxCrudChildForm';
export default {
    components:{
        cpGoodsRack,
    },
    data () {
        return {
            
            name_table:'racks',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },

            action_items: ['Edit', 'Goods', 'Delete'],

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

            component_goodsrack:false,
            id_goods_for_table:'all',

            valid:false,
            on:false,
            dialog_createedit:false,
            e6:1,
            

            id_data_edit:-1,

           
            input_before_edit:{ //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
                
            },
            input:{
                name:'',
                warehouse_id:'',
                goods_rack:[],
            },

            temp_input:{
                id_edit_goods_rack:-1, //artinya add data,
                goods_rack:
                {
                    goods:
                    {
                        id:null,
                        name:null,
                    },
                    stock:null,
                },
            },


            ref_input:
            {
                warehouses:[
                    {id:6,name:'gudang A'},
                    {id:7,name:'gudang TENGAH'},
                    {id:8,name:'gudang B'},
                ],
                goods:[
                    {id:6,name:'meja'},
                    {id:7,name:'kursi'},
                ],
            },
            

            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Warehouse', value: 'warehouse' },
                { text: 'Action', align:'left',sortable:false},
            ],


            data_table: [],
            search_data:null,
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
                this.get_data_before_edit(id_datatable);
            }
            else if(idx_action == 1)
            {
                
                this.opencomponent_goodsrack(id_datatable);
            }
            else if(idx_action == 2)
            {
                this.delete_data(id_datatable);
                
            }
           
            
        },


        table_goods()
        {
            var self = this;
            return{

                
                showData(idx){
                    
                    self.temp_input.goods_rack = JSON.parse(JSON.stringify(self.input.goods_rack[idx]));
                    self.temp_input.id_edit_goods_rack = idx;
                },
                clearTempInput(){
                    for (var key in self.temp_input.goods_rack)
                    {
                        if(self.temp_input.goods_rack[key])
                            self.temp_input.goods_rack[key] = null;
                    }
                    
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_goods_rack));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.goods_rack));
                    
                        self.input.goods_rack.push(temp);

                        
                    }
                    else
                    {
                        self.input.goods_rack[id_edit] = JSON.parse(JSON.stringify(self.temp_input.goods_rack));
                        self.temp_input.id_edit_goods_rack = -1;

                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_goods_rack = -1;
                },
                delete(idx)
                {
                    self.input.goods_rack.splice(idx,1);
                }



            }
        },

        
        opencomponent_goodsrack(id)
        {
            
            if(id != -1)
            {
                
                this.id_goods_for_table = id;
                this.component_goodsrack = true;
            }
        },

       
        
        showTable(r)
        {
            //console.log(r.data.items.goods[0]);

            this.data_table = r.data.items.racks;
        },
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items[0].units);
            this.ref_input.warehouses = r.data.items.warehouses;
            //kasus khusus
            this.ref_input.goods = r.data.items.goods;
            
            

        },
        convert_data_input_rack(r)
        {
            var temp_r = r.data.items.rack;

            this.input.name = temp_r.name;
            this.input.warehouse_id = temp_r.warehouse_id;

            for(var i = 0;i<temp_r.goods_racks.length;i++)
            {
                this.input.goods_rack.push(
                {
                    goods:{
                        id:temp_r.goods_racks[i].goods_id, 
                        name:temp_r.goods_racks[i].goods_name,
                        
                    },
                    stock:temp_r.goods_racks[i].stock,
                }
                    
                );
            }
            //this.input.goods_rack = temp_r.goods_racks;

            

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form_rack()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.id_data_edit != -1) //jika sedang diedit
            {

                //data yang harus dikirim saat update :
                //1. data racks yang BERUBAH SAJA
                //2. data goods_rack AKHIR


                //step-step :
                //1. kirim data rack yang berubah
                if(this.input.name != this.input_before_edit.name) formData.append('name', this.input.name);
                if(this.input.warehouse_id != this.input_before_edit.warehouse_id) formData.append('warehouse_id', this.input.warehouse_id);


                //2. kirim data goods_rack 
                for(var i = 0;i<this.input.goods_rack.length;i++)
                {
                    formData.append('goods_rack[' + i + '][goods_id]',this.input.goods_rack[i].goods.id);
                    formData.append('goods_rack[' + i + '][stock]',this.input.goods_rack[i].stock);
                }

                
                formData.append('_method', 'patch');

            }
            else //jika sedang add
            {

                //data-data yang harus dikirim : 
                //1. semua data racks
                //2. semua data goods_rack

                //step-step : 
                //1. kirim data rack
                formData.append('name', this.input.name);
                formData.append('warehouse_id', this.input.warehouse_id);

                //2. kirim data goods_rack

               

                for(var i = 0;i<this.input.goods_rack.length;i++)
                {
                    formData.append('goods_rack[' + i + '][attribute_id]',this.input.goods_rack[i].goods.id);
                    formData.append('goods_rack[' + i + '][stock]',this.input.goods_rack[i].stock);

                }

            }

            
            

            //4. masukan token
            formData.append('token', localStorage.getItem('token'));
            return formData;
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
    mixins:[
        mxCrudChildForm,
    ]
}
</script>

