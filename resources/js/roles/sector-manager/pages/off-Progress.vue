<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'SM_PNO' || $store.state.userRole =='SM_PNO')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath"/>
       <div id="layoutSidenav">
       <Sidebar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath"  :username="username" :manualPath="manualPath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">Progress of Cases</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/officer" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/officer/progress-reports" class="breadcrumb-item active">Cases Progress</BreadcrumbItem>
                        </Breadcrumb>
                      </div>

                      <!-- include Cases Progress component -->
                      <!-- <CasesProgress/> -->
                      <div class="card detail-card mb-4">
                            <div class="card-header bg-dark text-light">
                                <i class="fas fa-table me-1"></i>
                               Progress Report
                            </div>
                            <!-- include case details -->
                               <Report :column="data.column" :username="data.username" />
                            <!-- include case details -->
                        </div>
                      <!-- include Cases Progress component ends-->

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
                    Permissions to Access This Route!</span>
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
// import casesProgress from '../../../components/pages/caseProgress.vue'
// import caseTable from '../../../components/pages/mycasestable.vue'
import Report from '../../../components/pages/caseProgress.vue'

export default {
  data(){
    return{

        data: {
          username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
                column: 'SM_PNO',
                channel: '/Sector-manager/progress-reports',
                manualPath:'/Sector-manager/Manual-cases',
                messagePath:'/Sector-manager/messages'
              }
    }


  },

  components : {
    Footer,
    Sidebar,
    Navbar,
    // caseTable,
    Report,
    // casesProgress
    // name:casesPrgress

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
