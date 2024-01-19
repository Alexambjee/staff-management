<template>
<div>
<!-- table card -->
 <div class="card-body table-responsive">
    <table  class="table table-striped table-bordered table-hover" style="width:100%">
    <thead>
        <tr>
            <th>CASE ID</th>
            <th>TAXPAYER PIN</th>
            <th>TAXPAYER NAME</th>
            <!-- <th>ASSESSMENT NUMBER</th> -->
            <th>PRELIM_REPORT_AMOUNT</th>
            <!-- <th v-if="($store.state.userRole =='Report')">OFFICER</th> 
            <th v-if="($store.state.userRole=='Report' || $store.state.userRole=='report')">MANAGER</th>
            <th v-if="($store.state.userRole=='Report'|| $store.state.userRole=='report' )"> CHIEF MANAGER</th> 
            -->
            <th>REPORT_FIN_AMOUNT</th> 
            <th>DATE CREATED</th> 
   
            <th>STATUS</th>
        </tr>
    </thead>
        <tbody>
                 <tr v-for="(c, i) in caseProgress" :key="i">

                <td>{{c.CASE_ID}}</td>
                <td>{{c.PIN_NO}}</td>
                <td>{{c.TAXPAYER_NAME}}</td>
                <td>{{c.PRELIM_REPORT_AMOUNT}}</td>
                <td>{{c.REPORT_FIN_AMOUNT}}</td>
                <td>{{moment(c.created_at).format('lll')}}</td> 
                 <td>{{c.STATUS_DESCRIPTION}}</td> 
            </tr>
        </tbody>
        <!-- `${c.CASE_ID}`' -->
        </table>
        <div style="margin: 10px;overflow: hidden">
                  <div style="float: right;">
                  <ul class="pagination d-flex" style="list-style-type: none">
                      <li v-if="page > 1">
                        <Button @click="page=page-1">Previous</Button>
                      </li>
                      <li v-for="pagex in [0,1,2,3,4,5]" :key="page" >
                          <Button @click="page=pagex + page" :class="pagex + page === page ? 'btn btn-primary ':''">{{ pagex+page }}</Button>
                      </li>
                      <li v-if="page< caseProgress.length">
                          <Button @click="page=page+1">Next</Button>
                      </li>
                    </ul>
                    </div>
              </div>

    </div>
<!-- table card ends-->
</div>
</template>

<script>
export default {
    props:['channel','column','username'],
    data(){
        return{
            page:1,
            data:{
                // column: this.column,
                // username: this.username
                // caseStatus:[this.caseStatus]
            },
            caseProgress:[],
        }
    },
    watch:{
        page (){
          console.log('refreshing data')
                this.fetchData().then(()=>void 0).catch(console.error)
        }

    },

methods: {
    async fetchData(){
        const res = await this.callApi('get',`/api/getAdminProgress/${[this.data.caseStatus ]}?page=${this.page}`)
    // status
    if (res.status === 200) {
      this.caseProgress = res.data;
      
    } else {
      this.swr("something went wrong");
    }

  },
    
},
  async created() {
    this.fetchData()
  }


}
</script>