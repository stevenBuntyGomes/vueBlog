<template>
    <div>
        <h1>basic methods</h1>
        <h3>counters: {{ views }}</h3>
        <button @click = "updateCounter(1)">Increase</button>
        <button @click = "updateCounter(-1)">Decrease</button>

        <div v-for = '(blog, i) in blogs' :key = 'i' v-if = "blogs.length">
            <h3>{{ blog.title }}</h3>
            <p>{{ blog.post }}</p>
        </div>

        <br/>
        <br/>

        <div>
            <p v-if = "showItem">show this if true</p>
            <p v-else>show this if false</p>
            <!-- <button @click = "changeShowItem">Change Condition</button> -->
            <button @click = "showItem = !showItem">Change Condition</button>
            <!-- <button @click = "showItem!=showItem">False</button> -->
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            views: 0,
            blogs: [],
            showItem: false,
        }
    },

    methods: {
        updateCounter(num){
            this.views += num;
        },

        changeShowItem(){
            this.showItem = !this.showItem
        }
    },
    // call the server to get the views of the blog

    created(){
        this.views = 100;

        // get the blog posts
        let posts = [
                {
                    title: 'this is blog title 1',
                    post: 'this is blog post 1',
                    id: 1,
                },
                {
                    title: 'this is blog title 2',
                    post: 'this is blog post 2',
                    id: 2,
                },
                {
                    title: 'this is blog title 3',
                    post: 'this is blog post 3',
                    id: 3,
                },
                {
                    title: 'this is blog title 4',
                    post: 'this is blog post 4',
                    id: 4,
                },
                {
                    title: 'this is blog title 5',
                    post: 'this is blog post 5',
                    id: 5,
                },
            ];

            this.blogs = posts;
    }

}
</script>
