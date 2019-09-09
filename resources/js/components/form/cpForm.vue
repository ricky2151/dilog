<template>
	<div v-if='prop_dataInfo && finish_loading'>


		<v-dialog v-model='dialog_form' :width='prop_widthForm' >
			<v-form v-model='valid' ref='form'  onSubmit="return false;">

				<v-card>
					<v-toolbar dark color="menu">
						<v-btn icon dark v-on:click='close_dialog'>
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title v-html='id_edit == -1 ? "Add " + prop_title : "Edit " + prop_title'></v-toolbar-title>
					</v-toolbar>

					<v-stepper v-model='stepNow' vertical>
							<!-- FORM SINGLE -->
							
						<v-stepper-step :complete='stepNow > 1' step="1" :editable='id_edit == -1 ? prop_editableAdd : prop_editableEdit'>
							<h3>{{prop_title}} Data</h3>
						</v-stepper-step>
						<v-stepper-content step='1'>
								
								<v-layout row 
								v-for='(columns,key,idx) in prop_dataInfo.form_single' :key='key'>
									<v-flex 
									v-for='(column,key,idx) in columns'
									:key='key'

									:set='objColumn = prop_dataInfo.single[column]'
									:xs1='objColumn.width == 1'
									:xs2='objColumn.width == 2'
									:xs3='objColumn.width == 3'
									:xs4='objColumn.width == 4'
									:xs5='objColumn.width == 5'
									:xs6='objColumn.width == 6'
									:xs7='objColumn.width == 7'
									:xs8='objColumn.width == 8'
									:xs9='objColumn.width == 9'
									:xs10='objColumn.width == 10'
									:xs11='objColumn.width == 11'
									:xs12='objColumn.width == 12'
									>
									<template v-if='objColumn.type=="tf"'><!--  -->

										<template v-if='(!objColumn.vif) || (input[conditional_input[column][0]] && input[conditional_input[column][0]][conditional_input[column][1]] == 1)'>
											
											<v-text-field
											:rules='$list_validation[objColumn.validation]'
											:label='objColumn.label'
											v-model='input[column]'
											:disabled='objColumn.disabled'

											class="pa-2"
											>
											</v-text-field>
											
										</template>
									</template>
									
									<template v-if='objColumn.type=="date"'><!--  -->

										<v-menu
			                              ref="menu_date"
			                              v-model="menu_date"
			                              :close-on-content-click="false"
			                              :nudge-right="40"
			                              lazy
			                              transition="scale-transition"
			                              offset-y
			                              full-width
			                              max-width="290px"
			                              min-width="290px"
			                            >
			                              <template v-slot:activator="{ on }">
			                                <v-text-field
			                                  v-model="input[column]"
			                                  label="Date"
			                                  hint="YYYY/MM/DD format"
			                                  persistent-hint
			                                  prepend-icon="event"
			                                  @blur="date = input[column]"
			                                  v-on="on"
			                                ></v-text-field>
			                              </template>
			                              <v-date-picker v-model="input[column]" no-title @input="menu_date = false"></v-date-picker>
			                            </v-menu>
											
										
									</template>

										<template v-if='objColumn.type=="tf_gm"'>
											<template v-if='objColumn.gm=="lat"'>
												<GmapMap
			                                        v-bind:center="my_location"
			                                        v-bind:zoom="7"
			                                        map-type-id="terrain"
			                                        style="width: 500px; height: 300px"
			                                    >
			                                      <GmapMarker
			                                        v-bind:key="index"
			                                        v-for="(m, index) in markers"
			                                        v-bind:position="m.position"
			                                        @drag="updateMarkers"
			                                        v-bind:clickable="true"
			                                        v-bind:draggable="true"
			                                        v:on-click="center=m.position"
			                                      />
			                                    </GmapMap>
											</template>

											<v-text-field
											
											:rules='$list_validation[objColumn.validation]'
											:label='objColumn.label'
											v-model='input[column]'
											:disabled='objColumn.disabled'

											class="pa-2"
											>
											</v-text-field>
										</template>

										<v-textarea
										v-if='objColumn.type=="ta"'
										:rules='$list_validation[objColumn.validation]'
										:label='objColumn.label'
										v-model='input[column]'
										:disabled='objColumn.disabled'

										class="pa-2"
										>
										</v-textarea>

										<template v-if='objColumn.type == "s"'>
											<v-select
											v-if='!objColumn.child_of'
											:rules='$list_validation[objColumn.validation]'
											:label='objColumn.label'
											v-model='input[column]'
											:disabled='objColumn.disabled'

											:items='ref_input[objColumn.table_ref]'
											:item-text='objColumn.itemText'
											return-object

											class="pa-2"
											>
											</v-select>

											<v-select
											v-if='objColumn.child_of && input[objColumn.child_of]'
											:rules='$list_validation[objColumn.validation]'
											:label='objColumn.label'
											v-model='input[column]'
											:disabled='objColumn.disabled'

											:items='input[objColumn.child_of][objColumn.table_ref]'
											:item-text='objColumn.itemText'
											return-object

											class="pa-2"
											>
											</v-select>

											
										</template>
										
										<v-autocomplete
										v-if='objColumn.type=="s2"'
										:rules='$list_validation[objColumn.validation]'
										:label='objColumn.label'
										v-model='input[column]'
										:disabled='objColumn.disabled'

										:items='ref_input[objColumn.table_ref]'
										:item-text='objColumn.itemText'
										return-object

										class="pa-2"

								        
								        >
								        </v-autocomplete>

										
										<template v-if='objColumn.type=="img"' :set='tempObjColumn = objColumn'>
											
											<v-text-field
											v-on:click='pickFile(column)'
											prepend-icon='attach_file'
											label='Select Image'
											v-model='input[objColumn.fileNameVariable]'
											:disabled='objColumn.disabled'

											class="pa-2"
											>
											</v-text-field>
											<input
	                                        :id='column'
	                                        type="file"
	                                        style="display: none"
	                                        accept="image/*"
	                                        v-on:change='changeImage($event)'

	                                        class="pa-2"
	                                    	>
	                                    	<img v-if="preview[objColumn.previewVariable]" :src="preview[objColumn.previewVariable]" height="150" />
                                    	</template>
                                    </v-flex>
                                </v-layout>
                                <v-btn color='primary' v-if='stepNow < prop_countStep' v-on:click='next_step()'>Continue</v-btn>
				                <v-btn color='gray' v-if='stepNow > 1' v-on:click='prev_step()'>Back</v-btn>
                        </v-stepper-content>
                        <!-- =========== -->

                        <!-- FORM MULTIPLE -->
                        <!-- Catatan : Agar idx dimulai dari 2, maka idx harus ditambah 2 -->
                        <template v-for='(table_name, idx) in prop_dataInfo.form_multiple' >
                        	<v-stepper-step :complete='stepNow > (idx + 2)' :step="idx + 2"  :editable='id_edit == -1 ? prop_editableAdd : prop_editableEdit'>
								<h3>{{prop_dataInfo.multiple[table_name].title}} Data  </h3>
							</v-stepper-step>
							<v-stepper-content :step='idx + 2'>
								

									<!-- TIPE CHIPS -->
									<div v-if=' prop_dataInfo.multiple[table_name].type == "chips" '>
										
				                            <v-combobox
				                                v-model='input[table_name]'
				                                :items='ref_input[prop_dataInfo.multiple[table_name].ref_table]'
				                                :item-value="prop_dataInfo.multiple[table_name].item_value"
				                                :item-text="prop_dataInfo.multiple[table_name].item_text"
				                                :label='"Type or select" + prop_dataInfo.multiple[table_name].title'
				                                chips
				                                clearable
				                                prepend-icon="filter_list"
				                                solo
				                                multiple
				                                v-on:keyup.enter="checkItemInChip(table_name)"
				                                :disabled='prop_dataInfo.multiple[table_name].disabled'
				                                >
				                                    <template v-slot:selection="data">
				                                        <v-chip
				                                          :selected="data.selected"
				                                          close
				                                          v-on:input="removeChip(data.item, table_name)"
				                                        >
				                                            <strong>{{ data.item[prop_dataInfo.multiple[table_name].item_text] }}</strong>
				                                        </v-chip>
				                                    </template>
				                            </v-combobox>
				                            
				                        
									</div>

									<!-- TIPE TABLE -->
									<div v-if='prop_dataInfo.multiple[table_name].type == "table"'>
										
										<v-layout row 
										v-for='(columns,idx) in prop_dataInfo.multiple[table_name].form_single' :key='idx'>

											<v-flex 
											v-for='(column,idx) in columns'
											:key='idx'

											:set='objColumn = prop_dataInfo.multiple[table_name].single[column]'
											:xs1='objColumn.width == 1'
											:xs2='objColumn.width == 2'
											:xs3='objColumn.width == 3'
											:xs4='objColumn.width == 4'
											:xs5='objColumn.width == 5'
											:xs6='objColumn.width == 6'
											:xs7='objColumn.width == 7'
											:xs8='objColumn.width == 8'
											:xs9='objColumn.width == 9'
											:xs10='objColumn.width == 10'
											:xs11='objColumn.width == 11'
											:xs12='objColumn.width == 12'
											>

											{{testlog(temp_input)}}
												<v-text-field
												v-if='objColumn.type=="tf" && temp_input[table_name]'
												:rules='$list_validation[objColumn.validation]'
												:label='objColumn.label'
												v-model='temp_input[table_name][column]'
												:disabled='objColumn.disabled'
												>
												</v-text-field>

												<v-textarea
												v-if='objColumn.type=="ta" && temp_input[table_name]'
												:rules='$list_validation[objColumn.validation]'
												:label='objColumn.label'
												v-model='temp_input[table_name][column]'
												:disabled='objColumn.disabled'
												>
												</v-textarea>


												<v-select
												v-if='objColumn.type=="s" && temp_input[table_name]'
												:rules='$list_validation[objColumn.validation]'
												:label='objColumn.label'
												v-model='temp_input[table_name][column]'

												:items='ref_input[objColumn.table_ref]'
												:item-text='objColumn.itemText'
												return-object
												:disabled='objColumn.disabled'
												>
												</v-select>

												<v-autocomplete
												v-if='objColumn.type=="s2" && temp_input[table_name]'
												:label='objColumn.label'
												v-model='temp_input[table_name][column]'
												:items='ref_input[objColumn.table_ref]'
												:item-text='objColumn.itemText'
												return-object
												:disabled='objColumn.disabled'

										        
										        >
										        </v-autocomplete>

												<!-- SEMENTARA INI BELUM ADA CHILD FORM YANG MENGGUNAKAN GAMBAR -->
												<!-- <v-text-field
												v-if='objColumn.type=="img"'
												v-on:click='pickFile(objColumn.idButton)'
												prepend-icon='attach_file'
												label='Select Image'
												v-model='input[objColumn.fileNameVariable]'
												:disabled='objColumn.disabled'
												>
												</v-text-field>
												<input
		                                        :id='objColumn.idButton'
		                                        type="file"
		                                        style="display: none"
		                                        accept="image/*"
		                                        v-on:change='changeImage($event,objColumn.fileNameVariable, objColumn.previewVariable, fileVariable)'
		                                    	>
		                                    	<img :src="preview[objColumn.previewVariable]" height="150" v-if="preview[objColumn.previewVariable]"/> -->
		                                    </v-flex>
		                                </v-layout>


		                                <v-toolbar flat color="white" >
			                                <v-spacer></v-spacer>
			                                <v-btn v-if='temp_input[table_name] && temp_input[table_name].idx_edit != -1' color="red" dark v-on:click='tableCanceledit(table_name)'>
			                                    Cancel
			                                </v-btn>
			                                
			                                <v-btn v-if='temp_input[table_name]' color="primary" dark v-on:click='tableSave(table_name)' v-html='temp_input[table_name].idx_edit == -1?"Add to Table":"Save Changes"'>
			                                </v-btn>
			                            </v-toolbar>
			                            


			                            <v-toolbar flat color="white" >
			                                <v-toolbar-title>{{prop_dataInfo.multiple[table_name].title}} Data</v-toolbar-title>
			                                
			                            </v-toolbar>
			                            

			                            <v-data-table
			                                disable-initial-sort
			                                :headers="prop_dataInfo.multiple[table_name].headers"
			                                :items="input[table_name]"
			                                class=""
			                            >

			                                <template v-slot:items="props">
			                                    <td>{{ props.index + 1 }}</td>
			                                    <td v-for='obj in prop_dataInfo.multiple[table_name].datatable'>{{obj.column.length == 1 ? props.item[obj.column[0]] : props.item[obj.column[0]][obj.column[1]]}}</td>
			                                    <td>
			                                        <v-btn class='button-action' v-on:click='tableShowData(props.index,table_name)' color="primary" fab depressed small dark>
			                                            <v-icon small>edit</v-icon>
			                                        </v-btn>
			                                        <v-btn class='button-action' v-on:click='tableDelete(props.index, table_name)' color="red" fab small dark depressed>
			                                            <v-icon small>delete</v-icon>
			                                        </v-btn>

			                                    </td>
			                                </template>
			                            </v-data-table>



	                            	</div>
	                            	<v-btn color='primary' v-if='stepNow < prop_countStep' v-on:click='next_step()'>Continue</v-btn>
				                	<v-btn color='gray' v-if='stepNow > 1' v-on:click='prev_step()'>Back</v-btn>
                            </v-stepper-content>
                        </template>
	                    <!-- ============================ -->


	                    <!-- FORM CUSTOM COMPONENT -->
                        <template v-for='(component_name, idx) in prop_dataInfo.form_custom_component' >
                        	<template v-if='component_name != "cpMakeOrCopyChild" || (component_name == "cpMakeOrCopyChild" && id_edit == -1)'> <!-- ditampilkan jika -->
		                    	<v-stepper-step :complete='stepNow > (idx + 2)' :step="idx + 2"  :editable='id_edit == -1 ? prop_editableAdd : prop_editableEdit'>
									<h3>{{prop_dataInfo.custom_component[component_name].title}} Data  </h3>
								</v-stepper-step>
								<v-stepper-content :step='idx + 2'>
									

										<!-- TIPE CHIPS -->
										<div v-if='component_name == "cpMakeOrCopyChild" '>
											<cp-make-or-copy-child
											:prop_id_edit='id_edit'
											:prop_dataInfo='prop_dataInfo.custom_component[component_name]'
											:prop_urlMOCC='prop_dataInfo.custom_component[component_name].url'
											:prop_headerChild='prop_dataInfo.custom_component[component_name].child.header'
											ref='cpMakeOrCopyChild'
											>
											</cp-make-or-copy-child>
					                        
										</div>

										
		                            	<v-btn color='primary' v-if='stepNow < prop_countStep' v-on:click='next_step()'>Continue</v-btn>
					                	<v-btn color='gray' v-if='stepNow > 1' v-on:click='prev_step()'>Back</v-btn>
		                        </v-stepper-content>
		                    </template>
                        </template>
	                    <!-- ============================ -->



                        <v-btn v-on:click='save_data()' class='floatright marginright25'>submit</v-btn>
                    </v-stepper>

                </v-card>
            </v-form>
        </v-dialog>
    </div>
