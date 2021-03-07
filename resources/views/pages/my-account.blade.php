@php
    $name = '';
    $email = '';
    $phone = '';
    $whatsapp_no = '';
    $business_name = '';
    $address = '';
    $city = '';
    $country_id = '';

    if(isset($myInfo) && !empty($myInfo)) {
      $name = $myInfo->name;
      $email = $myInfo->email;
      $phone = $myInfo->phone;
      $whatsapp_no = $myInfo->whatsapp_no;
      $business_name = $myInfo->business_name;
    }

    if(isset($userDetails) && !empty($userDetails)) {
      $address = $userDetails->address;
      $city = $userDetails->city;
      $country_id = $userDetails->country_id;
    }
@endphp

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
      <form name="frmx_myprofile" id="frmx_myprofile" method="POST">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Name: <em>*</em></label>
              <input type="text" v-model="name" :disabled='isDisabled' name="name" id="name" class="form-control" placeholder="Name">
              <div class="text-danger" v-if="!$v.name.required && $v.name.$error">Please enter your name.</div>
              <div class="text-danger" v-if="!$v.name.regxAlfaWithSpace && $v.name.required && $v.name.$error">Please enter valid name.</div>
              <div class="text-danger" v-if="!$v.name.minLength && $v.name.regxAlfaWithSpace && $v.name.required && $v.name.$error">Name should be greater than 6 chars.</div>
            </div>
            <div class="form-group">
              <label class="form-label">Phone: </label>
              <input type="text" v-model="phone" :disabled='isDisabled' maxlength="12" name="phone" id="phone" class="form-control" placeholder="Phone">
              <div class="text-danger" v-if="!$v.phone.regxNumeric && $v.phone.$error">Please enter valid mobile number.</div>
              <div class="text-danger" v-if="!$v.phone.minLength && $v.phone.regxNumeric && $v.phone.$error">Minimum 10 digits.</div>
              <div class="text-danger" v-if="!$v.phone.maxLength && $v.phone.minLength && $v.phone.regxNumeric && $v.phone.$error">Maximum 12 digits.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Email: <em>*</em></label>
              <input type="email" v-model="email" :disabled='isDisabled' name="email" id="email" class="form-control" placeholder="Email">
              <div class="text-danger" v-if="!$v.email.required && $v.email.$error">Please enter email address.</div>
              <div class="text-danger" v-if="!$v.email.regxEmailAddress && $v.email.$error">Please enter valid email address.</div>
            </div>
            <div class="form-group">
              <label class="form-label">WhatsApp No: </label>
              <input type="text" v-model="whatsapp_no" :disabled='isDisabled' maxlength="12" name="whatsapp_no" id="whatsapp_no" class="form-control" placeholder="Whatsapp No">
              <div class="text-danger" v-if="!$v.whatsapp_no.regxNumeric && $v.whatsapp_no.$error">Please enter valid mobile number.</div>
              <div class="text-danger" v-if="!$v.whatsapp_no.minLength && $v.whatsapp_no.regxNumeric && $v.whatsapp_no.$error">Minimum 10 digits.</div>
              <div class="text-danger" v-if="!$v.whatsapp_no.maxLength && $v.whatsapp_no.minLength && $v.whatsapp_no.regxNumeric && $v.whatsapp_no.$error">Maximum 12 digits.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group mt-4">
              <ul>
                <li><a href="{{ route('uploadProfileImage') }}" class="text-info">Add/Edit Profile Image</a></li>
                <li v-if="isHaveBusinessName"><a href="{{ route('uploadBusinessLogo') }}" class="text-info">Add/Edit Business Logo</a></li>
                <li><a href="{{ route('changePassword') }}" class="text-info">Change Password</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label class="form-label">Business Name/Brand Name:</label>
              <input type="text" v-model="business_name" :disabled='isDisabled' name="business_name" id="business_name" class="form-control" placeholder="Business Name">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label class="form-label">Address:</label>
              <textarea v-model="address" :disabled='isDisabled' name="address" id="address" class="form-control" placeholder="Address"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Country: <em>*</em></label>
              <select v-model="country_id" :disabled='isDisabled' name="country_id" id="country_id" class="form-control">
                <option v-for="(item, index) in countryList" :value="item.id">@{{item.name}}</option>
              </select>
              <div class="text-danger" v-if="!$v.country_id.required && $v.country_id.$error">Please select country.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">City: </label>
              <input type="text" v-model.trim="city" :disabled='isDisabled' name="city" id="city" class="form-control" placeholder="City">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <button type="button" v-if="!isEditModeActive" class="btn btn-primary" @click="editModeActive">Edit Profile</button>
              <button type="button" v-if="isEditModeActive" class="btn btn-info" @click="updateProfile">Save Changes</button>
              <button type="button" v-if="isEditModeActive" class="btn btn-danger" @click="editModeDeactive">Cancel</button>
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
let appMyAccountProfile = new Vue({
    el: '#app-myAccountProfile',
    data() {
      return {
        isLoading: false,
        isDisabled: true,
        isEditModeActive: false,
        name: "{!! $name !!}",
        email: "{!! $email !!}",
        phone: "{!! $phone !!}",
        business_name: "{!! $business_name !!}",
        whatsapp_no: "{!! $whatsapp_no !!}",
        address: "{!! $address !!}",
        city: "{!! $city !!}",
        country_id: "{!! $country_id !!}",
        countryList: {!! json_encode($countries); !!}
      }
    },
    validations: {
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
      whatsapp_no: {
        regxNumeric,
        minLength: minLength(10),
        maxLength: maxLength(12)
      },
      country_id: {
        required
      }
    },
    mounted() {
        
    },
    computed: {
      isHaveBusinessName: function () {
        return this.business_name != '';
      }
    },
    methods: {
      editModeActive() {
        this.isDisabled = false;
        this.isEditModeActive = true;
      },
      editModeDeactive() {
        this.isDisabled = true;
        this.isEditModeActive = false;
      },
      async updateProfile() {
        var _this = this;
        this.$v.$touch();
        if (!this.$v.$error) {
          _this.isLoading = true;
          _this.updateProfileProcess();
        }
      },
      async updateProfileProcess() {
        var _this = this;
        var url = "{{ route('updateProfile') }}";
        const updateMyProfile = await axios({
            method: 'post',
            url: url,
            data: {
              name: _this.name,
              email: _this.email,
              phone: _this.phone,
              business_name: _this.business_name,
              whatsapp_no: _this.whatsapp_no,
              address: _this.address,
              city: _this.city,
              country_id: _this.country_id
            },
            headers: {'Content-Type': 'application/json'}
        })
        .then(function (response) {
          _this.isLoading = false;
          if (response.data.isSuccess == 'OK') {
            _this.$toastr.s(response.data.successMsg, response.data.successMsgTitle);
            _this.editModeDeactive();
          }
          if (response.data.isSuccess == 'ERROR' && response.data.isEmailExist != 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Sorry!',
              text: 'Please try with another email address.'
            });
          }
        })
        .catch(function (response) {
          _this.isLoading = false;
          _this.$toastr.e("Sorry! something went wrong.");
        });
      }
    }
});
</script>
@endpush