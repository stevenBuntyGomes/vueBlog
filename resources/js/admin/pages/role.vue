<template>
    <div>
        <div class="content">
			<div class="container">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                    <p class="_title0" v-if = "isWritePermitted">Role Management <Button type="success" @click = "addModal=true" size = "small"> <Icon type="md-add" /> Add New Role</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>No</th>
								<th>Id</th>
								<th>Role Type</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for = "(singleRole, i) in role" :key = "i">
								<td>{{ i }}</td>
								<td>{{ singleRole.id }}</td>
								<td class="_table_name">{{ singleRole.roleName }}</td>
								<td>{{ singleRole.created_at }}</td>
								<td>

									<Button type="info" v-if = "isUpdatePermitted"  size = "small" @click="showEditModal(singleRole, i)">Edit</Button>
									<!-- <button class="_btn _action_btn make_btn2" type="button">Make Features</button>
									<button class="_btn _action_btn make_btn3" type="button">Make Card</button> -->

                                    <Button type="error" v-if = "isDeletePermitted" size = "small" @click="showDeleteModal(singleRole, i)" :loading = "singleRole.isDeleting">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
								<!-- ITEMS -->


						</table>
					</div>
                    <!-- tag adding model -->


                    <Modal
                        v-model="addModal"
                        title="add role" :mask-closable = 'false' :closable = 'false'>

                        <input type="text" v-model="data.roleName" placeholder = "Add Role">
                        <div class = "mt-2"></div>
                        <div slot = "footer">
                            <button type = "default" @click = "addModal=false">Close</button>
                            <button type = "primary" @click = "add" :disabled = "isAdding" :loading="isAdding">Add Role</button>
                        </div>
                    </Modal>
                    <!-- tag adding model -->
                    <!-- category edit Modal -->
                    <Modal
                        v-model="editModal"
                        title="edit role" :mask-closable = 'false' :closable = 'false'>
                        <input type="text" v-model="editData.roleName" placeholder = "enter role Name">
                        <div class = "mt-2"></div>
                        <div slot = "footer">
                            <button type = "default" @click = "closeEditModal">Close</button>
                            <button type = "primary" @click = "edit" :disabled = "isAdding" :loading="isAdding">{{ isAdding ? 'Editing..' : 'Edit Role Name' }}</button>
                        </div>
                    </Modal>
                    <!-- category edit Modal -->
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
                    roleName: '',
                },


                path: '/uploads/category/',
                addModal: false,
                isAdding: false,
                categories: [],
                role: [],
                editModal: false,
                editData: {
                    // tagName: ''
                },
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
            add(){
                if(this.data.roleName.trim() == ''){
                    return this.error('category name is required');
                }
                // this.data.iconImage = `uploads/category/${this.data.iconImage}
                // axios method starts
                var self = this;
                axios.post('/app/create_role', {
                    roleName: self.data.roleName,
                })
                .then(function (response) {
                    self.success('the Role has been added successfully.');
                    self.role.unshift(response.data);
                    self.addModal = false
                    console.log(response.data);
                    self.data.roleName = ''
                    // console.log(response.data.iconImage);
                })
                .catch(function (error) {
                    for(let i in error.response.data.errors){
                        self.error(error.response.data.errors[i]);
                    }
                });
                // axios method ends


            },

            async edit(){
                // if(this.editData.trim()==''){
                //     return this.error('tag name is required.');
                // }
                const res = await this.callApi('post', '/app/edit_role_name', this.editData);
                if(res.status == 200){
                    // this.tags.unshift(res.data)
                    this.role[this.index].roleName = this.editData.roleName
                    // this.categories[this.index].iconImage = this.editData.iconImage
                    this.success('the tagName has been edited successfully')
                    // this.editModal = false,
                    this.data.roleName = '';
                    this.editModal = false;
                }
            },
            showEditModal(role, index){
                // let obj = {
                //     id: category.id,
                //     categoryName: category.categoryName
                // }
                this.editData = role;
                this.editModal = true;
                // console.log(this.editData.tagName);
                this.index = index;
                this.isEditingItem = true;
            },

            closeEditModal(){
                this.isEditingItem = false;
                this.editModal = false;
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
                    return this.editData.iconImage = res
                    console.log(res)

                }
                this.data.iconImage = res
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
                    var image = this.editData.iconImage;
                    this.editData.iconImage = '';
                    this.$refs.editDataUpload.clearFiles();
                }else{
                    var image = this.data.iconImage;
                    this.data.iconImage = '';
                    this.$refs.upload.clearFiles();
                }
                const res = await this.callApi('post', 'app/delete_image', {imgName: image});
                // const res =
                if(res.status != 200){
                    this.data.iconImage = image;
                    this.error('something wrong happened');

                }
            },
            // image Upload ends

        },

        created(){
            console.log(this.isWritePermitted);
            this.token = window.Laravel.csrfToken;
            var self = this;
           axios.get('/app/get_role')
            .then(function (response) {
                self.role = response.data;

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
