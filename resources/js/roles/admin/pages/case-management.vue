<template>
  <div v-if="($store.state.user) && ($store.state.userRole == 'Admin' || $store.state.userRole =='Super'
    || $store.state.userRole =='administrator' || $store.state.userRole =='Administrator')">
    <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user"/>
    <div id="layoutSidenav">
      <Sidebar v-if="$store.state.user" :user="$store.state.user" :channel="data.channel"/>
      
      <div id="layoutSidenav_content">
        <main>
          <div class="container-fluid px-4">
            <h1 class="mt-4">CASE MANAGEMENT </h1>
            <div class="breadcrumb mb-4">
              <Breadcrumb>
                <BreadcrumbItem to="/admin" class="breadcrumb-item"
                  >Dashboard</BreadcrumbItem
                >
                <BreadcrumbItem
                  to="/admin/case-management"
                  class="breadcrumb-item active"
                  >Case Management</BreadcrumbItem
                >
              </Breadcrumb>
            </div>

            <div class="card mb-4 detail-card">
              <div class="card-header bg-dark text-light">
                 <div class="row">
                <div class="col">
                  <i class="fa fa-list" aria-hidden="true"></i>&nbsp;   Case Management
                </div>
                <!-- <div class="col" align="right">
                  <Button type="default" class="btn btn-sm btn-success" @click="addModal = true"><i class="fa fa-file text-dark" aria-hidden="true"></i>&nbsp;Add New Case</Button>
                </div> -->
              </div>
              </div>
              <div class="form-group row">
                <div class="col-4" align="left">
                    <Button type="default" @click="print" >Export Csv</Button>
                    <Button type="default" >Print</Button>
                </div>
                <div class="col-5"></div>
                <div class="col-3 d-flex justify-content-between" align="right"> 
                  <Input type="text" v-model="searchQuery" placeholder="Search..." />  
                  <Button  type="default" @click="search_me">Search</Button>             
                </div>
              </div>
              <div class="card-body table-responsive">
                <table
                  class="table table-striped table-bordered table-hover"
                  style="width: 100%" ref="table" id="table"
                >
                  <thead>
                    <tr>
                      <th>CASE ID </th>
                      <th>TAXPAYER PIN</th>
                      <th>TAXPAYER NAME</th>
                      <th>DATE CREATED</th>
                      <th>CASE SOURCE</th>
                      <th>STATUS</th>
                      <th>ACCOUNT MANAGER</th>
                      <th>TEAM LEAD</th>
                      <th>SECTOR MANAGER</th>
                      <th>TSO</th>
                      <th>Assign</th>
                      <!-- <th>ACTION</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(c, i) in cases" :key="c.id">
                      <td>{{ c.CASE_ID }}</td>
                      <td>{{ c.PIN_NO }}</td>
                      <td>{{ c.TAXPAYER_NAME }}</td>
                       <td>{{  moment(String(c.created_at)).format('MM/DD/YYYY HH:mm') }}</td>
                      <td>{{ c.SOURCE }}</td>
                      <td>{{ c.STATUS_DESCRIPTION }}</td>
                      <td>{{ c.AM_PNO }}</td>
                      <td>{{ c.TL_PNO }}</td>
                      <td>{{ c.SM_PNO }}</td>
                      <td>{{ c.TSO_PNO }}</td>
                      <td><Button type="primary" ghost size="small"
                                  @click="showEditModal(c,i)"><i class="fa fa-exchange" aria-hidden="true"></i></Button></td>
                    </tr>
                  </tbody>
                </table>
                <div style="margin: 10px;overflow: hidden">
                  <div style="float: right;">
                    <ul class="pagination d-flex" style="list-style-type: none">
            <li v-if="page > 1">
              <Button @click="page=page-1">Previous</Button>
            </li>
            <li v-for="pagex in Math.ceil(cases.length/casesPerPage)" :key="page" >
              <Button @click="page=page" :class="'btn btn-primary'">{{ page }}</Button>
                <Button @click="page=pagex + page" :class="pagex + page === page ? 'btn btn-primary ':''">{{ pagex+page }}</Button>
            </li>
            <li v-if="page< cases.length ">
                <Button @click="page=page+1">Next</Button>
            </li>
          </ul>
                    </div>
              </div>
              </div>
            </div>
          </div>
        </main>
        <!-- create case modal -->

        <Modal v-model="addModal" title="Create New Case" :mask-closable="false" width="600" :styles="{ top: '60px' }" :closable="true">
  
          <div class="card">
            <div class="card card-detail">
              <div class="card-body">
                <form id="roleForm">
         
                  <div class="row">
                 
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="label">Taxpayer PIN <span class="text-danger ml-2">*</span></label>
                        <span>
                        <input type="text" class="form-control"  v-model="data.tPin" placeholder="Taxpayer PIN"
                         maxlength="11"  minlength="11"  onkeyup="this.value = this.value.toUpperCase();"/>
                         </span>
                      </div>
                    </div>
                
                    <div class="col-md-6">
                      <br>
                      <small class="text-dark">Pin must start with A or P in uppercase and end with a uppercase letter e.g A123456789W</small>
                    </div>
                   
                  </div>
                
                  <div v-if="option">
                
                    <div class="row">
                
                      <div class="col-md-12">
                        <div class="form-group">
                          <Input type="text"  v-model="data.caseId" placeholder="Taxpayer Name" hidden />
                        </div>
                      </div>
                  
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="label">Taxpayer Name<span class="text-danger ml-2">*</span></label>
                          <Input type="text"  v-model="data.tName" placeholder="Taxpayer Name" />
                        </div>
                      </div>
                      
                    </div>
                  
                    <div class="row">
                  
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="label">Taxpayer E-Mail<span class="text-danger ml-2">*</span></label>
                          <Input type="text"  v-model="data.tMail"  placeholder="Taxpayer E-Mail" readonly/>
                        </div>
                      </div>
                  
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="label">Taxpayer Phone<span class="text-danger ml-2">*</span></label>
                          <Input  v-model="data.tPhone" placeholder="Taxpayer Phone Number" readonly></Input>
                        </div>
                      </div>
                  
                    </div>
              
                    <div class="row">
                  
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="label">Taxpayer Station<span class="text-danger ml-2">*</span></label>
                            <Input  v-model="data.tStation" placeholder="Taxpayer Phone Number" readonly></Input>
                            <Input  v-model="data.stId" readonly hidden></Input>
          
                        </div>
                      </div>
            
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="label">Obligation<span class="text-danger ml-2">*</span></label>
                          <Select  v-model="data.tObligation" clearable filterable style="width: 100%" placeholder="Obligation">
                            <Option v-for="(obData, i) in obligation" :value='obData.id' :key="obData.id">{{ obData.OBLIGATION_NAME }}</Option>
                          </Select>
                        </div>
                      </div>
            
                    </div>
              
                    <div class="row">
                    <!-- col-md-4 -->
                      <div class="col-md-12">
                        <label class="label">Reason <span class="text-danger ml-2">*</span></label>
                        <textarea name="" id="" cols="30" rows="5" class="form-control"  v-model="data.objReason" placeholder=" Reason..."></textarea>
                      </div>
                      <div :class="`${smallCol}`">
                        <div class="card-body">
                          <!-- attachment lists -->
                          <attachments :case_id="this.data.caseId" :tPin="data.tpin" />
                          <!-- attachment list ends -->
                          <div class="file-section top-header bg-primary d-flex justify-content-between" style="box-shadow:0 5px 10px rgba(0,0,0,0.1);">
                            <span class="m-auto">
                              <Upload 
                                  ref="upload"
                                  caseId
                                  :on-success="handleSuccess"
                                  :headers="{'x-csrf-token' :token,'X-Requested-With':'XMLHttpRequest'}"
                                  :on-error="handleError"
                                  :format="['pdf','docx']"
                                  :on-format-error="handleFormatError"
                                  :max-size="20048"
                                  :on-exceeded-size="handleMaxSize"
                                  action="/api/upload" id="fileUpload" class="input mt-3">

                                  <Button icon="ios-cloud-upload-outline">Upload File</Button>
                                  <small class="text-muted">(.pdf or .docx)</small>
                              </Upload>
                              <small id="error"></small>
                            </span>
                          </div>
                        </div>
                      </div>
                      <!-- col-md-4 ends -->
                    </div>
                    <!-- row ends -->
                  </div>
                </form>
              </div>
            </div>
          </div>

            <!--new  message body  ends-->
          <div slot="footer">
              <Button type="success" size="small" @click="createCase()"
            :loading="isLoading" :disabled="isLoading" v-if="isfound"
            ><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;{{isLoading ? 'Loading..':'Send'}}</Button>
              <Button type="success" size="small" @click="getTpContacts()" v-else
            :loading="isLoading" :disabled="isLoading"
            ><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;{{isLoading ? 'Loading..':'Submit'}}</Button>
            <Button type="error" @click="remove()" size="small">Close &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i></Button>
          </div>
        </Modal>
        <!-- create user modal ends -->
         <!-- Reassign Case -->
        <Modal v-model="editModal" title="Case Reassignment" :mask-closable="false" width="400" :styles="{ top: '60px' }"
          :closable="true">

          <div class="card">
            <div class="card card-detail">
              <div class="card-body">
                <form id="roleForm">
                  <!-- col-md-4 -->
                  <div class="col-12">
                    <div class="form-group ">
                      <label class="label mb-2">account manager: <span class="text-danger ml-2">*</span></label>
                      <Input type="text" v-model="editData.am" placeholder="change" />
                      <Input type="text" v-model="editData.am" placeholder="change" hidden />
                    </div>
                  </div>
                  <!-- col-md-4 ends -->
                  <!-- col-md-4 -->
                  <div class="col-12 mt-2">
                    <div class="form-group ">
                      <label class="label mb-2">Team lead: <span class="text-danger ml-2">*</span></label>
                      <Input type="text" v-model="editData.tl" placeholder="change" />
                    </div>
                  </div>
                  <!-- col-md-4 ends -->
                   <!-- col-md-4 -->
                  <div class="col-12 mt-2">
                    <div class="form-group ">
                      <label class="label mb-2">Sector manager: <span class="text-danger ml-2">*</span></label>
                      <Input type="text" v-model="editData.sm" placeholder="change" />
                    </div>
                  </div>
                  <!-- col-md-4 ends -->
                  <div class="col-12 mt-2">
                    <div class="form-group ">
                      <label class="label mb-2">TSO: <span class="text-danger ml-2">*</span></label>
                      <Input type="text" v-model="editData.tso" placeholder="change " />
                    </div>
                  </div>
                </form>
              </div>
            </div>

          </div>
          <div slot="footer">
            <Button type="success" size="small" @click="edit()" :loading="isLoading" :disabled="isLoading"><i class="fa fa-exchange" aria-hidden="true" style="width:35px">              
            </i>&nbsp;{{ isLoading ?
                'Please Wait..' : 'Edit'
            }}</Button>
            <Button type="error" @click="editModal = false" size="small">Close &nbsp;<i class="fa fa-times-circle" aria-hidden="true" style="width:35px"></i></Button>
          </div>
        </Modal>
        <!-- edit role modal ends -->

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
import Footer from "../../../components/footer.vue";
import Sidebar from "../../../components/sidebar.vue";
import Navbar from "../../../components/navbar.vue";
export default {
  data() {
    return {
   
      options1: {
                    disabledDate (date) {
                        return date && date.valueOf() < Date.now();
                    }
                },
                options2: {
                    disabledDate (date) {
                      return date && date.valueOf() > Date.now();
                    }
                },
                page:1,
                casesPerPage:10,
      data: {
        filename:'',   
        tPin: "",
        tStation: "",
        tObligation: "",
        source:'MANUAL',
        objReason: "",
        tMail: "",
        tPhone: "",
        tName: "",
        stId: "",
        username:this.$store.state.user.USER_NAME,
        role: this.$store.state.userRole,
        total:0,
      },
      stIdData: {
        stId:''
      },
      pinData:{
        message:"",
        code:"",
      },
      isfound: false,
      option: false,
      stations: [],
      cases: [],
      total:"",
      obligation: [],
      addModal:false,
      editModal:false,
      isLoading:false,
      searchQuery:'',
     // edit data
     
      editData: {
        caseId: '',
        am: '',
        tl: '',
        sm: '',
        tso: '',
        username: this.$store.state.user.USER_NAME,
        
      },
    //edit data
  
    };
  },
  components: {
    Footer,
    Sidebar,
    Navbar,
  },
  watch:{
        page (){
                this.fetchData().then(()=>void 0).catch(console.error)
        }

    },

  methods: {
    async search_me(){    
      
          var res = await this.callApi('get',`/api/getAllCases/?page=${this.page}&query=${this.searchQuery}`)
          if (res.status === 200) {
            this.cases = res.data;
            this.total=this.cases.length
          } else {
            this.swr("something went wrong");
          }
        },
   async fetchData(){
      const [res, resStation, resObligation] = await Promise.all([
      this.callApi("get", `/api/getAllCases?page=${this.page}&username=${this.data.username}&role=${this.data.role}&&station=${this.$store.state.user.station_id}`),
      this.callApi("get", "/api/getStations"),
      this.callApi("get", "/api/getObligations"),
    ]);
    // status
    if (res.status === 200) {
      
      this.cases = res.data;
      this.total=this.cases.length
      console.log(res.data)
    } else {
      this.swr("something went wrong");
    }
    // stations
    if (resStation.status === 200) {
      this.stations = resStation.data;
    } else {
      this.swr();
    }
    // Obligations
    if (resObligation.status === 200) {
      this.obligation = resObligation.data;
    } else {
      this.swr();
    }

    },

    handleSuccess(res){
            res = `${res}`
            this.data.filename = res
            this.isUploaded = true
        },
        handleError(file){
            this.$Notice.warning({
                title:'The file format is incorrect!',
                desc:`${file.errors.file.length ? file.errors.file[0] :'Something went wrong!'}`
            })
        },
    remove(){
      this.createModal = false;
      this.addModal =false;
  // changes
        this.isLoading = false;
        
        this.option = false;
        this.isfound = false;
        this.data.tPin = "";
        this.data.tStation = "";
        this.data.tName= "";
        this.data.tMail= "";
        this.data.tPhone= "";
        this.data.objReason = "";
      },
     
    print() {
            this.$refs.table.exportCsv({
            filename: 'Case Export Table',
            original: true
             });
            
        },
    async getTpContacts() {
      if (this.data.tPin.trim() == "")
       return this.error("Taxpayer PIN is Required.");
      if (this.data.tPin.trim().length<11 || this.data.tPin.trim().length>11)
       return this.error("PIN length should not be less or greater than 11 characters.");
      var re = /[A,P,a,p]{1}\d{9}[A-Za-z]{1}/;
      if (!re.test((this.data.tPin.trim()))) return this.error("Invalid PIN format.");
         this.isLoading = true;

         const res = await this.callApi('get',`/api/getTaxpayerDetails/${this.data.tPin}`)
        if (res.status == 200) {
       
          this.isLoading = false;
          this.option = true;
          this.isfound = true;
          this.data.tMail = res.data[0]["email_id"];
          this.data.tPhone = res.data[0]["mobile_number"];
          this.data.tName = res.data[0]["TAX_PAYER_NAME"];
          this.data.tStation = res.data[0]["STATION_NAME"];
          this.data.tMail= res.data[0]["EMAIL"];
          this.data.tPhone= res.data[0]["PHONE"];
          //get station id begins
          const resStationId = await this.callApi('get',`/api/getStationId/${this.data.tStation}`)
          if (resStationId.status==200) {
            this.data.stId = resStationId.data[0]["id"];
          }
      //get station id ends
        }
        else{
          if(res.status === 401){
            this.isLoading = false;
            this.error(res.data.msg)
          }
          else{
            this.swr()
          }
        }

    },

    async createCase() {
      if (this.data.tPin.trim() == "")
        return this.error("Taxpayer PIN is required!");
   
      if (this.data.objReason.trim() == "")
        return this.error("Objection reason is required");
        this.isLoading = true;
        console.log(this.data)
      const resCase = await this.callApi("post","/api/createManualCase",this.data);
      if (resCase.status == 200) {
        this.cases.unshift(resCase.data);
        this.success("Case Created Successfully!");
        
        this.isLoading = false;
        this.addModal =false;
        this.createModal = false;
        this.data.tPin = "";
        this.data.tStation = "";
        this.data.objReason = "";
      } else {
        if (resCase.status == 422) {
          if (resCase.data.errors) {
            this.error(resCase.data.errors[0]);
          }
        } else {
          this.swr();
        }
        this.isLoading = false;
      }
    },
    async edit() {
      this.isLoading = true
      var username= this.$store.state.user.USER_NAME
      var status=14
      var role = this.$store.state.userRole
      const newData = {...this.editData,username,status,role}
     
      const resEdit = await this.callApi('post', '/api/AdminReassignCase',newData)

      if (resEdit.status == 200) {
        this.cases.splice(this.index, 1)
        this.success('Case Assigned Successfully!')
        this.isLoading = false
        this.editModal = false
        this.editData = ''

        // window.location.reload();
      }
      else {
        if (resEdit.status == 422) {
          if (resEdit.editData.errors) {
            // this.error(resEdit.editData.errors.officer[0])   
            this.swr(resEdit.editData.errors)         
          }
        }
        else if(resEdit.status == 401){
          // this.error(resEdit.data.msg)
          this.swr(resEdit.data.msg)  
        }
        else {
          this.swr()
        }
        this.isLoading = false
      }
    },
    //case reassigning ends
    showEditModal(c, index) {
      let obj = {
        caseId: c.CASE_ID,
        am: c.AM_PNO,
        tl: c.TL_PNO,
        sm: c.SM_PNO,
        tso:c.TSO_PNO,
      }
      this.editModal = true
      this.editData=obj
      this.delData = obj
      this.index = index
    },
  },

  async created() {
   await this.fetchData()
},
}

</script>
