    <template>
    <div v-if="($store.state.user) && ($store.state.userRole == 'TSOhead' || $store.state.userRole =='TSO_PNO')">
    <!-- navbar -->
        <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
        <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="data.channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Tso-head" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Tso-head/mycases" class="breadcrumb-item">My cases</BreadcrumbItem>
                                    <BreadcrumbItem to="/Tso-head/mycases/:id" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                </Breadcrumb>
                            </div>

                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="$store.state.user.role.ROLE_NAME" :Rstatus="data.Rstatus"
                    :Astatus="data.Astatus" :username="data.username" :Path="data.LocPath" />

                    <!-- case details ends-->
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
import CaseDetails from '../../../components/pages/casedetails.vue'

export default {
    props:['user'],
data(){
    return{
        data:{
            caseId: this.$route.params.caseId,
            Rstatus:'-2',
            Astatus: '6',
            Fstatus:'4',
            LocPath:'/Tso-head/my-cases',
            username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
            messagePath:'/Tso-head/messages',
            channel: '/Tso-head/progress-reports'

        },
    };
},
    components : {
        Footer,
        Sidebar,
        Navbar,
        CaseDetails

    },
    created() {
        // if (!this.$state.state.user) {
        //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
        //     window.location ='/'
        // }
        // else {
            
        // }
    }
  


    }
</script>
