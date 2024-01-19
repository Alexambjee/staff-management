
<template>
  <div>
    
  <!-- table card -->
  <div class="card-header bg-dark text-muted d-flex justify-content-between">
    
    <span>
      <i class="fas fa-table me-1"></i>
      Manual Cases
    </span>
    <span  v-if="this.data.role != 'admin'" class="text-right"> <Button type="info" ghost 
      @click="createModal = true">Create Case</Button></span>
  </div>
  <div class="card-body table-responsive">
    <div class="form-group row">
            <div class="form-group d-flex mb-2">
              <div class="col" align="left">
                  <Button type="default" @click="print" >Export Csv</Button>
                  <Button type="default" >Print</Button>
              </div>
              <div class="float-right" align="right">
                <div class="col d-flex"> 
                    <Input class="w-75 mx-1" type="text" v-model="searchQuery" placeholder="Search..."/>  
                    <Button  type="default" @click="search_me">Search</Button>             
                </div>
              </div>
          </div>
              
    </div>
      <!-- <div class=" d-flex justify-content-between mb-3 mt-3"> -->
          <!-- <div class="form-group">
              <label class="label">Show</label>
              <Select v-model="length" @change="resetPagination()" placeholder="select.." style="width:100px;">
                  <Option value="10">10</Option>
                  <Option value="20">20</Option>
                  <Option value="30">30</Option>
              </Select>
              <label class="label">Entries</label>
          </div> -->
         
     
      <!-- </div> -->
      
      <table   class="table table-striped table-bordered table-hover" style="width:100%">
      <thead>
          <tr >
              <th v-for="(column, i) in columns" :key="column.name">{{column.label}}</th>
              <th>ACTION</th>                                
          </tr>
      </thead>
          <tbody>
              <tr v-for="(c, i) in cases" :key="c.id">
                  <td>{{c.CASE_ID}}</td>
                  <td>{{c.PIN_NO}}</td>
                  <td>{{c.TAXPAYER_NAME}}</td>
                  <td>{{c.SOURCE}}</td>
                  <td>{{c.created_at}}</td>
                  <td>{{c.STATUS_DESCRIPTION}}</td>
                  <td>
                      <router-link :to="`${path + c.CASE_ID}`">
                        <button class="btn btn-circle btn-success text-white btn-sm "
                            style="display: flex;align-items: center;justify-content: center;gap: 5px;" :disabled="column == 'AM_PNO'">
                            {{ action }}
                            </button> 
                          </router-link>
                  </td>
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
  <!-- table card ends-->

    <!-- history modal -->

