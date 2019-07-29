<template>
    <div>       
        
        <!-- POPUP DETAIL RACK -->
        <v-dialog v-model="dialog" width=750>
            <v-card>
                <v-toolbar dark color="menu">
                    <v-btn icon dark v-on:click="close_dialog()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{prop_title}}</v-toolbar-title>

                </v-toolbar>
                <div style='padding:30px'>
                    
                    <v-text-field
                        v-model="search_data"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                      ></v-text-field>
                    <v-data-table
                    disable-initial-sort
                    :headers="prop_headers"
                    :items="data"
                    :search="search_data"
                    class=""
                    >
                    <template v-slot:items="props">
                        <td>{{ props.index + 1 }}</td>
                        <td v-for='(obj,column_name) in prop_columns' v-if='obj.show'>{{props.item[column_name]}}</td>
                    </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>

    import axios from 'axios'
    export default{
        props: [
        'prop_title', 
        'prop_response_attribute', 
        'prop_headers', 
        'prop_columns', 
        'prop_read'
        ],
        data(){
            return{
                //for element
                dialog:null,
                search_data:null,

                //for data
                data:[],
                url:null,

                //for element & data
                

            }
        },
        methods:
        {
            //for element
            close_dialog()
            {
                this.dialog = false;
            },
            open_dialog()
            {
                
                
                this.dialog = true;
                this.get_data();
            },
            


            //for data
            get_data()
            {
                axios.get(
                    this.url,
                    {
                        params : {
                            token: localStorage.getItem('token')
                        }
                    }
                ).then((r) => {
                    this.data = r.data.items[this.prop_response_attribute];
                });

            }



        }
    }

</script>