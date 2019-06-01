

<template>
    <div>
        
        <v-dialog v-model="dialog_createedit" fullscreen>
            <v-form v-model="valid" ref='formCreateEdit' class='fixfullscreen'>
                <v-card class='fixfullscreen'>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html='idx_data_edit == -1 ?"Add Goods Rack":"Edit Goods Rack"'></v-toolbar-title>

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

                        <!-- ==== STEPPER 2 ==== -->

                        <v-stepper-step :complete="e6 > 2" step="2" editable><h3>Goods Price Selling</h3></v-stepper-step>

                        <v-stepper-content step="2">

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" v-model='temp_input.price_sellings.stock_cut_off' label="Stock Cut Off" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' v-model='temp_input.price_sellings.category_price_sellings' :items="ref_input.category_price_sellings" item-text='name' return-object label="Select Category Price Selling"></v-select>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" v-model='temp_input.price_sellings.price' label="Price" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" v-model='temp_input.price_sellings.discount' label="Discount" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2' v-model='temp_input.price_sellings.free' :items="ref_input.free" item-text='name' return-object label="Free (True / False)"></v-select>
                                </v-flex>
                            </v-layout>
                           
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_price_selling != -1' color="red" dark v-on:click='table_price_selling().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_price_selling().save()' v-html='temp_input.id_edit_price_selling == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>Price Selling Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'Stock Cut Off', value:'stock_cut_off'},
                                {text:'Category Price Selling', value:'category_price_sellings'},
                                {text:'Price',value:'price'},
                                {text:'Discount',value:'discount'},
                                {text:'Free',value:'free'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.price_sellings"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.stock_cut_off }}</td>
                                    <td>{{ props.item.category_price_sellings.name }}</td>
                                    <td>{{ props.item.price }}</td>
                                    <td>{{ props.item.discount }}</td>
                                    <td>{{ props.item.free.name }}</td>

                                    <td>
                                        <v-btn class='button-action' v-on:click='table_price_selling().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_price_selling().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>
                            <v-btn color='primary' v-on:click='e6=3'>Continue</v-btn>
                            {{input}}
                        </v-stepper-content>

                        <!-- ==== STEPPER 3 ==== -->

                        <v-stepper-step :complete="e6 > 3" step="3" editable><h3>Batch Data</h3></v-stepper-step>

                        <v-stepper-content step="3">


                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" v-model='temp_input.batch.stock' label="Stock Batch" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" v-model='temp_input.batch.batch_number' label="Batch Number" required></v-text-field>
                                    
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-select class='pa-2'  v-model='temp_input.batch.sources' :items="ref_input.sources" item-text='name' return-object label="Select Sources"></v-select>
                                </v-flex>
                            </v-layout>

                            
                           
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_batch != -1' color="red" dark v-on:click='table_batch().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_batch().save()' v-html='temp_input.id_edit_batch == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>Batch Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'Stock', value:'stock'},
                                {text:'Batch Number', value:'batch_number'},
                                {text:'Source',value:'sources'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.batch"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.stock }}</td>
                                    <td>{{ props.item.batch_number }}</td>
                                    <td >{{ props.item.sources.name }}</td>

                                    <td>
                                        <v-btn class='button-action' v-on:click='table_batch().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_batch().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>

                            

                        </v-stepper-content>

                        
                        {{input}}
                        <v-btn v-on:click='save_goodsrack()' >submit</v-btn>
                        {{temp_input}}
                        
                        
                    </v-stepper>
                </v-card>
            </v-form>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>Good Rack Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="goodsracks"
            class=""
        >
        <template v-slot:items="props">
            <td >{{ props.item.rack_name }}</td>
            <td>{{ props.item.goods_name }}</td>
            <td class="text-xs-right">{{ props.item.stock }}</td>
            <td>
                <v-btn class='button-action' v-on:click='get_data_before_edit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_goods_rack(props.index)' color="red" fab small dark depressed>
                    <v-icon small>delete</v-icon>
                </v-btn>

            </td>
        </template>
        </v-data-table>
        
    </div>
</template>

<script>
import axios from 'axios'
export default {
    name: 'cpGoodsRack',
    props:['prop_id_goods_for_table'],
    data () {
        return {
            
            valid:false,
            on:false,
            dialog_createedit:false,
            e6:1,
            dialog_stock:false,

            idx_data_edit:-1,

            
            input_before_edit:{ //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
                
            },
            input:{
                goods_id:'',
                rack_id:'',
                stock:'',
                price_sellings:[],
                batch:[],
            },

            temp_input:{
                id_edit_price_selling:-1, //artinya add data,
                id_edit_batch:-1, //artinya add data,
                price_sellings:
                {
                    category_price_sellings:
                    {
                        id:null,
                        name:null,
                    },
                    stock_cut_off:null,
                    price:null,
                    discount:null,
                    free:null,
                },
                batch:
                {
                    stock:null,
                    batch_number:null,
                    sources:
                    {
                        id:null,
                        name:null,
                    }
                }
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
                category_price_sellings:[
                    {id:6,name:'Harga Jual'},
                    {id:7,name:'Harga Beli'},
                ],
                sources:[
                    {id:5,name:'Pembelian'},
                    {id:4,name:'Diberi'},
                    {id:3,name:'Nemu'},
                ],
                

                //statis
                free:[
                    {value:1,name:'True'},
                    {value:0,name:'False'},
                ]
            },
            

            

            headers: [
                { text: 'Racks', value: 'racks'},
                { text: 'Goods', value: 'goods'},
                { text: 'Stock', value: 'stock', align:'right' },
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],

            goodsracks:[],
            
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
        table_price_selling()
        {
            var self = this;
            return{

                
                showData(idx){
                    
                    self.temp_input.price_sellings = JSON.parse(JSON.stringify(self.input.price_sellings[idx]));
                    self.temp_input.id_edit_price_selling = idx;
                },
                clearTempInput(){
                    for (var key in self.temp_input.price_sellings)
                    {
                        if(self.temp_input.price_sellings[key])
                            self.temp_input.price_sellings[key] = null;
                    }
                    
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_price_selling));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.price_sellings));
                    
                        self.input.price_sellings.push(temp);
                        
                    }
                    else
                    {
                        self.input.price_sellings[id_edit] = JSON.parse(JSON.stringify(self.temp_input.price_sellings));
                        self.temp_input.id_edit_price_selling = -1;

                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_price_selling = -1;
                },
                delete(idx)
                {
                    self.input.price_sellings.splice(idx,1);
                }



            }
        },

        table_batch()
        {
            var self = this;
            return{

                
                showData(idx){
                    
                    self.temp_input.batch = JSON.parse(JSON.stringify(self.input.batch[idx]));
                    self.temp_input.id_edit_batch = idx;
                },
                clearTempInput(){

                   
                    for (var key in self.temp_input.batch)
                    {
                        if(self.temp_input.batch[key])
                            self.temp_input.batch[key] = null;
                    }
                    
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_batch));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.batch));
                        
                        self.input.batch.push(temp);
                        
                    }
                    else
                    {
                        
                        self.input.batch[id_edit] = JSON.parse(JSON.stringify(self.temp_input.batch));
                        
                        self.temp_input.id_edit_batch = -1;


                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_batch = -1;
                },
                delete(idx)
                {
                    self.input.batch.splice(idx,1);
                }



            }
        },


        closedialog_createedit(){
            this.idx_data_edit = -1;
            this.dialog_createedit = false;
        },
        opendialog_createedit(idx_data_edit,r){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;
                this.convert_data_input_goodsrack(r);
                
            }
            else
            {
                this.clear_input();
            }

            this.dialog_createedit = true;
        },
        clear_input(){
            this.$refs.formCreateEdit.resetValidation();
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
        },
        
        
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items.goods);
            this.ref_input.goods = r.data.items.goods;
            this.ref_input.racks = r.data.items.racks;
            this.ref_input.category_price_sellings = r.data.items.category_price_selling;
            this.ref_input.sources = r.data.items.sources;
            
        },
        convert_data_input_goodsrack(r)
        {
            var temp_r = r.data.items.goods_rack;
            this.input.goods_id = temp_r.goods_id;
            this.input.rack_id = temp_r.rack_id;
            this.input.stock = temp_r.stock;

            if(temp_r.price_selling) this.input.price_sellings = temp_r.price_selling;
            if(temp_r.batch) this.input.batch = temp_r.batch;

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



            if(this.idx_data_edit != -1) //jika sedang diedit
            {

                //data yang harus dikirim saat update :
                //1. data goodsrack yang BERUBAH SAJA
                //2. data price_sellings dan batch AKHIR 
                

                //step-step :
                //1. kirim data goodsrack yang berubah
                if(this.input.goods_id != this.input_before_edit.goods_id) formData.append('goods_id', this.input.goods_id);
                if(this.input.rack_id != this.input_before_edit.rack_id) formData.append('rack_id', this.input.rack_id);
                if(this.input.stock != this.input_before_edit.stock) formData.append('stock', this.input.stock);
                

                //2. kirim data price_sellings dan batch akhir
                for(var i = 0;i<this.input.price_sellings.length;i++)
                {
                    formData.append('price_sellings[' + i + '][category_price_selling_id]',this.input.price_sellings[i].category_price_sellings.id);
                    formData.append('price_sellings[' + i + '][stock_cut_off]',this.input.price_sellings[i].stock_cut_off);
                    formData.append('price_sellings[' + i + '][price]',this.input.price_sellings[i].price);
                    formData.append('price_sellings[' + i + '][discount]',this.input.price_sellings[i].discount);
                    formData.append('price_sellings[' + i + '][free]',this.input.price_sellings[i].free.value);
                }

                for(var i = 0;i<this.input.batch.length;i++)
                {
                    formData.append('batch[' + i + '][source_id]',this.input.batch[i].sources.id);
                    formData.append('batch[' + i + '][stock]',this.input.batch[i].stock);
                    formData.append('batch[' + i + '][batch_number]',this.input.batch[i].batch_number);
                }

                
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

                

                //2. kirim data price selling dan batch

                for(var i = 0;i<this.input.price_sellings.length;i++)
                {
                    formData.append('price_sellings[' + i + '][category_price_selling_id]',this.input.price_sellings[i].category_price_sellings.id);
                    formData.append('price_sellings[' + i + '][stock_cut_off]',this.input.price_sellings[i].stock_cut_off);
                    formData.append('price_sellings[' + i + '][price]',this.input.price_sellings[i].price);
                    formData.append('price_sellings[' + i + '][discount]',this.input.price_sellings[i].discount);
                    formData.append('price_sellings[' + i + '][free]',this.input.price_sellings[i].free);
                }

                for(var i = 0;i<this.input.batch.length;i++)
                {
                    formData.append('batch[' + i + '][source_id]',this.input.batch[i].sources.id);
                    formData.append('batch[' + i + '][stock]',this.input.batch[i].stock);
                    formData.append('batch[' + i + '][batch_number]',this.input.batch[i].batch_number);
                }

            }

            
            

            //3. masukan token
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },
         get_goodsrack(id_goods_for_table) {

            axios.get('/api/goodsRacks', {
                params:{
                    token: localStorage.getItem('token')
                }
            },{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json'
                }
            }).then(r => 
            {
                this.showTable(r,id_goods_for_table);
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
        showTable(r,id_goods_for_table)
        {
            //process r agar dari id menjadi nama
            if(id_goods_for_table != "all")
            {

                for(var i = 0;i<r.data.items.goods_rack.length;i++)
                {
                    if(r.data.items.goods_rack[i].id == id_goods_for_table)
                    {
                        this.goodsracks.push(r.data.items.goods_rack[i]);
                    }
                }
            }
            else
            {
                this.goodsracks = r.data.items.goods_rack;
                
            }

        },
        save_goodsrack()
        {
            if(this.valid)
            {
                if(this.idx_data_edit != -1) //jika sedang diedit
                {
                    console.log(this.prepare_data_form_goodsrack());
                    axios.post('api/goodsRacks/' + this.goodsracks[this.idx_data_edit].id,this.prepare_data_form_goodsrack(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                    }).then((r)=> {
                        this.get_goodsrack();
                        this.closedialog_createedit();
                        this.clear_input();
                        this.idx_data_edit = -1;
                        swal("Good job!", "Data saved !", "success");
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

                    
                }
                else //jika sedang tambah data
                {

                    axios.post('api/goodsRacks',this.prepare_data_form_goodsrack(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        }
                    }).then((r)=> {
                        this.get_goodsrack();
                        this.closedialog_createedit();
                        this.clear_input();
                        this.idx_data_edit = -1;
                        swal("Good job!", "Data saved !", "success");
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
                }
                
            }
            else
            {
                swal('Form Is not Valid', "Please check your input" , 'error');
            }
        },
        delete_goodsrack(idx_data_delete){
            
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/goodsRacks/' + this.goodsracks[idx_data_delete].id,{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.get_goodsrack();
                            swal("Good job!", "Data Deleted !", "success");
                            
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
                    }
            });
        },
        get_master_data()
        {
            axios.get('/api/goodsRacks/create', {
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
                console.log(error);
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
            var id_edit = this.goodsracks[idx_edit].id;
            axios.get('/api/goodsRacks/' + id_edit + '/edit', {
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
            


        }


    },
    mounted(){

        
        this.get_goodsrack(this.id_goods_for_table);
        this.get_master_data();
        //this.testing_input();

    },
}
</script>


