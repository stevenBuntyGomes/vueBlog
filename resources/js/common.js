import {mapGetters} from 'vuex'


export default {


    data(){
        return {

        }
    },

    methods: {
        async callApi(method, url, dataObj){

            try{
                // Send a POST request
                return await axios({
                    method: 'post',
                    url: url,
                    data: dataObj,
                });
            } catch(error){
                // console.log(error);
                return error.response
            }

        },

            info(desc, title="Hey!") {
                this.$Notice.info({
                    title: title,
                    desc: desc
                });
            },
            success (desc, title="Great!") {
                this.$Notice.success({
                    title: title,
                    desc: desc
                });
            },
            warning (desc, title="Opps!") {
                this.$Notice.warning({
                    title: title,
                    desc: desc
                });
            },
            error (desc, title="Shit!") {
                this.$Notice.error({
                    title: title,
                    desc: desc
                });
            },
            // sw (desc, title="Hell!") {
            //     this.$Notice.sw({
            //         title: title,
            //         desc: desc
            //     });
            // }
            checkUserPermission(key){
                if(!this.userPermission){
                    return true;
                }
                let isPermitted = false;
                for(let d of this.userPermission){
                    if(this.$route.name == d.name){
                        if(d[key]){
                            isPermitted = true;
                            break;
                        }else{
                            break;
                        }
                    }
                    // console.log(d[key]);
                }
                return isPermitted;
            }


    },

    computed : {

        ...mapGetters({
                'userPermission' : 'getUserPermission',
            }),

        isReadPermitted(){
            // console.log('this is permission ', this.userPermission);
            return this.checkUserPermission('read');
        },

        isWritePermitted(){
            return this.checkUserPermission('write');
        },

        isUpdatePermitted(){
            return this.checkUserPermission('update');
        },

        isDeletePermitted(){
            return this.checkUserPermission('delete');
        },
    }


}
