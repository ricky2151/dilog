<div>
  
      <v-container fluid>
          <h3>Goods</h3>
      </v-container>
  
</div>


      
<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>Goods Data</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn color="primary" dark>
        Add Data
      </v-btn>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="desserts"
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
        <td>{{ props.item.name }}</td>
        <td class="text-xs-right">{{ props.item.code }}</td>
        <td class="text-xs-right">{{ props.item.value }}</td>
        <td class="text-xs-right">{{ props.item.status }}</td>
        <td class="text-xs-right">{{ props.item.lastbuypricelist }}</td>
        <td class="text-xs-right">
          <v-dialog
              v-model="dialog"
              width="500"
            >

            <template v-slot:activator="{ on }">
              <label v-on="on" style='color:blue;text-decoration: underline;'>
              {{ props.item.stock }}
              </label>
            </template>

            <v-card>
              <v-card-title
                class="headline grey lighten-2"
                primary-title
              >
                Detail Stock
              </v-card-title>

              <v-card-text>
                Gudang X Rack X : 5
              </v-card-text>

              

              
            </v-card>


          </v-dialog>
        </td>
        <td>
           
            <v-btn href='/GoodsCreate' color="primary" fab small dark v-on="on">
              <v-icon small>edit</v-icon>
            </v-btn>

        </td>
        <td>
          
            <v-btn color="red" fab small dark>
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
      
        headers: [
          { text: 'Name',value: 'name'},
          { text: 'Code', value: 'code', align:'right' },
          { text: 'Value', value: 'value', align:'right' },
          { text: 'Status', value: 'status', align:'right' },
          { text: 'Last Buy Pricelist', value: 'lastbuypricelist', align:'right' },
          { text: 'Stock', value: 'stock', align:'right' }
        ],
        desserts: [
          {
            name: 'Frozen Yogurt',
            code: '159',
            value: 400,
            status: 'in stock',
            lastbuypricelist: 100000,
            stock: 5
          },
          {
            name: 'Ice cream sandwich',
            code: '237',
            value: 400,
            status: 'in stock',
            lastbuypricelist: 120000,
            stock: 6
          },
          {
            name: 'Eclair',
            code: '262',
            value: 400,
            status: 'in stock',
            lastbuypricelist: 160000,
            stock: 9
          },
          {
            name: 'Cupcake',
            code: '305',
            value: 400,
            status: 'in stock',
            lastbuypricelist: 200000,
            stock: 3
          },
          {
            name: 'Gingerbread',
            code: '356',
            value: 400,
            status: 'out of stock',
            lastbuypricelist: 120000,
            stock: 2
          },
          {
            name: 'Jelly bean',
            code: '375',
            value: 400,
            status: 'in stock',
            lastbuypricelist: 100000,
            stock: 4
          },
          
        ]
      }
    }
  }
</script>
