<template>
    <div class="content">
			<div class="container">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                    <div class = "space">
                        <div class = "login_header">
                            <h3>Log In to Dashboard</h3>
                        </div>
                    </div>
                        <br>
                        <br>
                        <div class="space">
                            <label for="email">Email</label>
                            <br>
                            <input class="form-control" type="email" v-model = "data.email" placeholder = "Email">
                        </div>

                        <br>
                        <br>
                        <div class="space">
                            <label for="password">Password</label>
                            <br>
                            <input class="form-control" type="password" v-model = "data.password" placeholder = "password">
                        </div>

                        <br>
                        <br>
                        <div class="space">
                            <div class="login_footer">
                                <button type = "submit" class = 'btn btn-info' @click = "login" :disabled = "isLoggedin" :loading = "isLoggedin">{{ isLoggedin ? 'connecting' : 'Log In' }}</button>
                            </div>
                        </div>
                </div>
			</div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            data : {
                email: '',
                password: '',
            },

            isLoggedin: false,
        }
    },

    methods : {
        login(){
                // if(this.data.email.trim() == ''){
                //     return this.error('email is required');
                // }

                // if(this.data.password.trim() == ''){
                //     return this.error('password is required');
                // }
                // if(this.data.password.length < 6){
                //     return this.error('password minimum length must be 6 chars');
                // }
                var self = this;
                this.isLoggedin = true;
                axios.post('app/admin/login', {
                    email: this.data.email,
                    password: this.data.password,
                })
                .then(function (response) {
                    // console.log(response);
                    if(response.data.status == 0){
                        self.error('User can not log in from here');
                    }
                    window.location = '/'
                })
                .catch(function (error) {
                    for(let i in error.response.data.errors){
                        self.error(error.response.data.errors[i]);
                    }
                });
                this.isLoggedin = true;

        }
    }



}
</script>
<style scoped>
    ._1adminOverveiw_table_recent{
        margin-left: 20%;
    }
    .login_footer{
        margin-top: 15px;
    }

    /* .login_header{

    } */

</style>
