<template>
    <div>
        <div class="content">
			<div class="container">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Category <Button type="success" @click = "addModal=true" size = "small"> <Icon type="md-add" /> Add New Category</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>No</th>
								<th>Id</th>
								<th>Category Name</th>
								<th>Image</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for = "(category, i) in categories" :key = "i">
								<td>{{ i }}</td>
								<td>{{ category.id }}</td>
								<td class="_table_name">{{ category.categoryName }}</td>
								<td class = "table_image">
                                    <!-- {{  }} -->
                                    <img :src="`/uploads/category/${category.iconImage}`" alt="" style = "width: 50px; height: 30px">
                                </td>
								<td>{{ category.created_at }}</td>
								<td>

									<Button type="info" size = "small" @click="showEditModal(category, i)">Edit</Button>
									<!-- <button class="_btn _action_btn make_btn2" type="button">Make Features</button>
									<button class="_btn _action_btn make_btn3" type="button">Make Card</button> -->
									<Button type="error" size = "small" @click="showDeleteModal(category, i)" :loading = "category.isDeleting">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
								<!-- ITEMS -->

						</table>
					</div>
                    <!-- tag adding model -->


                    <Modal
                        v-model="addModal"
                        title="add category" :mask-closable = 'false' :closable = 'false'>

                        <input type="text" v-model="data.categoryName" placeholder = "enter Image">
                        <div class = "mt-2"></div>
                        <Upload
                            ref="upload"
                            type="drag"
                            :headers= "{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                            :on-success="handleSuccess"
                            :format="['jpg','jpeg','png']"
                            :max-size="2048"
                            :data = "{'my_data' : 100}"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="app/upload">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                        <div class = "demo-upload-list" v-if="data.iconImage">
                            <img :src="`/uploads/category/${data.iconImage}`">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click="deleteImage()"></Icon>
                            </div>
                        </div>
                        <div slot = "footer">
                            <button type = "default" @click = "addModal=false">Close</button>
                            <button type = "primary" @click = "addCategory" :disabled = "isAdding" :loading="isAdding">Add Category</button>
                        </div>
                    </Modal>
                    <!-- tag adding model -->
                    <!-- category edit Modal -->
                    <Modal
                        v-model="editModal"
                        title="edit Category" :mask-closable = 'false' :closable = 'false'>
                        <input type="text" v-model="editData.categoryName" placeholder = "enter Image">
                        <div class = "mt-2"></div>
                        <Upload
                            v-show="isIconImageNew"
                            ref="editDataUpload"
                            type="drag"
                            :headers= "{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                            :on-success="handleSuccess"
                            :format="['jpg','jpeg','png']"
                            :max-size="2048"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="app/upload">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                        <div class = "demo-upload-list" v-if="editData.iconImage">
                            <img :src="`/uploads/category/${editData.iconImage}`">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                            </div>
                        </div>
                        <div slot = "footer">
                            <button type = "default" @click = "closeEditModal">Close</button>
                            <button type = "primary" @click = "editCategory" :disabled = "isAdding" :loading="isAdding">{{ isAdding ? 'Editing..' : 'Edit Category' }}</button>
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
                    iconImage: '',
                    categoryName: '',
                },


                path: '/uploads/category/',
                addModal: false,
                isAdding: false,
                categories: [],
                tags: [],
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
            addCategory(){
                if(this.data.categoryName.trim() == ''){
                    return this.error('category name is required');
                }

                if(this.data.iconImage.trim() == ''){
                    return this.error('category image is required');
                }
                // this.data.iconImage = `uploads/category/${this.data.iconImage}
                // axios method starts
                var self = this;
                axios.post('/app/create_category', {
                    categoryName: self.data.categoryName,
                    iconImage: self.data.iconImage,
                })
                .then(function (response) {
                    self.success('the tagName has been added successfully.');
                    self.categories.unshift(response.data);
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

            async editCategory(){
                // if(this.editData.trim()==''){
                //     return this.error('tag name is required.');
                // }
                const res = await this.callApi('post', '/app/edit_category', this.editData);
                if(res.status == 200){
                    // this.tags.unshift(res.data)
                    this.categories[this.index].categoryName = this.editData.categoryName
                    // this.categories[this.index].iconImage = this.editData.iconImage
                    this.success('the tagName has been edited successfully')
                    // this.editModal = false,
                    this.data.categoryName = '';
                    this.editModal = false;
                }
            },
            showEditModal(category, index){
                // let obj = {
                //     id: category.id,
                //     categoryName: category.categoryName
                // }
                this.editData = category;
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
            this.token = window.Laravel.csrfToken;
            var self = this;
           axios.get('/app/get_category')
            .then(function (response) {
                self.categories = response.data;

                // console.log(self.categories);
                // window.tagItem = self.tags[self.tags.length - (self.tags.length + 1)];
                window.tagItem = self.categories[0];
                console.log(window.tagItem);

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
