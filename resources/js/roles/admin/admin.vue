<template>
    <div v-if="($store.state.user) && ($store.state.userRole == 'Admin' || $store.state.userRole == 'Super'
    || $store.state.userRole == 'administrator' || $store.state.userRole == 'Administrator')">
      <!-- navbar -->
      <Navbar v-if="$store.state.user" :user="$store.state.user " :messagePath="data.messagePath"  />
      <div id="layoutSidenav">
        <Sidebar v-if="$store.state.user" :role="data.role" :user="$store.state.user" :channel="data.channel" :manualPath="data.manualPath" :RolePath="data.RolePath"  :progressPath="data.progressPath" :closedPath="data.closedPath" :messagePath="data.messagePath"/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">ADMIN DASHBOARD</h1>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/admin/mycases">
                                    <div
                                        class="card bg-primary text-white mb-4">
                                        <div
                                            class="card-body d-flex justify-content-between " >
                                           Cases to Assign
                                            <!-- <div class=" badge bg-white badge-count ">
                                                <span class="text-danger">102</span>
                                            </div> -->
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
                                <router-link to="/admin/user-mapping">
                                    <div class="card bg-warning text-white mb-4" >
                                        <div class=" card-body d-flex justify-content-between">
                                            Uploading mapping
                                            <!-- <div class=" badge bg-white badge-count">
                                                <span class="text-danger">0</span>
                                            </div> -->
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
                            
                            <!-- <div class="col-xl-3 col-md-6">
                                <router-link to="/manager/sent-back-cases">
                              <div class="card bg-danger text-white mb-4">
                              <div class="card-body d-flex justify-content-between">
                                    Reassign cases
                                    <div class="badge bg-white badge-count"><span class="text-danger">0 </span></div>
                                    </div>
                                  <div class="card-footer d-flex align-items-center justify-content-between">
                                      <a class="small text-secondary stretched-link" href="#">View Details</a>
                                      <div class="small text-success"><i class="fas fa-angle-right"></i></div>
                                  </div>
                              </div>
                          </router-link>
                            </div> -->
                               <!-- Asign role -->

                               <div class="col-xl-3 col-md-6">
                                <router-link to="/admin/assign">
                               <div class="card bg-danger text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                 User and role Management
                                  <!-- <div class="badge bg-white badge-count"><span class="text-danger">0</span></div> -->
                                  </div>
                                  <div class="card-footer d-flex align-items-center justify-content-between">
                                      <a class="small text-white stretched-link" href="#">View Messages</a>
                                      <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                  </div>
                              </div>
                              </router-link>
                            </div>
                            <!-- decision -->
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/admin/cases-progress">
                               <div class="card bg-greeny text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                Case Progress
                                  <!-- <div class="badge bg-white badge-count"><span class="text-danger">0</span></div> -->
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
                                        <chart :column="data.column" :username="data.username" :AsStatus="data.AsStatus"  />
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
import chart from "../../components/charts/don-report";
import barChart from "../../components/charts/bar-report";
export default {
    // props: ['user', 'role'],
    
    data() {
        return {
            count: '',
            data:{
                column:['AM_PNO'],
                username: this.$store.state.user.USER_NAME,
                role:this.$store.state.userRole,
                AsStatus:[26],
                messagePath: '/admin/messages',
                manualPath: 'admin/Manual-cases',
                RolePath:'/admin/Temporary-role-change', 
                closedPath:'/admin/closed-cases',
                progressPath: 'admin/cases-progress', 
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
  
    
    // async created() {
    //     let Data = [this.data.username,this.data.column]

    //     const resCount = await this.callApi('get',`/api/getCaseCount/${[ this.AsStatus +"*"+ Data]}`)
    //     if(resCount.status===200){
    //         this.count = resCount.data
    //     }
    //     else{
    //         this.error('something went wrong')  
    //     }
 
    // }
    
}
</script>
