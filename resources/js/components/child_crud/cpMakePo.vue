<template>
    <div>
    	<v-layout row class='bgwhite' style='margin-bottom: 20px'>
            <v-flex xs8>
                <div class='marginleft30 margintop10'>
                    <h2 class='titledatatable'>Make PO</h2>
                </div>
            </v-flex>
        </v-layout>
        <div v-if='data_ready'>
	        <div v-for='(item,index) in data.rekap_purchase_orders'>

	        	<v-layout row class='bgwhite' style='margin-bottom: 20px'>
		            <v-flex xs8>
		                <div class='marginleft30 margintop10'>
		                    <h2 class='titledatatable'>{{item.supplier_name}}</h2>
		                </div>
		            </v-flex>
		        </v-layout>

		        <v-layout row class='bgwhite marginleft30 margintop10' style='margin-bottom: 20px' >
		        	<v-flex xs11>
				        <v-data-table
				        disable-initial-sort
				        :headers="headers"
				        :items="item.purchase_request_details"
				        class='datatable'
				        :rows-per-page-items='[{"text" : "All", "value" : -1}]'
				        hide-actions
				        >
					        <template v-slot:items="props">
					            <td>{{ props.index + 1 }}</td>
					            <td>{{ props.item.goods_name}}</td>
					            <td>{{ props.item.qty}}</td>
					            <td>{{ format_data(props.item.price, ["price"])}}</td>
					            <td>{{ format_data(props.item.subtotal, ["price"])}}</td>
					        </template>
				        </v-data-table>
				    </v-flex>
			    </v-layout>

		        <v-layout row class='bgwhite marginleft30 margintop10' style='margin-bottom: 20px'>
		        	<v-flex xs12 class='marginleft30'>
		        		<h3>Total : {{strToPrice(item.total, "Rp. ")}}</h3>
		        	</v-flex>
		            <v-flex xs4>
		                <v-btn style='margin-left: 90px' v-if='data.rekap_purchase_orders[index].disable == false' color='primary' dark @click='make_po(JSON.parse(JSON.stringify(item.supplier_id)))'>
		                	Make PO
		                	
		                </v-btn>
		            </v-flex>
		        </v-layout>

	        </div>
	        <v-btn @click='cancel'>Close</v-btn>
	    </div>
    	
        
        <!-- ================================ -->
    </div>
    
</template>
<script>

	export default {
		props : [
		'prop_list_filter',
		],
		data () {
			return {
				id : null,
				data : [],
				data_ready : false,
				headers: [
					{ text: 'No', value:'no',sortable:false,width:'10%'},
    				{ text: 'Goods', value:'goods_name',sortable:false,width:'35%'},
    				{ text: 'Amount Order', value:'qty',sortable:false,width:'15%'},
    				{ text: 'Pricelist', value:'price',sortable:false,width:'20%'},
    				{ text: 'Subtotal', value:'subtotal',sortable:false,width:'20%'},
				],
			}
		},
		methods: 
		{
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
			cancel()
			{

				localStorage.removeItem('temp_pr_recap_' + this.id);
				this.$emit('cancel');
				//this.$emit('cancel');
			},
			make_po(supplier_id)
			{
				const formData = new FormData();
				formData.append('supplier_id', supplier_id);
				axios.post(
	            	'api/purchaseRequests/' + this.id + '/purchaseOrders',
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
		            	for(var i = 0;i<this.data.rekap_purchase_orders.length;i++)
		            	{
		            		if(this.data.rekap_purchase_orders[i].supplier_id == supplier_id)
		            		{
		            			swal("Good job!", "Data saved !", "success");
		            			Vue.set(this.data.rekap_purchase_orders[i], 'disable', true)
		            			this.data_ready = false;
		            			this.data_ready = true;

		            			
		            			break;
		            		}
		            	}
	            });
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
	        
			fill_data(data_from_server)
			{
				this.data = JSON.parse(JSON.stringify(data_from_server));
				if(this.data.rekap_purchase_orders.length == 0)
				{
					this.$emit('cancel');
					swal("All Have Become PO", "If you would like to check it out, please go to the PO menu", "error");
				}
				else
				{
					for(var i = 0;i<this.data.rekap_purchase_orders.length;i++)
					{
						this.data.rekap_purchase_orders[i].disable = false;
					}

					this.data_ready = true;

				}

				
			}
		},
		computed : 
		{
			
		}
	}
</script>