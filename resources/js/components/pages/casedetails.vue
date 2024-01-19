<template>
<!-- case details component -->
    <div class="card shadow mb-4">

            <div class="card-body">

            <casedefault :stage="stage" />
             <div class="comments-details" v-if="stage != 'prelim'">
                <div class="row">
                <!-- comments -->
                <div class="col-lg-12 col-md-12">
                    <comments :case_id="data.caseId" />
                </div>
                <!-- comments ends -->
                <!--taxpayer response -->
                    <!-- <div class="col-lg-6 col-md-12">
                        <TaxpayerResponse :case_id="data.caseId"/>
                    </div> -->
                <!--taxpayer response ends -->
                </div>
            </div>
            <!-- comments & attachment section -->
            <div class="comments-details">
                <!-- <form> -->
                    <div class="row d-flex flex-row">
                       
                    <!-- comment section -->
                    <!--attachment section -->
                        <div :class="`${smallCol}`" class="col-3">
                            <div class="card detail-card">
                            <div class="card-header pb-2 bg-dark text-muted text-center">
                                    <h5 class="text-muted">ATTACHMENTS</h5>
                            </div>
                                <div class="card-body">
                                    <!-- attachment lists -->
                                    <attachments :case_id="this.data.caseId" :tPin="data.tpin" :stage="stage"/>
                                    <!-- attachment list ends -->
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
                        <div :class="`${bigCol}`" v-if="stage !='Details'" class="col-9">
                            <div class="card">
                            <div class="card-header bg-dark  text-muted"
                                    style="text-transform:uppercase; text-align:center; ">
                                {{ actionTitle }}
                                </div>
                                <!-- actions -->
                                <div class="card-body detail-card" >
                                <br/>
                                    <div class="row">
                                        <!-- prelim -->
                                        <div class="col-md-12" v-if="stage =='prelim'">
                                            <!-- cm actions -->
                                            <div>
                                            <!-- seletions -->
                                            
                                                <Col span="13"  offset="0">
                                                    <div class="form-group ml-2 f-s-13">
                                                        <label class="mb-3 label">Recommendation <span class="text-danger">*</span></label>
                                                        <Select v-model="data.Recommendation" style="width:100%"
                                                        placeholder="Select Recommendation...">
                                                        <Option value="Proceed with Case">Proceed with Case</Option>
                                                        <Option value="Recomnend As Level-Two">Recomnend As Level-Two</Option>
                                                        <Option value="Recomnend for Closure">Recomnend for Closure</Option>
                                                    </Select>
                                                    </div>
                                                </Col>
                                        <!-- seletions ends-->
                                                <!-- proceed with case / close case -->
                                                <div class="card"
                                                    v-if="data.Recommendation == 'Proceed with Case' || data.Recommendation == 'Recomnend for Closure' || data.Recommendation == 'Recomnend As Level-Two'">
                                                    <div class="card-body">
                                                        
                                                        <!-- <Space direction="vertical" size="large" > -->
                                                            <RadioGroup v-model="Choice">
                                                                <Radio label="periods">
                                                                    <span class="label">Select Periods</span>
                                                                </Radio>
                                                                <Radio label="others">
                                                                    <span class="label">Others</span>
                                                                </Radio>
                                                            </RadioGroup>
                                                        <!-- </Space> -->
                                                    <!-- select periods -->
                                                        <div v-if="Choice == 'periods'">
                                                        <!-- select test -->
                                                        <Form ref="formDynamic1" :model="formDynamic1" style="width: 100%"
                                                                label-position="center" :label-width="100">
                                                                <!-- <div class="card shadow"> -->


                                                                <template v-for="(item, index) in formDynamic1.items">
                                                                    <!-- :label="'Period ' + item.index" -->
                                                                    <!-- :rules="{required: true, message: 'Amount ' + item.index +' can not be empty', trigger: 'blur'}" -->
                                                                    <div class="card">
                                                                        <!-- :prop="'items.' + index + '.Amount'"
                                                                    :rules="{required: true, message: 'Amount ' + item.index +' can not be empty', trigger: 'blur'}" -->
                                                                        <Item v-if="item.status" :key="index"
                                                                            :label="'Period ' + item.index">
                                                                            <!-- <Row> -->
                                                                            <!-- <Col flex="4"> -->
                                                                            <!-- row -->
                                                                            <div class="m-5">
                                                                                <div class="row mb-4">

                                                                                    <div class="form-group col-7">
                                                                                        <label class="label">Tax Head <span
                                                                                                class="text-danger ml-2">*</span></label>
                                                                                        <Select v-model="item.TaxHead"
                                                                                            style="width:100%"
                                                                                            placeholder="Select Tax head...">
                                                                                            <Option
                                                                                                v-for="(obData, i) in obligation"
                                                                                                :value='obData.OBLIGATION_NAME'
                                                                                                :key="i">
                                                                                                {{ obData.OBLIGATION_NAME }}
                                                                                            </Option>

                                                                                        </Select>
                                                                                    </div>
                                                                                    <div class="form-group col-5">
                                                                                        <label class="label">Amount <span
                                                                                                class="text-danger ml-2">*</span></label>
                                                                                        <Input v-model="item.Amount" type="text"
                                                                                            placeholder="Enter Amount..." />
                                                                                        <!-- <InputNumber :max="100999900" type="number" :min="1" style="width:100%"
                                                                                placeholder="Enter Amount..." v-model="item.Amount" /> -->

                                                                                    </div>
                                                                                    <div class="form-group col-4">
                                                                                        <label class="label">Period From <span
                                                                                                class="text-danger ml-2">*</span></label>
                                                                                        <DatePicker :hasInputElement="false"
                                                                                            :max="maxDate"
                                                                                            v-model="item.Year_from"
                                                                                            placeholder="Select Year..." />
                                                                                    </div>
                                                                                    <div class="form-group col-4">
                                                                                        <label class="label">Period To <span
                                                                                                class="text-danger ml-2">*</span></label>
                                                                                        <DatePicker :hasInputElement="false"
                                                                                            :max="maxDate"
                                                                                            v-model="item.Year_to"
                                                                                            placeholder="Select Year..." />
                                                                                    </div>


                                                                                    <div class="form-group col-3 ">
                                                                                        <div class="">
                                                                                            <label class="label">Add
                                                                                            Period</label>
                                                                                        </div>
                                                                                        <Button type="info" ghost
                                                                                            @click="handleAdd1('formDynamic1')"
                                                                                            icon="md-add">Add</Button>
                                                                                    </div>

                                                                                    <div class="form-group col-3 "
                                                                                        v-if="item.index != '1'">
                                                                                        <div class="">
                                                                                            <label class="label">Delete
                                                                                            Option</label>
                                                                                        </div>
                                                                                        <Button @click="handleRemove1(index)"
                                                                                            ghost type="error">Delete</Button>
                                                                                    </div>

                                                                                    
                                                                                </div>
                                                                                <!-- </Col> -->
                                                                                <!-- <Col flex="1"> -->
                                                                                <!-- <Row>
                                                                        
                                                                            
                                                                            <Col span="8" offset="3">
                                                                                <Button @click="handleRemove1(index)">Delete</Button>
                                                                            </Col>
                                                                            </Row> -->
                                                                            </div>
                                                                            <!-- row ends -->
                                                                            <!-- </Col> -->
                                                                            <!-- </Row> -->

                                                                        </Item>
                                                                    </div>
                                                                </template>
                                                                <!-- </div> -->
                                                                <!-- <FormItem> -->
                                                                <!-- <Row> -->
                                                                    <!-- <Col span="12">
                                                                        <Button type="dashed" long @click="handleAdd" icon="md-add">Add item</Button>
                                                                    </Col> -->
                                                                    <!-- <Col span="8" offset="3">
                                                                    <Button type="info" ghost
                                                                        @click="handleAdd1('formDynamic1')"
                                                                        icon="md-add">Add</Button>
                                                                    </Col> -->
                                                                    <!-- <Col span="8"  offset="3">
                                                                        <Button @click="handleReset1('formDynamic1')" style="margin-left: 8px">Reset</Button>
                                                                    </Col> -->

                                                                <!-- </Row> -->
                                                                <!-- </FormItem> -->
                                                                <!-- <FormItem>
                                                                <Button type="primary" @click="handleSubmit1('formDynamic1')">Submit</Button>
                                                            </FormItem> -->
                                                        </Form>
                                                        <form action="" ref="formDynamic1" :model="formDynamic1">
                                                        <!-- more entry -->
                                                        <div class="card">
                                                            <div class="card-body ">
                                                                <!-- <div class="form-group ml-2 d-flex align-items-center mt-2 justify-content-between">
                                                                    <label class="label mr-5">Total </label>
                                                                    <Input disabled type="text" v-model="prelimData.total" />
                                                                </div> -->
                                                                
                                                                <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                                    <label class="label">Reason <span class="text-danger ml-2">*</span></label>
                                                                    <Select v-model="data.Reason" style="width:100%" placeholder="Select Recommendation...">
                                                                        <Option :value="p.option" v-for="(p, i) in ProceedReasons"
                                                                :key="i">{{p.option}}</Option>
                                                                    </Select>

                                                                </div>
                                                                <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                                    <label class="mb-3 label mb-2">Remarks <span class="text-danger ml-2">*</span></label>
                                                                    <Input v-model="data.Remarks" placeholder="Enter Remarks" type="textarea"
                                                                    :Row="10" :col="6" />
                                                                </div>
                                                                
                                                                <!-- attachment addition -->
                                                            
                                                                <!-- attachment addition ends -->

                                                            </div>

                                                        </div>
                                                        <div class="form-group text-center mt-5">
                                                            <!-- <Space wrap> -->
                                                                <Col span="8"  offset="7" v-if="isValid">
                                                                    <Button type="primary" size="medium"
                                                                    @click="handleSubmit1('formDynamic1')">Submit</Button>
                                                                </Col>
                                                                
                                                            <!-- </Space> -->
                                                            
                                                        </div>
                                                        <!-- more entry ends -->
                                                        <!-- select test ends -->
                                                        </form>                                                
                                                        </div>
                                                    <!--select periods ends -->
                                                    <!-- others -->
                                                    <div v-if="Choice == 'others'">
                                                        <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                            <label class="mb-3 label mb-2">Reason <span class="text-danger ml-2">*</span></label>
                                                            <Select v-model="data.Reason" style="width:100%" placeholder="Select Recommendation...">
                                                                <Option :value="r.option" v-for="(r, i) in ClosureReasons"
                                                                :key="i">{{r.option}}</Option>
                                                            </Select>

                                                        </div>
                                                        <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                            <label class="mb-3 label mb-2">Remarks <span class="text-danger ml-2">*</span></label>
                                                            <Input v-model="data.Remarks" placeholder="Enter Remarks" type="textarea"
                                                            :Row="10" :col="6" />
                                                        
                                                        </div>
                                                        <div class="form-group ml-2 mt-2 text-center">
                                                            <Button type="success" size="small"
                                                            :disabled="isLoading" :loading="isLoading"
                                                            @click="handleSubmit1('formDynamic1')">{{isLoading ?'Please wait...' :'Submit'}}
                                                            </Button>
                                                        </div>

                                                        <!-- test -->
                                                    
                                                        <!-- test ends -->
                                                    </div>
                                                <!--others ends -->
                                                    </div>
                                                    <!-- </div> -->
                                                </div>
                                                <!-- proceed with case / close case ends -->

                                            </div>


                                        </div>
                                        <!-- prelim ends -->
                                
                                        <!-- Decision (approve/reject) stages -->
                                        <div class="col-md-12" v-if="stage =='Decision'">
                                            <!-- <Space wrap> -->
                                                <!-- <div></div> -->
                                        
                                                <Col span="13"  offset="5" >
                                                    <div class="mt-2" >
                                                        <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                            <label class="mb-3 label mb-2">Action <span class="text-danger ml-2">*</span></label>
                                                            <Select v-model="ApproveData.option" style="width:100%" placeholder="Select action...">
                                                                <Option value="Approve">Approve</Option>
                                                                <Option value="Revert">Revert</Option>
                                                                <!-- <Option value="Level Two">Level Two</Option> -->
                                                            </Select>
                                                        </div>
                                                    </div>
                                                </Col>
                                            <!-- </Space> -->
                                            <!-- <Space wrap> -->
                                                <Col span="13"  offset="5">
                                                    <!-- <div class="form-group  mt-4">
                                                        <Col span="13"  offset="5">
                                                            <label class="label">Approve Option</label>
                                                        </Col>

                                                    </div> -->
                                                        <!-- approve option -->
                                                        <div class="mt-4" v-if="ApproveData.option == 'Close'">
                                                            <!-- <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                                <label class="mb-3 label mb-2">Reason <span class="text-danger ml-2">*</span></label>
                                                                <Select v-model="data.Reason" style="width:100%" placeholder="Select Reason...">
                                                                    <Option value="Sufficient data for proof provided">Sufficient data for proof provided</Option>
                                                                    <Option value="Others">Others</Option>
                                                                </Select>
                                                            </div> -->
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
                                                            <!-- <div class="form-group ml-2 mt-2 text-center">
                                                                <Button type="success" size="small"
                                                                :disabled="isLoading" :loading="isLoading"
                                                                @click="aprrove()">{{isLoading ?'Please wait...' :'Submit'}}
                                                                </Button>
                                                            </div> -->
                                                        </div>

                                                    <div class="mt-4" v-if="ApproveData.option == 'Approve'">
                                                        <!-- <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                            <label class="mb-3 label mb-2">Reason <span class="text-danger ml-2">*</span></label>
                                                            <Select v-model="data.Reason" style="width:100%" placeholder="Select Reason...">
                                                                <Option value="Sufficient data for proof provided">Sufficient data for proof provided</Option>
                                                                <Option value="Others">Others</Option>
                                                            </Select>
                                                        </div> -->
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
                                                        <!-- <div class="form-group ml-2 mt-2 text-center">
                                                            <Button type="success" size="small"
                                                            :disabled="isLoading" :loading="isLoading"
                                                            @click="aprrove()">{{isLoading ?'Please wait...' :'Submit'}}
                                                            </Button>
                                                        </div> -->
                                                    </div>
                                                        <!-- approve option ends -->
                                                        <!-- reject option -->
                                                    <div class="mt-4" v-if="ApproveData.option == 'Revert'">
                                                        <!-- <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                            <label class="mb-3 label mb-2">Reason <span class="text-danger ml-2">*</span></label>
                                                            <Select v-model="RejectData.Reason" style="width:100%" placeholder="Select Reason...">
                                                                <Option value="Report is very shallow">Report is very shallow</Option>
                                                                <Option value="Insufficient amount to be received">Insufficient amount to be received</Option>
                                                                <Option value="Errors in report computation">Report is very shallow</Option>
                                                                <Option value="Insufficient data prove in report">Report is very shallow</Option>
                                                                <Option value="High profile case">Report is very shallow</Option>
                                                                <Option value="Others">Others</Option>
                                                            </Select>
                                                        </div> -->
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
                                                        <!-- reject option ends -->
                                                        <!-- Level Two option -->
                                                    <div class="mt-4 fadeIn" v-if="ApproveData.option == 'Level Two'">
                                                        <div class="form-group ml-2 d-flex align-items-center mt-3">
                                                            <label class="mb-3 label mb-2">Remarks <span class="text-danger ml-2">*</span></label>
                                                            <Input v-model="LevelTwoData.Remarks" placeholder="Enter Remarks...." type="textarea"
                                                            :rows="6" />
                                                        </div>
                                                            <div class="form-group text-center mt-5">
                                                                <!-- <Space wrap> -->
                                                                    <Col span="8"  offset="10">
                                                                        <Button type="info" size="medium"
                                                                        @click="aprrove()" :disabled="isLoading" 
                                                                        :loading="isLoading">{{isLoading ?'Please wait...' :'Submit'}}</Button>
                                                                    </Col>
                                                                    
                                                                <!-- </Space> -->
                                                            
                                                            </div>
                                                        
                                                    </div>
                                                        <!-- Level Two option ends -->

                                                </Col>
                                                
                                            <!-- </Space> -->
                                        </div>
                                        <!-- Decision (approve/reject) stages ends -->
                                        <!-- Taxpayer contact stage-->
                                        <div class="col-md-12" v-if="stage == 'TaxpayerContact'">
                                            <div class="card-body shadow justify-content-center my-2 mx-2">
                                                <form>
                                                    <div class="form-content">
                                                        <div class="form-group d-flex  mt-3">
                                                            
                                                            <div class="col-1"></div>
                                                            <div class="col-10 text-muted">
                                                                <label class="label">Taxpayer Email <span
                                                                        class="ml-2 text-danger">*</span></label>
                                                                <Input placeholder="Taxpayer email...." class="input"
                                                                    type="email" style="color:#ccc !important; font-weight:bold !important;
                                                                    text-transform: lowercase !important;" v-model="data.taxpayerEmail" id="temail" disabled />
                                                                <small id="error"></small>
                                                            </div>
                                                            <div class="col-1"></div>
                                                        </div>

                                                        <div class="form-group d-flex ">
                                                            <div class="col-1"></div>
                                                            <div class="col-10">
                                                                <label class="label">CC </label>
                                                                <Select v-model="data.cc" class="mt-1" style="width:100%"
                                                                    placeholder="Select email..." multiple clearable sortable
                                                                    filterable>
                                                                    <Option :value="c.cc" v-for="(c, i) in ccEmails" :key="i">
                                                                        {{ c.label }} <small class="text-muted">({{ c.cc
                                                                        }})</small>
                                                                    </Option>
                                                                    <Input placeholder="Type a new email then press enter"
                                                                        v-model="newCC" @on-enter="addNewCC" />
                                                                </Select>
                                                            </div>
                                                            <div class="col-1"></div>
                                                        </div>

                                                        <div class="form-group d-flex mt-3">
                                                            <div class="col-1"></div>
                                                            <div class="col-10 text-muted">
                                                                <label class="label">BCC<span class="ml-2 text-danger">*</span></label>
                                                                <Input placeholder="CM emails..." class="input" type="email" style="color:#ccc !important; font-weight:bold !important; text-transform: lowercase !important;" v-model="data.bcc" id="bccMails" disabled />
                                                                <small id="error"></small>
                                                            </div>
                                                            <div class="col-1"></div>
                                                        </div>

                                                        <div class="form-group d-flex">
                                                            <div class="col-1"></div>
                                                            <div class="col-10">
                                                                <label class="label">Subject <span
                                                                        class="ml-2 text-danger">*</span></label>
                                                                <Input placeholder="Subject.." type="textarea" :rows="2"
                                                                    v-model="data.subject" class="input" id="subject" />
                                                                <small id="error"></small>
                                                            </div>
                                                            <div class="col-1"></div>
                                                        </div>

                                                        <div class="mt-4 form-group d-flex">
                                                            <div class="col-1"></div>
                                                            <div class="col-10">
                                                                <label class="label">Attachment<span
                                                                        class="ml-2 text-danger">*</span></label><br>
                                                                <Button type="info" class="shadow"
                                                                    @click="attachmentPreview = true">
                                                                    Show attachment preview
                                                                </Button>
                                                            </div>
                                                            <div class="col-1"></div>
                                                        </div>

                                                        <div class="form-group d-flex mt-2">
                                                            <div class="col-1"></div>
                                                            <div class="col-10">
                                                                <label class="label">Message Body <span
                                                                        class="ml-2 text-danger">*</span></label><br>
                                                                <Input type="textarea" placeholder="Message body...." :rows="15"
                                                                    id="body" class="input text-monospace" v-model="data.body"
                                                                    style="text-align:justify;" />
                                                                <small id="error"></small>
                                                            </div>
                                                            <div class="col-1"></div>
                                                        </div>
                                                        <!-- submit button -->
                                                        <div class="form-group d-flex mt-4">
                                                            <div class="col-1"></div>
                                                            <div class="col-10 d-flex justify-content-left">
                                                                <Button type="info" size="medium" @click="contactTaxpayer()"
                                                                    :loading="isLoading">
                                                                    {{ isLoading ? 'Please wait...' : 'Send' }}
                                                                </Button>
                                                            </div>
                                                            <div class="col-1"></div>
                                                        </div>
                                                    </div>
                                                    <!-- content form ends-->
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Taxpayer stage ends -->

                                        <!-- assessment and returns stage -->
                                        <div class="col-md-12 " v-if="stage =='AssessmentReturns'">
                                            <div class="card-body table-responsive">
                                                <table style="width:100%;" class="table table-bordered table-striped mb-3 table-hover text-left" >
                                                    <thead class="table-headerr">
                                                        <tr style="font-size:12px !important;">
                                                            <th>Assessment No.</th>
                                                            <th>Period From</th>
                                                            <th>Period To</th>
                                                            <th>Assessment Amount</th>
                                                            <th>New Amount</th>
                                                            <th>Description</th>
                                                            <th>Select</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-body">
                                                        <tr v-for="per in res_case1" :key="per.id">
                                                            <td>{{ per.ASSESSMENT_NUMBER }}</td>
                                                            <td>{{ per.YEAR_FROM ? formatDate(per.YEAR_FROM) : null }}</td>
                                                            <td style="width: fit-content !important;">
                                                                {{ per.YEAR_TO ? formatDate(per.YEAR_TO) : null }}</td>
                                                            <td>{{ per.ASSESSMENT_AMOUNT }}</td>
                                                            <td value="per.newAmount">{{ per.REPORT_FIN_AMOUNT }}</td>
                                                            <td>
                                                                <Input placeholder="Description.." disabled type="text"
                                                                    :value="getDescription(per)" />
                                                            </td>
                                                            <td>
                                                                <Checkbox v-model="per.isChecked" :disabled="!per.isChecked"></Checkbox>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="form-group text-center mt-5">
                                                <!-- <Space wrap> -->
                                                    <Col span="8"  offset="7">
                                                        <Button type="primary" size="medium"
                                                        @click="Confirm()" :disabled="!checkValid" >Submit</Button>
                                                    </Col>
                                                    
                                                <!-- </Space> -->
                                                
                                            </div>
                                        </div>

                                        <!-- assessment and returns stage ends -->
                                        <!-- reverted cases stage -->
                                    <div  v-if="stage =='sent-back'">
                                        <table style="width:100%;" class="table table-bordered table-striped mb-3 table-hover text-center">
                                            <thead class="table-header">
                                                <tr style="font-size:13px !important; text-align: left !important;">
                                                    <th>Tax Head</th>
                                                    <th>Year From</th>
                                                    <th>Year To</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body" :key="i">
                                                <tr class="table-body" v-for="(item, idx) in formDynamic2.items" :key="idx">
                                                    <td>
                                                        <Select v-model="formDynamic2.items[idx]['OBLIGATION_NAME']" :value="formDynamic2.items[idx].OBLIGATION_NAME" placeholder="Obligation">
                                                            <Option v-for="(obData, i) in obligation" :value='obData.OBLIGATION_NAME' :key="i">
                                                                {{ obData.OBLIGATION_NAME }}
                                                            </Option>
                                                        </Select>
                                                    </td>
                                                    <td>
                                                        <DatePicker v-model="formDynamic2.items[idx].YEAR_FROM" :options="preventedDate" type="month" 
                                                        placeholder="Select Year..." />
                                                    </td>
                                                    <td>
                                                        <DatePicker v-model="formDynamic2.items[idx].YEAR_TO" :options="preventedDate" type="month" 
                                                        placeholder="Select Year..." />
                                                    </td>
                                                    <td>
                                                        <Input v-model="formDynamic2.items[idx]['PRELIM_REPORT_AMOUNT']" type="text" placeholder="Enter Amount..."/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="form-group text-center mt-5">
                                            <Space wrap>
                                                <Col span="8"  offset="7">
                                                    <Button type="primary" size="medium"
                                                    @click="Confirm1()">Submit</Button>
                                                </Col>
                                            </Space>
                                        </div>
                                    </div>
                                                
                                    <!-- reverted cases stage ends -->

                                    <!-- taxpayer response -->
                                    <div class="col-md-12 " v-if="stage =='TaxpayerResponse'">
                                        <div class="card-body table-responsive">
                                            <!-- <form> -->
                                            <table style="width:100%;" class="table table-bordered table-striped mb-3 table-hover text-center" >
                                                <thead class="table-headerh">
                                                    <tr style="font-size:12px !important;">
                                                        <th>Tax Head</th>
                                                        <th>Year From</th>
                                                        <th>Year To</th>
                                                        <th>Preliminary Amount</th>
                                                        <th>Final Report Amount</th>
                                                        <th>Select Action</th>
                                                    </tr>
                                                </thead>
                                                    <tbody class="table-body" >
                                                        <tr v-for="row in res_case" :key="row.id">
                                                            <td>{{ row.OBLIGATION_NAME }}</td>
                                                            <td>{{ row.YEAR_FROM ? formatDate(row.YEAR_FROM) : "N/A" }}</td>
                                                            <td>{{ row.YEAR_TO ? formatDate(row.YEAR_TO) : "N/A" }}</td>
                                                            <td>{{ row.PRELIM_REPORT_AMOUNT }}</td>
                                                            <td>
                                                                <Input v-model="data.finr_amount[row.id]" placeholder="Original amount" type="text" style="width:100%;"/>
                                                            </td>
                                                            <td>
                                                                <Select v-model="data.options_r[row.id]" placeholder="select action..." style="width:100%;">

                                                                    <Option value="Additional Assessment">Additional Assessment</Option>
                                                                    <Option value="No Payments">No Payments</Option>
                                                                </Select>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                            </table>
                                        
                                        <!-- </form> -->
                                        </div>
                                        <!-- <Space wrap> -->
                                        <Col span="13"  offset="5">
                                            <div class=" mt-5">

                                                <div class="form-group ml-2 d-flex align-items-center mt-2">
                                                    <label class="mb-3 label mb-2">Date <span class="text-danger">*</span></label>
                                                    <Input v-model="data.now" placeholder="Date" type="text" style="margin-left:18px;" disabled/>
                                                </div>

                                                <div class="form-group ml-2 d-flex align-items-center mt-3">
                                                    <label class="mb-3 label mb-2">Remarks <span class="text-danger ml-2">*</span></label>
                                                    <Input v-model="data.Remarks" placeholder="Enter Remarks" type="textarea"
                                                    :rows="6" />
                                                </div>
                                                
                                                <div class="form-group text-center mt-5">
                                                    <!-- <Space wrap> -->
                                                        <Col span="8"  offset="10">
                                                            <Button type="info" size="medium"
                                                            @click="ResponseSubmit()" :disabled="isLoading" 
                                                            :loading="isLoading">{{isLoading ?'Please wait...' :'Submit'}}</Button>
                                                        </Col>
                                                    <!-- </Space> -->
                                                </div>
                                            
                                            </div>
                                        </Col>
                                        <!-- </Space> -->
                                    </div>
                                    <!-- taxpayer response ends -->
                                </div>
                            </div>
                            <!-- actions ends-->
                        </div>
                    </div>
                    <!-- action section  ends -->
                <!-- </div> -->
                <!-- ocomment section ends-->
                    
                    </div>
            <!-- </form> -->
            </div>
            <!-- comments & attachment section ends -->
            <PdfPreview :isOpen="attachmentPreview" :path="`/api/pdf/contact-tp/${data.caseId}`"
                :onClose="onPreviewChange" />
        </div>
    </div>
    <!--</div>-->
