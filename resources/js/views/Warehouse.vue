
<div>
    <v-container fluid>
        <h3>Warehouse</h3>
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
                    <v-toolbar-title>Add Warehouse</v-toolbar-title>

                </v-toolbar>
                <v-stepper v-model="e6" vertical>

                    <!-- ==== STEPPER 1 ==== -->

                    <v-stepper-step :complete="e6 > 1" step="1" editable>
                        <h3>Goods Data</h3>
                    </v-stepper-step>

                    <v-stepper-content step="1">
                        
                            <v-text-field v-model='input.name' label="Name" required></v-text-field>
                            
                            <v-text-field v-model='input.telp' label="Telp" required></v-text-field>
                            
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

                            <v-text-field v-model='input.lat' label="Latitude" disabled required></v-text-field>
                            
                            <v-text-field v-model='input.lng' label="Longitude" disabled required></v-text-field>
                            {{input.lat}}
                            {{markers[0].position}}
                            
                            <v-text-field v-model='input.email' label="Email" required></v-text-field>
                            
                            <v-text-field v-model='input.pic' label="PIC" required></v-text-field>
                            
                            <v-btn color='primary' v-on:click='e6=2'>Continue</v-btn>
                            
                        
                    </v-stepper-content>

                    <!-- ==== STEPPER 2 ==== -->

                    <v-stepper-step :complete="e6 > 2" step="2" editable><h3>Create Rack</h3></v-stepper-step>

                    <v-stepper-content step="2">
                        <v-combobox
                            v-model='input.racks'
                            label="Type rack"
                            chips
                            clearable
                            prepend-icon="filter_list"
                            solo
                            multiple
                            >
                                <template v-slot:selection="data">
                                    <v-chip
                                      :selected="data.selected"
                                      close
                                      v-on:input="removeChip(data.item)"
                                    >
                                        <strong>{{ data.item }}</strong>
                                    </v-chip>
                                </template>
                        </v-combobox>
                        <v-btn color='primary' v-on:click='e6=3'>Continue</v-btn>
                    </v-stepper-content>

                    

                    <v-btn v-on:click='save_unit()' >submit</v-btn>

                </v-stepper>
            </v-card>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>Warehouse Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="warehouses"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.item.name }}</td>
            <td class="text-xs-right">{{ props.item.address }}</td>
            <td class="text-xs-right">{{ props.item.telp }}</td>
            <td class="text-xs-right">{{ props.item.pic }}</td>
            <td>
                <v-btn class='button-action' v-on:click='opendialog_createedit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_warehouse(props.index)' color="red" fab small dark depressed>
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

            my_location:{lat:10, lng:10},
            markers:[{
            	position:{lat:10,lng:10}
            	}],

            dialog_createedit:false,
            e6:1,
            dialog_stock:false,

            idx_data_edit:-1,

            input:{
                name:'',
                address:'',
                telp:'',
                pic:'',
                lat:null,
                lng:null,
                racks:[],
            },

            headers: [
                { text: 'Name', value: 'name'},
                { text: 'Address', value: 'address', align:'right' },
                { text: 'Telephone', value: 'telp', align:'right' },
                { text: 'PIC', value: 'pic', align:'right' },
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],


            warehouses: []
        }
    },
    methods: {

        

        removeChip(item){
            this.input.racks.splice(this.input.racks.indexOf(item), 1);
            this.input.racks = [...this.input.racks];
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

        closedialog_createedit(){
            this.dialog_createedit = false;
        },
        opendialog_createedit(idx_data_edit){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;
                
                
                this.input.name = this.warehouses[this.idx_data_edit].name;
                this.input.address = this.warehouses[this.idx_data_edit].address;
                this.input.telephone = this.warehouses[this.idx_data_edit].telephone;
                this.input.pic = this.warehouses[this.idx_data_edit].pic;
                
            }

            this.dialog_createedit = true;
        },
        
        showTable(r)
        {
            
            this.warehouses = r.data.items.warehouses;
        },
        get_warehouse() {

            axios.get('/api/warehouses', {
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
        save_warehouses()
        {
            if(this.idx_data_edit != -1) //jika sedang diedit
            {
                axios.patch('api/warehouses/' + this.warehouses[this.idx_data_edit].id,{
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
               
                axios.post('api/warehouses',{
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
        delete_warehouses(idx_data_delete){
            
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/warehouses/' + this.goods[idx_data_delete].id,{
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
        


    },
    mounted(){
        
        this.get_my_location();
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
