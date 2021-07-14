<template>
    <div>
        <h1 ref = "myref">This is hook</h1>
        <p>{{ name }}</p>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                name: 'bunty'
            }
        },

        beforeCreate(){
            console.log(this.name);
        },

        created(){
           // console.log(this.$refs.myref);
            console.log('data and other functions are available but not html: ', this.name);
        },

        mounted(){
            console.log(this.$refs.myref);
        },
    }
</script>
