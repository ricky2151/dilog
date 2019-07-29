<div>
    <v-container fluid>
        <h3>goods</h3>
    </v-container>
</div>

<template>
    <div>
    	<cp-halo ref='cpHalo' :pesan='pesan_orangtua'></cp-halo>
    	<button @click='ubah_pesan'>ubah</button>

    </div>
</template>

<script>
import cpHalo from './../components/cpHalo.vue'

export default {
	components:{
		cpHalo,
	},
	data () {
		return {
			pesan_orangtua:'jangan pergi malam2',
		}
	},
	methods:{
		ubah_pesan()
		{
			this.$refs.cpHalo.umur = 100;
			this.pesan_orangtua = 'Jangan sering marah';
		}
	}
}
</script>