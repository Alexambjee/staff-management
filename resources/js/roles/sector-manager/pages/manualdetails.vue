<template>
    <div >
    <!-- navbar -->
        <Navbar  :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
            <Sidebar  :messagePath="messagePath" :username="username" :manualPath="manualPath" :channel="channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Sector-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Sector-manager/Manual-cases" class="breadcrumb-item">My cases</BreadcrumbItem>
                                    <BreadcrumbItem to="/Sector-manager/my-cases/:caseId" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
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
            Rstatus:'-13',
            Astatus: '14',
            Fstatus: '4',
            actionTitle: 'MANUAL APPROVAL',
            stage:'Decision',
            LocPath:'/Sector-manager/Manual-cases',
            username:this.$store.state.user.USER_NAME,
            role: this.$store.state.userRole,
            messagePath:'/Sector-manager/messages',
            channel: '/Sector-manager/progress-reports',
            manualPath:'/Sector-manager/Manual-cases',


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
