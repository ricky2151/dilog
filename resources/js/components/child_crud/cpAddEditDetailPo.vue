<template>
	<v-dialog v-model='dialog' width=750>
		<v-form v-model='valid' ref='form'>
			<v-card>
				<v-toolbar dark color="menu">
					<v-btn icon dark v-on:click='close_dialog'>
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title v-html='id_edit == -1 ? "Add Detail PO" : "Edit Detail PO"'></v-toolbar-title>
				</v-toolbar>

				<v-stepper vertical>
					<v-stepper-step step="1">
						<h3>Detail PO</h3>
					</v-stepper-step>
					<v-stepper-content step="1">
						<v-layout row>
							<v-flex xs12>
								<v-select
								v-if='ref_input.goods'
								:rules='$list_validation.selectdata_req'
								label='Goods'
								v-model='input.goods'

								:items='ref_input.goods'
								item-text='name'
								return-object

								class="pa-2"
								>
								</v-select>

							</v-flex>
						</v-layout>

						<v-layout row>
							<v-flex xs12>
								
								<v-select
								v-if='input.goods'
								:rules='$list_validation.selectdata_req'
								label='Pricelist'
								v-model='input.pricelist'

								:items='input.goods.pricelists'
								item-text='pricerupiah'
								return-object

								class="pa-2"
								>
								</v-select>

							</v-flex>
						</v-layout>

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
								<v-text-field
								:rules='$list_validation.numeric_req'
								label='Tax'
								v-model='input.tax'
								class="pa-2"
								suffix='%'
								>
								</v-text-field>

							</v-flex>
						</v-layout>

						<v-checkbox
			            v-model="use_rupiah"
			            color="primary"
			            label='Use Discount Rupiah'
				        ></v-checkbox>


						<v-layout row v-if='!use_rupiah'>
							<v-flex xs12>
								<v-text-field
								:rules='$list_validation.numeric'
								label='Dicount Percent'
								v-model='input.discount_percent'
								class="pa-2"
								>
								</v-text-field>

							</v-flex>
						</v-layout>

						<v-layout row v-if='use_rupiah'>
							<v-flex xs12>
								<v-text-field
								:rules='$list_validation.numeric'
								label='Discount Rupiah'
								v-model='input.discount_rupiah'
								class="pa-2"
								>
								</v-text-field>

							</v-flex>
						</v-layout>

						<v-layout row>
							
							<v-flex xs12>
								<h4>{{use_rupiah ? "Percent : " + computed_price + "%" : "Discount Rupiah : " + strToPrice(computed_price, "Rp. ")}}</h4>

							</v-flex>
						</v-layout>
						<br><br>



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
		props : ['prop_purchase_order_id'],
		data () {
			return {
				dialog : false,
				valid : false,

				use_rupiah : false,

				id_edit : null,
				input : {
					goods : null,
					pricelist : null,
					qty : 0,
					tax : 0,
					discount_percent : 0,
					discount_rupiah : 0,
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
					formData.append('purchase_order_id', this.prop_purchase_order_id);
				}
				formData.append('goods_id', this.input.goods.id);
				formData.append('pricelist_id', this.input.pricelist.id);
				formData.append('qty', this.input.qty);
				formData.append('tax', this.input.tax);
				formData.append('discount_rupiah', this.input.discount_rupiah);
				formData.append('discount_percent', this.input.discount_percent);
				var discount_choose = 0;
				if(this.use_rupiah)
				{
					discount_choose = 2;
				}
				else
				{
					discount_choose = 1;
				}
				formData.append('discount_choose', discount_choose);
				formData.append('token', localStorage.getItem('token'));

				return formData;

			},
			save_data()
			{
				var url_post = '';
				if(this.id_edit == -1)
				{
					url_post = '/api/purchaseOrderDetails';
				}
				else
				{
					url_post = '/api/purchaseOrderDetails/' + this.id_edit;
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
						qty : 0,
						tax : 0,
						discount_percent : 0,
						discount_rupiah : 0,
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
			get_master_data()
			{
				try{
		            var response = axios.get('/api/purchaseOrderDetails/create', {
		                params:{
		                    token: localStorage.getItem('token'),
		                    purchase_order_id : this.prop_purchase_order_id,
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
			async fill_select_master_data()
			{
				let r = await this.get_master_data();
				this.ref_input = r.data.items;
				
				//buat pricerupiah
				for(var i = 0;i<this.ref_input.goods.length;i++)
				{
					for(var j = 0;j<this.ref_input.goods[i].pricelists.length;j++)
					{
						
						this.ref_input.goods[i].pricelists[j].pricerupiah = this.format_data(this.ref_input.goods[i].pricelists[j].price, ["price"]);
					}
				}
			},
			get_data_before_edit(id_edit) //nanti dihapus karena sudah ada di component
	        {
	        	try{
		            var response = axios.get('/api/purchaseOrderDetails/' + id_edit + '/edit', {
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


					//isi master data dulu
					//isi refinput
					
					this.ref_input.goods = JSON.parse(JSON.stringify(r.master_data.goods));

					for(var i = 0;i<this.ref_input.goods.length;i++)
					{
						for(var j = 0;j<this.ref_input.goods[i].pricelists.length;j++)
						{
							
							this.ref_input.goods[i].pricelists[j].pricerupiah = this.format_data(this.ref_input.goods[i].pricelists[j].price, ["price"]);
						}
					}
					

					//this.input.goods di assign dari master data
					
					var id_goods = r.purchase_order_detail.goods.id;
					for(var i = 0;i<this.ref_input.goods.length;i++)
					{
						if(this.ref_input.goods[i].id == id_goods)
						{
							this.input.goods = this.ref_input.goods[i];
							
							break;
						}
					}

					//this.input.pricelist di assign dari input.goods.pricelists
					var id_pricelist = r.purchase_order_detail.pricelist.id;
					for(var i = 0;i<this.input.goods.pricelists.length;i++)
					{
						if(this.input.goods.pricelists[i].id == id_pricelist)
						{
							this.input.pricelist = this.input.goods.pricelists[i];
							break;
						}
					}

					this.input.qty = r.purchase_order_detail.qty;
					this.input.tax = r.purchase_order_detail.tax;
					this.input.discount_percent = r.purchase_order_detail.discount_percent;
					this.input.discount_rupiah = r.purchase_order_detail.discount_rupiah;

					
					
				}
				else
				{
					//clear input
					this.input = {
						goods : null,
						pricelist : null,
						qty : 0,
						tax : 0,
						discount_percent : 0,
						discount_rupiah : 0,
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
				var discount_rupiah = 0;
				var discount_percent = 0;
				var qty = 0;
				var tax = 0;
				var price = 0;
				if(this.input.qty)
				{
					qty = parseInt(this.input.qty);
				}
				if(this.input.tax)
				{
					tax = parseInt(this.input.tax);
				}
				if(this.input.pricelist)
				{
					price = parseInt(this.input.pricelist.price);
				}
				tax += 100;
				tax /= 100;
				

				if(this.use_rupiah)
				{
					discount_rupiah = this.input.discount_rupiah;
				}
				else
				{
					discount_percent = this.input.discount_percent;
					discount_percent = (100 - discount_percent) / 100;
				}
				
				
				return (Math.floor(price * qty));
				// if(tax != 0)
				// {
				// 	if(this.use_rupiah)
				// 		return (Math.floor(tax * (price * qty)) - discount_rupiah);	
				// 	else
				// 		return Math.floor((tax * (price * qty)) * discount_percent);	
				// }
				// else
				// {
				// 	if(this.use_rupiah)
				// 		return Math.floor(((price * qty) - discount_rupiah));	
				// 	else
				// 		return Math.floor(((price * qty) * discount_percent));	
				// }
				
			},
			computed_price : function() {
				
				var discount_rupiah = 0;
				var discount_percent = 0;
				var qty = 0;
				var tax = 0;
				var price = 0;
				if(this.input.qty)
				{
					qty = parseInt(this.input.qty);
				}
				if(this.input.tax)
				{
					tax = parseInt(this.input.tax);
				}
				if(this.input.pricelist)
				{
					price = parseInt(this.input.pricelist.price);
				}
				tax += 100;
				tax /= 100;

				if(this.use_rupiah)
				{
					discount_rupiah = this.input.discount_rupiah;
				}
				else
				{
					discount_percent = this.input.discount_percent;
					discount_percent = (discount_percent) / 100; //karena hanya dihitung pengurangannya saja
				}


				if(this.use_rupiah)
				{
					if(this.input.discount_rupiah && this.input.pricelist)
					{
						//return Math.floor((discount_rupiah / (price * tax * qty)) * 100);
						var result = Math.floor((discount_rupiah / (price * qty)) * 100);
						if(result)
						{

							return result;
						}
						else
						{
							return 0;
						}
					}
				}
				else
				{
					if(this.input.pricelist)
					{
						//return Math.floor((price * tax * qty) * discount_percent);	
						var result = Math.floor(price * qty * discount_percent);
						if(result)
						{
							return result;
						}
						else
						{
							return 0;
						}
					}
					
				}
				return 0;
			}
		},
		watch : {
			
		}
	}
</script>