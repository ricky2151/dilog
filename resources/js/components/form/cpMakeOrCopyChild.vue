<template>
	<div v-if='finish_loading'>
		<v-select v-if='prop_id_edit == -1' v-model='interaction.create.method' :items="static_select" item-text='name' item-value='id' label="Select Method"></v-select>

        <!-- CREATE NEW CHILD -->
        
        <v-combobox
            v-show='interaction.create.method == 0 || prop_id_edit != -1'
            v-model='input.new'
            :label='"Type " + prop_dataInfo.child.title'
            chips
            clearable
            
            prepend-icon="filter_list"
            solo
            multiple
            
            >
                <template v-slot:selection="data">
                    <v-chip
                      :selected="data.selected"
                      close
                      v-on:input='removeChip(data)'
                      

                    >
                        <strong>{{ data.item }}</strong>
                    </v-chip>
                </template>
        </v-combobox>

        <!-- COPY FROM PARENT -->
        <div v-show='interaction.create.method == 1 && prop_id_edit == -1'>
            <v-select v-model='interaction.create.selected_parent' :items='mocc_data' item-text='name' return-object :label='"Select" + prop_dataInfo.parent.title'>
            </v-select>
            <b>Select {{ prop_dataInfo.child.title }}</b>
            
            <v-data-table
                v-if='interaction.create.selected_parent'
                :headers="prop_headerChild"
                :items='interaction.create.selected_parent[prop_dataInfo.child.table_name]'
            >
                <template v-slot:items="props">
                    <td> 
                        
                        <v-checkbox v-model='interaction.create.selected_parent[prop_dataInfo.child.table_name][props.item.no]["copy_child"]' :label='props.item[prop_dataInfo.child.column_show]' class='colorblack'></v-checkbox>
                        
                    </td>
                    <td> 
                        
                        <v-checkbox v-model='interaction.create.selected_parent[prop_dataInfo.child.table_name][props.item.no]["copy_grand_child"]' v-if='props.item[prop_dataInfo.child.flag_grandchild] == true && interaction.create.selected_parent[prop_dataInfo.child.table_name][props.item.no]["copy_child"] == true' :label='"Copy " + prop_dataInfo.grandchild.title' class='colorblack'></v-checkbox>
                        
                    </td>
                </template>

            </v-data-table>
        </div>
    </div>
</template>
<script>
    //catatan 
    //input dibagi menjadi 2 yaitu : 
    //new rack dan copy rack
    //new rack berada di interaction.create.selected_parent
    //sedangkan copy rack berada di input.new
	export default {
		props : [
		'prop_id_edit',
		'prop_dataInfo',
		'prop_urlMOCC',
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
						selected_parent : null, //untuk method kedua (yaitu pilih parent, lalu pilih childnya)
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
                    new : [],
                    
				},
                mocc_data : [],
				



			}
		},

		methods : 
		{
			removeChip(item){
                
                this.input.new.splice(this.input.new.indexOf(item.item), 1);
                //this.interaction.create.created_child = [...this.interaction.create.created_child];
            },

            request_select_child_data()
            {
                //langsung request ke server

                try{
                    var response = axios.get(this.prop_urlMOCC, {
                        params:{
                            token: localStorage.getItem('token')
                        }
                    },{
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json'
                        }
                    });

                    return response;
                    

                }
                catch (error)
                {
                    
                    result_data = false;
                }
                
                
            },

            async get_data_child()
            {
                //request data
                let r_response_mocc = await this.request_select_child_data();
                
                this.mocc_data = r_response_mocc.data.items[this.prop_dataInfo.parent.table_name];
                //pemberian no pada setiap rack di setiap warehouse dimulai dari 0 (KASUS KHUSUS)
                for(var i = 0;i<this.mocc_data.length;i++)
                {
                    for(var j = 0;j<this.mocc_data[i].racks.length;j++)
                    {
                        this.mocc_data[i].racks[j].no = j;
                    }
                }
                

                //taruh di select master data
                //kan mocc_data semua data parent yang didalmanya ada child nya, maka dari itu childnya harus dibuang dulu.
                // for(var i = 0;i<this.mocc_data[this.prop_dataInfo.parent.table_name].length;i++)
                // {
                //     var temp_obj = this.mocc_data[this.prop_dataInfo.parent.table_name][i];
                //     delete temp_obj[this.prop_dataInfo.child.table_name];
                // }


            }
               
		},

		mounted() {

			//isi static select
			this.static_select[1].name += this.prop_dataInfo.parent.title;


            this.get_data_child();
            
            

			//finish loading
			this.finish_loading = true;
		}
            
	}
</script>