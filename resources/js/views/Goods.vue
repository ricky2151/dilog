<div>
    <v-container fluid>
        <h3>goods</h3>
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


        <v-dialog v-model="dialog_createedit">
            <v-form v-model="valid" ref='formCreateEdit'>
                <v-card>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html='id_data_edit == -1 ?"Add Goods":"Edit Goods"'></v-toolbar-title>

                    </v-toolbar>
                    <v-stepper v-model="e6" vertical >

                        <!-- ==== STEPPER 1 ==== -->

                        <v-stepper-step :complete="e6 > 1" step="1" >
                            <h3>Goods Data</h3>
                        </v-stepper-step>

                        <v-stepper-content step="1" editable='id_data_edit != -1'>
                            
                            <v-layout row>
                                <v-flex xs9>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.name'  label="Name" counter=191></v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.code' label="Code" counter=191 required></v-text-field>
                                </v-flex>
                            </v-layout>
                            
                            <v-layout row>
                                <v-flex xs12>
                                    <v-textarea
                                      label="Description"
                                      v-model='input.desc'
                                      :rules='this.$list_validation.max'
                                    ></v-textarea>
                                    
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.numeric_req" v-model='input.status' label="Status" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs6>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.numeric_req" v-model='input.value' label="Value" required></v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.numeric" v-model='input.last_buy_pricelist' label="Last Buy Price List" required></v-text-field>
                                </v-flex>
                            </v-layout>    

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max" v-model='input.barcode_master' label="Barcode" required></v-text-field>
                                </v-flex>
                            </v-layout>
                                
                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field v-model='input.thumbnail_filename' label="Select Image" v-on:click='pickFile' prepend-icon='attach_file'></v-text-field>
                                    <input
                                        id='btn_upload_thumbnail'
                                        type="file"
                                        style="display: none"
                                        accept="image/*"
                                        v-on:change='changeImage'
                                    >
                                    <img :src="preview.thumbnail" height="150" v-if="preview.thumbnail"/>
                                </v-flex>
                            </v-layout>
                                
                                
                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' :rules="this.$list_validation.selecttf_req" v-model='input.avgprice_status' :items="ref_input.avgprice_status" item-text='name' item-value='value' label="Select Average Price Status"></v-select>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs6>
                                    <v-text-field class='pa-2' :rules="this.$list_validation.numeric" v-model='input.avgprice' label="AVG Price" required></v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field class='pa-2' :rules="this.$list_validation.numeric" v-model='input.tax' label="Tax" required></v-text-field>
                                </v-flex>
                            </v-layout>


                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' :rules="this.$list_validation.selectdata_req"  v-model='input.unit_id' :items="ref_input.unit" item-text='name' item-value='id' label="Select Unit"></v-select>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' :rules="this.$list_validation.selectdata_req" v-model='input.cogs_id' :items="ref_input.cogs" item-text='name' item-value='id' label="Select COGS"></v-select>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.numeric_req" v-model='input.margin' label="Margin" required></v-text-field>
                                </v-flex>
                            </v-layout>
                            

                            <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                              
                                
                            
                        </v-stepper-content>



                        <!-- ==== STEPPER 2 ==== -->

                        <v-stepper-step :complete="e6 > 2" step="2"><h3>Price Selling</h3></v-stepper-step>

                        <v-stepper-content step="2">


                            <v-select v-model='temp_input.price_sellings.warehouse' :items="ref_input.warehouse" item-text='name' return-object label="Select Warehouse"></v-select>

                            <v-text-field v-model="temp_input.price_sellings.stock_cut_off" label="Stock Cut Off" required></v-text-field>

                            <v-select v-model='temp_input.price_sellings.categorypriceselling' :items="ref_input.categorypriceselling" item-text='name' return-object label="Select Category Price Selling"></v-select>
    
                            <v-text-field v-model="com_price" disabled label="Price" required></v-text-field> 
                            <!-- <label>{{com_price}}</label> -->
                            <v-select v-model='temp_input.price_sellings.free' :items="ref_input.free" item-text='name' return-object label="Free"></v-select>

                            {{temp_input.price_sellings.price}}
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_price_sellings != -1' color="red" dark v-on:click='table_priceselling().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_priceselling().save()' v-html='temp_input.id_edit_price_sellings == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>Price Sellings Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'No', value:'no'},
                                {text:'Warehouse',value:'warehouse'},
                                {text:'Stock Cut Off',value:'stock_cut_off'},
                                {text:'Category',value:'category'},
                                {text:'Price',value:'price'},
                                {text:'Free',value:'free'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.price_sellings"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.index + 1 }}</td>
                                    <td>{{ props.item.warehouse.name }}</td>
                                    <td>{{ props.item.stock_cut_off }}</td>
                                    <td>{{ props.item.categorypriceselling.name }}</td>
                                    <td>{{ props.item.price }}</td>
                                    <td>{{ props.item.free.name }}</td>
                                    <td>
                                        <v-btn class='button-action' v-on:click='table_priceselling().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_priceselling().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>
                            <v-btn color='primary' v-on:click='e6=3'>Continue</v-btn>
                            <v-btn color='gray' v-on:click='e6=1'>Back</v-btn>

                        </v-stepper-content>



                        <!-- ==== STEPPER 3 ==== -->

                        <v-stepper-step :complete="e6 > 3" step="3"><h3>Goods Category</h3></v-stepper-step>

                        <v-stepper-content step="3">
                            <v-combobox
                                v-model='input.category_goods'
                                :items="ref_input.category"
                                item-value="id"
                                item-text="name"
                                label="Type or select goods category"
                                chips
                                clearable
                                prepend-icon="filter_list"
                                solo
                                multiple
                                v-on:keyup.enter="checkItemInList()"
                                >
                                    <template v-slot:selection="data">
                                        <v-chip
                                          :selected="data.selected"
                                          close
                                          v-on:input="table_category().removeChip(data.item)"
                                        >
                                            <strong>{{ data.item.name }}</strong>
                                        </v-chip>
                                    </template>
                            </v-combobox>
                            <v-btn color='primary' v-on:click='e6=4'>Continue</v-btn>
                            <v-btn color='gray' v-on:click='e6=2'>Back</v-btn>
                        </v-stepper-content>

                        <!-- ==== STEPPER 4 ==== -->

                        <v-stepper-step :complete="e6 > 4" step="4" ><h3>Goods Attribute</h3></v-stepper-step>

                        <v-stepper-content step="4">


                            <v-select v-model='temp_input.attribute_goods.attribute' :items="ref_input.attribute" item-text='name' return-object label="Select Attribute"></v-select>
                            


                            <v-text-field v-model="temp_input.attribute_goods.value" label="Value" required></v-text-field>
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_attribute_goods != -1' color="red" dark v-on:click='table_attribute().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_attribute().save()' v-html='temp_input.id_edit_attribute_goods == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>Goods Attribute Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'Attribute', value:'attribute'},
                                {text:'Values',value:'value',align:'right'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.attribute_goods"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.attribute.name }}</td>
                                    <td class="text-xs-right">{{ props.item.value }}</td>
                                    <td>
                                        <v-btn class='button-action' v-on:click='table_attribute().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_attribute().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>
                            <v-btn color='primary' v-on:click='e6=5'>Continue</v-btn>
                            <v-btn color='gray' v-on:click='e6=3'>Back</v-btn>

                        </v-stepper-content>

                        <!-- ==== STEPPER 5 ==== -->

                        <v-stepper-step step="5" :complete="e6 > 5" ><h3>Goods Material</h3></v-stepper-step>

                        <v-stepper-content step="5">
                            <!-- <v-select v-model='temp_input.material_goods.name' :items="ref_input.material" item-text='name' return-object label="Select Material"></v-select> -->
                            
                            <v-text-field v-model="temp_input.material_goods.name" label="Name" required></v-text-field>

                            <v-text-field v-model="temp_input.material_goods.total" label="Total" required></v-text-field>

                            <v-text-field v-model="temp_input.material_goods.adjust" label="Adjust" required></v-text-field>
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_material_goods != -1' color="red" dark v-on:click='table_material().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_material().save()' v-html='temp_input.id_edit_material_goods == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>Goods Material Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'Material', value:'name'},
                                {text:'Total',value:'total',align:'right'},
                                {text:'Adjust',value:'adjust',align:'right'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.material_goods"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.name }}</td>
                                    <td class="text-xs-right">{{ props.item.total }}</td>
                                    <td class="text-xs-right">{{ props.item.adjust }}</td>
                                    <td>
                                        <v-btn class='button-action' v-on:click='table_material().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_material().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>
                            <v-btn color='primary' v-on:click='e6=6'>Continue</v-btn>
                            <v-btn color='gray' v-on:click='e6=4'>Back</v-btn>
                        </v-stepper-content>

                        <!-- ==== STEPPER 6 ==== -->

                        <v-stepper-step :complete="e6 > 6" step="6"><h3>Price List</h3></v-stepper-step>

                        <v-stepper-content step="6">


                            <v-select v-model='temp_input.pricelists.supplier' :items="ref_input.supplier" item-text='name_company' return-object label="Select Supplier"></v-select>
                            


                            <v-text-field v-model="temp_input.pricelists.price" label="Price" required></v-text-field>
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_pricelists != -1' color="red" dark v-on:click='table_pricelist().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_pricelist().save()' v-html='temp_input.id_edit_pricelists == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>Pricelist Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'Supplier', value:'supplier'},
                                {text:'Price',value:'price',align:'right'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.pricelists"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.supplier.name_company }}</td>
                                    <td class="text-xs-right">{{ props.item.price }}</td>
                                    <td>
                                        <v-btn class='button-action' v-on:click='table_pricelist().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_pricelist().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>
                            
                            <v-btn color='gray' v-on:click='e6=5'>Back</v-btn>
                        </v-stepper-content>
                        
                        <v-btn v-on:click='save_data()' >submit</v-btn>

                        
                        
                        
                    </v-stepper>
                </v-card>
            </v-form>
        </v-dialog>

        <v-layout row class='bgwhite margintop10'>
            <v-flex xs6>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>widgets</v-icon>
                    <h2 class='titledatatable'>Goods Data</h2>
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
            <td class="text-xs-right">{{ props.item.code }}</td>
            <td class="text-xs-right">{{ props.item.value }}</td>
            <td class="text-xs-right">{{ props.item.status }}</td>
            <td class="text-xs-right">{{ props.item.last_buy_pricelist }}</td>
            <td class="text-xs-right">{{ props.item.avg_price }}</td>
            <td class="text-xs-right">{{ props.item.stock }}</td>
            <td class="text-xs-right">{{ props.item.avg_price * props.item.stock }}</td>
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
    errorCaptured (err, vm, info) {
    this.error = `${err.stack}\n\nfound in ${info} of component`
    return false
    },

    components:{
        
    },
    data () {
        return {
            info_table:{},

            name_table:'goods',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'multipart/form-data'
            },

            action_items: ['Edit', 'Rack', 'SP', 'Stock Card', 'Supplier', 'COGS', 'Delete'],
            
            

            valid:false,
            on:false,

            dialog_createedit:false,
            dialog_detailracks:false,
            dialog_detailsellingprices:false,
            dialog_detailpricelists:false,
            dialog_detailstockcards:false,

            e6:1,

            id_data_edit:-1,

            preview:{
                thumbnail:'',
            },
            input_before_edit:{ //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
                
            },
            input:{
                name:'',
                code:'',
                desc:'',
                margin:'',
                value:'',
                status:'',
                last_buy_pricelist:'',
                barcode_master:'',
                thumbnail_filename:'', //tidak ikut dikirim ke server (cuman muncul di form)
                thumbnail_file:'', //ini akan dikirim ke server
                avgprice_status:'',
                avgprice:'',
                tax:'',
                unit_id:'', 
                cogs_id:'',
                category_goods:[],
                attribute_goods:[],
                material_goods:[],
                price_sellings:[],
                pricelists:[],
            },

            temp_input:{
                id_edit_attribute_goods:-1, //artinya add data,
                id_edit_material_goods:-1, //artinya add data,
                id_edit_price_sellings:-1, //artinya add data
                id_edit_pricelists:-1, //artinya add data
                attribute_goods:
                {
                    attribute:
                    {
                        id:null,
                        name:null,
                    },
                    value:null,
                },
                material_goods:
                {
                   
                    total:null,
                    adjust:null,
                },
                price_sellings:
                {
                    warehouse:
                    {
                        id:null,
                        name:null
                    },
                    stock_cut_off:null,
                    categorypriceselling:
                    {
                        id:null,
                        name:null
                    },
                    price:null,
                    free:null,
                },
                pricelists:
                {
                    supplier:
                    {
                        id:null,
                        name_company:null,
                    },
                    price:null,
                },
            },


            ref_input:
            {
                user:[
                    {id:6,name:'ricky'},
                    {id:7,name:'roy'},
                    {id:8,name:'kevin'},
                    {id:9,name:'thomas'},
                ],
                unit:[
                    {id:6,name:'kilogram'},
                    {id:7,name:'ton'},
                    {id:8,name:'pcs'},
                    {id:9,name:'cm'},
                ],
                cogs:[
                    {id:6,name:'HPP Penjualan'},
                    {id:7,name:'HPP Pembelian'},
                    {id:8,name:'HPP xxxx'},
                    {id:9,name:'HPP yyyyy'},
                ],
                category:[
                    {id:5,name:'kayu'},
                    {id:4,name:'batang'},
                    {id:3,name:'piring'},
                ],
                attribute:[
                    {id:5,name:'Warna'},
                    {id:4,name:'Ukuran'},
                    {id:3,name:'Diameter'},
                ],
                material:[
                    {id:5,name:'piring cantik'},
                    {id:4,name:'payung cantik'},
                    {id:3,name:'gelas cantik'},
                ],
                avgprice_status:[
                    {value:1,name:'True'},
                    {value:0,name:'False'},
                ],
                free:[
                    {value:1,name:'True'},
                    {value:2,name:'False'},
                ],
                warehouse:[
                    {id:3,name:'coba warehouse1'},
                    {id:4,name:'coba warehouse2'},
                ],
                categorypriceselling:[
                    {id:3,name:'coba categorypriceselling1'},
                    {id:4,name:'coba categorypriceselling2'},
                ],
                supplier:[
                    {id:3,name_company:'Borobudur Silver'},
                    {id:4,name_company:'Cocoon'},
                ]

            },
            

            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Code', value: 'code', align:'right' },
                { text: 'Value', value: 'value', align:'right' },
                { text: 'Status', value: 'status', align:'right' },
                { text: 'Last Buy Pricelist', value: 'last_buy_pricelist', align:'right' },
                { text: 'AVGPrice', value: 'avgprice', align:'right' },
                { text: 'Stock', value: 'stock', align:'right' },
                { text: 'Inventory Value', value: 'inventory_value', align:'right' },
                { text: 'Action', align:'left',sortable:false, width:'15%'},
            ],

            


            data_table:[],
            search_data: null,

            
        }
    },
    computed: {
        com_last_buy_pricelist()
        {
            return this.input.last_buy_pricelist;
        },
        com_margin()
        {
            return this.input.margin;
        },
        com_price()
        {
            return this.strToPrice((parseInt(this.input.margin) + parseInt(this.input.last_buy_pricelist)).toString(), "Rp. ") ; 
            
        },


    },
    watch: {
        com_last_buy_pricelist()
        {

            this.temp_input.price_sellings.price = parseInt(this.input.last_buy_pricelist) + parseInt(this.input.margin);
        },
        com_margin()
        {
            this.temp_input.price_sellings.price = parseInt(this.input.last_buy_pricelist) + parseInt(this.input.margin);
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
                this.debugLog('nol')
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
        table_pricelist()
        {
            var self = this;
            return{
                showData(idx){ 
                    self.temp_input.pricelists = JSON.parse(JSON.stringify(self.input.pricelists[idx]));
                    self.temp_input.id_edit_pricelists = idx;
                },
                clearTempInput(){
                    for (var key in self.temp_input.pricelists)
                    {
                        if(self.temp_input.pricelists[key])
                            self.temp_input.pricelists[key] = null;
                    }
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_pricelists));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.pricelists));
                    
                        self.input.pricelists.push(temp);
                        
                    }
                    else
                    {
                        self.input.pricelists[id_edit] = JSON.parse(JSON.stringify(self.temp_input.pricelists));
                        self.temp_input.id_edit_pricelists = -1;

                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_pricelists = -1;
                },
                delete(idx)
                {
                    self.input.pricelists.splice(idx,1);
                }



            }
        },
        table_priceselling()
        {
            var self = this;
            return{
                showData(idx){ 
                    self.temp_input.price_sellings = JSON.parse(JSON.stringify(self.input.price_sellings[idx]));
                    self.temp_input.id_edit_price_sellings = idx;
                },
                clearTempInput(){
                    for (var key in self.temp_input.price_sellings)
                    {

                        if(self.temp_input.price_sellings[key] && key != "price")
                            self.temp_input.price_sellings[key] = null;
                    }
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_price_sellings));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.price_sellings));
                    
                        self.input.price_sellings.push(temp);
                        
                    }
                    else
                    {
                        self.input.price_sellings[id_edit] = JSON.parse(JSON.stringify(self.temp_input.price_sellings));
                        self.temp_input.id_edit_price_sellings = -1;

                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_price_sellings = -1;
                },
                delete(idx)
                {
                    self.input.price_sellings.splice(idx,1);
                }



            }
        },

        table_attribute()
        {
            var self = this;
            return{
                showData(idx){ 
                    self.temp_input.attribute_goods = JSON.parse(JSON.stringify(self.input.attribute_goods[idx]));
                    self.temp_input.id_edit_attribute_goods = idx;
                },
                clearTempInput(){
                    for (var key in self.temp_input.attribute_goods)
                    {
                        if(self.temp_input.attribute_goods[key])
                            self.temp_input.attribute_goods[key] = null;
                    }
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_attribute_goods));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.attribute_goods));
                    
                        self.input.attribute_goods.push(temp);
                        
                    }
                    else
                    {
                        self.input.attribute_goods[id_edit] = JSON.parse(JSON.stringify(self.temp_input.attribute_goods));
                        self.temp_input.id_edit_attribute_goods = -1;

                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_attribute_goods = -1;
                },
                delete(idx)
                {
                    self.input.attribute_goods.splice(idx,1);
                }



            }
        },

        table_material()
        {
            var self = this;
            return{

                
                showData(idx){
                    
                    self.temp_input.material_goods = JSON.parse(JSON.stringify(self.input.material_goods[idx]));
                    self.temp_input.id_edit_material_goods = idx;
                },
                clearTempInput(){

                   
                    for (var key in self.temp_input.material_goods)
                    {
                        if(self.temp_input.material_goods[key])
                            self.temp_input.material_goods[key] = null;
                    }
                    
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_material_goods));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.material_goods));
                        console.log(temp);
                        self.input.material_goods.push(temp);
                        
                    }
                    else
                    {
                        
                        self.input.material_goods[id_edit] = JSON.parse(JSON.stringify(self.temp_input.material_goods));
                        
                        self.temp_input.id_edit_material_goods = -1;


                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_material_goods = -1;
                },
                delete(idx)
                {
                    self.input.material_goods.splice(idx,1);
                }



            }
        },

        table_category()
        {
            var self = this;
            return{
                removeChip(item){
                    self.input.category_goods.splice(self.input.category_goods.indexOf(item), 1);
                    self.input.category_goods = [...self.input.category_goods];
                },
            }
        },

        
        checkItemInList(){
            var temp = this.input.category_goods;
            if (!temp[temp.length - 1].hasOwnProperty('id')) //artinya cuman asal enter, tanpa ambil item dari ref_input
            {
                this.table_category().removeChip(temp[temp.length - 1]);
            }
        },


        changeImage(e){
            const files = e.target.files;

            if(files[0] !== undefined) {
                console.log(files[0].size);
                if(((files[0].size / 1024) / 1024) < 2)
                {
                    
                    
                    
                    this.input.thumbnail_filename = files[0].name;

                    const fr = new FileReader ()
                    fr.readAsDataURL(files[0])
                    fr.addEventListener('load', () => {
                        this.preview.thumbnail = fr.result; //jadi preview
                        this.input.thumbnail_file = files[0]; //yang dikirim ke server
                    })
                    
                }
                else
                {
                    
                    swal("File is to Big", "Pleas uload file with size < 2 MB !", "error");
                }
            } else {
                swal("Your file is empty !", "Please Upload Your File !", "error");
                
            }
        },
        pickFile () {
            var el = document.getElementById('btn_upload_thumbnail').click();
        },


        
        
        


        


        clear_input(){
            this.$refs.formCreateEdit.resetValidation();
            this.preview.thumbnail = '';
            for (var key in this.input)
            {
                if(this.input[key])
                {
                    if(Array.isArray(this.input[key]))
                    {
                        this.input[key] = [];     
                    }
                    else
                    {
                        this.input[key] = "";
                    }
                    
                    
                }
                    
            }
            //this.$refs.formCreateEdit.reset();

        },
        
        showTable(r)
        {
            //console.log(r.data.items.goods[0]);

            this.data_table = r.data.items.goods;

            
            
        },
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items[0].units);
            console.log(r.data.items.units);
            this.ref_input.unit = r.data.items.units;
            this.ref_input.cogs = r.data.items.cogs;
            this.ref_input.category = r.data.items.categories;
            this.ref_input.attribute = r.data.items.attributes;
            this.ref_input.warehouse = r.data.items.warehouses;
            this.ref_input.categorypriceselling = r.data.items.category_price_sellings;
            //hapus yang tidak penting
            delete r.data.items.suppliers.name_owner;
            delete r.data.items.suppliers.name_pic;
            delete r.data.items.suppliers.name_sales;
            this.ref_input.supplier = r.data.items.suppliers;
            //this.ref_input.material = r.data.items[0].materials;
        },
        convert_data_input(r)
        {
            var temp_r = r.data.items.goods;
            this.input.name = temp_r.name;
            this.input.code = temp_r.code;
            this.input.desc = temp_r.desc;
            this.input.margin = temp_r.margin;
            this.input.value = temp_r.value;
            this.input.status = temp_r.status;
            this.input.last_buy_pricelist = temp_r.last_buy_pricelist;
            this.input.barcode_master = temp_r.barcode_master;
            this.input.avgprice_status = temp_r.avg_price_status;
            this.input.avgprice = temp_r.avg_price;
            this.input.tax = temp_r.tax;
            this.input.unit_id = temp_r.unit_id;
            this.input.cogs_id = temp_r.cogs_id;
            this.preview.thumbnail = temp_r.thumbnail;

            console.log('testing convert_data_input_goods');
            console.log(temp_r);
            this.input.category_goods = temp_r.category_goods;

            for(var i = 0;i<temp_r.attribute_goods.length;i++)
            {  
                this.input.attribute_goods.push({
                    attribute:{
                        id: temp_r.attribute_goods[i].id,
                        name: temp_r.attribute_goods[i].name,
                    },
                    value:temp_r.attribute_goods[i].value,
                })
            }

            for(var i = 0;i<temp_r.price_sellings.length;i++)
            {  
                var temp_name_free = '';
                if(temp_r.price_sellings[i].free == 1)
                {
                    temp_name_free = 'true';
                }
                else
                {
                    temp_name_free = 'false';
                }
                this.input.price_sellings.push({
                    id:temp_r.price_sellings[i].id,
                    warehouse:{
                        id: temp_r.price_sellings[i].warehouse_id,
                        name: temp_r.price_sellings[i].warehouse_name,
                    },
                    stock_cut_off:temp_r.price_sellings[i].stock_cut_off,
                    categorypriceselling:
                    {
                        id: temp_r.price_sellings[i].category_price_selling_id,
                        name: temp_r.price_sellings[i].category_price_selling_name,
                    },
                    price:temp_r.price_sellings[i].price,
                    free:
                    {
                        value: temp_r.price_sellings[i].free,
                        name:temp_name_free,
                    }
                })
            }

            for(var i = 0;i<temp_r.pricelists.length;i++)
            {  
                this.input.pricelists.push({
                    supplier:{
                        id: temp_r.pricelists[i].id,
                        name_company: temp_r.pricelists[i].name_company,
                    },
                    price:temp_r.pricelists[i].price,
                })
            }


            this.input.material_goods = temp_r.material_goods;

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.id_data_edit != -1) //jika sedang diedit
            {

                //data yang harus dikirim saat update :
                //1. data goods yang BERUBAH SAJA
                //2. data goods_attribute dan goods_category AKHIR 
                //3. data material yang BERUBAH, DITAMBAH, & DIHAPUS
                //4. jika thumbnail awal ada, lalu thumbnail akhir kosong (user menghapus gambar thumbnail), maka tambahin data is_image_deleted = true, jika tidak is_image_deleted = false

                //step-step :
                //1. kirim data goods yang berubah
                if(this.input.name != this.input_before_edit.name) formData.append('name', this.input.name);
                if(this.input.code != this.input_before_edit.code) formData.append('code', this.input.code);
                if(this.input.desc != this.input_before_edit.desc) formData.append('desc', this.input.desc);
                if(this.input.margin != this.input_before_edit.margin) formData.append('margin', this.input.margin);
                if(this.input.value != this.input_before_edit.value) formData.append('value', this.input.value);
                if(this.input.status != this.input_before_edit.status) formData.append('status', this.input.status);
                if(this.input.last_buy_pricelist != this.input_before_edit.last_buy_pricelist) formData.append('last_buy_pricelist', this.input.last_buy_pricelist);
                if(this.input.barcode_master != this.input_before_edit.barcode_master) formData.append('barcode_master', this.input.barcode_master);
                if(this.input.thumbnail_file != this.input_before_edit.thumbnail_file) formData.append('thumbnail', this.input.thumbnail_file); 
                if(this.input.avgprice_status != this.input_before_edit.avgprice_status) formData.append('avgprice_status', this.input.avgprice_status);
                if(this.input.avgprice != this.input_before_edit.avgprice) formData.append('avgprice', this.input.avgprice);
                if(this.input.tax != this.input_before_edit.tax) formData.append('tax', this.input.tax);
                if(this.input.unit_id != this.input_before_edit.unit_id) formData.append('unit_id', this.input.unit_id);
                if(this.input.cogs_id != this.input_before_edit.cogs_id) formData.append('cogs_id', this.input.cogs_id);

                //2. kirim data goods_attribute, goods_category, pricelist
                for(var i = 0;i<this.input.category_goods.length;i++)
                {
                    formData.append('category_goods[' + i + '][category_id]',this.input.category_goods[i].id);
                }

                for(var i = 0;i<this.input.attribute_goods.length;i++)
                {
                    console.log(this.input.attribute_goods[i]);
                    formData.append('attribute_goods[' + i + '][attribute_id]',this.input.attribute_goods[i].attribute.id);
                    formData.append('attribute_goods[' + i + '][value]',this.input.attribute_goods[i].value);

                }

                for(var i = 0;i<this.input.pricelists.length;i++)
                {
                    
                    formData.append('pricelists[' + i + '][supplier_id]',this.input.pricelists[i].supplier.id);
                    formData.append('pricelists[' + i + '][price]',this.input.pricelists[i].price);

                }

                //3. kirim data material yang berubah, ditambah, dan dihapus
                
                //cek di input cocokin dengan input_before_edit
                //1. cek apakah ada id nya atau tidak, jika tidak memiliki id, pasti itu tambah baru
                //2. jika punya id, cocokan dengan input_before_edit, jika sama berarti tidak diedit, jika beda berarti diedit

                //temp adalah data dari input
                //temp2 adalah data dari input_before_edit
                var counteridx = 0;
                for(var i = 0;i<this.input.material_goods.length;i++)
                {
                    var temp = this.input.material_goods[i];
                    if(temp.id == null)
                    {
                        formData.append('material_goods[' + counteridx + '][name]', temp.name);
                        formData.append('material_goods[' + counteridx + '][adjust]', temp.adjust);
                        formData.append('material_goods[' + counteridx + '][total]', temp.total);
                        formData.append('material_goods[' + counteridx + '][type]', '1');
                        counteridx++;
                    }
                    else
                    {
                        //cocokan dengan input_before_edit
                        var edittrue = false;
                        for(var j = 0;j<this.input_before_edit.material_goods.length;j++)
                        {
                            var temp2 = this.input_before_edit.material_goods[i];
                            if(temp.id == temp2.id)
                            {
                                if(temp.name != temp2.name || temp.adjust != temp2.adjust || temp.total != temp2.total) //jika ada salah satu saja yang berbeda, maka ini pasti diedit
                                {
                                    edittrue = true;
                                }
                                break;
                            }
                        }

                        if(edittrue)
                        {
                            formData.append('material_goods[' + counteridx + '][id]', temp.id);
                            formData.append('material_goods[' + counteridx + '][name]', temp.name);
                            formData.append('material_goods[' + counteridx + '][adjust]', temp.adjust);
                            formData.append('material_goods[' + counteridx + '][total]', temp.total);
                            formData.append('material_goods[' + counteridx + '][type]', '0');
                            counteridx++;
                        }

                    }
                }

                //cek di input_before_edit cocokin dengan input
                //1. jika ada data dengan id yang tidak ada di data input, berarti data tersebut pasti dihapus
                for(var i = 0;i<this.input_before_edit.material_goods.length;i++)
                {
                    var deletetrue = true;
                    for(var j=0;j<this.input.material_goods.length;j++)
                    {
                        if(this.input.material_goods[j].id == this.input_before_edit.material_goods[i].id)
                        {
                            deletetrue = false;
                            break;
                        }
                    }

                    if(deletetrue)
                    {
                        formData.append('material_goods[' + counteridx + '][id]', this.input_before_edit.material_goods[i].id);
                        formData.append('material_goods[' + counteridx + '][type]', '-1');
                        counteridx++;
                    }
                }

                
                //4. kirim data price selling yang berubah, ditambah, dan dihapus
                
                //cek di input cocokin dengan input_before_edit
                //1. cek apakah ada id nya atau tidak, jika tidak memiliki id, pasti itu tambah baru
                //2. jika punya id, cocokan dengan input_before_edit, jika sama berarti tidak diedit, jika beda berarti diedit

                //temp adalah data dari input
                //temp2 adalah data dari input_before_edit
                var counteridx = 0;
                for(var i = 0;i<this.input.price_sellings.length;i++)
                {
                    var temp = this.input.price_sellings[i];
                    if(temp.id == null)
                    {
                        formData.append('price_sellings[' + counteridx + '][warehouse_id]', temp.warehouse.id);
                        formData.append('price_sellings[' + counteridx + '][stock_cut_off]', temp.stock_cut_off);
                        formData.append('price_sellings[' + counteridx + '][category_price_selling_id]', temp.categorypriceselling.id);
                        formData.append('price_sellings[' + counteridx + '][price]', temp.price);
                        formData.append('price_sellings[' + counteridx + '][free]', temp.free.value);
                        formData.append('price_sellings[' + counteridx + '][type]', '1');
                        counteridx++;
                    }
                    else
                    {
                        //cocokan dengan input_before_edit
                        var edittrue = false;
                        for(var j = 0;j<this.input_before_edit.price_sellings.length;j++)
                        {
                            var temp2 = this.input_before_edit.price_sellings[i];
                            if(temp.id == temp2.id)
                            {
                                if(temp.warehouse.id != temp2.warehouse.id || temp.stock_cut_off != temp2.stock_cut_off || temp.categorypriceselling.id != temp2.categorypriceselling.id || temp.price != temp2.price || temp.free != temp2.free) //jika ada salah satu saja yang berbeda, maka ini pasti diedit
                                {
                                    edittrue = true;
                                }
                                break;
                            }
                        }

                        if(edittrue)
                        {
                            formData.append('price_sellings[' + counteridx + '][id]', temp.id);
                            formData.append('price_sellings[' + counteridx + '][warehouse_id]', temp.warehouse.id);
                            formData.append('price_sellings[' + counteridx + '][stock_cut_off]', temp.stock_cut_off);
                            formData.append('price_sellings[' + counteridx + '][category_price_selling_id]', temp.categorypriceselling.id);
                            formData.append('price_sellings[' + counteridx + '][price]', temp.price);
                            formData.append('price_sellings[' + counteridx + '][free]', temp.free.value);
                            formData.append('price_sellings[' + counteridx + '][type]', '0');
                            counteridx++;
                        }

                    }
                }

                //cek di input_before_edit cocokin dengan input
                //1. jika ada data dengan id yang tidak ada di data input, berarti data tersebut pasti dihapus
                for(var i = 0;i<this.input_before_edit.price_sellings.length;i++)
                {
                    var deletetrue = true;
                    for(var j=0;j<this.input.price_sellings.length;j++)
                    {
                        if(this.input.price_sellings[j].id == this.input_before_edit.price_sellings[i].id)
                        {
                            deletetrue = false;
                            break;
                        }
                    }

                    if(deletetrue)
                    {
                        formData.append('price_sellings[' + counteridx + '][id]', this.input_before_edit.price_sellings[i].id);
                        formData.append('price_sellings[' + counteridx + '][type]', '-1');
                        counteridx++;
                    }
                }



                //5. tambahin is_image_deleted
                if(this.input.thumbnail_file.length > 0 && this.input_before_edit.thumbnail_file == null)
                {
                    formData.append('is_image_delete', '1');
                }
                else
                {
                    formData.append('is_image_delete', '0');   
                }
                formData.append('_method', 'patch');

            }
            else //jika sedang add
            {

                //data-data yang harus dikirim : 
                //1. semua data goods
                //2. semua data attribute,category, dan material

                //step-step : 
                //1. kirim data goods
                formData.append('name', this.input.name);
                formData.append('code', this.input.code);
                formData.append('desc', this.input.desc);
                formData.append('margin', this.input.margin);
                formData.append('value', this.input.value);
                formData.append('status', this.input.status);
                formData.append('last_buy_pricelist', this.input.last_buy_pricelist);
                formData.append('barcode_master', this.input.barcode_master);
                formData.append('thumbnail', this.input.thumbnail_file); 
                formData.append('avgprice_status', this.input.avgprice_status);
                formData.append('avgprice', this.input.avgprice);
                formData.append('tax', this.input.tax);
                formData.append('unit_id', this.input.unit_id);
                formData.append('cogs_id', this.input.cogs_id);

                //2. kirim data attribute,category, dan material

                for(var i = 0;i<this.input.material_goods.length;i++)
                {
                    formData.append('material_goods[' + i + '][adjust]',this.input.material_goods[i].adjust);
                    formData.append('material_goods[' + i + '][total]',this.input.material_goods[i].total);
                    formData.append('material_goods[' + i + '][name]',this.input.material_goods[i].name);

                }

                for(var i = 0;i<this.input.category_goods.length;i++)
                {
                    formData.append('category_goods[' + i + '][category_id]',this.input.category_goods[i].id);
                }

                for(var i = 0;i<this.input.attribute_goods.length;i++)
                {
                    formData.append('attribute_goods[' + i + '][attribute_id]',this.input.attribute_goods[i].attribute.id);
                    formData.append('attribute_goods[' + i + '][value]',this.input.attribute_goods[i].value);

                }

            }

            
            

            //4. masukan token
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },
        
        
        

       

        get_popup_detailracks(id_edit_popup_detailracks){
            axios.get('api/goods/' + id_edit_popup_detailracks + '/racks',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailracks = r.data.items.goods;
            })
        },


        get_popup_detailsellingprices(id_edit_popup_detailsellingprices){
            axios.get('api/goods/' + id_edit_popup_detailsellingprices + '/sellingPrices',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailsellingprices = r.data.items.goods;
            })
        },


        get_popup_detailstockcards(id_edit_popup_detailstockcards){
            axios.get('api/goods/' + id_edit_popup_detailstockcards + '/racks',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailstockcards = r.data.items.goods;
            })
        },


        get_popup_detailpricelists(id_edit_popup_detailpricelists){
            axios.get('api/goods/' + id_edit_popup_detailpricelists + '/pricelists',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailpricelists = r.data.items.goods;
            })
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
            this.input.avgprice = 100000;
            this.input.tax = '180';
            this.input.unit_id = '1';
            this.input.cogs_id = '2';


        }


    },
    mounted(){
        
        this.get_data();
        this.get_master_data();
        this.strToPrice("9000");
        this.name_table = "goods";

        this.info_table = this.database[this.name_table];
        
        
        //this.testing_input();

    },
    mixins:[
        mxCrudChildForm,
    ],
}
</script>

