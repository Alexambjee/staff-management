<template>
    <div v-if="($store.state.user) && ($store.state.userRole == 'TL_PNO' || $store.state.userRole =='TL_PNO')">
    <!-- navbar -->
      <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath"/>
         <div id="layoutSidenav">
         <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="data.channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">RE-ASSIGN CASES</h1>
                          <div class="breadcrumb mb-4 mt-3">
                             <Breadcrumb>
                              <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                              <BreadcrumbItem to="/Team-lead/reassign-cases" class="breadcrumb-item active">Re-assign Cases</BreadcrumbItem>
                          </Breadcrumb>
                        </div>
  
                          <div class="card detail-card mb-4">
                              <div class="card-header bg-dark text-muted">
                                  <i class="fas fa-table me-1"></i>
                                 Re-assign Cases
                              </div>
                          <Reassign :column= "data.column" :username="data.username" :caseId="data.caseId" :path="data.path" />
                          </div>
  
                    </div>
                </main>
  
              <!-- footer -->
              <Footer></Footer>
            </div>
          </div>
           <!-- details modal -->
  
          <!-- details modal -->
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
  import Reassign from '../../../components/pages/re-assign.vue'
  export default {
    props:['user'],
    data(){
      return{
        data:{
                caseId: this.$route.params.caseId,
                column:'TL_PNO',
                officer:'',
                username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
         
                path: '/Team-lead/re-assign',
                channel: '/Team-lead/progress-reports',
                messagePath:'/Team-lead/messages',
                manualPath:'/Team-lead/Manual-cases',
  
              },
             officers:[],
  
          }
      },
  
  
      components : {
          Footer,
          Sidebar,
          Navbar,
          Reassign
    },
    created() {
       if (!this.$store.state.user) {
        this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
        window.location = '/'
        
      }
      else {
      }
    }
   
  }
  </script>
  