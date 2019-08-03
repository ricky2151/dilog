export default
{
	//label => judul input
	//header => header pada datatable
	//read => nama kolom yang ditampilkan di datatable
	//title => judul dari tabel
	//width => lebar input
	//layout => urutan dan posisi

	//tf => text field
	//ta => textarea
	//img => v-text-field + input + img
	//s => v-select

	//editable => bisa next ke step berikutnya

	//index pada single merepresentasikan input.[index pada single]
	//ref pada input tipe select, tidak perlu dituliskan karena pasti ref_input.[nama tabel]

	//send type : 
	//1. langsung kirim hasil akhir
	//2. kirim hasil akhir dengan penjelasan apa yang diinsert, apa yang diupdate, dan apa yang didelete

	data () {
		return{
			database : 
			{
				"categories" : 
				{
					table_name : 'categories',
					title : 'Category',
					icon : 'category',

					singular_name : 'category',
					plural_name : 'categories',
					column_desc : 'name',

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit','Goods', 'Delete'],

					request_master_data : false,
					data : 
					{
						datatable:[
							{
								column : 'name',
							},
							// {
							// 	column : '',
							// 	value : ['name', '*', 'qty']
							// }
						],
						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Name', value:'name'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],

						form_single : [['name']],
						single : 
						{
							'id' : {
								label : '',
							},
							'name' : {
								label : 'Name', width:12, type:'tf', validation:'max_req',
							},

						}
					},
					get_data_detail : 
					{
						"goods":
						{
							table_name : 'goods',
							title : 'Goods',
							single : 
							{
								'id' : {show : false},
								'name' : {show : true},
								'stock' : {show : true},
							},
							headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Name', value:'name'},
                				{ text: 'Stock', value:'stock'},
							]
						},
					}
				},
				"goods" : 
				{
					table_name : 'goods',
					title : 'Goods',
					icon : 'widgets',

					singular_name : 'goods',
					plural_name : 'goods',
					column_desc : 'name',

					widthForm : 'fullscreen',
					editable_edit : true,
					editable_add : true,
					count_step : 6,

					actions:['Edit', 'Rack', 'SP', 'Stock Card', 'Supplier', 'COGS', 'Delete'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {
							'avg_price_status':[
			                    {value:1,name:'True'},
			                    {value:0,name:'False'},
			                ],
			                'free':[
			                    {value:1,name:'True'},
			                    {value:0,name:'False'},
			                ],
						},
						datatable:[
							{ column : 'name' }, { column : 'code' }, { column : 'value' }, { column : 'status' }, { column : 'last_buy_pricelist' }, { column : 'avg_price' }, { column : 'stock' }, { column : '', value : ['avg_price', '*', 'stock'] },
						],
						headers: [
			                { text: 'No', value: 'no'},
			                { text: 'Name', value: 'name'},
			                { text: 'Code', value: 'code', align:'right' },
			                { text: 'Value', value: 'value', align:'right' },
			                { text: 'Status', value: 'status', align:'right' },
			                { text: 'Last Buy Pricelist', value: 'last_buy_pricelist', align:'right' },
			                { text: 'AVGPrice', value: 'avgprice', align:'right' },
			                { text: 'Stock', value: 'stock', align:'right' },
			                { text: 'Inventory Value', value: 'inventory_value', align:'right' },
			                { text: 'Action', align:'left',sortable:false, width:'15%'},
			            ],
						form_single : [['name', 'code'], ['desc'],['status'], ['value', 'last_buy_pricelist'], ['barcode_master'], ['thumbnail'], ['avg_price_status'], ['avg_price', 'tax'], ['unit'], ['cogs'], ['margin']],
						single : 
						{
							'id' : { 
								label : '', 
							},
							'name' : { 
								label : 'Name', width:9, type:'tf', validation:'max_req',
							},
							'code' : { 
								label : 'Code', width:3, type:'tf', validation:'max_req',
							},
							'desc' : { 
								label : 'Description', width:12, type:'ta', validation:'max',
							},
							'margin' : { 
								label : 'Margin', width:12, type:'tf', validation:'numeric_req',
							},
							'value' : { 
								label : 'Value', width:6, type:'tf', validation:'numeric_req',
							},
							'status' : { 
								label : 'Status', width:12,  type:'tf', validation:'numeric_req',
							},
							'last_buy_pricelist' : { 
								label : 'Last Buy Pricelist', width:6,  type:'tf', validation:'numeric',
							},
							'barcode_master' : { 
								label : 'Barcode', width:12,  type:'tf', validation:'max',
							},
							'avg_price_status' : { 
								label : 'Average Price Status', width:12, type:'s', 
								itemText:'name', itemValue:'value', column:'avg_price_status'
							},
							'avg_price' : { 
								label : 'Average Price', width:6, type:'tf', validation:'numeric',
							},
							'tax' : { 
								label : 'Tax', width:6, type:'tf', validation:'numeric',
							},
							'unit' : { 
								label : 'Unit', width:12, type:'s', validation:'selectdata_req', 
								itemText:'name', itemValue:'id', column:'unit_id', table_ref:'units'
							},
							'cogs' : { 
								label : 'COGS', width:12, type:'s', validation:'selectdata_req', 
								itemText:'name', itemValue:'id', column:'cogs_id', table_ref:'cogs'
							},
							'thumbnail' : { 
								label : 'Thumbnail', width:12,  type:'img', validation:'',
								fileNameVariable : 'thumbnail_filename', fileVariable : 'thumbnail_file', previewVariable : 'thumbnail',
							},
							
						},
						
						form_multiple : ['price_sellings', 'category_goods', 'attribute_goods', 'pricelists', 'materials'],
						multiple : {
							"category_goods":
							{
								title : 'Gooods Category',
								type : 'chips',
								item_value : 'id',
								item_text : 'name',
								ref_table : 'categories',
								column : 'category_id',
								send_type : '1' //langsung (sync)
							},
							"attribute_goods":
							{
								title : 'Goods Attributes',
								type : 'table',

								singular_name : 'attribute_goods',
								plural_name : 'attribute_goods',

								datatable : [{column : ['attribute', 'name']}, {column : ['value']}],
								headers: 
								[
									{ text: 'No', value:'no'},
	                				{ text: 'Attribute', value:'attribute'},
	                				{ text: 'Value', value:'value'},
	                				{ text: 'Action', align:'left',sortable:false, width:'15%'},
								],
								form_single:[['attribute'], ['value']],
								single : 
								{
									'id' : { 
										label : '', 
									},
									'attribute' : { 
										label : 'Attribute', width:12, type:'s',
										itemText:'name', itemValue:'id', column:'attribute_id', table_ref:'attributes'

									},
									'value' : { 
										label : 'Value', width:12, type:'tf', column:'value'
									},
									
								},
								send_type : '1'
							},
							"price_sellings":
							{
								title : 'Goods Price Sellings',
								type : 'table',

								singular_name : 'price_sellings',
								plural_name : 'price_sellings',

								datatable : [{column : ['warehouse', 'name']}, {column : ['stock_cut_off']}, {column : ['category_price_selling','name']}, {column : ['price']}, {column : ['free','name']}],
								headers: 
								[
									{ text: 'No', value:'no'},
	                				{ text: 'Warehouse', value:'warehouse'},
	                				{ text: 'Stock Cut Off', value:'stock_cut_off'},
	                				{ text: 'Category Price Selling', value:'category_price_selling'},
	                				{ text: 'Price', value:'price'},
	                				{ text: 'Free', value:'free'},
	                				{ text: 'Action', align:'left',sortable:false, width:'15%'},
								],
								form_single:[['warehouse'], ['stock_cut_off'], ['category_price_selling'], ['price'], ['free']],
								single : 
								{
									'id' : { 
										label : '', 
									},
									'warehouse' : { 
										label : 'Warehouse', width:12, type:'s',
										itemText:'name', itemValue:'id', column:'warehouse_id', table_ref:'warehouses'
									},
									'stock_cut_off' : { 
										label : 'Stock Cut Off', width:12, type:'tf', column:'stock_cut_off'
									},
									'category_price_selling' : { 
										label : 'Category Price Selling', width:12, type:'s',
										itemText:'name', itemValue:'id', column:'category_price_selling_id', table_ref:'category_price_sellings'
									},
									'price' : { 
										label : 'Price', width:12, type:'tf',
										value : ['margin', '+', 'last_buy_pricelist'], disabled : true, column:'price'
									},
									'free' : { 
										label : 'Free', width:12, type:'s',
										itemText:'name', itemValue:'value', column:'free', table_ref:'free'
									},
									
								},
								send_type : '2' //menggunakan type (insert, update, delete)
							},
							"pricelists":
							{
								title : 'Goods Pricelists',
								type : 'table',

								singular_name : 'pricelists',
								plural_name : 'pricelists',

								datatable : [{column : ['supplier', 'name_company']}, {column : ['price']} ],
								headers: 
								[
									{ text: 'No', value:'no'},
	                				{ text: 'Supplier', value:'supplier'},
	                				{ text: 'Price', value:'price'},
	                				{ text: 'Action', align:'left',sortable:false, width:'15%'},
								],

								form_single:[['supplier'], ['price']],
								single : 
								{
									'id' : { 
										label : '', 
									},
									'supplier' : { 
										label : 'Supplier', width:12, type:'s',
										itemText:'name_company', itemValue:'id', column:'supplier_id', table_ref:'suppliers'
									},
									'price' : { 
										label : 'Price', width:12, type:'tf', column:'price'
									},
									
								},
								send_type : '1'
							},
							"materials":
							{
								title : 'Goods Materials',
								type : 'table',

								singular_name : 'materials',
								plural_name : 'materials',

								datatable : [{column : ['name']}, {column : ['total']}, {column : ['adjust']} ],
								headers: 
								[
									{ text: 'No', value:'no'},
	                				{ text: 'Name', value:'material'},
	                				{ text: 'Total', value:'total'},
	                				{ text: 'Adjust', value:'adjust'},
	                				{ text: 'Action', align:'left',sortable:false, width:'15%'},
								],

								form_single:[['name'], ['total'], ['adjust']],
								single : 
								{
									'id' : { 
										label : '', 
									},
									'name' : { 
										label : 'Name', width:12, type:'tf', column:'name'
									},
									'total' : { 
										label : 'Total', width:12, type:'tf', column:'total'
									},
									'adjust' : { 
										label : 'Adjust', width:12, type:'tf', column:'adjust'
									},
									
								},
								send_type : '2'
							}
						}
					},

					get_data_detail : 
					{
						"racks":
						{
							table_name : 'racks',
							title : 'Racks',
							single : 
							{
								'id' : {show : false},
								'rack' : {show : true},
								'stock' : {show : true},
							},
							headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Racks', value:'rack'},
                				{ text: 'Stock', value:'stock'},
							]
						},
						"price_sellings":
						{
							table_name : 'price_sellings',
							title : 'Price Sellings',
							single : 
							{
								'id' : {show : false},
								'goods_id' : {show : false},
								'category_price_selling_id' : {show : false},
								'warehouse_id' : {show : false},
								'warehouse_name' : {show : true},
								'stock_cut_off' : {show : true},
								'category_price_selling_name' : {show : true},
								'price' : {show : true},
								'free' : {show : true},
							},
							headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Warehouse', value:'warehouse_name'},
                				{ text: 'Stock Cut Off', value:'stock_cut_off'},
                				{ text: 'Category Price Selling', value:'category_price_selling_name'},
                				{ text: 'Price', value:'price'},
                				{ text: 'Free', value:'free'},
							]
						},
						// "pricelists": //belum jadi
						// {
						// 	table_name : 'pricelists',
						// 	title : 'Pricelists',
						// 	single : ['id', 'supplier_id', 'goods_id', 'price'],
						// 	read : [''],
						// 	headers: [
						// 		{ text: 'No', value:'no'},
      //           				{ text: 'Supplier'},
      //           				{ text: 'Goods'},
      //           				{ text: 'Price'},
						// 	]
						// },

					}



				},
				"warehouses" : 
				{
					table_name : 'warehouses',
					title : 'Warehouse',
					icon : 'store',

					singular_name : 'warehouse',
					plural_name : 'warehouses',
					column_desc : 'name',

					widthForm : 'fullscreen',
					editable_edit : true,
					editable_add : true,
					count_step : 2,

					actions:['Edit', 'Rack', 'Goods', 'Stock OP', 'Delete'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {},
						datatable:[
							{ column : 'name' }, { column : 'address' }, { column : 'telp' }, { column : 'pic' },
						],
						headers: [
			                { text: 'No', value: 'no'},
			                { text: 'Name', value: 'name'},
			                { text: 'Address', value: 'address', align:'right' },
			                { text: 'Telephone', value: 'telp', align:'right' },
			                { text: 'PIC', value: 'pic', align:'right' },
			                { text: 'Action', align:'left',width:'15%',sortable:false},
			            ],
						form_single : [['name'], ['address'],['telp'], ['pic'], ['email'], ['lat'], ['lng']],
						single : 
						{
							'id' : { 
								label : '', 
							},
							'name' : { 
								label : 'Name', width:12, type:'tf', validation:'max_req',
							},
							'address' : { 
								label : 'Address', width:12, type:'tf', validation:'max_req',
							},
							'telp' : { 
								label : 'Telphone', width:12, type:'tf', validation:'max_req',
							},
							'pic' : { 
								label : 'PIC', width:12, type:'tf', validation:'max_req',
							},
							'email' : { 
								label : 'Email', width:12, type:'tf', validation:'max_req',
							},
							'lat' : { 
								label : 'Latitude', width:12,  type:'tf_gm', validation:'max_req',
								disabled : true, gm : 'lat'
							},
							'lng' : { 
								label : 'Longitde', width:12,  type:'tf_gm', validation:'max_req',
								disabled : true, gm : 'lng'
							},
						},
						custom_single : 
						{
							gm : 
							{
								active :  true,
							}	
						},
						
						form_multiple : [],
						multiple : {
							
						},

						form_custom_component : ['cpMakeOrCopyChild'],
						custom_component : 
						{
							'cpMakeOrCopyChild' : 
							{
								title : 'Racks',
								parent : 
								{
									title : "Warehouse",
									table_name : "warehouse",
									itemText : 'name',
									itemValue : 'id'
								},
								child : 
								{
									title : "Racks",
									table_name : 'racks',
									column_show : 'name',
									header : [{text:'Rack', value:'rack'},{text:'Action', value:'action'}],
								}

							}
							
						}
					},

					get_data_detail : 
					{
						"racks":
						{
							table_name : 'racks',
							title : 'Racks',
							single : 
							{
								'id' : {show : false},
								'rack' : {show : true},
								'stock' : {show : true},
							},
							headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Racks', value:'rack'},
                				{ text: 'Stock', value:'stock'},
							]
						},
						"price_sellings":
						{
							table_name : 'price_sellings',
							title : 'Price Sellings',
							single : 
							{
								'id' : {show : false},
								'goods_id' : {show : false},
								'category_price_selling_id' : {show : false},
								'warehouse_id' : {show : false},
								'warehouse_name' : {show : true},
								'stock_cut_off' : {show : true},
								'category_price_selling_name' : {show : true},
								'price' : {show : true},
								'free' : {show : true},
							},
							headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Warehouse', value:'warehouse_name'},
                				{ text: 'Stock Cut Off', value:'stock_cut_off'},
                				{ text: 'Category Price Selling', value:'category_price_selling_name'},
                				{ text: 'Price', value:'price'},
                				{ text: 'Free', value:'free'},
							]
						},
						// "pricelists": //belum jadi
						// {
						// 	table_name : 'pricelists',
						// 	title : 'Pricelists',
						// 	single : ['id', 'supplier_id', 'goods_id', 'price'],
						// 	read : [''],
						// 	headers: [
						// 		{ text: 'No', value:'no'},
      //           				{ text: 'Supplier'},
      //           				{ text: 'Goods'},
      //           				{ text: 'Price'},
						// 	]
						// },

					}



				}
			}
		}
	},
	methods:{
		generate_url(table,type,id,tableDetail)
		{

			var result = '';
			if(type == 'index' || type == 'store')
			{
				result = '/api/' + table;
			}
			else if(type == 'edit')
			{
				result = '/api/' + table + '/' + id + '/edit';
			}
			else if(type == 'detail')
			{
				
				result = '/api/' + table + '/' + id + '/' + tableDetail;
			}
			else if(type == 'update' || type == 'delete')
			{
				result = '/api/' + table + '/' + id;
			}
			else if(type == 'create')
			{
				result = '/api/' + table + '/create';
			}
			return result;
			
		},
		generate_input(table)
		{
			if(table)
			{
				var result = {};
				if(this.database[table].data.single)
				{
					
					var form_single = this.database[table].data.form_single;

					for(var i = 0;i<form_single.length;i++)
		        	{
		        		for(var j =0;j<form_single[i].length;j++)
		        		{
		        			var nameColumn = form_single[i][j];
		        			result[nameColumn] = null;
		        		}
		        	}
				}

				if(this.database[table].data.multiple)
				{
					
					var form_multiple = this.database[table].data.form_multiple;
					var multiple = this.database[table].data.multiple;

					for(var i = 0;i<form_multiple.length;i++)
					{
						var name_child_form = form_multiple[i];
						result[name_child_form] = [];

						

					}
				}
				
				
				return result;


			}
			
		},
		generate_preview(table) //sementara ini baru untuk single dulu yang image
		{
			if(table)
			{
				if(this.database[table].data.single)
				{
					var result = {};
					var form_single = this.database[table].data.form_single;
					var single = this.database[table].data.single;


					for(var i = 0;i<form_single.length;i++)
		        	{
		        		for(var j =0;j<form_single[i].length;j++)
		        		{
		        			var nameColumn = form_single[i][j];
		        			if(single[nameColumn].type == 'img')
		        			{
		        				result[nameColumn] = null;
		        			}
		        			
		        		}
		        	}
				}

			}
			return result;
		},
		generate_temp_input(table)
		{
			if(table)
			{
				if(this.database[table].data.multiple)
				{
					var result = {};
					var form_multiple = this.database[table].data.form_multiple;
					var multiple = this.database[table].data.multiple;

					for(var i = 0;i<form_multiple.length;i++)
					{
						var name_child_form = form_multiple[i];
						var obj = multiple[name_child_form];
						if(obj.type == 'table')
						{
							var temp_result = {};
							Object.keys(obj.single).map(function(key, index) {
								temp_result[key] = null;
							});
							temp_result['idx_edit'] = -1;
							result[name_child_form] = temp_result;
						}
					}
				}

			}
			return result;
		}
		
	}	
}