<template>
    <v-app id="login" class="red">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4 lg4>
                        <v-card class="elevation-1 pa-3">
                            <v-card-text>
                                <div class="layout column align-center pb-4">
                                    <img src="/assets/images/logo.png" alt="Dilog" width="200">
                                </div>
                                <v-form>
                                    <v-text-field
                                        append-icon="person"
                                        name="login"
                                        label="Login"
                                        type="text"
                                        v-model="form.email.value"
                                        :error="error"
                                        :rules="form.email.rules"/>
                                    <v-text-field
                                        :type="form.password.hide ? 'password' : 'text'"
                                        :append-icon="form.password.hide ? 'visibility_off' : 'visibility'"
                                        name="password"
                                        label="Password"
                                        id="password"
                                        :rules="form.password.rules"
                                        v-model="form.password.value"
                                        :error="error"
                                        @click:append="form.password.hide = !form.password.hide"/>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn block large color="primary" @click="login" :loading="loading">Login</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-snackbar
                v-model="showResult"
                :timeout="2000"
                top>
                {{ result }}
            </v-snackbar>
        </v-content>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            error: false,
            showResult: false,
            result: '',
            form: {
                email: {
                    value: '',
                    rules: [
                        v => !!v || 'Email is required!',
                    ]
                },
                password: {
                    value: '',
                    rules: [
                        v => !!v || 'Password is required!',
                    ],
                    hide: true,
                }
            }
        }
    },

    methods: {
        login() {
            const vm = this;

            if (!vm.form.email.value || !vm.form.password.value) {

                vm.result = "Email and Password can't be null.";
                vm.showResult = true;

                return;
            }

            if (true) {
                router.push('/')
            }
            else {
                vm.error = true;
                vm.result = "Email or Password is incorrect.";
                vm.showResult = true;
            }
        }
    }
}
</script>

<style scoped>
#login {
    height: 50%;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    content: "";
    z-index: 0;
}
</style>
