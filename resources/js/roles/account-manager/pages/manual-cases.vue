<template>
  <div >
  <!-- navbar -->
    <Navbar  :messagePath="data.messagePath"/>
       <div id="layoutSidenav">
       <Sidebar :channel="data.channel" :manualPath="data.manualPath"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">MANUAL CASES</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Account-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Account-manager/Manual-cases" class="breadcrumb-item active">Manual Cases</BreadcrumbItem>
                        </Breadcrumb>
                      </div>
                        <div class="card detail-card mb-4">
                         
                            <manualTable :path="data.path" :username="data.username" :caseStatus="data.caseStatus" :action="data.action" :column="data.column"/>
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


</template>
<script>
import Footer from '../../../components/footer.vue'
import Sidebar from '../../../components/sidebar.vue'
import Navbar from '../../../components/navbar.vue'
import manualTable from '../../../components/pages/manualTable.vue'
export default {
  data(){
    return{
        data:{
              column:'AM_PNO',
              officer: '',
              manualPath:'/Account-manager/Manual-cases',
              username:this.$store.state.user.USER_NAME,
              role: this.$store.state.userRole,
              path:'/Account-manager/Manual-cases/',
              channel: '/manager/progress-reports',
              messagePath: '/Account-manager/messages',
              // caseStatus:[13,14,12,18],
              caseStatus: [12],
              action:'Details',
            },
        }
    },


    components : {
        Footer,
        Sidebar,
        Navbar,
        manualTable
    },
  created() {
    //   if (!this.$store.state.user) {
    //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
    //     window.location = '/'
    // }
    // else {
    // }
  }
}
</script>
