<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'TSOhead' || $store.state.userRole =='TSO_PNO')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="messagePath" />
       <div id="layoutSidenav">
       <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="data.channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">MY CASES</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Tso-head" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Tso-head/my-cases" class="breadcrumb-item active">My cases</BreadcrumbItem>
                        </Breadcrumb>
                      </div>

                        <div class="card shadow mb-4">
                            <div class="card-header bg-dark text-muted">
                                <i class="fas fa-table me-1"></i>
                               My Cases
                            </div>
                           <!-- include case details -->
                           <caseTable :path="path" :username="username" :caseStatus="caseStatus" :action="action" :column="column"/>
                            <!-- include case details -->
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
import caseTable from '../../../components/pages/caseTable.vue'

export default {
  props:['user'],
  data(){
    return{
      data: {
        channel: '/Tso-head/progress-reports',

      },
      caseStatus:[4],
      path:'/Tso-head/my-cases/',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
      column: 'TSO_PNO',
      action:'Decision',
 

    }
  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    caseTable,
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
