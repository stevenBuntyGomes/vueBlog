import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        counter: 1000,
        deleteModalObj : {
            showDelModal: false,
            deleteUrl: '',
            data : null,
            delIndex: -1,
            isDel : false,
        },
        role_id: null,

        user: false,
        userPermission: null,
    },

    getters: {
        getCounter(state){
            // if(state.counter > 1000) return "omg this is big..."
            return state.counter;
        },

        getDelModal(state){
            return state.deleteModalObj;
        },

        getUserPermission(state){
            return state.userPermission;
        },

        // get role_id start

        // get role_id ends
    },

    mutations: {
        changeCounter(state, data){
            state.counter += data;
        },
        setDelModel(state, data){
            const deleteModalObj = {
                showDelModal: false,
                deleteUrl: '',
                data : null,
                delIndex: data,
                isDel : true,
            }
            state.deleteModalObj = deleteModalObj;
        },

        setDeletingModalObject(state, data){
            state.deleteModalObj = data
        },

        // mutate role_id start
        // mutate role_id end

        updateUser(state, data){
            state.user = data
        },

        setUserPermission(state, data){
            state.userPermission = data
        }
    },

    actions : {
        changeCountAction({commit}, data){
            commit('changeCounter', data)
        }
    },
})
// export const strict = false
// export const state = () => ({

// })

// // common getters
// export const getters = {

// }

// // actions for comitting mutations


// export const mutations = {

// }

// // actions for comitting actions

// export const actions = {

// }
