
<div>
    <v-container fluid>
        <h3>Warehouse</h3>
    </v-container>
</div>

<template>

    <div>

        <!-- POPUP DETAIL RACK -->
        <v-dialog v-model="dialog_detailracks" width=750>
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
                        <td>{{ props.item.name }}</td>
                        
                    </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-dialog>

        <!-- POPUP DETAIL GOODS -->
        <v-dialog v-model="dialog_detailgoods" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_detailgoods()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Goods</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px'>

                    <v-text-field
                        v-model="popup_search_detailgoods"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                      ></v-text-field>
                    <v-data-table
                    disable-initial-sort
                    :headers="headers_popup_detailgoods"
                    :items="popup_detailgoods"
                    :search="popup_search_detailgoods"
                    class=""
                    >
                    <template v-slot:items="props">
                        <td>{{ props.index + 1 }}</td>
                        <td>{{ props.item.rack_name }}</td>
                        <td>{{ props.item.goods_name }}</td>
                        <td>{{ props.item.stock }}</td>
                        
                    </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-dialog>

        <!-- POPUP STOCK OPNAME -->
        <v-dialog v-model="dialog_stockopname" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_stockopname()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Dialog Stock Opname</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px'>

                    <v-text-field :rules='this.$list_validation.max_req' v-model='input.name' label=name counter=191></v-text-field>

                    <v-text-field :rules='this.$list_validation.max_req' disabled v-model='input.user' label="User" counter=191></v-text-field>

                    <v-text-field :rules='this.$list_validation.max_req' disabled v-model='input.warehouse' label="Warehouse" counter=191></v-text-field>

                    <v-text-field :rules='this.$list_validation.max_req' disabled v-model='input.notes' label="Notes" counter=191></v-text-field>

                    <v-btn color='primary'>Add</v-btn>

                    <div class='container'>
                        <cp-stock-opname :prop_id_stockopname='temp'></cp-stock-opname>
                    </div>
                </div>
            </v-card>
        </v-dialog>


        <v-dialog v-model="dialog_createedit">
            <v-form v-model="valid" ref='formCreateEdit'>
                <v-card>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html='id_data_edit == -1 ?"Add Warehouse":"Edit Warehouse"'></v-toolbar-title>

                    </v-toolbar>
                    <v-stepper v-model="e6" vertical>

                        <!-- ==== STEPPER 1 ==== -->

                        <v-stepper-step :complete="e6 > 1" step="1" editable>
                            <h3>Warehouse Data</h3>
                        </v-stepper-step>

                        <v-stepper-content step="1">
                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field :rules='this.$list_validation.max_req' v-model='input.name' label=name counter=191></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field :rules='this.$list_validation.max_req' counter=20 v-model='input.telp' label=telp></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <GmapMap
                                        v-bind:center="my_location"
                                        v-bind:zoom="7"
                                        map-type-id="terrain"
                                        style="width: 500px; height: 300px"
                                    >
                                      <GmapMarker
                                        v-bind:key="index"
                                        v-for="(m, index) in markers"
                                        v-bind:position="m.position"
                                        @drag="updateMarkers"
                                        v-bind:clickable="true"
                                        v-bind:draggable="true"
                                        v:on-click="center=m.position"
                                      />
                                    </GmapMap>
                                </v-flex>
                            </v-layout>


                            <v-layout row>
                                <v-flex xs6>
                                    <v-text-field class="pa-2" v-model='input.lat' label="Latitude" disabled></v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field class="pa-2" v-model='input.lng' label="Longitude" disabled></v-text-field>
                                </v-flex>
                            </v-layout>
                            
                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field :rules='this.$list_validation.max_req' counter=191 v-model='input.address' label=address></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field :rules='this.$list_validation.email_req' counter=191 v-model='input.email' label=email></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field :rules='this.$list_validation.max_req' counter=191 v-model='input.pic' label=pic></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex xs12>
                                    <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                                </v-flex>
                            </v-layout>

                        </v-stepper-content>

                        <!-- ==== STEPPER 2 ==== -->

                        <v-stepper-step :complete="e6 > 2" step="2" editable><h3>Create Rack</h3></v-stepper-step>

                        <v-stepper-content step="2">
                            <v-select v-if='id_data_edit == -1' v-model='interaction.create_rack.method' :items="[{id:0, name:'Create New'},{id:1, name:'Copy From Warehouse'} ]" item-text='name' item-value='id' label="Select Method"></v-select>

                            <!-- CREATE NEW RACK -->
                            <v-combobox
                                v-show='interaction.create_rack.method == 0 || id_data_edit != -1'
                                v-model='input.racks'
                                label="Type rack"
                                chips
                                clearable
                                prepend-icon="filter_list"
                                solo
                                multiple
                                v-on:keyup.enter="table_rack().updateChip()"
                                >
                                    <template v-slot:selection="data">
                                        <v-chip
                                          :selected="data.selected"
                                          close
                                          
                                          v-on:input="table_rack().removeChip(data.item)"

                                        >
                                            <strong>{{ data.item.name }}</strong>
                                        </v-chip>
                                    </template>
                            </v-combobox>

                            <!-- COPY FROM WAREHOUSE -->
                            <div v-show='interaction.create_rack.method == 1 && id_data_edit == -1'>
                                <v-select v-model='interaction.create_rack.selected_warehouse' :items='ref_input.warehouse' item-text='name' item-value='id' label='Select Warehouse'>
                                </v-select>
                                <b>Select Rack</b>
                                <v-data-table
                                    :headers="[{text:'Rack', value:'rack'},{text:'Action', value:'action'}]"
                                    :items='filtered_racks'
                                >
                                    <template v-slot:items="props">
                                        <td> 
                                            
                                            <v-checkbox v-model='filtered_racks[props.index].select_rack' :label='props.item.name' class=''></v-checkbox>
                                            
                                        </td>
                                        <td> 
                                            
                                            <v-checkbox v-model='filtered_racks[props.index].select_copy' v-if='filtered_racks[props.index].is_have_goods == true' :label='"Copy Goods"' class=''></v-checkbox>
                                            
                                        </td>
                                    </template>

                                </v-data-table>
                            </div>
                            
                        </v-stepper-content>

                        

                        <v-btn v-on:click='save_data()' >submit</v-btn>
                        
                    </v-stepper>
                </v-card>
                
            </v-form>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>Warehouse Data</v-toolbar-title>
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
            class=""
        >
        <template v-slot:items="props">
            <td>{{ findDataById(props.item.id,true) }}</td>
            <td>{{ props.item.name }}</td>
            <td class="text-xs-right">{{ props.item.address }}</td>
            <td class="text-xs-right">{{ props.item.telp }}</td>
            <td class="text-xs-right">{{ props.item.pic }}</td>
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


