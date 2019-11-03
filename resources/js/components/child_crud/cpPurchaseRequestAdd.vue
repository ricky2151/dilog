<template>
    <div>
    	<v-layout row class='bgwhite' style='margin-bottom: 20px'>
            <v-flex xs8>
                <div class='marginleft30 margintop10'>
                    <h2 class='titledatatable'>Group By Goods</h2>
                </div>
            </v-flex>
        </v-layout>
        
    	<v-data-table
        disable-initial-sort
        :headers="headers"
        :items="data"
        v-if='data_ready'
        :rows-per-page-items='[{"text" : "All", "value" : -1}]'
		hide-actions
        >
	        <template v-slot:items="props">
	            <td>{{ props.item.no }}</td>
	            <td>{{ props.item.goods_name}}</td>
	            <td>{{ props.item.stock}}</td>
	            <td>{{ props.item.total_required_by_mr}}</td>
	            <td>{{ props.item.total_already_po}}</td>
	            <td>
	            	<v-text-field
					label='Amount'
					v-model='input[props.index].amount_order'
					class="pa-2"
					>
					</v-text-field>
	            </td>
	            <td>
		            	<v-select
		            	v-if='typeof input[props.index].selected_supplier !== "undefined"'
						label='Supplier'
						clearable
						@click:clear='clear_input_select_supplier(props.index)'
						v-model='input[props.index].selected_supplier'
						:items='props.item.suppliers'
						item-text='name_company'
						return-object
						>
						</v-select>
	            </td>
	            <td>
		            	<v-select
		            	v-if='typeof input[props.index].selected_supplier !== "undefined"'
						label='Pricelists'
						clearable
						@click:clear='clear_input_select_pricelist(props.index)'
						v-model='input[props.index].selected_pricelist'
						:items='input[props.index].selected_supplier.pricelists'
						item-text='price_rupiah'
						return-object
						>
						</v-select>
	            </td>
	            <td>
	            	<div v-if='input[props.index].selected_pricelist'>
	            		<template v-if='input[props.index].amount_order && input[props.index].selected_pricelist.price'>
	            		{{strToPrice(input[props.index].amount_order * input[props.index].selected_pricelist.price, "Rp. ")}}
	            		</template>
	            		<template v-else>
	            			{{strToPrice("0", "Rp. ")}}
	            		</template>
	            		
	            	</div>
	            </td>
	        </template>
        </v-data-table>
        <v-layout row>
        	<v-flex xs9></v-flex>
        	<v-flex xs3>
        		<v-btn dark @click='save_to_localstorage()' color='blue'>Cancel</v-btn>
        		<v-btn dark @click='submit()' color='blue'>Submit</v-btn>
        	</v-flex>
        	
        </v-layout>
        
        <!-- ================================ -->
        
    </div>
    
