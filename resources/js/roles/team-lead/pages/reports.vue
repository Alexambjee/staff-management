<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'TL_PNO' || $store.state.userRole =='TL_PNO')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="messagePath"/>
       <div id="layoutSidenav">
       <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="data.channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">REPORTS</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Team-lead/reports" class="breadcrumb-item active">Reports</BreadcrumbItem>
                        </Breadcrumb>
                      </div>

                        <div class="card detail-card mb-4">
                            <div class="card-header bg-dark text-light">
                                <i class="fas fa-table me-1"></i>
                               REPORTS
                            </div>
                           <!-- include reports details -->
                              <Reportsdetails :path="path" :username="username" />
                            <!-- include reports details -->
                        </div>

                  </div>
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
import Reportsdetails from '../../../components/pages/reports-details.vue'

export default {
  data(){
    return{
      data:{channel: '/Team-lead/progress-reports'
      },

      path:'/Team-lead/reports/',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,

      messagePath:'/manager/messages'
    }
  },
  components : {
    Footer,
    Sidebar,
    Navbar,
   Reportsdetails
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
