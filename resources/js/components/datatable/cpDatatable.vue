<template>
	<div v-if='data_table'>

		<v-data-table
            disable-initial-sort
            :headers="prop_header"
            :items="computed_data_table"
            :search="prop_search_data"
            class="datatable"
            
            
        >
        	
	        	
			<template v-slot:items="props">
		    	
					<v-checkbox
					class='datatable_checklist'
					v-if='checklisting'
		            
		            v-model="props.item.checked"
		            color="primary"
			            
			         ></v-checkbox>
			        <td>{{ props.item.no }}</td>
			        <td v-for='(obj,index) in prop_infoDatatable'>
						{{ 
							obj.column?
								obj.format?
									format_data(props.item[obj.column],obj.format) : 
								props.item[obj.column]:
								obj.format?
									format_data(calculate_custom_value(props.item,obj.value), obj.format):
							calculate_custom_value(props.item,obj.value) 
						}}
			    	</td>
			        <td v-show='prop_action_items.length > 0'>
			            <div class="text-xs-left" v-if='(prop_conditional_action_button && check_conditional_action_button(props.item, prop_conditional_action_button)) || (!prop_conditional_action_button)'>
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
			                      v-for="(item, index_action) in prop_action_items"
			                      :key="index_action"
			                      v-if='prop_conditional_action && prop_conditional_action[index_action].length > 0 ? check_conditional_action(props.item[prop_conditional_action[index_action][0]],prop_conditional_action[index_action][1],prop_conditional_action[index_action][2]) : true '
			                      v-on:click="$emit('action_clicked',props.item.id,index_action, props.item)"
			                    >
			                      <v-list-tile-title >{{ item }}</v-list-tile-title>
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
			'prop_trigger_after_refresh_data',
			'prop_conditional_action',
			'prop_conditional_action_button'
		],
		watch : 
		{

		},
		computed : 
		{
			computed_data_table : function() 
			{

				var data_table_now = JSON.parse(JSON.stringify(this.data_table));
				var result = [];
				if((this.prop_filter_by_user_value && this.prop_format_filter_by_user.column_in_table))
				{
					if(this.prop_filter_by_user_value != '0')
					{
						for(var i = 0;i<data_table_now.length;i++)
						{
							if(data_table_now[i][this.prop_format_filter_by_user.column_in_table] == this.prop_filter_by_user_value)
							{
								result.push(data_table_now[i]);
							}
						}
						for(var i = 0;i<result.length;i++)
		            	{
		            		result[i].no = result.length - i;
		            	}
						return result;
					}
					else
					{

						return data_table_now;
					}
				}
				else
				{
					return data_table_now;
				}
				
			}
		},
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
			check_conditional_action_button(data,requireif)
			{
				console.log('cek prop_conditional_action_button');
				console.log(data);
				console.log(requireif);
				var result = true;
				if(requireif[1] == '==')
				{
					if(data[requireif[0]] == requireif[2])
					{
						result = true;
					}
					else
					{
						result = false;
					}
				}
				return result;
			},
			check_conditional_action(data,logical,truevalue)
			{
				console.log('masuk check_conditional_action');
				console.log(data);
				console.log(logical);
				console.log(truevalue);
				if(logical == '==')
				{
					if(data == truevalue)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			},
			clear_checklisted()
			{
				for(var i = 0;i<this.data_table.length;i++)
				{
					this.data_table[i].checked = false;
				}
			},
			get_checklisted()
			{

				var filtered = [];
				for(var i = 0;i<this.computed_data_table.length;i++)
				{
					if(this.computed_data_table[i].checked)
					{
						filtered.push(this.computed_data_table[i]);
					}
				}
				
				
				return filtered;
			},
			strToPrice(angka,prefix)
	        {
	            //100000
	            //9.000
	            //11212
	            //11.212
	            angka = angka.toString();
	            var hasil = "";
	            var counter = 0;
	            for(var i = angka.length - 1;i>= 0;i--)
	            {
	                counter++;
	                if(counter % 3 == 0)
	                {
	                    if(i != 0)
	                        hasil = "." + angka.charAt(i) +  hasil;
	                    else
	                            hasil = angka.charAt(i) + hasil;
	                }
	                else
	                {
	                    hasil = angka.charAt(i) + hasil;
	                }
	            }
	            return prefix + hasil;
	        },
			format_data(value,types)
			{
				var result = value;
				result = Math.ceil(result);
				for(var i = 0;i<types.length;i++)
				{
					var type = types[i];
					if(type == 'price')
					{
						result = this.strToPrice(result,"Rp. ");
					}
					else if(type == 'percent')
					{
						result = result + "%";
					}
					else if(type == 'approveornot')
					{
						if(result == 0)
						{
							result = 'New';
						}
						else
						{
							result = 'Approved';
						}
					}
				}
				return result;
			},
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
	            	if(this.prop_trigger_after_refresh_data)
	            	{
	            		this.$emit('data_refreshed');
	            	}


	            })
	        },
	        showTable(r)
        	{
        		var temp_r = [];
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
        		
        		if(!temp_r)
        		{
        			temp_r = [];
        		}
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