</template>
<script>

	export default {
		props : [
		'prop_list_filter',
		'prop_temp_data',
		
		],
		
		data () {
			return {
				data : [],
				data_ready : false,
				input : [],
				headers: [
					{ text: 'No', value:'no',sortable:false},
    				{ text: 'Goods', value:'goods_name',sortable:false},
    				{ text: 'Stock', value:'stock',sortable:false},
    				{ text: 'Total MR', value:'total_required_by_mr',sortable:false},
    				{ text: 'Total PO', value:'total_already_po',sortable:false},
    				{ text: 'Qty Order', value:'amount_order',sortable:false},
    				{ text: 'Supplier', value:'supplier',sortable:false, width:'16%'},
    				{ text: 'Pricelists', value:'pricelists',sortable:false, width:'18%'},
    				{ text: 'Subtotal', value:'subtotal',sortable:false, width:'15%'},
				],
			}
		},
		methods: 
		{
			clear_input_select_supplier(idx)
			{
				this.$nextTick(() => {
					//this.input[idx] = {};
					this.input[idx].selected_pricelist = [];
					this.input[idx].selected_supplier = [];	
					this.input[idx].selected_supplier.pricelists = [];	
				});
					
				
				
			},
			clear_input_select_pricelist(idx)
			{
				this.$nextTick(() => {
					//this.input[idx] = {};
					this.input[idx].selected_pricelist = [];
					this.input[idx].selected_supplier = [];	
					this.input[idx].selected_supplier.pricelists = [];		
				});
				
					//print(this.input[idx]);
				
				
			},
			prepare_data()
			{
				const formData = new FormData();
				var idxformdata = 0;
				var cond = 3;
				//0 => ada data yang kosong
				//1 => amount order yang dimasukan melebihi yang diminta oleh mr
				//2 => tidak ada data yang dikirim
				//3 => benar
				for(var i = 0;i<this.input.length;i++)
				{

					//(B==S && B+F?G) && (!(B)) && (!C || !D || !E || !F)
					//a = this.input[i].amount_order && (parseInt(this.input[i].amount_order) + parseInt(this.data[i].total_already_po) > (parseInt(this.data[i].total_required_by_mr)))
					//!b = this.input[i].amount_order != 0
					//!c = this.input[i].amount_order <= 0
					//!d = this.input[i].selected_pricelist.price == null
					//!e = this.input[i].selected_pricelist.id == null
					//!f = this.input[i].selected_pricelist.supplier_id

					//declare bool variable
					var fill_the_amount_correctly = this.input[i].amount_order != '0' && this.input[i].amount_order && (parseInt(this.input[i].amount_order) + parseInt(this.data[i].total_already_po) <= (parseInt(this.data[i].total_required_by_mr))); //jika user mengisi amount order sesuai dengna yang diminta mr
					var all_fields_filled = this.input[i].amount_order != null && this.input[i].selected_pricelist.price != null && this.input[i].selected_pricelist.id != null && this.input[i].selected_pricelist.supplier_id != null //jika semua field terisi 
					var amount_is_zero = this.input[i].amount_order == '0'

					//!((A & B) || C)
// (!A || !B) && !C

// if(!(A))
// 	...
// else if(!B
					if(!( ((fill_the_amount_correctly) && (all_fields_filled)) || (amount_is_zero) ))
					{
						alert('masuk error');
						if(!amount_is_zero)
						{
							if(!all_fields_filled)
							{
								cond = 0;
							}
							else if(!fill_the_amount_correctly)
							{
								cond = 1;
							}
						}
						
						break;
					}
					else
					{
						
						if(!amount_is_zero)
						{
							formData.append('purchase_request_details[' + idxformdata + '][goods_id]', this.input[i].goods_id);
							formData.append('purchase_request_details[' + idxformdata + '][qty]', this.input[i].amount_order);
							formData.append('purchase_request_details[' + idxformdata + '][price]', this.input[i].selected_pricelist.price);
							formData.append('purchase_request_details[' + idxformdata + '][supplier_id]', this.input[i].selected_pricelist.supplier_id);
							formData.append('purchase_request_details[' + idxformdata + '][pricelist_id]', this.input[i].selected_pricelist.id);
							idxformdata += 1;	
						}

						
					}
					if(idxformdata == 0)
					{
						cond = 2;
					}
					
				}
				if(cond != 3)
				{
					if(cond == 0)
					{
						swal('Submit Failed !', 'Please input all field !', 'error');
					}
					else if(cond == 1)
					{
						swal('Submit Failed !', 'Please input the amount as requested by MR !', 'error');
					}
					else if(cond == 2)
					{
						swal('Submit Failed !', 'Cannot send empty input', 'error');
					}
					return false;
				}
				else
				{
					return formData;
				}
			},
			submit()
			{
				var formData = new FormData();
				formData = this.prepare_data();
				if(formData)
				{
					axios.post(
		            	'/api/purchaseRequests/' + this.data.id + '/purchaseRequestDetails',
		            		formData
		            	 ,{
			                headers: {
			                    'Accept': 'application/json',
			                    'Content-type': 'application/json'
			                },
			                params : 
			                {
			                	'token' : localStorage.getItem('token')
			                }
			            }).then((r)=> {
			            	this.delete_from_localstorage();
				            this.$emit('done', r,this.data.id);
		            });

				}
				// else
				// {
				// 	swal('Submit Failed !', 'Please input correctly !', 'error');
				// }
			},
			save_to_localstorage()
			{
				localStorage.setItem('temp_pr_recap_' + this.data.id, JSON.stringify(this.input));
				this.$emit('cancel');
			},
			delete_from_localstorage()
			{
				localStorage.setItem('temp_pr_recap_' + this.data.id, "");
			},
			strToPrice(angka,prefix)
	        {
	            //100000
	            //9.000
	            //11212
	            //11.212
	            //933913
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
	        
			fill_data(data_from_server, id_pr)
			{

				this.data = JSON.parse(JSON.stringify(data_from_server));
				this.data.id = id_pr;

				//sederhanakan array supplier dan array pricelist
				//sekalian tambahkan attribute supplier_selected dan pricelist_selected pada this.input

				var temp_input = localStorage.getItem('temp_pr_recap_' + this.data.id);
				if(temp_input)
				{
					this.input = JSON.parse(temp_input);
				}
				for(var i = 0;i<this.data.length;i++)
				{
					if(!temp_input)
					{
						var temp_push = 
						{
							goods_id : this.data[i].goods_id,
							selected_supplier : [],
							selected_pricelist : [],
							amount_order : [],
						}
						this.input.push(temp_push);

					}



					for(var j = 0;j<this.data[i].suppliers.length;j++)
					{
						for(var k =0;k<this.data[i].pricelists.length;k++)
						{
							if(this.data[i].pricelists[k].supplier_id == this.data[i].suppliers[j].id)
							{
								if(!this.data[i].suppliers[j]['pricelists'])
								{
									this.data[i].suppliers[j]['pricelists'] = [];
								}
								this.data[i].pricelists[k].price_rupiah = this.strToPrice(this.data[i].pricelists[k].price, 'Rp. ');
								this.data[i].suppliers[j]['pricelists'].push(this.data[i].pricelists[k]);
							}
						}
					}
					delete this.data[i].pricelists;
				}

				//isi attribute input agar tidak error
				// for(var i =0;i<this.data.length;i++)
				// {
				// 	this.input[i] = {};
				// 	this.inputf
				// }
				this.data_ready = true;
			}
		},
		
	}
</script>