import axios from 'axios';
import mxCrudChildForm from '../mixin/mxCrudChildForm';
import cpStockOpname from './../components/cpStockOpname.vue';
export default {
    name:'Warehouse',
    components:{
        cpStockOpname,
    },
    data () {
        return {
            temp:1,
            name_table:'warehouses',
            header_api:{
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },

            action_items: ['Edit', 'Rack', 'Goods', 'Stock OP', 'Delete'],

            on:false,
            valid:false,

            my_location:{lat:10, lng:10},
            markers:[{
            	position:{lat:10,lng:10}
            	}],


            dialog_createedit:false,
            dialog_detailracks:false,
            dialog_detailgoods:false,
            dialog_stockopname:false,


            e6:1,

            id_data_edit:-1,

            input_before_edit:{ //variabel ini digunakan untuk menampung input sebelum di klik submit saat edit
                
            },
            input:{
                name:'',
                address:'',
                telp:'',
                pic:'',
                email:'',
                lat:null,
                lng:null,
                racks:[],
                copy_racks:[],
            },

            ref_input:
            {
                warehouse:[
                    
                ],
                rack:[
                    {
                        id:4,
                        name:'cobarack1',
                        warehouse_id:'2',
                        warehouse_name:'cobawarehouse2',
                    },
                    {
                        id:5,
                        name:'cobarack2',
                        warehouse_id:'2',
                        warehouse_name:'cobawarehouse2',
                    },
                    {
                        id:6,
                        name:'cobarack3',
                        warehouse_id:'3',
                        warehouse_name:'cobawarehouse3',
                    },
                    ,
                    {
                        id:7,
                        name:'cobarack4',
                        warehouse_id:'3',
                        warehouse_name:'cobawarehouse3',
                    },
                    ,
                    {
                        id:8,
                        name:'cobarack5',
                        warehouse_id:'3',
                        warehouse_name:'cobawarehouse3',
                    },
                ]
            },


            headers: [
                { text: 'No', value: 'no'},
                { text: 'Name', value: 'name'},
                { text: 'Address', value: 'address', align:'right' },
                { text: 'Telephone', value: 'telp', align:'right' },
                { text: 'PIC', value: 'pic', align:'right' },
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],

            headers_popup_detailracks:[
                { text: 'No', value:'no'},
                { text: 'Racks', value:'name'},
                
            ],

            headers_popup_detailgoods:[
                { text: 'No', value:'no'},
                { text: 'Racks', value:'rack'},
                { text: 'Goods', value:'goods'},
                { text: 'Stock', value:'stock'},
            ],


            data_table: [],
            search_data:null,

            popup_detailracks:
            [
                {
                    rack:'Meja',
                    stock:12,
                }
            ],
            popup_search_detailracks :null,

            popup_detailgoods:
            [
                {
                    rack_name:'Rack1',
                    goods_name:'goods1',
                    stock:12,
                }
            ],
            popup_search_detailgoods:null,

            interaction:
            {
                create_rack:
                {
                    method:-1, //jika 0, maka create new rack, jika 1 maka copy dari    warehouse
                    selected_warehouse:null, //warehouse yang dipilih saat user menggunakan copy from warehouse
                    list_rack:[], //rack yang dipilih beserta apakah copy goodsnya juga atau tidak
                }
                

            }
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
                
                this.opendialog_detailracks(id_datatable);
            }
            else if(idx_action == 2)
            {
                this.opendialog_detailgoods(id_datatable);
                
            }
            else if(idx_action == 3)
            {
                this.opendialog_stockopname(id_datatable); 
            }
            else if(idx_action == 4)
            {
                this.delete_data(id_datatable);
            }
            
        },

        table_rack()
        {
            var self = this;
            return{
                removeChip(item){
                    this.input.racks.splice(this.input.racks.indexOf(item), 1);
                    this.input.racks = [...this.input.racks];
                },
                updateChip(item)
                {
                    //console.log(this.input.racks);
                    var temprack = this.input.racks;
                    var tempdata = temprack[temprack.length - 1];
                    this.removeChip(tempdata);
                    this.input.racks.push({name:tempdata});
                },

                
            }
        },
        
        fill_select_master_data(r) //fill select master data tidak selalu masuk ke ref_input !!!
        {
            //var temp_r = r.data.items.warehouses;
            var temp_r = [
                {
                    id: 1,
                    name: "Bakpia Ku",
                    address: "89696 Raymond Locks Suite 778\nRoweberg, MT 27491-6639",
                    lat: "Suscipit in et non.",
                    lng: "Ullam molestiae.",
                    telp: "+6207219900426",
                    email: "rosemarie58@gmail.com",
                    pic: "Coleman Parker",
                    created_at: "2019-06-02 14:01:20",
                    updated_at: "2019-06-02 14:01:20",
                    deleted_at: null,
                    racks: [
                        {
                            id: 1,
                            name: "Rack Buku",
                            warehouse_id: 1,
                            created_at: "2019-06-02 14:01:20",
                            updated_at: "2019-06-02 14:01:20",
                            deleted_at: null,
                            is_have_goods: true
                        }
                    ]
                },
                {
                    id: 2,
                    name: "Bakpia Tugu",
                    address: "805 McGlynn Village Suite 031\nAnnamariefurt, ID 84198",
                    lat: "Repellat et modi.",
                    lng: "Cumque facilis ipsa.",
                    telp: "+6258713342150",
                    email: "mona37@west.net",
                    pic: "Katlyn Stamm",
                    created_at: "2019-06-02 14:01:20",
                    updated_at: "2019-06-02 14:01:20",
                    deleted_at: null,
                    racks: [
                        {
                            id: 2,
                            name: "Rack Buku",
                            warehouse_id: 2,
                            created_at: "2019-06-02 14:01:20",
                            updated_at: "2019-06-02 14:01:20",
                            deleted_at: null,
                            is_have_goods: true
                        },
                        {
                            id: 4,
                            name: "Rack Sapi",
                            warehouse_id: 2,
                            created_at: "2019-06-02 14:01:20",
                            updated_at: "2019-06-02 14:01:20",
                            deleted_at: null,
                            is_have_goods: true
                        }
                    ]
                },
                {
                    id: 3,
                    name: "Bakpia Kukus",
                    address: "973 Champlin Point Suite 414\nLake Michele, MA 59833-5409",
                    lat: "Laborum aspernatur.",
                    lng: "Sunt repellendus.",
                    telp: "+6213323545308",
                    email: "lang.orion@dach.com",
                    pic: "Melisa Gusikowski DDS",
                    created_at: "2019-06-02 14:01:20",
                    updated_at: "2019-06-02 14:01:20",
                    deleted_at: null,
                    racks: []
                },
                {
                    id: 4,
                    name: "Bakpia Pathok",
                    address: "6095 Lorna Turnpike Suite 514\nPort Tobyview, NV 38517",
                    lat: "Suscipit quia.",
                    lng: "Minima quasi.",
                    telp: "+62 843 5822 5454",
                    email: "adah.wehner@wintheiser.net",
                    pic: "Dr. Sidney Jacobi V",
                    created_at: "2019-06-02 14:01:20",
                    updated_at: "2019-06-02 14:01:20",
                    deleted_at: null,
                    racks: [
                        {
                            id: 3,
                            name: "Rack Ayam",
                            warehouse_id: 4,
                            created_at: "2019-06-02 14:01:20",
                            updated_at: "2019-06-02 14:01:20",
                            deleted_at: null,
                            is_have_goods: true
                        }
                    ]
                }
            ];


            //fill warehouse -> ref_input & rack -> interaction
            for(var i =0;i<temp_r.length;i++)
            {
                console.log('masukan ke ref_input wraehouse');
                //fill warehouse
                this.ref_input.warehouse.push
                ({
                    id: temp_r[i].id,
                    name: temp_r[i].name,
                });

                //fill rack
                if(temp_r[i].racks.length > 0)
                {
                    var name_warehouse = temp_r[i].name;
                    var id_warehouse = temp_r[i].id;
                    for(var j = 0;j<temp_r[i].racks.length;j++)
                    {
                        this.interaction.create_rack.list_rack.push
                        ({
                            id:temp_r[i].racks[j].id,
                            name:temp_r[i].racks[j].name,
                            is_have_goods:temp_r[i].racks[j].is_have_goods,
                            warehouse_id:id_warehouse,
                            warehouse_name:name_warehouse,
                            select_rack:false,
                            select_copy:false,
                        })
                    }
                }
            }

            



        },
        get_my_location(){
			navigator.geolocation.getCurrentPosition((position) => {
		        this.my_location = {
		        	lat: position.coords.latitude,
		        	lng: position.coords.longitude
		        };
		        this.markers[0].position = JSON.parse(JSON.stringify(this.my_location));
		        //binding
		        

		      });
		},
		updateMarkers(event){
			
			this.markers[0].position = {
			    lat: event.latLng.lat(),
			    lng: event.latLng.lng(),
			  }
			this.input.lat = this.markers[0].position.lat;
		    this.input.lng = this.markers[0].position.lng;  
			
		},

        opendialog_detailracks(id_edit_popup_detailracks)
        {
            this.dialog_detailracks = true;
            this.get_popup_detailracks(id_edit_popup_detailracks);
        },
        closedialog_detailracks()
        {
            this.dialog_detailracks = false;
        },

        opendialog_detailgoods(id_edit_popup_detailgoods)
        {
            this.dialog_detailgoods = true;
            //
        },
        closedialog_detailgoods()
        {
            this.dialog_detailgoods = false;
        },

        opendialog_stockopname(id_edit_popup_stockopname)
        {
            this.dialog_stockopname = true;
            //
        },
        closedialog_stockopname()
        {
            this.dialog_stockopname = false;
        },





        showTable(r)
        {
            
            this.data_table = r.data.items.warehouse;
        },
        convert_data_input(r)
        {
            console.log('masuk convert data');
            console.log(r);
            var temp_r = r.data.items.warehouse;
            this.input.name = temp_r.name;
            this.input.address = temp_r.address;
            this.input.telp = temp_r.telp;
            this.input.pic = temp_r.pic;
            this.input.email = temp_r.email;
            this.input.lat = temp_r.lat;
            this.input.lng = temp_r.lng;

            this.input.racks = temp_r.racks; //ini bisa berubah

            

            
            this.input_before_edit = JSON.parse(JSON.stringify(this.input));
            
        },
        prepare_data_form()
        {
            //prepare data selalu dari this.input, tapi bandingkan dulu dengan this.input_before_edit
            
            
            const formData = new FormData();



            if(this.id_data_edit != -1) //jika sedang diedit
            {

                //data yang harus dikirim saat update :
                //1. data warehouse yang BERUBAH SAJA
                //2. data rack yang BERUBAH, DITAMBAH, & DIHAPUS

                //step-step :
                //1. kirim data warehouse yang berubah
                if(this.input.name != this.input_before_edit.name) formData.append('name', this.input.name);
                if(this.input.address != this.input_before_edit.address) formData.append('address', this.input.address);
                if(this.input.telp != this.input_before_edit.telp) formData.append('telp', this.input.telp);
                if(this.input.pic != this.input_before_edit.pic) formData.append('pic', this.input.pic);
                if(this.input.email != this.input_before_edit.email) formData.append('email', this.input.email);
                if(this.input.lat != this.input_before_edit.lat) formData.append('lat', this.input.lat);
                if(this.input.lng != this.input_before_edit.lng) formData.append('lng', this.input.lng);
                
                //2. kirim data warehouse yang berubah, ditambah, dan dihapus
                
                //cek di input cocokin dengan input_before_edit
                //1. cek apakah ada id nya atau tidak, jika tidak memiliki id, pasti itu tambah baru
                //2. jika punya id, cocokan dengan input_before_edit, jika attribute lainnya sama berarti tidak diedit, jika beda berarti diedit

                //temp adalah data dari input
                //temp2 adalah data dari input_before_edit
                var counteridx = 0;
                for(var i = 0;i<this.input.racks.length;i++)
                {
                    var temp = this.input.racks[i];
                    if(temp.id == null)
                    {
                        formData.append('racks_new[' + counteridx + '][name]', temp.name);
                        counteridx++;
                    }
                    else
                    {
                        //cocokan dengan input_before_edit
                        var edittrue = false;
                        for(var j = 0;j<this.input_before_edit.racks.length;j++)
                        {
                            var temp2 = this.input_before_edit.racks[i];
                            if(temp.id == temp2.id)
                            {
                                if(temp.name != temp2.name) //jika ada salah satu saja yang berbeda, maka ini pasti diedit
                                {
                                    edittrue = true;
                                }
                                break;
                            }
                        }

                        if(edittrue)
                        {
                            formData.append('racks_update[' + counteridx + '][id]', temp.id);
                            formData.append('racks_update[' + counteridx + '][name]', temp.name);
                            counteridx++;
                        }

                    }
                }

                //cek di input_before_edit cocokin dengan input
                //1. jika ada data dengan id yang tidak ada di data input, berarti data tersebut pasti dihapus
                for(var i = 0;i<this.input_before_edit.racks.length;i++)
                {
                    var deletetrue = true;
                    for(var j=0;j<this.input.racks.length;j++)
                    {
                        if(this.input.racks[j].id == this.input_before_edit.racks[i].id)
                        {
                            deletetrue = false;
                            break;
                        }
                    }

                    if(deletetrue)
                    {
                        formData.append('racks_delete[' + counteridx + '][id]', this.input_before_edit.racks[i].id);
                        counteridx++;
                    }
                }

                
                formData.append('_method', 'patch');

            }
            else //jika sedang add
            {

                //data-data yang harus dikirim : 
                //1. semua data warehouse
                //2. semua data racks

                //step-step : 
                //1. kirim data warehouse
                formData.append('name', this.input.name);
                formData.append('address', this.input.address);
                formData.append('telp', this.input.telp);
                formData.append('pic', this.input.pic);
                formData.append('email', this.input.email);
                formData.append('lat', this.input.lat);
                formData.append('lng', this.input.lng);
                

                //2. kirim data racks

                for(var i = 0;i<this.input.racks.length;i++)
                {
                    formData.append('racks[' + i + '][adjust]',this.input.racks[i].adjust);
                    formData.append('racks[' + i + '][total]',this.input.racks[i].total);
                    formData.append('racks[' + i + '][name]',this.input.racks[i].name);

                }

                

            }

            
            

            //4. masukan token
            formData.append('token', localStorage.getItem('token'));
            return formData;
        },







    },
    computed: {
        filtered_racks()
        {
            return this.interaction.create_rack.list_rack.filter((row) => {
                return (row.warehouse_id == this.interaction.create_rack.selected_warehouse);
            });
        }
    },
    mounted(){
        //console.log('masuksini');
         this.get_data();
         this.get_my_location();
         this.get_master_data();

    },
    mixins:[
        mxCrudChildForm,
    ]
}
</script>

