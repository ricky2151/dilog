<template>
	<div v-if='prop_dataInfo'>

		<v-dialog v-model='dialog_form' :width='prop_widthForm'>
			<v-form v-model='valid' ref='form'>
				<v-card>
					<v-toolbar dark color="menu">
						<v-btn icon dark v-on:click='close_dialog()'>
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title v-html='prop_idEdit == -1 ? "Add " + prop_title : "Edit " + prop_title'></v-toolbar-title>
					</v-toolbar>

					<v-stepper v-model='stepNow' vertical>
						<div v-for='(indexStep, key, idx) in prop_countStep' :key='key'>
							<v-stepper-step :complete='stepNow > (indexStep + 1)' step="indexStep + 1">
								<h3>{{prop_title}} Data</h3>
							</v-stepper-step>
							<v-stepper-content step='indexStep + 1' editable='prop_idEdit == -1 ? prop_editableEdit : prop_editableAdd'>
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
										<v-text-field
										v-if='objColumn.type=="tf"'
										:rules='$list_validation[objColumn.validation]'
										:label='objColumn.header'
										v-model='input[column]'
										>
										</v-text-field>

										<v-textarea
										v-if='objColumn.type=="ta"'
										:rules='$list_validation[objColumn.validation]'
										:label='objColumn.header'
										v-model='input[column]'
										>
										</v-textarea>


										<v-select
										v-if='objColumn.type=="s"'
										:rules='$list_validation[objColumn.validation]'
										:label='objColumn.header'
										v-model='input[column]'

										:items='ref_input[column]'
										:item-text='objColumn.itemText'
										:item-value='objColumn.itemValue'
										>
										</v-select>


										<v-text-field
										v-if='objColumn.type=="img"'
										v-on:click='pickFile(objColumn.idButton)'
										prepend-icon='attach_file'
										label='Select Image'
										v-model='input[objColumn.fileNameVariable]'
										>
										</v-text-field>
										<input
                                        :id='objColumn.idButton'
                                        type="file"
                                        style="display: none"
                                        accept="image/*"
                                        v-on:change='changeImage($event,objColumn.fileNameVariable, objColumn.previewVariable, fileVariable)'
                                    	>
                                    	<img :src="preview[objColumn.previewVariable]" height="150" v-if="preview[objColumn.previewVariable]"/>
                                    </v-flex>
                                </v-layout>
                            </v-stepper-content>
                        </div>
                    </v-stepper>
                </v-card>
            </v-form>
        </v-dialog>
    </div>
</template>
<script>
	import axios from 'axios'
	

	export default{
		props:[
		'prop_idEdit', 
		'prop_title', 
		'prop_countStep', 
		'prop_editableEdit', 
		'prop_editableAdd', 
		'prop_dataInfo',
		'prop_tableName',
		'prop_widthForm',
		],
		data() {
			return {
				//for element
				dialog_form:false,
				valid:null,
				stepNow:null,

				//for data
				input : [],
				ref_input : [],
				preview : [],
				url : null,

				//for element & data


			}
		},
		methods:
		{
			//for element
			close_dialog()
			{
				this.prop_idEdit = -1;
            	this.dialog_form = false;
			},
			open_dialog(id)
			{

				 if(id != -1)
		        {
		            this.prop_idEdit = id;
		            var r = this.get_data_before_edit(id);
		            this.convert_data_input(r);
		            
		        }
		        else
		        {
		        	this.clear_input();
		        }
		        this.dialog_form = true;
			},
			clear_input(){
	            this.$refs.form.resetValidation();


				for (var key in this.preview) {
				    // skip loop if the property is from prototype
				    if (!this.preview.hasOwnProperty(key)) continue;
				    //clear preview
				    this.preview[key] = '';
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
			get_data_before_edit(id_edit) //nanti dihapus karena sudah ada di component
	        {
	            
	            axios.get(this.url, {
	                params:{
	                    token: localStorage.getItem('token')
	                }
	            },{
	                headers: {
	                    'Accept': 'application/json',
	                    'Content-type': 'application/json'
	                }
	            }).then(r=> {

	                return r;
	            })
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
	        },
	        convert_data_input(r)
	        {
	        	var temp_r = r.data.items[this.prop_tableName];

	        	//isi data single
	        	for (var key in this.prop_dataInfo.form_single) {
				    // skip loop if the property is from prototype
				    if (!this.prop_dataInfo.form_single.hasOwnProperty(key)) continue;
				    
				    for(var nameColumn in key)
				    {
				    	// skip loop if the property is from prototype
				    	if (!key.hasOwnProperty(nameColumn)) continue;

				    	//cek jika gambar, maka tidak dimasukan ke input, tapi ke preview
				    	if(this.prop_dataInfo.single[nameColumn].type == 'img')
				    	{
				    		this.preview[this.prop_dataInfo.single[nameColumn].previewVariable] = temp_r[nameColumn];
				    	}
				    	else
				    	{
				    		this.input[nameColumn] = temp_r[nameColumn];
				    	}
				    }
				    
				}


				//isi data multiple
	        },

	        	
		},
		mounted(){
			console.log('mounted');
			console.log(this.prop_title);
		}
	}
</script>