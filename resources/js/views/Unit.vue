<div>
    <v-container fluid>
        <h3>Units</h3>
    </v-container>
</div>

<template>
    <div>
        <v-dialog v-model="dialog_createedit" width=750>
            <v-card>
                <v-toolbar dark color="red">
                    <v-btn icon dark @click="closedialog_createedit()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Add Units</v-toolbar-title>

                </v-toolbar>
                <form style='padding:30px'>
                    <v-text-field v-model='in_name' label="Name" required></v-text-field>
                    <v-btn v-on:click='post_unit()' >submit</v-btn>
                </form>
            </v-card>
        </v-dialog>

        <v-toolbar flat color="white">
            <v-toolbar-title>Units Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click='opendialog_createedit()' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            disable-initial-sort
            :headers="headers"
            :items="units"
            class=""
        >
        <template v-slot:items="props">
            <td>{{ props.item.name }}</td>
            <td>
                <v-btn class='button-action' v-on:click='opendialog_createedit()' color="primary" fab depressed small dark v-on="on">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <v-btn class='button-action' color="red" fab small dark depressed>
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
            in_name:'',
            headers: [
                { text: 'Name', value: 'name'},
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],
            units: []
        }
    },
    methods: {
        closedialog_createedit(){
            this.dialog_createedit = false;
        },
        opendialog_createedit(){
            this.dialog_createedit = true;
        },
        showTable(r)
        {
            this.units = r.data.data.units
        },
        get_unit() {
            axios.get('/api/units', {
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
        post_unit(){
            axios.post('api/units',{
                name: this.in_name,
                token: localStorage.getItem('token')
            })
        }

    },
    mounted(){
        this.get_unit()
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
