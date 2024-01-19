<template>
    <div>
        <!-- navbar -->
        <Navbar />
        <div id="layoutSidenav">
            <!-- sidebar -->
            <Sidebar/>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">SUPER ADMIN DASHBOARD</h1>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <router-link to="/manager/mycases">
                                    <div
                                        class="card bg-primary text-white mb-4">
                                        <div
                                            class="card-body d-flex justify-content-between " >
                                            My Cases
                                            <div class=" badge bg-white badge-count ">
                                                <span class="text-danger">0</span>
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
                                <router-link to="/manager/reassign-cases">
                                    <div class="card bg-warning text-white mb-4" >
                                        <div class=" card-body d-flex justify-content-between">
                                            Re-assign Cases
                                            <div class=" badge bg-white badge-count">
                                                <span class="text-danger">0</span>
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
                                <router-link to="/manager/sent-back-cases">
                              <div class="card bg-danger text-white mb-4">
                              <div class="card-body d-flex justify-content-between">
                                    Reverted cases
                                    <div class="badge bg-white badge-count"><span class="text-danger">0 </span></div>
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
                                <router-link to="manager/decision">
                               <div class="card bg-greeny text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                 Decision Issued
                                  <div class="badge bg-white badge-count"><span class="text-danger">0</span></div>
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
                                        <chart :column="data.column" :username="data.username"  />
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
        
   
 
    }
    
}
</script>
