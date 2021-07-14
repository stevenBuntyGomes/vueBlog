<template>
    <div>
        <div class="content">
			<div class="container">
                <h1>I will show how all other components react to changes</h1>
                <p>current master state of counter: {{ counter }}</p>
			</div>
            <div>
                <compA></compA>
            </div>
            <div>
                <compB></compB>
            </div>
            <div>
                <compC></compC>
            </div>
            <button type = "info" @click = "changeCounter">Change state of counter</button>
        </div>
    </div>
</template>
<script>
    import compA from './compA'
    import compB from './compB'
    import compC from './compC'
    import {mapGetters, mapActions} from 'vuex'
export default {

    data(){
        return {
            localVar : 'some value'
        }
    },

    computed : {
        ...mapGetters({
            'counter' : 'getCounter'
        }),
        // ...mapGetters(['getCounter'])
    },

    created(){
        console.log(this.$store.state.counter);
    },

    methods: {
        changeCounter(){
            this.$store.dispatch('changeCountAction', 1);
            // this.$store.commit('changeCounter', 1);
        },

        whenCounterChange(){
            console.log('i am running as the counter changes')
        },

        ...mapActions([
            'changeCountAction'
        ]),
    },


    components : {
        compA,
        compB,
        compC,
    },

    watch : {
        counter(value){
            console.log(value);
            this.whenCounterChange();
            console.log(this.localVar);
        }
    }

}
</script>
