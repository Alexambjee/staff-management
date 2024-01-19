<template>
    <div>
        <!-- navbar -->
        <Navbar :messagePath="data.messagePath"/>
        <div id="layoutSidenav">
            <!-- sidebar -->
            <Sidebar :manualPath="data.manualPath" :RolePath="data.RolePath"  :progressPath="data.progressPath" :closedPath="data.closedPath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">TEAM LEAD DASHBOARD</h1>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Team-lead/Manual-cases">
                                    <div
                                        class="card bg-primary text-white mb-4">
                                        <div
                                            class="card-body d-flex justify-content-between " >
                                            Manual Cases
                                            <div class=" badge bg-white badge-count ">
                                                <span class="text-danger">{{ this.data.count }}</span>
                                            </div>
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
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Team-lead/Preliminary-reports">
                                    <div class="card bg-warning text-white mb-4" >
                                        <div class=" card-body d-flex justify-content-between">
                                            Preliminary Reports
                                            <div class=" badge bg-white badge-count">
                                                <span class="text-danger">{{ this.data.count1 }}</span>
                                            </div>
                                        </div>
                                        <div class=" card-footer d-flex align-items-center justify-content-between ">
                                            <a class="small  text-secondary stretched-link "
                                                href="#">View Details</a>
                                            <div class="small text-warning">
                                                <i class="fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                            
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Team-lead/sent-back-cases">
                              <div class="card bg-danger text-white mb-4">
                              <div class="card-body d-flex justify-content-between">
                                    Reverted cases
                                    <div class="badge bg-white badge-count"><span class="text-danger">{{ this.data.count2 }}</span></div>
                                    </div>
                                  <div class="card-footer d-flex align-items-center justify-content-between">
                                      <a class="small text-secondary stretched-link" href="#">View Details</a>
                                      <div class="small text-success"><i class="fas fa-angle-right"></i></div>
                                  </div>
                              </div>
                          </router-link>
                            </div>
                               
                            <!-- decision -->
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Team-lead/Report-findings">
                               <div class="card bg-greeny text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                 Report Findings
                                  <div class="badge bg-white badge-count"><span class="text-danger">{{ this.data.count3 }}</span></div>
                                  </div>
                                  <div class="card-footer d-flex align-items-center justify-content-between">
                                      <a class="small text-white stretched-link" href="#">View Messages</a>
                                      <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                  </div>
                              </div>
                              </router-link>

                            </div>
                            <!-- decision ends-->

                        
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
                                    
                                        <chart :column="data.column" :username="data.username" :AsStatus="data.AsStatus" />
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
      <!-- if not authorized -->
      
    <!-- if not authorized  ends-->
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
            
            data:{
                count: '',
                count1: '',
                count2: '',
                count3: '',
                mcCaseStatus: [1,3],
                tpresCaseStatus: [1],
                revCaseStatus: [-2,-6,-13],
                arCaseStatus: [5],
                path:'/Team-lead',
                manualPath:'/Team-lead/Manual-cases',
                channel: '/Team-lead/progress-reports',
                messagePath: '/Team-lead/messages',
                progressPath: 'Team-lead/cases-progress',
                ApprovedPath:'/Team-lead/contact-taxpayer/',
                username:this.$store.state.user.USER_NAME,
                role: this.$store.state.userRole,
                column:'TL_PNO',
                AsStatus:'1', 
            },
    // path:'/Team-lead',
    // manualPath:'/Team-lead/Manual-cases',
    //   channel: '/Team-lead/progress-reports',
    //   messagePath: '/Team-lead/messages',
    //   progressPath: 'Team-lead/cases-progress',
    //   ApprovedPath:'/Team-lead/contact-taxpayer/',
    //   username:this.$store.state.user,
    //   role: this.$store.state.userRole,
    //   column:'TL_PNO',
    //   AsStatus:'1',

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
        

        const resCount = await this.callApi('get',`/api/getCaseCount/${[ this.data.mcCaseStatus +"*"+ Data]}`)
        const resCount1 = await this.callApi('get',`/api/getCaseCount/${[ this.data.tpresCaseStatus +"*"+ Data]}`)
        const resCount2 = await this.callApi('get',`/api/getCaseCount/${[ this.data.revCaseStatus +"*"+ Data]}`)
        const resCount3 = await this.callApi('get',`/api/getCaseCount/${[ this.data.arCaseStatus +"*"+ Data]}`)
        console.log("Counts are here!:", resCount, resCount1, resCount2, resCount3);

        if(resCount.status===200 && resCount1.status===200 && resCount2.status===200 && resCount3.status===200){
            this.data.count = resCount.data
            this.data.count1 = resCount1.data
            this.data.count2 = resCount2.data
            this.data.count3 = resCount3.data
        }
        else{
            this.error('something went wrong')  
        }
    }
    
}
</script>
