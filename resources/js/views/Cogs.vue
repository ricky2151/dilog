<div>
    <v-container fluid>
        <h3>COGS</h3>
    </v-container>
</div>

<template>
    <div>
        
        <v-dialog v-model="dialog_createedit" fullscreen>
            <v-form v-model="valid" ref='formCreateEdit'  class='fixfullscreen'>
                <v-card class='fixfullscreen'>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html='idx_data_edit == -1 ?"Add COGS":"Edit COGS"'></v-toolbar-title>

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
                <td>{{ props.item.name }}</td>
                <td class="text-xs-right">{{ props.item.nominal }}</td>
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
                types:[
                    {id:6,name:'HPP Penjualan'},
                    {id:7,name:'HPP Pembelian'},
                ],
            },
            

            

            headers: [
                { text: 'Name', value: 'name'},
                { text: 'Nominal', value: 'nominal', align:'right' },
                { text: 'Type', value: 'type', align:'right' },
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],


            cogs: []
        }
    },
    methods: {
        table_cogs_component()
        {
            var self = this;
            return{

                
                showData(idx){
                    
                    self.temp_input.cogs_components = JSON.parse(JSON.stringify(self.input.cogs_components[idx]));
                    self.temp_input.id_edit_cogs_component = idx;
                },
                clearTempInput(){
                    for (var key in self.temp_input.cogs_components)
                    {
                        if(self.temp_input.cogs_components[key])
                            self.temp_input.cogs_components[key] = null;
                    }
                    
                },
                save(){ //bisa edit / add
                    var id_edit = JSON.parse(JSON.stringify(self.temp_input.id_edit_cogs_component));
                    if(id_edit == -1)
                    {
                        var temp = JSON.parse(JSON.stringify(self.temp_input.cogs_components));
                    
                        self.input.cogs_components.push(temp);
                        
                    }
                    else
                    {
                        self.input.cogs_components[id_edit] = JSON.parse(JSON.stringify(self.temp_input.cogs_components));
                        self.temp_input.id_edit_cogs_component = -1;

                    }
                    this.clearTempInput();
                },
                canceledit(){
                    this.clearTempInput();
                    self.temp_input.id_edit_cogs_component = -1;
                },
                delete(idx)
                {
                    self.input.cogs_components.splice(idx,1);
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
                this.convert_data_input_cogs(r);
                
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

            this.cogs = r.data.items.cogs;
        },
        
        fill_select_master_data(r)
        {
            //console.log(r.data.items[0].units);
            this.ref_input.types = r.data.items.types;
        },
        convert_data_input_cogs(r)
        {
            var temp_r = r.data.items.cogs;
            this.input.name = temp_r.name;
            this.input.nominal = temp_r.nominal;
            this.input.type_id = temp_r.type_id;
            
            this.input.cogs_components = temp_r.cogs_components;

            

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form_cogs()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.idx_data_edit != -1) //jika sedang diedit
            {

                //data yang harus dikirim saat update :
                //1. data cogs yang BERUBAH SAJA
                //2. data cogs_components yang BERUBAH, DITAMBAH, & DIHAPUS

                //step-step :
                //1. kirim data cogs yang berubah
                if(this.input.name != this.input_before_edit.name) formData.append('name', this.input.name);
                if(this.input.nominal != this.input_before_edit.nominal) formData.append('nominal', this.input.nominal);
                if(this.input.type_id != this.input_before_edit.type_id) formData.append('type_id', this.input.type_id);


                

                

                //2. kirim data cogs_component yang berubah, ditambah, dan dihapus
                
                //cek di input cocokin dengan input_before_edit
                //1. cek apakah ada id nya atau tidak, jika tidak memiliki id, pasti itu tambah baru
                //2. jika punya id, cocokan dengan input_before_edit, jika sama berarti tidak diedit, jika beda berarti diedit

                //temp adalah data dari input
                //temp2 adalah data dari input_before_edit
                var counteridx = 0;
                for(var i = 0;i<this.input.cogs_components.length;i++)
                {
                    var temp = this.input.cogs_components[i];
                    if(temp.id == null)
                    {
                        formData.append('cogs_components_new[' + counteridx + '][name]', temp.name);
                        formData.append('cogs_components_new[' + counteridx + '][value]', temp.value);
                        formData.append('cogs_components_new[' + counteridx + '][info]', temp.info);
                        counteridx++;
                    }
                    else
                    {
                        //cocokan dengan input_before_edit
                        var edittrue = false;
                        for(var j = 0;j<this.input_before_edit.cogs_components.length;j++)
                        {
                            var temp2 = this.input_before_edit.cogs_components[i];
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
                            formData.append('cogs_components_new[' + counteridx + '][name]', temp.name);
                            formData.append('cogs_components_new[' + counteridx + '][value]', temp.value);
                            formData.append('cogs_components_new[' + counteridx + '][info]', temp.info);
                            counteridx++;
                        }

                    }
                }

                //cek di input_before_edit cocokin dengan input
                //1. jika ada data dengan id yang tidak ada di data input, berarti data tersebut pasti dihapus
                for(var i = 0;i<this.input_before_edit.cogs_components.length;i++)
                {
                    var deletetrue = true;
                    for(var j=0;j<this.input.cogs_components.length;j++)
                    {
                        if(this.input.cogs_components[j].id == this.input_before_edit.cogs_components[i].id)
                        {
                            deletetrue = false;
                            break;
                        }
                    }

                    if(deletetrue)
                    {
                        formData.append('cogs_components_delete[' + counteridx + '][id]', this.input_before_edit.cogs_components[i].id);
                        counteridx++;
                    }
                }

                
                formData.append('_method', 'patch');

            }
            else //jika sedang add
            {

                //data-data yang harus dikirim : 
                //1. semua data cogs
                //2. semua data cogs_component

                //step-step : 
                //1. kirim data cogs
                formData.append('name', this.input.name);
                formData.append('nominal', this.input.nominal);
                formData.append('type_id', this.input.type_id);

                //2. kirim data cogs_component

                for(var i = 0;i<this.input.cogs_components.length;i++)
                {
                    formData.append('cogs_components_new[' + i + '][name]', temp.name);
                    formData.append('cogs_components_new[' + i + '][value]', temp.value);
                    formData.append('cogs_components_new[' + i + '][info]', temp.info);

                }

                

            }

            
            

            //4. masukan token
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },
        get_cogs() {

            axios.get('/api/cogs', {
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
        save_cogs()
        {
            if(this.valid)
            {
                if(this.idx_data_edit != -1) //jika sedang diedit
                {
                    axios.post('api/cogs/' + this.cogs[this.idx_data_edit].id,this.prepare_data_form_cogs(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                    }).then((r)=> {
                        this.get_cogs();
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

                    axios.post('api/cogs',this.prepare_data_form_cogs(),
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        }
                    }).then((r)=> {
                        this.get_cogs();
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
        delete_cogs(idx_data_delete){
            
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/cogs/' + this.cogs[idx_data_delete].id,{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.get_cogs();
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
        
        this.get_cogs();
        this.get_master_data();
        //this.testing_input();

    },
}
</script>

