<template>
	<div v-if='finish_loading'>
		<v-select v-if='prop_id_edit == -1' v-model='interaction.create.method' :items="static_select" item-text='name' item-value='id' label="Select Method"></v-select>

        <!-- CREATE NEW CHILD -->
        <v-combobox
            v-show='interaction.create.method == 0 || prop_id_edit != -1'
            v-model='input[prop_dataInfo.child.table_name]'
            :label='"Type " + prop_dataInfo.child.title'
            chips
            clearable
            prepend-icon="filter_list"
            solo
            multiple
            v-on:keyup.enter="updateChip()"
            >
                <template v-slot:selection="data">
                    <v-chip
                      :selected="data.selected"
                      close
                      
                      v-on:input="removeChip(data.item)"

                    >
                        <strong>{{ data.item[prop_dataInfo.child.column_show] }}</strong>
                    </v-chip>
                </template>
        </v-combobox>

        <!-- COPY FROM PARENT -->
        <div v-show='interaction.create.method == 1 && prop_id_edit == -1'>
            <v-select v-model='interaction.create.selected_parent' :items='prop_masterDataParent' item-text='name' item-value='id' label='Select Warehouse'>
            </v-select>
            <b>Select Rack</b>
            <v-data-table
                :headers="prop_headerChild"
                :items='filtered_racks'
            >
                <template v-slot:items="props">
                    <td> 
                        
                        <v-checkbox v-model='filtered_racks[props.index].select_rack' :label='props.item.name' class=''></v-checkbox>
                        
                    </td>
                    <td> 
                        
                        <v-checkbox v-model='filtered_racks[props.index].select_copy' v-if='filtered_racks[props.index].is_have_goods == true' :label='"Copy Goods"' class=''></v-checkbox>
                        
                    </td>
                </template>

            </v-data-table>
        </div>
    </div>
</template>
<script>
	export default {
		props : [
		'prop_id_edit',
		'prop_dataInfo',
		'prop_masterDataParent'
		'prop_headerChild'
		],

		data () {
			return {

				//for element
				interaction : 
				{
					create : 
					{
						method : null,
						selected_parent : null,
					},
				},

				static_select : 
				[ 
					{id : 0, name:'create New'},
					{id : 1, name:'Copy From '},
				],

				finish_loading : false,

				//for data
				input : 
				{

				}
				



			}
		},

		methods : 
		{
			removeChip(item){
				var temp_table_name_child = this.prop_dataInfo.child.table_name
                this.input[temp_table_name_child].splice(this.input[temp_table_name_child].indexOf(item), 1);
                this.input[temp_table_name_child] = [...this.input[temp_table_name_child]];
            },
            updateChip(item)
            {
                
                var data_input_child = this.input[temp_table_name_child];
                var tempdata = data_input_child[data_input_child.length - 1];
                this.removeChip(tempdata);
            }
               
		},

		mounted() {
			//isi static select
			this.static_select[1].name += this.prop_dataInfo.parent.title;

			//daftarkan semua index
			this.input[this.prop_dataInfo.child.table_name] = null,

			//finish loading
			this.finish_loading = true;
		}
	}
</script>