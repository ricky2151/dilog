<template>
    <div>
        
    

    
        

            

            

            <!-- POPUP CREATE EDIT -->

            <cp-form 

            :prop_countStep='info_table.count_step' 
            :prop_editableEdit='info_table.editable_edit'
            :prop_editableAdd='info_table.editable_add'
            :prop_title='info_table.title'
            :prop_dataInfo='info_table.data'
            :prop_tableName='name_table'
            :prop_widthForm='info_table.widthForm'
            :prop_singularName='info_table.singular_name'
            
            :prop_input='generate_input(info_table.plural_name)'
            prop_send_parent_table_key='purchase_order_id'
            :prop_send_parent_table_value='prop_list_filter["id_selected"]'
            
            :prop_custom_formData='info_table.data.custom_formData'
            

            v-on:done='refresh_table()'
            ref="cpForm"

            ></cp-form>


            <!-- ================================ -->

            


            <!-- HEADER DATATABLE -->

           <cp-header
           :prop_icon='info_table.icon'
           :prop_title='info_table.title'
           :prop_search_data='search_data'
           :prop_information='additional_data ? additional_data : ""'
            :prop_format_information='info_table.format_additional_data ? info_table.format_additional_data : ""'
           :prop_button_on_index='info_table.button_on_index'
           :prop_function_format_information='info_table.function_format_additional_data'

           v-on:button_index_clicked='button_index_clicked'
           v-on:search_change='search_data=$event'
           
           v-on:add_clicked='opendialog_createedit(-1)'
           >
           </cp-header>

           <!-- ================================ -->


            
            <!-- DATATABLE -->

            <cp-datatable 
            v-if='info_table.data'

            :prop_header='info_table.data.headers'
            :prop_search_data='search_data'
            :prop_infoDatatable='info_table.data.datatable'
            :prop_action_items='info_table.actions'
            :prop_plural_name='info_table.plural_name'
            :prop_url_index='prop_list_filter? generate_url("purchase_orders", "detail",prop_list_filter["id_selected"], info_table.plural_name) :  generate_url(info_table.table_name, "index")'
            :prop_filter='prop_list_filter'
            prop_get_additional_data='true'
            prop_trigger_after_refresh_data='true'
            :prop_conditional_action_button='info_table.conditional_action_button'
            :prop_conditional_action='info_table.conditional_action'


            
            v-on:data_refreshed='after_data_refreshed'
            v-on:show_additional_data='fill_additional_data'            
            v-on:action_clicked='action_change'
            ref="cpDatatable"

            ></cp-datatable>

        

        

        <!-- ================================ -->
    </div>
    
</template>

<script>
import mxCrud from '../mixin/mxCrud';


export default {
    props: ['prop_list_filter', 'prop_format_additional_data', 'prop_additional_data'],
    data () {
        return {
            info_table:{},
            name_table:'payments',
            search_data: null,
            additional_data:null,

            
        }
    },
    methods: {
        after_data_refreshed()
        {

            //hitung semua total payment yang di approve
            //lalu jika total payment = total yang harus dibayarkan, maka disable tombol add
            var temp_data_table = JSON.parse(JSON.stringify(this.$refs['cpDatatable'].data_table));
            var total_payment_approved = 0;
            for(var i = 0;i<temp_data_table.length;i++)
            {
                if(temp_data_table[i].status == 1)
                {
                    total_payment_approved += temp_data_table[i].paid_off;
                }
            }
            if(total_payment_approved >= this.additional_data.total)
            {
                this.info_table.button_on_index = [];
            }
        },
        after_fill_additional_data()
        {
            
            //hitung semua total payment yang di approve
            //lalu jika total payment = total yang harus dibayarkan, maka disable tombol add
            var temp_data_table = JSON.parse(JSON.stringify(this.$refs['cpDatatable'].data_table));
            var total_payment_approved = 0;
            for(var i = 0;i<temp_data_table.length;i++)
            {
                if(temp_data_table[i].status == 1)
                {
                    total_payment_approved += temp_data_table[i].paid_off;
                }
            }
            if(total_payment_approved >= this.additional_data.total)
            {
                this.info_table.button_on_index = [];
            }
           
        },
        done_submit_incoming()
        {
            swal("Good job!", "Data Saved !", "success");
        },
        button_index_clicked(index)
        {
            if(index == 0)
            {
                this.opendialog_createedit(-1);
            }
        },
        approve_payment(id,data)
        {
            //api/payments/7/approve
            //cek apakah payment tidak melebihi dari kekurangan yang harus dibaya
            if(data.paid_off <= this.$refs['cpDatatable'].additional_data.not_paid_yet)
            {
                const formData = new FormData();
                formData.append('token', localStorage.getItem('token'));
                formData.append('_method', 'patch');
                axios.post('/api/payments/' + id + '/approve', formData, {
                        'Accept': 'application/json',
                        'Content-type': 'application/json' //default
                    })
                .then((r) => {
                    swal("Good job!", "Data saved !", "success");
                    this.refresh_table();
                });

            }
            else
            {
                swal('Wrong Payment !', 'Your payment bigger then no yet paid !', 'error');
            }
        },
        action_change(id,idx_action, data)
        {
            this.selected_data = data;
            if(idx_action == 0)
            {
                 this.opendialog_createedit(id);
            }
            else if(idx_action == 1)
            {
                 this.approve_payment(id,data);
            }
            else if(idx_action == 2)
            {
                //delete
                this.delete_data(id);
            }
            
        },
        
        

    },
    mounted(){      
        this.info_table = this.database[this.name_table];
    },
    mixins:[
        mxCrud
    ],
}
</script>

