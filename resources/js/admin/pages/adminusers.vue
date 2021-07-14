<template>
    <div>
        <div class="content">
			<div class="container">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Admin Users <Button type="success" @click = "addModal=true" size = "small"> <Icon type="md-add" /> Add New Admin</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>No</th>
								<th>Id</th>
								<th>User Name</th>
								<th>Email</th>
								<th>User Type</th>
								<th>Image</th>
								<th>Created At</th>
								<th>Edit</th>
								<th>Edit Pass</th>
								<th>Delete</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for = "(user, i) in users" :key = "i">
								<td>{{ i }}</td>
								<td>{{ user.id }}</td>
								<td class="_table_name">{{ user.fullName }}</td>
								<td>{{ user.email }}</td>
								<td>{{ user.userType }}</td>
								<td class = "table_image">
                                    <!-- {{  }} -->
                                    <img :src="`/uploads/user/${user.image}`" alt="" style = "width: 50px; height: 30px">
                                </td>
								<td>{{ user.created_at }}</td>
								<td>

									<Button type="info" size = "small" @click="showEditModal(user, i)">Edit</Button>

									<!-- <button class="_btn _action_btn make_btn2" type="button">Make Features</button>
									<button class="_btn _action_btn make_btn3" type="button">Make Card</button> -->

								</td>
                                <td><Button type="primary" size = "small" @click="showEditPassModal(user, i)">Edit Password</Button></td>
                                <td><Button type="error" size = "small" @click="showDeleteModal(user, i)" :loading = "user.isDeleting">Delete</Button></td>
							</tr>
								<!-- ITEMS -->
								<!-- ITEMS -->


						</table>
					</div>
                    <!-- tag adding model -->


                    <Modal
                        v-model="addModal"
                        title="add category" :mask-closable = 'false' :closable = 'false'>
                        <div class = "space">
                            <label for="full name">Full name</label><br>
                            <br>
                            <input class="form-control" type="text" v-model="data.fullName" placeholder = "Full Name">
                        </div>
                        <br>
                        <br>
                        <div class="space">
                            <label for="email">Email Name</label>
                            <br>
                            <input class="form-control" type="email" v-model="data.email" placeholder = "Email">
                        </div>

                        <br>
                        <br>
                        <div class="space">
                            <label for="password">Password</label>
                            <br>
                            <input class="form-control" type="password" v-model="data.password" placeholder = "password">
                        </div>
                        <br>
                        <br>
                        <div class="space">
                            <Select v-model="data.role_id" style="width:100%" placeholder = "select admin type">
                                    <Option v-for='(role, i) in roles' :key='i' :value="role.id">{{ role.roleName  }}</Option>


                                <!-- <Option value="Editor">Editor</Option> -->
                            </Select>
                        </div>

                        <div class = "mt-2"></div>
                        <Upload
                            ref="upload"
                            type="drag"
                            :headers= "{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                            :on-success="handleSuccess"
                            :format="['jpg','jpeg','png']"
                            :max-size="2048"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="app/upload/userImage">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                        <div class = "demo-upload-list" v-if="data.image">
                            <img :src="`/uploads/user/${data.image}`">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click="deleteImage()"></Icon>
                            </div>
                        </div>
                        <div slot = "footer">
                            <button type = "default" @click = "addModal=false">Close</button>
                            <button type = "primary" @click = "addAdmin" :disabled = "isAdding" :loading="isAdding">Add Admin</button>
                        </div>
                    </Modal>
                    <!-- tag adding model -->
                    <!-- category edit Modal -->
                    <Modal
                        v-model="editModal"
                        title="edit Category" :mask-closable = 'false' :closable = 'false'>
                        <div class = "space">
                            <label for="full name">Full name</label><br>
                            <br>
                            <input class="form-control" type="text" v-model="editData.fullName" placeholder = "Full Name">
                        </div>
                        <br>
                        <br>
                        <div class="space">
                            <label for="email">Email Name</label>
                            <br>
                            <input class="form-control" type="email" v-model="editData.email" placeholder = "Email">
                        </div>

                        <!-- <br>
                        <br>
                        <div class="space">
                            <label for="password">Password</label>
                            <br>
                            <input class="form-control" type="password" v-model="editData.password" placeholder = "password">
                        </div> -->
                        <br>
                        <br>
                        <div class="space">
                            <Select v-model="editData.userType" style="width:100%" placeholder = "select admin type">
                                <Option value="Admin">Admin</Option>
                                <Option value="Editor">Editor</Option>
                            </Select>
                        </div>
                        <div class = "mt-2"></div>
                        <Upload
                            v-show="isIconImageNew"
                            ref="upload"
                            type="drag"
                            :headers= "{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                            :on-success="handleSuccess"
                            :format="['jpg','jpeg','png']"
                            :max-size="2048"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="app/upload/userImage">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                        <div class = "demo-upload-list" v-if="editData.image">
                            <img :src="`/uploads/user/${editData.image}`">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                            </div>
                        </div>
                        <div slot = "footer">
                            <button type = "default" @click = "closeEditModal">Close</button>
                            <button type = "primary" @click = "editAdmin" :disabled = "isAdding" :loading="isAdding">{{ isAdding ? 'Editing..' : 'Edit Admin' }}</button>
                        </div>
                    </Modal>
                    <!-- category edit Modal -->


                    <!-- edit password model starts -->

                    <Modal
                        v-model="editPassModal"
                        title="edit Category" :mask-closable = 'false' :closable = 'false'>
                        <div class = "space">
                            <label for="full name">Old Password</label><br>
                            <br>
                            <input class="form-control" type="password" v-model="editData.password" placeholder = "Old Password">
                        </div>
                        <div class = "space">
                            <label for="full name">New Password</label><br>
                            <br>
                            <input class="form-control" type="password" v-model="new_password" placeholder = "New Password">
                        </div>
                        <br>
                        <br>
                        <div class="space">
                            <label for="email">Confirm Password</label>
                            <br>
                            <input class="form-control" type="password" v-model="confirm_password" placeholder = "Confirm Password">
                        </div>

                        <!-- <br>
                        <br>
                        <div class="space">
                            <label for="password">Password</label>
                            <br>
                            <input class="form-control" type="password" v-model="editData.password" placeholder = "password">
                        </div> -->
                        <div class = "mt-2"></div>
                        <div slot = "footer">
                            <button type = "default" @click = "closeEditPassModal">Close</button>
                            <button type = "primary" @click = "editAdminPass" :disabled = "isAdding" :loading="isAdding">{{ isAdding ? 'Editing..' : 'Edit Admin' }}</button>
                        </div>
                    </Modal>

                    <!-- edit password model ends -->
                    <!-- tag delete modal -->
                        <!-- <Modal v-model="showDelModal" width="360">
                            <p slot="header" style="color:#f60;text-align:center">
                                <Icon type="ios-information-circle"></Icon>
                                <span>Delete confirmation</span>
                            </p>
                            <div style="text-align:center">
                                <p>Will you delete it?</p>
                            </div>
                            <div slot="footer">
                                <Button type="error" size="large" long :loading="isDel" :disabled = "isDel" @click="deleteCategory">Delete</Button>
                            </div>
                        </Modal> -->
                        <deleteModal>
                        </deleteModal>

                    <!-- tag delete modal -->
				</div>
				 <!-- <Page :total="100" /> -->

			</div>
		</div>
    </div>
