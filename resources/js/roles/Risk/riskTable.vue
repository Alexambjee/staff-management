<template>
        <div>
    <!-- table card -->
     <div class="card-body table-responsive">
        <div class="control d-flex justify-content-between mb-4">
            <div class="form-group row">
                <div class="col" align="left">
                    <Button type="default" @click="print" >Export Csv</Button>
                    <!-- <Button type="default" >Print</Button> -->
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
        <!-- table -->
        <Table :border="showBorder" :stripe="showStripe" :width="width" :show-header="showHeader" :height="fixedHeader ? 'fit-content' : 'auto'" 
        :size="tableSize" :data="cases" :columns="Columns" ref="table" id="table"  />


        
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
      <div class="text-center ">
        <Input v-model="message_text" type="textarea" class="w-25 p-3" placeholder="Enter Message..." />
      </div>
      <div class="text-center mt-5 mb-0">
          <Button class="bg-warning color-white text-white" style="margin-top:-30px ;" @click="handleSelection" :disabled="isLoading" :loading="isLoading">
          <span style="margin-bottom:10px !important;">{{isLoading ?'Please Wait.....':'create case(s)'}}</span></Button>
      </div>
        </div>
      </div>
    </div>
</template>
<script>

export default {
  props:['data_sent'],
  data(){
    return{
      isLoading:false,
      selected_cases:{},
      message_text:'',
      cases:[],
      casesPerPage:10,
      searchQuery:'',
      searchData:'',
      width: 'auto',
      searchTerm: "",
      page:1,
      path:'/risk/risk_found/',
      username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
      action:'Details',
      ApprovedAction: 'Details',
      NewAction: 'Start',
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
                this.submit_data_1().then(()=>void 0).catch(console.error)
        }
      },
computed: {
            Columns () {
                let columns = [];
              
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
                        title: 'INDUSTRY',
                        key: 'INDUSTRY',
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        align: 'left',
                    });
                    columns.push({
                        title: 'STATION',
                        key: 'STATION',
                        sortable: true,
                        width: '80',
                        align: 'left',
                        filterable: true,
                    });
                    columns.push({
                        title: 'PERIOD',
                        key: 'PERIOD',
                        sortable: true,
                        filterable: true,
                        width: '80',
                        align: 'left',
                    });
                    
                  columns.push({
                          title: 'INDICATORS',
                          key: 'INDICATORS',
                          width: 'fit-content',
                          align: 'center',
                  });
                    columns.push({
                          title: 'RULE NAME',
                          key: 'RULE_NAME',
                          sortable: true,
                          filterMultiple: false,
                          width: 'fit-content',
                          align: 'center',
                      });
                      columns.push({
                          title: 'RULE SCORE',
                          key: 'RULE_SCORE',
                          sortable: true,
                          filterMultiple: false,
                          width: '80',
                          align: 'center',
                      });
                    columns.push({
                        title: 'TOTAL RISK SCORE',
                        key: 'TOTAL_RISK_SCORE',
                        sortable: true,
                        filterMultiple: false,
                        width: '80',
                        align: 'center',
                    });
                    columns.push({
                                title: 'SELECT',
                                key: 'select',
                                width: 'fit-content',
                                type: 'selection',
                                width: 60,
                                align: 'center',
                    });
            return columns;

          },
    },
  
  methods:{
    async search_me(){      
          var res = await this.callApi('post',`/api/submitRisk?page=${this.page}&query=${this.searchQuery}`,this.data_sent)
          if (res.status === 200) {
            this.cases = res.data;
          } else {
            this.swr("something went wrong");
          }
        },
        handleSelectAll(val) {
            this.$refs.table.toggleAllSelection(val)
        },
        handleSelection() {
          
          if (this.message_text ==''){
            this.swr("Fill in the message")   
            }else{
            const selectedItems = this.$refs.table.getSelection();
            const selectedIds = selectedItems.map(item => {return{
              PIN_NO:item.PIN_NO,
              STATION:item.STATION,
              TAXPAYER_NAME:item.TAXPAYER_NAME,
              PERIOD:item.PERIOD,
              RULE_ID:item.RULE_ID
        }});

            if (selectedIds != ''){
              
            let selected_cases_ = {
                           data:selectedIds,
                           msg:this.message_text
                            }
                      this.submit_cases(selected_cases_)
                       // Update the cases data object by filtering out the selected cases
                        this.cases = this.cases.filter(caseItem => {
                          return !selectedItems.includes(caseItem);
                        });
                        // Clear the selection
                        this.$refs.table.clearSelection();
                  }else{
                      this.swr("Please select the cases")    
                  }
              }},
async submit_cases(x){
  this.isLoading=true
  const res = await this.callApi('post', `/api/create_risk_cases`,x);
        if (res.status === 200) {
          this.isLoading=false
             this.success('Cases created')
        }
        else{
          this.isLoading=false
            this.swr('Something Went Wrong!')
        }
  },

  async submit_data_1(){
      const res = await this.callApi('post', `/api/submitRisk?page=${this.page}`,this.data_sent);
        if (res.status === 200) {
             this.cases = res.data
        }
        else{
            this.swr('Something Went Wrong!')
        }
    },
    print() {
            this.$refs.table.exportCsv({
            filename: 'Case Export Table',
            original: true
             });
            
        },
  },

  async created() {
    await this.submit_data_1()
  },



}
</script>
