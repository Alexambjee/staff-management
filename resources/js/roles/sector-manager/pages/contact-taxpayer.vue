<template>
  <div v-if="data.caseId && ($store.state.user) && ($store.state.userRole == 'SM_PNO' || $store.state.userRole =='SM_PNO')">
  <!-- navbar -->
    <Navbar v-if="$store.state.user" :user="$store.state.user" :messagePath="data.messagePath"/>
       <div id="layoutSidenav">
       <Sidebar v-if="$store.state.user" :user="$store.state.user"  :username="username" :manualPath="manualPath" :channel="channel"/>
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                      <h1 class="mt-4">CASE ANALYSIS</h1>
                        <div class="breadcrumb mb-4 mt-3">
                           <Breadcrumb>
                            <BreadcrumbItem to="/officer" class="breadcrumb-item">Dashboard</BreadcrumbItem>
                            <BreadcrumbItem to="/officer/mycases" class="breadcrumb-item">My cases</BreadcrumbItem>
                            <BreadcrumbItem to="/officer/mycases/" class="breadcrumb-item active">Case Analysis</BreadcrumbItem>
                        </Breadcrumb>
                      </div>
                    <div class="card mb-4">


                            <div class="card-body">
                           <!-- default case component -->
                            <casedefault :Details="this.caseDetails"  :opt="data.opt" :role="data.role" />
                            <!-- comments & attachment section -->
                            <div class="comments-details">
                                <div class="row ">
                                    <!--contact taxpayer section -->
                                    <div class="col-md-8">
                                        <div class="card detail-card">
                                                <div class="card-header bg-dark text-muted text-center">
                                                    <h5 class="text-muted">CONTACT TAXPAYER</h5>
                                                </div>

                                            <div class="card-body detail-card pt-0 mt-0">
                                              <!-- content form -->
                                              <form>
                                                <div class="form-content">
                                                    <div class="form-group d-flex  mt-3">
                                                        <div class="col-3">
                                                            <label class="label">Taxpayer Email <span class="ml-2 text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-8 text-muted" >
                                                            <Input placeholder="Taxpayer email...." class="input"
                                                            type="email" style="color:#ccc !important; font-weight:bold !important;
                                                            text-transform: lowercase !important;"
                                                            v-model="data.taxpayerEmail" id="temail" disabled />
                                                             <small id="error"></small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group d-flex ">
                                                        <div class="col-3">
                                                            <label class="label">CC </label>
                                                        </div>
                                                        <div class="col-8">
                                                            <Input placeholder="CC email...." id="cc"  type="email" v-model="data.cc" />
                                                             <small id="error"></small>

                                                        </div>
                                                    </div>
                                                    <div class="form-group d-flex">
                                                        <div class="col-3">
                                                            <label class="label">Subject <span class="ml-2 text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-8">
                                                            <Input placeholder="Subject.." disabled
                                                             type="textarea" :rows="2" v-model="data.subject" class="input" id="subject"   />
                                                             <small id="error"></small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group d-flex mt-2">
                                                        <div class="col-3">
                                                            <label class="label">Message Body <span class="ml-2 text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-8" style="text-align:justify;">
                                                        <Input type="textarea" placeholder="Message body...."
                                                        :rows="8" id="body" class="input"
                                                        v-model="data.body" />
                                                             <small id="error"></small>

                                                        </div>


                                                    </div>
                                                    <!-- submit button -->
                                                        <div class="form-group text-center mt-2">
                                                            <Button type="success" size="small"
                                                            @click="contactTaxpayer()" :loading="isLoading">
                                                            {{ isLoading ?'Please wait...' :'Submit' }}
                                                            </Button>
                                                        </div>
                                                </div>
                                                <!-- content form ends-->
                                               </form>
                                            </div>

                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="card detail-card">
                                              <div class="card-header pb-2 bg-dark text-muted text-center">
                                                    <h5 class="text-muted">ATTACHMENTS</h5>
                                                </div>
                                                    <div class="card-body detail-card">

                                                      <!-- attachment lists -->
                                                      <attachments :case_id="data.caseId" :tPin="data.tpin" />
                                                      <!-- attachment lists ends-->
                                                       <div class="file-section top-header d-flex justify-content-between" style="box-shadow:0 5px 10px rgba(0,0,0,0.1);">
                                                        <span class="m-2" style="text-decoration:none !important;">
                                                        <!-- uploading files -->
                                                            <!--  -->
                                                        <Upload
                                                            ref="upload"
                                                            :on-success="handleSuccess"
                                                            :headers="{'x-csrf-token' :token,'X-Requested-With':'XMLHttpRequest'}"
                                                            :on-error="handleError"
                                                            :format="['pdf','docx','xlsx']"
                                                            :on-format-error="handleFormatError"
                                                            :max-size="20048"
                                                            :on-exceeded-size="handleMaxSize"
                                                            action="/api/upload" id="fileUpload" class="input">
                                                            <Button icon="ios-cloud-upload-outline">Upload file(s)</Button>
                                                        <small class="text-muted">(.pdf, .docx)</small>
                                                        </Upload>

                                                        <small id="error"></small>
                                                        </span>

                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--contact taxpayer section ends-->
                            </div>
                            <!-- comments & attachment section ends -->
                        </div>
                    </div>
                  </div>
            <!--details ends -->
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
import Footer from '../../../components/footer.vue'
import Sidebar from '../../../components/sidebar.vue'
import Navbar from '../../../components/navbar.vue'
import casedefault from '../../../components/pages/cases.vue'
import attachments from '../../../components/pages/attachments.vue'


