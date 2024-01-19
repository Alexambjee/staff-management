<template>
    <div >
    <!-- navbar -->
      <Navbar :messagePath="messagePath"/>
         <div id="layoutSidenav">
         <Sidebar  :channel="channel" :manualPath="manualPath" :progressPath="progressPath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">MY CASES</h1>
                          <div class="breadcrumb mb-4 mt-3">
                             <Breadcrumb>
                              <BreadcrumbItem to="/Sector-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                              <BreadcrumbItem to="/Sector-manager/cases-progress" class="breadcrumb-item active">My cases</BreadcrumbItem>
                          </Breadcrumb>
                        </div>                   
                                  <div class="card detail-card mb-4">
                              <div class="card-header bg-dark text-light">
                                  <i class="fas fa-table me-1"></i>
                                 My Cases
                              </div>
                              <!-- include case details -->
                              <!-- <caseTable :username="username" :caseStatus="caseStatus" :action="ApprovedAction" :column="column" :progressPath="progressPath"/> -->
                              <caseTable :path="ApprovedProgress" :username="username" :caseStatus="caseStatus" :action="ApprovedAction" :column="column" :progressPath="progressPath" />


                              <!-- include case details -->
                          </div>
                              <!-- taxpayer responded -->                     
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
        caseStatus:[1,2,3,4,5,6,7,8,9,11,12,13,14,-1,-2,-3,-5,-6,-12,-13],
        progressPath: '/Sector-manager/cases-progress',
        username:this.$store.state.user.USER_NAME,
        role: this.$store.state.userRole,
        column: 'SM_PNO',
        manualPath:'/Sector-manager/Manual-cases',
        channel: '/Sector-manager/progress-reports',
        messagePath: '/Sector-manager/messages',
        ApprovedAction: 'Details',
        NewAction: 'Start',
        ApprovedProgress:'/Sector-manager/cases-progress/'
  
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
  