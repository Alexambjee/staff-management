<template>
    <div >
    <!-- navbar -->
      <Navbar />
         <div id="layoutSidenav">
         <Sidebar :manualPath="manualPath" :progressPath="progressPath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">MY CASES</h1>
                          <div class="breadcrumb mb-4 mt-3">
                             <Breadcrumb>
                              <BreadcrumbItem to="/Admin" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                              <BreadcrumbItem to="/Admin/cases-progress" class="breadcrumb-item active">My cases</BreadcrumbItem>
                          </Breadcrumb>
                        </div>                   
                                  <div class="card detail-card mb-4">
                              <div class="card-header bg-dark text-light">
                                  <i class="fas fa-table me-1"></i>
                                 My Cases
                              </div>
                              <caseTable :path="ApprovedProgress" :username="username" :caseStatus="caseStatus" :action="ApprovedAction" :progressPath="progressPath" />
                          </div>                  
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
  export default {
    data(){
      return{
       
        caseStatus:[27,0,11,12,13,14,1,2,3,4,5,6,7,8,9,-1,-2,-3,-4,-5,-6,-7,-8,-12,-13,-14],
        progressPath: '/admin/cases-progress',
        username:this.$store.state.user.USER_NAME,
        role: this.$store.state.userRole,
        manualPath:'/admin/Manual-cases',
        ApprovedAction: 'Details',
        NewAction: 'Start',
        ApprovedProgress:'/Admin/cases-progress/'
  
      }
  
    },
    components : {
      Footer,
      Sidebar,
      Navbar,
      caseTable,
    },
    created() {
      
    }
  
  
  }
  </script>
  