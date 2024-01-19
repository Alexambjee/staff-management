<template>
  <div  v-if="($store.state.user) && ($store.state.userRole == 'Officer' || $store.state.userRole =='officer')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="messagePath"/>
       <div id="layoutSidenav">
        <Sidebar v-if="$store.state.user" :user="$store.state.user"  :messagePath="messagePath"  :username="username" :manualPath="manualPath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">MY CASES</h1>
                      <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/officer" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/officer/mycases" class="breadcrumb-item active">Taxpayer Response</BreadcrumbItem>
                        </Breadcrumb>
                      </div>
                      <div class="card">
                              <!-- include case table -->
                              <!-- <caseTable  :path="path" :username="username" :caseStatus="caseStatus" /> -->

                            <!-- include case table ends-->
                            <!-- response from taxpayer test -->
                                <div class="card">
                                    <!-- headers -->
                                    <div class="d-flex justify-content-around">
                                        <div class=" col-md-2 text-center ml-auto mr-5" id="headingOne">
                                            <h5 class="mb-0 shadow" style="border:1px solid orange;">
                                                <button class="btn" data-toggle="collapse" data-target="#awaiting"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                <i class="fas fa-refresh text-warning me-1"></i>
                                                    Awaiting Response
                                                <span class=" badge" style="font-size:13px; width:22px !important; height:22px !important;
                                                background:orange !important; color:#fff ;  align-items:center; text-align:center;
                                                position:absolute; border-radius:50%; margin-top:-5px;">
                                               {{ this.AwaitData.awaiting }}
                                                </span>

                                                </button>
                                            </h5>
                                        </div>

                                        <div class=" col-md-2 text-center  mr-auto ml-5" id="">
                                            <h5 class="mb-0 shadow" style="border:1px solid skyblue;">
                                                <button class="btn collapsed" data-toggle="collapse"
                                                data-target="#Response" aria-expanded="false" aria-controls="#Response">
                                                 <i class="fa fa-check text-success fa-1x me-1"></i>
                                                  Taxpayer Responded
                                                <span class=" badge" style="font-size:14px; width:22px !important; height:22px !important;
                                                background:skyblue !important; color:#fff ;  align-items:center; text-align:center;
                                                position:absolute; border-radius:50%; margin-top:-5px;">
                                                {{ this.Responsedata.responded}}</span>
                                                </button>
                                            </h5>
                                        </div>
                                        <div class="col-md-2 text-center  mr-auto ml-5" id="headingTwo">
                                            <h5 class="mb-0 shadow" style="border:1px solid red;">
                                                <button class="btn collapsed " data-toggle="collapse"
                                                data-target="#NoResponse" aria-expanded="false" aria-controls="NoResponse">
                                                 <i class="fas fa-times-circle text-danger fa-1x me-1"></i> No Taxpayer Response
                                                <span class=" badge" style="font-size:14px; width:22px !important; height:22px !important;
                                                background:red !important; color:#fff ;  align-items:center; text-align:center;
                                                position:absolute; border-radius:50%; margin-top:-5px;">
                                                {{ this.NoResponseRdata.notResponded}}
                                                </span>
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
                                                    Cases Awaiting Response
                                                </div>
                                                <!-- card-header ends -->
                                                <!-- awaiting response table -->
                                                <responseTable :action="AwaitData.action"
                                                    :caseStatus="AwaitData.caseStatus" :path="AwaitData.path"
                                                    :username="$store.state.user.USER_NAME" />
                                                <!-- awaiting response table ends -->


                                            </div>
                                        </div>
                                        <!-- awaiting taxpayer response ends-->
                                        <!--taxpayer responded -->
                                        <div class="card shadow">
                                            <div id="Response" class="collapse" aria-labelledby="Response" data-parent="#accordion">
                                              <div class="card-header bg-dark text-muted text-center">
                                                    <i class="fa fa-check text-success fa-1x me-1"></i>
                                                Taxpayer Responded
                                                </div>
                                                <!-- card header ends -->
                                                  <!-- taxpayer responded table -->
                                                    <responseTable :action="Responsedata.action"
                                                    :caseStatus="Responsedata.caseStatus" :path="Responsedata.path"
                                                    :username="$store.state.user.USER_NAME"/>
                                                <!-- taxpayer responded table ends -->
                                            </div>
                                        </div>
                                        <!-- taxpayer responded -->

                                        <!-- no taxpayer response -->
                                        <div class="card shadow">
                                            <div id="NoResponse" class="collapse" aria-labelledby="NoResponse" data-parent="#accordion">
                                             <div class="card-header bg-dark text-muted text-center">
                                                    <i class="fas fa-times-circle text-danger fa-1x me-1"></i>
                                                No Taxpayer Response
                                                </div>
                                              <!-- card header ends -->
                                                  <!-- no response table -->
                                                    <responseTable :action="NoResponseRdata.action"
                                                    :caseStatus="NoResponseRdata.caseStatus" :path="NoResponseRdata.path"
                                                    :username="$store.state.user.USER_NAME" />
                                                <!-- no response table ends-->
                                            </div>
                                        </div>
                                        <!-- no taxpayer response ends-->

                                    </div>
                                </div>
                            <!-- end panel-body -->

                        </div>

                  </div>
              </main>
            <!-- footer -->
            <Footer></Footer>
          </div>
        </div>
  </div>
    <!-- if not authorized -->
    <div v-else>
      <div class="col-md-5" style="margin:auto ">
      <div class="card shadow mt-5">
        <div class="card-body">
        <!-- error card -->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                  <span class="text-danger">Access Denied. You Don't Have Enough
                  Permissions to Access This Route!</span>
                    </div>
                </main>
          <!-- footer -->
        </div>
        <!-- error card ends -->
          </div>
        </div>
      </div>
    </div>
  <!-- if not authorized  ends-->

