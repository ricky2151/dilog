<div>
    <v-container fluid>
        <h3>COGS</h3>
    </v-container>
</div>

<template>
    <div>
        
        <v-dialog v-model="dialog_createedit" fullscreen>
            <v-form v-model="valid" ref='formCreateEdit'>
                <v-card>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Add COGS</v-toolbar-title>

                    </v-toolbar>
                    <v-stepper v-model="e6" vertical>

                        <!-- ==== STEPPER 1 ==== -->

                        <v-stepper-step :complete="e6 > 1" step="1" editable>
                            <h3>COGS Data</h3>
                        </v-stepper-step>

                        <v-stepper-content step="1">
                            
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
                                
                            <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                                
                            
                        </v-stepper-content>

                        <!-- ==== STEPPER 2 ==== -->

                        <v-stepper-step :complete="e6 > 2" step="2" editable><h3>COGS Component</h3></v-stepper-step>

                        <v-stepper-content step="2">
                            
                            <v-text-field v-model="temp_input.cogs_components.name" label="Name" required></v-text-field>

                            <v-text-field v-model="temp_input.cogs_components.value" label="Value" required></v-text-field>

                            <v-text-field v-model="temp_input.cogs_components.info" label="Info" required></v-text-field>
                            
                            <v-toolbar flat color="white" >
                                
                                <v-spacer></v-spacer>
                                <v-btn v-if='temp_input.id_edit_cogs_component != -1' color="red" dark v-on:click='table_cogs_component().canceledit()'>
                                    Cancel
                                </v-btn>
                                
                                <v-btn color="primary" dark v-on:click='table_cogs_component().save()' v-html='temp_input.id_edit_cogs_component == -1?"Add to Table":"Save Changes"'>
                                </v-btn>
                            </v-toolbar>
                            


                            <v-toolbar flat color="white" >
                                <v-toolbar-title>COGS Components Data</v-toolbar-title>
                                
                            </v-toolbar>
                            

                            <v-data-table
                                disable-initial-sort
                                :headers="[
                                {text:'Name', value:'name'},
                                {text:'Value',value:'value',align:'right'},
                                {text:'Info',value:'info',align:'right'},
                                {text:'Action',align:'left',width:'15%',sortable:false}
                                ]"
                                :items="input.cogs_components"
                                class=""
                            >

                                <template v-slot:items="props">
                                    <td>{{ props.item.name }}</td>
                                    <td class="text-xs-right">{{ props.item.value }}</td>
                                    <td class="text-xs-right">{{ props.item.info }}</td>
                                    <td>
                                        <v-btn class='button-action' v-on:click='table_cogs_component().showData(props.index)' color="primary" fab depressed small dark v-on="on">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                        <v-btn class='button-action' v-on:click='table_cogs_component().delete(props.index)' color="red" fab small dark depressed>
                                            <v-icon small>delete</v-icon>
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>
                            

                        </v-stepper-content>


                        <v-btn v-on:click='save_cogs()' >submit</v-btn>
                        
                        
                        
                    </v-stepper>
                </v-card>
            </v-form>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>COGS Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                Add COGS
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="cogs"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.item.nominal }}</td>
            <td class="text-xs-right">{{ props.item.name }}</td>
            <td class="text-xs-right">{{ props.item.types.name }}</td>
            <td>
                <v-btn class='button-action' v-on:click='get_data_before_edit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_cogs(props.index)' color="red" fab small dark depressed>
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
                nominal:'',
                name:'',
                type_id:'',
                cogs_components:[]
            },

            temp_input:{
                id_edit_cogs_component:-1, //artinya add data,
                cogs_components:
                {
                    name:null,
                    value:null,
                    info:null,
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

                //statis
                avgprice_status:[
                    {value:1,name:'True'},
                    {value:0,name:'False'},
                ]
            },
            

            

            headers: [
                { text: 'Name', value: 'name'},
                { text: 'Code', value: 'code', align:'right' },
                { text: 'Value', value: 'value', align:'right' },
                { text: 'Status', value: 'status', align:'right' },
                { text: 'Last Buy Pricelist', value: 'last_buy_pricelist', align:'right' },
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],


            goods: []
        }
    },
    methods: {
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

        removeChip(item){
            this.input.category_goods.splice(this.input.category_goods.indexOf(item), 1);
            this.input.category_goods = [...this.input.category_goods];
        },
        checkItemInList(){
            var temp = this.input.category_goods;
            if (!temp[temp.length - 1].hasOwnProperty('id')) //artinya cuman asal enter, tanpa ambil item dari ref_input
            {
                this.removeChip(temp[temp.length - 1]);
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
        

        closedialog_createedit(){
            this.dialog_createedit = false;
        },
        opendialog_createedit(idx_data_edit,r){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;
                this.convert_data_input_goods(r);
                
            }

            this.dialog_createedit = true;
        },
        clear_input(){
            for (var key in self.input)
            {
                if(self.input[key])
                    self.input[key] = null;
            }
        },
        
        showTable(r)
        {
            //console.log(r.data.items.goods[0]);

            this.goods = r.data.items.goods;
        },
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items[0].units);
            this.ref_input.unit = r.data.items[0].units;
            this.ref_input.cogs = r.data.items[0].cogs;
            this.ref_input.category = r.data.items[0].categories;
            this.ref_input.attribute = r.data.items[0].attributes;
            this.ref_input.material = r.data.items[0].materials;
        },
        convert_data_input_goods(r)
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
            this.input.avgprice_status = temp_r.avgprice_status;
            this.input.tax = temp_r.tax;
            this.input.unit_id = temp_r.unit_id;
            this.input.cogs_id = temp_r.cogs_id;
            this.preview.thumbnail = temp_r.thumbnail;

            console.log(temp_r.category_goods);
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
            this.input.material_goods = temp_r.material_goods;

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form_goods()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.idx_data_edit != -1) //jika sedang diedit
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
                if(this.input.tax != this.input_before_edit.tax) formData.append('tax', this.input.tax);
                if(this.input.unit_id != this.input_before_edit.unit_id) formData.append('unit_id', this.input.unit_id);
                if(this.input.cogs_id != this.input_before_edit.cogs_id) formData.append('cogs_id', this.input.cogs_id);

                //2. kirim data goods_attribute dan goods_category
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
                        formData.append('material_goods_new[' + counteridx + '][name]', temp.name);
                        formData.append('material_goods_new[' + counteridx + '][adjust]', temp.adjust);
                        formData.append('material_goods_new[' + counteridx + '][total]', temp.total);
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
                            formData.append('material_goods_update[' + counteridx + '][id]', temp.id);
                            formData.append('material_goods_update[' + counteridx + '][name]', temp.name);
                            formData.append('material_goods_update[' + counteridx + '][adjust]', temp.adjust);
                            formData.append('material_goods_update[' + counteridx + '][total]', temp.total);
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
                        formData.append('material_goods_delete[' + counteridx + '][id]', this.input_before_edit.material_goods[i].id);
                        counteridx++;
                    }
                }

                //4. tambahin is_image_deleted
                if(this.input.thumbnail_file.length > 0 && this.input_before_edit.thumbnail_file == null)
                {
                    formData.append('is_image_deleted', '1');
                }
                else
                {
                    formData.append('is_image_deleted', '0');   
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
            axios.get('/api/goods/' + id_edit, {
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
