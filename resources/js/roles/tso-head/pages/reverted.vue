<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'Report' || $store.state.userRole =='report')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath"/>
       <div id="layoutSidenav">
              <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="data.channel" />

          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">Reverted Cases</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/reporting" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/reporting/reverted" class="breadcrumb-item active">Reverted Cases</BreadcrumbItem>
                        </Breadcrumb>
                      </div>

                      <!-- include Cases Progress component -->
                      <!-- <CasesProgress/> -->
                      <div class="card detail-card mb-4">
                            <div class="card-header bg-dark text-light">
                              <div class="row">
                                <div class="col-md-6">
                                   <i class="fas fa-table me-1"></i>
                                    Reverted Cases
                                </div>
                                <div class="col-md-6" align="right">
                                  <button class="btn btn-primary btn-sm"><i class="fa fa-file-pdf" aria-hidden="true"></i>&nbsp;Generate PDF</button>
                                    <a target="_blank" :href="`${data.path + ['print']}`">
                                        <i class="fa fa-print mr-3 text-success  button-print"></i>                   
                                    </a>
                                    <a target="_blank" :href="`${data.path + ['download']}`" style="font-size: 16px;">
                                        <i class="fa fa-download text-success button-print" ></i>
                                    </a>
                                </div>
                              </div>                               
                            </div>
                            <!-- include case details -->
                               <Reports  :username="data.username" />
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
import Reports from '../../../components/pages/revertedReports.vue'

export default {
  data(){
    return{

 data: {
  username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
        column: 'TSO_PNO',       
        channel: '/admin/progress-reports',
        messagePath:'/admin/messages',
         action: 'print',
         path:'/api/pdfAdmin/',
      }
    }


  },

  components : {
    Footer,
    Sidebar,
    Navbar,
    // caseTable,
    Reports,
    // casesProgress
    // name:casesPrgress

  },




}
</script>
