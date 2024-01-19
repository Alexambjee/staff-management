    <template>
    <div  v-if="($store.state.user) && ($store.state.userRole == 'Manager' || $store.state.userRole =='manager')">
    <!-- navbar -->
        <Navbar  v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
        <Sidebar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath" :channel="data.channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">FORWARD CASE DECISIONS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/manager/decision" class="breadcrumb-item">Forward Case Decision</BreadcrumbItem>
                                    <BreadcrumbItem to="/manager/decision/:caseId" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                </Breadcrumb>
                            </div>
                            <!-- case details -->
                            <div class="card-body">
                                <casedefault :Details="this.caseDetails"/>
                                <div class="comments-details">
                                    <div class="row">
                                    <!-- comments -->
                                        <div class="col-lg-6 col-md-12">
                                            <comments :case_id="data.caseId"/>
                                        </div>
                                    <!-- comments ends -->
                                    <!--taxpayer response -->
                                        <div class="col-lg-6 col-md-12">
                                            <TaxpayerResponse :case_id="data.caseId"  />
                                        </div>
                                    <!--taxpayer response ends -->
                                    </div>
                                </div>
                            </div>
                            <!-- case details ends-->
                            <!-- report submission -->
                            <!-- report submission -->
                            <ResposeSubmission :case_id="data.caseId" :operation="data.operation"/>
                        <!-- report submission ends -->
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
// import officerCaseDetails from '../../../components/pages/officerCaseDetails.vue'
import ResposeSubmission from '../../../components/pages/report-submission.vue'
import casedefault from '../../../components/pages/cases.vue'
import TaxpayerResponse from '../../../components/pages/taxpayer-response.vue'
import comments from '../../../components/pages/comments.vue'


// import attachments from '../../../components/pages//attachments.vue'
export default {
    data() {
        return {
            data: {
                caseId: this.$route.params.caseId,
                operation: 'Forward-decision',
                channel: '/manager/progress-reports',
                 messagePath:'/manager/messages'


            },
            caseDetails: [],
        }
    },
    components: {
        Footer,
        Sidebar,
        Navbar,
        casedefault,
        TaxpayerResponse,
        ResposeSubmission,
        comments
    },
    async created() {
        if (!this.$store.state.user) {
            this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
            window.location ='/'
            
        }
        else {
            const resCase = await this.callApi('get', `/api/fetchDetails/${this.data.caseId}`)
            if (resCase.status == 200) {
                this.caseDetails = resCase.data
            }
            else {
                this.swr('Something went wrong!')
            }
            
        }

        
    }


}
</script>
