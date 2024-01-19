<template>
  <div>
    <router-view :user="user" :role="this.data.role" />
  </div>
</template>
<script>
export default {
  props:['user'],
    data(){
      return {
        user: [
          {
            role: {
              ROLE_NAME:''
            }
          }
        ],
          data:{
            role:this.role,
            role:'',
          }
        }
  },
  created() {   
    console.log(this.user)
      this.$store.commit('setUpdatedUser', this.user)
      this.$store.commit('setUserRole', this.user['role']['ROLE_NAME'])
  }
   
}
</script>
