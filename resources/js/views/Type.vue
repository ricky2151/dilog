<div>
    <v-container fluid>
        <h3>types</h3>
    </v-container>
</div>

<template>
    <div>
        <v-dialog v-model="dialog_createedit" width=750>
            <v-card>
                <v-toolbar dark color="red">
                    <v-btn icon dark v-on:click="closedialog_createedit()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Add types</v-toolbar-title>

                </v-toolbar>
                <form style='padding:30px'>
                    <v-text-field v-model='input.name' label="Name" required></v-text-field>
                    <v-btn v-on:click='save_type()' >submit</v-btn>
                </form>
            </v-card>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>types Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="types"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.item.name }}</td>
            <td>
                <v-btn class='button-action' v-on:click='opendialog_createedit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_type(props.index)' color="red" fab small dark depressed>
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


            types: []
        }
    },
    methods: {
        closedialog_createedit(){
            this.dialog_createedit = false;
        },
        opendialog_createedit(idx_data_edit){
            if(idx_data_edit != -1)
            {
                this.idx_data_edit = idx_data_edit;

                
                this.input.name = this.types[this.idx_data_edit].name;
            }

            this.dialog_createedit = true;
        },
        
        showTable(r)
        {
            
            this.types = r.data.items.types;
        },
        get_type() {

            axios.get('/api/types', {
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
        save_type()
        {
            if(this.idx_data_edit != -1) //jika sedang diedit
            {
                axios.patch('api/types/' + this.types[this.idx_data_edit].id,{
                    name: this.input.name,
                    token: localStorage.getItem('token')
                }).then((r) => {
                    this.get_type();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                    this.idx_data_edit = -1;
                    this.input.name = '';
                });
                
                
                

                
            }
            else //jika sedang tambah data
            {
                axios.post('api/types',{
                    name: this.input.name,
                    token: localStorage.getItem('token')
                }).then((r)=> {
                    this.get_type();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                });
            }
        },
        delete_type(idx_data_delete){
            
            axios.delete('api/types/' + this.types[idx_data_delete].id,{
                data:{
                    token: localStorage.getItem('token')    
                }
                
            }).then((r)=>{
                this.get_type();
                swal("Good job!", "Data Deleted !", "success");
                
            });
        }


    },
    mounted(){
        this.get_type();
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