</template>
<script>
import Footer from '../../../components/footer.vue'
import Sidebar from '../../../components/sidebar.vue'
import Navbar from '../../../components/navbar.vue'
import responseTable from '../../../components/pages/responseTable.vue'

export default {
    props:['user'],
  data(){
    return {
          messagePath:'/Sector-manager/messages',
          channel: '/Sector-manager/progress-reports',
          manualPath: 'Sector-manager/Manual-cases',
          username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
            AwaitData: {
                caseStatus:[7],
                path:'/Sector-manager/response-from-taxpayer',
                username: '',
                action:'Yes',
                awaiting:''
            },
            Responsedata: {
                caseStatus:[2],
                path:'/Sector-manager/response-from-taxpayer/',
                username: '',
                action:'View',
                responded:''
            },
            NoResponseRdata: {
                caseStatus:[-3],
                path:'/Sector-manager/response-from-taxpayer/',
                username: '',
                action:'Invalidate',
                notResponded:''
            },


    }

  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    responseTable
  },
    async created() {
      if (!this.$store.state.user) {
        this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
        window.location = '/'
    } else {
      const [ resAwaiting,resResponded,resNotResponded] = await Promise.all([
        this.callApi('get',`/api/getOfficerTotalAwaitingResponse/${this.$store.state.user.USER_NAME}`),
        this.callApi('get',`/api/getOfficerTotalResponsed/${this.$store.state.user.USER_NAME}`),
        this.callApi('get',`/api/getOfficerTotalNotResponded/${this.$store.state.user.USER_NAME}`)
    ])
    // total awaiting response
    if(resAwaiting.status==200){
        this.AwaitData.awaiting = resAwaiting.data;
      }
    else {
        this.swr()
    }
    // total  responded
    if(resResponded.status==200){
        this.Responsedata.responded = resResponded.data;
      }
    else {
        this.swr()
    }
    // total not responded
    if(resNotResponded.status==200){
        this.NoResponseRdata.notResponded = resNotResponded.data;
      }
    else {
        this.swr()
    }
    }    
  }
  
}
</script>


