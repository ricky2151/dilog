<template>
    <div class='bgwhite'>

        <v-breadcrumbs divider=">" :items='computed_breadcrumbs' class='breadcrumbs'>
            </v-breadcrumbs-item>
            <v-breadcrumbs-item
                slot="item"
                slot-scope="{ item }"
                exact
                :disabled='item.disabled'
                @click="item.disabled ? '' : open_component(item.cp)"
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
    computed : 
    {
        computed_breadcrumbs: function () {
          var result_breadcrumbs = JSON.parse(JSON.stringify(this.breadcrumbs));
          var i = 0;

          //hapus item breadcrumb yang gak kepake
          while(i<result_breadcrumbs.length)
          {
            if(result_breadcrumbs[i].disabled)
            {
                result_breadcrumbs.splice(i,1);
                i--;
            }

            i++;

          }

          //beri note jika ada note yang diperlukan
          i = 0;
          while(i<result_breadcrumbs.length)
          {
            if(result_breadcrumbs[i].text_note)
            {
                result_breadcrumbs[i].disabled=  true;
                result_breadcrumbs.splice(i,0, {
                    text : result_breadcrumbs[i].text_note,
                    disabled : true,
                });
                i++;
            }

            i++;

          }
          if(result_breadcrumbs.length == 1)
          {
            return [];
          }
          else
          {
              return result_breadcrumbs;
          }
        }
    },
}
</script>


