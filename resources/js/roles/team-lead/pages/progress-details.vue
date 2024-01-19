<template>
    <div >
    <!-- navbar -->
        <Navbar />
        <div id="layoutSidenav">
        <Sidebar :progressPath="data.progressPath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">CASE ANALYSIS</h1>
                            <div class="breadcrumb mb-4 mt-3">
                                <Breadcrumb>
                                    <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                                    <BreadcrumbItem to="/Team-lead/cases-progress" class="breadcrumb-item">Progress of cases</BreadcrumbItem>
                                    <BreadcrumbItem :to="`/Team-lead/cases-progress/${data.caseId}`" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                                    
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
        <!-- if not authorized -->
       <!-- <div v-else>
            <div class="col-md-5" style="margin:auto ">
            <div class="card shadow mt-5">
            <div class="card-body">
                <div id="layoutSidenav_content">
                    <main>
                        <div class="container-fluid px-4">
                        <span class="text-danger">Access Denied. You Don't Have Enough
                        Permissions to Access This Route!</span>
                        </div>
                    </main>
                    </div>

                </div>
            </div>
            </div>
        </div> -->
    <!-- if not authorized  ends-->

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
            stage:'Details',
            LocPath:'/Team-lead/mycases',
            channel: '/Team-lead/progress-reports',
            actionTitle:'PRELIMINARY REPORT FINDINGS',
            progressPath: '/Team-lead/cases-progress',

            username:this.$store.state.user,
                role: this.$store.state.userRole,
            
            messagePath:'/Team-lead/messages'

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