<Modal v-model="createModal" title="Create New Case" :mask-closable="false" width="600" :styles="{ top: '60px' }" :closable="true">

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
                  <Option v-for="(obData, i) in obligation" :value='obData.OBLIGATION_ID' :key="i">{{ obData.OBLIGATION_NAME }}</Option>
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
                                  <div v-if="stage !='Details'" class="file-section top-header bg-dark d-flex justify-content-between" style="box-shadow:0 5px 10px rgba(0,0,0,0.1);">
                                      <span class="m-auto">
                                        <Upload 
                                      ref="upload"
                                      :on-success="handleSuccess"
                                      :headers="{'x-csrf-token' :token,'X-Requested-With':'XMLHttpRequest'}"
                                      :on-error="handleError"
                                      :format="['pdf','docx']"
                                      :on-format-error="handleFormatError"
                                      :max-size="20048"
                                      :on-exceeded-size="handleMaxSize"
                                      action="/api/upload" 
                                      
                  id="fileUpload" class="input mt-3">
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

  </div>
  
  </template>
  
  <script>

  export default {
      props:['path','bath','username','caseStatus','action','column'],
  data() {
      let sortOrders = {};
      let columns = [
      {label: 'CASE ID', name: 'CASE_ID' },
      {label: 'PIN', name: 'PIN_NO'},
      {label: 'TAXPAYER NAME', name: 'TAXPAYER_NAME'},
      {label: 'SOURCE', name: 'SOURCE'},
      {label: 'DATE CREATED', name: 'created_at'},
      {label: 'STATUS', name: 'status',width:'5%'},
      ];
      columns.forEach((column) => {
      sortOrders[column.name] = -1;
      });
      return {
        casesPerPage:10,
        searchQuery:'',
        isfound:false, 
        option : false,
          page:1,
          cases: [],
          obligation: [],
          columns: columns,
          sortKey: 'created_at',
          sortOrders: sortOrders,
          length: 10,
          search: '',
          tableShow: {
          showdata: true,
          },
          pagination: {
          currentPage: 1,
          total: '10',
          nextPage: '2',
          prevPage: '',
          from: '1',
          to: '10'
          },
          createModal: false,
          data: {
            filename:[],   
            tPin: "",
            tStation: "",
            tObligation: "",
            source:'MANUAL',
            objReason: "",
            tMail: "",
            tPhone: "",
            tName: "",
            stId: "",
            username: this.$store.state.user.USER_NAME,
            role: this.$store.state.userRole,
          },
      isLoading:false,
      }
          
  },
  beforeDestroy(){
      this.$store.commit('resetFormData')
    this.editModal = true

  },
  watch:{
      page (){
              this.fetchData().then(()=>void 0).catch(console.error)
      }

  },
  methods: {
    async search_me(){ 
      var Data=[this.username,this.column]
     
        var res = await this.callApi('get',`/api/getCases/${[this.caseStatus +"*"+ Data]}?page=${this.page}&manualOnly=1&query=${this.searchQuery}`)
        if (res.status === 200) {
          this.cases = res.data;
        } else {
          this.swr("something went wrong");
        }
      },
    async fetchData(){
    const  res1= await this.callApi("get", "/api/getObligations")
      
      if (res1.status === 200) {
          this.obligation = res1.data;
        } else {
          this.swr();
        }
   
    var Data=[this.username,this.column]
   
          const res = await this.callApi('get',`/api/getCases/${[this.caseStatus +"*"+ Data]}?page=${this.page}&manualOnly=1`)
          if(res.status===200){this.cases = res.data}else{this.swr('something went wrong')}
    
  },
   
  print() {
          this.$refs.table.exportCsv({
          filename: 'Case Export Table',
          original: true
           });
          
      },
    handleSuccess(res){
      this.data.filename.push(res.filenames[0]);
      this.isUploaded = this.filename.length > 0;
      },
      handleError(file){
          this.warning({
              title:'The file format is incorrect!',
              desc:`${file.errors.file.length ? file.errors.file[0] :'Something went wrong!'}`
          })
      },
      handleFormatError(file){
          this.$Notice.warning({
              title:'The file format is incorrect!',
              desc:'File format of ' + file.name + ' is incorrect, please select jpg, jpeg or png.'
          });
      },
      handleMaxSize(file){
          this.$Notice.warning({
              title:'Exceeding file size limit!',
              desc:'File '+file.name + 'is too large, no more than 21MB'
          });
      },
       //case reassigning ends
  showEditModal(c, index) {
    let obj = {
      caseId: c.CASE_ID,
      am: c.AM_PNO,
      tl: c.TL_PNO,
      sm: c.SM_PNO,
      tso:c.TSO_PNO,
      status:12,
      role: this.$store.state.userRole,
      username: this.$store.state.user.USER_NAME,
    }
    this.editModal = true
    this.editData=obj
    this.delData = obj
    this.index = index
  },

    remove(){
// changes
      this.isLoading = false;
      this.createModal = false;
      this.option = false;
      this.isfound = false;
      this.data.tPin = "";
      this.data.tStation = "";
      this.data.tName= "";
      this.data.tMail= "";
      this.data.tPhone= "";
      this.data.objReason = "";
    },
    async edit() {
    this.isLoading = true
    const resEdit = await this.callApi('post', '/api/AdminReassignCase',this.editData)

    if (resEdit.status == 200) {
      this.cases[this.index].AM_PNO = this.editData.am
      this.cases[this.index].TL_PNO = this.editData.tl
      this.cases[this.index].SM_PNO = this.editData.sm
      this.cases[this.index].TSO_PNO = this.editData.tso
      
      this.success('Case Reassigned Successfully!')
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
  
        this.data.tPhone = res.data[0]["mobile_number"];
        this.data.tName = res.data[0]["tax_payer_name"];
        this.data.tStation = res.data[0]["station_name"];
        this.data.tMail= res.data[0]["email_id"];
        this.data.tPhone= res.data[0]["mobile_number"];
      
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


      async createCase(){
      
    if (this.data.tPin.trim() == "")
      return this.error("Taxpayer PIN is required!");

    if (this.data.objReason.trim() == "")
      return this.error("Reason is required");
  
    this.isLoading = true;
    const resCase = await this.callApi("post","/api/createManualCase",this.data);
    if (resCase.status == 200) {
      this.cases.unshift(resCase.data[0]);
      this.success("Case Created Successfully!");
      this.remove()
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
      

    

  },
  
  async created() {
      await this.fetchData()

    }
  
  }
  </script>