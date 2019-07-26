import mxStringProcessing from '../mixin/mxStringProcessing'
import mxDatabase from '../mixin/mxDatabase'
import mxVariableProcess from '../mixin/mxVariableProcess'
import cpDetail from './../components/popup/cpDetail.vue'
import cpForm from './../components/form/cpForm.vue'

export default {
    components:{
        cpDetail,
        cpForm,
    },
	methods:{
        debugLog(item) {
            console.log('ini debugLog');
            console.log(item);
        },
		findDataById(id)
		{
			
			for(var i = 0;i<this.data_table.length;i++)
			{
				if(this.data_table[i].id == id)
				{
                    
                    return this.data_table[i];
                    
				}
			}
		},

        opendialog_detail(id,ref,last_url)
        {
            //this.debugLog(this.$refs);
            this.$refs[ref][0].url = 'api/' + this.info_table.table_name +'/' + id + '/' + last_url;
            

            //this.$refs['cpDetailRacks'].open_dialog();
            //this.debugLog(this.$refs[ref].prop_title);
            this.$refs[ref][0].open_dialog();
            
        },

		closedialog_createedit(){  //nanti dihapus, karena sudah ada di compoenent form
            this.id_data_edit = -1;
            this.dialog_createedit = false;
        },

        clear_input(){
            this.$refs.formCreateEdit.resetValidation();
            
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
        },

        save_data() 
        {
            

            if(this.id_data_edit != -1) //jika sedang diedit
            {
                
                axios.post(
                	'api/' + this.name_table + '/' + this.findDataById(this.id_data_edit).id,
                	this.prepare_data_form(),
                	this.header_api).then((r) => {
                	this.clear_input();
                    this.get_data();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                    this.id_data_edit = -1;
                    
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
                	'api/' + this.name_table,
                	this.prepare_data_form(),
                	this.header_api).then((r)=> {
                    this.clear_input();
                    this.get_data();
                    this.closedialog_createedit();
                    swal("Good job!", "Data saved !", "success");
                });
            }
        },

        get_data() {

            axios.get('/api/' + this.name_table, {
                params:{
                    token: localStorage.getItem('token')
                }
            },this.header_api).then((r) => {

            	
            	this.showTable(r);
            	for(var i = 0;i<this.data_table.length;i++)
            	{
            		this.data_table[i].no = this.data_table.length - i;
            	}
            })
        },

        delete_data(id_data_delete){
            
            swal({
                    title: "Are you sure want to delete this item?",
                    text: "Once deleted, it can't be undone",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('api/' + this.name_table + '/' + this.findDataById(id_data_delete).id,{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.get_data();
                            swal("Good job!", "Data Deleted !", "success");
                            
                        });
                    }
            });
        },

        get_master_data()
        {
            axios.get('/api/' + this.name_table +'/create', {
                params:{
                    token: localStorage.getItem('token')
                }
            },{
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
        },

        
        get_popup_detail(id_edit, path)
        {
        	
        	axios.get('api/' + this.name_table + '/' + this.findDataById(id_edit).id + '/' + path,{
                params : {
                    token: localStorage.getItem('token')
                }

            }).then((r) => {
                return r.data.items;
            })
        },


        
	},
	mixins:[
		mxStringProcessing,
        mxDatabase,
        mxVariableProcess,
	]
}