<template>
  <div >
  <!-- navbar -->
    <Navbar  :messagePath="messagePath"/>
       <div id="layoutSidenav">
        <Sidebar  :messagePath="messagePath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">REPORT FINDINGS</h1>
                      <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Team-lead/Report-findings" 
                            class="breadcrumb-item active">Report Findings </BreadcrumbItem>
                        </Breadcrumb>
                      </div>


                        <div class="card shadow mb-4">
                            <div class="card-header bg-dark text-muted">
                                <i class="fas fa-table me-1"></i>
                                Report Findings
                            </div>
                              <!-- include case table -->
                              <caseTable  :path="path" :action="action" :caseStatus="caseStatus" 
                              :username="username" :column="column" />
                            <!-- include case table ends-->

                        </div>

                  </div>
              </main>
            <!-- footer -->
            <Footer></Footer>
          </div>
        </div>
  </div>
      <!-- if not authorized -->
   
  <!-- if not authorized  ends-->

</template>
<script>
import Footer from '../../../components/footer.vue'
import Sidebar from '../../../components/sidebar.vue'
import Navbar from '../../../components/navbar.vue'
import caseTable from '../../../components/pages/caseTable.vue'

export default {
  props: ['user'],
  data(){
    return{
      caseStatus:[5],
      path:'/Team-lead/Report-findings/',
      column:'TL_PNO',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
      action:'Details',
      channel: '/Team-lead/progress-reports',
       messagePath:'/Team-lead/messages',
       manualPath:'/Team-lead/Manual-cases',
    }

  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    caseTable
  },
  // created() {
  //    if (!this.$store.state.user) {
  //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
  //     window.location = '/'
  //   }
  //   else {
  //   }
  // }



}
</script>
