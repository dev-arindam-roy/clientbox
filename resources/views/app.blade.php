@extends('layout.layout')

@section('page_content')

<div class="container-fluid mt-3" id="app-createAccount" v-cloak>
  @include('layout.includes.loading')

  <h3>CREATE YOUR ACCOUNT</h3>
  <span><i>Get a easy way to manage your clients informations.</i></span>
  <hr/>
  
  <form name="frmx_CreateUserAccount" id="frmx_CreateUserAccount" action="{{ route('createUserAccount') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-4">
        
        <div class="form-group">
          <label class="form-label">Name: <em>*</em></label>
          <input type="text" v-model.trim="userRegistration.name" name="name" id="name" class="form-control" placeholder="Your Full Name">
          <div class="text-danger" v-if="!$v.userRegistration.name.required && $v.userRegistration.name.$error">Please enter your name.</div>
          <div class="text-danger" v-if="!$v.userRegistration.name.regxAlfaWithSpace && $v.userRegistration.name.required && $v.userRegistration.name.$error">Please enter valid name.</div>
          <div class="text-danger" v-if="!$v.userRegistration.name.minLength && $v.userRegistration.name.regxAlfaWithSpace && $v.userRegistration.name.required && $v.userRegistration.name.$error">Name should be greater than 6 chars.</div>
        </div>
        
        <div class="form-group">
          <label class="form-label">Email: <em>*</em></label>
          <input type="email" v-model.trim="userRegistration.email" name="email" id="email" class="form-control" placeholder="Your Email Address">
          <div class="text-danger" v-if="!$v.userRegistration.email.required && $v.userRegistration.email.$error">Please enter email address.</div>
          <div class="text-danger" v-if="!$v.userRegistration.email.regxEmailAddress && $v.userRegistration.email.$error">Please enter valid email address.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Mobile Number: </label>
          <input type="text" v-model.trim="userRegistration.phone" name="phone" id="phone" maxlength="12" class="form-control" placeholder="Your Mobile Number">
          <div class="text-danger" v-if="!$v.userRegistration.phone.regxNumeric && $v.userRegistration.phone.$error">Please enter valid mobile number.</div>
          <div class="text-danger" v-if="!$v.userRegistration.phone.minLength && $v.userRegistration.phone.regxNumeric && $v.userRegistration.phone.$error">Minimum 10 digits.</div>
          <div class="text-danger" v-if="!$v.userRegistration.phone.maxLength && $v.userRegistration.phone.minLength && $v.userRegistration.phone.regxNumeric && $v.userRegistration.phone.$error">Maximum 12 digits.</div>
        </div>
        
        <div class="form-group">
          <button type="button" class="btn btn-info" @click="createUserAccount">Create My Account</button>
          <a href="javascript:void(0);" id="loginBoxBtn2" class="btn btn-success">Login</a>
        </div>
      </div>
      
      <div class="col-md-4">
        
        <div class="form-group">
          <label class="form-label">Brand Name:</label>
          <input type="text" v-model.trim="userRegistration.business_name" name="business_name" id="business_name" class="form-control" placeholder="Your Business Name">
        </div>
        
        <div class="form-group">
          <label class="form-label">Password: <em>*</em></label>
          <input type="password" v-model.trim="userRegistration.password" name="password" id="password" class="form-control" placeholder="Password">
          <div class="text-danger" v-if="!$v.userRegistration.password.required && $v.userRegistration.password.$error">Please enter password.</div>
          <div class="text-danger" v-if="!$v.userRegistration.password.regxPassword && $v.userRegistration.password.$error">Use number, text and # for password.</div>
        </div>
        
        <div class="form-group">
          <label class="form-label">Confirm Password: <em>*</em></label>
          <input type="password" v-model.trim="userRegistration.confirm_password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
          <div class="text-danger" v-if="!$v.userRegistration.confirm_password.required && $v.userRegistration.confirm_password.$error">Please enter confirm password.</div>
          <div class="text-danger" v-if="!$v.userRegistration.confirm_password.sameAs && $v.userRegistration.confirm_password.required && $v.userRegistration.confirm_password.$error">Confirm password not match.</div>
        </div>
      </div>
      
      <div class="col-md-4 text-center">
        <img src="{{ asset('public/images/checklist.png') }}" class="img-fluid" style="width:150px; margin-top:62px;">
      </div>
    </div>
  </form>
</div>

