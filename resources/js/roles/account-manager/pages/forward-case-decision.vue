<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'Manager' || $store.state.userRole =='manager')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="messagePath" />
       <div id="layoutSidenav">
       <Sidebar v-if="$store.state.user" :user="$store.state.user" :messagePath="messagePath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">FORWAD CASE DECISION</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/manager/decision" class="breadcrumb-item active">Foward Case Decisions</BreadcrumbItem>
                        </Breadcrumb>
                      </div>

                        <div class="card shadow mb-4">
                            <div class="card-header bg-dark text-muted">
                                <i class="fas fa-table me-1"></i>
                              Approved Cases To Forward
                            </div>
                            <!-- include case table -->
                              <caseTable  :path="path" :bath="bath" :username="$store.state.user.USER_NAME" :column="column" :caseStatus="caseStatus" :action="action" />
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
      <div v-else>
      <div class="col-md-5" style="margin:auto ">
      <div class="card shadow mt-5">
        <div class="card-body">
        <!-- error card -->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                  <span class="text-danger">Access Denied. You Don't Have Enough
                  Permissions to Access This Route!</span>
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
import caseTable from '../../../components/pages/mycasestable.vue'

export default {
  props:['user'],
  data(){
    return{
      caseStatus:[5,6,9],
      path:'/api/pdf/',
      bath: '/manager/decision/',
      username:this.$store.state.user,
                role: this.$store.state.userRole,
      action: 'print',
      messagePath:'/manager/messages',
      channel: '/manager/progress-reports',
      column:'MANAGER_PNO'

    }

  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    caseTable
  },
  created() {
    if (!this.$store.state.user) {
      this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      window.location ='/'
      }
      else {
          
      }

  }



}
</script>
