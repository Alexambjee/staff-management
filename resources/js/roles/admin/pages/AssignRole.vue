<template>
<div>
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
                    <Option v-for="(roleData, i) in roles" :value='roleData.ROLE_ID' :key="i">{{ roleData.ROLE_NAME
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
<div class="card shadow mb-4">

  <div class="card-body">

    <!-- <table  style="border:none !important;width:100%;" class="table table-bordered table-striped mb-3" >
      <thead class="table-header">
          <tr>
              <th>CASE ID</th>
              <th>SOURCE</th>
              <th v-if="stage !='prelim'">RECOMMENDATION</th>
              <th>STATUS</th>
              <th>DATE CREATED</th>
              <th >PREPARED BY</th>
          </tr>
      </thead>
          <tbody class="table-body" >
              <tr >
                  <td>23</td>
                  <td>Risk</td>
                  <td v-if="stage !='prelim'">Proceed with case</td>
                  <td>preliminary report stage</td>
                  <td>12-12-1022</td>
                  <td>K0003434</td>
              </tr>
          </tbody>
  </table> -->
  <div class="row">
    <!-- taxpayer details -->
    <div class="taxpayer-details col-sm-6 col-md-6 col-lg-6">
        <div class="card shadow">
            <div class="card-header headings" style="text-transform:uppercase; font-size:12px;">
                Search Details
            </div>
            <div class="card-body">
                    <div class="card-content">
                        <div class="card-items">
                          <div class="form-group">
                            <div class="col-md-12">
                              <div class="form-group ">
                                <label class="label">Username <span class="text-danger ml-2">*</span></label>
                                <input type="text" v-model="data.userName" class="form-control" placeholder="Username" onkeyup="this.value = this.value.toUpperCase();" />
                              </div>
                            </div>
                            <br/>
                            
            <Button type="success" size="small" @click="getUser()" >
            <i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;{{
                isLoading ? 'Loading..' : 'Submit'
            }}</Button>
            <div class="form-group " :loading="isLoading" :disabled="isLoading"
            v-if="isfound">
              <label class="label">User Role <span class="text-danger ml-2">*</span></label>
              <Select v-model='data.roleId' placeholder="Select User Role.." style="width:100%;">
                <Option v-for="(roleData, i) in roles" :value='roleData.ROLE_ID' :key="i">{{ roleData.ROLE_NAME
                }}</Option>
              </Select>
              <br/><br/>
              <Button type="success" size="small" @click="add()"><i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;{{
                  isLoading ? 'Loading..' : 'Create'
              }}</Button>
            </div>




            <!-- <Button type="error" @click="addModal = false" size="small">Close &nbsp;<i class="fa fa-user-times" aria-hidden="true"></i></Button> -->
                            </div>
                        </div>
                     
                       
                    </div>
            </div>
        </div>
    </div>
    <div class="-details col-sm-6 col-md-6 col-lg-6">
      <div class="card shadow">
          <div class="card-header headings" style="text-transform:uppercase; font-size:12px;">
               User Details
          </div>
          <div class="card-body">
                  <div class="card-content">
                      <div class="card-items">
                      <span class="text-left" >LastName</span>
                      <p class="text-left">{{data.firstName}}</p>
                      </div>
                      <div class="card-items">
                      <span class="text-left" >OtherNames</span>
                      <p class="text-left">{{data.otherNames}}</p>
                      </div>
                      <div class="card-items">
                          <span class="text-left" >Email</span>
                          <p class="">{{data.email}}</p>
                      </div>
                      <div class="card-items">
                          <span class="text-left" >NationId</span>
                          <p class="">{{data.nationalId }}</p>
                      </div>   
                  </div>
          </div>
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
            <td>{{ u.USER_NAME }}</td>
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
            <li v-for="pagex in Math.ceil(users.length/casesPerPage)" :key="page" >
              <Button @click="page=page" :class="'btn btn-primary'">{{ page }}</Button>
                <Button @click="page=pagex + page" :class="pagex + page === page ? 'btn btn-primary ':''">{{ pagex+page }}</Button>
            </li>
            <li v-if="page< users.length ">
                <Button @click="page=page+1">Next</Button>
            </li>
          </ul>
          </div>
    </div>
    </div>
            <!-- create user modal -->
            <Modal v-model="addModal" title="Create New User" :mask-closable="false" width="600" :styles="{ top: '60px' }"
            :closable="true">
            <div class="card">
              <div class="card card-detail">
                <div class="card-body">
                  <form>
                    <div v-if="option">
                      <div class="row">
                        <div class="col-md-12 mt-2">
                          <div class="form-group ">
                            <label class="label">Last Name <span class="text-danger ml-2">*</span></label>
                            <Input placeholder="First Name.." v-model="data.firstName"/>
                          </div>
                          
                           <div class="form-group ">
                            <label class="label">User Id <span class="text-danger ml-2">*</span></label>                         
                             <Input placeholder=""  v-model="data.nationalId"/>
                          </div>
                          <div class="form-group ">
                            <label class="label">Other Names <span class="text-danger ml-2">*</span></label>
                            <Input placeholder="Other Names.." v-model="data.otherNames"/>
                          </div>
                          <div class="form-group ">
                            <label class="label">Staff Mail <span class="text-danger ml-2">*</span></label>
                            <Input type="email" placeholder="Email.." v-model="data.email"/>
                          </div>
                          <!-- <div class="form-group ">
                            <label class="label">Phone <span class="text-danger ml-2">*</span></label>
                            <Input placeholder="Phone Number.." v-model="data.phone" />
                          </div> -->
                          <div class="form-group ">
                            <label class="label">User Role <span class="text-danger ml-2">*</span></label>
                            <Select v-model='data.roleId' placeholder="Select User Role.." style="width:100%;">
                              <Option v-for="(roleData, i) in roles" :value='roleData.ROLE_ID' :key="i">{{ roleData.ROLE_NAME
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
              <Button type="success" size="small" @click="add()"><i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;{{
                    isLoading ? 'Loading..' : 'Create'
                }}</Button>
            </div>
          </Modal>
          <!-- create user modal ends -->
    <!-- //// -->
</div>
</div>
</div>
<!-- case details component -->


</template>
<script>
import Footer from '../../../components/footer.vue'
import Sidebar from '../../../components/sidebar.vue'
import Navbar from '../../../components/navbar.vue'
export default {
  data() {
    return {
      page:1,
      casesPerPage:10,
      data: {
        userName: '',
        nationalId:'',
        firstName: '',
        otherNames: '',
        email: '',
        roleId: '',
        action: '',
        nationalId:'',
        username:this.$store.state.user.USER_NAME,
        role: this.$store.state.userRole,
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
        roleId:'',
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
          this.getDetails().then(()=>void 0).catch(console.error)
        }
      },
  methods: {
    async  getDetails (){
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
      this.isLoading = false
    }
    else {
      this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      window.location ='/'
    }
  },
    
    async getUser() {
      
      if (this.data.userName.trim() == '')
        return this.error('Username is required');
      if (this.data.userName.trim().length < 9 || this.data.userName.trim().length > 9)
        return this.error('Username cannot be more or less than 9 characters in length');
      var getIndexZero = Array.from(this.data.userName.trim())[0];
      this.isLoading=true
      const res = await this.callApi('get', `/api/getUserInfoWhere/${this.data.userName}`)
      console.log(res);
      if (res.status == 200) {
        console.log(res.data[[0]])
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
            this.isfound = false
          this.data.userName=this.data.userName
          this.data.firstName =""
          this.data.otherNames =""
          this.data.email =""
          this.data.status = ""
          this.data.nationalId = ""
            
            this.error('User Not found')
            this.addModal = true
          } else {
            this.swr()
          }
        }

    },
    async add()
    {
      if (this.data.userName.trim() == '') return this.error('Username is required')
      this.isLoading = true
      console.log("sdfghjkldfghj",this.$store.state.user)
      const resUser = await this.callApi('post', '/api/createUser', this.data)
      if (resUser.status == 201) {
        this.users.unshift(resUser.data);
        this.success('User Created Successfully!')
        this.option = false
        this.isLoading = false
        this.addModal = false
        this.data.userName = ''
        this.data.roleId = ''

        // getthisandthat()
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
      console.log(this.editData)
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
        username:this.$store.state.user.USER_NAME

      }
      this.modalChangeRole = true
      this.roleData = obj
      this.index = index
    }
    //Change Role Ends////////////////////////////////////////////////////////////////////////////////
  },


  async getthisandthat(){
    if (this.$store.state.user) {
    if (this.$store.state.userRole == 'Admin') {
        const [res, resRoles] = await Promise.all([
          this.callApi("get", "/api/getAllUsersWithout"),])
    }
    if (resRoles.status === 200) {
          this.roles = resRoles.data
        
        }
        else {
          this.swr('something went wrong')
        }
  }
  },
 async created() {
     await this.getDetails()
  }

}
</script>