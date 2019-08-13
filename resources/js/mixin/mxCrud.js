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
    data () {
        return {
            selected_data : null,
        }
    },
	methods:{
        get_property_from_list_filter(obj)
        {
            var result = [];
            var temp_table = "";
            var temp_idparent = "";
            Object.keys(obj).map(function(key,index)
            {
                temp_table = key;
                temp_idparent = obj[key];
            })
            result["table"] = temp_table;
            result["idparent"] = temp_idparent;
            return result;
        },
        fill_filter_by_user_ref(arr)
        {
            this.filter_by_user_ref = arr;
        },
        fill_filter_by_user_value(val)
        {
            this.filter_by_user_value = val;
        },

        debugLog(item) {
            console.log('ini debugLog');
            console.log(item);
        },
        fill_additional_data(item)
        {
            this.additional_data = item;
            if (typeof me.after_fill_additional_data === "function") { 
                this.after_fill_additional_data();
            }
        },
        open_component(name_component, table_parent,id_selected)
        {
            if(table_parent)
            {
                this.list_state[name_component]['table_parent'] = table_parent;
                this.list_state[name_component]['id_selected'] = id_selected;
            }
            this.open_state = name_component;

            //setting breadcrumbs
            //disable semua breadcrumbs
            for(var i = 0;i<this.breadcrumbs.length;i++)
            {
                this.breadcrumbs[i].disabled = true;
            }
            for(var i = 0;i<this.breadcrumbs.length;i++)
            {
                if(this.breadcrumbs[i].cp == name_component)
                {
                    var temp_breadcrumb = this.breadcrumbs[i];
                    this.breadcrumbs[i].disabled = false;
                    while(temp_breadcrumb.before)
                    {
                        for(var j = 0;j<this.breadcrumbs.length;j++)
                        {
                            if(this.breadcrumbs[j].cp == temp_breadcrumb.before)
                            {
                                temp_breadcrumb = this.breadcrumbs[j];
                                this.breadcrumbs[j].disabled = false;
                                break;
                            }
                        }
                    }
                    break;
                }
            }

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

        filterObject(obj, keySelected)
        {
            var listremove = [];
            Object.keys(obj).map(function(key, index) {
                var removethiskey = true;
                for(var i = 0;i<keySelected.length;i++)
                {
                    if(keySelected == key)
                    {
                        removethiskey = false;
                        break;
                    }
                }

                if(removethiskey == true)
                {
                    listremove.push(key)
                }

            });

            for(var i = 0;i<listremove.length;i++)
            {
                delete obj[listremove];
            }
            return obj;
        },
        

        


        
	},
    created()
    {
        this.info_table = this.database[this.name_table];
    },
    mounted()
    {
        
        this.$refs['cpForm'].get_master_data();
    },
	mixins:[
		mxStringProcessing,
        mxDatabase,
        mxVariableProcess,
	]
}