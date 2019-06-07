<div>
    <v-container fluid>
        <h3>Supplier</h3>
    </v-container>
</div>

<template>
    <div>
        
        

        <!-- POPUP CREATE EDIT -->


        <v-dialog v-model="dialog_createedit">
            <v-form v-model="valid" ref='formCreateEdit'>
                <v-card>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html='idx_data_edit == -1 ?"Add Supplier":"Edit Supplier"'></v-toolbar-title>

                    </v-toolbar>
                    <v-stepper v-model="e6" vertical>

                        <!-- ==== STEPPER 1 ==== -->

                        <v-stepper-step :complete="e6 > 1" step="1" editable>
                            <h3>Supplier Data</h3>
                        </v-stepper-step>

                        <v-stepper-content step="1">
                            
                            

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.name_company' label="Name Company" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.name_pic' label="Name PIC" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.name_sales' label="Name Sales" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.address' label="Address" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.notelp_company' label="No Telp Company" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.email_req" v-model='input.email' label="Email" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.tax' label="Tax" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.npwp' label="NPWP" required></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field class="pa-2" :rules="this.$list_validation.max_req" v-model='input.norek' label="No Rek" required></v-text-field>
                                </v-flex>
                            </v-layout>
                                
                            <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                                
                            
                        </v-stepper-content>

                        <!-- ==== STEPPER 2 ==== -->

                        <v-stepper-step :complete="e6 > 2" step="2" editable><h3>Price List</h3></v-stepper-step>

                        <v-stepper-content step="2">


                            <v-select v-model='temp_input.pricelists.goods' :items="ref_input.goods" item-text='name' return-object label="Select Goods"></v-select>
                            


                            <v-text-field v-model="temp_input.pricelists.price" label="Value" required></v-text-field>
                            
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
                                {text:'Goods', value:'goods'},
                                {text:'Price',value:'price',align:'right'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.pricelists"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.goods.name }}</td>
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
                            

                        </v-stepper-content>
                        
                        <v-btn v-on:click='save_supplier()'>submit</v-btn>
                        
                        
                        
                    </v-stepper>
                </v-card>
            </v-form>
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
                    v-model="search_suppliers"
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
            :items="suppliers"
            :search="search_suppliers"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.index + 1 }}</td>
            <td>{{ props.item.name_company }}</td>
            <td class="text-xs-right">{{ props.item.name_pic }}</td>
            <td class="text-xs-right">{{ props.item.address }}</td>
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
                          v-on:click="action_change(props.index,index)"
                          
                        >
                          <v-list-tile-title>{{ item }}</v-list-tile-title>
                        </v-list-tile>
                      </v-list>
                    </v-menu>
                </div>
                <!-- <v-select
                  :items="action_items"
                  label="Action"
                  v-on:change='action_change(props.index)'
                  v-model='action_selected[props.index]'
                  solo
                  item-text='cobatext'

                  hide-selected
                ></v-select> -->
                <!-- <v-btn class='button-action' v-on:click='get_data_before_edit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_goods(props.index)' color="red" fab small dark depressed>
                    <v-icon small>delete</v-icon>
                </v-btn> -->

            </td>
        </template>
        </v-data-table>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    errorCaptured (err, vm, info) {
    this.error = `${err.stack}\n\nfound in ${info} of component`
    return false
  },
    data () {
        return {
            
            action_items: ['Edit','Delete'],
            
            

            valid:false,
            on:false,

            dialog_createedit:false,


            e6:1,

            idx_data_edit:-1,

            input_before_edit:{ //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
                
            },
            input:{
                name_company:'',
                name_pic:'',
                name_sales:'',
                address:'',
                notelp_company:'',
                email:'',
                tax:'',
                npwp:'',
                norek:'',
                pricelists:[],
            },

            temp_input:{
                id_edit_pricelists:-1, //artinya add data

                pricelists:
                {
                    goods:
                    {
                        id:null,
                        name:null,
                    },
                    price:null,
                },
            },


            ref_input:
            {
                goods:[
                    {id:3,name:'Gelang'},
                    {id:4,name:'Meja'},
                ]

            },
            

            

            headers: [
                { text: 'No', value: 'no'},
                { text: 'Company', value: 'name_company'},
                { text: 'PIC', value: 'name_pic', align:'right' },
                { text: 'Address', value: 'address', align:'right' },
                { text: 'Action', align:'left',sortable:false, width:'15%'},
            ],

            

            suppliers: [],
            search_suppliers: null,

        }
    },
    methods: {
        action_change(idx_datatable,idx_action)
        {
            
            //console.log('action_change');
            //console.log(this.action_selected);
            // console.log(this.action_selected == 'Rack');
            if(idx_action == 0)
            {
                this.get_data_before_edit(idx_datatable);
            }
            else if(idx_action == 1)
            {
                
                this.delete_suppliers(idx_datatable);
            }
            
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
        

        closedialog_createedit(){
            this.dialog_createedit = false;
            this.idx_data_edit = -1;
        },
        opendialog_createedit(idx_data_edit,r){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;
                this.convert_data_input_supplier(r);
                
            }
            else
            {
                this.clear_input();
            }

            this.dialog_createedit = true;
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

            this.suppliers = r.data.items.suppliers;

            
            
        },
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items[0].units);
            console.log(r.data.items.units);
            this.ref_input.goods = r.data.items.goods;
        },
        convert_data_input_goods(r)
        {
            var temp_r = r.data.items.suppliers;
            this.input.name_company = temp_r.name_company;
            this.input.name_pic = temp_r.name_pic;
            this.input.name_sales = temp_r.name_sales;
            this.input.address = temp_r.address;
            this.input.notelp_company = temp_r.notelp_company;
            this.input.email = temp_r.email;
            this.input.tax = temp_r.tax;
            this.input.npwp = temp_r.npwp;
            this.input.norek = temp_r.norek;
            
            

            for(var i = 0;i<temp_r.pricelists.length;i++)
            {  
                this.input.pricelists.push({
                    goods:{
                        id: temp_r.pricelists[i].goods_id,
                        name: temp_r.pricelists[i].goods_name,
                    },
                    price:temp_r.pricelists[i].price,
                })
            }


            

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form_supplier()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.idx_data_edit != -1) //jika sedang diedit
            {

                //data yang harus dikirim saat update :
                //1. data supplier yang BERUBAH SAJA
                //2. data pricelist AKHIR 
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
                        formData.append('price_sellings[' + counteridx + '][stockcutoff]', temp.stockcutoff);
                        formData.append('price_sellings[' + counteridx + '][category_price_selling_id]', temp.categorypriceselling.id);
                        formData.append('price_sellings[' + counteridx + '][price]', temp.price);
                        formData.append('price_sellings[' + counteridx + '][free]', temp.free);
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
                                if(temp.warehouse.id != temp2.warehouse.id || temp.stockcutoff != temp2.stockcutoff || temp.categorypriceselling.id != temp2.categorypriceselling.id || temp.price != temp2.price || temp.free != temp2.free) //jika ada salah satu saja yang berbeda, maka ini pasti diedit
                                {
                                    edittrue = true;
                                }
                                break;
                            }
                        }

                        if(edittrue)
                        {
                            formData.append('price_sellings[' + counteridx + '][warehouse_id]', temp.warehouse.id);
                            formData.append('price_sellings[' + counteridx + '][stockcutoff]', temp.stockcutoff);
                            formData.append('price_sellings[' + counteridx + '][category_price_selling_id]', temp.categorypriceselling.id);
                            formData.append('price_sellings[' + counteridx + '][price]', temp.price);
                            formData.append('price_sellings[' + counteridx + '][free]', temp.free);
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
        get_goods() {

            axios.get('/api/goods', {
                params:{
                    token: localStorage.getItem('token')
                }
            },{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json'
                }
            }).then(r => this.showTable(r))
            .catch(function (error)
            {
                console.log("error : ")
                console.log(error)
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
        save_goods()
        {
            if(this.valid)
            {
                if(this.idx_data_edit != -1) //jika sedang diedit
                {
                    axios.post('api/goods/' + this.goods[this.idx_data_edit].id,this.prepare_data_form_goods(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                    }).then((r)=> {
                        this.get_goods();
                        this.closedialog_createedit();
                        this.clear_input();
                        this.idx_data_edit = -1;
                        swal("Good job!", "Data saved !", "success");
                    })
                    .catch(function (error)
                    {
                        console.log("error : ")
                        console.log(error)
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

                    axios.post('api/goods',this.prepare_data_form_goods(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        }
                    }).then((r)=> {
                        this.get_goods();
                        this.closedialog_createedit();
                        this.clear_input();
                        this.idx_data_edit = -1;
                        swal("Good job!", "Data saved !", "success");
                    })
                    .catch(function (error)
                    {   
                        console.log("error : ")
                        console.log(error)
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
        delete_goods(idx_data_delete){
            
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/goods/' + this.goods[idx_data_delete].id,{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.get_goods();
                            swal("Good job!", "Data Deleted !", "success");
                            
                        })
                        .catch(function (error)
                        {
                            console.log("error : ")
                            
                            console.log(error)
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
            axios.get('/api/goods/create', {
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
                console.log("error : ")
                console.log(error)
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
            var id_edit = this.goods[idx_edit].id;
            axios.get('/api/goods/' + id_edit + '/edit', {
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
                console.log("error : ")
                console.log(error)
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


        get_popup_detailracks(idx_edit_popup_detailracks){
            axios.get('api/goods/' + this.goods[idx_edit_popup_detailracks].id + '/racks',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailracks = r.data.items.goods;
            })
        },


        get_popup_detailsellingprices(idx_edit_popup_detailsellingprices){
            axios.get('api/goods/' + this.goods[idx_edit_popup_detailsellingprices].id + '/sellingPrices',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailsellingprices = r.data.items.goods;
            })
        },


        get_popup_detailstockcards(idx_edit_popup_detailstockcards){
            axios.get('api/goods/' + this.goods[idx_edit_popup_detailstockcards].id + '/racks',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailstockcards = r.data.items.goods;
            })
        },


        get_popup_detailpricelists(idx_edit_popup_detailpricelists){
            axios.get('api/goods/' + this.goods[idx_edit_popup_detailpricelists].id + '/pricelists',{
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
        
        this.get_goods();
        this.get_master_data();
        //this.testing_input();

    },
}
</script>

<style>

.text-link{
    color:blue;
    text-decoration: underline;
    cursor:pointer;
}
.button-action{
    width: 30px;
    height: 30px;
}
.button-action i{
    font-size: 14px !important;
}

</style>
