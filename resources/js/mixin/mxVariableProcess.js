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
		},
		check_property(obj, property_array)
        {
            //misal : 
            //obj = a : {b : {c}}
            //property_array = b,c,d

            //macam-macam nilai result : 
            //0 -> undefine
            //1 -> define tapi kosong
            //2 -> define tapi ada nilainya
            if(obj)
            {
	            var result = -1;
	            var temp = JSON.parse(JSON.stringify(obj));
	            var notundefined = true;
	            for(var i = 0;i<property_array.length;i++)
	            {
	                if(temp.hasOwnProperty(property_array[i]))
	                {
	                    temp = temp[property_array[i]];
	                }
	                else
	                {
	                    notundefined = false;
	                    break;
	                }
	            }
	            if(notundefined)
	            {
	                if(temp.length > 0)
	                {
	                    result = 2;
	                }
	                else
	                {
	                    result = 1;
	                }
	            }
	            else
	            {
	                result = 0;
	            }

            }
            else
            {
            	result = 0;
            }
            return result;

        },

	}
}