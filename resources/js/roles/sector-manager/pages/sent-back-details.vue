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
                                    <BreadcrumbItem to="/Sector-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Sector-manager/sent-back-cases" class="breadcrumb-item">Reverted cases</BreadcrumbItem>
                                    <BreadcrumbItem :to="`/Sector-manager/sent-back-cases/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                
                                </Breadcrumb>
                            </div>

                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="data.role" :Rstatus="data.Rstatus"
                    :Astatus="data.Astatus" :Fstatus="data.Fstatus" :username="data.username"
                    :RFstatus= "data.RFstatus" :RMstatus="data.RMstatus"  :Mstatus="data.Mstatus" :Path="data.LocPath" :stage="data.stage" :action="data.action" :actionTitle="data.actionTitle" />

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
         
            Fstatus:'6',
            Astatus: '2',
            Mstatus:'14',

            Rstatus:'-2',
            RFstatus:'-6',
            RMstatus:'-13',
            LocPath:'/Sector-manager/sent-back-cases',
            username:this.$store.state.user.USER_NAME,
            role: this.$store.state.userRole,
            actionTitle:'ACTION',
            stage:'Decision',
            channel: '/Sector-manager/progress-reports',
            messagePath:'/Sector-manager/messages',
            manualPath: 'Sector-manager/Manual-cases',
            action:'sent-back',
            

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
        console.log(this.data.username);
        // if (!this.$store.state.user) {
        //     this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
        //     window.location = '/'
        // }
        // else {
            
        // }
    }





    }
</script>
