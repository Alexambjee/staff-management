<template>
    <div>
    <!-- table card -->
     <div class="card-body table-responsive">
        <div class="control d-flex justify-content-between mb-4">
            <div class="form-group row">
                <div class="col" align="left">
                    <Button type="default" @click="print" >Export Csv</Button>
                    <Button type="default" >Print</Button>
                </div>
           
            </div>
            
            <div class="form-group">
                    <div class="float-right">
                        <div class="col d-flex justify-content-between" align="right"> 
                            <Input class="mx-1" type="text" v-model="searchQuery" placeholder="Search..."/>  
                            <Button  type="default" @click="search_me">
                                Search
                            </Button>             
                        </div>
                    </div>
                    
            </div>
        </div>
        <div  v-if="action == 'Assign'" >
            <div class="form-group">
                <el-checkbox v-model="selectAll" @change="handleSelectAll">{{ selectAll ? 'Deselect All' : 'Select All' }}</el-checkbox>
                <!-- <div class="float-right mt-2 me-3">
                    <Button class="bg-warning color-white text-white" style="margin-top:-30px ;" @click="handleSelection">Assign Multiple</Button>
                </div> -->
            </div>
        </div>
        <!-- table -->
        
    
        <Table :border="showBorder" :stripe="showStripe" :width="width" :show-header="showHeader" :height="fixedHeader ? 'fit-content' : 'auto'" 
        :size="tableSize" :data="filteredCases" :columns="Columns" ref="table" id="table"  />

        <div  v-if="action == 'Assign'" class="form-group row">
            <div class="form-group mt-4 mb-0">
                <!-- <el-checkbox v-model="selectAll" @change="handleSelectAll">{{ selectAll ? 'Deselect All' : 'Select All' }}</el-checkbox> -->
                <div class="float-right mt-1 mb-0">
                    <Button class="bg-warning color-white text-white" style="margin-top:-30px ;" @click="handleSelection">Assign Multiple</Button>
                </div>
            </div>
        </div>
        
        <div style="margin: 10px;overflow: hidden">
            <div style="float: right;">
              <ul class="pagination d-flex" style="list-style-type: none">
            <li v-if="page > 1">
              <Button @click="page=page-1">Previous</Button>
            </li>
            <li v-for="pagex in Math.ceil(filteredCases.length/casesPerPage)" :key="page" >
              <Button @click="page=page" :class="'btn btn-primary'">{{ page }}</Button>
                <Button @click="page=pagex + page" :class="pagex + page === page ? 'btn btn-primary ':''">{{ pagex+page }}</Button>
            </li>
            <li v-if="page< filteredCases.length ">
                <Button @click="page=page+1">Next</Button>
            </li>
          </ul>
              </div>
        </div>
        
        <!-- table ends-->
        <!-- assign modal -->
        <div v-if="action == 'Assign' ">
            <Modal
            v-model="Assignmodal"
            title="Assign Case"
            :mask-closable="false"
            width="500"
            :closable="true">
                 <!-- case details  -->
                <div class="card">
                    <div class="card card-detail">
                        <div class="card-body">
                            <form>
                                <div class="form-group d-flex justify-content-around ">
                                        <label class="label">Case ID</label>                                    
                                        <Input type="text" placeholder="Enter Case ID"
                                        disabled v-model="editData.caseId" style="width:60%;" />
                                </div>
                                <div class="form-group d-flex mt-3 justify-content-around">
                                    <label class="label">Account Manager <span class="ml-2 text-danger">*</span></label>                                    
                                    <Space/>
                                
                                     <!-- <Option v-for="(obData, i) in obligation" :value='obData.OBLIGATION_NAME' :key="i"> -->
                                                                                 
                                                                   
                                    <Select v-model="selectedoff" style="width:100%">
                                    <Option v-for="(off, i) in officer" :value='off' :key="i">
                                        {{ off }}
                                    </Option>
                                    </Select> 
                                                   
                                </div>                            
                            </form>
                        </div>
                    </div>
                    
                </div>
                <!-- end of case details -->
                <div slot="footer">
                    <Button type="info" :loading="isLoading" @click="submit()">
                    {{isLoading ?'Please wait':'Submit'}}</Button>
                    <Button type="error" @click="remove()">Close</Button>
                </div>
                           
        </Modal>
        </div>
        <!-- assign modal ends-->
       
        </div>
    <!-- table card ends-->
    </div>
    </template>
    
    <script>
    export default {
        props:['path','bath','username','caseStatus', 'caseStatus1', 'caseStatus2','action','column'],
        
        data() {
        return {
            casesPerPage:10,
            searchQuery:'',
            selectAll: false,
            cases: [],
            page:1,
            Assignmodal: false,
            selectedoff:'',
            editData: {},
            searchData:'',
            width: 'auto',
            searchTerm: "",
            officer:[],
            total:3,
            index:-1,
          
            showBorder: false,
            showStripe: true,
            showHeader: true,
            showIndex: false,
            showCheckbox: false,
            fixedHeader: true,
            tableSize: 'small',
      
            
        }
            
    },
    watch:{
        page (){
                this.fetchData().then(()=>void 0).catch(console.error)
        },
},
    computed: {
            Columns () {
                let columns = [];
                if (this.action =='Assign' ){
                    columns.push({
                    type: 'selection',
                    width: 60,
                    align: 'center',
                    key:'CASE_ID'
                });
                }
              
                    columns.push({
                        title: 'CASE ID',
                        key: 'CASE_ID',
                        sortable: true,
                        filterMultiple: false,
                        width: 'fit-content',
                        align: 'center',
                    });
                    columns.push({
                        title: 'TAXPAYER PIN',
                        key: 'PIN_NO',
                        filterMultiple: true,
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        align: 'center',
                    });
                    columns.push({
                        title: 'TAXPAYER NAME',
                        key: 'TAXPAYER_NAME',
                        sortable: true,
                        filterable: true,
                        filterMultiple: true,
                        width: 'fit-content',
                        align: 'left',
                    });
                    columns.push({
                        title: 'SOURCE',
                        key: 'SOURCE',
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        align: 'left',
                    });
                    columns.push({
                        title: 'STATUS',
                        key: 'STATUS_DESCRIPTION',
                        sortable: true,
                        width: 185,
                        align: 'left',
                        filterable: true,
                    });
                    columns.push({
                        title: 'DATE CREATED',
                        key: 'DATE_CREATED',
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        align: 'left',
                    });
                    
                columns.push({
                        title: 'ACTION',
                        key: 'Action',
                        width: 'fit-content',
                        align: 'center',
                        render: (h, params) => {
                            
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'info',
                                        size: 'small',
                                        ghost:true
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            // this.$router.push({ path: this.$route.path + '/' + params.row.CASE_ID });
                                            this.show(params.index)
                                        }
                                    }
                                }, this.action)
                            ]);
                            }
                        
                    }); 
                
                return columns;
            },
        // Formart date fetched from the DB
        formattedCases() {
            // Copy the cases array to avoid modifying the original array
            let casesCopy = [...this.cases];
            casesCopy.sort((a, b) => {
                return b.STATUS - a.STATUS;
            });
            return this.cases.map(_case => {
                let date = new Date(_case.DATE_CREATED);
                _case.DATE_CREATED = date.toLocaleDateString('en-GB', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                _case.PIN_NO = _case.PIN_NO.toUpperCase();
                return _case;
            });
        },
        // Filter/Search cases
        filteredCases() {
            return this.formattedCases.filter(_case => {
                return _case.TAXPAYER_NAME.toLowerCase().includes(this.searchTerm.toLowerCase().trim()) ||
                _case.CASE_ID.toLowerCase().includes(this.searchTerm.toLowerCase().trim()) ||
                _case.PIN_NO.toLowerCase().includes(this.searchTerm.toLowerCase().trim());
            });
        }
    },

    methods: {
        handleSelectAll(val) {
            this.$refs.table.toggleAllSelection(val)
        
  },
  handleSelection() {
   
  const selectedItems = this.$refs.table.getSelection();
  const selectedIds = selectedItems.map(item => item.CASE_ID);
  if (selectedIds != ''){
  let obj = {
                caseId: selectedIds,
                officer: this.officer,
            }
            this.editData = obj
            this.Assignmodal = true
        }else{
            this.swr("Please select the cases")    
        }
},
async submit(){
  
    this.isLoading = true
    var username= this.$store.state.user.USER_NAME

    const newData = {
        cass:this.editData.caseId,
        officer:this.selectedoff,
        username:username
    }
    const resEdit = await this.callApi('post', '/api/ReassignCase',newData)

      if (resEdit.status == 200) {
        this.success('Case Reassigned Successfully!')
        this.remove()
      }
      else {
        if (resEdit.status == 422) {
          if (resEdit.data.errors) {
            this.swr("Missing case Ids or Username")    
          }
        }
        else if(resEdit.status == 401){
          this.swr(resEdit.data.msg)  
        }
        else {
          this.swr()
        }
        this.remove()
      }
    
},

remove(){
        this.isLoading = false
        this.Assignmodal = false
        this.editData = ''
        this.selectAll=false

},

async getoff(){
            const res = await this.callApi('get',`/api/getassOff/${this.username +"*"+ this.column}`)
            if(res.status===200){
                this.officer = res.data
            }else{
                this.swr('something went wrong')
            }
  
},

        print() {
            this.$refs.table.exportCsv({
            filename: 'Case Export Table',
            original: true
             });
            
        },
        async search_me(){
            if (this.$store.state.userRole == 'Admin'){

                console.log(this.$store.state.user.station)
                // let Data = [this.username, this.column]      
                var res = await this.callApi('get',`/api/getCases/?page=${this.page}&role=${role}&station=${station}&query=${this.searchQuery}`)
                if (res.status === 200) {
                    this.cases = res.data;
                    this.total=this.cases.length
                } else {
                    this.swr("something went wrong");
                }

            }else{
            let Data = [this.username, this.column]      
          var res = await this.callApi('get',`/api/getCases/${[this.caseStatus +"*"+ Data]}?page=${this.page}&query=${this.searchQuery}`)
          if (res.status === 200) {
            this.cases = res.data;
            this.total=this.cases.length
          } else {
            this.swr("something went wrong");
          }
        }
        },



        async fetchData() {
       
                if (this.$store.state.userRole == 'Admin'){
                let role = this.$store.state.userRole
                let station =this.$store.state.user.station_id
                console.log(this.caseStatus)
                var res = await this.callApi('get',`/api/getCases/${[this.caseStatus]}?page=${this.page}&role=${role}&station=${station}`)
                console.log( res);
                if (res.status === 200) {
                this.cases = res.data;
                this.total=this.cases.length
                } else {
                this.swr("something went wrong");
                }

}else{
            let Data = [this.username, this.column]
         
            const res = await this.callApi('get',`/api/getCases/${[this.caseStatus +"*"+ Data]}?page=${this.page}`)
            if(res.status===200){
                this.cases = res.data
            }else{
                this.swr('something went wrong')
            }

            let data = res.data
            return data;
        }     
        },

   

        
        show(index) {
            if (this.action == 'Assign') {
                let obj = {
                caseId: [this.cases[index].CASE_ID],
                officer: this.cases[index].username,
            }
           
            this.editData = obj
            this.index = index
            this.Assignmodal = true
        }
            else {
                this.$router.push({path:this.path +`${this.cases[index].CASE_ID}`})
            }
        },
    
    },
    
    created() {
        this.fetchData() 
        console.log(this.cases)
        console.log("User role:", this.$store.state.userRole);
        if (this.action == 'Assign') {
                 this.getoff()
        }
       
      }
    
    }
    </script>