<template>
    <div >
    <!-- navbar -->
        <Navbar :messagePath="data.messagePath" />
        <div id="layoutSidenav">
        <Sidebar :channel="data.channel" :manualPath="data.manualPath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Account-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Account-manager/assessment-and-returns" class="breadcrumb-item">Assessment and Returns</BreadcrumbItem>
                                    <BreadcrumbItem :to="`/Account-manager/assessment-and-returns/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                    
                                </Breadcrumb>
                            </div>

                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="data.role" :Rstatus="data.Rstatus" :stage="data.stage"
                    :Fstatus="data.Fstatus" :Astatus="data.Astatus" :username="data.username" :actionTitle="data.actionTitle" />
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
                Rstatus:'-1',
                Fstatus:'4',
                Astatus: '5',
                stage:'AssessmentReturns',
                channel: '/Account-manager/progress-reports',
                actionTitle:'ADDITIONAL RETURNS OR AMENDMENTS FOR THIS PIN',
                username:this.$store.state.user.USER_NAME,
                role: this.$store.state.userRole,
                messagePath:'/Account-manager/messages'
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
        //     window.location ='/'
        // }
        // else {
            
        // }
    }
}
</script>
