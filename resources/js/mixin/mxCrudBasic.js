import mxCrud from '../mixin/mxCrud'
export default {
	methods:{
        

        opendialog_createedit(id){
            this.$refs['cpForm'].url_edit = this.generate_url(this.info_table.table_name, 'edit', id);
            this.$refs['cpForm'].url_store = this.generate_url(this.info_table.table_name, 'store');
            this.$refs['cpForm'].url_update = this.generate_url(this.info_table.table_name, 'update', id);
            this.$refs['cpForm'].url_create = this.generate_url(this.info_table.table_name, 'create');
            this.$refs['cpForm'].open_dialog(id);
        },


        
        
	},
	mounted(){
		//this.get_data();
	},
    mixins:[
        mxCrud
    ],
}