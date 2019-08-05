<div>
    <v-container fluid>
        <h3>Goods Rack</h3>
    </v-container>
</div>

<template>
    <div>
        <cp-goods-rack :prop_list_filter='[["goods", "-1"]]' ></cp-goods-rack>
        
    </div>
    
</template>

<script>
import axios from 'axios'
import cpGoodsRack from './../components/cpGoodsRack.vue'

export default {
    name:'GoodsRack',
    components:{
        cpGoodsRack,
    },
    data () {
        return {
            id_goods_for_table:'all'
            
            
        }
    },
    mounted(){
        
        
        
        

    },
}
</script>


