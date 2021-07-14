<template>
    <div>
        <div class="content">
			<div class="container">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                    <p class="_title0" v-if = "isWritePermitted">Role Management <Button type="success" @click = "addModal=true" size = "small"> <Icon type="md-add" /> Add New Role</Button></p>

					<div class="_overflow _table_div">
                        <editor
                            ref="editor"
                            autofocus
                            image
                            :config="config"
                            :initialized="onInitialized"
                            holder-id="codex-editor"
                            save-button-id="save-button"
                            :init-data="initData"
                            @save="onSave"
                        />
					</div>
                    <Button @click = "save">Save the data</Button>
                    <!-- tag adding model -->

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
                config: {
                    image: {
                    // Like in https://github.com/editor-js/image#config-params
                    field: 'image',
                    types: 'image/*',
                    },
                },

                initData : null,

                data: {

                },


                path: '/uploads/category/',
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


            onSave(response){
                console.log('response on save: ', response);
            },

            save(){
                console.log(this.$refs.editor.save())
            },

            onInitialized(){

            },

            // save(){

            // }
            // image Upload ends

        },
    }
</script>
