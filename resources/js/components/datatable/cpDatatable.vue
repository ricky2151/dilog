<template>
	<div v-if='data_table'>
		<v-data-table
            disable-initial-sort
            :headers="prop_header"
            :items="data_table"
            :search="prop_search_data"
            class="datatable"
        >
	        <template v-slot:items="props">
	            <td>{{ props.item.no }}</td>
	            <td v-for='(obj,index) in prop_infoDatatable'>
	            {{ obj.column?props.item[obj.column]:calculate_custom_value(props.item,obj.value) }}
	        	</td>
	            
	            <td>
	                <div class="text-xs-left">
	                    <v-menu offset-y>
	                      <template v-slot:activator="{ on }">
	                        <v-btn
	                          color="primary"
	                          dark
	                          v-on="on"
	                          class='btnaction'
	                        >
	                          Action
	                        </v-btn>
	                      </template>
	                      <v-list>
	                        <v-list-tile
	                          v-for="(item, index) in prop_action_items"
	                          :key="index"
	                          v-on:click="$emit('action_clicked',props.item.id,index)"
	                        >
	                          <v-list-tile-title>{{ item }}</v-list-tile-title>
	                        </v-list-tile>
	                      </v-list>
	                    </v-menu>
	                </div>
	                

	            </td>
	        </template>
        </v-data-table>
	</div>
</template>
<script>
	export default {
		props:[
			'prop_header',
			'prop_search_data',
			'prop_infoDatatable',
			'prop_action_items',
			'prop_plural_name',
			'prop_url_index',
			'prop_filter'
		],
		data () {
			return {
				data_table:[],
				header_api:{
	                'Accept': 'application/json',
	                'Content-type': 'application/json'
	            },
			}
		},
		methods: {
			get_data() {

	            axios.get(this.prop_url_index, {
	                params:{
	                    token: localStorage.getItem('token')
	                }
	            },this.header_api).then((r) => {
	            	this.showTable(r);
	            	console.log(r.data);
	            	for(var i = 0;i<this.data_table.length;i++)
	            	{
	            		this.data_table[i].no = this.data_table.length - i;
	            	}
	            })
	        },
	        showTable(r)
        	{
	            this.data_table = r.data.items[this.prop_plural_name];
	        },

			calculate_custom_value(data, value)
			{
				//data adalah row dari database
				//value adalah ['price', '*', 'qty']
				var result = data[value[0]];
				for(var i = 1;i<value.length;i+=2)
				{
					if(value[i] == '+')
						result += data[value[i + 1]];
					else if(value[i] == '-')
						result -= data[value[i + 1]];
					else if(value[i] == '*')
						result *= data[value[i + 1]];
					else if(value[i] == '/')
						result /= data[value[i + 1]];
				}
				return result;

			},
		},
		mounted(){
			this.get_data();
		}
	}
</script>