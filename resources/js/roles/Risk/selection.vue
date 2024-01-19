<template>
    <div>
     
    <!-- navbar -->
      <Navbar />
         <div id="layoutSidenav">
         <Sidebar/>
            <div id="layoutSidenav_content">
                <main>
                  <div v-if ="Selection =='show_table'">
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Risk  Cases</h1>
                          <div class="breadcrumb mb-4 mt-3">
                             <Breadcrumb>
                              <BreadcrumbItem to="/risk" class="breadcrumb-item">Dashboard  </BreadcrumbItem>
                              <BreadcrumbItem to="/risk/risk_selection" class="breadcrumb-item active">Selection</BreadcrumbItem>
                          </Breadcrumb>
                        </div>
                        <div class="card shadow p-3 mb-5 bg-white rounded">
                      <caseTable :data_sent="data"/>
                        </div>
                      </div>
                   </div>
                   
      <div v-if ="Selection == 'show_selection'">
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Risk Selection</h1>
                          <div class="breadcrumb mb-4 mt-3">
                             <Breadcrumb>
                              <BreadcrumbItem to="/risk" class="breadcrumb-item">Dashboard  </BreadcrumbItem>
                              <BreadcrumbItem to="/risk/risk_selection" class="breadcrumb-item active">Selection</BreadcrumbItem>
                          </Breadcrumb>
                        </div>
                        <div class="card">

          <label>Station Name:</label>
             <select v-model="data.st2" required class="form-control" multiple="multiple" style="font-size: 0.9em; color:black" size="6px">
               <Option
                  v-for="(stData, i) in station"
                  :value='stData.station_id'
                  :key="i">
                {{ stData.station_name }}
                </Option> 
            <option v-for="(station, index) in station" :value="index">{{ station.station_name }}</option>
             </select>
            <label>Sector:</label>
        <select v-model="data.ind2" required class="form-control" multiple="multiple" style="font-size: 0.9em; color:black" size="6px">
              <option
          v-for="(inData, i) in industry"
          :value="inData.industry_id"
          :key="i">
        {{ inData.industy_name }}
        
        </option>
        </select>

        <label>Period:</label>
        <select v-model="data.period" required class="form-control" multiple="multiple" style="font-size: 0.9em; color:black" size="6px">
          <option v-for="year in years" :value="year">{{ year }}  </option>
        </select>
        <label>Risk Score Greater Than:</label>
        <input type="number" v-model="data.inscore" value=0 required min="0" style="font-size: 0.9em; color:black" class="form-control"/>
        
        <label>Rule Name:</label>
        <select v-model="data.rule2"  required class="form-control" multiple="multiple" style="font-size: 0.9em; color:black" size="6px">
          <option
          v-for="(rlData, i) in rule"
          :value='rlData.rule_id'
          :key="i">
          {{rlData.rule_name }}
        </option>
        </select>

        <!-- <label>Specifty Number of rows:</label> -->
        <!-- <input type="number" v-model="data.rows2" value=0 required min="0" style="font-size: 0.9em; color:black" class="form-control"> -->
        <!-- <button type="submit" class="btn btn-info " data-toggle="modal" >Submit</button>   -->
        <br/>
        <div class="log form-floating mb-3 " style="display: flex; justify-content: center;">
          <button  style="width:30%"  class="btn btn-success btn-block "
          @click="submit_data()" :disabled="isLoading" :loading="isLoading"
          ><span style="margin-bottom:10px !important;">{{isLoading ?'Please Wait.....':'Submit'}}</span></button>
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
   
</template>
<script>
import Footer from '../../components/footer.vue'
import Sidebar from '../../components/sidebar.vue'
import Navbar from '../../components/navbar.vue'
import caseTable from './riskTable.vue'

export default {
  props:['user'],
  data(){
    return{
      isLoading:false,
      Selection :'show_selection',
      yearStart: 2000,
      yearEnd: new Date().getFullYear(),
      station:[],
      industry:[],
      rule:[],
    
    data:{
      st2:[],
      period:[],
      rule2:[],
      inscore:'',
      ind2:[],
    },
  
    path:'/risk/risk_table/',
    username:this.$store.state.user.USER_NAME,
    role: this.$store.state.userRole,
    action:'Details',
    ApprovedAction: 'Details',
    NewAction: 'Start',
     
    }
  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    caseTable,
  },
  computed: {
    years() {
      const years = []
      for (let year = this.yearStart; year <= this.yearEnd; year++) {
        years.push(year)
      }
      return years
    }
  },

  methods:{
 
    async submit_data(){
             this.Selection = 'show_table'
    },

    async Get_tables(){
        const res = await this.callApi('get', `/api/fetchTables`);
        if (res.status === 200) {
            var x= res.data
            this.station= x[0]
            this.industry= x[1]
            this.rule=x[2]
            // this.success('Data found')
        }
        else {
            this.swr('Something Went Wrong!')
        }
    },
    
   
  },

  async created() {
    await this.Get_tables()
  }



}
</script>
