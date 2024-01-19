<template>
  <div >
  <!-- navbar -->
    <Navbar  :messagePath="messagePath"/>
       <div id="layoutSidenav">
        <Sidebar  :messagePath="messagePath" :username="username" :manualPath="manualPath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">MY CASES</h1>
                      <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Tso-head" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Tso-head/my-cases" class="breadcrumb-item active">Cases to Assign </BreadcrumbItem>
                        </Breadcrumb>
                      </div>


                        <div class="card shadow mb-4">
                            <div class="card-header bg-dark text-muted">
                                <i class="fas fa-table me-1"></i>
                               Cases to Assign 
                            </div>
                              <!-- include case table -->
                              <caseTable  :path="path" :action="action" :caseStatus="caseStatus" :username="username" :column="column" />
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
    
      caseStatus:[0,1,2,3,4,5,6,7,9,12,13,14],
      path:'/Tso-head/my-cases/',
      column:'TSO_PNO',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
      action:'Assign',
      channel: '/Tso-head/progress-reports',
       messagePath:'/Tso-head/messages',
       manualPath:'/Tso-head/Manual-cases',
       path:'/Tso-head/Manual-cases',
      

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
