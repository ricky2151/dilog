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
		    		(prop_filter_by_user_value == 0)
		    		||
    				(prop_filter_by_user_value && prop_format_filter_by_user.column_in_table && props.item[prop_format_filter_by_user.column_in_table] == prop_filter_by_user_value) 
    				||
    				(!prop_filter_by_user_value) '
    			>

					<v-checkbox
					class='datatable_checklist'
					v-if='checklisting'
		            v-model="data_table[props.index]['checked']"
		            color="primary"
			            
			         ></v-checkbox>
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
			'prop_format_filter_by_user',
			'prop_filter_by_user_value',
			'prop_get_additional_data',
			'prop_custom_response_attribute',
		],
		data () {
			return {
				checklisting : false,
				data_table:[],
				header_api:{
	                'Accept': 'application/json',
	                'Content-type': 'application/json'
	            },
			}
		},
		methods: {
			convert_to_checklist(val)
			{
				if(val == true)
				{
					this.checklisting = true;
					this.prop_header.splice(0,0,{ text: '',  width:'3%', value:'checklist'});
				}
				else if(val == false)
				{
					this.checklisting = false;
					this.prop_header.splice(0,1);
				}
			},
			send_filter_by_user_ref(r)
			{
				var result = r.data.items[this.prop_format_filter_by_user.response_attribute];
				result.splice(0,0,{"id" : 0, 'name' : 'All'});
				this.$emit('response_filter_by_user_ref', result);
			},
			get_data() {

	            axios.get(this.prop_url_index, {
	                params:{
	                    token: localStorage.getItem('token')
	                }
	            },this.header_api).then((r) => {
	            	

	            	this.showTable(r);
	            	if(this.prop_format_filter_by_user)
	            	{
	            		this.send_filter_by_user_ref(r);
	            	}
	            	for(var i = 0;i<this.data_table.length;i++)
	            	{
	            		this.data_table[i].no = this.data_table.length - i;
	            	}

	            	//jika ada additional data, emit data tersebut lalu dikirimkan ke cpheader
	            	if(this.prop_get_additional_data)
	            	{
	            		this.$emit('show_additional_data', r.data.items[this.prop_filter['table_parent']]);
	            	}
	            })
	        },
	        showTable(r)
        	{
        		var temp_r;
        		var response_attribute;
        		if(this.prop_custom_response_attribute)
        		{
        			response_attribute = this.prop_custom_response_attribute;
        		}
        		else
        		{
        			response_attribute = this.prop_plural_name;
        		}
        		//jika adaadditional_data, maka format r nya agak beda
        		if(this.prop_get_additional_data)
        		{
        			temp_r = r.data.items[this.prop_filter['table_parent']][response_attribute];
        		}
        		else
    			{
    				temp_r = r.data.items[response_attribute];
    			}
        		
        		// else if(this.prop_filter_by_user_value && this.prop_format_filter_by_user.column_in_table)
        		// {
        		// 	console.log('harusnya masuk sini');
        		// 	for(var i = 0;i<temp_r.length;i++)
		        //     {
		        //     	console.log(i);
		        //         if(temp_r[i][this.prop_format_filter_by_user.column_in_table] == this.prop_filter_by_user_value)
		        //         {
		        //             this.data_table.push(temp_r[i]);
		        //         }
		        //     }
        		// }
		        this.data_table = temp_r;
        		
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