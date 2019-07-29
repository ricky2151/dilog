import mxStringProcessing from '../mixin/mxStringProcessing'
import mxDatabase from '../mixin/mxDatabase'
import mxVariableProcess from '../mixin/mxVariableProcess'
import cpDetail from './../components/popup/cpDetail.vue'
import cpForm from './../components/form/cpForm.vue'
import cpDatatable from './../components/datatable/cpDatatable.vue'
import cpHeader from './../components/datatable/cpHeader.vue'

export default {
    components:{
        cpDetail,
        cpForm,
        cpDatatable,
        cpHeader
    },
	methods:{
        debugLog(item) {
            console.log('ini debugLog');
            console.log(item);
        },
		findDataById(id)
		{
			
			for(var i = 0;i<this.$refs['cpDatatable'].data_table.length;i++)
			{
				if(this.$refs['cpDatatable'].data_table[i].id == id)
				{
                    
                    return this.$refs['cpDatatable'].data_table[i];
                    
				}
			}
		},

        opendialog_createedit(id){
            this.$refs['cpForm'].url_edit = this.generate_url(this.info_table.table_name, 'edit', id);
            this.$refs['cpForm'].url_store = this.generate_url(this.info_table.table_name, 'store');
            this.$refs['cpForm'].url_update = this.generate_url(this.info_table.table_name, 'update', id);
            this.$refs['cpForm'].url_create = this.generate_url(this.info_table.table_name, 'create');
            this.$refs['cpForm'].open_dialog(id);
        },

        opendialog_detail(id,ref,last_url)
        {
           
            this.$refs[ref][0].url = this.generate_url(this.info_table.table_name, 'detail', id, last_url);
            

            //this.$refs['cpDetailRacks'].open_dialog();
            //this.debugLog(this.$refs[ref].prop_title);
            this.$refs[ref][0].open_dialog();
            
        },

		
        refresh_table() {

            this.$refs.cpDatatable.get_data();

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
                        axios.delete(this.generate_url(this.name_table,'delete',this.findDataById(id_data_delete).id),{
                            data:{
                                token: localStorage.getItem('token')    
                            }
                            
                        }).then((r)=>{
                            this.refresh_table();
                            swal("Good job!", "Data Deleted !", "success");
                            
                        });
                    }
            });
        },

        get_master_data()
        {
            if(this.info_table.request_master_data)
            {
                axios.get(this.generate_url(this.name_table, 'create'), {
                    params:{
                        token: localStorage.getItem('token')
                    }
                },{
                    headers: {
                        'Accept': 'application/json',
                        'Content-type': 'application/json'
                    }
                }).then(r => this.$refs['cpForm'].fill_select_master_data(r))
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

        


        
	},
	mixins:[
		mxStringProcessing,
        mxDatabase,
        mxVariableProcess,
	]
}