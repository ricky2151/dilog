import mxCrud from '../mixin/mxCrud'
export default {
	methods:{
        

		// opendialog_createedit(id_data_edit,r){ //tdk bisa, nanti dihapus karena udah ada di component
	 //        if(id_data_edit != -1)
	 //        {
	 //            this.id_data_edit = id_data_edit;
	            
	 //            this.convert_data_input(r);
	            
	 //        }
	 //        else
	 //        {
	 //        	this.clear_input();
	 //        }
	 //        this.dialog_createedit = true;
  //       },


        
        

        get_data_before_edit(id_edit) //nanti dihapus karena sudah ada di component
        {
            
            axios.get('/api/' + this.name_table +'/' + id_edit + '/edit', {
                params:{
                    token: localStorage.getItem('token')
                }
            },{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json'
                }
            }).then(r=> {

                this.opendialog_createedit(id_edit,r);
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

        
        
	},
	mounted(){
		//this.get_data();
	},
    mixins:[
        mxCrud
    ],
}