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

	data() {
		return{
			database : 
			{

				//1. crud-categories
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
					button_on_index : ['Add Data'],

					request_master_data : false,
					data : 
					{
						rule_update:'send_all',
						custom_master_data : {},
						datatable:[
							{
								column : 'name',
							},
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

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
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


				//2. crud-units
				"units" : 
				{
					table_name : 'units',
					title : 'Unit',
					icon : 'dns',

					singular_name : 'unit',
					plural_name : 'units',
					column_desc : 'name', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : false,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'name',
							},
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

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},


				//3. crud-attributes
				"attributes" : 
				{
					table_name : 'attributes',
					title : 'Attribute',
					icon : 'build',

					singular_name : 'attribute',
					plural_name : 'attributes',
					column_desc : 'name', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : false,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'name',
							},
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

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},



				//4. crud-cogs
				"cogs" : 
				{
					table_name : 'cogs',
					title : 'COGS',
					icon : 'monetization_on',

					singular_name : 'cogs',
					plural_name : 'cogs',
					column_desc : 'name', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'name',
							},
							{
								column : 'nominal',
							},
							{
								column : 'type_name',
							},
						],
						
						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Name', value:'name'},
                				{ text: 'Nominal', value:'nominal'},
                				{ text: 'Type', value:'type_name'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],

						form_single : [['name'],['nominal'],['type']],
						single : 
						{
							'id' : {
								label : '',
							},
							'name' : {
								label : 'Name', width:12, type:'tf', validation:'max_req',
							},
							'nominal' : {
								label : 'Nominal', width:12, type:'tf', validation:'numeric_req',
							},
							'type' : {
								label : 'Type', width:12, type:'s', validation:'selectdata_req',
								itemText:'name', itemValue:'id', column:'type_id', table_ref:'types'
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},


				//5. crud-category_price_sellings
				"category_price_sellings" : 
				{
					table_name : 'category_price_sellings',
					title : 'Category Price Selling',
					icon : 'compare_arrows',

					singular_name : 'category_price_selling',
					plural_name : 'category_price_sellings',
					column_desc : 'name', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : false,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'name',
							},
							{
								column : 'discount',
							},
						],
						
						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Name', value:'name'},
                				{ text: 'Discount', value:'discount'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],

						form_single : [['name'], ['discount']],
						single : 
						{
							'id' : {
								label : '',
							},
							'name' : {
								label : 'Name', width:12, type:'tf', validation:'max_req',
							},
							'discount' : {
								label : 'Discount', width:12, type:'tf', validation:'numeric_req',
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},


				//6. crud-types
				"types" : 
				{
					table_name : 'types',
					title : 'Types',
					icon : 'bookmarks',

					singular_name : 'type',
					plural_name : 'types',
					column_desc : 'name', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : false,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'name',
							},
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

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},


				//7. crud-goods
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
					button_on_index : ['Add Data'],

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
						rule_update:'some',
						datatable:[
							{ column : 'name' }, { column : 'code' }, { column : 'value' }, { column : 'status' }, { column : 'last_buy_pricelist' }, { column : 'avg_price' }, { column : 'stock' }, { column : '', value : ['avg_price', '*', 'stock'] },
						],
						
						headers: [
			                { text: 'No', value: 'no'},
			                { text: 'Name', value: 'name'},
			                { text: 'Code', value: 'code'},
			                { text: 'Value', value: 'value'},
			                { text: 'Status', value: 'status'},
			                { text: 'Last Buy Pricelist', value: 'last_buy_pricelist'},
			                { text: 'AVGPrice', value: 'avgprice'},
			                { text: 'Stock', value: 'stock'},
			                { text: 'Inventory Value', value: 'inventory_value'},
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
								itemText:'name', itemValue:'value', column:'avg_price_status',table_ref:'avg_price_status'
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
										itemText:'name', itemValue:'value', column:'free', table_ref:'free', custom_table_ref:true
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
								send_type : '2'
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

						"pricelists": 
						{
							table_name : 'pricelists',
							title : 'Pricelists',
							single : 
							{
								'id' : {show : false},
								'supplier' : {show : true},
								'price' : {show : true},
							},
							headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Supplier', value:'supplier'},
                				{ text: 'Price', value:'price'},
                				
							]
						},



					}



				},


				//8. crud-warehouses
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
					button_on_index : ['Add Data'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {},
						rule_update:'some',
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
								label : 'Email', width:12, type:'tf', validation:'email_req',
							},
							'lat' : { 
								label : 'Latitude', width:12,  type:'tf_gm',
								disabled : true, gm : 'lat'
							},
							'lng' : { 
								label : 'Longitde', width:12,  type:'tf_gm',
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
								url : 'api/warehouses/create',
								parent : 
								{
									title : "Warehouse",
									table_name : "warehouses",
									itemText : 'name',
									itemValue : 'id'
								},
								child : 
								{
									title : "Racks",
									table_name : 'racks',
									column_show : 'name',
									flag_grandchild : 'is_have_goods',
									header : [{text:'Rack', value:'rack',width:'50%'},{text:'Action', value:'action',width:'50%'}],
								},
								grandchild : 
								{
									title : "Goods",
									table_name : 'goods',
									
								},
								editing : 
								{
									"racks":
									{
										title : 'Racks',
										type : 'table',

										singular_name : 'rack',
										plural_name : 'racks',

										datatable : [{column : ['name']}],
										headers: 
										[
											{ text: 'No', value:'no'},
			                				{ text: 'Name', value:'material'},
			                				{ text: 'Action', align:'left',sortable:false, width:'15%'},
										],

										form_single:[['name']],
										single : 
										{
											'id' : { 
												label : '', 
											},
											'name' : { 
												label : 'Name', width:12, type:'tf', column:'name'
											},
											
											
										},
										send_type : '2'
									}
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


					}



				},

				//9. crud-supplier
				"suppliers" : 
				{
					table_name : 'suppliers',
					title : 'Supplier',
					icon : 'perm_contact_calendar',

					singular_name : 'supplier',
					plural_name : 'suppliers',
					column_desc : 'name_company',

					widthForm : 'fullscreen',
					editable_edit : true,
					editable_add : true,
					count_step : 2,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {
						},
						rule_update:'some',
						datatable:[
							{ column : 'name_company' }, { column : 'name_pic' }, { column : 'address' },
						],
						
						headers: [
			                { text: 'No', value: 'no'},
			                { text: 'Company', value: 'name_company'},
			                { text: 'PIC', value: 'name_pic'},
			                { text: 'Address', value: 'address'},
			                { text: 'Action', align:'left',sortable:false, width:'15%'},
			            ],
						form_single : [['name_company'],['name_owner'], ['name_pic'],['name_sales'], ['address'], ['no_telp_company'], ['no_telp_owner'], ['email'], ['fax'], ['npwp'], ['no_rek']],
						single : 
						{
							'id' : { 
								label : '', 
							},
							'name_company' : { 
								label : 'Name Company', width:12, type:'tf', validation:'max_req',
							},
							'name_owner' : { 
								label : 'Name Owner', width:12, type:'tf', validation:'max_req',
							},
							'name_pic' : { 
								label : 'Name PIC', width:12, type:'tf', validation:'max_req',
							},
							'name_sales' : { 
								label : 'Name Sales', width:12, type:'tf', validation:'max_req',
							},
							'address' : { 
								label : 'Address', width:12, type:'tf', validation:'max_req',
							},
							'no_telp_company' : { 
								label : 'No Telp Company', width:12, type:'tf', validation:'max_req',
							},
							'no_telp_owner' : { 
								label : 'No Telp Owner', width:12,  type:'tf', validation:'max_req',
							},
							'email' : { 
								label : 'Email', width:12,  type:'tf', validation:'email_req',
							},
							'fax' : { 
								label : 'Fax', width:12,  type:'tf', validation:'max_req',
							},
							'npwp' : { 
								label : 'NPWP', width:12, type:'tf', validation:'max_req', 
							},
							'no_rek' : { 
								label : 'No Rekening', width:12, type:'tf', validation:'max_req',
							},
							
							
						},
						
						form_multiple : ['pricelists'],
						multiple : {
							"pricelists":
							{
								title : 'Pricelists',
								type : 'table',

								singular_name : 'pricelists',
								plural_name : 'pricelists',

								datatable : [{column : ['goods', 'name']}, {column : ['price']}],
								headers: 
								[
									{ text: 'No', value:'no'},
	                				{ text: 'Goods', value:'goods'},
	                				{ text: 'Price', value:'price'},
	                				{ text: 'Action', align:'left',sortable:false, width:'15%'},
								],
								form_single:[['goods'], ['price']],
								single : 
								{
									'id' : { 
										label : '', 
									},
									'goods' : { 
										label : 'Goods', width:12, type:'s2',
										itemText:'name', itemValue:'id', column:'goods_id', table_ref:'goods'

									},
									'price' : { 
										label : 'Price', width:12, type:'tf', column:'price'
									},
									
								},
								send_type : '1'
							},
							
						}
					},

					get_data_detail : 
					{
						
						

					}



				},

				//10. crud-customer
				"customers" : 
				{
					table_name : 'customers',
					title : 'Customer',
					icon : 'person_pin',

					singular_name : 'customer',
					plural_name : 'customers',
					column_desc : 'name', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : false,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'name',
							},
							{
								column : 'no_hp',
							},
							{
								column : 'address',
							},
						],

						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Name', value:'name'},
                				{ text: 'No HP', value:'no_hp'},
                				{ text: 'Address', value:'address'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],

						form_single : [['name'],['no_hp'],['address']],
						single : 
						{
							'id' : {
								label : '',
							},
							'name' : {
								label : 'Name', width:12, type:'tf', validation:'max_req',
							},
							'no_hp' : {
								label : 'No HP', width:12, type:'tf', validation:'max_req',
							},
							'address' : {
								label : 'Address', width:12, type:'tf', validation:'max_req',
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},

				//11. crud-goods_rack
				"goods_racks" : 
				{
					table_name : 'goods_racks',
					title : 'Goods Rack',
					icon : 'assignment_returned',

					singular_name : 'goods_rack',
					plural_name : 'goods_racks',
					custom_response_attribute : 'goods_rack',
					column_desc : 'stock', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'goods_name',
							},
							{
								column : 'rack_name',
							},
							{
								column : 'stock',
							},
						],
						
						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Goods', value:'goods_name'},
                				{ text: 'Rack', value:'rack_name'},
                				{ text: 'Stock', value:'stock'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],

						form_single : [['goods'],['rack'],['stock']],
						single : 
						{
							'id' : {
								label : '',
							},
							'goods' : {
								label : 'Goods', width:12, type:'s2', validation:'selectdata_req', 
								itemText:'name', itemValue:'id', column:'goods_id', table_ref:'goods'
							},
							'rack' : {
								label : 'Racks', width:12, type:'s', validation:'selectdata_req', 
								itemText:'name', itemValue:'id', column:'rack_id', table_ref:'racks'
							},
							'stock' : {
								label : 'Stock', width:12, type:'tf', validation:'numeric_req',
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},

				//12. crud-racks
				"racks" : 
				{
					table_name : 'racks',
					title : 'Rack',
					icon : 'dns',

					singular_name : 'rack',
					plural_name : 'racks',
					column_desc : 'name', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Edit', 'Goods', 'Delete'],
					button_on_index : ['Add Data'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {},
						rule_update:'send_all',
						datatable:[
							{
								column : 'name',
							},
							{
								column : 'warehouse_name',
							},
						],
						filter_by_user : {
							column : 'name',
							title : 'Filter Rack',
						},

						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Name', value:'name'},
                				{ text: 'Warehouse', value:'warehouse_name'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],
						

						form_single : [['name'],['warehouse']],
						single : 
						{
							'id' : {
								label : '',
							},
							'name' : {
								label : 'Name', width:12, type:'tf', validation:'max_req',
								
							},
							'warehouse' : {
								label : 'Warehouse', width:12, type:'s', validation:'selectdata_req',
								itemText:'name', itemValue:'id', column:'warehouse_id', table_ref:'warehouses'
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},

				//13. crud-purchase_orders
				"purchase_orders" : 
				{
					table_name : 'purchase_orders',
					title : 'Purchase Order',
					icon : 'store',

					singular_name : 'purchase_order',
					plural_name : 'purchase_orders',
					column_desc : 'total', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					actions:['Detail', 'Approve', 'Incoming', 'Payment', 'History', 'Retur'],
					button_on_index : ['Add Data'],

					request_master_data : true,
					data : 
					{
						custom_master_data : {
							'payment_type':[
			                    {value:1,name:'Tempo'},
			                    {value:2,name:'Tunai'},
			                ],
						},
						rule_update:'some',
						datatable:[
							{
								column : 'no_po',
							},
							{
								column : 'arrival_percent',
							},
							{
								column : 'total',
							},
							{
								column : 'payment_percent',
							},
							{
								column : 'created_at',
							},
							{
								column : 'supplier_name',
							},
							{
								column : 'status',
							},
							{
								column : 'tax',
							},
							{
								column : 'discount_percent',
							},
							{
								column : '',
								value:['total', '+', 'tax'],
							},
						],
						filter_by_user : {
							column_in_table : 'periode',
							response_attribute : 'periodes',
							itemText : "name",
							itemValue : 'id',
							title : 'Filter Periode',
						},

						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'NO PO', value:'no_po'},
                				{ text: '% Arrival', value:'is_completed'},
                				{ text: 'Total', value:'total'},
                				{ text: '% Payment', value:'percent_payment'},
                				{ text: 'Created At', value:'created_at'},
                				{ text: 'Supplier', value:'supplier_name'},
                				{ text: 'Status', value:'status'},
                				{ text: 'Tax', value:'tax'},
                				{ text: '% Discount', value:'discount'},
                				{ text: 'DPP', value:'dpp'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],

						

						form_single : [['supplier'],['periode'],['payment_type'],['payment_terms'],['no_po']],
						single : 
						{
							'id' : {
								label : '',
							},
							'supplier' : {
								label : 'Supplier', width:12, type:'s', validation:'selectdata_req',
								itemText:'company_name', itemValue:'id', column:'supplier_id', table_ref:'suppliers'
								
							},
							'periode' : {
								label : 'Periode', width:12, type:'s', validation:'selectdata_req',
								itemText:'name', itemValue:'id', column:'periode_id', table_ref:'periodes'
							},
							'payment_type' : {
								label : 'Payment Type', width:12, type:'s', validation:'selectdata_req',
								itemText:'name', itemValue:'value', column:'payment_type',table_ref:'payment_type'
							},
							'payment_terms' : {
								label : 'Payment Terms', width:12, type:'tf', validation:'numeric_req',
								vif:true,
							},
							'no_po' : {
								label : 'No PO', width:12, type:'tf', validation:'max_req',
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
						conditional_input:{
							"payment_terms" : ['payment_type','value','1'],
						}
					},
					get_data_detail : 
					{
						
					}
				},

				//14. crud-purchase_order_details
				"purchase_order_details" : 
				{
					table_name : 'purchase_order_details',
					title : 'Purchase Order Detail',
					icon : 'add_shopping_cart',

					singular_name : 'purchase_order_detail',
					plural_name : 'purchase_order_details',
					column_desc : 'qty', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					additional_param_index : 'purchase_order_id',

					actions:['Edit', 'Revision', 'Delete'],
					button_on_index : ['Add Data', 'Submit', 'Incoming', 'Print'],

					format_additional_data : 
					{
						'NO_PO' : 'no_po',
						'Type' : 'type',
						'Total' : 'total',
						'Created_By' : 'created_by_user_name',
						'Status' : 'status'
					},

					request_master_data : true,
					data : 
					{
						custom_master_data : {
							
						},
						rule_update:'some',
						datatable:[
							{
								column : 'goods_name',
							},
							{
								column : 'qty',
							},
							{
								column : 'discount_percent',
							},
							{
								column : 'discount_rupiah',
							},
							{
								column : 'subtotal',
							},
						],
						filter_by_user : {
							
						},

						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Goods', value:'goods_name'},
                				{ text: 'Quantity', value:'qty'},
                				{ text: 'Discount Percent', value:'discount_percent'},
                				{ text: 'Discount Rupiah', value:'discount_rupiah'},
                				{ text: 'Subtotal', value:'subtotal'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],


						form_single : [['goods'],['pricelist'],['qty'],['tax'],['discount_percent'],['discount_rupiah']],
						single : 
						{
							'id' : {
								label : '',
							},
							'goods' : {
								label : 'Goods', width:12, type:'s2', validation:'selectdata_req',
								itemText:'goods_name', itemValue:'id', column:'goods_id', table_ref:'goods'
								
							},
							'pricelist' : {
								label : 'Pricelists', width:12, type:'s', validation:'selectdata_req',
								itemText:'price', itemValue:'id', column:'pricelist_id', table_ref:'pricelists'
							},
							'qty' : {
								label : 'Quantity', width:12, type:'tf', validation:'numeric_req',
							},
							'tax' : {
								label : 'Tax', width:12, type:'tf', validation:'numeric_req',
							},
							'discount_percent' : {
								label : 'Discount Percent', width:12, type:'tf', validation:'numeric_req',
							},
							'discount_rupiah' : {
								label : 'Discount Rupiah', width:12, type:'tf', validation:'numeric_req',
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
						conditional_input:{
							"payment_terms" : ['payment_type','value','1'],
						}
					},
					get_data_detail : 
					{
						
					}
				},

				//15. crud-material_requests
				"material_requests" : 
				{
					table_name : 'material_requests',
					title : 'Material Request',
					icon : 'next_week',

					singular_name : 'material_request',
					plural_name : 'material_requests',
					custom_response_attribute : 'material_request',
					column_desc : 'code', //untuk fk

					// widthForm : '750',
					// editable_edit:true,
					// editable_add:true,
					// count_step:1,

					actions:['Detail', 'Approve'],
					button_on_index : ['Add Data', 'Mark Complete', 'List PR', 'Create PO'],
					button_for_checklist : ['Mark Complete'],

					request_master_data : false,
					data : 
					{
						
						rule_update:'some',
						datatable:[
							{
								column : 'code',
							},
							{
								column : 'division_name',
							},
							{
								column : 'created_at',
							},
							{
								column : 'status',
							},
							
						],
						filter_by_user : {
							column_in_table : 'periode',
							response_attribute : 'periodes',
							itemText : "name",
							itemValue : 'id',
							title : 'Filter Periode',
						},

						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Code', value:'code'},
                				{ text: 'Division', value:'division_name'},
                				{ text: 'Created At', value:'created_at'},
                				{ text: 'Status', value:'status'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],

						// form_single : [['supplier'],['periode'],['payment_type'],['payment_terms'],['no_po']],
						// single : 
						// {
						// 	'id' : {
						// 		label : '',
						// 	},
						// 	'supplier' : {
						// 		label : 'Supplier', width:12, type:'s', validation:'selectdata_req',
						// 		itemText:'company_name', itemValue:'id', column:'supplier_id', table_ref:'suppliers'
								
						// 	},
						// 	'periode' : {
						// 		label : 'Periode', width:12, type:'s', validation:'selectdata_req',
						// 		itemText:'name', itemValue:'id', column:'periode_id', table_ref:'periodes'
						// 	},
						// 	'payment_type' : {
						// 		label : 'Payment Type', width:12, type:'s', validation:'selectdata_req',
						// 		itemText:'name', itemValue:'value', column:'payment_type',table_ref:'payment_type'
						// 	},
						// 	'payment_terms' : {
						// 		label : 'Payment Terms', width:12, type:'tf', validation:'numeric_req',
						// 		vif:true,
						// 	},
						// 	'no_po' : {
						// 		label : 'No PO', width:12, type:'tf', validation:'max_req',
						// 	},

						// },
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
						// conditional_input:{
						// 	"payment_terms" : ['payment_type','value','1'],
						// }
					},
					get_data_detail : 
					{
						
					}
				},

				//16. crud-material_request_details
				"material_request_details" : 
				{
					table_name : 'material_request_details',
					title : 'Material Request Detail',
					icon : 'add_shopping_cart',

					singular_name : 'material_request_detail',
					plural_name : 'material_request_details',
					column_desc : 'notes', //untuk fk

					widthForm : '750',
					editable_edit:true,
					editable_add:true,
					count_step:1,

					additional_param_index : 'material_request_id',

					actions:['Edit', 'Delete'],
					button_on_index : ['Add Data'],

					format_additional_data : 
					{
						'Code' : 'code',
					},

					request_master_data : true,
					data : 
					{
						custom_master_data : {
							
						},
						rule_update:'some',
						datatable:[
							{
								column : 'goods_name',
							},
							{
								column : 'qty',
							},
						],
						filter_by_user : {
							
						},

						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Goods', value:'goods_name'},
                				{ text: 'Quantity', value:'qty'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],


						form_single : [['goods'],['qty']],
						single : 
						{
							'id' : {
								label : '',
							},
							'goods' : {
								label : 'Goods', width:12, type:'s2', validation:'selectdata_req',
								itemText:'goods_name', itemValue:'id', column:'goods_id', table_ref:'goods'
							},
							'qty' : {
								label : 'Quantity', width:12, type:'tf', validation:'numeric_req',
							},

						},
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},

				//17. crud-purchase_requests
				"purchase_requests" : 
				{
					table_name : 'purchase_requests',
					title : 'Purchase Request',
					icon : 'add_shopping_cart',

					singular_name : 'purchase_request',
					plural_name : 'purchase_requests',
					column_desc : 'code', //untuk fk

					


					actions:['Edit', 'Make PO', 'Delete'],
					button_on_index : ['Add Data'],

					

					
					data : 
					{
						custom_master_data : {
							
						},
						
						datatable:[
							{
								column : 'code',
							},
							{
								column : 'created_by_user_name',
							},
							{
								column : 'created_at',
							},
							{
								column : 'status_name',
							},
						],
						filter_by_user : {
							column_in_table : 'periode',
							response_attribute : 'periodes',
							itemText : "name",
							itemValue : 'id',
							title : 'Filter Periode',
						},

						headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Code', value:'code'},
                				{ text: 'Created By', value:'created_by_user_name'},
                				{ text: 'Created At', value:'created_at'},
                				{ text: 'Status', value:'status_name'},
                				{ text: 'Action', value:'action',sortable:false, width:'15%'},
						],


						
						
						custom_single:{},
						form_multiple : [],
						multiple:{},
						form_custom_component:[],
						custom_component:{},
					},
					get_data_detail : 
					{
						
					}
				},
			}
		}
	},
	methods:{
		generate_url(table,type,id,tableDetail)
		{
			
			var result = '';
			if(type == 'index' || type == 'store')
			{
				result = '/api/' + this.to_snack_case(table);
			}
			else if(type == 'edit')
			{
				result = '/api/' + this.to_snack_case(table) + '/' + id + '/edit';
			}
			else if(type == 'detail')
			{
				
				result = '/api/' + this.to_snack_case(table) + '/' + id + '/' + this.to_snack_case(tableDetail);
			}
			else if(type == 'update' || type == 'delete')
			{
				result = '/api/' + this.to_snack_case(table) + '/' + id;
			}
			else if(type == 'create')
			{
				result = '/api/' + this.to_snack_case(table) + '/create';
			}
			return result;
			
		},
		to_snack_case(str)
		{
			
			var result = str;
			for(var i = 0;i<result.length;i++)
			{
				if(result[i] == '_')
				{
					var temp_chr = result[i + 1];
					temp_chr = temp_chr.toUpperCase();
					result = result.substr(0,i) + temp_chr + result.substr(i + 2);
				}
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