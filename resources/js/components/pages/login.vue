<template>
<div>

    <div id="layoutSidenav">
          <div id="layoutSidenav_content">
              <main>
                  <div class="container-fluid px-4">
                  <!-- login -->
                  <div id="login">
            <!-- <div id="layoutAuthentication_content"> -->
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-sm-6 d-flex  justify-content-center">
                                <div class="card shadow-lg border-0 rounded-lg mt-3">
                                    <div class="card-body">
                                        <div class=""><img src="/images/insight.png" alt="insight-image"/> </div>
                                        <div><h5 class="text-success text font-weight-bold my-4">DTD Compliance Level One</h5></div>
                                            <div class="form-group form-floating mb-3">
                                             <div class="label"> <label for="Uname">Username</label> </div>
                                                <Input  v-model="data.username"  type="text" placeholder="username"  style="text-transform:uppercase important"/>
                                            </div>
                                            <div class="form-group form-floating mb-3">
                                             <div class="label"> <label for="inputPassword">Password</label> </div>
                                                <Input  v-model="data.password" id="inputPassword" type="password" placeholder="*********" />
                                            </div> <br>
                                            <!-- <router-link to="/officer"> -->
                                               <div class="log form-floating mb-3">
                                                <button  style="width:100%"  class="btn btn-success btn-block"
                                                @click="login()" :disabled="isLoading" :loading="isLoading"
                                                ><span style="margin-bottom:10px !important;">{{isLoading ?'Please Wait.....':'Login'}}</span></button>
                                            </div>
                                            <!-- </router-link> -->

                                            <!-- <div><router-link class="small" to="/reset-password">Forgot Password?</router-link></div> -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            <!-- </div> -->
            <div id="layoutAuthentication_footer">
            </div>
        </div>
                  <!-- login ends-->


                    </div>
              </main>
          </div>
    </div>
</div>
</template>

<script>
import store  from '../../store';
export default {
    data(){
        return{
            data:{
                username:'',
                password:''
            },
            isLoading:false

        }
    },
    methods: {
        async login() {
            if(this.data.username.trim()=='') return this.warning('Username Is Required')
            if(this.data.password.trim()=='') return this.warning('Password Is Required')
            let obj={
                username:this.data.username.toUpperCase(),
                password:this.data.password
                }
            this.isLoading=true
            const res = await this.callApi('post','/api/login',obj)
            // login successfull
            if(res.status==200){
                this.success(res.data.msg)
                let x =res.data.user.role.ROLE_NAME
                let uid = new Date() + res.data.user.password + new Date();
                sessionStorage.setItem('dtd-user',  uid)
                localStorage.setItem('dtd-user', uid)
                localStorage.setItem('primary-permission',x);
                // localStorage.setItem('secondary-permission', res.data.user.SECONDARY_ROLE);


                switch (x) {   
                    case "Risk":
                        window.location="/risk"
                        break;                 
                  case "AccountManager":
                        window.location="/Account-manager"
                        break;
                   case "TeamLead":
                        window.location="/Team-lead"
                        break;
                   case "SectorManager":
                   window.location="/Sector-manager"
                        break;
                   case "TSOhead":
                   window.location="/Tso-head"
                        break;
                    case "Admin":
                    window.location="/admin"
                        break;
                    default:
                        this.error("Something went wrong! contact Adminstrator");
                        break;
              
                }
                this.isLoading=false
            }
            else{
                // invalid credentials
                if(res.status === 401){
                    this.warning(res.data.msg)
                    this.isLoading=false
                    
                }

                // return errors
                else if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                    this.isLoading=false

                }
                else{
                    this.swr()
                    this.isLoading=false
                }


            }

            this.isLoading= false

        }

    }
}
</script>
<style scoped>
.container-fluid{
width: 100%;
height: 100vh;
background-image: url('/images/NAIROBI.jpg');
background-position:top;
background-size: cover;
position: relative;
/* margin-top: 300px !important; */


}
.card{
max-width: 24rem;
align-items:center;
margin-top: 100px !important;
border-radius:10px !important;
justify-content:center !important;

}


.form-control{
width:100%;
height: 32px;
}
img {
 display: block;
 margin-left: auto;
 margin-right: auto;
width: 50%;
/* height: 100px; */
}

.text {
text-align: center;
font-weight: 700 !important;
}
.form-control{

padding: 10px 10px !important;
}

button{
width:100%;
height: 40px;
}

button span{
letter-spacing:2px;
font-size:13px;
}

</style>
