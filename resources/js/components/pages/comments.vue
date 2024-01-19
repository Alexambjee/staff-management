<template>

    <div class="card message-card">
    <!-- include an accordion -->
        <div class="card-header bg-dark text-muted collapsed"
         style="text-transform:uppercase; text-align:center; cursor:pointer;"
         data-toggle="collapse" 
        data-target="#Response" aria-expanded="false" aria-controls="#Response">
            COMMENTS
        </div>
        <!-- accordion -->
        <div id="accordion" class="col-lg-12 mt-3" style="width:100%">
            <!-- awaiting taxpayer response -->
            <div class="card shadow">
                <div id="Response" class="collapse" aria-labelledby="Response" data-parent="#accordion">
        
                    <!-- comments -->
                     <div class="card-body detail-card table-responsive">
                            <table  
                                style="border-style:none !important;width:100%;"
                                class="table no-wrap email-table table-striped table-hover" >
                                <thead class="bg-light text-dark">
                                    <tr>
                                        <th>NAME</th>
                                        <th>ROLE</th>                                                               
                                        <th>COMMENT</th>                                
                                        <th>DATE</th>                                
                                        <!-- <th>RECOMMENDATION</th>    rEVISIT JOIN                             -->
                                    </tr>
                                </thead>
                                    <tbody class="table-body" style="font-size:12px;">
                                        <tr v-for="(remark, i) in comments" v-if="comments.length">
                                            <td>{{remark.OTHER_NAMES + ' '}} ({{ remark.CREATED_BY_ }}) </td>
                                            <td>{{remark.ROLE_NAME}} </td>
                                            <td>{{remark.REMARKS}} </td>
                                            <td>{{ formatDate(remark.updated_at) }} </td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                    <!-- comments ends-->

                </div>
            </div>
            <!-- awaiting taxpayer response ends-->

        </div>
        <!-- accordion ends -->
                                                

           
                <!-- comment ends -->

    <!-- include an accordion ends-->
            
    </div>
          
</template>

<script>
export default {
    props:['case_id','comment'],
    data() {
        return {
            data: {
                caseId:this.case_id,
            },
            comments:[],
           
        }
    },
    methods:{
        formatDate(date) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },
    },
    async created() {
        const res = await this.callApi('get', `/api/get_comments/${this.data.caseId}`)
        if (res.status == 200) {
            this.comments = res.data
           
        }
        else {
            this.swr('Something Went Wrong!')
        }
    
    }
}
</script>