</template>
<script>
    const axios = require('axios').default;
    import {mapGetters} from 'vuex'
    import  deleteModal from '../components/deleteModal.vue'

    export default {

        data(){
            return {
                data: {
                    fullName: '',
                    email: '',
                    password: '',
                    userType: '',
                    role_id: null,
                    image: '',
                },


                path: '/uploads/category/',
                addModal: false,
                isAdding: false,
                users: [],
                roles: [],

                categories: [],
                tags: [],
                editModal: false,
                editPassModal: false,
                editData: {
                    fullName: '',
                    email: '',
                    password: '',
                    userType: '',
                    image: '',
                },
                confirm_password: '',
                new_password: '',
                index: -1,
                showDelModal: false,
                isDel: false,
                deleteItem: {
                    delIndex: -1,
                },
                token: '',
                isIconImageNew: false,
                isEditingItem: false,
            }
        },

        methods:{
            addAdmin(){
                if(this.data.fullName.trim() == ''){
                    return this.error('Admin name is required');
                }

                if(this.data.email.trim() == ''){
                    return this.error('Admin email is required');
                }

                if(this.data.password.trim() == ''){
                    return this.error('Admin password is required');
                }
                if(!this.data.role_id){
                    return this.error('Admin user type is required');
                }
                if(this.data.image.trim() == ''){
                    return this.error('Admin image is required');
                }
                // this.data.iconImage = `uploads/category/${this.data.iconImage}
                // axios method starts
                var self = this;
                axios.post('app/upload/create_user', {
                    fullName: self.data.fullName,
                    email: self.data.email,
                    password: self.data.password,
                    role_id: self.data.role_id,
                    image: self.data.image,
                })
                .then(function (response) {
                    self.success('the tagName has been added successfully.');
                    self.users.unshift(response.data);
                    self.addModal = false
                    console.log(response.data);
                    self.data.categoryName = ''
                    self.data.iconImage = ''
                    // console.log(response.data.iconImage);
                })
                .catch(function (error) {
                    console.log(error);
                });
                // axios method ends


            },

            async editAdmin(){
                if(this.editData.fullName.trim() == ''){
                    return this.error('Admin name is required');
                }

                if(this.editData.email.trim() == ''){
                    return this.error('Admin email is required');
                }

                // if(this.editData.password.trim() == ''){
                //     return this.error('Admin password is required');
                // }
                if(!this.editData.role_id){
                    return this.error('Admin user type is required');
                }
                if(this.editData.image.trim() == ''){
                    return this.error('Admin image is required');
                }
                const res = await this.callApi('post', '/app/edit_user', this.editData);
                if(res.status == 200){
                    // this.tags.unshift(res.data)
                    this.users[this.index].fullName = this.editData.fullName
                    this.users[this.index].email = this.editData.email
                    this.users[this.index].userType = this.editData.userType
                    this.users[this.index].image = this.editData.image
                    // this.categories[this.index].iconImage = this.editData.iconImage
                    this.success('the user has been edited successfully')
                    // this.editModal = false,
                    this.data.categoryName = '';
                    this.editModal = false;
                }else if(res.status == 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i])
                    }
                }
            },

            editAdminPass(){
                // if(this.editData.password.trim() == ''){
                //     return this.error('Admin password is required');
                // }

                // if(this.confirm_password.trim() == ''){
                //     return this.error('confirm password is required');
                // }
                var self = this;
                axios.post('/app/edit_user/password', {
                    id: this.editData.id,
                    oldPass: this.editData.password,
                    newPass: this.new_password,
                    confirmPass: this.confirm_password,
                })
                .then(function (response) {
                    if(response.data == 1){
                        self.error('The Old Password Does not match');
                    }else if(response.data == 2){
                        self.error('Old Password and New Password Can not be same.');
                    }else if(response.data == 3){
                        self.error('New Password and Confirm Password has to be same.');
                    }else if(response.data == 4){
                        self.success('Password changed successfully');
                        self.editPassModal = false;
                        self.editData.password = '';
                        self.new_password = '';
                        self.confirm_password = '';
                    }
                })
                .catch(function (error) {
                    for(let i in error.response.data.errors){
                        // console.log(error.response.data.errors[i]);
                        self.error(error.response.data.errors[i]);
                    }

                });

            },


            showEditModal(user, index){
                // let obj = {
                //     id: category.id,
                //     categoryName: category.categoryName
                // }
                this.editData = user;
                this.editModal = true;
                // console.log(this.editData.tagName);
                this.index = index;
                this.isEditingItem = true;
            },

            closeEditModal(){
                this.isEditingItem = false;
                this.editModal = false;
            },

            closeEditPassModal(){
                this.editPassModal = false;
            },

            showEditPassModal(user, index){
                this.editData = user;
                this.index = index;
                this.editPassModal = true;
            },

            async deleteCategory(){
                    // this.$set(tag, 'isDeleting', true);
                    this.isDel = true;
                    // console.log(tag.tagName);
                    const res = await this.callApi('post', 'app/delete_category', this.deleteItem)
                    if(res.status == 200){
                        this.categories.splice(this.delIndex, 1)
                        this.success('tag has been deleted successfully');
                        this.showDelModal = false;
                        this.isDel = false;
                    }else{
                        this.error('something wrong happened')
                    }


                    // this.showDelModal = true;

            },

            showDeleteModal(category, i){
                const deleteModalObj = {
                    showDelModal: true,
                    deleteUrl: 'app/delete_category',
                    data : category,
                    delIndex: i,
                    isDel : false,
                }
                this.$store.commit('setDeletingModalObject', deleteModalObj)
                // this.deleteItem = category,
                // this.delIndex = i,
                // this.showDelModal = true
                // console.log(this.deleteItem.iconImage);
                // console.log(this.deleteItem.categoryName);
            },

            // image Upload starts
            handleSuccess (res, file) {
                if(this.isEditingItem){
                    return this.editData.image = res
                    // console.log(res)

                }
                this.data.image = res
            },
            handleFormatError (file) {
                this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },
            handleMaxSize (file) {
                this.$Notice.warning({
                    title: 'Exceeding file size limit',
                    desc: 'File  ' + file.name + ' is too large, no more than 2M.'
                });
            },

            // handleError (res, file){
            //     console.log('res', res);
            //     console.log('file', file);
            //     this.$Notice.warning({
            //         title: 'Exceeding file size limit',
            //         desc: `${file.errors.file.length ? file.errors.file[0] : 'something wrong!'}`
            //     });
            // },

            handleView (name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove (file) {
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
            },

            async deleteImage(isAdd=true){
                if(!isAdd){//for editingIconImage
                    this.isIconImageNew = true;
                    var image = this.editData.image;
                    this.editData.image = '';
                    this.$refs.upload.clearFiles();
                }else{
                    var image = this.data.image;
                    this.data.image = '';
                    this.$refs.upload.clearFiles();
                }
                const res = await this.callApi('post', 'app/user/delete_image', {imgName: image});
                // const res =
                if(res.status != 200){
                    this.data.image = image;
                    this.error('something wrong happened');

                }
            },
            // image Upload ends

        },

        created(){
            this.token = window.Laravel.csrfToken;
            var self = this;
            // const [res, resRole] = await Promise.all([
            //     this.callApi('get', '/app/get_user'),
            //     this.callApi('get', '/app/get_role'),
            // ])
           axios.get('/app/get_user')
            .then(function (response) {
                self.users = response.data;
                // console.log(response.data);

                // console.log(self.categories);
                // window.tagItem = self.tags[self.tags.length - (self.tags.length + 1)];

            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            axios.get('/app/get_role')
            .then(function (response2) {
                self.roles = response2.data;

                // console.log(self.categories);
                // window.tagItem = self.tags[self.tags.length - (self.tags.length + 1)];

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
        },

        computed : {
            ...mapGetters({
                'deleteModalObj' : 'getDelModal',
            })
        },
        watch: {
            deleteModalObj(obj){
                console.log(obj)
                if(obj.isDel){
                    this.categories.splice(obj.delIndex, 1)
                }
            }
        },


        components : {
            deleteModal
        }
    }
</script>
