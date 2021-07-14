<template>
    <div>
        <div class="content">
			<div class="container">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button type="success" @click = "addModal=true" size = "small"> <Icon type="md-add" /> Add New Tags</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>Id</th>
								<th>Tag Name</th>
								<th>Category</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for = "(tag, i) in tags" :key = "i">
								<td>{{ tag.id }}</td>
								<td class="_table_name">{{ tag.tagName }}</td>
								<td>{{ tag.created_at }}</td>
								<td>

									<Button type="info" size = "small" @click="showEditModal(tag, i)">Edit</Button>
									<!-- <button class="_btn _action_btn make_btn2" type="button">Make Features</button>
									<button class="_btn _action_btn make_btn3" type="button">Make Card</button> -->
									<Button type="error" size = "small" @click="showDeleteModal(tag, i)" :loading = "tag.isDeleting">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
								<!-- ITEMS -->


						</table>
					</div>
                    <!-- tag adding model -->


                    <Modal
                        v-model="addModal"
                        title="add tag" :mask-closable = 'false' :closable = 'false'>
                        <input type="text" v-model="data.tagName" placeholder = "enter name">
                        <div slot = "footer">
                            <button type = "default" @click = "addModal=false">Close</button>
                            <button type = "primary" @click = "addTag" :disabled = "isAdding" :loading="isAdding">Add Tag</button>
                        </div>
                    </Modal>
                    <!-- tag adding model -->
                    <!-- tag edit Modal -->
                    <Modal
                        v-model="editModal"
                        title="edit tag" :mask-closable = 'false' :closable = 'false'>
                        <input type="text" v-model="editData.tagName" placeholder = "enter name">
                        <div slot = "footer">
                            <button type = "default" @click = "editModal=false">Close</button>
                            <button type = "primary" @click = "editTag" :disabled = "isAdding" :loading="isAdding">{{ isAdding ? 'Editing..' : 'Edit tag' }}</button>
                        </div>
                    </Modal>
                    <!-- tag edit Modal -->
                    <!-- tag delete modal -->
                        <Modal v-model="showDelModal" width="360">
                            <p slot="header" style="color:#f60;text-align:center">
                                <Icon type="ios-information-circle"></Icon>
                                <span>Delete confirmation</span>
                            </p>
                            <div style="text-align:center">
                                <p>Will you delete it?</p>
                            </div>
                            <div slot="footer">
                                <Button type="error" size="large" long :loading="isDel" :disabled = "isDel" @click="deleteTag">Delete</Button>
                            </div>
                        </Modal>

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
                    tagName: '',
                },


                addModal: false,
                isAdding: false,
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
                }
            }
        },

        methods:{
            async addTag(){
                if(this.data.tagName.trim()==''){
                    return this.error('tag name is required');
                }

                const res = await this.callApi('post', '/app/create_tag', this.data);
                if(res.status == 200){
                    this.tags.unshift(window.tagItem)
                    this.success('the tagName has been added successfully.')
                    this.addModal = false
                    this.data.tagName = ''
                }else{
                    // this.error('something is going wrong');
                    if(res.status == 422){
                        if(res.data.errors.tagName){
                            this.i(res.data.errors.tagName[0]);
                        }
                        console.log(res.data.errors);
                    }
                }
            },

            async editTag(){
                // if(this.editData.trim()==''){
                //     return this.error('tag name is required.');
                // }
                const res = await this.callApi('post', 'app/edit_tag/', this.editData);
                if(res.status == 200){
                    // this.tags.unshift(res.data)
                    this.tags[this.index].tagName = this.editData.tagName
                    this.success('the tagName has been edited successfully')
                    this.editModal = false,
                    this.data.tagName = ''
                }
            },
            showEditModal(tag, index){
                let obj = {
                    id: tag.id,
                    tagName: tag.tagName
                }
                this.editData = obj;
                this.editModal = true;
                // console.log(this.editData.tagName);
                this.index = index;
            },
            async deleteTag(){
                    // this.$set(tag, 'isDeleting', true);
                    this.isDel = true;
                    // console.log(tag.tagName);
                    const res = await this.callApi('post', 'app/delete_tag', this.deleteItem)
                    if(res.status == 200){
                        this.tags.splice(this.delIndex, 1)
                        this.success('tag has been deleted successfully');
                        this.showDelModal = false;
                        this.isDel = false;
                    }else{
                        this.error('something wrong happened')
                    }


                    // this.showDelModal = true;

            },

            showDeleteModal(tag, i){
                this.deleteItem = tag,
                this.delIndex = i,
                this.showDelModal = true
                // console.log(this.deleteItem.tagName);
            }

        },

        created(){
            var self = this;
           axios.get('/app/get_tags')
            .then(function (response) {
                self.tags = response.data;

                console.log(self.tags);
                // window.tagItem = self.tags[self.tags.length - (self.tags.length + 1)];
                window.tagItem = self.tags[self.tags.length - 1];

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
        }
    }
</script>
