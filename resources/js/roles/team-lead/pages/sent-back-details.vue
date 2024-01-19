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
                                    <BreadcrumbItem to="/Team-Lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Team-Lead/sent-back-cases" class="breadcrumb-item">Reverted cases</BreadcrumbItem>
                                    <BreadcrumbItem :to="`/Team-Lead/sent-back-cases/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                
                                </Breadcrumb>
                            </div>

                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="data.role" :Rstatus="data.Rstatus"
                    :RFstatus="data.RFstatus" :RMstatus="data.RMstatus"  :Mstatus="data.Mstatus" :Astatus="data.Astatus" :Fstatus="data.Fstatus" :username="data.username" :Path="data.LocPath" :action="data.action" :stage="data.stage" :stageM="data.stageM" />
                    
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

export default {
data(){
    return{
        data:{
            caseId: this.$route.params.caseId,
        
            Fstatus:'4',
            Astatus: '2',
            Mstatus:'13',
            
            RMstatus:'-12',
            Rstatus:'-1',
            RFstatus:'-3',

            LocPath:'/Team-lead/sent-back-cases/',
            username:this.$store.state.user.USER_NAME,
            role: this.$store.state.userRole,
            action:'sent-back',
            channel: '/Team-lead/progress-reports',
            messagePath:'/Team-lead/messages',
            stage:'Decision',
            manualPath:'/Team-lead/Manual-cases',
            progressPath: 'Team-lead/cases-progress',
            ApprovedPath:'/Team-lead/contact-taxpayer/',
            // stage:'sent-back'

            


        },
    };
},
    components : {
        Footer,
        Sidebar,
        Navbar,
        CaseDetails

    },
    methods :{

        },
    created() {
        // if (!this.$store.state.user) {
        //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
        //     window.location = '/'
        // }
        // else {
            
        // }
    }





    }
</script>
