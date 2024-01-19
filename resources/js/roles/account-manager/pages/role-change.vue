<template>
  <div >
  <!-- navbar -->
    <Navbar  :messagePath="data.messagePath"/>
       <div id="layoutSidenav">
       <Sidebar  :messagePath="data.messagePath" :channel="data.channel" :RolePath="data.RolePath" />
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">Temporary Role  Delegation</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Account-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Account-manager/Temporary-role-change"
                             class="breadcrumb-item active">Temporary Role Delegation</BreadcrumbItem>
                        </Breadcrumb>
                      </div>

                      <!-- include Cases Progress component -->
                      <!-- <CasesProgress/> -->
                      <!-- <div class="card detail-card mb-4"> -->
                            <RoleDelegation :role="data.role" :rejectAction="data.rejectAction"  :approveAction="data.approveAction"
                            :column="data.column" :approvedStatus="data.approvedStatus"
                            :rejectedStatus="data.rejectedStatus" :pendingStatus="data.pendingStatus" />
                            <!-- include case details -->
                            <!-- :action2="data.action2" -->
                               <!-- <Report :column="data.column" :username="data.username" /> -->
                            <!-- include case details -->
                        <!-- </div> -->
                      <!-- include Cases Progress component ends-->

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

import RoleDelegation from '../../../components/pages/Role-Delegation.vue'

export default {
  data(){
    return{

      data: {
        username:this.$store.state.user.USER_NAME,
                role: this.$store.state.userRole,
          column: 'AM_PNO',
          channel: '/Account-manager/progress-reports',
          messagePath: '/Account-manager/messages',
          RolePath: '/Account-manager/Temporary-role-change',
          approveAction: 'Approve',
          approvedStatus: [3],
          rejectedStatus:[-2],
        pendingStatus: [-1],
          rejectAction:'Reject'
        }
    }


  },

  components : {
    Footer,
    Sidebar,
    Navbar,
    RoleDelegation

  }
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
