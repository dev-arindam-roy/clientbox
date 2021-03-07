@extends('layout.layout')


@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/sweetalert2/sweetalert2.css') }}">
@endpush


@section('page_content')

<div class="container-fluid mt-3" style="margin-bottom: 50px;" id="app-myAccountProfile" v-cloak>
  @include('layout.includes.loading')
  @include('layout.includes.account-infobar')
  <hr/>

  <div class="row">
    <div class="col-md-3">
        @include('layout.includes.account-sidemenu')
    </div>
    <div class="col-md-9">
      <form name="frmx_cngPwd" id="frmx_cngPwd" method="POST">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Current Password: <em>*</em></label>
              <input :type="passwordFieldType" v-model="current_password" name="current_password" id="current_password" class="form-control" placeholder="Current Password">
              <div class="text-danger" v-if="!$v.current_password.required && $v.current_password.$error">Please enter current password.</div>
            </div>
            <div class="form-group">
              <label class="form-label">New Password: <em>*</em></label>
              <input :type="passwordFieldType" v-model="new_password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
              <div class="text-danger" v-if="!$v.new_password.required && $v.new_password.$error">Please enter new password.</div>
              <div class="text-danger" v-if="!$v.new_password.regxPassword && $v.new_password.$error">Use number, text and # for password.</div>
            </div>
            <div class="form-group">
              <label class="form-label">Confirm Password: <em>*</em></label>
              <input :type="passwordFieldType" v-model="confirm_password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
              <div class="text-danger" v-if="!$v.confirm_password.required && $v.confirm_password.$error">Please enter confirm password.</div>
              <div class="text-danger" v-if="!$v.confirm_password.sameAs && $v.confirm_password.required && $v.confirm_password.$error">Confirm password not match.</div>
            </div>
          </div>
          <div class="col-md-4">
            
          </div>
          <div class="col-md-4">
            <div class="form-group mt-4">
                <ul>
                    <li><a href="{{ route('myAccount') }}" class="text-info">Back To My Account</a></li>
                </ul>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <button type="button" class="btn btn-info" @click="changePasswordProcess">Change Password</button>
              <a href="javascript:void(0)" class="btn btn-warning" @click="switchPasswordVisibility" v-bind:class="{ disabled: !haveAnyPassword }"><i class="fas " v-bind:class="{ 'fa-eye': !isPasswordVisable , 'fa-eye-slash': isPasswordVisable }"></i></a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('page_js')
<script src="{{ asset('public/assets/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
let appCngPwd = new Vue({
    el: '#app-myAccountProfile',
    data() {
      return {
        isLoading: false,
        passwordFieldType: 'password',
        isPasswordVisable: false,
        current_password: '',
        new_password: '',
        confirm_password: ''
      }
    },
    validations: {
        current_password: {
            required
        },
        new_password: {
          required,
          regxPassword
        },
        confirm_password: {
          required,
          sameAs: sameAs('new_password')
        }
    },
    mounted() {
        
    },
    computed: {
        haveAnyPassword: function () {
            return this.current_password != '' || this.new_password != '' || this.confirm_password != '';
        }
    },
    methods: {
      defaultInitData() {
        this.passwordFieldType = 'password',
        this.isPasswordVisable = false,
        this.current_password = '',
        this.new_password = '',
        this.confirm_password = ''
      },
      switchPasswordVisibility() {
          this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password'
          if (this.passwordFieldType == 'password') {
              this.isPasswordVisable = false
          } else {
              this.isPasswordVisable = true
          }
      },
      async changePasswordProcess() {
          this.$v.$touch();
          var _this = this;
          if (!this.$v.$error) {
            _this.isLoading = true;
            var url = "{{ route('changePasswordChange') }}";
            const chgPwdProcess = await axios({
                method: 'post',
                url: url,
                data: {
                  current_password: _this.current_password,
                  new_password: _this.new_password,
                  confirm_password: _this.confirm_password
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
              _this.isLoading = false;
              _this.$v.$reset();
              _this.defaultInitData();
              if (response.data.isSuccess == 'OK') {
                _this.$toastr.s(response.data.successMsg, response.data.successMsgTitle);
              }
              if (response.data.isSuccess == 'ERROR' && response.data.isCurrentPasswordNotMatch) {
                Swal.fire({
                    icon: 'error',
                    title: 'Sorry!',
                    text: 'Current account password incorrect'
                });
              }
              if (response.data.isSuccess == 'ERROR' && response.data.isConfirmPasswordNotMatch) {
                Swal.fire({
                    icon: 'error',
                    title: 'Sorry!',
                    text: 'Confirm password not match with new password'
                });
              }
            })
            .catch(function (response) {
              _this.isLoading = false;
              _this.$toastr.e("Sorry! something went wrong.");
            });
          }
      }
    }
});
</script>
@endpush