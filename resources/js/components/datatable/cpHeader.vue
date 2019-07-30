<template>
	<div>
		<v-layout row class='bgwhite margintop10'>
            <v-flex xs6>
                <div class='marginleft30 margintop10'>
                    <v-icon class='icontitledatatable'>{{prop_icon}}</v-icon>
                    <h2 class='titledatatable'>{{prop_title}} Data</h2>
                    <v-btn v-on:click='add_clicked(-1)' color="primary" dark class='btnadddata'>
                    Add Data
                </v-btn>
                </div>
                
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
    </div>
</template>
<script>
	export default {
		props : ['prop_icon', 'prop_title', 'prop_search_data'],
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
			add_clicked()
			{
				this.$emit('add_clicked');
			}
		}
	}
</script>