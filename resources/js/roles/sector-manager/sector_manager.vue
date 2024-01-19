<template>
    <div>
        <!-- navbar -->
        <Navbar />
        <div id="layoutSidenav">
            <!-- sidebar -->
            <Sidebar :messagePath="data.messagePath"   :username="username" :manualPath="manualPath" :channel="channel"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">SECTOR MANAGER DASHBOARD</h1>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Sector-manager/my-cases">
                                    <div
                                        class="card bg-primary text-white mb-4">
                                        <div
                                            class="card-body d-flex justify-content-between " >
                                          Assign Cases
                                            <div class=" badge bg-white badge-count ">
                                                <span class="text-danger">{{ this.count }}</span>
                                            </div>
                                        </div>
                                        <div class=" card-footer  d-flex align-items-center  justify-content-between">
                                            <a class="small text-secondary stretched-link" href="#" >Assign Cases</a>
                                            <div class="small text-primary">
                                                <i class="fas fa-angle-right" ></i>
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/Sector-manager/Preliminary-reports">
                                    <div class="card bg-warning text-white mb-4" >
                                        <div class=" card-body d-flex justify-content-between">
                                            Preliminary Reports
                                            <div class=" badge bg-white badge-count">
                                                <span class="text-danger">{{ this.count1 }}</span>
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
                                <router-link to="/Sector-manager/sent-back-cases">
                              <div class="card bg-danger text-white mb-4">
                              <div class="card-body d-flex justify-content-between">
                                    Reverted Cases
                                    <div class="badge bg-white badge-count"><span class="text-danger">{{ this.count2 }}</span></div>
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
                                <router-link to="Sector-manager/Report-findings">
                               <div class="card bg-greeny text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                               Report Findings
                                  <div class="badge bg-white badge-count"><span class="text-danger">{{ this.count3 }}</span></div>
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
            messagePath: '/Sector-manager/messages',
            manualPath: 'Sector-manager/Manual-cases',
            username:this.$store.state.user.USER_NAME,
             role: this.$store.state.userRole,
             column:'SM_PNO',
            RolePath:'/Sector-manager/Temporary-role-change', 
            closedPath:'/Sector-manager/closed-cases',
            progressPath: 'Sector-manager/cases-progress',
            AsStatus:[3,13]
                
            },
            messagePath: '/Sector-manager/messages',
            manualPath: 'Sector-manager/Manual-cases',
            RolePath:'/Sector-manager/Temporary-role-change', 
            closedPath:'/Sector-manager/closed-cases',
            progressPath: 'Sector-manager/cases-progress',
            count: '',
            count1: '',
            count2: '',
            count3: '',
            mcCaseStatus: [2, 13],
            tpresCaseStatus: [2],
            revCaseStatus: [-14,],
            arCaseStatus: [6], 
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
