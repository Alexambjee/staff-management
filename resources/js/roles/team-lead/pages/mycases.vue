<template>
  <div >
  <!-- navbar -->
    <Navbar :messagePath="messagePath" />
       <div id="layoutSidenav">
       <Sidebar :channel="data.channel" :manualPath="manualPath"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">MY CASES</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/Team-lead" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/Team-lead/my-cases" class="breadcrumb-item active">My cases</BreadcrumbItem>
                        </Breadcrumb>
                      </div>
                      <div class="card">
                        <!-- headers -->
                        <div class="d-flex justify-content-around">
                            <div class=" col-md-2 text-center ml-auto mr-5" id="headingOne">
                                <h5 class="mb-0 shadow" style="border:1px solid orange;">
                                    <button class="btn" data-toggle="collapse" data-target="#awaiting"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-refresh text-warning me-1"></i>
                                        Assigned Cases
                                    <span class=" badge" style="font-size:13px; width:22px !important; height:22px !important;
                                    background:orange !important; color:#fff ;  align-items:center; text-align:center;
                                    position:absolute; border-radius:50%; margin-top:-5px;">
                                   1
                                    </span>

                                    </button>
                                </h5>
                            </div>

                            <div class=" col-md-2 text-center  mr-auto ml-5" id="">
                                <h5 class="mb-0 shadow" style="border:1px solid skyblue;">
                                    <button class="btn collapsed" data-toggle="collapse"
                                    data-target="#Response" aria-expanded="false" aria-controls="#Response">
                                     <i class="fa fa-check text-success fa-1x me-1"></i>
                                      Approved Cases
                                    <span class=" badge" style="font-size:14px; width:22px !important; height:22px !important;
                                    background:skyblue !important; color:#fff ;  align-items:center; text-align:center;
                                    position:absolute; border-radius:50%; margin-top:-5px;">
                                    1</span>
                                    </button>
                                </h5>
                            </div>
                            
                        </div>
                        <!-- headers ends -->
                        <div id="accordion" class="col-lg-12 mt-3" style="width:100%">
                            <!-- awaiting taxpayer response -->
                            <div class="card shadow">
                                <div id="awaiting" class="collapse" aria-labelledby="awaiting" data-parent="#accordion">

                                    <div class="card-header bg-dark text-muted ">
                                            <i class="fas fa-refresh text-warning me-1"></i>
                                       Assigned Cases 
                                    </div>
                                    <!-- card-header ends -->
                                    <caseTable :path="path" :username="username" 
                                    :caseStatus="caseStatus" :action="NewAction" :column="column" />
                                    <!-- awaiting response table ends -->
                                </div>
                            </div>
                            <!-- awaiting taxpayer response ends-->
                            <!--taxpayer responded -->
                            <div class="card shadow">
                                <div id="Response" class="collapse" aria-labelledby="Response" data-parent="#accordion">
                                  <div class="card-header bg-dark text-muted text-center">
                                        <i class="fa fa-check text-success fa-1x me-1"></i>
                                    Approved Cases
                                    </div>
                                    <!-- card header ends -->
                                      <!-- taxpayer responded table -->
                                      <caseTable :path="ApprovedPath" :username="username" 
                                      :caseStatus="caseStatusA" :action="ApprovedAction" :column="column" />

                                    <!-- taxpayer responded table ends -->
                                </div>
                            </div>
                            <!-- taxpayer responded -->
                        </div>
                    </div>
                  </div>
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
import caseTable from '../../../components/pages/caseTable.vue'

export default {
  props:['user'],
  data(){
    return{
      data: {
        // channel: '/chief-manager/progress-reports',
      },
      caseStatus:[1],
      caseStatusA:[3],
      path:'/Team-lead/my-cases/',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
      column: 'TL_PNO',
      action:'Details',
      // messagePath: '/manager/messages'
      manualPath:'/Team-lead/Manual-cases',
      channel: '/Team-lead/progress-reports',
      messagePath: '/Team-lead/messages',
      progressPath: 'Team-lead/cases-progress',
      ApprovedAction: 'Details',
      NewAction: 'Start',
      ApprovedPath:'/Team-lead/contact-taxpayer/'
    }
  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    caseTable,
  },
  created() {
   
  }



}
</script>
