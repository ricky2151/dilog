<template>
    <div>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Login form</v-toolbar-title>
                                
                            </v-toolbar>
                            <v-card-text>
                                <v-form>
                                	<input type="hidden" name="_token" :value="csrf">
                                    <v-text-field v-model="in_username" prepend-icon="person" name="login" label="Username" type="text"></v-text-field>
                                    <v-text-field v-model="in_password" prepend-icon="lock" name="password" label="Password" id="password" type="password"></v-text-field>
                                    <label>{{token}}</label>

                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" v-on:click='req_login()' >Login</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </div>
</template>

<script>
	import axios from 'axios'
    export default {
    	data()
    	{
    		return {
    			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
	            in_password:'',
	            in_username:'',
	            token:'',
        	}
    	},
    	methods:
    	{
    		req_login(){

    			axios.post('/api/auth/login',{
    				username:this.in_username,
    				password:this.in_password
    			}).then(r => saveToken(r));
    		},

    		saveToken(r){
    			localStorage.setItem('token',r.access_token)
    		}
    	}
    }
}
</script>