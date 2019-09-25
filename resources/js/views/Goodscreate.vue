<div>
  
      <v-container fluid>
          <h3>Goods</h3>
      </v-container>
  
</div>


      
<template>

  <form style='padding:30px'>

    <v-text-field
      
      label="Name"
      required
      
    ></v-text-field>

    <v-text-field
      
      label="Description"
      required
    ></v-text-field>

    <v-text-field
      
      label="Margin"
      required
      
    ></v-text-field>
    
    <v-text-field
      
      label="Value"
      required
    ></v-text-field>

    <v-text-field
      
      label="Status"
      required
      
    ></v-text-field>

    <v-text-field
      
      label="Last Buy Price List"
      required
    ></v-text-field>

    <v-text-field
      
      label="Barcode"
      required
      
    ></v-text-field>
    
    <v-text-field
      
      label="Thumbnail"
      required
    ></v-text-field>

    <v-text-field
      
      label="AVG Price Status"
      required
      
    ></v-text-field>


    <v-text-field
      
      label="Tax"
      required
      
    ></v-text-field>
    
    

    <v-select
          :items="item_unit"
          label="Unit"
        ></v-select>

    <v-select
          :items="item_cogs"
          label="COGS"
        ></v-select>   

    <v-select
          :items="item_user"
          label="User"
        ></v-select>     

    
    

    <v-btn >submit</v-btn>
    
  </form>
</template>

<script>
  export default {
    data: () => ({

      

     

      item_unit: ['item1', 'item2', 'item3'],
      

      item_cogs: ['cogs1', 'cogs2', 'cogs3'],
      

      item_user: ['user1', 'user2', 'user3'],
      


    })
  }
</script>

