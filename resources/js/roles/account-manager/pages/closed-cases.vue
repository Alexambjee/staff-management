<template>
    <div >
    <!-- navbar -->
      <Navbar :messagePath="messagePath"/>
         <div id="layoutSidenav">
         <Sidebar  :channel="channel" :manualPath="manualPath" :closedPath="closedPath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">MY CASES</h1>
                          <div class="breadcrumb mb-4 mt-3">
                             <Breadcrumb>
                              <BreadcrumbItem to="/Account-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                              <BreadcrumbItem to="/Account-manager/closed-cases" class="breadcrumb-item active">Closed cases</BreadcrumbItem>
                          </Breadcrumb>
                        </div>
  
         
                        <div class="card shadow">
                                <div class="card-header bg-dark text-muted ">
                                        <i class="fas fa-refresh text-warning me-1"></i>
                                    Closed Cases 
                                </div>
                                <!-- card-header ends -->
                                <caseTable :path="closedPath" :username="username" :caseStatus="caseStatus" :action="ApprovedAction" :column="column" />
                                <!-- awaiting response table ends -->

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
        caseStatus:[11],
        closedPath:'/Account-manager/closed-cases/',
        username:this.$store.state.user.USER_NAME,
                role: this.$store.state.userRole,
        column: 'AM_PNO',
        manualPath:'Account-manager/Manual-cases',
        channel: '/Account-manager/progress-reports',
        messagePath: '/Account-manager/messages',
        ApprovedAction: 'Details',
        
  
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
  