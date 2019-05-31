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
                        <v-toolbar-title>Add Racks</v-toolbar-title>

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

                        
                        {{input}}
                        <v-btn v-on:click='save_rack()' >submit</v-btn>
                        
                        
                        
                    </v-stepper>
                </v-card>
            </v-form>
        </v-dialog>


        <!-- UNTUK COMPONENT GOODS_RACK -->
        
        <cp-goods-rack v-if="id_goods_for_table != 'all' && component_goodsrack" :prop_id_goods_for_table='id_goods_for_table'></cp-goods-rack>
        


        <div v-if="!component_goodsrack">
            <v-toolbar flat color="white">
                <v-toolbar-title>Racks Data</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                    Add Data
                </v-btn>
            </v-toolbar>
            <v-data-table
                disable-initial-sort
                :headers="headers"
                :items="racks"
                class=""
            >
                <template v-slot:items="props">
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.warehouse }}</td>
                    <td>
                        <v-btn class='button-action' v-on:click='opencomponent_goodsrack(props.index)' color="primary" block dark v-on="on">
                            Goods
                        </v-btn>
                    </td>
                    <td>
                        <v-btn class='button-action' v-on:click='get_data_before_edit(props.index)' color="primary" fab depressed small dark v-on="on">
                            <v-icon small>edit</v-icon>
                        </v-btn>
                        <v-btn class='button-action' v-on:click='delete_racks(props.index)' color="red" fab small dark depressed>
                            <v-icon small>delete</v-icon>
                        </v-btn>

                    </td>

                </template>
            </v-data-table>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import cpGoodsRack from './../components/cpGoodsRack.vue'
export default {
    components:{
        cpGoodsRack,
    },
    data () {
        return {
            
            component_goodsrack:false,
            id_goods_for_table:'all',

            valid:false,
            on:false,
            dialog_createedit:false,
            e6:1,
            dialog_stock:false,

            idx_data_edit:-1,

            preview:{
                thumbnail:'',
            },
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
                { text: 'Name', value: 'name'},
                { text: 'Warehouse', value: 'warehouse' },
                { text: 'Goods', align:'left',width:'4%',sortable:false},
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],


            racks: [
            {
                name: 'rack1',
                warehouse : 'warehouse1',
            }]
        }
    },
    methods: {
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

        
        opencomponent_goodsrack(idx)
        {
            
            if(idx != -1)
            {
                console.log('id_goods_for_table : ' + this.id_goods_for_table);
                console.log('component_goodsracks : ' + this.component_goodsrack);
                this.id_goods_for_table = this.racks[idx].id;
                this.component_goodsrack = true;
            }
        },

        closedialog_createedit(){
            this.dialog_createedit = false;
        },
        opendialog_createedit(idx_data_edit,r){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;
                this.convert_data_input_rack(r);
                
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
        
        showTable(r)
        {
            //console.log(r.data.items.goods[0]);

            this.racks = r.data.items.racks;
        },
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items[0].units);
            this.ref_input.warehouses = r.data.items.warehouses;
            this.ref_input.goods = r.data.items.goods;

        },
        convert_data_input_rack(r)
        {
            var temp_r = r.data.items.racks;
            this.input.name = temp_r.name;
            this.input.warehouse_id = temp_r.warehouse_id;

            this.input.goods_rack = temp_r.goods_rack;

            

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form_rack()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.idx_data_edit != -1) //jika sedang diedit
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
        get_rack() {

            axios.get('/api/racks', {
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
        save_rack()
        {
            if(this.valid)
            {
                if(this.idx_data_edit != -1) //jika sedang diedit
                {
                    axios.post('api/racks/' + this.racks[this.idx_data_edit].id,this.prepare_data_form_rack(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                    }).then((r)=> {
                        this.get_rack();
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

                    axios.post('api/racks',this.prepare_data_form_rack(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        }
                    }).then((r)=> {
                        this.get_rack();
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
        delete_rack(idx_data_delete){
            
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/racks/' + this.racks[idx_data_delete].id,{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.get_rack();
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
            axios.get('/api/racks/create', {
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
            var id_edit = this.racks[idx_edit].id;
            axios.get('/api/racks/' + id_edit + '/edit', {
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
        
        this.get_rack();
        this.get_master_data();
        //this.testing_input();

    },
}
</script>

