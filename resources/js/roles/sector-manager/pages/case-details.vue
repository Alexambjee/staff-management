<template>
  <div >
  <!-- navbar -->
      <Navbar />
      <div id="layoutSidenav">
      <Sidebar  :username="username" :manualPath="manualPath" :channel="channel" />
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">CASE ANALYSIS</h1>
                          <div class="breadcrumb mb-4 mt-3">
                              <Breadcrumb>
                                  <BreadcrumbItem to="/Sector-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                  <BreadcrumbItem to="/Sector-manager/my-cases" class="breadcrumb-item">My cases</BreadcrumbItem>
                                  <BreadcrumbItem :to="`/Sector-manager/my-cases/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                  
                              </Breadcrumb>
                          </div>

                  <!-- case details-->
                  <CaseDetails :case_id="data.caseId" :role="data.role" :Rstatus="data.Rstatus" :stage="data.stage"
                  :Astatus="data.Astatus" :Fstatus="data.Fstatus" :username="data.username" :Path="data.LocPath"
                  :actionTitle="data.actionTitle" :stage1="data.stage1"/>

                  <!-- case details ends-->
                  </div>

              <!--details ends -->
              </main>

              <!-- footer -->
              <Footer></Footer>
          </div>
          </div>
      </div>
      <!-- if not authorized -->
     <!-- <div v-else>
          <div class="col-md-5" style="margin:auto ">
          <div class="card shadow mt-5">
          <div class="card-body">
              <div id="layoutSidenav_content">
                  <main>
                      <div class="container-fluid px-4">
                      <span class="text-danger">Access Denied. You Don't Have Enough
                      Permissions to Access This Route!</span>
                      </div>
                  </main>
                  </div>

              </div>
          </div>
          </div>
      </div> -->
  <!-- if not authorized  ends-->

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
          stage:'prelim',
          stage1:'sector',
          LocPath:'/Sector-manager/mycases',
          channel: '/Sector-manager/progress-reports',
          manualPath:'/Sector-manager/Manual-cases',
          actionTitle:'ASSIGN CASE',
          username:this.$store.state.user.USER_NAME,
          role: this.$store.state.userRole,
          messagePath:'/Sector-manager/messages',

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
 console.log("wertyuiop[");
      // if (!this.$store.state.user) {
      //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      //     window.location ='/'
      // }
      // else {
          
      // }
  }





  }
</script>
