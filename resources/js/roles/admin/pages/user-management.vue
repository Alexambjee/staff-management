<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'Admin' || $store.state.userRole == 'Super'
  || $store.state.userRole == 'administrator' || $store.state.userRole == 'Administrator')">
    <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user " :messagePath="data.messagePath"  />
    <div id="layoutSidenav">
      <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="data.channel" :manualPath="data.manualPath" :RolePath="data.RolePath"  :progressPath="data.progressPath" :closedPath="data.closedPath" :messagePath="data.messagePath"/>
      <div id="layoutSidenav_content">
        <main>
          <div class="container-fluid px-4">
            <h1 class="mt-4">USER MANAGEMENT</h1>
            <div class="breadcrumb mb-4">
              <Breadcrumb>
                <BreadcrumbItem to="/admin" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                <BreadcrumbItem to="/admin/user-management" class="breadcrumb-item active">User Management</BreadcrumbItem>
              </Breadcrumb>
            </div>

            <div class="card detail-card mb-4">
              <div class="card-header bg-dark text-light">
                <div class="row">
                <div class="col">
                  <i class="fa fa-list" aria-hidden="true"></i>&nbsp; User Management
                </div>
                <div class="col" align="right">
                  <Button type="default" class="btn btn-sm btn-success" @click="addModal = true"><i class="fa fa-user-plus text-dark" aria-hidden="true"></i>&nbsp;Create New User</Button>
                </div>
              </div>
              </div>

             
              <div class="card-body table-responsive">
            
                <table class="table table-striped  table-bordered table-responsive table-hover" cellspacing="0"
                  width="100%">
                  <thead>
                    <tr>
                      <th>USER_NAME</th>
                      <th>LAST_NAME</th>
                      <th>OTHER_NAME</th>
                      <th>EMAIL_ADDRESS</th>                     
                      <th>ROLE</th>
                      <th>DOMAIN_NAME</th>
                      <th>DATE CREATED</th>
                      <th>CREATED_BY</th>
                      <th>STATUS</th>
                      <th>ACTION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(u, i) in users" :key="i">
                      <td>{{ u.USER_NAME }} </td>
                      <td>{{ u.LAST_NAME }}</td>
                      <td>{{ u.OTHER_NAMES }}</td>
                      <td>{{ u.EMAIL_ADDRESS }}</td>                     
                      <td>{{ u.ROLE_NAME }}</td>
                      <td>{{ u.USER_ID }}</td>
                      <td>{{ u.created_at }}</td>
                      <td>{{ u.CREATED_BY }}</td>
                       <td>
                        <div v-if="u.STATUS === '0'" class="text-danger">
                            <div class="btn btn-danger btn-sm" style="width:90px"><i class="fa fa-toggle-off text-dark" aria-hidden="true"></i>&nbsp; Inactive
                          </div></div>
                          <div v-else-if="u.STATUS === '1'"  class="text-info">
                            <div class="btn btn-success btn-sm" style="width:90px"><i class="fa fa-toggle-on text-dark" aria-hidden="true"></i>&nbsp; Active </div>
                          </div>
                      </td>
                      <td>
                        <Dropdown placement="bottom-start" class="bg-info" style="width:135px;border-radius: 3px;" align="center">
                          <a href="javascript:void(0)" class="btn btn-sm btn-info btn-default">
                            <span class="text-white bg-info" style="width:105px"><i class="fa fa-cog text-dark" aria-hidden="true"></i>&nbsp;Action</span>
                            <Icon type="ios-arrow-down"></Icon>
                          </a>
                          <template #list  >
                            <DropdownMenu class="bg-dark">
                              <DropdownItem>
                                <Button type="error" ghost size="small"
                                  @click="showEditModal(u, index)" style="width:105px">
                                  Change Status
                                  </Button>
                                  </DropdownItem>
                                   <DropdownItem>
                                    <Button type="primary" ghost size="small"
                                     @click="changeRoleModal(u, index)" style="width:105px">
                                      Changer Role
                                  </Button>
                                </DropdownItem>
                            </DropdownMenu>
                          </template>
                        </Dropdown>
                      </td>
                    </tr>


                  </tbody>

                </table>

            
                
                <div style="margin: 10px;">
                  <div style="float: right;">
                  <ul class="pagination d-flex" style="list-style-type: none">
                      <li v-if="page > 1">
                        <Button @click="page=page-1">Previous</Button>
                      </li>
                      <li v-for="pagex in [0,1,2,3,4,5]" :key="page" >
                          <Button @click="page=pagex + page" :class="pagex + page === page ? 'btn btn-primary ':''">{{ pagex+page }}</Button>
                      </li>
                      <li v-if="page< users.length">
                          <Button @click="page=page+1">Next</Button>
                      </li>
                    </ul>
                    </div>
              </div>
              </div>
            </div>

          </div>
        </main>
        <!-- create user modal -->
        <Modal v-model="addModal" title="Create New User"  :mask-closable="false" width="600" :styles="{ top: '60px' }"
          :closable="true">
          <!-- new message body -->
          <div class="card">
            <div class="card card-detail">
              <div class="card-body">
                <form>
                  <!-- row -->
                  <div class="row">
                    <!-- col-md-4 -->
                    <div class="col-md-12">
                      <div class="form-group ">
                        <label class="label">Username <span class="text-danger ml-2">*</span></label>
                        <input type="text" v-model="data.userName" class="form-control" placeholder="Username" onkeyup="this.value = this.value.toUpperCase();" />
                      </div>
                    </div>
                    <!-- col-md-4 ends -->
                  </div>
                  <!-- row ends -->
                  <div v-if="option">
                    <!-- row -->
                    <div class="row">
                      <!-- col-md-4 -->
                      <div class="col-md-12 mt-2">
                        <div class="form-group ">
                          <label class="label">Last Name <span class="text-danger ml-2">*</span></label>
                          <Input placeholder="First Name.." v-model="data.firstName"/>
                        </div>
                        
                         <div class="form-group ">
                          <label class="label">User Id <span class="text-danger ml-2">*</span></label>                         
                           <Input placeholder="" v-model="data.nationalId"/>
                        </div>
                        <div class="form-group ">
                          <label class="label">Other Names <span class="text-danger ml-2">*</span></label>
                          <Input placeholder="Other Names.." v-model="data.otherNames" />
                        </div>
                        <div class="form-group ">
                          <label class="label">Staff Mail <span class="text-danger ml-2">*</span></label>
                          <Input type="email" placeholder="Email.." v-model="data.email" />
                        </div>
                        <!-- <div class="form-group ">
                          <label class="label">Phone <span class="text-danger ml-2">*</span></label>
                          <Input placeholder="Phone Number.." v-model="data.phone" />
                        </div> -->
                        <div class="form-group ">
                          <label class="label">User Role <span class="text-danger ml-2">*</span></label>
                          <Select v-model='data.roleId' placeholder="Select User Role.." style="width:100%;">
                            <Option v-for="(roleData, i) in roles" :value='roleData.id' :key="i">{{ roleData.ROLE_NAME
                            }}</Option>
                          </Select>
                        </div>
                        <!-- col-md-4 ends -->
                      </div>
                      <!-- row ends -->
                    </div>
                    <!-- col-md-4 -->
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!--new  message body  ends-->
          <div slot="footer">
            <Button type="success" size="small" @click="add()" :loading="isLoading" :disabled="isLoading"
              v-if="isfound"><i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;{{
                  isLoading ? 'Loading..' : 'Send'
              }}</Button>
            <Button type="success" size="small" @click="getUser()" v-else :loading="isLoading" :disabled="isLoading">
            <i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;{{
                isLoading ? 'Loading..' : 'Submit'
            }}</Button>
            <Button type="error" @click="addModal = false" size="small">Close &nbsp;<i class="fa fa-user-times" aria-hidden="true"></i></Button>
          </div>
        </Modal>
        <!-- create user modal ends -->

        <!-- Edit modal -->
        <Modal v-model="editModal" title="Block / Unblock User" :mask-closable="false" width="400"
          :styles="{ top: '60px' }" :closable="true">
          <!-- new message body -->
          <div class="card">
            <div class="card card-detail">
              <div class="card-body">
                <form>
                  <!-- row -->
                  <div class="row">
                    <!-- col-md-4 -->
                    <div class="col-md-12">
                      <div class="form-group ">
                        <label class="label">Username <span class="text-danger ml-2">*</span></label>
                        <Input type="text" v-model="editData.userName" placeholder="Username" />
                      </div>
                    </div>
                    <!-- col-md-4 ends -->
                  </div>
                  <!-- row ends -->
                  <!-- row -->
                  <div class="row">
                    <!-- col-md-4 -->
                    <div class="col-md-12 mt-2">
                      <div class="form-group ">
                        <label class="label">Last Name <span class="text-danger ml-2">*</span></label>
                        <Input placeholder="First Name.." v-model="editData.lastName" readonly />
                      </div>
                      <div class="form-group ">
                        <label class="label">Other Names <span class="text-danger ml-2">*</span></label>
                        <Input placeholder="Other Names.." v-model="editData.otherNames" readonly />
                      </div>
                      <div class="form-group ">
                        <label class="label">Select Action <span class="text-danger ml-2">*</span></label>
                        <select class="form-control" v-model="editData.action" placeholder="Select Action">
                          <option value="0">Deactivate</option>
                          <option value="1">Activate</option>
                        </Select>
                      </div>
                      <div class="form-group ">
                        <label class="label">Reason <span class="text-danger ml-2">*</span></label>
                        <!-- <Textarea placeholder="Enter reason" class="form-control"  v-model="editData.reason" cols="30" rows="5">
                        </Textarea> -->
                          <Input placeholder="Reason.." v-model="editData.reason" />
                      </div>
                    </div>
                    <!-- col-md-4 ends -->
                  </div>
                  <!-- row ends -->

                </form>
              </div>
            </div>

          </div>
          <!--new  message body  ends-->
          <div slot="footer" align="center">
            <Button type="success" size="small" @click="edit()"
                :loading="isLoading" :disabled="isLoading"
                style="width:170px"
                >
                <i class="fa fa-toggle-on"></i>
                {{isLoading ? 'Please Wait..':'Change Status'}}</Button>
                <Button type="error" @click="editModal = false" size="small" style="width:170px">Close <i class="fa fa-times-circle" aria-hidden="true"></i></Button>
          </div>
        </Modal>
        <!-- create user modal ends -->

        <!-- Change Role Modal Begins -->
         <Modal v-model="modalChangeRole" title="Change User Role" :mask-closable="false" width="400"
          :styles="{ top: '60px' }" :closable="true">
          <!-- new message body -->
          <div class="card">
            <div class="card card-detail">
              <div class="card-body">
                <form>
                  <!-- row -->
                  <div class="row">
                    <!-- col-md-4 -->
                    <div class="col-md-12">
                      <div class="form-group ">
                        <label class="label">Username <span class="text-danger ml-2">*</span></label>
                        <Input type="text" v-model="roleData.userName" placeholder="Username" />
                      </div>
                    </div>
                    <!-- col-md-4 ends -->
                  </div>
                  <!-- row ends -->
                  <!-- row -->
                  <div class="row">
                    <!-- col-md-4 -->
                    <div class="col-md-12 mt-2">
                      <div class="form-group ">
                        <label class="label">Last Name <span class="text-danger ml-2">*</span></label>
                        <Input placeholder="First Name.." v-model="roleData.lastName" readonly />
                      </div>
                      <div class="form-group ">
                        <label class="label">Other Names <span class="text-danger ml-2">*</span></label>
                        <Input placeholder="Other Names.." v-model="roleData.otherNames" readonly />
                      </div>
                      <div class="form-group ">
                        <label class="label">Select Role<span class="text-danger ml-2">*</span></label>
                            <Select v-model='roleData.roleId' placeholder="Select User Role.." style="width:100%;">
                            <Option v-for="(roleData, i) in roles" :value='roleData.id' :key="i">{{ roleData.ROLE_NAME
                            }}</Option>
                          </Select>
                      </div>
                    </div>
                    <!-- col-md-4 ends -->
                  </div>
                  <!-- row ends -->

                </form>
              </div>
            </div>

          </div>
          <!--new  message body  ends-->
          <div slot="footer" align="center">
            <Button type="success" size="small" @click="changeRole()"
                :loading="isLoading" :disabled="isLoading"
                style="width:170px"
                >
                <i class="fa fa-toggle-on"></i>
                {{isLoading ? 'Please Wait..':'Change Status'}}</Button>
                <Button type="error" @click="modalChangeRole = false" size="small" style="width:170px">Close <i class="fa fa-times-circle" aria-hidden="true"></i></Button>
          </div>
        </Modal>
        <!-- Change Role Modal Ends -->

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
export default {
  data() {
    return {
      page:1,
      data: {
        messagePath: '/admin/messages',
        manualPath: 'admin/Manual-cases',
        RolePath:'/admin/Temporary-role-change', 
        closedPath:'/admin/closed-cases',
        progressPath: 'admin/cases-progress',
        userName: '',
        nationalId:'',
        firstName: '',
        otherNames: '',
        email: '',
        roleId: '',
        action: '',
        nationalId:'',
        username:this.$store.state.user.USER_NAME,
             
      },
      isfound: false,
      option: false,
      users: [],
      addModal: false,
      isLoading: false,
      editData: {
        id:'',
        reason:'',
        action:'',
        username:this.$store.state.user.USER_NAME,
               
      },
      roleData: {
        userName: '',
        lastName:'',
        otherNames: '',
        email: '',
        roleId:''
      },
      editModal: false,
      modalChangeRole: false,
      index: -1

    }

  },
  components: {
    Footer,
    Sidebar,
    Navbar
  },
  watch:{
        page (){
                this.created().then(()=>void 0).catch(console.error)
        }
      },
  methods: {
    async getUser() {
      console.log("ffffff");
      if (this.data.userName.trim() == '')
        return this.error('Username is required');
      if (this.data.userName.trim().length < 9 || this.data.userName.trim().length > 9)
        return this.error('Username cannot be more or less than 9 characters in length');
      var getIndexZero = Array.from(this.data.userName.trim())[0];
     
      this.isLoading=true
      const res = await this.callApi('get', `/api/getUserInfoWhere/${this.data.userName}`)
      if (res.status == 200) {
        console.log(res.data ,"asasa");
        this.isLoading = false
        this.option = true
        this.isfound = true
        this.data.firstName = res.data[0]['surname']
        this.data.otherNames = res.data[0]['othernames']
        this.data.email = res.data[0]['email_address']
        this.data.status = res.data[0]['status']
        this.data.nationalId = res.data[0]['id_number']
      }
        else{
          if(res.status === 401){
            this.isLoading = false;
            this.option = true
            this.option = true
            this.isfound =false
            this.data.firstName = ""
            this.data.otherNames = ""
            this.data.email = ""
            this.data.status = ""
            this.data.nationalId = ""
          }
      else {
        this.swr()
      }
        }
    },
    async add()
    {
      if (this.data.userName.trim() == '') return this.error('Username is required')
      this.isLoading = true
      const resUser = await this.callApi('post', '/api/createUser', this.data)
      if (resUser.status == 201) {
        this.users.unshift(resUser.data);
     
        this.success('User Created Successfully!')
        this.option = false
        this.isLoading = false
        this.addModal = false
        this.data.userName = ''
        this.data.roleId = ''
      }
      else{
        // invalid credentials
        if(resUser.status === 401){
            this.warning(resUser.data.msg)
            this.isLoading=false
        }
        // return errors
        else if(resUser.status === 422){
            for(let i in resUser.data.errors){
                this.error(resUser.data.errors[i][0])
            }
            this.isLoading=false
        }
        else{
            this.swr()
        }

      }
      
    },
    //Editing begins////////////////////////////////////////////////////////////////////
    async edit() {
      this.isLoading = true
      const resEdit = await this.callApi('post', '/api/activateDeactivateUser', this.editData)
      if (resEdit.status == 200) {
        this.success('User status updated successfully')
        this.isLoading = false
        this.editModal = false
        this.editData = ''
      }
      else {
        if (resEdit.status == 422) {
          if (resEdit.data.errors) {
            this.error(resEdit.data.errors)
          }
        }

        else {
          this.swr()
        }
        this.isLoading = false

      }
       window.location.reload();
    },

    showEditModal(u, index) {
      let obj = {
           id: u.id,
        userName: u.USER_NAME,
        lastName: u.LAST_NAME,
        otherNames: u.OTHER_NAMES,
        email: u.EMAIL_ADDRESS,
        action:u.STATUS

      }
      this.editModal = true
      this.editData = obj
      this.index = index
    },

    //Editing ends////////////////////////////////////////////////////////////////////////////////////
    //Change Role Begins//////////////////////////////////////////////////////////////////////////////
       async changeRole() {
      this.isLoading = true
      const resRoleData = await this.callApi('post', '/api/changeUserRole', this.roleData)
      if (resRoleData.status == 200) {
        this.success('Role successfully Changed')
        this.isLoading = false
        this.modalChangeRole = false
        this.editData = ''
      }
      else {
        if (resRoleData.status == 422) {
            this.error(resRoleData.data.errors)
        }

        else {
          this.swr()
        }
        this.isLoading = false

      }
       window.location.reload();
    },

    changeRoleModal(u, index) {
      let obj = {
        id: u.id,
        userName: u.USER_NAME,
        lastName: u.LAST_NAME,
        otherNames: u.OTHER_NAMES,
        email: u.EMAIL_ADDRESS,
        roleId: u.ROLE_ID,

      }
      this.modalChangeRole = true
      this.roleData = obj
      this.index = index
    }
    //Change Role Ends////////////////////////////////////////////////////////////////////////////////
  },

  async created() {
    // run apis if logedin
    if (this.$store.state.user) {
       // run if role is admin
      if (this.$store.state.userRole == 'Admin') {
        const [res, resRoles] = await Promise.all([
          this.callApi("get", `/api/getAllUsersWithout?page=${this.page}`),
          this.callApi('get', `/api/fetchRolesWithout?page=${this.page}`),
        ])
        if (res.status === 200) {
          this.users = res.data
        }
        else {
          this.swr('something went wrong')

        }
        if (resRoles.status === 200) {
          this.roles = resRoles.data
        
        }
        else {
          this.swr('something went wrong')

        }
      }
      // else run if role is super
      else {
        const [res, resRoles] = await Promise.all([
          this.callApi("get", "/api/getAllUsers"),
          this.callApi('get', '/api/fetchRolesWithout'),
        ])
        if (res.status === 200) {
          this.users = res.data
        }
        else {
          this.swr('something went wrong')

        }
        if (resRoles.status === 200) {
          this.roles = resRoles.data
          
        }
        else {
          this.swr('something went wrong')

        }
      }
      
    }
    else {
      this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      window.location ='/'
    }
   

   
  }

}
</script>