<!-- case details component ends-->

</template>
<script>
import casedefault from './cases.vue'
import TaxpayerResponse from './taxpayer-response.vue'
import comments from './comments.vue'
import attachments from './attachments.vue'
import moment from 'moment';

import PdfPreview from './PdfPreview.vue'// import sentBackDetails from '../../roles/account-manager/pages/sent-back-details.vue'

export default {
    props: ['Rstatus', 'RFstatus','Fstatus', 'Astatus','ACstatus','FAstatus','username','role','Path', 'action', 'stage', 'actionTitle','Mstatus','RMstatus','stage1', 'reverted'],
    data(){
        return {
            attachmentPreview: false,
            res_case: [],
            res_case1: [],
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
            days:15,
            newCC: '',
            ccEmails: this.initialCCEmails || [],
            caseId: this.$route.params.caseId,
            data:{
                caseId: this.$route.params.caseId,
                mailx:'',
                approvalType: 'approval',
                Approval: "",
                periodss:'',
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
                taxpayerEmail: "",
                smMail: "",
                dcMail: "",
                subject: "",
          
                cc: [],
                bcc: "",
                to: "",
                body: "",
                Reason: "",
                Remarks: "",
                now: null,
                finr_amount: {},
                options_r: {},
                newAmount: {},
                Description: '',
               
            },
            isChecked: false,
            ApproveData: {
                option:''
            },
            RejectData: {
                Remarks:'',
                Reason:''
            },
            LevelTwoData: {
                Remarks:'' 
            },
            MonthRegister:[
                {
                id:'1',
                Month:'January'
                },
                {
                id:'2',
                Month:'February'
                },
                {
                id:'3',
                Month:'March'
                },
                {
                id:'4',
                Month:'April'
                },
                {
                id:'5',
                Month:'May'
                },
                {
                id:'6',
                Month:'June'
                },
                {
                id:'7',
                Month:'July'
                },
                {
                id:'8',
                Month:'August'
                },
                {
                id:'9',
                Month:'September'
                },
                {
                id:'10',
                Month:'October'
                },
                {
                id:'11',
                Month:'November'
                },
                {
                id:'12',
                Month:'December'
                }
            ],
            TaxHeadInfo2: [
                // {
                //     option:'VAT TAX'
                // },
                // {
                //     option:'Income Tax'
                // },
                {
                    option:'Presumptive Tax'
                },
                {
                    option:'National Hospital Insurance Fund (NHIF)'
                },
                {
                    option:'Advamce Tax'
                },
                {
                    option:'Income Tax-Rent Income Withholding'
                },
            ],
            // from db
            TaxHeadInfo: [
                {
                    option:'Presumptive Tax'
                },
                {
                    option:'National Hospital Insurance Fund (NHIF)'
                },
                {
                    option:'Advamce Tax'
                },
                {
                    option:'Income Tax-Rent Income Withholding'
                },
            ],
            ProceedReasons: [
                {
                    option:'Significant Amount to be Recovered'
                },
                {
                    option:'Others'
                },
            ],
            ClosureReasons: [
                {
                    option:'Insignificant Amount amount to be Recovered'
                },
                {
                    option:'Errors in Computation'
                },
                {
                    option:'High Profile Case'
                },
                {
                    option:'Insufficient Data for Proove'
                },
                {
                    option:'Others'
                },
            ],
            index: 1,
            formDynamic2: {
                items: [
                    {
                        PRELIM_REPORT_AMOUNT: "",
                        YEAR_FROM: "",
                        YEAR_TO: "",
                        OBLIGATION_NAME: "",
                    }
                ]
            },
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
        attachments,
        PdfPreview
        // sentBackDetails
    },
    computed: {
       
        totalAmount: {
            
            get() {
                return this.prelimData.total;
                
            },
            set(value) {
                this.prelimData.total = value;
            }
        },
        checkValid() {
            return this.res_case1.every((per) => per.isChecked);
        },
        maxDate() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return today;
        },
    },
    watch: {
        'res_case1.$each.REPORT_FIN_AMOUNT'(newValue, oldValue) {
        const index = this.res_case1.findIndex((per) => per.newAmount === oldValue);
        if (newValue === '' || newValue == 0) {
            this.$set(this.res_case1[index].Description, 'payment', false);
        } else {
            this.$set(this.res_case1[index].Description, 'payment', true);
        }
    },
        res_case: {
            handler(newData){
                this.formDynamic2.items = newData
            }
        }
    },
    methods: {
        handleBeforeUpload(files) {
            const totalSize = files.reduce((acc, file) => acc + file.size, 0);
            if (totalSize > 25 * 1024 * 1024) {
                this.$Message.error('Total file size should not exceed 25MB');
                return false;
            }
            return true;
        },
        onPreviewChange(isOpen) {
            this.attachmentPreview = isOpen
        },
        getDescription(per) {
            // console.log("New amount:", per.REPORT_FIN_AMOUNT);
            if (per.REPORT_FIN_AMOUNT == "") {
                return 'Assement not yet raised on Itax!';
            }
            return 'Assement Raised';
        },

        formatDate(date) {
            const options = { year: 'numeric', month: 'short' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },

        async getTaxpayerResData() {
            // console.log("I can be seen");
            const res = await this.callApi('get', `/api/fetchDetails/${this.data.caseId}`);
            this.res_case = res.data.period;
            console.log("File at the index of 0", this.res_case);
            this.res_case1 = res.data.period.map((item) => ({ ...item, newAmount: '', isChecked: false, Description: { payment: false }, }));
            // console.log("I can be seen 2");
            if (this.stage =='AssessmentReturns') {
                const itaxArray = await this.callApi('get', `/api/compare-assessment-amounts/${this.data.caseId}`);
            // console.log("Can get I tax:", itaxArray.data);
            const taxArray = itaxArray.data;

            if (taxArray && Array.isArray(taxArray)) {
                for (let i = 0; i < taxArray.length; i++) {
                    // console.log("In the loop.....");
                    const itaxData = taxArray[i];
                    if (Array.isArray(itaxData) && itaxData.length > 0) {
                        const itax = itaxData[0];
                        // const resIndex = taxArray.length - 1 - i;
                        const yearFromFormatted = moment(this.res_case1[i].YEAR_FROM).format('YYYY-MM-DD HH:mm:ss');
                        const yearToFormatted = moment(this.res_case1[i].YEAR_TO).format('YYYY-MM-DD HH:mm:ss');
                        const match = this.res_case1[i] && yearFromFormatted == itax.trp_from_dt &&
                        yearToFormatted == itax.trp_to_dt && this.res_case1[i].REPORT_FIN_AMOUNT == itax.gross_tax;
                        
                        // console.log("From:", yearFromFormatted,"To:", yearToFormatted);
                        // console.log("Itax match", match);
                        if (match) {
                            this.res_case1[i].isChecked = true;
                        }
                    }
                }
            } else {
                console.error("Invalid itax data!");
                this.error("Something went wrong!");
            }
            }
            // console.log("Fetched data:", res);
        },
        async ResponseSubmit(){
            try {
                const { Remarks, Reason,finr_amount, options_r, caseId, username } = this.data;
                const resData = await this.callApi('post', `/api/submitReport`, { Remarks, Reason,finr_amount, options_r, caseId, username })
                console.log("Submit TP res report:", resData);
                this.success('Response Submitted Successfully!');                                                                                                                                                                   
                this.$router.go(-1)
                this.isLoading = false;
            } catch (error) {
                console.error(error);
                this.isLoading = false;
            }
        },

        // Contacting Taxpayer
        async contactTaxpayer(){
            this.isLoading = true;
                try {
                    const { taxpayerEmail, cc, bcc, subject, body, caseId, filename, username } = this.data;
                    const res = await this.callApi('post', `/api/contactTaxpayer`, { username, taxpayerEmail, cc, bcc, subject, body, caseId, filename })

                    if (res.status == 200) {
                        this.$Message.success('Contacting Taxpayer Successful!');
                        this.isLoading = false;
                        this.success("Message sent to taxpayer...")
                        this.$router.go(-1)
                    } else {
                        this.swr("Error occured while contacting taxpayer!")
                    }
                    
                } catch (error) {
                    console.error(error);
                    this.swr("Error occured while contacting taxpayer!")
                    this.isLoading = false;
                }
        },
        // confirm assssment
        async Confirm() {
            try {
                    // const { caseId, filename, newAmount } = this.data
                    const pass ={...this.res_case1,case: "AssessmentReturns", filename: this.data.filename, caseId: this.data.caseId}
                    console.log("Pass data:", pass);
                    const res = await this.callApi('post', `/api/updateCase`, pass)
                    // console.log("Confirm assement one:", res);
                    this.$Message.success('Success!');
                    this.$router.go(-1)
                } catch (error) {
                    console.log(error);
                    this.swr("Something went wrong!")
                }
            
        },
        async Confirm1() {
          
            // const res2 = await this.callApi('get', `/api/fetchDetails/${this.data.caseId}`);
            // this.res_case = res2.data.details[0];

            var formData1 = this.formDynamic2.items;
            let size = formData1.length
            this.index++;
            const fileAttached = this.data.filename[0] ? true: false;
            console.log("Check file:", fileAttached);
            if (!fileAttached) return this.error("Attachment's missing. Please attach a file! ");
            for (let i = 0; i < size; i++){
              
                var newData1 = formData1[i];
                // return error
                if(newData1.OBLIGATION_NAME =='') return this.error('Tax Head is required')
                if(newData1.YEAR_FROM =='') return this.error('Period From is required')
                if(newData1.YEAR_TO =='') return this.error('Period To is required')
                if(newData1.PRELIM_REPORT_AMOUNT =='') return this.error('Amount is required')
            }
           
            const res2 = await this.callApi('get', `/api/fetchDetails/${this.data.caseId}`);
            const details = res2.data.details[0];
            console.log(res2)
            console.log(details)

         
        
            if (details['STATUS'] == -1) {
                    const payload = {...formData1,
                        items:  this.formDynamic2.items.map(itm => ({
                            TaxHead: itm.OBLIGATION_NAME,
                            Year_from: itm.YEAR_FROM,
                            Year_to: itm.YEAR_TO,
                            Amount: itm.PRELIM_REPORT_AMOUNT,
                        })),
                        
                        filename: this.data.filename, 
                        case: "reverted", 
                        caseId: this.data.caseId,
                        status: this.Astatus
                    }
                    // console.log("Data I want to pass:",payload);
                    const res = await this.callApi('post', `/api/updateCase`, payload)
                    if (res.status == 200) {
                        // console.log("Confirm assement one:", res);
                        this.$Message.success('Success!');
                        this.$router.go(-1)
                    } else {
                        this.$Message.error("Something went wrong!");
                    }
                }
                if (details['STATUS'] == -5 || details['STATUS'] == -3) {
                    const payload = {...formData1,
                        items:  this.formDynamic2.items.map(itm => ({
                            TaxHead: itm.OBLIGATION_NAME,
                            Year_from: itm.YEAR_FROM,
                            Year_to: itm.YEAR_TO,
                            Amount: itm.PRELIM_REPORT_AMOUNT,
                        })),
                    filename: this.data.filename, 
                    case: "reverted", 
                    caseId: this.data.caseId,
                    status: this.Fstatus
                }
                console.log("Data I want to pass:",payload);
                const res = await this.callApi('post', `/api/updateCase`, payload)
                if (res.status == 200) {
                    console.log("Confirm assement one:", res);
                    this.$Message.success('Success!');
                    this.$router.go(-1)
                } else {
                    this.$Message.error("Something went wrong!");
                }
                }
                if (details['STATUS'] == -12) {
                    const payload = {...formData1,
                        items:  this.formDynamic2.items.map(itm => ({
                            TaxHead: itm.OBLIGATION_NAME,
                            Year_from: itm.YEAR_FROM,
                            Year_to: itm.YEAR_TO,
                            Amount: itm.PRELIM_REPORT_AMOUNT,
                        })),
                    filename: this.data.filename, 
                    case: "reverted", 
                    caseId: this.data.caseId,
                    status: this.Rstatus
                }
                console.log("Data I want to pass:",payload);
                const res = await this.callApi('post', `/api/updateCase`, payload)
                if (res.status == 200) {
                    // console.log("Confirm assement one:", res);
                    this.$Message.success('Success!');
                    this.$router.go(-1)
                } else {
                    this.$Message.error("Something went wrong!");
                }
                

            }
        },

        async handleSubmit1 () {
                const fileAttached = this.data.filename[0] ? true: false;

                if (fileAttached) {
                    switch (true) {
                        case ( this.Choice === "periods" ):
                            var formData = this.formDynamic1.items;
                            console.log("Form Data[0]:", formData);
                            let size = formData.length
                            this.index++;
                            for (let i = 0; i < size; i++){
                                let newData = formData[i];
                                if(newData.TaxHead =='') return this.error('Tax Head is required')
                                if(newData.Amount =='') return this.error('Amount is required')
                                if(newData.Year_from =='') return this.error('Period From is required')
                                if(newData.Year_to =='') return this.error('Period To is required')
                            }
                            if(this.data.Remarks =='') return this.error('Please add a RemarK')
                            if(this.data.Reason =='') return this.error('Please Select a Reason')
                            let totalAmounts = 0;
                            this.totalAmount = totalAmounts;
                            for (let i = 0; i < this.formDynamic1.items.length; i++) {
                                totalAmounts += parseInt(this.formDynamic1.items[i].Amount);
                                const postData = {...this.data, 
                                    Amount: this.formDynamic1.items[i].Amount,
                                    Obligation: this.formDynamic1.items[i].TaxHead,
                                    Year_from: this.formDynamic1.items[i].Year_from,
                                    Year_to: this.formDynamic1.items[i].Year_to,
                                    status: this.formDynamic1.items[i].status,
                                    Choice: this.Choice,
                                    totalAmount: this.totalAmount,
                                    case:"",
                                    here:"prelimAmount"
                                }
                                console.log("First looop postData:", postData,'\n',"Amount totalAmounts:", totalAmounts);
                                const res = await this.callApi('post',"/api/updateCase/", postData)
                                // console.log("Response when periods:",res);
                            }
                            const payload = {
                                caseId: this.data.caseId,
                                prAmount: totalAmounts,
                                Choice: this.Choice,
                                case: "",
                                here: ""
                            };
                            const res = await this.callApi('post', "/api/updateCase/", payload);
                            this.success('Prelim Report Submited to the Team Lead!');
                            // this.isLoading = false
                            this.$router.go(-1)
                            break;
                            
                            case ( this.Choice === "others" ):
                                for (let i = 0; i < this.formDynamic1.items.length; i++) {
                                    if(this.data.Remarks =='') return this.error('Please add a RemarK')
                                    if(this.data.Reason =='') return this.error('Please Select a Reason')
                                    const postData2 = {...this.data, 
                                        status: this.formDynamic1.items[i].status,
                                        Choice: this.Choice,
                                        case:""
                                    }
                                    // console.log("Files jowa!:", this.data.filename);
                                    const res2 = await this.callApi('post',"/api/updateCase/", postData2)
                                    // console.log("Response when others:",res2);
                                    // if (res2.status == 200) {
                                    //     this.isLoading = true
                                    // } else {
                                    //     this.error("Something Went Wrong!")
                                    // }
                                }
                            this.success('Success!');
                            // this.isLoading = false
                            this.$router.go(-1)
                            break;
                    
                        default:
                            this.$Message.error("Something went wrong!");
                            break;
                    }
                } else {
                    this.error("Report file is required. Kindly upload it!");
                }
        },
        handleReset1 (name) {
            this.$refs[name].resetFields();
        },
        handleAdd1(name) {
            var formData = this.formDynamic1.items;
            var size = formData.length
            this.index++;
            // check for empty formdata varibles and return errors
            for (let i = 0; i < size; i++){
                var newData = formData[i];
                // return error
                // this.isValid = false;
                if(newData.Amount =='') return this.error('Amount is required')
                if(newData.TaxHead =='') return this.error('Tax Head is required')
                if(newData.Year_from =='') return this.error('Period From is required')
                if(newData.Year_to =='') return this.error('Period To is required')
            }
            // var Total = formData.Amount.reduce((a,b)=> a +b ,0);
            // this.isValid = true
            // if formdata is filled add new one
            let amount = isNaN(parseFloat(newData.Amount)) ? 0: parseFloat(newData.Amount);
            this.totalAmount = amount + parseFloat(this.totalAmount);
            formData.push({
                Amount: '',
                Year_from: '',
                Year_to: '',
                TaxHead: '',
                index: 1,
                status: 1,
                index: this.index
            }); 

        },
      
        handleRemove1 (index) {
            const amount = 0;
            console.log(this.formDynamic1.items[index].Amount)
            if (this.formDynamic1.items[index].Amount == '') {
                this.formDynamic1.items[index].Amount =amount
            }else{
                this.formDynamic1.items[index].Amount = this.formDynamic1.items[index].Amount;
            }
            this.prelimData.total -= parseFloat(this.formDynamic1.items[index].Amount)
            this.formDynamic1.items[index].status = 0;
            this.formDynamic1.items.splice(index);
            var amount2 = isNaN(parseFloat(this.formDynamic1.items[index].Amount)) ? 0 : parseFloat(this.formDynamic1.items[index].Amount);
                this.totalAmount -= amount2;
            this.formDynamic1.items[index].Amount;
                        this.isValid = true
                        console.log(this.formDynamic1.items)
                        console.log(this.prelimData.total)
                        // console.log(this.prelimData.total)
                    },
                    // attachment ends
        
            handleSuccess(res) {
                // Add the uploaded file path to the existing state array
                this.data.filename.push(res.filenames[0]);
                // Set the isUploaded flag to true if there is at least one file path in the state array
                this.isUploaded = this.filename.length > 0;
            },

        // handleSuccess(res){
        //     // res = `${res}`
        //     // this.data.filename = res
        //     this.data.filename = res.filePaths;
        //     this.isUploaded = true
        // },
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
        addNewCC() {
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!filter.test(this.newCC)) {
                return this.error('Please provide a valid email address');
            }
            else {
                this.ccEmails.push({
                cc: `${this.newCC}`,
                label: `${this.newCC}`
                });
                this.data.cc.push(this.newCC)
                this.newCC = '';
            }

        },

    async Approve(){
        // checking for file upload status
        const uploadfile = document.querySelector('#fileUpload')

        this.isLoading=true

        console.log("It's me you want!")
    
        const res = await this.callApi('post', "/api/ApproveCase", this.data)
        if (res.status === 200) {
            this.success('Case has been Approved successfully!')
            window.location =`${this.Path}`
        }
        else {
            this.swr('Something Went Wrong')
        }
        this.isLoading=false

    },


                    
    async rejectcase(postData){
        console.log("Rej postData:", postData);
        const res = await this.callApi('post', "/api/RejectCase",postData)
        
        if (res.status === 200) {
            this.isLoading = false
            this.success('case has be rejected')
            window.location =`${this.Path}`
        }
        else {
            this.isLoading = false
            this.swr('Something Went Wrong!')
        }
                    
    }, 
    
    async approvecaseyah(postData){ 
        console.log("Aprv postData:", postData);
        const res = await this.callApi('post', "/api/ApproveCase",postData)
        if (res.status === 200) {
            this.isLoading = false
            this.success('Case has been forwared')
            window.location =`${this.Path}`
        }
        else {

            this.isLoading = false
            this.swr('Something Went Wrong!')
        }
    },
    async aprrove() {
    
        const res2 = await this.callApi('get', `/api/fetchDetails/${this.data.caseId}`);
        const details = res2.data.details[0];
        const stat =details['STATUS']
        const dx = res2.data.period[0];
        const rico =res2.data.period[0]? dx['REPORT_ACTION']:null


        if (this.role == 'TeamLead'){   
            const uploadfile = document.querySelector('#fileUpload')
            if(this.ApproveData.option=="Approve"){
                this.isLoading = true
                if (this.action == "sent-back"){
                
                    if (stat==1 || stat==-2){

                        const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.data.Reason,
                            remarks: this.data.Remarks,
                            Astatus:this.Astatus
                        } 
                        this.approvecaseyah(postData)

                    } else if (stat==-6 || stat == 5){

                        const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.data.Reason,
                            remarks: this.data.Remarks,
                            Fstatus:this.Fstatus
                        } 
                        this.approvecaseyah(postData)

                    } else if (stat == 12|| stat ==-13){

                        const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.data.Reason,
                            remarks: this.data.Remarks,
                            Mstatus:this.Mstatus
                        } 
                        this.approvecaseyah(postData)
                    }

                    
                } else {
                
                    const postData = {...this.data, 
                        Recommendation:this.ApproveData.option,
                        caseId:this.data.caseId,
                        reason: this.data.Reason,
                        remarks: this.data.Remarks,
                        Astatus:this.Astatus
                    } 
                    this.approvecaseyah(postData)
                    
                }
                
            
            }
            else if(this.ApproveData.option=="Revert"){
                this.isLoading = true
                if (stat==1 || stat==-2){

                    const postData = {...this.data, 
                        Recommendation:this.ApproveData.option,
                        caseId:this.data.caseId,
                        reason: this.RejectData.Reason,
                        remarks: this.RejectData.Remarks,
                        Rstatus:this.Rstatus
                    } 
                    this.rejectcase(postData)
                
                }else if(stat == -6 || stat == 5){

                    const postData = {...this.data, 
                        Recommendation:this.ApproveData.option,
                        caseId:this.data.caseId,
                        reason: this.RejectData.Reason,
                        remarks: this.RejectData.Remarks,
                        Rstatus:this.RFstatus? this.RFstatus:this.Rstatus
                    } 
                    this.rejectcase(postData)

                }else if(stat == 12|| stat ==-13){
                    
                    const postData = {...this.data, 
                        Recommendation:this.ApproveData.option,
                        caseId:this.data.caseId,
                        reason: this.RejectData.Reason,
                        remarks: this.RejectData.Remarks,
                        RMstatus:this.RMstatus ? this.RMstatus:this.Rstatus
                    } 
                    this.rejectcase(postData)
                    
                }

            } 
            
        }
    else if (this.role == 'SectorManager'){    
        // this.isLoading = true
        const uploadfile = document.querySelector('#fileUpload')
        if(this.ApproveData.option=="Approve"){
            this.isLoading = true
            if (this.action == "sent-back"){
                const postData = {...this.data, 
                    Recommendation:this.ApproveData.option,
                    caseId:this.data.caseId,
                    reason: this.data.Reason,
                    remarks: this.data.Remarks,
                    Mstatus:this.Mstatus
                }
                this.approvecaseyah(postData) 
                this.isLoading = false  
            }else{
                if (rico =="Proceed with Case" ){
                const Ax = {...this.data, 
                        Recommendation:this.ApproveData.option,
                        caseId:this.data.caseId,
                        reason: this.data.Reason,
                        remarks: this.data.Remarks,
                        Astatus:this.Astatus
                    } 
                    this.approvecaseyah(Ax)
                    this.isLoading = false
                    }
                else if(rico =="Recomnend As Level-Two" ){
                const Bx = {...this.data, 
                        Recommendation:this.ApproveData.option,
                        caseId:this.data.caseId,
                        reason: this.data.Reason,
                        remarks: this.data.Remarks,
                        Astatus:8
                    } 
                        this.approvecaseyah(Bx)
                        this.isLoading = false
                    }
                else if (rico =="Recomnend for Closure" ){
                const Cx = {...this.data, 
                        Recommendation:this.ApproveData.option,
                        caseId:this.data.caseId,
                        reason: this.data.Reason,
                        remarks: this.data.Remarks,
                        Astatus:11
                    } 
                        this.approvecaseyah(Cx)
                        this.isLoading = false
                }
             
                }
        }
                else if(this.ApproveData.option=="Revert"){
                   
                    this.isLoading = true
                    if (stat==2){
                        const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.RejectData.Reason,
                            remarks: this.RejectData.Remarks,
                            Rstatus:this.Rstatus
                        } 
                        this.rejectcase(postData)
                        this.isLoading = false
                    }else if(stat == 6){
                        var s = this.RFstatus ? this. RFstatus : this.Rstatus
                        const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.RejectData.Reason,
                            remarks: this.RejectData.Remarks,
                            Rstatus:s

                        } 
                        this.rejectcase(postData)
                        this.isLoading = false
                    }else if(stat == 13|| stat ==-14){
                        const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.RejectData.Reason,
                            remarks: this.RejectData.Remarks,
                            RMstatus:this.RMstatus ? this.RMstatus : this.Rstatus
                        } 
                        this.rejectcase(postData)
                        this.isLoading = false
                    }
                }
            }
            
            else if (this.role == 'TSOhead'){ 
                this.isLoading = true   
            const uploadfile = document.querySelector('#fileUpload')   
            if(this.ApproveData.option=="Approve"){
                if (this.action == "sent-back"){
                    
                    const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.data.Reason,
                            remarks: this.data.Remarks,
                            Mstatus:this.Mstatus
                        }
                        this.approvecaseyah(postData)
                    
                    }else{
                const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.data.Reason,
                            remarks: this.data.Remarks,
                            Astatus:this.Astatus
                        } 
                       
                        this.approvecaseyah(postData)
                    }
                }
                else if(this.ApproveData.option=="Revert"){
                    
                    if (this.action == "sent-back"){
                    const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason: this.data.Reason,
                            remarks: this.data.Remarks,
                            RMstatus:this.RMstatus
                        }
                        this.approvecaseyah(postData)
                    
                    }
                        else
                        {
                    const postData = {...this.data, 
                            Recommendation:this.ApproveData.option,
                            caseId:this.data.caseId,
                            reason:this.RejectData.Reason,
                            remarks: this.RejectData.Remarks,
                            approvalType:"rejection",
                            Rstatus:this.Rstatus 
                        } 
                   
                       
                        const res = await this.callApi('post', "/api/RejectCase",postData)
                        if (res.status === 200) {
                            this.isLoading = false
                            this.success('case has be rejected')
                            window.location =`${this.Path}`
                        }
                        else {

                            this.isLoading = false
                            this.swr('Something Went Wrong!')
                        }
                    }
                }
            
            }
       
        },



    },
        async created() {
           console.log("formDynamic2:", this.formDynamic2.items);
            const  res1= await this.callApi("get", "/api/getObligations")
            if (res1.status === 200) {
                this.obligation = res1.data;
            } else {
                this.swr();
            }
            
            this.res_case.forEach(row => {
                this.$set(this.data.finr_amount, row.id, '');
                this.$set(this.data.options_r, row.id, '');
            });
            const manager =await this.callApi('get',`/api/getUserInfoWhere/${this.username}`)
            console.log("Here's Manager's mail",manager);
                   var mailx = manager.data[0].EMAIL_ADDRESS
                const caseIdxx = this.$route.params.caseId;
               
                const res = await this.callApi('get',`/api/fetchDetails/${caseIdxx}`)
             
               
              
                if(res.status===200){
                    // this.details = res.data.details
                    this.periodss = res.data.period
                    this.data.body = `DOMESTIC TAX DEPARTMENT

The amounts listed in the attachement herein are due and payable immediately. You are therefore required to make the payments or provide a valid explanation for the above discrepancies within 15 days.

Kindly channel all responses  through email: ${mailx}\n
Yours Faithfully,\n
FOR: COMMISSIONER,DOMESTIC TAX DEPARTMENT\n 
This is an automatically generated email.  Please do not reply to it.`
                }else{
                    this.swr('something went wrong')
                }
            this.getTaxpayerResData()
            // this.contactTaxpayer()
            let date = new Date();
            this.data.now = date.toISOString().substr(0, 10);
            // toLocaleDateString('en-GB', {
            //         year: 'numeric',
            //         month: 'short',
            //         day: 'numeric'
            // });
            if ((this.stage) === 'AssessmentReturns') {
                this.bigCol = 'col-md-9',
                this.smallCol ='col-md-3'
            }      
            else {
                this.bigCol = 'col-md-9'
                this.smallCol ='col-md-3'
            }
            // this.token = window.Laravel.csrfToken
            const [resCase] = await Promise.all([
            // const [resCase,resApproval,resRejection,resReason] = await Promise.all([
                this.callApi('get',`/api/fetchDetails/${this.data.caseId}`)
               
            ])
            // console.log('Rescase data',resCase)
            if(resCase.status == 200){
                this.caseDetails = resCase.data.details[0]
                this.casePeriods = resCase.data.period[0]
                var smPin = this.caseDetails['SM_PNO']
                // this.data.tpin = this.caseDetails[0]['ASSESSMENT_NUMBER']
                this.data.taxpayerEmail = this.caseDetails['TP_EMAIL']
                this.data.subject = this.casePeriods['OBLIGATION_NAME']
               
                // fetching rejection reason
                this.data.rejection_id =this.caseDetails['REJECTION_ID']
            }
            const getsmMail = await this.callApi('get',`/api/getsmMAil/${smPin}`)
            
            if (getsmMail.status == 200) {
                this.data.bcc = getsmMail.data[0]['EMAIL_ADDRESS']
            }
    }

}
</script>
