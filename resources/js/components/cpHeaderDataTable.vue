<template>
	<div>
		<v-layout row class='bgwhite margintop10'>
	        <v-flex xs3>
	            <div class='marginleft30 margintop10'>
	                <v-icon class='icontitledatatable'>{{icon_header}}</v-icon>
	                <h2 class='titledatatable' v-html='title_header + " Data"'></h2>
	            </div>
	        </v-flex>
	        <v-flex xs3>
	            <v-btn v-on:click='opendialog_createedit(-1)' color="primary" dark class='btnadddata'>
	                Add Data
	            </v-btn>
	        </v-flex>
	        <v-flex xs12 class="text-xs-right">
	            <v-text-field
	                class='d-inline-block searchdatatable'
	                v-model="search_data_com"
	                append-icon="search"
	                label="Search"
	                single-line
	                hide-details
	            ></v-text-field>
	        </v-flex>
	    </v-layout>
	</div>
	
</template>
<style>

</style>
<script>
export default{
	name:'cpHeaderDataTable',
	props:['prop_icon_header', 'prop_title_header', 'prop_search_data_com'],
	data(){
		return {
			icon_header : this.prop_icon_header,
			title_header : this.prop_title_header,
			search_data_com : this.prop_search_data_com,
		}
	},
	computed: {


	}
}
</script>