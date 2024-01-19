<template>
    <div >
    <!-- navbar -->
        <Navbar  :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
        <Sidebar  :messagePath="data.messagePath" :username="username" :manualPath="manualPath" :channel="channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Sector-manager" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Sector-manager/Preliminary-reports" class="breadcrumb-item">Report findings</BreadcrumbItem>
                                    <BreadcrumbItem :to="`/Sector-manager/Preliminary-reports/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                </Breadcrumb>
                            </div>
                    <!-- case details-->
                    <CaseDetails :case_id="data.caseId" :role="data.role" 
                    :Rstatus="data.Rstatus" :stage="data.stage"
                    :Astatus="data.Astatus" :Fstatus="data.Fstatus" :title="data.title"
                    :stage1="data.stage1" :username="data.username" :Path="data.LocPath" :actionTitle="data.actionTitle" />

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
            Rstatus:'-6',
            Fstatus:'4',
            Astatus: '9',
            LocPath:'/Sector-manager/Report-findings',
             stage1:'sector',
            actionTitle:'REPORT FINDINGS APPROVAL',
            username:this.$store.state.user.USER_NAME,
             role: this.$store.state.userRole,
            action:'Details',
            stage:'Decision',
            channel: '/Sector-manager/progress-reports',
            messagePath:'/Sector-manager/messages',
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
