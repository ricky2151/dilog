<div>
    <v-container fluid>
        <h3>goods</h3>
    </v-container>
</div>

<template>
    <div>
        <v-dialog v-model="dialog_createedit" fullscreen>
            <v-card>
                <v-toolbar dark color="red">
                    <v-btn icon dark v-on:click="closedialog_createedit()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Add goods</v-toolbar-title>

                </v-toolbar>
                <v-stepper v-model="e6" vertical>

                    <!-- ==== STEPPER 1 ==== -->

                    <v-stepper-step :complete="e6 > 1" step="1" editable>
                        <h3>Goods Data</h3>
                    </v-stepper-step>

                    <v-stepper-content step="1">
                        
                            <v-text-field v-model='input.name' label="Name" required></v-text-field>
                            
                            <v-text-field v-model='input.code' label="Code" required></v-text-field>
                            
                            <v-text-field v-model='input.desc' label="Description" required></v-text-field>
                            
                            <v-text-field v-model='input.margin' label="Margin" required></v-text-field>
                            
                            <v-text-field v-model='input.value' label="Value" required></v-text-field>
                            
                            <v-text-field v-model='input.status' label="Status" required></v-text-field>
                            
                            <v-text-field v-model='input.last_buy_pricelist' label="Last Buy Price List" required></v-text-field>
                            
                            <v-text-field v-model='input.barcode' label="Barcode" required></v-text-field>

                            <v-text-field v-model='input.thumbnail_filename' label="Select Image" v-on:click='pickFile' prepend-icon='attach_file'></v-text-field>
                            <input
                                id='btn_upload_thumbnail'
                                type="file"
                                style="display: none"
                                accept="image/*"
                                v-on:change='changeImage'
                            >
                            <img :src="input.thumbnail_file" height="150" v-if="input.thumbnail_filename"/>

                            <v-text-field v-model='input.avgprice' label="Average price" required></v-text-field>

                            <v-select v-model='input.user_id' :items="ref_input.user" item-text='name' item-value='id' label="Select User"></v-select>

                            <v-text-field v-model='input.tax' label="Tax" required></v-text-field>

                            <v-select v-model='input.unit_id' :items="ref_input.unit" item-text='name' item-value='id' label="Select Unit"></v-select>

                            <v-select v-model='input.cogs_id' :items="ref_input.cogs" item-text='name' item-value='id' label="Select COGS"></v-select>
                            
                            <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                            
                        
                    </v-stepper-content>

                    <!-- ==== STEPPER 2 ==== -->

                    <v-stepper-step :complete="e6 > 2" step="2" editable><h3>Goods Category</h3></v-stepper-step>

                    <v-stepper-content step="2">
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
                                      v-on:input="removeChip(data.item)"
                                    >
                                        <strong>{{ data.item.name }}</strong>
                                    </v-chip>
                                </template>
                        </v-combobox>
                        <v-btn color='primary' v-on:click='e6=3'>Continue</v-btn>
                    </v-stepper-content>

                    <!-- ==== STEPPER 3 ==== -->

                    <v-stepper-step :complete="e6 > 3" step="3" editable><h3>Goods Attribute</h3></v-stepper-step>

                    <v-stepper-content step="3">


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
                        <v-btn color='primary' v-on:click='e6=4'>Continue</v-btn>

                    </v-stepper-content>

                    <!-- ==== STEPPER 4 ==== -->

                    <v-stepper-step step="4" editable><h3>Goods Material</h3></v-stepper-step>

                    <v-stepper-content step="4">
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
                        <v-btn color='primary' v-on:click='e6=4'>Continue</v-btn>
                    </v-stepper-content>

                    <v-btn v-on:click='save_unit()' >submit</v-btn>

                </v-stepper>
            </v-card>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>goods Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="goods"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.item.name }}</td>
            <td class="text-xs-right">{{ props.item.code }}</td>
            <td class="text-xs-right">{{ props.item.value }}</td>
            <td class="text-xs-right">{{ props.item.status }}</td>
            <td class="text-xs-right">{{ props.item.last_buy_pricelist }}</td>
            <td>
                <v-btn class='button-action' v-on:click='opendialog_createedit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_goods(props.index)' color="red" fab small dark depressed>
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
            on:false,

            dialog_createedit:false,
            e6:1,
            dialog_stock:false,

            idx_data_edit:-1,

            input:{
                name:'',
                code:'',
                desc:'',
                margin:'',
                value:'',
                status:'',
                last_buy_pricelist:'',
                barcode:'',
                thumbnail_filename:'', //tidak ikut dikirim ke server (cuman muncul di form)
                thumbnail_file:'', //tidak ikut dikirim ke server (cuman muncul di preview di form)
                thumbnail_filesend:'', //ini akan dikirim ke server
                avgprice:'',
                user_id:'',
                tax:'',
                unit_id:'', //seola
                cogs_id:'',
                category_goods:[],
                attribute_goods:[],
                material_goods:[]
            },

            temp_input:{
                id_edit_attribute_goods:-1, //artinya add data,
                id_edit_material_goods:-1, //artinya add data,
                attribute_goods:
                {
                    attribute:
                    {
                        id:null,
                        name:null,
                    },
                    tax:null,
                },
                material_goods:
                {
                    goods:
                    {
                        id:null,
                        name:null,
                    },
                    total:null,
                    adjust:null,
                }
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

                    self.temp_input.attribute_goods.attribute = null;
                    self.temp_input.attribute_goods.value = 0;
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

                    self.temp_input.material_goods.name = null;
                    self.temp_input.material_goods.total = 0;
                    self.temp_input.material_goods.adjust = 0;
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
            const files = e.target.files
            if(files[0] !== undefined) {
                this.input.thumbnail_filename = files[0].name;
                if(this.input.thumbnail_filename.lastIndexOf('.') <= 0) { //jika bukan file 
                    return
                }
                const fr = new FileReader ()
                fr.readAsDataURL(files[0])
                fr.addEventListener('load', () => {
                    this.input.thumbnail_file = fr.result //jadi preview
                    this.input.thumbnail_filesend = files[0] //yang dikirim ke server
                })
            } else {
                this.input.thumbnail_filename = ''
                this.input.thumbnail_file = ''
                this.input.thumbnail_filesend = ''
                
            }
        },
        pickFile () {
            var el = document.getElementById('btn_upload_thumbnail').click();
        },
        

        closedialog_createedit(){
            this.dialog_createedit = false;
        },
        opendialog_createedit(idx_data_edit){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;

                
                this.input.name = this.goods[this.idx_data_edit].name;
                this.input.code = this.goods[this.idx_data_edit].code;
                this.input.value = this.goods[this.idx_data_edit].value;
                this.input.status = this.goods[this.idx_data_edit].status;
                this.input.last_buy_pricelist = this.goods[this.idx_data_edit].last_buy_pricelist;
                this.input.stock = this.goods[this.idx_data_edit].stock;
                
            }

            this.dialog_createedit = true;
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
        },
        save_goods()
        {
            if(this.idx_data_edit != -1) //jika sedang diedit
            {
                axios.patch('api/goods/' + this.goods[this.idx_data_edit].id,{
                    headers: {
                        'Accept': 'application/json',
                        'Content-type': 'application/json'
                    },
                    name: this.input.name,
                    code: this.input.code,
                    value: this.input.value,
                    status: this.input.status,
                    token: localStorage.getItem('token')
                }).then((r) => {
                    this.get_goods();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                    this.idx_data_edit = -1;
                    this.input.name = '';
                });
                
                
                

                
            }
            else //jika sedang tambah data
            {
               
                axios.post('api/goods',{
                    headers: {
                        'Accept': 'application/json',
                        'Content-type': 'application/json'
                    },
                    name: this.input.name,
                    code: this.input.code,
                    value: this.input.value,
                    status: this.input.status,
                    last_buy_pricelist: this.input.last_buy_pricelist,
                    stock: this.input.stock,
                    token: localStorage.getItem('token')
                }).then((r)=> {
                    this.get_goods();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                });
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
        },


    },
    mounted(){
        this.get_goods();
        this.get_master_data();
        
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
