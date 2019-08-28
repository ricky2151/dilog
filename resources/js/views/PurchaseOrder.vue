<template>
    <div class='bgwhite'>

        <v-layout>
            <v-flex  sm4 style='padding:50px'>
                <v-card>
                    <v-img
                      src="https://cdn2.iconfinder.com/data/icons/euro-banking-rounded/512/xxx037-512.png"
                      height='200px'
                      contain
                    ></v-img>

                    <v-card-title primary-title>
                        <div style='width: 100%'>
                            <h3 class="headline text-lg-center"><b>PO Direct</b></h3>
                            <div>  </div>
                        </div>
                    </v-card-title>

                    <v-card-actions>
                        <v-btn @click='goto("/podirect")' style='width:100%' flat color="blue">Detail</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <v-flex  sm4 style='padding:50px'>
                <v-card>
                    <v-img
                      src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSwR_N7j5Mu5aW1roiU-N_6egPrieUlBkyZmvq2LFX9zp9Ezwol3Q"
                      height='200px'
                      contain
                    ></v-img>

                    <v-card-title primary-title>
                        <div style='width: 100%'>
                            <h3 class="headline text-lg-center"><b>Material Request</b></h3>
                            <div>  </div>
                        </div>
                    </v-card-title>

                    <v-card-actions>
                        <v-btn @click='goto("/materialrequest")' style='width:100%' flat color="blue">Detail</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <v-flex  sm4 style='padding:50px'>
                <v-card>
                    <v-img
                      src="https://cdn.worldvectorlogo.com/logos/dropbox-2.svg"
                      height='200px'
                      contain
                    ></v-img>

                    <v-card-title primary-title>
                        <div style='width: 100%'>
                            <h3 class="headline text-lg-center"><b>Stock Min</b></h3>
                            <div>  </div>
                        </div>
                    </v-card-title>

                    <v-card-actions>
                        <v-btn style='width:100%' flat color="blue">Detail</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import mxCrudBasic from '../mixin/mxCrudBasic';

export default {
    data () {
        return {
            info_table:{},
            name_table:'units',
            search_data: null,

            open_state : 'Unit',
            list_state : 
            {
                'Unit' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'Unit',
                    disabled: false,
                    cp : 'Unit',
                    before : null,
                },
                //level 2
                
            ],
        }
    },
    methods: {
        goto(url)
        {
            this.$router.replace(url);
        }

    }
}
</script>

