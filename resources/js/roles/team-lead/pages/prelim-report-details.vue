<template>
  <div >
  <!-- navbar -->
      <Navbar  :messagePath="data.messagePath"/>
      <div id="layoutSidenav">
      <Sidebar  :messagePath="data.messagePath" :channel="data.channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">PRELIMINARY REPORT ANALYSIS</h1>
                          <div class="breadcrumb mb-4 mt-3">
                              <Breadcrumb>
                                  <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                  <BreadcrumbItem to="/Team-lead/Preliminary-reports" class="breadcrumb-item">Preliminary Reports</BreadcrumbItem>
                                  <BreadcrumbItem :to="`/Team-lead/Prelimary-reports/${data.caseId}`" class="breadcrumb-item active">Preliminary Report Analysis</BreadcrumbItem>
                              </Breadcrumb>
                          </div>
                  <!-- case details-->
                  <CaseDetails :case_id="data.caseId" :role="data.role" 
                  :Rstatus="data.Rstatus" :stage="data.stage"
                  :Astatus="data.Astatus" :Fstatus="data.Fstatus" 
                  :username="data.username" :Path="data.LocPath" :actionTitle="data.actionTitle"/>

                  <!-- case details ends-->
                  </div>

              <!--details ends -->
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
import CaseDetails from '../../../components/pages/casedetails.vue'

export default {
data(){
  return{
      data:{
          caseId: this.$route.params.caseId,
          Rstatus:'-1',
          Fstatus:'4',
          Astatus: '2',
          LocPath:'/Team-lead/Preliminary-reports',
          username:this.$store.state.user.USER_NAME,
          role: this.$store.state.userRole,
          actionTitle:'PRELIMINARY REPORT APPROVAL',
          stage:'Decision',
          channel: '/Team-lead/progress-reports',
          messagePath:'/Team-lead/messages',
          manualPath:'/Team-lead/Manual-cases',

      },
  };
},
  components : {
      Footer,
      Sidebar,
      Navbar,
      CaseDetails

  },
  methods :{

      },
  created() {
    console.log("jhrerh");
      // if (!this.$store.state.user) {
      //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      //     window.location = '/'
      // }
      // else {
          
      // }
  }





  }
</script>
