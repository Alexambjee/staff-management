<template>
  <div>
      <!-- message container -->
        <div class="message-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class=" card-header text-light bg-dark  text-center"
                        data-toggle="collapse" 
                        data-target="#taxpayer" aria-expanded="false" aria-controls="#Response"
                        style="cursor:pointer;">
                            <h5 class="text-center text-muted pb-1">MESSAGE TO TAXPAYER </h5>
                        </div>
                         <!-- accordion -->
                        <div id="accordion" class="col-lg-12 mt-3" style="width:100%">
                            <!-- awaiting taxpayer response -->
                            <div class="card shadow">
                                <div id="taxpayer" class="collapse" aria-labelledby="taxpayer" data-parent="#accordion">
                        <!-- Mail list-->
                            <div class="table-responsive">
                                <table class="table email-table no-wrap table-hover table-bordered v-middle mb-0 font-13 " style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>TAXPAYER PIN</th>
                                            <th>SUBJECT</th>
                                            <th>MESSAGE</th>
                                            <th>DATE CREATED</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="color: gray" v-for="(message, i) in Messages" :key="i" v-if="Messages.length">
                                            <td class="pl-3">{{message.ID}}</td>
                                            <td class="pl-3">{{message.TO}}</td>
                                            <td><span class="mb-0">{{message.SUBJECT}}</span></td>
                                            <td class="message-reduced">
                                                <span> {{message.BODY }}</span>
                                            </td>
                                            <td>{{moment(message.created_at).format('lll')}}</td>
                                            <td>
                                                <button class=" btn btn-circle btn-success text-white btn-sm " @click="showDetailsModal(message, i)" 
                                                style="display: flex; align-items: center; justify-content: center; gap: 5px; " >
                                                    <i class=" fa fa-eye  mr-3" ></i> view
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Mail list ends-->
                                   

                                </div>
                            </div>
                            <!-- awaiting taxpayer response ends-->

                        </div>
                        <!-- accordion ends -->

                        <!-- <div class="tab-content" id="myTabContent"> -->
                            

                    </div>
                </div>
            </div>
        </div>
        <!-- message container ends-->
       <!-- details modal -->
        <Modal
            v-model="detailsModal"
            title="Message Details"
            :mask-closable="false"
            width="600"
            :styles="{ top: '20px' }"
            :closable="true"
        >
            <!-- message body -->
            <div class="row inbox-wrapper">
                <div class="col-lg-12">
                    <div class="card message-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 email-content">
                                    <div class="email-head">
                                        <div class="email-head-subject">
                                            <div class=" title d-flex align-items-center justify-content-between " >
                                                <div class=" d-flex align-items-center">
                                                    <a class="active" href="#" ><span class="icon" ></span  ></a>
                                                    <span class=" text-muted f-s-14 " style="text-transform:capitalize;" >
                                                    {{messageData.SUBJECT}}</span>
                                                </div>
                                                <div class="icons">
                                                    <button class=" btn btn-circle btn-success text-white btn-sm float-right " style=" display: flex;  align-items: center; justify-content: center; "
                                                        @click="download()"> <i class=" fa fa-print f-s-16  mr-1 "></i>
                                                        <span class=" ml-3 font-normal text-light " >Print</span >
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" email-head-sender d-flex align-items-center justify-content-between flex-wrap " ref="body" >
                                            <div class=" d-flex align-items-center " >
                                                <div class="avatar">
                                                    <img src="" alt="Avatar" class=" rounded-circle user-avatar-md "/>
                                                </div>
                                                <div class="sender d-flex align-items-center ">
                                                    <span  class="text-success">{{messageData.TAXPAYER_EMAIL }}</span>
                                                    <span>to</span><span class="text-muted" >{{messageData.FROM }}</span>
                                                </div>
                                            </div>
                                            <div class="date">
                                                {{moment(messageData.DATE_CREATED).format('lll')}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="email-body">
                                        <p>{{messageData.MESSAGE }}</p>
                                        <br />

                                        
                                    </div>
                                    <!-- <Grid :col="4" square>
                                        <GridItem>
                                            <Button @click="anim1 = !anim1">view Attachment</Button>
                                            <transition name="slide-down">
                                                <div v-show="anim1" class="transition-box">
                                                    <div class="card card-detail"> 
                                                        <div class="card-body">
                                                            <div class="email-attachments">
                                                                <div class="title text-bold">Attachments
                                                                    <span>(1 files, 12.44 KB)</span>
                                                                </div>
                                                                <ul>
                                                                    <li>
                                                                        <a href="#">Reference.zip
                                                                            <span class="text-muted tx-11 ">(5.10MB)</span>
                                                                        </a>
                                                                    </li>
                                                                
                                                                </ul>
                                                                   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </transition>
                                        </GridItem>
                                     
                                    </Grid> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- message body  ends-->
            <div slot="footer">
                <Button type="error" @click="detailsModal = false">Close</Button>
            </div>
        </Modal>
        <!-- details modal -->
        
    </div>

</template>
<script>
export default {
    props:['case_id'],
    data() {
        return {
            data: {
                message: "",
                subject: "",
                taxpayerEmail: "",
                date: "",
                frequency: "",
                bcc: "",
                cc: "",
            },
            anim1: false,
            detailsModal: false,
            Messages: [],
            messageData:[],
            index:-1,
        }
        
    },
    methods: {
        showDetailsModal(message, index) {
            let obj={
                ID: message.ID,
                CASE_ID: message.CASE_ID,
                MESSAGE: message.BODY,
                DATE_CREATED: message.created_at,
                TAXPAYER_PIN: message.TO,
                TAXPAYER_EMAIL: message.TAXPAYER_EMAIL,
                FROM: message.FROM
            }
            this.messageData = obj
            this.index = index
            this.detailsModal = true;
        },
    },
    async created() {
       const res = await this.callApi('get',`/api/getTaxpayerMessages/${this.case_id}`)
        if (res.status == 200) {
           this.Messages = res.data
        }
        else {
           this.swr('Something went wrong when fetching messages!')
       }
    }
};
</script>
