<template>
    <div >
    <!-- navbar -->
        <Navbar />
        <div id="layoutSidenav">
        <Sidebar />
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Account-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Account-manager/taxpayer-response" class="breadcrumb-item">Response</BreadcrumbItem>
                                    <BreadcrumbItem :to="`0/Account-manager/taxpayer-response/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                    
                                </Breadcrumb>
                            </div>

                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="data.role" :Rstatus="data.Rstatus" :stage="data.stage"
                    :Astatus="data.Astatus" :Fstatus="data.Fstatus" :username="data.username" :Path="data.LocPath"
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
data(){
    return{
        data:{
            caseId: this.$route.params.caseId,
            Rstatus:'-1',
            Fstatus:'4',
            Astatus: '5',
            // stage:'Decision',
            stage:'TaxpayerResponse',
            LocPath:'/manager/mycases',
            channel: '/Account-manager/progress-reports',
            // actionTitle:'REPORT REVIEW AND APPROVAL',
            actionTitle:'RESPONSE FROM TAXPAYER',
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
