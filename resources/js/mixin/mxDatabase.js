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


	data () {
		return{
			database : 
			{
				"categories" : 
				{
					table_name : 'categories',
					title : 'Category',
					singular_name : 'category',
					plural_name : 'categories',
					widthForm : '750',
					data : 
					{
						
						form_single : [['name']],
						single : 
						{
							'id' : {
								label : '',
								header : '',
							},
							'name' : {
								label : 'Name', width:12, type:'tf', validation:'max_req',
								header : 'Name',
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
					singular_name : 'goods',
					plural_name : 'goods',
					widthForm : 'fullscreen',
					data : 
					{
						form_single : [['name', 'code'], ['desc'],['status'], ['value', 'last_buy_pricelist'], ['barcode_master'], ['thumbnail'], ['avg_price_status'], ['avg_price', 'tax'], ['unit_id'], ['cogs_id'], ['margin']],
						single : 
						{
							'id' : { 
								label : '', 
								header : ''
							},
							'name' : { 
								label : 'Name', width:9, type:'tf', validation:'max_req',
								header : 'Name'
							},
							'code' : { 
								label : 'Code', width:3, type:'tf', validation:'max_req',
								header : 'Code'
							},
							'desc' : { 
								label : 'Description', width:12, type:'ta', validation:'max',
								header : 'Description'
							},
							'margin' : { 
								label : 'Margin', width:12, type:'tf', validation:'numeric_req',
								header : 'Margin'
							},
							'value' : { 
								label : 'Value', width:6, type:'tf', validation:'numeric_req',
								header : 'Value'
							},
							'status' : { 
								label : 'Status', width:6,  type:'tf', validation:'numeric_req',
								header : 'Status'
							},
							'last_buy_pricelist' : { 
								label : 'Last Buy Pricelist', width:12,  type:'tf', validation:'numeric',
								header : 'Last Buy Pricelist'
							},
							'barcode_master' : { 
								label : 'Barcode', width:12,  type:'tf', validation:'max',
								header : 'Barcode'
							},
							'avg_price_status' : { 
								label : 'Average Price Status', width:12, type:'s', validation:'selecttf_req', 
								itemText:'name', itemValue:'value',
								header : 'AVG Price Status'
							},
							'avg_price' : { 
								label : 'Average Price', width:6, type:'tf', validation:'numeric',
								header : 'Average Price'
							},
							'tax' : { 
								label : 'Tax', width:6, type:'tf', validation:'numeric',
								header : 'Tax'
							},
							'unit_id' : { 
								label : 'Unit', width:12, type:'s', validation:'selectdata_req', 
								itemText:'name', itemValue:'id',
								header : 'Unit'
							},
							'cogs_id' : { 
								label : 'COGS', width:12, type:'s', validation:'selectdata_req', 
								itemText:'name', itemValue:'id',
								header : 'COGS'
							},
							'thumbnail' : { 
								label : 'Thumbnail', width:12,  type:'image', validation:'',
								fileNameVariable : 'thumbnail_filename', fileVariable : 'thumbnail_file', previewVariable : 'thumbnail', idButton : 'btn_upload_thumbnail',
								header : 'Thumbnail'
							},
							
						},
						
						multiple : {
							"category_goods":
							{
								table_name : 'category_goods',
								title : 'Gooods Category',
								headers: 
								[
									{ text: 'No'},
	                				{ text: 'Category'},
								],
								label : ['Name'],
								read : ['name'],
								single : ['id', 'name'],
							},
							"attribute_goods":
							{
								table_name : 'attribute_goods',
								single : ['id', 'name', 'value'],
							},
							"price_sellings":
							{
								table_name : 'price_sellings',
								single : ['id', 'goods_id', 'warehouse_id', 'stock_cut_off', 'category_price_selling_id', 'price', 'free', 'warehouse_name', 'category_price_selling_name'],
							},
							"pricelists":
							{
								table_name : 'pricelists',
								single : ['id', 'supplier_id', 'goods_id', 'price', 'name_company']
							},
							"material_goods":
							{
								table_name : 'material_goods',
								single : ['id', 'name', 'goods_id', 'total', 'adjust']
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
						"pricelists": //belum jadi
						{
							table_name : 'pricelists',
							title : 'Pricelists',
							single : ['id', 'supplier_id', 'goods_id', 'price'],
							read : [''],
							headers: [
								{ text: 'No', value:'no'},
                				{ text: 'Supplier'},
                				{ text: 'Goods'},
                				{ text: 'Price'},
							]
						},

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
			
		}
		
	}	
}