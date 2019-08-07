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
		    	<template v-if='
    				(prop_filter_by_user_value && prop_get_unique_value && props.item[prop_get_unique_value] == prop_filter_by_user_value) 
    				||
    				(!prop_filter_by_user_value) '
    			>
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
			                      v-on:click="$emit('action_clicked',props.item.id,index, props.item)"
			                    >
			                      <v-list-tile-title>{{ item }}</v-list-tile-title>
			                    </v-list-tile>
			                  </v-list>
			                </v-menu>
			            </div>
			        </td>
			    </template>
			</template>
        </v-data-table>
	</div>
</template>
<script>
	import mxVariableProcess from '../../mixin/mxVariableProcess';
	export default {
		mixins:[
		    mxVariableProcess
		],
		props:[
			'prop_header',
			'prop_search_data',
			'prop_infoDatatable',
			'prop_action_items',
			'prop_plural_name',
			'prop_url_index',
			'prop_filter',
			'prop_get_unique_value',
			'prop_filter_by_user_value',
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

			send_unique_value()
			{
				var result = [];
				for(var i = 0;i<this.data_table.length;i++)
				{
					result[i] = this.data_table[i][this.prop_get_unique_value];
				}
				console.log('filter_by_user 1');
				console.log(result);
				result = result.filter((v, i, a) => a.indexOf(v) === i); 
				console.log('filter_by_user 2');
				console.log(result);
				this.$emit('response_unique_value', result);
			},
			get_data() {

	            axios.get(this.prop_url_index, {
	                params:{
	                    token: localStorage.getItem('token')
	                }
	            },this.header_api).then((r) => {
	            	this.showTable(r);
	            	if(this.prop_get_unique_value)
	            	{
	            		this.send_unique_value();
	            	}
	            	for(var i = 0;i<this.data_table.length;i++)
	            	{
	            		this.data_table[i].no = this.data_table.length - i;
	            	}
	            })
	        },
	        showTable(r)
        	{
        		
        		var temp_r = r.data.items[this.prop_plural_name];
        		if(this.prop_filter)
        		{
        			Object.keys(this.prop_filter).map(function(key, index) {
        				var temp_column_filter = key;
						var temp_value_filter = this.prop_filter[key];
	        			for(var i = 0;i<temp_r.length;i++)
			            {
			                if(temp_r[i][temp_column_filter] == temp_value_filter)
			                {
			                    this.data_table.push(temp_r[i]);
			                }
			            }
					});
        			
        		}
        		// else if(this.prop_filter_by_user_value && this.prop_get_unique_value)
        		// {
        		// 	console.log('harusnya masuk sini');
        		// 	for(var i = 0;i<temp_r.length;i++)
		        //     {
		        //     	console.log(i);
		        //         if(temp_r[i][this.prop_get_unique_value] == this.prop_filter_by_user_value)
		        //         {
		        //             this.data_table.push(temp_r[i]);
		        //         }
		        //     }
        		// }
        		else
        		{
		            this.data_table = temp_r;
        		}
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
		watch : {

		},
		mounted(){
			this.get_data();
		},
		
	}
</script>