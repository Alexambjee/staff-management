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
            <h1 class="mt-4">MANUAL CASE</h1>
            <div class="breadcrumb mb-4">
              <Breadcrumb>
                <BreadcrumbItem to="/admin" class="breadcrumb-item"
                  >Dashboard</BreadcrumbItem
                >
                <BreadcrumbItem
                  to="/admin/Manual-cases"
                  class="breadcrumb-item active"
                  >Manual cases</BreadcrumbItem
                >
              </Breadcrumb>
            </div>

            <div class="card mb-4 detail-card">
              <div class="card-header bg-dark text-light">
                 <div class="row">
                <div class="col">
                  <i class="fa fa-list" aria-hidden="true"></i>&nbsp;Manual cases Upload
                </div>
                <form id="formMapping">
                  <div class="row">
                   
                    <div class="col" align="right">
                      <div class='bg bg-primary' style="border-radius: 2px; width:350px" align="center">
                        <small>(Allowed file formats: xlsx, csv & xls)</small>    
                        <input type="file" name="cases" class="btn btn-info" style="margin:5px;height:40px" >                   

                        <input type="submit" value="Upload" @click.prevent="saveUsers" class="btn btn-info"
                          style="margin:5px;height:40px" />
                          <!-- //////////////////////// -->                       
                      </div>
                    </div>
                  </div>
                  <!-- <Button type="default" class="btn btn-sm btn-success" @click="addModal = true"><i class="fa fa-file text-dark" aria-hidden="true"></i>&nbsp;Upload Many Manual cases</Button> -->
              
              </form>
              </div>
              </div>
              <div class="form-group row">
                <div class="col" align="left">
                    <Button type="default" @click="print" >Export Csv</Button>
                    <Button type="default" >Print</Button>
                </div>
                <div class="col" align="right"> 
                      <input type="text" v-model="searchQuery" placeholder="Search...">  
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
                      <th>CASE ID</th>
                      <th>TAXPAYER PIN</th>
                      <th>TAXPAYER NAME</th>
                      <th>DATE CREATED</th>
                      <th>CASE SOURCE</th>
                      <th>STATUS</th>
                      <th>ACCOUNT MANAGER</th>
                      <th>TEAM LEAD</th>
                      <th>SECTOR MANAGER</th>
                      <th>TSO</th>
                      <th>assign</th>
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
            <Button type="error" @click=" remove()" size="small">Close &nbsp;<i class="fa fa-times-circle" aria-hidden="true" style="width:35px"></i></Button>
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
        searchData:[],
      },
      casesPerPage:10,
      searchQuery:'',
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
     // edit data
     
      editData: {
        caseId: '',
        am: '',
        tl: '',
        sm: '',
        tso: '',
        username: this.$store.state.user.USER_NAME,
        role: this.$store.state.userRole,
        
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
        },

    },

  methods: {
    async search_me(){    
          var res = await this.callApi('get',`/api/getAllCases/?page=${this.page}&manualOnly=1&role=${this.$store.state.userRole}&query=${this.searchQuery}`)
          if (res.status === 200) {
            
            this.cases = res.data;
            console.log(this.cases );
            this.total=this.cases.length
          } else {
            this.swr("something went wrong");
          }
        },
   async fetchData(){
    var cx = this.$store.state.userRole
      const [res, resStation, resObligation] = await Promise.all([

      this.callApi('get',`/api/getAllCases/?page=${this.page}&manualOnly=1&role=${cx}&station=${this.$store.state.user.station_id}`),
      this.callApi("get", "/api/getStations"),
      this.callApi("get", "/api/getObligations"),
    ]);
    // status
    console.log(res);
    if (res.status === 200) {
    
      this.cases = res.data;
      this.total=this.cases.length
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
      async saveUsers() {
      this.isLoading = true
      var formMapping = $('#formMapping')[0]
      var data = new FormData(formMapping)
      const getData = await this.callApi('post', '/api/uploadManual', data)
      if (getData.status==200) {
        this.isLoading = false
        this.cases.unshift(getData.response)
        console.log(getData.response);
       this.success('User mapping successfully uploaded.')
      } else if (getData.status==422) {
        this.isLoading = false
        this.error('The mapping must be a file of type: xlsx, csv, xls.');
      }
      this.isLoading = false
      // window.location.reload();
    },
     
    print() {
            this.$refs.table.exportCsv({
            filename: 'Case Export Table',
            original: true
             });
            
        },


    async edit() {
      this.isLoading = true
      var username= this.$store.state.user.USER_NAME
      var status= 12;
     
      const newData = {...this.editData,username,status
      }
      
      const resEdit = await this.callApi('post', '/api/AdminReassignCase',newData)

      if (resEdit.status == 200) {
        this.cases[this.index].AM_PNO = this.editData.am
        this.cases[this.index].TL_PNO = this.editData.tl
        this.cases[this.index].SM_PNO = this.editData.sm
        this.cases[this.index].TSO_PNO = this.editData.tso
        
        this.success('Case Reassigned Successfully!')
        await this.fetchData()
        this.isLoading = false
        this.editModal = false
        this.editData = ''
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
        username: this.$store.state.user.USER_NAME,
        role: this.$store.state.userRole,
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
