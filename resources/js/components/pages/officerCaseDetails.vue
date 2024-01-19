<template>
<!-- case details component -->
    <div class="card mb-4">
        <div class="card-header bg-dark card-header-table ">
            Case Analysis
        </div>
            <div class="card-body">
            <casedefault :Details="this.caseDetails"/>
             <div class="comments-details">
                <div class="row">
                <!-- comments -->
                    <div class="col-md-6">
                        <comments :case_id="case_id"/>
                    </div>
                <!-- comments ends -->
                <!--taxpayer response -->
                    <div class="col-md-6">
                        <TaxpayerResponse :case_id="case_id"  />
                    </div>
                <!--taxpayer response ends -->
                </div>
            </div>
        </div>
    </div>
<!-- case details component ends-->

</template>
<script>
import casedefault from './cases.vue'
import TaxpayerResponse from './taxpayer-response.vue'
import comments from './comments.vue'
import attachments from './attachments.vue'
export default {
    props:['case_id','type_id'],
    data(){
        return {
            data: {
                option:this.type_id
            },
            caseDetails: [],
        };
},
    components : {
        casedefault,
        TaxpayerResponse,
        comments,
        attachments
    },
    methods :{

        },
       
    async created(){
        const resCase = await this.callApi('get',`/api/fetchDetails/${this.case_id}`)

        if(resCase.status == 200){
            this.caseDetails = resCase.data
            // this.data.option = this.caseDetails[0]['TYPE_ID']
            // console.log(this.data.option)

        }
        else{
            this.swr('Something went wrong!')
        }
    
  }
    
}
</script>
