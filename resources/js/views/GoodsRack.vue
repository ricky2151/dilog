<template>
    <div class='bgwhite'>

        <v-breadcrumbs divider=">" :items='breadcrumbs' class='breadcrumbs'>
            <v-breadcrumbs-item
                slot="item"
                slot-scope="{ item }"
                exact
                :class="{breadcrumbs_hidden : item.disabled}"
                @click="open_component(item.cp)"
                >

                {{ item.text }}
            </v-breadcrumbs-item>
        </v-breadcrumbs>

        <template v-if='open_state == "GoodsRack"'>
            <cp-goods-rack prop_list_filter='' ></cp-goods-rack>
        </template>
        
    </div>
    
</template>

<script>
import axios from 'axios'
import cpGoodsRack from './../components/child_crud/cpGoodsRack.vue'

export default {
    name:'GoodsRack',
    components:{
        cpGoodsRack,
    },
    data () {
        return {
            id_goods_for_table:'all',
            
            open_state : 'GoodsRack',
            list_state : 
            {
                'GoodsRack' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'GoodsRack',
                    disabled: false,
                    cp : 'GoodsRack',
                    before : null,
                },
                //level 2
                
            ],
            
        }
    },
    mounted(){
        
        
        
        

    },
}
</script>


