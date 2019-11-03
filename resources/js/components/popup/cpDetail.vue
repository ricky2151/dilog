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
                        <td>{{ props.item.no }}</td>
                        <td v-for='(obj,column_name) in prop_columns' v-if='obj.show'>
                            {{obj.format?
                                    format_data(props.item[column_name],obj.format) : 
                                props.item[column_name]}}
                        </td>
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
            
            strToPrice(angka,prefix)
            {
                //100000
                //9.000
                //11212
                //11.212
                angka = angka.toString();
                var hasil = "";
                var counter = 0;
                for(var i = angka.length - 1;i>= 0;i--)
                {
                    counter++;
                    if(counter % 3 == 0)
                    {
                        if(i != 0)
                            hasil = "." + angka.charAt(i) +  hasil;
                        else
                                hasil = angka.charAt(i) + hasil;
                    }
                    else
                    {
                        hasil = angka.charAt(i) + hasil;
                    }
                }
                return prefix + hasil;
            },
            format_data(value,types)
            {
                var result = value;
                result = Math.ceil(result);
                for(var i = 0;i<types.length;i++)
                {
                    var type = types[i];
                    if(type == 'price')
                    {
                        result = this.strToPrice(result,"Rp. ");
                    }
                    else if(type == 'percent')
                    {
                        result = result + "%";
                    }
                    else if(type == 'approveornot')
                    {
                        if(result == 0)
                        {
                            result = 'New';
                        }
                        else
                        {
                            result = 'Approved';
                        }
                    }
                    else if(type == 'freeornot')
                    {
                        if(result == 0)
                        {
                            result = 'Not Free';
                        }
                        else
                        {
                            result = 'Free';
                        }
                    }
                    else if(type == 'havegoodsornot')
                    {
                        if(result == 0)
                        {
                            result = 'Not Have Goods';
                        }
                        else
                        {
                            result = 'Have Goods';
                        }   
                    }
                    else if(type == 'statusmaterialrequest')
                    {
                        //Status is condition this Material Request where value 2 = diproses ,1 = approved, 0 = new
                        if(result == 0)
                        {
                            result = 'New';
                        }
                        else if(result == 1)
                        {
                            result = 'Have Goods';
                        } 
                        else if(result == 2)
                        {
                            result = 'Proccess';  
                        }
                    }
                }
                return result;
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

                    for(var i = 0;i<this.data.length;i++)
                    {
                        this.data[i].no = i + 1;
                    }
                    
                });

            }



        }
    }

</script>