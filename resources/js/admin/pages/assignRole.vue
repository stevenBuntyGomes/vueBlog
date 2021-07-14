<template>
    <div>
        <div class="content">
			<div class="container">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management
                         <Select v-model="data.role_id" style="max-width:300px" placeholder = "select admin type" @on-change="changeRole">
                                    <Option v-for='(singleRole, i) in role' :key='i' :value="singleRole.id">{{  singleRole.roleName  }}</Option>


                                <!-- <Option value="Editor">Editor</Option> -->
                            </Select>
                         </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>Resource Name</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
								<th>Delete</th>
								<th>Name</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(r, i) in assignedRoles" :key="i">
                                <td>{{ r.resourceName }}</td>
                                <td><Checkbox v-model="r.read"></Checkbox></td>
                                <td><Checkbox v-model="r.write"></Checkbox></td>
                                <td><Checkbox v-model="r.update"></Checkbox></td>
                                <td><Checkbox v-model="r.delete"></Checkbox></td>
                                <td>{{ r.name }}</td>
							</tr>
								<!-- ITEMS -->
								<!-- ITEMS -->
                                <br>
                                <br>
                                <br>
                            <div class = "text-cnter">
                                <button type = "button" class = "btn btn-primary" :loading = "isSending" :disabled = "isSending" @click = "assignRoles">Assign</button>
                            </div>
						</table>
					</div>
                    <!-- tag adding model -->

                    <!-- tag adding model -->
                    <!-- category edit Modal -->

                    <!-- tag delete modal -->
				</div>
				 <!-- <Page :total="100" /> -->

			</div>
		</div>
    </div>
</template>
<script>
    const axios = require('axios').default;

    export default {

        data(){
            return {
                data: {
                    roleName: '',
                    role_id: null,

                },
                isSending: false,

                role: [],
                assignedRoles: [
                        {resourceName: 'home', read: false, write: false, update: false, delete: false, name: '/'},
                        {resourceName: 'tags', read: true, write: false, update: false, delete: false, name: 'tags'},
                        {resourceName: 'category', read: false, write: false, update: false, delete: false, name: 'category'},
                        {resourceName: 'Create Blogs', read: false, write: false, update: false, delete: false, name: 'create_blogs'},
                        {resourceName: 'adminusers', read: false, write: false, update: false, delete: false, name: 'adminusers'},
                        {resourceName: 'role', read: false, write: false, update: false, delete: false, name: 'role'},
                        {resourceName: 'assignRole', read: false, write: false, update: false, delete: false, name: 'assignRole'},
                ],
                resources: [
                        {resourceName: 'home', read: false, write: false, update: false, delete: false, name: '/'},
                        {resourceName: 'tags', read: true, write: false, update: false, delete: false, name: 'tags'},
                        {resourceName: 'category', read: false, write: false, update: false, delete: false, name: 'category'},
                        {resourceName: 'Create Blogs', read: false, write: false, update: false, delete: false, name: 'create_blogs'},
                        {resourceName: 'adminusers', read: false, write: false, update: false, delete: false, name: 'adminusers'},
                        {resourceName: 'role', read: false, write: false, update: false, delete: false, name: 'role'},
                        {resourceName: 'assignRole', read: false, write: false, update: false, delete: false, name: 'assignRole'},
                ],

            }
        },

        methods:{
            assignRoles(){
                // console.log(this.assignedRoles);
                var self = this;
                var data = JSON.stringify(this.assignedRoles);
                this.$store.commit('setRoleId', self.data.role_id)
                axios.post('app/assign_roles', {
                    permission: data,
                    role_id: self.data.role_id,
                })
                .then(function (response) {
                    self.success('the roles has been assigned successfully.')
                    var index = self.data.role_id
                    self.role[self.role.length - index].permission = data
                    window.location.reload();
                    console.log(window.role_id)

                    // console.log(response.data.iconImage);
                })
                .catch((error)=>{
                    self.error(error)

                });
            },

            changeRole(){
                var index = this.role.findIndex(singleRole => singleRole.id == this.role.length - 1);
                var index2 = this.data.role_id;
                var permission = this.role[this.role.length - index2].permission
                console.log(permission);
                if(!permission){
                    this.assignedRoles = this.resources
                }else{
                    this.assignedRoles = JSON.parse(permission);
                }
            }
        },

        created(){
            var self = this;
            console.log(this.$route)
           axios.get('/app/get_role')
            .then(function (response) {
                self.role = response.data;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
        //     .then(function () {
        //         // good
        //     });

            // const res = await this.callApi('get', '/app/get_tags');
            // if(res.status == 200){
            //     self.tags = res.data
            //     console.log(self.tags);
            // }else{
            //     this.error('something is going wrong');
            // }
            // getting updated role id starts
            axios.get('/app/get_role_id')
            .then(function (response) {

                if(response.data){
                    self.data.role_id = response.data;
                    self.data.role_id = self.role[self.role.length - response.data].id;
                    if(self.role[self.role.length - response.data].permission != null){
                        self.assignedRoles = JSON.parse(self.role[self.role.length - response.data].permission);
                    }else if(self.role[self.role.length - response.data].permission == null){
                        self.assignedRoles = self.resources;
                    }
                    // console.log(self.data.role_id);
                }
                // else{
                //     self.data.role_id = self.role[self.role.length - 1].id
                //     if(self.role[self.role.length - 1].permission){
                //         self.assignedRoles = JSON.parse(self.role[self.role.length - 1].permission);
                //     }else{
                //         self.assignedRoles = self.resources;
                //     }
                // }

                // console.log(self.categories);
                // window.tagItem = self.tags[self.tags.length - (self.tags.length + 1)];

            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            // getting updated role id ends
        },

    }
</script>
