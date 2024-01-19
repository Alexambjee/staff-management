<template>
    <div id="layoutSidenav_nav" >
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div  class="sb-sidenav-menu-heading">Dashboard</div>
                    <!-- test pages-->

                    <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#messages" aria-expanded="false"
                        aria-controls="messages" >
                            <div class="sb-nav-link-icon"><i class="fas fa-message"></i></div>
                            Profiles 
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a> -->
                        <!-- <div class="collapse" id="messages" aria-labelledby="headingOne" 
                        data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                               <router-link to="/Account-manager"  class="nav-link ">
                                    <span> Account Manager </span>
                                </router-link>
                               <router-link to="/Team-lead"  class="nav-link ">
                                    <span>Team Lead </span>
                                </router-link>
                               <router-link to="/Sector-manager"  class="nav-link ">
                                    <span>Sector Manager</span>
                                </router-link>
                               <router-link to="/Tso-head"  class="nav-link ">
                                    <span>TSO Head </span>
                                </router-link>
                               <router-link to="/Admin"  class="nav-link ">
                                    <span>Admin </span>
                                </router-link>
                               <router-link to="/super-admin"  class="nav-link ">
                                    <span>Super Admin </span>
                                </router-link>
                            </nav>
                        </div> -->
                    <!-- test pages ends-->

                    <!-- messages begins-->
                    <div v-if="$store.state.userRole != 'Risk'" >
                        <router-link :to="`${data.manualPath}`" class="nav-link text-center">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            <span class="">Manual Cases </span>
                                 <!-- <span class="badge badge-light"
                                style=" margin-left:10px !important;">
                                {{data.manualTotal}}</span> -->
                        </router-link>
                    </div>
                    <!-- Message ends -->
                        <!--  <router-link to="/"  class="nav-link d-flex justify-content-between align-items-center" v-if="$store.state.userRole == 'SectorManager'">
                        <span class="d-flex">
                            Rollback
                        </span>
                          <span class="badge badge-light" 
                          style=" margin-left:10px !important;">0</span>
                    </router-link>-->
                    <!-- messages begins-->
                    <!--<div>
                        <router-link   :to="`${messagePath}`"  v-if="$store.state.userRole != 'Admin'||$store.state.userRole !='TSOhead'" class="nav-link  align-items-center">
                            <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                            <span class="">Send mail </span>
                        </router-link>
                    </div>-->
                     <!--<div>
                        <router-link :to="`${RolePath}`"  v-if="$store.state.userRole != 'Admin'||$store.state.userRole !='TSOhead'"  class="nav-link align-items-center">
                            <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                            <span class="d-flex">Role Delegation </span>
                        </router-link>
                    </div>-->
                    <!-- Message ends -->
                    <!-- messages begins-->
                    <div v-if="$store.state.userRole != 'Risk'">
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#itax" aria-expanded="false"
                        aria-controls="itax"   v-if="$store.state.userRole != 'Admin'||$store.state.userRole !='TSOhead'">
                            <div class="sb-nav-link-icon"><i class="fas fa-info"></i></div>
                            Itax Details 
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="itax" aria-labelledby="headingOne" 
                        data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                               <a class="nav-link d-flex justify-content-between align-items-center" v-if="$store.state.userRole != 'Admin'||$store.state.userRole !='TSOhead'">
                                    <a class="d-flex" href="https://itax-bo.kra.go.ke/ITMS-Web/frontController.do?actionCode=viewlogin&theme=kraitms&fromBeforeLoginPage=test">View Taxpayer Details</a>
                                </a>
                            </nav>
                        </div>
                    </div>
                    <!-- Message ends -->
                
                   <!-- Report begins -->
                    <div  v-if="$store.state.userRole != 'Risk'" >
                    <!-- <div> -->
                      <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Reports
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                    <!-- <router-link to="/"  class="nav-link">
                                        <span>Revenue summary</span>
                                </router-link> -->
                                <router-link :to="`${data.progressPath}`" class="nav-link">
                                        <span>Cases Progress</span>
                                </router-link>
                                   <!--  <router-link to="/"  class="nav-link">
                                        <span>Cases summary</span>
                                </router-link> -->
                                <router-link :to="`${data.closedPath}`"  class="nav-link">
                                        <span>Closed Cases</span>
                                </router-link>
                                
                            </nav>
                        </div>
                        </div>
                    <!-- report ends -->

                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                
                <span>{{ $store.state.userRole}}   :</span><span>{{$store.state.user.LAST_NAME}}</span>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    props:['user','messagePath','channel','manualPath','role','RolePath','progressPath','closedPath'],
    data(){
        return {
            data:{
                // totalMessages:''
                manualTotal:"1",
                RolePath:'', 
                closedPath:'',
                progressPath: '',
                messagePath:'',
                // manualPath: '',
                // itaxPath: location.href = 'https://itax-bo.kra.go.ke/ITMS-Web/frontController.do?actionCode=viewlogin&theme=kraitms&fromBeforeLoginPage=test'
            }
        }
    },

    methods: {
        switchPath(){
            var x = this.$store.state.userRole
            if (x == 'AccountManager') {

                this.data.RolePath='/Account-manager/Temporary-role-change' 
                this.data.closedPath='/Account-manager/closed-cases'
                this.data.progressPath= '/Account-manager/cases-progress'
                this.data.messagePath = '/Account-manager/messages'
                this.data.manualPath ='/Account-manager/Manual-cases'

            } else if (x == 'SectorManager') {

                this.data.RolePath='/Sector-manager/Temporary-role-change' 
                this.data.closedPath='/Sector-manager/closed-cases'
                this.data.progressPath= '/Sector-manager/cases-progress'
                this.data.messagePath = '/Sector-manager/messages'
                this.data.manualPath = '/Sector-manager/Manual-cases'

            } else if (x == 'TeamLead') {

                this.data.RolePath='/Team-lead/Temporary-role-change' 
                this.data.closedPath='/Team-lead/closed-cases'
                this.data.progressPath= '/Team-lead/cases-progress'
                this.data.messagePath = '/Team-lead/messages'
                this.data.manualPath = '/Team-lead/Manual-cases'

            }else if (x == 'TSOhead') {

                this.data.RolePath='/Tso-head/Temporary-role-change' 
                this.data.closedPath='/Tso-head/closed-cases'
                this.data.progressPath= '/Tso-head/cases-progress'
                this.data.messagePath = '/Tso-head/messages'
                this.data.manualPath = '/Tso-head/Manual-cases'

            }else if (x == 'Admin' || x == 'SuperAdmin'|| 'Risk') {

                this.data.RolePath='/admin/Temporary-role-change' 
                this.data.closedPath='/admin/closed-cases'
                this.data.progressPath= '/admin/cases-progress'
                this.data.messagePath = '/admin/messages'
                this.data.manualPath = '/admin/Manual-cases'

            }
            else {
                this.error('Wrong path!')
            }
        }
    },
    created() {
        // const  res = this.callApi('get', `/api/unreadMessages/${this.$store.state.user.USER_NAME}`)
        // if(res.status==200){
        //     this.data.totalMessages = res.data
        
        // }
        this.switchPath()
    }
}
</script>






