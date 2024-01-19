<template>
    <div>
        <!-- navbar -->
        <Navbar  :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
            <!-- sidebar -->
            <Sidebar  :manualPath="data.manualPath" :RolePath="data.RolePath"  :progressPath="data.progressPath" :closedPath="data.closedPath" :messagePath="data.messagePath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">ACCOUNT MANAGER DASHBOARD</h1>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Account-manager/my-cases">
                                    <div
                                        class="card bg-primary text-white mb-4">
                                        <div
                                            class="card-body d-flex justify-content-between " >
                                            My Cases
                                            <span class=" badge" style="font-size:14px; width:22px !important; height:22px !important;
                                            background:#fff !important; color:#348fe2 ; display: flex; justify-content: center; align-items:center; text-align:center;
                                            border-radius:50%; padding: auto;">
                                                <Totals :CaseStatus="caseStatus" :Cluster="unCluster" :Condition="condition1"
                                                :Column="column" />
                                                {{ this.count }}
                                            </span>                                        
                                        </div>
                                        <div class=" card-footer  d-flex align-items-center  justify-content-between">
                                            <a class="small text-secondary stretched-link" href="#" >View Details</a>
                                            <div class="small text-primary">
                                                <i class="fas fa-angle-right" ></i>
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                             <!-- Taxpayer Response -->
                            <div class="col-xl-3 col-md-6">
                                <router-link to="Account-manager/taxpayer-response">
                               <div class="card bg-greeny text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                 Taxpayer Response
                                 <span class=" badge" style="font-size:14px; width:22px !important; height:22px !important;
                                 background:#fff !important; color:#3ac04b ; display: flex; justify-content: center; align-items:center; text-align:center;
                                 border-radius:50%; padding: auto;">
                                     <Totals :CaseStatus="caseStatus" :Cluster="unCluster" :Condition="condition1"
                                     :Column="column" />
                                     {{ this.count1 }}
                                 </span>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                            <!-- decision ends-->

                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Account-manager/sent-back-cases">
                                    <div class="card bg-danger text-white mb-4" >
                                        <div class=" card-body d-flex justify-content-between">
                                          Reverted Cases
                                          <span class=" badge" style="font-size:14px; width:22px !important; height:22px !important;
                                          background:#fff !important; color:#f95b57 ; display: flex; justify-content: center; align-items:center; text-align:center;
                                          border-radius:50%; padding: auto;">
                                              <Totals :CaseStatus="caseStatus" :Cluster="unCluster" :Condition="condition1"
                                              :Column="column" />
                                              {{ this.count2 }}
                                          </span>
                                        </div>
                                        <div class=" card-footer d-flex align-items-center justify-content-between ">
                                            <a class="small  text-secondary stretched-link "
                                                href="#">View Details</a>
                                            <div class="small text-danger">
                                                <i class="fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                            
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Account-manager/assessment-and-returns">
                              <div class="card bg-warning text-white mb-4">
                              <div class="card-body d-flex justify-content-between">
                                    Assessment and Returns
                                    <span class=" badge" style="font-size:14px; width:22px !important; height:22px !important;
                                    background:#fff !important; color:#f59c1a ; display: flex; justify-content: center; align-items:center; text-align:center;
                                    border-radius:50%; padding: auto;">
                                        <Totals :CaseStatus="caseStatus" :Cluster="unCluster" :Condition="condition1"
                                        :Column="column" />
                                        {{ this.count3 }}
                                    </span>
                                    </div>
                                  <div class="card-footer d-flex align-items-center justify-content-between">
                                      <a class="small text-secondary stretched-link" href="#">View Details</a>
                                      <div class="small text-success"><i class="fas fa-angle-right"></i></div>
                                  </div>
                              </div>
                          </router-link>
                            </div>
                               
                           
                        
                        </div>
                        <!-- row ends/ -->
                          
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                       Progress of my Cases
                                    </div>
                                    <div class="card-body">
                                        <chart :column="data.column" :username="data.username" :AsStatus="data.AsStatus"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                         Revenue Amount 
                                    </div>
                                    <div class="card-body">
                                        <barChart :column="data.column" :username="data.username"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

         
        </main>


        <!-- footer -->
        <Footer></Footer>
      </div>
    </div>
    <!-- home test ends-->
  </div>
   
</template>
<script>
import Footer from "../../components/footer.vue";
import Sidebar from "../../components/sidebar.vue";
import Navbar from "../../components/navbar.vue";
// charts
import chart from "../../components/charts/chart";
import barChart from "../../components/charts/bar-chart";
export default {
    // props: ['user', 'role'],
    
    data() {
        return {
            count: '',
            count1: '',
            count2: '',
            count3: '',
            mcCaseStatus: [0,3],
            tpresCaseStatus: [20],
            revCaseStatus: [-1,-3,-5,-12],
            arCaseStatus: [9],
            data: {
                messagePath: '/Account-manager/messages',
                manualPath: 'Account-manager/Manual-cases',
               
                username:this.$store.state.user.USER_NAME,
                role: this.$store.state.userRole,
                column:'AM_PNO',
                RolePath:'/Account-manager/Temporary-role-change', 
                closedPath:'/Account-manager/closed-cases',
                progressPath: 'Account-manager/cases-progress',
                AsStatus:'0',
            },
     }
  },
  components: {
    Footer,
    Sidebar,
    Navbar,
    chart,
    barChart,
  },
  async created() {
      
        let Data = [this.data.username,this.data.column]

        const resCount = await this.callApi('get',`/api/getCaseCount/${[ this.mcCaseStatus +"*"+ Data]}`)
        const resCount1 = await this.callApi('get',`/api/getCaseCount/${[ this.tpresCaseStatus +"*"+ Data]}`)
        const resCount2 = await this.callApi('get',`/api/getCaseCount/${[ this.revCaseStatus +"*"+ Data]}`)
        const resCount3 = await this.callApi('get',`/api/getCaseCount/${[ this.arCaseStatus +"*"+ Data]}`)
        console.log("Counts are here!:", resCount, resCount1, resCount2, resCount3);

        if(resCount.status===200 && resCount1.status===200 && resCount2.status===200 && resCount3.status===200){
            this.count = resCount.data
            this.count1 = resCount1.data
            this.count2 = resCount2.data
            this.count3 = resCount3.data
        }
        else{
            this.error('something went wrong')  
        }
    }   
}
</script>
