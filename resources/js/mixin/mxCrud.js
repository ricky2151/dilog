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
        filter_finance()
        {
            if(JSON.parse(localStorage.getItem('user')).division_id != 1)
            {
                this.$router.replace('/');
                swal("You Cannot Open This Page", "Please Login with finance division to see this page", "error");
            }
        },
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
            // console.log('sampe fill filter by user ref');
            // console.log(arr);
            this.filter_by_user_ref = arr;
        },
        fill_filter_by_user_value(val)
        {
            this.filter_by_user_value = val;
        },

        debugLog(item) {
        },
        fill_additional_data(item)
        {
            this.additional_data = item;
            if (typeof this.after_fill_additional_data === "function") { 
                this.after_fill_additional_data();
            }
        },
        open_component(name_component, table_parent,id_selected, text_note)
        {
            if(table_parent)
            {
                this.list_state[name_component]['table_parent'] = table_parent;
                this.list_state[name_component]['id_selected'] = id_selected;
            }
            
            this.open_state = name_component;

            var idx_selected = -1;

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
                    idx_selected = i;
                    var temp_breadcrumb = this.breadcrumbs[i];
                    this.breadcrumbs[i].disabled = false;

                    //atur text_note
                    if(text_note)
                    {
                        if(text_note == '[use_same_text_note_level]')
                        {
                            //isi text_note dengan text_note yang selevel
                            var temp_before = temp_breadcrumb.before;
                            var text_note_this_level = '';
                            for(var j = 0;j<this.breadcrumbs.length;j++)
                            {
                                if(this.breadcrumbs[j].before == temp_before && this.breadcrumbs[j].text_note)
                                {
                                    text_note_this_level = this.breadcrumbs[j].text_note;
                                    break;

                                }
                            }
                            this.breadcrumbs[i].text_note = text_note_this_level;
                        }  
                        else
                        {
                            this.breadcrumbs[i].text_note = text_note;
                        }
//                        this.breadcrumbs[i].disabled = true;
                    }
                    


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
        if(this.info_table.additional_param_index)
        {
            if(this.$refs['cpForm'])
                this.$refs['cpForm'].get_master_data(this.prop_list_filter.id_selected,this.info_table.additional_param_index);
        }
        else
        {
            
            if(this.info_table.request_master_data)
            {
                if(this.$refs['cpForm'])
                    this.$refs['cpForm'].get_master_data();    

            }
        }

        
    },
    computed : 
    {
        computed_breadcrumbs: function () {
          var result_breadcrumbs = JSON.parse(JSON.stringify(this.breadcrumbs));
          var i = 0;

          //hapus item breadcrumb yang gak kepake
          while(i<result_breadcrumbs.length)
          {
            if(result_breadcrumbs[i].disabled)
            {
                result_breadcrumbs.splice(i,1);
                i--;
            }

            i++;

          }

          //beri note jika ada note yang diperlukan
          i = 0;
          while(i<result_breadcrumbs.length)
          {
            if(result_breadcrumbs[i].text_note)
            {
                result_breadcrumbs[i].disabled=  true;
                result_breadcrumbs.splice(i,0, {
                    text : result_breadcrumbs[i].text_note,
                    disabled : true,
                });
                i++;
            }

            i++;

          }
          if(result_breadcrumbs.length == 1)
          {
            return [];
          }
          else
          {
              return result_breadcrumbs;
          }
        }
    },
	mixins:[
		mxStringProcessing,
        mxDatabase,
        mxVariableProcess,
	]
}