<template>
    <div>
        <!-- tag delete modal -->
                        <Modal
                        :value="deleteModalObj.showDelModal"
                        :mask-closable = 'false'
                        :closable = 'false'
                        width="360">
                            <p slot="header" style="color:#f60;text-align:center">
                                <Icon type="ios-information-circle"></Icon>
                                <span>Delete confirmation</span>
                            </p>
                            <div style="text-align:center">
                                <p>Will you delete it?</p>
                            </div>
                            <div slot="footer">
                                <Button type="error" size="large" long :loading="deleteModalObj.isDel" :disabled = "deleteModalObj.isDel" @click="deleteCategory">Delete</Button>
                            </div>
                        </Modal>

                    <!-- tag delete modal -->
    </div>
</template>

<script>

    import {mapGetters} from 'vuex'
    export default {
        data(){
            return {
                isDel: false,
            }
        },

        methods : {
            async deleteCategory(){
                    // this.$set(tag, 'isDeleting', true);
                    // this.isDel = true;
                   // this.$set(tag, 'isDeleting', true);
                    this.isDel = true;
                    // // console.log(tag.tagName);
                    const res = await this.callApi('post', this.deleteModalObj.deleteUrl, this.deleteModalObj.data)
                    if(res.status == 200){
                        // this.categories.splice(this.delIndex, 1)
                        this.success('tag has been deleted successfully');
                        this.showDelModal = false;
                        // this.isDel = false;
                        this.$store.commit('setDelModel', this.deleteModalObj.delIndex); //change
                    }else{
                        this.error('something wrong happened');
                        this.$store.commit('setDelModel', false);
                    }


                    // this.showDelModal = true;

            },
        },

        computed : {
            ...mapGetters({
                'deleteModalObj' : 'getDelModal',
            })
        }
    }
</script>
