    <template>
    <div v-if="($store.state.user) && ($store.state.userRole == 'SM_PNO' || $store.state.userRole =='SM_PNO')">
    <!-- navbar -->
        <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath" />
        <div id="layoutSidenav">
        <Sidebar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath" :channel="data.channel" />
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4"> CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/officer" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/officer/response-from-taxpayer" class="breadcrumb-item">Response From Taxpayer</BreadcrumbItem>
                                    <BreadcrumbItem to="/officer/response-from-taxpayer/:id" class="breadcrumb-item active">Report Submission</BreadcrumbItem>
                                </Breadcrumb>
                            </div>

                            <casedefault :Details="this.caseDetails" :opt="data.opt"/>
                           <div class="comments-details">
                            <!--taxpayer response -->
                                <div class="col-md-12">
                                    <TaxpayerResponse :case_id="data.caseId" />
                                </div>
                            <!--taxpayer response ends -->
                            </div>
                            <!-- report submission -->
                                    <ResposeSubmission :case_id="data.caseId" :operation="data.operation"  :Details="this.caseDetails" />
                            <!-- report submission ends -->


                    </div>

                <!--details ends -->
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
import casedefault from '../../../components/pages/cases.vue'
import ResposeSubmission from '../../../components/pages/report-submission.vue'
import TaxpayerResponse from '../../../components/pages/taxpayer-response.vue'


export default {
data(){
    return{
        data: {
            caseId:this.$route.params.caseId,
            operation: 'submit-report',
            opt: 'see',
            channel: '/Sector-manager/progress-reports',
            messagePath:'/Sector-manager/messages',
            manualPath: 'Sector-manager/Manual-cases',
            username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
            },
        caseDetails:[],

    };
},
    components : {
        Footer,
        Sidebar,
        Navbar,
        ResposeSubmission,
        TaxpayerResponse,
        casedefault

    },
    methods :{

        },
        async created(){
            if (!this.$store.state.user) {
                this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
                window.location = '/'
            } else {
                const resCase = await this.callApi('get',`/api/fetchDetails/${this.data.caseId}`)
        if(resCase.status == 200){
            this.caseDetails = resCase.data
            console.log(this.caseDetails)
        }
        else{
            this.swr('Something went wrong!')
        }
    }
            
  }
}
</script>
