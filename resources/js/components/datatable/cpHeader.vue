<template>

	



	<div style='padding-right: 10px'>
		<v-layout row class='bgwhite' style='margin-bottom: 20px'>
            <v-flex xs8>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>{{prop_icon}}</v-icon>
                    <h2 class='titledatatable'>{{prop_title}} Data</h2>
                    <v-btn v-if='prop_button_on_index.length == 1' v-for='(button_name, index) in prop_button_on_index' :key='index' v-on:click='$emit("button_index_clicked", index)' color="primary" dark >
		                {{button_name}}
		            </v-btn>
                </div>
                
            </v-flex>
            <v-flex xs12 class="text-xs-right" v-if='!prop_filter_by_user_format'>
                <v-text-field
                    class='d-inline-block searchdatatable'
                    v-model='search_data'
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>

            </v-flex>
        </v-layout>

        <!-- additional information/data -->
        <template v-if='prop_information && prop_format_information' class='margintop10'>
        	<template v-for='(format, key) in prop_format_information'>
        		<v-layout row>
		        	<v-flex xs1 class='marginleft30'>
		        		<b>{{replace_underscore_with_space(key)}} :</b>
		        	</v-flex>
		        	<v-flex xs7>
		        		<b>{{ prop_information[format] }}</b>
		        	</v-flex>
		        </v-layout>
        	</template>
        </template>

        <v-layout row>
        	<v-flex xs8 class='marginleft30'>

        		<v-select
				v-if='prop_filter_by_user_format && prop_filter_by_user_ref'
				:label='prop_filter_by_user_format.title'
				@change='filter_by_user_change'
                v-model='selected_filter'
				:items='prop_filter_by_user_ref'
				:item-text='prop_filter_by_user_format.itemText'
				:item-value='prop_filter_by_user_format.itemValue'
				

				>
				</v-select>
        	</v-flex>
            <v-flex xs12 class="text-xs-right" v-if='prop_filter_by_user_format'>
                <v-text-field
                    class='d-inline-block searchdatatable'
                    v-model='search_data'
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>

            </v-flex>
        </v-layout>
        <v-layout justify-end>
        	<v-btn  v-if='prop_button_on_index.length > 1 && !check_listing' v-for='(button_name, index) in prop_button_on_index' :key='index' v-on:click='$emit("button_index_clicked", index)' color="primary" dark >
        		<template v-if='(!check_listing || (check_listing && button_name == prop_button_for_checklist))'>
                	{{button_name}}
            	</template>

            </v-btn>
            <v-btn  v-if='check_listing' v-on:click='$emit("submit_checklist")' color="primary" dark >
                	Submit Checklist
            </v-btn>
            <v-btn  v-if='check_listing' v-on:click='$emit("cancel_checklist")' color="primary" dark >
                	Cancel
            </v-btn>
        </v-layout>
    </div>
</template>
<script>
	export default {
		props : ['prop_icon', 'prop_title', 'prop_type_header', 'prop_search_data', 'prop_button_on_index','prop_information', 'prop_format_information', 'prop_filter_by_user_format', 'prop_filter_by_user_ref', 'prop_button_for_checklist'],
		data () {
			return {
				search_data :null,
				check_listing : false,
                selected_filter : null, //cuma buat setting default value
			}
		},
		watch:{
			search_data(val)
			{
				this.$emit('search_change', val);
			}
		},
		methods:
		{
			set_check_listing(val)
			{
				this.check_listing = val;
			},
			filter_by_user_change(val)
			{
				this.$emit('filter_by_user_change', val);
			},
			replace_underscore_with_space(str)
			{
				return str.replace(/_/g, ' ');
			}
		}
	}
</script>