<template>
    <div>
        <div class="card">

            <div class="card-header bg-dark text-muted text-center">
                <span>CASE ANALYSIS</span>

            </div>


        </div>
        <!-- Case Analysis begins -->
        <table style="border:none !important;width:100%;" class="table shadow table-bordered table-striped mb-3">
            <thead class="table-header">
                <tr>
                    <th>CASE ID</th>
                    <th>SOURCE</th>
                    <!-- <th v-if="stage !='prelim'">RECOMMENDATION</th> -->
                    <th>STATUS</th>
                    <th>DATE CREATED</th>
                    <th>PREPARED BY</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <tr v-for="_case in details" :key="_case.CASE_ID">
                    <td>{{ _case.CASE_ID }}</td>
                    <td>{{ _case.SOURCE }}</td>
                    <!-- <td v-if="stage !='prelim'">Proceed with case</td> -->
                    <td>{{ _case.STATUS_DESCRIPTION }}</td>
                    <td>{{ _case.DATE_CREATED }}</td>
                    <td>{{ _case.OTHER_NAMES }}</td>
                </tr>
            </tbody>
        </table>
        <!-- <div class="col-md-6 text-left" v-if="rejectionId">
            <div class="card shadow">
                <div class="card-header">
                <h6 class="text-dark">REASON FOR REVERTING CASE:  <span class="text-danger ml-3">{{ReasonName}}</span></h6>
                </div>
    
            </div>
        </div> -->
        <hr />

        <!-- case details -->
        <div class="case-details">
            <!-- <div class="card-header bg-dark text-muted text-center mb-1">
                    <span>BACKGROUND OF THE CASE</span>
                </div> -->
            <div class="row">
                <!-- taxpayer details -->
                <div class="taxpayer-details col-sm-6 col-md-6 col-lg-6">
                    <div class="card shadow">
                        <div class="card-header headings" style="text-transform:uppercase; font-size:12px;">
                            Taxpayer Details
                        </div>
                        <div class="card-body">
                            <!-- Make Dynamic Done -->
                            <div class="card-content" v-for="taxd in formattedDetails" :key="taxd.CASE_ID">
                                <div class="card-items">
                                    <span class="text-left">pin</span>
                                    <p class="text-left">{{ taxd.PIN_NO }}</p>
                                </div>
                                <div class="card-items">
                                    <span class="text-left">Name</span>
                                    <p class="text-left">{{ taxd.TAXPAYER_NAME }}</p>
                                </div>
                                <div class="card-items">
                                    <span class="text-left">email</span>
                                    <p class="">{{ taxd.TP_EMAIL }}</p>
                                </div>
                                <div class="card-items">
                                    <span class="text-left">Telephone</span>
                                    <p class="">{{ taxd.TP_PHONE }}</p>
                                </div>
                                <div class="card-items">
                                    <span class="text-left">Station</span>
                                    <p class="text-left">{{ taxd.STATION_NAME }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- taxpayer details ends-->
                <!-- obligation details -->
                <!-- Not yet done -->
                <div class="taxpayer-details col-sm-6 col-md-6 col-lg-6">
                    <div class="card shadow">
                        <div class="card-header headings" style="text-transform:uppercase; font-size:12px;">
                            Risk Profile
                        </div>
                        <div class="card-body">
                            <div class="card-content">
                                <div class="card-items">
                                    <span class="text-left">Business Rule</span>
                                    <p class="">{{ history && history[0] && history[0].RULE_DESC ? history[0].RULE_DESC : "None" }}</p>
                                </div>
                                <div class="card-items">
                                    <span class="text-left">Risk Score</span>
                                    <p class="">{{ history && history[0] && history[0].RISK_SCORE ? history[0].RISK_SCORE : "None" }}</p>
                                </div>
                                <div class="card-items">
                                    <span class="text-left">Total Risk Score</span>
                                    <p class="">{{ history && history[0] && history[0].TOTAL_RISK_SCORES ? history[0].TOTAL_RISK_SCORES : "None" }}</p>
                                </div>
                                <div class="card-items">
                                    <span class="text-left">Industry</span>
                                    <p class="">{{ history && history[0] && history[0].INDUSTRY_DESC ? history[0].INDUSTRY_DESC : "None" }}</p>
                                </div>
                                <div class="justify-content-center align-items-center text-align-center">

                                    <span class="text-right text-primary" style="cursor:pointer"
                                        @click="historyModal = true">
                                        <span class="f-s-11">View History</span>
                                        <i class="fas fa-eye f-s-11"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- obligation details ends-->

            </div>

            <!-- other details -->
            <div class="card message-card" v-if="stage != 'prelim'">

                <div class="card-header bg-dark text-muted text-center">
                    <span>PRELIMINARY REPORT SUMMARY</span>
                </div>

                <div class="card shadow">
                    <div class="card-body detail-card table-responsive">
                        <table style="width:100%;" class="table shadow no-wrap email-table table-striped table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th>Tax Head</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <!-- <th>Assess Amount</th> -->
                                    <th>Prelim Amount</th>
                                    <th>Final Amount</th>
                                    <th>Recommendation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-body bg-light text-dark">
                                <tr v-for="(d, i) in period" :key="i">
                                    <td>{{ d.OBLIGATION_NAME ? d.OBLIGATION_NAME : "N/A" }}</td>
                                    <td>{{ d.YEAR_FROM ? formatDate(d.YEAR_FROM) : "N/A" }}</td>
                                    <td>{{ d.YEAR_TO ? formatDate(d.YEAR_TO) : "N/A" }}</td>
                                    <!-- <td>{{ d.ASSESSMENT_AMOUNT }}</td> -->
                                    <td>{{ d.PRELIM_REPORT_AMOUNT ? d.PRELIM_REPORT_AMOUNT.toLocaleString() : "N/A" }}</td>
                                    <td>{{ d.ASSESSMENT_AMOUNT ? d.ASSESSMENT_AMOUNT.toLocaleString() : "N/A" }}</td>
                                    <td>{{ d.REPORT_ACTION ? d.REPORT_ACTION : "N/A" }}</td>
                                    <td>{{ d.RECOMMENDATION ? d.RECOMMENDATION : "N/A" }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- other details ends -->
        </div>
        <!-- case details ends -->
        <!-- history modal -->
        <Modal title="Risk History" v-model="historyModal" width="66%vw" style="z-index: 1020;">
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Obligation</th>
                            <th>Industry</th>
                            <th>Business Rule</th>
                            <th>Indicators</th>
                            <th>Risk Score</th>
                            <th>Total Risk Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(h, i) in history" :key="i">
                            <td>{{ h.PERIOD_YEAR ? h.PERIOD_YEAR: "None" }}</td>
                            <td>{{ h.OBLIGATION_ID ? h.OBLIGATION_ID: "None" }}</td>
                            <td>{{ h.INDUSTRY_DESC ? h.INDUSTRY_DESC: "None" }}</td>
                            <td>{{ h.RULE_DESC ? h.RULE_DESC: "None" }}</td>
                            <td>{{ h.INDICATORS ? h.INDICATORS: "None" }}</td>
                            <td>{{ h.RISK_SCORE ? h.RISK_SCORE: "None" }}</td>
                            <td>{{ h.TOTAL_RISK_SCORES ? h.TOTAL_RISK_SCORES: "None" }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div slot="footer">
                <Button type="error" ghost size="small" @click="historyModal = false">Close</Button>
            </div>
        </Modal>
        <!-- history modal ends -->
    </div>
</template>
    
<script>
export default {
    props: ['Details', 'role', 'opt', 'rejectionId', 'ReasonName', 'stage'],
    data() {
        return {
            historyModal: false,
            details: [],
            period: [],
            history: [],
            comment: '',
            tpin: '',
        }
    },

    methods: {
        formatDate(date) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },
        async fetchDetails() {
            const caseId = this.$route.params.caseId;

            const res = await this.callApi('get', `/api/fetchDetails/${caseId}`)

            if (res.status === 200) {
                this.details = res.data.details
                this.period = res.data.period
                this.tpin = res.data.details[0]['PIN_NO']
                const tpin = this.tpin
                // console.log("Taxpayer pin:", tpin);
                const apires = await this.callApi('get', `/api/getRpHistory/${tpin}`)
                // console.log('apires:',apires.data);
                const blank = apires.data == 0
                if (apires.status === 200 && !blank) {
                    this.history = apires.data
                } 
                else {
                    this.history = null
                    this.success('The Taxpayer has no Risk History!')
                }
                
            } else {
                this.swr('something went wrong')
            }
        }
    },

    computed: {

        formattedDetails() {
            return this.details || null.map(taxd => {
                 taxd.TP_EMAIL = taxd.TP_EMAIL.toLowerCase();
                 taxd.PIN_NO = taxd.PIN_NO.toUpperCase();
                
                return taxd;
            });
        },
    },

    created() {
        this.fetchDetails()
    }
}
</script>
    