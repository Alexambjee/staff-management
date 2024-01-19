<template>
    <!-- case details component -->
        <div class="card shadow mb-4">
    
                <div class="card-body">
    
                <!-- <casedefault :stage="stage" /> -->
        
                 <div class="comments-details" v-if="stage != 'prelim'">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <!-- <comments :case_id="data.caseId"/> -->
                        </div>
            
                    </div>
                </div>
                <div class="comments-details">
                    <!-- <form> -->
                        <div class="row ">
                           
                        <!-- comment section -->
                        <!--attachment section -->
                        <div :class="`${smallCol}`">
                        <div class="card detail-card">
                         <div class="card-header pb-2 bg-dark text-muted text-center">
                                <h5 class="text-muted">ATTACHMENTS</h5>
                         </div>
                            <div class="card-body">
                                <!-- attachment lists -->
                                <attachments :case_id="this.data.caseId" :tPin="data.tpin" :stage="stage"/>
                                <!-- attachment list ends -->
                                 <!-- <div v-if="stage !='Details'" class="file-section top-header bg-dark d-flex justify-content-between" style="box-shadow:0 5px 10px rgba(0,0,0,0.1);">
                                    <span class="m-auto">
                                        <Upload ref="upload" :on-success="handleSuccess"
                                            :headers="{ 'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest' }"
                                            :on-error="handleError" :format="['pdf', 'docx','zip','rar']"
                                            :on-format-error="handleFormatError" :max-size="21000"
                                            :on-exceeded-size="handleMaxSize" action="/api/upload" 
                                            :data="{ caseId: this.data.caseId }" :multiple="true" id="fileUpload" class="input mt-3">
                                            <Button icon="ios-cloud-upload-outline">Upload File</Button>
                                            <small class="text-muted">(.pdf,.docx,.zip or .rar)</small>
                                        </Upload>
                                        <small id="error"></small>
                                        <input type="hidden" name="caseId" v-model="caseId"/>
                                    </span>

                                    </div> -->
                                    <div v-if="stage !='Details'" class="file-section top-header bg-dark d-flex justify-content-between" style="box-shadow:0 5px 10px rgba(0,0,0,0.1);">
                                        <span class="m-auto">
                                            <Upload ref="upload" :on-success="handleSuccess"
                                                :headers="{ 'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest' }"
                                                :on-error="handleError" :format="['pdf', 'docx','zip','rar','xls','xlsx']"
                                                :on-format-error="handleFormatError" :max-size="21000"
                                                :on-exceeded-size="handleMaxSize" action="/api/upload" 
                                                :data="{ caseId: this.data.caseId }" :multiple="true" id="fileUpload" class="input mt-3">
                                                <Button icon="ios-cloud-upload-outline">Upload File</Button>
                                                <small class="text-muted">(.pdf,.docx,.zip ,.xls,.xlsx or .rar)</small>
                                            </Upload>
                                            <small id="error"></small>
                                            <input type="hidden" name="caseId" v-model="caseId"/>
                                        </span>

                                    </div>
                            </div>
                        </div>
                    </div>
                        <!-- attachment section ends-->
                        <!-- action section -->
                        <div :class="`${bigCol}`" v-if="stage !='Details'">
                            <div class="card ">
                            <div class="card-header bg-dark  text-muted"
                                    style="text-transform:uppercase; text-align:center; ">
                                  {{ actionTitle }}
                                </div>
                                <!-- actions -->
                                <div class="card-body detail-card" >
                                <br/>
                                    <div class="row">
                                        <!-- prelim ends -->
                                        <!-- Decision (approve/reject) stages -->
                                        <div class="col-md-12" v-if="stage =='Decision'">
                                            <!-- <Space wrap> -->
                                                <div></div>
                                                <Col span="13"  offset="5">
                                                    <div class="mt-2">
                                                        <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                            <label class="mb-3 label mb-2">Action <span class="text-danger ml-2">*</span></label>
                                                            <Select v-model="ApproveData.option" style="width:100%" placeholder="Select action...">
                                                                <Option value="Approve">Approve</Option>
                                                                <Option value="Revert">Revert</Option>
                                                              
    
                                                            </Select>
                                                        </div>
                                                    </div>
                                                </Col>
                                            <!-- </Space> -->
                                            <!-- <Space wrap> -->
                                                <Col span="13"  offset="5">
                                                 
                                                    <div class="mt-4" v-if="ApproveData.option == 'Approve'">
                                                      
                                                        <div class="form-group ml-2 d-flex align-items-center mt-3">
                                                            <label class="mb-3 label mb-2">Remarks <span class="text-danger ml-2">*</span></label>
                                                            <Input v-model="data.Remarks" placeholder="Enter Remarks" type="textarea" :rows="6" />
                                                        </div>
                                                            <div class="form-group text-center mt-5">
                                                                <Space wrap>
                                                                    <Col span="8"  offset="10">
                                                                        <Button type="info" size="medium"
                                                                        @click="aprrove()" :disabled="isLoading" 
                                                                        :loading="isLoading">{{isLoading ?'Please wait...' :'Submit'}}</Button>
                                                                    </Col>
                                                                    
                                                                </Space>
                                                            
                                                            </div>
                                                  
                                                    </div>
                                                        <!-- approve option ends -->
                                                        <!-- reject option -->
                                                    <div class="mt-4" v-if="ApproveData.option == 'Revert'">
                                         
                                                        <div class="form-group ml-2 d-flex align-items-center mt-3">
                                                            <label class="mb-3 label mb-2">Remarks <span class="text-danger ml-2">*</span></label>
                                                            <Input v-model="RejectData.Remarks" placeholder="Enter Remarks" type="textarea"
                                                            :rows="6" />
                                                        </div>
                                                            <div class="form-group text-center mt-5">
                                                                <!-- <Space wrap> -->
                                                                    <Col span="8"  offset="10">
                                                                        <Button type="info" size="mediu" @click="aprrove()" :disabled="isLoading" 
                                                                        :loading="isLoading">{{isLoading ?'Please wait...' :'Submit'}}</Button>
                                                                    </Col>
                                                                    
                                                                <!-- </Space> -->
                                                            
                                                            </div>
                                                        
                                                    </div>
                                        
    
                                                </Col>
                                 
                                        </div>
                                   
                                        <div class="col-md-12 " v-if="stage =='sent-back'">
                                            <div class="card-body table-responsive">
                                                <!-- <form> -->
                                                <table style="width:100%;" class="table table-bordered table-striped mb-3 table-hover text-center" >
                                                    <thead class="table-header">
                                                        <tr style="font-size:13px !important;">
                                                            <th>Tax Head</th>
                                                            <th>Month</th>
                                                            <th>Year</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                        <tbody class="table-body" >
                                                            <tr>
                                                                <td>
                                                                    <Select placeholder="Obligation">
                                                                        <Option value="VAT TAX">VAT TAX</Option>
                                                                        <Option value="Income TAX">Income TAX</Option>
                                                                    </Select>
                                                                </td>
                                                                <td>
                                                                    <Datepicker type="month"  />
                                                                    <!-- <Select placeholder="period from">
                                                                        <Option value="January">January</Option>
                                                                        <Option value="February">February</Option>
                                                                    </Select> -->
                                                                </td>
                                                                <td>
                                                                    <Datepicker type="year"  placeholder="select year.." />
                                                                    <!-- <Select placeholder="period to">
                                                                        <Option value="2022">2022</Option>
                                                                    </Select> -->
                                                                </td>
                                                                <td>
                                                                    <Input placeholder="amount" type="number"/>
                                                                </td>
                                                               
                                                               
                                                              
                                                            </tr>
                                                        </tbody>
                                                </table>
                                              
                                            <!-- </form> -->
                                            </div>
                                            <div class="form-group text-center mt-5">
                                                <Space wrap>
                                                    <Col span="8"  offset="7">
                                                        <Button type="primary" size="medium"
                                                        @click="Confirm()">Submit</Button>
                                                    </Col>
                                                    
                                                </Space>
                                                
                                            </div>
                                        </div>
                        
                                    </div>
                                </div>
                       
    
    
                            </div>
                        </div>
          
                        
                        </div>
    
                </div>
                <!-- comments & attachment section ends -->
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
        props: ['Rstatus', 'Fstatus', 'Astatus', 'username','role','Path', 'action', 'stage', 'actionTitle'],
        data(){
            return {
                prelimData: {
                    total:'0'
                },
                bigCol: '',
                smallCol:'',
                preventedDate:{
                    disabledDate(month) {
                        const today = new Date()
                        today.setHours(0, 0, 0, 0);
                        return month > today
                    }
                },
                data:{
                    caseId: this.$route.params.caseId,
                    approvalType: 'approval',
                    Approval: "",
                    Recommendation:"",
                    filename:[],
                    username: this.username,
                    role:this.role,
                    remarks: '',
                    assessment_no: '',
                    rejection_id: '',
                    ReasonName:'',
                    tpin:'',
                    total:'0',
                },
                ApproveData: {
                    option:''
                },
                RejectData: {
                    Remarks:'',
                    Reason:''
                    
                },
                index: 1,
                
                formDynamic1: {
                    items: [
                        {
                            Amount: '',
                            Year_from: '',
                            Year_to: '',
                            TaxHead: '',
                            index: 1,
                            status: 1
                        }
                    ]
                },
                Choice:'',
                Mdata:{
                    type:''
                },
                isValid:true,
                MApprovals:[],
                FApprovals:[],
                options:'',
                isUpload:false,
                casetype: '',
                typeId:'',
                Approvals:[],
                RApprovals:[],
                caseDetails:[],
                reasons: [],
                isLoading:false,
                Rdata:{
                    ReasonType:'reason',
                    reason: '',
                    caseId: this.$route.params.caseId,
                    username:this.username,
                 
                    Rstatus:this.Rstatus,
                    assessment_no:'',
                },
                isLoading:false,
                Fdata: {
                    Approval:'',
                    remarks: '',
                    caseId: this.$route.params.caseId,
                    username:this.username,
                    Fstatus: this.Fstatus,
                    assessment_no:''
                },
                isFormvalide:false,
                token: '',
                // validate form
               
    
            }
        },
    
        components : {
            casedefault,
            TaxpayerResponse,
            comments,
            attachments
        },
        computed: {
           
                totalAmount: {
                  
                    get() {
                        return this.prelimData.total;
                        
                    },
                    set(value) {
                        this.prelimData.total = value;
                    }
                }
            },
        methods: {

            handleSuccess(res) {
                // Add the uploaded file path to the existing state array
                this.data.filename.push(res.filenames[0]);
                // Set the isUploaded flag to true if there is at least one file path in the state array
                this.isUploaded = this.filename.length > 0;
            },
                  
            handleError(file){
            this.$Notice.warning({
                title:'The file format is incorrect!',
                desc:`${file.errors.file.length ? file.errors.file[0] :'Something went wrong!'}`
            })
            },
            handleFormatError(file){
                this.$Notice.warning({
                    title:'The file format is incorrect!',
                    desc:'File format of ' + file.name + ' is incorrect, please select pdf, docx ,rar, xls or zip.'
                });
            },
            handleMaxSize(file){
                this.$Notice.warning({
                    title:'Exceeding file size limit!',
                    desc:'File '+file.name + 'is too large, no more than 21MB'
                });
            },
            async aprrove() {
        
          if (this.role == 'TeamLead'){    
                const uploadfile = document.querySelector('#fileUpload')
                     
          
                if(this.ApproveData.option=="Approve"){
                    const postData = {...this.data, 
                                Recommendation:this.ApproveData.option,
                                caseId:this.data.caseId,
                                reason: this.data.Reason,
                                remarks: this.data.Remarks,
                                Astatus:this.Astatus
                            } 
                       console.log(postData);
                    const res = await this.callApi('post', "/api/ApproveCase",postData)
                    console.log(res.data);
                            if (res.status === 200) {
                                this.success('Oyan rada ....Case has been forwared')
                                window.location =`${this.Path}`
                            }
                            else {
                                this.swr('Something Went Wrong!')
                            }
                    }
                    else if(this.ApproveData.option=="Revert"){
                        
                        const postData = {...this.data, 
                                Recommendation:this.ApproveData.option,
                                caseId:this.data.caseId,
                                reason:this.RejectData.Reason,
                                remarks: this.RejectData.Remarks,
                                approvalType:"rejection",
                                Rstatus:this.Rstatus 
                            } 
                       
                            console.log(postData)
                            const res = await this.callApi('post', "/api/RejectCase",postData)
                            if (res.status === 200) {
                                this.success('Oyan rada ....case has be rejected')
                                window.location =`${this.Path}`
                            }
                            else {
                                this.swr('Something Went Wrong!')
                            }
                    }
                
                }
        else if (this.role == 'SectorManager'){    
                const uploadfile = document.querySelector('#fileUpload')
                  
                     
                if(this.ApproveData.option=="Approve"){
                    const postData = {...this.data, 
                                Recommendation:this.ApproveData.option,
                                caseId:this.data.caseId,
                                reason: this.data.Reason,
                                remarks: this.data.Remarks,
                                Astatus:this.Astatus
                            } 
                          
                    const res = await this.callApi('post', "/api/ApproveCase",postData)
                    
                            if (res.status === 200) {
                               
                                this.success('Oyan rada ....Case has been forwared')
                                window.location =`${this.Path}`
                            }
                            else {
                                this.swr('Something Went Wrong!')
                            }
                    }
                    else if(this.ApproveData.option=="Revert"){
                        
                        const postData = {...this.data, 
                                Recommendation:this.ApproveData.option,
                                caseId:this.data.caseId,
                                reason:this.RejectData.Reason,
                                remarks: this.RejectData.Remarks,
                                approvalType:"rejection",
                                Rstatus:this.Rstatus 
                            } 
                       
                            console.log(postData)
                            const res = await this.callApi('post', "/api/RejectCase",postData)
                            if (res.status === 200) {
                                this.success('Oyan rada ....case has be rejected')
                                window.location =`${this.Path}`
                            }
                            else {
                                this.swr('Something Went Wrong!')
                            }
                
                }
            }
            else if (this.role == 'TSOhead'){    
            
            const uploadfile = document.querySelector('#fileUpload')   
            if(this.ApproveData.option=="Approve"){
                
                const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.data.Reason,
                            remarks: this.data.Remarks,
                            Astatus:this.Astatus
                        } 
                       
                const res = await this.callApi('post', "/api/ApproveCase",postData)
                
                        if (res.status === 200) {
                            this.success('Oyan rada ....Case has been forwared')
                            window.location =`${this.Path}`
                        }
                        else {
                            this.swr('Something Went Wrong!')
                        }
                }
                else if(this.ApproveData.option=="Revert"){
                    
                    const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason:this.RejectData.Reason,
                            remarks: this.RejectData.Remarks,
                            approvalType:"rejection",
                            Rstatus:this.Rstatus 
                        } 
                   
                        console.log(postData)
                        const res = await this.callApi('post', "/api/RejectCase",postData)
                        if (res.status === 200) {
                            this.success('Oyan rada ....case has be rejected')
                            window.location =`${this.Path}`
                        }
                        else {
                            this.swr('Something Went Wrong!')
                        }
                }else if(this.ApproveData.option=="Level Two") {
                    console.log("two")
                }
            
            }
            },
    
    
    
        },
 
      created() {
        console.log(this.data.caseId,"fsdfsfjdsksfnjdfnsdjkfnsdjkfnsdjkfnsdjk");
            if ((this.stage) === 'AssessmentReturns') {
                this.bigCol = 'col-md-9',
                this.smallCol ='col-md-3'
            }      
            else {
                this.bigCol = 'col-md-9'
                this.smallCol ='col-md-3'
            }
        
        }
    
    
    
    
    
    
    
    }
    </script>
    