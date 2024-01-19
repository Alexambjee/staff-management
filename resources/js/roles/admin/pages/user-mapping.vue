
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
            <h1 class="mt-4">Upload Mapping</h1>
            <div class="breadcrumb mb-4">
              <Breadcrumb>
                <BreadcrumbItem to="/admin" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                <BreadcrumbItem to="/admin/user-mapping" class="breadcrumb-item active">Upload Mapping
                </BreadcrumbItem>
              </Breadcrumb>
            </div>

            <div class="card detail-card mb-4">
              <div class="card-header bg-dark text-light">
                <form id="formMapping">
                  <div class="form-group">
                    <div class="table">
                      <tr>
                      </tr>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <i class="fa fa-list" aria-hidden="true"></i>&nbsp; 
                     User Mapping 
                    <small>(Allowed file formats: xlsx, csv & xls)</small>                      
                    </div>
                    <div class="col" align="right">
                      <div class='bg bg-primary' style="border-radius: 2px; width:350px" align="center">
                        <input type="file" name="mapping" class="btn btn-info" style="margin:5px;height:40px" >                   

                        <input type="submit" value="Upload" @click.prevent="saveUsers" class="btn btn-sm btn-info"
                          style="margin:5px;height:40px">                      
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="form-group row p-1">
                <div class="col-4" align="left">
                    <Button type="default" @click="print" >Export Csv</Button>
                    <Button type="default" >Print</Button>
                </div>
                <div class="col-5"></div>
                <div class="col-3 d-flex justify-content-between" align="right"> 
                  <Input class="mx-0" type="text" v-model="searchQuery" placeholder="Search..."/>  
                  <Button  type="default" @click="search_me">Search</Button>             
                </div>
        </div>
              <div v-if="isLoading">Loading...</div>

              <div v-else>

                <div class="card-body table-responsive">
          
                  <Table :border="showBorder" :stripe="showStripe" :width="width" :show-header="showHeader" :height="fixedHeader ? 'fit-content' : 'auto'" 
                  :size="tableSize" :data="filteredCases" :columns="Columns" ref="table" id="table"  />
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

                </div>

              </div>
              
            </div>

          </div>
        </main>

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
import Footer from '../../../components/footer.vue';
import Sidebar from '../../../components/sidebar.vue';
import Navbar from '../../../components/navbar.vue';
import axios from 'axios';
import Papa from 'papaparse';

export default {
  data() {
    return {
      page:1,
      searchQuery:'',
      data: {
        filename: '',
        username:this.$store.state.user.USER_NAME, 
        messagePath: '/admin/messages',
        manualPath: 'admin/Manual-cases',
        RolePath:'/admin/Temporary-role-change', 
        closedPath:'/admin/closed-cases',
        progressPath: 'admin/cases-progress',   
      },
      mapping: [],
      casesPerPage:10,
      total: 0,
      index:-1,
      searchData:'',
      searchTerm: "",
      isLoading: true,

      width: 'auto',
      showBorder: false,
      showStripe: true,
      showHeader: true,
      showIndex: false,
      showCheckbox: false,
      fixedHeader: true,
      tableSize: 'small',
    }
  },
  components: {
    Footer,
    Sidebar,
    Navbar
  },
  computed: {
            Columns () {
                let columns = [];
                    // this.loading = false
                
                    columns.push({
                        title: 'PIN NO',
                        key: 'PIN_NO',
                        sortable: true,
                        filterMultiple: false,
                        width: 'fit-content',
                        align: 'center',
                    });
                    columns.push({
                        title: 'AM PNO',
                        key: 'AM_PNO',
                        filterMultiple: true,
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        align: 'center',
                    });
                    columns.push({
                        title: 'TL PNO',
                        key: 'TL_PNO',
                        sortable: true,
                        filterable: true,
                        filterMultiple: true,
                        width: 'fit-content',
                        align: 'left',
                    });
                    columns.push({
                        title: 'SM PNO',
                        key: 'SM_PNO',
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        

                        align: 'left',
                    });
                    columns.push({
                        title: 'TSO PNO',
                        key: 'TSO_PNO',
                        sortable: true,
                        width: 'fit-content',
                        align: 'left',
                        filterable: true,
                    });
                    columns.push({
                        title: 'DATE CREATED',
                        key: 'created_at',
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        align: 'left',
                    });
                    
                    columns.push({
                        title: 'DATE UPDATED',
                        key: 'updated_at',
                        sortable: true,
                        filterable: true,
                        width: 'fit-content',
                        align: 'left',
                    });
                
                return columns;
            },
        // Formart date fetched from the DB
        formattedCases() {
            return this.mapping.map(_case => {
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
        // Search function that filters the formattedcases based on Taxpayer Name/Case ID/Taxpayer Pin
        filteredCases() {
            return this.formattedCases.filter(_case => {
                return _case.PIN_NO.toLowerCase().includes(this.searchTerm.toLowerCase());
            });
        }

    },
    watch:{
        page (){
          console.log('refreshing data')
                this.fetchData().then(()=>void 0).catch(console.error)
        }

    },
  methods: {
    async search_me(){  
      var res = await this.callApi('get',`/api/getUserMapping?query=${this.searchQuery}`)
      if (res.status === 200) {
        this.mapping= res.data
      } else {
        this.swr("something went wrong");
      }
    },
    async fetchData(){
      this.isLoading = true;
      if (!this.$store.state.user) {
        this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
      }
      else {
        this.token = window.Laravel.csrfToken
        const res = await this.callApi('get', `/api/getUserMapping?page=${this.page}`)
        
        if (res.status === 200) {
          this.mapping= res.data
          this.isLoading = false;
        }
        else {
          this.swr('something went wrong')
        }
        
      }
    
    },
    
    async print() {
      try{
        const res = await this.callApi('get', `/api/getUserMapping`);
        if (res.status === 200) {
            const data = res.data;
            const csvData = Papa.unparse(data, {
              header: true,
            });

            const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            if (link.download !== undefined) {
              const url = URL.createObjectURL(blob);
              link.setAttribute('href', url);
              link.setAttribute('download', 'CaseExportData.csv');
              link.style.visibility = 'hidden';
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
            }
          } else {
            this.swr("Something went wrong");
          }
        } catch (error) {
          this.swr("Something went wrong");
        }
    },
   async saveUsers() {
      var formMapping = $('#formMapping')[0]
      var data = new FormData(formMapping)
      const getData = await this.callApi('post', '/api/uploadMapping', data)
      if (getData.status==200) {
        this.mapping.unshift(getData.response)
       this.success('User mapping successfully uploaded.')
      } else if (getData.status==422) {
        this.error('The mapping must be a file of type: xlsx, csv, xls.');
      }
      window.location.reload();
    }
  },
  async created() {
    await this.fetchData()

  }
}
</script>
