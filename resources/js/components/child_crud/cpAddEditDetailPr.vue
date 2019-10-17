<template>
	<v-dialog v-model='dialog' width=750>
		<v-form v-model='valid' ref='form'>
			<v-card>
				<v-toolbar dark color="menu">
					<v-btn icon dark v-on:click='close_dialog'>
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title v-html='id_edit == -1 ? "Add Detail PR" : "Edit Detail PR"'></v-toolbar-title>
				</v-toolbar>

				<v-stepper vertical>
					<v-stepper-step step="1">
						<h3>Detail PR</h3>
					</v-stepper-step>
					<v-stepper-content step="1">
						
						<v-layout row>
							<v-flex xs12>
								<v-text-field
								:rules='$list_validation.numeric_req'
								label='qty'
								v-model='input.qty'
								class="pa-2"
								>
								</v-text-field>

							</v-flex>
						</v-layout>

						<v-layout row>
							<v-flex xs12>
								
								<v-select
								v-if='input.goods'
								:rules='$list_validation.selectdata_req'
								label='Supplier'
								v-model='input.supplier'

								:items='input.goods.suppliers'
								item-text='name_company'
								return-object

								class="pa-2"
								>
								</v-select>

							</v-flex>
						</v-layout>

						<v-layout row>
							<v-flex xs12>
								
								<v-select
								v-if='input.supplier'
								:rules='$list_validation.selectdata_req'
								label='Pricelist'
								v-model='input.pricelist'

								:items='input.supplier.pricelists'
								item-text='price'
								return-object

								class="pa-2"
								>
								</v-select>

							</v-flex>
						</v-layout>

						

						


						<v-layout row>
							<v-flex xs12>
								<h3>Subtotal : {{input.pricelist ? strToPrice(subtotal, "Rp. ") : ""}}</h3>
								
							</v-flex>
						</v-layout>
						<br><br>

						<v-layout row>
							<v-flex xs2>
								<v-btn dark color='blue' @click='save_data'>Submit</v-btn>
							</v-flex>
						</v-layout>
					</v-stepper-content>
				</v-stepper>
				
				
			</v-card>
		</v-form>
	</v-dialog>
</template>
<script>
	export default{
		props : ['prop_purchase_request_id'],
		data () {
			return {
				dialog : false,
				valid : false,

				

				id_edit : null,
				input : {
					goods : null,
					pricelist : null,
					supplier : null,
					qty : 0,
				},

				
				ref_input : [],
			}
		},
		methods : {


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
				}
				return result;
			},


			prepare_data()
			{
				const formData = new FormData();
				if(this.id_edit != -1)
				{
					formData.append('_method', 'patch');
				}
				if(this.id_edit == -1)
				{
					formData.append('purchase_request_id', this.prop_purchase_request_id);
				}
				formData.append('pricelist_id', this.input.pricelist.id);
				formData.append('supplier_id', this.input.supplier.id);
				formData.append('qty', this.input.qty);
				formData.append('price', this.input.pricelist.price);
				formData.append('token', localStorage.getItem('token'));

				return formData;

			},
			save_data()
			{
				var url_post = '';
				if(this.id_edit == -1)
				{
					url_post = '/api/purchaseRequestDetails';
				}
				else
				{
					url_post = '/api/purchaseRequestDetails/' + this.id_edit;
				}

				axios.post(
                	url_post,
                	this.prepare_data(),
	                	{
		                'Accept': 'application/json',
		                'Content-type': 'application/json' //default
		            	}
		            ).then((r) => {
		            this.input = {
		            	goods : null,
						pricelist : null,
						supplier : null,
						qty : 0,
					};
					this.$refs.form.resetValidation();
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
				
			},

			strToPrice(angka,prefix)
	        {
	            //100000
	            //9.000
	            //11212
	            //11.212
	            if(angka){
		            angka = angka.toString();
		            var akhir = '';
		            if(angka.includes('.'))
		            {
		            	akhir = "," + angka.substr(angka.indexOf('.') + 1);
		            	angka = angka.substr(0, angka.indexOf('.'));

		            }
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
		            return prefix + hasil + akhir;

	            }
	            else
	            {
	            	return "";
	            }
	        },
			get_data_before_edit(id_edit) //nanti dihapus karena sudah ada di component
	        {
	        	try{
		            var response = axios.get('/api/purchaseRequestDetails/' + id_edit + '/edit', {
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
	        },
	        close_dialog()
	        {
	        	this.dialog = false;
	        },
			async open_dialog(id)
			{
				if(id == -1)
				{
					this.fill_select_master_data();	

				}
				
				var ref_input_from_server = [];
				if(id != -1)
				{
					let r = await this.get_data_before_edit(id);
					r = r.data.items;

					
					if(r.purchase_request_detail.is_created_as_po == 1)
					{
						swal('Cannot Edit !', 'This PR has been submitted to PO', 'error');
						this.close_dialog();
						return 0;
					}
					else
					{
						//isi master data dulu
						//isi refinput
						
						this.ref_input.goods = JSON.parse(JSON.stringify(r.master_data.goods));

						
						

						//this.input.goods di assign dari master data
						
						var id_goods = r.purchase_request_detail.goods_id;
						for(var i = 0;i<this.ref_input.goods.length;i++)
						{
							if(this.ref_input.goods[i].id == id_goods)
							{
								this.input.goods = this.ref_input.goods[i];
								
								break;
							}
						}

						var id_supplier = r.purchase_request_detail.supplier_id;
						for(var i = 0;i<this.input.goods.suppliers.length;i++)
						{
							if(this.input.goods.suppliers[i].id == id_supplier)
							{
								this.input.supplier = this.input.goods.suppliers[i];
								break;
							}
						}
						console.log('cek id supplier');
						console.log(this.input);

						//this.input.pricelist di assign dari input.goods.pricelists
						var id_pricelist = r.purchase_request_detail.pricelist_id;
						for(var i = 0;i<this.input.supplier.pricelists.length;i++)
						{
							if(this.input.supplier.pricelists[i].id == id_pricelist)
							{
								this.input.pricelist = this.input.supplier.pricelists[i];
								break;
							}
						}

						this.input.qty = r.purchase_request_detail.qty;

					}


					
					
				}
				else
				{
					//clear input
					this.input = {
						goods : null,
						supplier:null,
						pricelist : null,
						qty : 0,
					};
					this.$refs.form.resetValidation();
				}

				

				this.dialog = true;
				this.id_edit = id;
			}
		},
		mounted () {
			
		},
		computed : {
			subtotal : function () {
				var qty = 0;
				var price = 0;
				if(this.input.qty)
				{
					qty = parseInt(this.input.qty);
				}
				if(this.input.pricelist)
				{
					price = parseInt(this.input.pricelist.price);
				}
				
				

				
				
				
				return (Math.floor(price * qty));
				
				
			},
			
		},
		watch : {
			
		}
	}
</script>