</template>
<script>
	import axios from 'axios'
	import cpMakeOrCopyChild from './cpMakeOrCopyChild.vue'
	

	export default{
		props:[
		
		'prop_title', 
		'prop_countStep', 
		'prop_editableEdit', 
		'prop_editableAdd', 
		'prop_dataInfo',
		'prop_tableName',
		'prop_widthForm',
		'prop_singularName',
		'prop_tempInput',
		'prop_input',
		'prop_custom_formData',
		'prop_preview',
		'prop_urlGetMasterData',
		'prop_urlMOCC',
		'prop_additional_param_create_key',
		'prop_additional_param_create_value',
		'prop_send_parent_table_key',
		'prop_send_parent_table_value',

		],
		data() {
			return {
				//for element
				dialog_form:false,
				valid:null,
				stepNow:null,
				id_edit:-1,
				menu_date:null,

				//for data
				input : [],
				input_before_edit : [],
				temp_input : {},
				ref_input : [],
				preview : [],
				conditional_input:[],
				finish_loading:false,
				url_edit : null,
				url_store : null,
				url_update : null,
				url_create : null,
				header_api:{
	                'Accept': 'application/json',
	                'Content-type': 'application/json' //default
	            },


	            useGm:false,
	            my_location:{lat:10, lng:10},
	            markers:[{
	            	position:{lat:10,lng:10}
	            }],


				//for element & data


			}
		},
		components : 
		{
			cpMakeOrCopyChild,
		},
		methods:
		{
			
			testlog(obj)
			{
				
			},
			//for other component
			


			//for element
			close_dialog()
			{
				
				this.clear_input();
				this.id_edit = -1;
            	this.dialog_form = false;
			},
			async open_dialog(id)
			{
				

				//startup
				this.input = this.prop_input;
				
				var temp_temp_input = {};
				if(this.prop_tempInput)
				{
					temp_temp_input = JSON.parse(JSON.stringify(this.prop_tempInput));
				}

				
				

				//khusus mocc
				if(this.prop_dataInfo.custom_component)
				{
			        if(this.prop_dataInfo.custom_component['cpMakeOrCopyChild'] && id > -1)
			        {
			        	var temp_name_table_child_mocc = this.prop_dataInfo.custom_component['cpMakeOrCopyChild'].child.table_name;

			        	//1. generate input
			        	this.input[temp_name_table_child_mocc] = [];


			        	//2. generate temp_data
			        	var temp_result = {};
						Object.keys(this.prop_dataInfo.custom_component['cpMakeOrCopyChild'].editing[temp_name_table_child_mocc].single).map(function(key, index) {
							temp_result[key] = null;
						});
						temp_result['idx_edit'] = -1;
						temp_temp_input[temp_name_table_child_mocc] = temp_result;
						

						//3. masukan property editing mocc dari mxdatabase ke prop_datainfo, SEOLAH-OLAH itu adalah multiplenya (padahal hanya berlaku pada saat edit)
						this.prop_dataInfo.form_multiple = [];
						this.prop_dataInfo.form_multiple.push(temp_name_table_child_mocc);
						this.prop_dataInfo.multiple = {};
						this.prop_dataInfo.multiple[temp_name_table_child_mocc] = this.prop_dataInfo.custom_component['cpMakeOrCopyChild'].editing[temp_name_table_child_mocc];
			        }
			        else if(this.prop_dataInfo.custom_component['cpMakeOrCopyChild'] && id == -1)
			        {
			        	var temp_name_table_child_mocc = this.prop_dataInfo.custom_component['cpMakeOrCopyChild'].child.table_name;
			        	if(this.prop_dataInfo.multiple[temp_name_table_child_mocc])
			        	{
			        		delete this.prop_dataInfo.multiple[temp_name_table_child_mocc];
			        	}
			        	for(var i = 0;i<this.prop_dataInfo.form_multiple.length;i++)
			        	{
			        		if(this.prop_dataInfo.form_multiple[i] == temp_name_table_child_mocc)
			        		{
			        			delete this.prop_dataInfo.form_multiple[i];
			        			break;
			        		}
			        	}
			        	
			        }

				}

		        this.temp_input = temp_temp_input;
		        
		        

		        
		        this.preview = this.prop_preview;
		        this.conditional_input = this.prop_dataInfo.conditional_input;

		        
		        this.set_watcher_custom_value();
		        
		        this.set_custom_single();


		        


		        //untuk master data : 
		        //jika sedang add data, maka dia harus request ke /create
	        	//tapi jika sedang edit, tidak perlu request ke /create, karena waktu request /edit sudah ada


				 if(id != -1) //sedang diedit
		        {
		            this.id_edit = id;
		            let r = await this.get_data_before_edit(id);
		            
		            this.convert_data_input(r);
		            if(this.prop_urlGetMasterData != null)
		            {

		            	this.fill_select_master_data(r);
		            }

		            
		        }
		        else //sedang add
		        {
		        	this.id_edit = -1;
		        	if(this.prop_urlGetMasterData != null)
		        	{
		        		if(this.prop_additional_param_create_key)
		        		{
		        			this.get_master_data(this.prop_additional_param_create_value, this.prop_additional_param_create_key)
		        		}
		        		else
		        		{
			        		this.get_master_data();

		        		}
		        	}
		        	
		        }

		        
		        this.stepNow = 1;
		        this.finish_loading = true;
		        this.dialog_form = true;
		        

			},
			next_step()
			{
				this.stepNow = parseInt(this.stepNow) + 1;

			},
			prev_step()
			{
				this.stepNow = parseInt(this.stepNow) - 1;
			},

			testfungsi(param1)
			{
				
			},
			changeImage(e){

				
				var id = e.explicitOriginalTarget.id //penyelamat :) ini sama saja dengan nama kolom
				var fileNameVariable = this.prop_dataInfo.single[id].fileNameVariable;
				var previewVariable = this.prop_dataInfo.single[id].previewVariable;
				var fileVariable = this.prop_dataInfo.single[id].fileVariable;
				
	            const files = e.target.files;

	            if(files[0] !== undefined) {
	                if(((files[0].size / 1024) / 1024) < 2)
	                {
	                    
	                    
	                    
	                    this.input[fileNameVariable] = files[0].name;

	                    const fr = new FileReader ()
	                    fr.readAsDataURL(files[0])
	                    fr.addEventListener('load', () => {

	                        this.preview[previewVariable] = fr.result; //jadi preview
	                        this.input[fileVariable] = files[0]; //yang dikirim ke server
	                    })
	                    
	                }
	                else
	                {
	                    
	                    swal("File is to Big", "Pleas uload file with size < 2 MB !", "error");
	                }
	            } else {
	                swal("Your file is empty !", "Please Upload Your File !", "error");
	                
	            }
	        },

			pickFile (idElement) {
				//console.log(idElement);
	            var el = document.getElementById(idElement).click();
	        },


			clear_input(){
            	this.$refs.form.resetValidation();
	            if(this.preview)
	            {
					for (var key in this.preview) {
					    // skip loop if the property is from prototype
					    if (!this.preview.hasOwnProperty(key)) continue;
					    //clear preview
					    this.preview[key] = '';
					}
	            	
	            }


	            for (var key in this.input)
	            {
	                if(this.input[key])
	                {
	                    if(Array.isArray(this.input[key]))
	                    {
	                        this.input[key] = [];     
	                    }
	                    else
	                    {
	                        this.input[key] = "";
	                    }
	                    
	                    
	                }
	                    
	            }

	            

	            //this.$refs.formCreateEdit.reset();

	        },
	        clear_input_before_edit()
	        {
	        	if(this.preview)
	            {
					for (var key in this.preview) {
					    // skip loop if the property is from prototype
					    if (!this.preview.hasOwnProperty(key)) continue;
					    //clear preview
					    this.preview[key] = '';
					}
	            	
	            }


	            for (var key in this.input_before_edit)
	            {
	                if(this.input_before_edit[key])
	                {
	                    if(Array.isArray(this.input_before_edit[key]))
	                    {
	                        this.input_before_edit[key] = [];     
	                    }
	                    else
	                    {
	                        this.input_before_edit[key] = "";
	                    }
	                    
	                    
	                }
	                    
	            }
	        },

	        get_master_data(idparent,fktoparent)
	        {

	            if(this.prop_urlGetMasterData != null)
	            {
	            	var params = {
	            		params : {
	            			token : localStorage.getItem('token')
	            		}
	            	};
	            	if(idparent)
	            	{
	            		params['params'][fktoparent] = idparent;
	            	}
	            	
	            	
	                axios.get(this.prop_urlGetMasterData, params,{
	                    headers: {
	                        'Accept': 'application/json',
	                        'Content-type': 'application/json'
	                    }
	                }).then(r => this.fill_select_master_data(r))
	                .catch(function (error)
	                {
	                    console.log("error : ")
	                    console.log(error)
	                    if(error.response.status == 422)
	                    {
	                        swal('Request Failed', 'Check your internet connection !', 'error');
	                    }
	                    else
	                    {
	                        swal('Unkown Error', error.response.data , 'error');
	                    }
	                });

	            }
	        },


			get_data_before_edit(id_edit) //nanti dihapus karena sudah ada di component
	        {
	        	
	        	try{

		            var response = axios.get(this.url_edit, {
		                params:{
		                    token: localStorage.getItem('token')
		                }
		            },{
		                headers: {
		                    'Accept': 'application/json',
		                    'Content-type': 'application/json'
		                }
		            });

		            return response
	            	

	        	}
	        	catch (error)
	        	{
	        		console.log('error try catch : ' + error);
	        		result_data = false;
	        	}

	        	//return result_data;

	            
	            
	            
	        },
	        convert_data_input(r) //pengisian data response api ke this.input
	        {
	        	var temp_r = r.data.items[this.prop_singularName];
	        	for(var i = 0;i<this.prop_dataInfo.form_single.length;i++)
	        	{
	        		for(var j =0;j<this.prop_dataInfo.form_single[i].length;j++)
	        		{
	        			var nameColumn = this.prop_dataInfo.form_single[i][j];
	        			var objColumn = this.prop_dataInfo.single[nameColumn];

	        			if(objColumn.type == 'img')
				    	{
				    		this.preview[objColumn.previewVariable] = temp_r[nameColumn];
				    	}
				    	else
				    	{

							this.input[nameColumn] = temp_r[nameColumn];
				    	}
	        		}
	        	}
	        	


				//isi data multiple
				
				for(var i = 0;i<this.prop_dataInfo.form_multiple.length;i++)
				{
					var temp_name_table = this.prop_dataInfo.form_multiple[i];					
					
					this.input[temp_name_table] = temp_r[temp_name_table];


					//cek jika di tabel ini terdapat kolom yang merupakan custom master data, maka setting format inputnya
		    		var self = this;
		    		var true_value = ''; //nilai yang seharusnya
		    		if(this.prop_dataInfo.multiple[temp_name_table].type == 'table')
		    		{
			    		Object.keys(this.prop_dataInfo.multiple[temp_name_table].single).map(function(key, index)
			    		{
			    			var temp_obj = self.prop_dataInfo.multiple[temp_name_table].single[key];
			    			if(temp_obj.custom_table_ref)
			    			{
			    				var temp_custom_master_data = self.prop_dataInfo.custom_master_data[key];
			    				for(var j = 0;j<self.input[temp_name_table].length;j++)
			    				{
				    				for(var k = 0;k<temp_custom_master_data.length;k++)
				    				{
				    					if(self.input[temp_name_table][j][key] == temp_custom_master_data[k].value)
				    					{
				    						true_value = temp_custom_master_data[k];
				    						self.input[temp_name_table][j][key] = true_value;
				    						break;
				    					}
				    				}

			    				}

			    			}
			    		});

		    		}
		    		//cek jika di tabel ini, jika tabel ini menggunakan type chips, maka hilangkan pembungkusnya
		    		else if(this.prop_dataInfo.multiple[temp_name_table].type == 'chips')
		    		{
		    			for(var j = 0;j<this.input[temp_name_table].length;j++)
		    			{
		    				var temp_obj = this.input[temp_name_table][j];
		    				Object.keys(temp_obj).map(function(key, index)
		    				{
		    					self.input[temp_name_table][j] = temp_obj[key];
		    				});
		    			}



		    		
		    		}
		    		
				}

				//isi data mocc
				if(this.$refs.hasOwnProperty('cpMakeOrCopyChild'))
				{
					var data_cpmocc = this.$refs['cpMakeOrCopyChild'][0];
					//data_cpmocc.
				}

	            


				this.input_before_edit = JSON.parse(JSON.stringify(this.input));
	        },

	        fill_select_master_data(r)
	        {
	        	
	        	var self = this;
	        	var temp_r;

	        	if(this.id_edit == -1) //jika sedang add, maka kan dia manggilnya yang /create jadi langsung aja
	        	{
	        		temp_r = r.data.items;
	        	}
	        	else //jika sedang edit, maka kan dia dapet datanya sekalian waktu manggil /edit, nah jadi pembungkus arraynya adalah 'master_data'
	        	{
	        		temp_r = r.data.items.master_data;
	        	}

	            for(var index in temp_r) { 
				   this.ref_input[index] = temp_r[index];
				}
				
				//cek custom master data
				Object.keys(this.prop_dataInfo.custom_master_data).map(function(key, index) {
					var obj = self.prop_dataInfo.custom_master_data[key];
					
				    self.ref_input[key] = obj;
				});


	        },


	        checkItemInChip(table_name){
	            var temp = this.input[table_name];
	            if (!temp[temp.length - 1].hasOwnProperty('id')) //artinya cuman asal enter, tanpa ambil item dari ref_input
	            {
	                this.removeChip(temp[temp.length - 1], table_name);
	            }
	        },
	        removeChip(item, table_name)
	        {
	        	this.input[table_name].splice(this.input[table_name].indexOf(item), 1);
                this.input[table_name] = [...this.input[table_name]];
	        },




	        tableShowData(idx, table_name){ 

                this.temp_input[table_name] = JSON.parse(JSON.stringify(this.input[table_name][idx]));
                this.temp_input[table_name].idx_edit = idx;
                
                
            },
            tableClearTempInput(table_name){
                for (var key in this.temp_input[table_name])
                {
                    if(this.temp_input[table_name][key] && key != 'idx_edit') //jika ada tidak kosong dan bukan custom value
                    {

                    	if(!this.prop_dataInfo.multiple[table_name].single[key].hasOwnProperty("value"))
                    	{
	                        this.temp_input[table_name][key] = null;

                    	}

                    }
                }

            },
            tableSave(table_name){ //bisa edit / add

            	
                
                
                var idx_edit = JSON.parse(JSON.stringify(this.temp_input[table_name].idx_edit));
                if(idx_edit == -1)
                {
                    var temp = JSON.parse(JSON.stringify(this.temp_input[table_name]));
                	delete temp.idx_edit;
                    this.input[table_name].push(temp);
                    
                }
                else
                {
                	var temp = JSON.parse(JSON.stringify(this.temp_input[table_name]));
                	delete temp.idx_edit;

                	//seharusnya bisa seperti ini : 
                    //this.input[table_name][idx_edit] = temp;
                    //tapi karena bug tidak tau kenapa pokoknya harus di for per key nya, menjadi seperti ini :
                    for (var key in temp)
                    {
	                    this.input[table_name][idx_edit][key] = temp[key];

                    }
                    //===================
                    this.temp_input[table_name].idx_edit = -1;
                    //untuk mengtrigger datatable yang isinya this.input[table_name] biar berubah
                    


                }
               this.tableClearTempInput(table_name);

            },
            tableCanceledit(table_name){
                this.tableClearTempInput(table_name);
                this.temp_input[table_name].idx_edit = -1;
            },
            tableDelete(idx, table_name)
            {
                this.input[table_name].splice(idx,1);
            },




	        prepare_data_form()
	        {
	            const formData = new FormData();

	            if(this.prop_dataInfo.rule_update == 'send_all')
	            {
	            	this.clear_input_before_edit();
	            }



	            //custom formData
	            if(this.prop_custom_formData)
	            {

	            	for(var i = 0 ;i<this.prop_custom_formData.length;i++)
	            	{
		            	if(this.prop_custom_formData[i].when_id_edit == 'all')
		            	{
		            		formData.append(this.prop_custom_formData[i].key, this.prop_custom_formData[i].value);
		            	}
		            	else if(this.prop_custom_formData[i].when_id_edit == this.id_edit)
		            	{
		            		formData.append(this.prop_custom_formData[i].key, this.prop_custom_formData[i].value);
		            	}

	            	}
	            }

	            //send parent table
	            if(this.prop_send_parent_table_key)
	            {

	            	formData.append(this.prop_send_parent_table_key, this.prop_send_parent_table_value);
	            }

	            //


	            //prepare form_single (sudah menghandle saat edit maupun store)
	            for(var i = 0;i<this.prop_dataInfo.form_single.length;i++)
	        	{
	        		for(var j =0;j<this.prop_dataInfo.form_single[i].length;j++)
	        		{
	        			var nameColumn = this.prop_dataInfo.form_single[i][j];
	        			var objColumn = this.prop_dataInfo.single[nameColumn];

	        			if(objColumn.type == 'img')
				    	{
				    		this.header_api['Content-type'] = 'multipart/form-data';
				    		var temp_fileVariable = objColumn.fileVariable;
				    		var is_image_delete = '0';
				    		if(this.input[temp_fileVariable])
				    		{
					    		if((this.input[temp_fileVariable] != this.input_before_edit[temp_fileVariable]) || this.id_edit == -1)
					    		{
					    			formData.append(nameColumn, this.input[temp_fileVariable]);
					    		}
					    		is_image_delete = '0';
				    		}
				    		else
				    		{
				    			is_image_delete = '1';
				    		}
				    		formData.append('is_image_delete', is_image_delete);
				    	}
				    	else if(objColumn.type == 's' || objColumn.type == 's2')
				    	{
				    		if(!this.same_object(this.input[nameColumn],this.input_before_edit[nameColumn]) || this.id_edit == -1)
				    			formData.append(objColumn.column, this.input[nameColumn][objColumn.itemValue]);
				    	}
				    	else //tf
				    	{
				    		if((this.input[nameColumn] != this.input_before_edit[nameColumn]) || this.id_edit == -1)
				    		{
				    			var convert_null_to_empty_string = false;
				    			if(this.prop_dataInfo.conditional_input)
				    			{
				    				if(this.prop_dataInfo.conditional_input[nameColumn] && !this.input[nameColumn])
				    				{
				    					convert_null_to_empty_string = true;
				    				}
				    			}
				    			if(convert_null_to_empty_string)
				    			{
				    				formData.append(nameColumn, '');
				    			}
				    			else
				    			{
				    				formData.append(nameColumn, this.input[nameColumn]);
				    			}
				    			
				    			
				    			
				    		}
				    	}
	        		}
	        	}


	        	//prepare form_multiple (sudah menghandle saat edit maupun store)
	            for(var i = 0;i<this.prop_dataInfo.form_multiple.length;i++) //cek disetiap table
	        	{
	        		
	        		var nameTable = this.prop_dataInfo.form_multiple[i];
	        		var objTable = this.prop_dataInfo.multiple[nameTable];

	        		if(objTable.send_type =='1') //langsung
	        		{
	        			if(objTable.type == 'chips')
	        			{
	        				for(var j = 0;j<this.input[nameTable].length;j++) //disetiap data yang ingin dipost/update di input. 
	        				{
	        					
	        					formData.append(nameTable + '[' + j + '][' + objTable.column +']',this.input[nameTable][j]['id']);	
	        				}
	        				
	        			}
	        			else if(objTable.type == 'table')
	        			{
	        				for(var j = 0;j<this.input[nameTable].length;j++) //disetiap data yang ingin dipost/update di input. 
	        				{
	        					var self = this;
			                	Object.keys(objTable.single).map(function(key, index) //disetiap single pada multiple (contoh id, attribute, value)
			                	{ 
			                		var objColumnChildTable = objTable.single[key];
			                		if(objColumnChildTable.column) //cek jika dia benar akan dikirim ke server
			                		{
			                			if(objColumnChildTable.type == 's' || objColumnChildTable.type == 's2') //cek jika dia merupakan select, maka kita ambil value dari attribute 'itemValue'
			                			{
				                			formData.append(nameTable + '[' + j + '][' + objColumnChildTable.column +']',self.input[nameTable][j][key][objColumnChildTable.itemValue]);		
			                			}
			                			else if(objColumnChildTable.type == 'tf') //cek jika dia merupakan textfield, maka kita ambil langsung
			                			{
			                				formData.append(nameTable + '[' + j + '][' + objColumnChildTable.column +']',self.input[nameTable][j][key]);
			                			}
			                		}
			                	})

	        				}
	        			}
	        		}
	        		else if(objTable.send_type=='2')
	        		{
	        			//selama ini belum ada tipe send 2 dengan chips, pasti pake table
	        			if(objTable.type == 'table')
	        			{
	        				var counteridx = 0;
	        				for(var j = 0;j<this.input[nameTable].length;j++)
	        				{
	        					var temp = this.input[nameTable][j];
	        					if(temp.id == null) //jika form ini untuk add data (id_edit = -1)
	        					{
	        						var self = this;
	        						Object.keys(objTable.single).map(function(key, index) //disetiap single pada multiple (contoh id, attribute, value)
				                	{ 
				                		var objColumnChildTable = objTable.single[key];
				                		if(objColumnChildTable.column) //cek jika dia benar akan dikirim ke server
				                		{
				                			if(objColumnChildTable.type == 's' || objColumnChildTable.type == 's2') //cek jika dia merupakan select, maka kita ambil value dari attribute 'itemValue'
				                			{
					                			formData.append(nameTable + '[' + counteridx + '][' + objColumnChildTable.column +']',self.input[nameTable][j][key][objColumnChildTable.itemValue]);		
				                			}
				                			else if(objColumnChildTable.type == 'tf') //cek jika dia merupakan textfield, maka kita ambil langsung
				                			{
				                				formData.append(nameTable + '[' + counteridx + '][' + objColumnChildTable.column +']',self.input[nameTable][j][key]);
				                			}
				                			
				                			
				                		}
				                	})
				                	formData.append(nameTable + '[' + counteridx + '][type]','1'); //artinya data ini sedang di add (bukan edit ataupun delete)
				                	counteridx++;
	        					}
	        					else
	        					{
	        						//cocokan dengan input_before_edit karena ini mungkin sudah ada(dibiarkan) atau diupdate
	        						var edittrue = false;
			                        for(var k = 0;k<this.input_before_edit[nameTable].length;k++)
			                        {
			                            var temp2 = this.input_before_edit[nameTable][k];

			                            if(temp.id == temp2.id) //id nya sama, saatnya cek isinya beda ato enggak.
			                            {
			                            	if(!this.same_object(temp, temp2)) //jika input akhir dan input awal
			                            	{
			                            		edittrue = true;
			                            	}
			                                break;
			                            }
			                        }

			                        if(edittrue) //kirimkan hasil akhir ke server dengan keterangan type = 0 artinya diedit
			                        {
			                        	var self = this;
			                        	Object.keys(objTable.single).map(function(key, index) //disetiap single pada multiple (contoh id, attribute, value)
					                	{ 
					                		var objColumnChildTable = objTable.single[key];
					                		if(objColumnChildTable.column) //cek jika dia benar akan dikirim ke server
					                		{
					                			if(objColumnChildTable.type == 's' || objColumnChildTable.type == 's2') //cek jika dia merupakan select, maka kita ambil value dari attribute 'itemValue'
					                			{
						                			formData.append(nameTable + '[' + counteridx + '][' + objColumnChildTable.column +']',self.input[nameTable][j][key][objColumnChildTable.itemValue]);		
					                			}
					                			else if(objColumnChildTable.type == 'tf') //cek jika dia merupakan textfield, maka kita ambil langsung
					                			{
					                				formData.append(nameTable + '[' + counteridx + '][' + objColumnChildTable.column +']',self.input[nameTable][j][key]);
					                			}
					                			
					                			formData.append(nameTable + '[' + counteridx + '][id]',temp.id); 
					                			
					                		}
					                	})
					                	formData.append(nameTable + '[' + counteridx + '][type]','0'); //artinya data ini sedang di diedit 
					                	counteridx++;
			                        }
	        					}
	        				}

	        				//cek di input_before_edit cocokin dengan input
			                //1. jika ada data dengan id yang tidak ada di data input, berarti data tersebut pasti dihapus
			                if(this.input_before_edit.hasOwnProperty(nameTable))
			                {
				                for(var j = 0;j<this.input_before_edit[nameTable].length;j++)
				                {
				                    var deletetrue = true;
				                    for(var k=0;k<this.input[nameTable].length;k++)
				                    {
				                        if(this.input[nameTable][k].id == this.input_before_edit[nameTable][j].id)
				                        {
				                            deletetrue = false;
				                            break;
				                        }
				                    }

				                    if(deletetrue)
				                    {
				                    	formData.append(nameTable + '[' + counteridx + '][id]',this.input_before_edit[nameTable][j].id);
				                    	formData.append(nameTable + '[' + counteridx + '][type]','-1'); //artinya data ini sedang di didelete 

				                        
				                        counteridx++;
				                    }
				                }

			                }
	        			}
	        		}

	        	}


	        	//prepare form_custom_component
	        	//1. cpMakeOrCopyChild
	        	if(this.$refs['cpMakeOrCopyChild'] && this.id_edit == -1)
	        	{
	        		var data_cpmocc = this.$refs['cpMakeOrCopyChild'][0];
	        		var table_name_child = this.prop_dataInfo.custom_component['cpMakeOrCopyChild'].child.table_name;
	        		var table_name_grandchild = this.prop_dataInfo.custom_component['cpMakeOrCopyChild'].grandchild.table_name;
	        		if(data_cpmocc.interaction.create.method == 0) //langsung
	        		{
	        			formData.append('store_type', '2');
	        			for(var i = 0;i<data_cpmocc.input.new.length;i++)
	        			{
	        				formData.append(table_name_child + '[' + i + '][name]', data_cpmocc.input.new[i]);
	        			}
	        		}
	        		else //menggunakan copy
	        		{
	        			formData.append('store_type', '1');
	        			formData.append('warehouse_id', data_cpmocc.interaction.create.selected_parent.id);
	        			var idxcopychild = -1;
	        			for(var i = 0;i<data_cpmocc.interaction.create.selected_parent[table_name_child].length;i++)
	        			{
	        				var tempobj_child = data_cpmocc.interaction.create.selected_parent[table_name_child][i];
	        				if(tempobj_child.copy_child)
	        				{
	        					idxcopychild += 1;
	        					formData.append('copy_' + table_name_child + '[' + idxcopychild + '][id]', tempobj_child.id);
	        					if(tempobj_child.copy_grand_child)
	        					{
	        						formData.append('copy_' + table_name_child + '[' + idxcopychild + '][is_with_' + table_name_grandchild + ']', 1);
	        					}
	        					else
	        					{
	        						formData.append('copy_' + table_name_child + '[' + idxcopychild + '][is_with_' + table_name_grandchild + ']', 0);	
	        					}
	        				}
	        			}
	        		}
	        	}
	        		
	        	


	            if(this.id_edit != -1) //jika edit data
	            {
	            	formData.append('_method','patch');
	            }
	            formData.append('token', localStorage.getItem('token'));
	            return formData;
	        },

	        save_data()
	        {
	        	if(this.id_edit != -1) //jika sedang diedit
	            {
	                
	                axios.post(
	                	this.url_update,
	                	this.prepare_data_form(),
	                	this.header_api).then((r) => {
	                	this.clear_input();
	                    this.close_dialog();
	                    this.id_edit = -1;
	                    this.$emit('done');
	                    swal("Good job!", "Data saved !", "success");
	                    
	                    
	                    
	                }).catch(function (error)
	                {
	                    
	                    if(error.response.status == 422)
	                    {
	                        swal('Request Failed', 'Check your internet connection !', 'error');
	                    }
	                    else
	                    {
	                        swal('Unkown Error', error.response.data , 'error');
	                    }
	                });
	                
	            }
	            else //jika sedang tambah data
	            {
	            	
	                axios.post(
	                	this.url_store,
	                	this.prepare_data_form(),
	                	this.header_api).then((r)=> {
	                    this.clear_input();
	                    this.close_dialog();
	                    this.$emit('done');
	                    swal("Good job!", "Data saved !", "success");
	                });
	            }
	        },

	        calculate_custom_value(data, value, dataType)
			{
				//data adalah row dari database
				//value adalah ['price', '*', 'qty']
				var result;
				if(dataType == 'int')
				{
					result = parseInt(data[value[0]]);	
				}
				else
				{
					result = data[value[0]];	
				}
				
				for(var i = 1;i<value.length;i+=2)
				{
					if(dataType == 'int')
					{
						var isnum = /^\d+$/.test(data[value[i + 1]]);
						var second_number;
						if(isnum)
						{
							second_number = parseInt(data[value[i + 1]]);
						}
						else
						{
							second_number = parseInt(value[i + 1]);
						}
					}
					if(value[i] == '+')
					{
						if(dataType == 'int')
						{
							result += second_number;
						}
						else
						{
							result += data[value[i + 1]];
						}
					}
					else if(value[i] == '-')
					{
						if(dataType == 'int')
						{
							result -= second_number;
						}
						else
						{
							result -= data[value[i + 1]];
						}
					}
					else if(value[i] == '*')
					{
						if(dataType == 'int')
						{
							result *= second_number;
						}
						else
						{
							result *= data[value[i + 1]];
						}
					}
					else if(value[i] == '/')
					{
						if(dataType == 'int')
						{
							result /= second_number;
						}
						else
						{
							result /= data[value[i + 1]];
						}
					}
				}
				return result;

			},

			same_object(obj1,obj2)
			{
				return JSON.stringify(obj1) === JSON.stringify(obj2);
			},

			set_custom_single()
			{
				if(this.prop_dataInfo.hasOwnProperty('custom_single'))
				{
					//1. cek apakah form ini menggunakan googlemap (googlemap hanya sebatas di single)
					if(this.prop_dataInfo.custom_single.hasOwnProperty('gm'))
					{
						if(this.prop_dataInfo.custom_single.gm.active)
						{
							//set my_location awal
							navigator.geolocation.getCurrentPosition((position) => {
						        this.my_location = {
						        	lat: position.coords.latitude,
						        	lng: position.coords.longitude
						        };
						        this.markers[0].position = JSON.parse(JSON.stringify(this.my_location));
						      });
						}
						
					}

				}
			},
			updateMarkers(event){
			
				this.markers[0].position = {
				    lat: event.latLng.lat(),
				    lng: event.latLng.lng(),
				  }
				this.input.lat = this.markers[0].position.lat;
			    this.input.lng = this.markers[0].position.lng;  
				
			},

			set_watcher_custom_value()
			{

				if(this.prop_dataInfo)
				{
					 
					var self = this;

					//cek single (sementara ini tidak ada custom value yang melekat di single)
					// if(this.prop_dataInfo.form_single)
					// {
					// 	for(var i = 0;i<this.prop_dataInfo.form_single.length;i++)
					// 	{
					// 		var temp = this.prop_dataInfo.form_single[i];
					// 		if(!this.prop_dataInfo.single[temp]) continue;
					// 		var obj = this.prop_dataInfo.single[temp];
					// 		if(obj.value)
					// 		{
					// 			var formula = obj.value;
					// 			for(var j = 0;j<formula.length;j+=2)
					// 			{
					// 				self.$watch('input.' + formula[j], function(newValue, oldValue) {
					// 		            self.input[temp] = self.calculate_custom_value(self.input,formula, 'int');
					// 		        });
					// 			}
					// 		}
							

					// 	}
						
					// }


					//cek multiple
					if(this.prop_dataInfo.form_multiple)
					{
						for(var i = 0;i<this.prop_dataInfo.form_multiple.length;i++)
						{
							var temp = this.prop_dataInfo.form_multiple[i];
							if(!this.prop_dataInfo.multiple[temp].single) continue;
							Object.keys(this.prop_dataInfo.multiple[temp].single).map(function(key, index) {
								var obj = self.prop_dataInfo.multiple[temp].single[key];
							    if(obj.value) //jika valuenya custom
								{
									var formula = obj.value;
									for(var j = 0;j<formula.length;j+=2)
									{
										var temp2 = JSON.parse(JSON.stringify(temp));
										self.$watch('input.' + formula[j], function(newValue, oldValue) {
											
								            self.temp_input[temp2][key] = self.calculate_custom_value(self.input,formula, 'int');
								        });
									}
								}
							});

						}

					}
					

				}
				
			},

			// set_temp_input()
			// {
			// 	for(var i = 0;i<this.prop_dataInfo.form_multiple.length;i++)
			// 	{
			// 		var temp = this.prop_dataInfo.form_multiple[i];
			// 		if(!this.prop_dataInfo.multiple[temp].single) continue;
			// 		this.temp_input[temp] = {};
			// 		var self = this;
			// 		Object.keys(this.prop_dataInfo.multiple[temp].single).map(function(key, index) {
			// 			self.temp_input[temp][key] = null;
						
			// 		});
					

			// 	}
				
			// }

	        	
		},
		mounted(){


		},
		watch : 
		{
			dialog_form : function(val)
			{
				if(!val)
					this.clear_input();
			}
		},
		created()
		{

		}
	}
</script>