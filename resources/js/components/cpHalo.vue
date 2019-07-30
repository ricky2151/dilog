

<template>
    <div>
    	{{pesan}}
    	{{umur}}
    </div>
</template>
<script>
	
	export default {
		name:'cpHalo',
		
		props:['pesan'],
		data () {
			return {
				umur:12,
			}
		},
		methods:
		{

		},
	}
</script>