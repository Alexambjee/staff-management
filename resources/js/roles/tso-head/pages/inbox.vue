<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'TSOhead' || $store.state.userRole =='TSOhead')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="messagePath"/>
       <div id="layoutSidenav">
       <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">MESSAGE INBOX</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Tso-head" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Tso-head/messages" class="breadcrumb-item">Messages</BreadcrumbItem>
                        </Breadcrumb>
                      </div>
                      <!-- include message component -->
                      <message  :username="$store.state.user.USER_NAME"/>
                      <!-- include message component ends-->

                  </div>

            <!--details ends -->
              </main>

            <!-- footer -->
            <Footer></Footer>
          </div>
        </div>
    </div>
     <!-- if not authorized -->
       <div v-else>
        <div class="col-md-5" style="margin:auto ">
        <div class="card shadow mt-5">
          <div class="card-body">
          <!-- error card -->
              <div id="layoutSidenav_content">
                  <main>
                      <div class="container-fluid px-4">
                    <span class="text-danger">Access Denied. You Don't Have Enough
                    Permissions to Access This Profile!</span>
                      </div>
                  </main>
            <!-- footer -->
          </div>
          <!-- error card ends -->

            </div>
          </div>
        </div>
    </div>
    <!-- if not authorized  ends-->

</template>
<script>
import Footer from '../../../components/footer.vue'
import Sidebar from '../../../components/sidebar.vue'
import Navbar from '../../../components/navbar.vue'
import message from '../../../components/pages/message.vue'
export default {

  data(){
    return {
        channel: '/Team-lead/progress-reports',
      messagePath:'/Team-lead/messages',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
      manualPath:'/Team-lead/Manual-cases',
    }

  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    message
  },

created() {
     if (!this.$store.state.user) {
      this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      window.location = '/'
      
    }
    else {
    }
  }

}
</script>
