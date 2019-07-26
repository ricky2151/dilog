export default{
	methods:{
		notNullObject(obj)
		{
			
			return obj && obj !== 'null' && obj !== 'undefined';
		},
		removeSpace(str)
		{
			str = str.replace(/\s/g, '');
			return str;
		}

	}
}