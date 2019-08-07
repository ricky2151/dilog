<template>
	<div>
		<v-layout row class='bgwhite'>
            <v-flex xs8>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>{{prop_icon}}</v-icon>
                    <h2 class='titledatatable'>{{prop_title}} Data</h2>
                    
                </div>
                
            </v-flex>
        </v-layout>

        <!-- additional information/data -->
        <v-layout row v-if='prop_information && prop_format_information' class='margintop10'>
        	<template v-for='(format, key) in prop_format_information'>
	        	<v-flex xs1 class='marginleft30'>
	        		<b>{{key}} :</b>
	        	</v-flex>
	        	<v-flex xs7>
	        		<b>{{ prop_information[format] }}</b>
	        	</v-flex>
        	</template>
        </v-layout>

        <v-layout row>
        	<v-flex xs8 class='marginleft30'>
        		<v-select
				v-if='prop_filter_by_user_format && prop_filter_by_user_ref'
				:label='prop_filter_by_user_format.title'
				@change='filter_by_user_change'

				:items='prop_filter_by_user_ref'
				

				>
				</v-select>
        	</v-flex>
            <v-flex xs12 class="text-xs-right">
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
        	
        	<v-btn v-for='(button_name, index) in prop_button_on_index' :key='index' v-on:click='$emit("button_index_clicked", index)' color="primary" dark >
                {{button_name}}
            </v-btn>
        	
            
        </v-layout>
    </div>
</template>
<script>
	export default {
		props : ['prop_icon', 'prop_title', 'prop_search_data', 'prop_button_on_index','prop_information', 'prop_format_information', 'prop_filter_by_user_format', 'prop_filter_by_user_ref'],
		data () {
			return {
				search_data :null,
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
			filter_by_user_change(val)
			{
				this.$emit('filter_by_user_change', val);
			}
		}
	}
</script>