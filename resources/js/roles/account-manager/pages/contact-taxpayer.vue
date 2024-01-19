<template>
  <div >
  <!-- navbar -->
    <Navbar  :messagePath="data.messagePath"/>
       <div id="layoutSidenav">
       <Sidebar  :messagePath="data.messagePath" :channel="data.channel" :manualPath="data.manualPath"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">CASE ANALYSIS</h1>
                        <div class="breadcrumb mb-4 mt-3">
                          <Breadcrumb>
                            <BreadcrumbItem to="/Account-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Account-manager/my-cases" class="breadcrumb-item">My cases</BreadcrumbItem>
                            <BreadcrumbItem :to="`/Account-manager/my-cases/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                          </Breadcrumb>
                        </div>
                      <CaseDetails :case_id="data.caseId" :role="data.role" :Rstatus="data.Rstatus" :stage="data.stage"
                      :Astatus="data.Astatus" :Fstatus="data.Fstatus" :username="data.username" :Path="data.LocPath"
                      :actionTitle="data.actionTitle" />
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
import attachments from '../../../components/pages/attachments.vue'


export default {
  props:['user','role'],
  data(){
    return{
        data:{
            stage:'TaxpayerContact',
            username:this.$store.state.user.USER_NAME,
            role: this.$store.state.userRole,
            messagePath: '/Account-manager/messages',
            channel: '/officer/progress-reports',
            actionTitle:'CONTACT TAXPAYER',
            manualPath:'/Account-manager/Manual-cases',

        },
        

    }

  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    CaseDetails,
    attachments
  },
  methods:{

      
  },
    async created() {
        // if (!this.$store.state.user) {
        //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
        //     window.location = '/'
        // }
        // else {
        
    // }      
     
  }




}
</script>
