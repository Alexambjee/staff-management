    <template>
    <div >
    <!-- navbar -->
        <Navbar  :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
        <Sidebar  :messagePath="data.messagePath" :channel="data.channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Account-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Account-manager/sent-back-cases" class="breadcrumb-item">Reverted cases</BreadcrumbItem>
                                    <BreadcrumbItem :to="`/Account-manager/sent-back-cases/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                </Breadcrumb>
                            </div>
                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="data.role" 
                    :Rstatus="data.Rstatus" :stage="data.stage" :reverted="data.reverted"
                    :Astatus="data.Astatus" :Fstatus="data.Fstatus" :title="data.title"
                    :username="data.username" :Path="data.LocPath" :actionTitle="data.actionTitle" />

                    <!-- case details ends-->
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
import SentBack from '../../../roles/account-manager/pages/sent-back.vue'

export default {
    props: ['caseStatus'],
data(){
    return{
        data:{
            caseId: this.$route.params.caseId,
            Rstatus:'12',
            Fstatus:'5',
            Astatus: '1',
            LocPath:'',
            reverted: [-1,-3,-5],
            username:this.$store.state.user.USER_NAME,
            role: this.$store.state.userRole,
            actionTitle:'ACTION',
            action:'Details',
            stage:'sent-back',
            channel: '/Account-manager/progress-reports',
            messagePath:'/Account-manager/messages'

        },
    };
},
    components : {
        Footer,
        Sidebar,
        Navbar,
        CaseDetails,
        SentBack

    },
    methods :{

        },
    created() {

        // console.log("sent bck revt:", this.caseStatus);
        // if (!this.$store.state.user) {
        //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
        //     window.location = '/'
        // }
        // else {
            
        // }
    }





    }
</script>
