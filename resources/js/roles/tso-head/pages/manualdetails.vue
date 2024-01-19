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
                                    <BreadcrumbItem to="/Tso-head" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Tso-head/Manual-cases" class="breadcrumb-item">My cases</BreadcrumbItem>
                                    <BreadcrumbItem to="/Tso-head/my-cases/:caseId" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
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
import CaseDetails from '../../../components/pages/manualcasedetails.vue'

export default {
    props:['user'],
data(){
    return{
        data:{
            caseId: this.$route.params.caseId,
            Rstatus:'-14',
            Astatus: '0',
            Fstatus: '4',
            actionTitle: 'MANUAL APPROVAL',
            stage:'Decision',
            LocPath:'/Tso-head/Manual-cases',
            username:this.$store.state.user.USER_NAME,
            role: this.$store.state.userRole,
            messagePath:'/Tso-head/messages',
            channel: '/Tso-head/progress-reports',
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
