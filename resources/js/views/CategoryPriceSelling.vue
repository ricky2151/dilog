<div>
    <v-container fluid>
        <h3>Price Category</h3>
    </v-container>
</div>

<template>
    <div>
        <v-dialog v-model="dialog_createedit" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_createedit()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title v-html='idx_data_edit == -1 ?"Add Price Category":"Edit Price Category"'></v-toolbar-title>

                </v-toolbar>
                <form style='padding:30px'>
                    <v-text-field v-model='input.name' label="Name" required></v-text-field>
                    <v-btn v-on:click='save_categorypriceselling()' >submit</v-btn>
                </form>
            </v-card>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>Price Category Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="categorypricesellings"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.item.name }}</td>
            <td>
                <v-btn class='button-action' v-on:click='opendialog_createedit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_categorypriceselling(props.index)' color="red" fab small dark depressed>
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
            dialog_stock:false,

            idx_data_edit:-1,

            input:{
                name:'',    
            },
            

            headers: [
                { text: 'Name', value: 'name'},
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],


            categorypricesellings: []
        }
    },
    methods: {
        closedialog_createedit(){
            this.idx_data_edit = -1;
            this.dialog_createedit = false;
        },
        opendialog_createedit(idx_data_edit){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;

                
                this.input.name = this.categorypricesellings[this.idx_data_edit].name;
            }

            this.dialog_createedit = true;
        },
        
        showTable(r)
        {
            
            this.categorypricesellings = r.data.items.categorypricesellings;
        },
        get_categorypriceselling() {

            axios.get('/api/categorypricesellings', {
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
        save_categorypriceselling()
        {
            if(this.idx_data_edit != -1) //jika sedang diedit
            {
                axios.patch('api/categorypricesellings/' + this.categorypricesellings[this.idx_data_edit].id,{
                    name: this.input.name,
                    token: localStorage.getItem('token')
                }).then((r) => {
                    this.get_categorypriceselling();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                    this.idx_data_edit = -1;
                    this.input.name = '';
                });
                
                
                

                
            }
            else //jika sedang tambah data
            {
                axios.post('api/categorypricesellings',{
                    name: this.input.name,
                    token: localStorage.getItem('token')
                }).then((r)=> {
                    this.get_categorypriceselling();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                });
            }
        },
        delete_categorypriceselling(idx_data_delete){
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/categorypricesellings/' + this.categorypricesellings[idx_data_delete].id,{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.get_categorypriceselling();
                            swal("Good job!", "Data Deleted !", "success");
                            
                        });
                    }
            });
            
        }


    },
    mounted(){
        this.get_categorypriceselling();
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
