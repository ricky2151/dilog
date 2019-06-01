<div>
    <v-container fluid>
        <h3>categories</h3>
    </v-container>
</div>

<template>
    <div>
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
                        <td>{{ props.item.name }}</td>
                        <td>{{ props.item.stock }}</td>
                    </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-dialog>


        <v-dialog v-model="dialog_createedit" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="closedialog_createedit()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title v-html='idx_data_edit == -1 ?"Add Categories":"Edit Categories"'></v-toolbar-title>

                </v-toolbar>
                <form style='padding:30px'>
                    <v-text-field v-model='input.name' label="Name" required></v-text-field>
                    <v-btn v-on:click='save_category()' >submit</v-btn>
                </form>
            </v-card>
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
                <v-btn class='button-action' v-on:click='opendialog_detailgoods(props.index)' color="primary" block dark v-on="on">
                    Goods
                </v-btn>
            </td>
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
            dialog_detailgoods:false,
            dialog_stock:false,

            idx_data_edit:-1,

            input:{
                name:'',    
            },
            

            headers: [
                { text: 'Name', value: 'name',width:'70%'},
                { text: 'Goods', align:'left',width:'15%',sortable:false},
                { text: 'Action', align:'left',width:'15%',sortable:false},

            ],

            headers_popup_detailgoods : [
                { text: 'No', value:'no'},
                { text: 'Goods', value:'goods'},
                { text: 'Stock', value:'stock'},

            ],

            categories: [],

            popup_detailgoods :
            [
                {
                    goods:'meja',
                    stock:12,
                },
                {
                    goods:'kursi',
                    stock:13,
                },
                {
                    goods:'indomie',
                    stock:10,
                },
            ],
            popup_search_detailgoods:null,
        }
    },
    methods: {
        closedialog_detailgoods(){
            this.dialog_detailgoods = false;
        },
        opendialog_detailgoods(idx_edit_popup_detailgoods)
        {

            this.dialog_detailgoods = true;
            this.get_popup_detailgoods(idx_edit_popup_detailgoods);

        },
        closedialog_createedit(){
            this.idx_data_edit = -1;
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
        },
        get_popup_detailgoods(idx_edit_popup_detailgoods){
            axios.get('api/categories/' + this.categories[idx_edit_popup_detailgoods].id + '/goods',{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                this.popup_detailgoods = r.data.items.categories;
            })
        }


    },
    mounted(){
        this.get_category();
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
