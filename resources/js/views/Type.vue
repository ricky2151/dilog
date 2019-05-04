<div>
  
    <v-container fluid>
        <h3>Types</h3>
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
                    <v-toolbar-title>Add Type</v-toolbar-title>
                    
                </v-toolbar>
                <form style='padding:30px'>
                    <v-text-field label="Name" required></v-text-field>

                     

                    <v-btn >submit</v-btn>
                    
                </form>
            </v-card>
        </v-dialog>


        <v-toolbar flat color="white">
            <v-toolbar-title>Types Data</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click='opendialog_createedit()' color="primary" dark>
                Add Data
            </v-btn>
        </v-toolbar>
        <v-data-table
            id='table-types'
            :headers="headers"
            :items="types"
            class=""
        >
            <template slot="headerCell" slot-scope="props">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <span v-on="on">
                            {{ props.header.text }}
                        </span>
                    </template>
                    <span>
                        {{ props.header.text }}
                    </span>
                </v-tooltip>
            </template>
        <template v-slot:items="props">
            <td>{{ props.item.no }}</td>
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

export default {
    data () {
        return {
            on:false,
            dialog_createedit:false,
            dialog_stock:false,
            headers: [
                { text: 'No.',value: 'no'},
                { text: 'Name',value: 'name'},
                
                { text: 'Action', align:'left',width:'15%',sortable:false},
            ],
            types: [
            {
                no : 1,
                name: 'HPP Penjualan',
                
            },
            {
                no : 2,
                name: 'HPP Pembelian',
                
            },
            

            ]
        }
    },
    methods: {
        closedialog_createedit(){
            this.dialog_createedit = false;
        },
        opendialog_createedit(){
            this.dialog_createedit = true;
        },
        fetchTypes() {
            //axios
            return "";
        },

        


    }
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