<!-- Login Modal -->
<!-- The Modal -->
<div class="modal fade" id="loginModal" v-cloak>
  @include('layout.includes.loading')
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Hey! Login Your Account</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form name="frmx_login" id="frmx_login" action="{{ route('loginUserAccount') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-8">
              
              <div class="form-group">
                <label class="form-label">Email:</label>
                <input type="email" v-model.trim="userLogin.login_email" name="login_email" id="login_email" class="form-control" placeholder="Email Address">
                <div class="text-danger" v-if="!$v.userLogin.login_email.required && $v.userLogin.login_email.$error">Please enter email address.</div>
                <div class="text-danger" v-if="!$v.userLogin.login_email.regxEmailAddress && $v.userLogin.login_email.$error">Please enter valid email.</div>
              </div>
              
              <div class="form-group">
                <label class="form-label">Password:</label>
                <input type="password" v-model.trim="userLogin.login_password" name="login_password" id="login_password" class="form-control" placeholder="Password">
                <div class="text-danger" v-if="!$v.userLogin.login_password.required && $v.userLogin.login_password.$error">Please enter password.</div>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-info" @click="userAccountLogin">Login</button>
                <a href="javascript:void(0);" class="btn btn-warning" id="forgotPasswordBtn">Forgot Password?</a>
              </div>
            </div>
            <div class="col-md-4 text-center">
              <img src="{{ asset('public/images/login.png') }}" class="img-fluid" style="margin-top:55px;">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->


<!-- Forgot Password Modal -->
<!-- The Modal -->
<div class="modal fade" id="forgotPwdModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Don't Worry, Try To Set New Password</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form name="frmx_forgotpwd" id="frmx_forgotpwd" action="" method="POST">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="form-label">Registered Email:</label>
                <input type="email" name="registered_email" id="registered_email" class="form-control" placeholder="Registered Email Address">
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-info" style="width: 100%;">Send Reset Password Link</button>
              </div>
            </div>
            <div class="col-md-4 text-center">
              <img src="{{ asset('public/images/forgot.png') }}" class="img-fluid" style="margin-top:25px;">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->
@endsection

@push('page_js')
<script>
$(function() {
  $('#loginBoxBtn, #loginBoxBtn2').on('click', function() {
    $('.modal').modal('hide');
    $('#loginModal').modal('show');
  });
  $('#forgotPasswordBtn').on('click', function() {
    $('.modal').modal('hide');
    $('#forgotPwdModal').modal('show');
  });
});

let appCreateAccount = new Vue({
    el: '#app-createAccount',
    data() {
      return {
        isLoading: false,
        userRegistration: {
          name: '',
          email: '',
          phone: '',
          business_name: '',
          password: '',
          confirm_password: ''
        }
      }
    },
    validations: {
      userRegistration: {
        name: {
          required,
          regxAlfaWithSpace,
          minLength: minLength(6)
        },
        email: {
          required,
          regxEmailAddress
        },
        phone: {
          regxNumeric,
          minLength: minLength(10),
          maxLength: maxLength(12)
        },
        password: {
          required,
          regxPassword
        },
        confirm_password: {
          required,
          sameAs: sameAs('password')
        }
      }
    },
    mounted() {
    },
    methods: {
      async createUserAccount() {
        this.$v.userRegistration.$touch();
        if (!this.$v.userRegistration.$error) {
          this.isLoading = true;
          document.getElementById('frmx_CreateUserAccount').submit();
        }
      }
    }
});

let appUserLogin = new Vue({
    el: '#loginModal',
    data() {
      return {
        isLoading: false,
        userLogin: {
          login_email: '',
          login_password: ''
        }
      }
    },
    validations: {
      userLogin: {
        login_email: {
          required,
          regxEmailAddress
        },
        login_password: {
          required
        }
      }
    },
    mounted() {
    },
    methods: {
      async userAccountLogin() {
        this.$v.userLogin.$touch();
        if (!this.$v.userLogin.$error) {
          var _currentInstance = this;
          _currentInstance.isLoading = true;
          var url = document.getElementById('frmx_login').action;
          const userAccountLoginProcess = await axios({
              method: 'post',
              url: url,
              data: _currentInstance.userLogin,
              headers: {'Content-Type': 'application/json'}
          })
          .then(function (response) {
            _currentInstance.isLoading = false;
            if (response.data.isSuccess == 'OK') {
              $('.modal').modal('hide');
              _currentInstance.$toastr.s(response.data.successMsg, response.data.successMsgTitle);
              setTimeout(() => {
                window.location.href = "{{ route('quickDashBoard') }}";
              }, 2000);
            }
            if (response.data.isSuccess == 'ERROR') {
              _currentInstance.$toastr.e(response.data.errorMsg, response.data.errorMsgTitle);
            }
          })
          .catch(function (response) {
            _currentInstance.isLoading = false;
            _currentInstance.$toastr.e("Sorry! something went wrong.");
          });
        }
      }
    }
});
</script>
@endpush
