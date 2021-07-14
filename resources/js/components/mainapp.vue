
<template>
  <div id="app">
    <div>
      <!--========== ADMIN SIDE MENU one ========-->
      <div class="_1side_menu" v-if="$store.state.user">
        <div class="_1side_menu_logo" >
          <h3 style="text-align:center;">Logo Image</h3>
          <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
        </div>

        <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
        <div class="_1side_menu_content" >
          <div class="_1side_menu_img_name">
            <!-- <img class="_1side_menu_img" src="/pic.png" alt="" title=""> -->
            <p class="_1side_menu_name">Admin</p>
          </div>

          <!--~~~ MENU LIST ~~~~~~-->
          <div class="_1side_menu_list">
            <ul class="_1side_menu_list_ul">
              <li v-for="(link, i) in permission" :key="i" v-if="permission.length && link.read">
                  <router-link :to="link.name"> <Icon type="ios-speedometer"/>{{ link.resourceName }}</router-link>
                </li>
              <!-- <li><router-link to="tags"> <Icon type="ios-speedometer"/>Tags</router-link></li>
              <li><router-link to="category"> <Icon type="ios-speedometer"/>Category</router-link></li>
              <li><router-link to="adminusers"> <Icon type="ios-speedometer"/>Admin Users</router-link></li>
              <li><router-link to="role"> <Icon type="ios-speedometer"/>Roles</router-link></li>
              <li><router-link to="assignRole"> <Icon type="ios-speedometer"/>Assign Role</router-link></li> -->
              <li><a href="/logout"><Icon type="ios-speedometer"/>Log Out</a></li>

            </ul>
          </div>
        </div>
      </div>
      <!--========== ADMIN SIDE MENU ========-->

      <!--========= HEADER ==========-->
      <div class="header" v-if="$store.state.user">
        <div class="_2menu _box_shadow">
          <div class="_2menu_logo">
            <ul class="open_button">
              <li>
                <Icon type="ios-list" />
              </li>
              <!--<li><Icon type="ios-albums" /></li>-->
            </ul>
          </div>
        </div>
      </div>
      <!--========= HEADER ==========-->
    </div>
    	<router-view>
            </router-view>
  </div>
</template>
<script>

export default {
    props: ['user', 'permission'],
    data(){
        return {
            isLoggedin: false,
        }
    },
    created(){
        this.$store.commit('updateUser', this.user);
        this.$store.commit('setUserPermission', this.permission);
        // console.log(this.user);
        // console.log(this.permission);
        // this.$store.commit('updatePermission', this.permission);
    },
}

</script>
