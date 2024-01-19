<template>
  <div >
  <!-- navbar -->
    <Navbar :messagePath="messagePath"/>
       <div id="layoutSidenav">
       <Sidebar :messagePath="messagePath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">SENT BACK CASES</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                              <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                              <BreadcrumbItem to="/Team-lead/sent-back-cases" class="breadcrumb-item active">Sent Back Cases </BreadcrumbItem>
                          </Breadcrumb>
                      </div>
                      
                      <!-- reverted cases -->
                      <RevertedCases :path="SentbackPath" :username="username" 
                      :caseStatus="caseStatus"  :caseStatusM="caseStatusM" :caseStatusF="caseStatusF" :ApprovedAction="ApprovedAction" :column="column" />
                     

                  </div>
              </main>

            <!-- footer -->
            <Footer></Footer>
          </div>
        </div>
    </div>

   

</template>
<script>
import Footer from '../../../components/footer.vue'
import Sidebar from '../../../components/sidebar.vue'
import Navbar from '../../../components/navbar.vue'
import caseTable from '../../../components/pages/caseTable.vue'
import RevertedCases from '../../../components/pages/reverted-cases.vue'

export default {
  data(){
    return{
      caseStatus:[-2],
      caseStatusF:[-6],
      caseStatusM:[-13],
      SentbackPath:'/Team-lead/sent-back-cases/',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
      ApprovedAction: 'Action',
      column: 'TL_PNO',
      channel: '/Team-lead/progress-reports',
      messagePath: '/Team-lead/messages',
      progressPath: 'Team-lead/cases-progress',
      ApprovedPath:'/Team-lead/contact-taxpayer/',
      manualPath:'/Team-lead/Manual-cases',
    }


  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    RevertedCases
  },
  created() {
    
    // if (!this.$store.state.user) {
    //   this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
    //   window.location ='/'
    // }
    // else {
      
    // }
      
  }



}
</script>
