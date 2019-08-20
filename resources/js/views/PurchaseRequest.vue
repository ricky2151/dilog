<template>
    <div class='bgwhite'>

        

        <template v-if='open_state == "PurchaseRequest"'>
            <cp-purchase-request prop_list_filter='' ></cp-purchase-request>
        </template>
        
    </div>
    
</template>

<script>
import axios from 'axios'
import cpPurchaseRequest from './../components/child_crud/cpPurchaseRequest.vue'

export default {
    components:{
        cpPurchaseRequest,
    },
    data () {
        return {
            
            
            open_state : 'PurchaseRequest',
            list_state : 
            {
                'PurchaseRequest' : {},
            },
            
            breadcrumbs:[
                //level 1
                {
                    text: 'Purchase Request',
                    disabled: false,
                    cp : 'PurchaseRequest',
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


