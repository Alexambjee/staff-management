<template>
  <div >
  <!-- navbar -->
      <Navbar  :messagePath="data.messagePath"/>
      <div id="layoutSidenav">
      <Sidebar  :messagePath="data.messagePath"  :username="username" :manualPath="manualPath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">PRELIMINARY REPORT ANALYSIS</h1>
                          <div class="breadcrumb mb-4 mt-3">
                              <Breadcrumb>
                                  <BreadcrumbItem to="/Sector-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                  <BreadcrumbItem to="/Sector-manager/Preliminary-reports" class="breadcrumb-item">Preliminary Reports</BreadcrumbItem>
                                  <BreadcrumbItem :to="`/Sector-manager/Preliminary-reports/${data.caseId}`" class="breadcrumb-item active">Preliminary Report Analysis</BreadcrumbItem>
                              </Breadcrumb>
                          </div>
                  <!-- case details-->
                  <CaseDetails :case_id="data.caseId" :role="data.role" 
                  :Rstatus="data.Rstatus" :stage="data.stage"
                  :Astatus="data.Astatus" :Fstatus="data.Fstatus" 
                  :stage1="data.stage1" :username="data.username" :Path="data.LocPath" :actionTitle="data.actionTitle" :ACstatus="data.ACstatus" />

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
          Rstatus:'-2',
          Fstatus:'4',
          Astatus: '3',
          ACstatus: '11',
        
          
          actionTitle:'PRELIMINARY REPORT APPROVAL',
          username:this.$store.state.user.USER_NAME,
          role: this.$store.state.userRole,
          stage1:'sector',
          stage:'Decision',
          channel: '/Sector-manager/progress-reports',
          LocPath: '/Sector-manager/Preliminary-reports',
          messagePath:'/Sector-manager/messages',
          manualPath:'/Sector-manager/Manual-cases',

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
    console.log("Cd roles:", this.data.role);
      // if (!this.$store.state.user) {
      //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      //     window.location = '/'
      // }
      // else {
          
      // }
  }





  }
</script>