export default {
  props:['user','role'],
  data(){
    return{
        data:{
            caseId: this.$route.params.caseId,
            body:'',
            subject: "",
            taxpayerEmail:'',
            cc:'',
            filename: '',
            opt:'see',
            tpin:'',
            username:this.$store.state.user.USER_NAME,
      role: this.$store.state.userRole,
            messagePath:'/Sector-manager/messages',
            channel: '/Sector-manager/progress-reports',
            manualPath:'/Sector-manager/Manual-cases',
        },
        caseId: this.$route.params.caseId,
        caseDetails: [],
        isLoading:false,
        isFormvalide:false,
        token:'',
        isUploaded:false,
        taxpayerEmail:'',
        assessment_no:''


    }

  },
  components : {
    Footer,
    Sidebar,
    Navbar,
    casedefault,
    attachments
  },
  methods:{
      async contactTaxpayer(){
        // console.log(this.data.taxpayerEmail)
          const email = document.querySelector('#temail')
          const subject = document.querySelector('#subject')
          const cc = document.querySelector('#cc')
          const body = document.querySelector('#body')
          const uploadfile = document.querySelector('#fileUpload')
        //   validation
            const isEmailValid = (email) => {
                return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email);
            }
        // checking email

        // cc
        //  if (!isEmailValid(this.data.cc)) {
        //     let pt = cc.parentNode;
        //     let msgElem =pt.querySelector("#error");
        //     msgElem.textContent = 'Kindly enter a valid email address!';
        //     this.isFormvalide = false

        // }
        // else if (isEmailValid(this.data.cc)==true) {
        //     let pt = cc.parentNode;
        //     email.classList.remove('invalid');
        //     let msgElem =pt.querySelector("#error");
        //     msgElem.textContent = '';
        //     this.isFormvalide = true
        //   }
        // checking subject
        if (this.data.subject == '') {
            let pt = subject.parentNode;
            subject.classList.add('invalid');
            let msgElem =pt.querySelector("#error");
            msgElem.textContent = 'Subject field is required!';
            this.isFormvalide = false

        }

        else {
            let pt = subject.parentNode;
            subject.classList.remove('invalid');
            let msgElem =pt.querySelector("#error");
            msgElem.textContent = '';
            this.isFormvalide = true


        }
        // checking body
        if (this.data.body== '') {
            let pt = body.parentNode;
            body.classList.add('invalid');
            let msgElem =pt.querySelector("#error");
            msgElem.textContent = 'Message field is required!'
            this.isFormvalide = false

        } else {
            let pt = body.parentNode;
            body.classList.remove('invalid');
            let msgElem =pt.querySelector("#error");
            msgElem.textContent = '';
            this.isFormvalide = true


        }


        // checking for file upload status
        if (this.data.filename == '') {
            let pt = uploadfile.parentNode;
            uploadfile.classList.add('invalid');
            let msgElem = pt.querySelector("#error");
            msgElem.textContent = 'Kindly upload a report';
            this.isFormvalide = false
            this.isUploaded = false
            this.error('Report File Is Required. Kindly upload it!')
        }else{
            let pt = uploadfile.parentNode;
            uploadfile.classList.remove('invalid');
            let msgElem = pt.querySelector("#error");
            msgElem.textContent = '';
            this.isFormvalide = true
            this.isUploaded = true
        }
        if(this.isUploaded==true){
            let pt = uploadfile.parentNode;
            uploadfile.classList.remove('invalid');
            let msgElem =pt.querySelector("#error");
            msgElem.textContent = '';
            this.isFormvalide =true


        }

        // if inputs are valid send  api request
          if (this.isUploaded) {
            this.isLoading = true
            this.data.filename = `${this.data.filename}`;
            const res = await this.callApi('post','/api/contactTaxpayer',this.data)
             if(res.status===200){
                this.success('Message has been sent Successfully.')
             
                 window.location="/officer/mycases"
            }
            else{
                if(res.status===422)
                {
                    for(let i in res.data.errors)
                    {
                        this.error(res.data.errors[i][0])
                    }
                }
                else{

                    this.swr()
                }
              
            }
            this.isLoading=false
        }

      },

      handleSuccess(res){
          res = `${res}`
          this.data.filename = res
          this.isUploaded = true
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
              desc:'File format of ' + file.name + ' is incorrect, please select jpg, jpeg or png.'
          });
      },
      handleMaxSize(file){
          this.$Notice.warning({
              title:'Exceeding file size limit!',
              desc:'File '+file.name + 'is too large, no more than 21MB'
          });
      },
  },
    async created() {
        if (!this.$store.state.user) {
            this.warning("Access Denied. You Don't Have Enough Permissions to Access This Route!")
            window.location = '/'
    } else{
        this.token = window.Laravel.csrfToken
        const resCase = await this.callApi('get',`/api/fetchDetails/${this.caseId}`)
        if(resCase.status == 200){
            this.caseDetails = resCase.data
            this.data.tpin = this.caseDetails[0]['ASSESSMENT_NO']
            this.assessment_no=this.caseDetails[0]['ASSESSMENT_NUMBER']
            this.data.subject="OBJECTION TO ASSESSMENT NUMBER :  " +`${this.assessment_no}`
            this.data.taxpayerEmail=this.caseDetails[0]['TP_EMAIL']
        //   this.data.tpin=this.caseDetails[0]['TAXPAYER_PIN']
      }
      else{
          this.swr('Something went wrong!')
      }
    }      
     
  }




}
</script>
