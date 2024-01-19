    <template>
    <div >
    <!-- navbar -->
        <Navbar  :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
        <Sidebar  :channel="data.channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Team-lead/my-cases" class="breadcrumb-item">My cases</BreadcrumbItem>
                                    <BreadcrumbItem to="/Team-lead/my-cases/:caseId" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                </Breadcrumb>
                            </div>

                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="data.role" :Rstatus="data.Rstatus" :stage="data.stage"  :stage1="data.stage1"
                    :Astatus="data.Astatus" :FAstatus="data.FAstatus" :Fstatus="data.Fstatus" :username="data.username" :Path="data.LocPath"
                    :actionTitle="data.actionTitle" />
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
    props:['user'],
data(){
    return{
        data:{
            caseId: this.$route.params.caseId,
            Rstatus:'-1',
            Astatus: '2',
            Fstatus: '4',
            actionTitle: 'REPORT APPROVAL',
            stage:'Decision',
            LocPath:'/Team-lead/my-cases',
            username:this.$store.state.user.USER_NAME,
             role: this.$store.state.userRole,
            messagePath:'/Team-lead/messages',
            channel: '/Team-lead/progress-reports',
            manualPath:'/Team-lead/Manual-cases',
       

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
        
    }
  


    }
</script>
