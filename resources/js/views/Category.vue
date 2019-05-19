<div>
    <v-container fluid>
        <h3>categories</h3>
    </v-container>
</div>

<template>
    <div>
        <v-dialog v-model="dialog_createedit" width=750 >
            <v-form v-model="valid" ref='formCreateEdit'>
                <v-card>
                    <v-toolbar dark color="menu">
                        <v-btn icon dark v-on:click="closedialog_createedit()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Add categories</v-toolbar-title>

                    </v-toolbar>
                    <div class='padding30'>
                        <v-text-field v-model='input.name' label="Name" required></v-text-field>
                        <v-btn v-on:click='save_category()' >submit</v-btn>
                    </div>
                </v-card>
            </v-form>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>categories Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="categories"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.item.name }}</td>
            <td>
                <v-btn class='button-action' v-on:click='opendialog_createedit(props.index)' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' v-on:click='delete_category(props.index)' color="red" fab small dark depressed>
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


            categories: []
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

                
                this.input.name = this.categories[this.idx_data_edit].name;
            }

            this.dialog_createedit = true;
        },
        
        showTable(r)
        {
            
            this.categories = r.data.items.categories;
        },
        get_category() {

            axios.get('/api/categories', {
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
        save_category()
        {
            if(this.idx_data_edit != -1) //jika sedang diedit
            {
                axios.patch('api/categories/' + this.categories[this.idx_data_edit].id,{
                    name: this.input.name,
                    token: localStorage.getItem('token')
                }).then((r) => {
                    this.get_category();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                    this.idx_data_edit = -1;
                    this.input.name = '';
                });
                
                
                

                
            }
            else //jika sedang tambah data
            {
                axios.post('api/categories',{
                    name: this.input.name,
                    token: localStorage.getItem('token')
                }).then((r)=> {
                    this.get_category();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                });
            }
        },
        delete_category(idx_data_delete){
            
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/categories/' + this.categories[idx_data_delete].id,{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.get_category();
                            swal("Good job!", "Data Deleted !", "success");
                            
                        });
                    }
            });
        }


    },
    mounted(){
        this.get_category();
    },
}
</script>


