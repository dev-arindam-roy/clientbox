@php
    $id = 0;
    $fname = '';
    $lname = '';
    $email = '';
    $phno = '';
    $countryId = 1;
    $city = '';
    $note = '';

    if(isset($clientData) && !empty($clientData)) {
        $id = $clientData->id;
        $fname = $clientData->first_name;
        $lname = $clientData->last_name;
        $email = $clientData->email_id;
        $phno = $clientData->phno;
        $countryId = $clientData->country_id;
        $city = $clientData->city;
        $note = $clientData->note;
    }
@endphp

@extends('layout.layout')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/sweetalert2/sweetalert2.css') }}">
@endpush

@section('page_content')

<div class="container-fluid mt-3" style="margin-bottom: 50px;" id="app-AddClient" v-cloak>
  @include('layout.includes.loading')
  @include('layout.includes.account-infobar')
  <hr/>

  <div class="row">
    <div class="col-md-3">
        @include('layout.includes.account-sidemenu')
    </div>
    <div class="col-md-9">
        <form name="frmx_AddClient" id="frmx_AddClient" @if(isset($clientData)) @submit.prevent="onSubmitUpdateClient" @else @submit.prevent="onSubmitAddClient" @endif method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <label class="form-label">First Name: <em>*</em></label>
                        <input type="text" v-model.trim="first_name" name="first_name" id="first_name" class="form-control" placeholder="First Name">
                        <div class="text-danger" v-if="!$v.first_name.required && $v.first_name.$error">Please enter first name.</div>
                        <div class="text-danger" v-if="!$v.first_name.regxAlfaWithSpace && $v.first_name.required && $v.first_name.$error">Please enter valid first name.</div>
                        <div class="text-danger" v-if="!$v.first_name.minLength && $v.first_name.regxAlfaWithSpace && $v.first_name.required && $v.first_name.$error">Minimum chars 3.</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contact Email: <em>*</em></label>
                        <input type="email" v-model.trim="email_id" name="email_id" id="email_id" class="form-control" placeholder="Email Address">
                        <div class="text-danger" v-if="!$v.email_id.required && $v.email_id.$error">Please enter email-id.</div>
                        <div class="text-danger" v-if="!$v.email_id.regxEmailAddress && $v.email_id.$error">Please enter valid email-id.</div>
                    </div>
                    <div class="form-group">
                    
                        <label class="form-label">Country: <em>*</em></label>
                        <select v-model.trim="country_id" name="country_id" id="country_id" class="form-control">
                            @if(isset($countries))
                                @foreach($countries as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="text-danger" v-if="!$v.country_id.required && $v.country_id.$error">Please select country.</div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <label class="form-label">Last Name: <em>*</em></label>
                        <input type="text" v-model.trim="last_name" name="last_name" id="last_name" class="form-control" placeholder="Last Name">
                        <div class="text-danger" v-if="!$v.last_name.required && $v.last_name.$error">Please enter last name.</div>
                        <div class="text-danger" v-if="!$v.last_name.regxAlfaWithSpace && $v.last_name.required && $v.last_name.$error">Please enter valid last name.</div>
                        <div class="text-danger" v-if="!$v.last_name.minLength && $v.last_name.regxAlfaWithSpace && $v.last_name.required && $v.last_name.$error">Minimum chars 2.</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Mobile Number: <em>*</em></label>
                        <input type="text" v-model.trim="phno" name="phno" id="phno" maxlength="12" class="form-control" placeholder="Mobile Number">
                        <div class="text-danger" v-if="!$v.phno.required && $v.phno.$error">Please enter phone number.</div>
                        <div class="text-danger" v-if="!$v.phno.regxNumeric && $v.phno.required && $v.phno.$error">Please enter valid number.</div>
                        <div class="text-danger" v-if="!$v.phno.minLength && $v.phno.regxNumeric && $v.phno.required && $v.phno.$error">Minimum 10 digits.</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">City: </label>
                        <input type="text" v-model.trim="city" name="city" id="city" class="form-control" placeholder="City">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">Note: </label>
                        <textarea v-model.trim="note" name="note" id="note" class="form-control" placeholder="Any Note.."></textarea>
                    </div>
                    <div class="form-group">
                        @if(isset($clientData))
                            <button type="submit" class="btn btn-info">Save Changes</button>
                            <a href="javascript:void(0);" class="btn btn-danger" @click="allClients">Cancel</a>
                        @else
                            <button type="submit" class="btn btn-info">Add Client</button>
                        @endif
                        <input type="hidden" v-model.trim="id">
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
let appAddClient = new Vue({
    el: '#app-AddClient',
    data() {
      return {
        isLoading: false,
        id: "{!! $id !!}",
        first_name: "{!! $fname !!}",
        last_name: "{!! $lname !!}",
        email_id: "{!! $email !!}",
        phno: "{!! $phno !!}",
        country_id: "{!! $countryId !!}",
        city: "{!! $city !!}",
        note: "{!! $note !!}"
      }
    },
    validations: {
        first_name: {
            required,
            regxAlfaWithSpace,
            minLength: minLength(3)
        },
        last_name: {
            required,
            regxAlfaWithSpace,
            minLength: minLength(2)
        },
        email_id: {
            required,
            regxEmailAddress
        },
        phno: {
            required,
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
    methods: {
        defaultInitClientData() {
            this.first_name = '';
            this.last_name = '';
            this.email_id = '';
            this.phno = '';
            this.country_id = 1;
            this.city = '';
            this.note = '';
        },
        async onSubmitAddClient() {
            this.$v.$touch();
            if (!this.$v.$error) {
                var _currentInstance = this;
                _currentInstance.isLoading = true;
                var url = "{{ route('addNewClientSave') }}";
                const addClientProcess = await axios({
                    method: 'post',
                    url: url,
                    data: {
                        first_name: _currentInstance.first_name,
                        last_name: _currentInstance.last_name,
                        email_id: _currentInstance.email_id,
                        phno: _currentInstance.phno,
                        country_id: _currentInstance.country_id,
                        city: _currentInstance.city,
                        note: _currentInstance.note
                    },
                    headers: {'Content-Type': 'application/json'}
                })
                .then(function (response) {
                    _currentInstance.isLoading = false;
                    if (response.data.isSuccess == 'OK') {
                        _currentInstance.$toastr.s(response.data.successMsg, response.data.successMsgTitle);
                        _currentInstance.$v.$reset();
                        _currentInstance.defaultInitClientData();
                    }
                    if (response.data.isSuccess == 'ERROR' && response.data.isEmailExist != 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sorry!',
                            text: 'This email address already exist with your account'
                        });
                    }
                })
                .catch(function (response) {
                    _currentInstance.isLoading = false;
                    _currentInstance.$toastr.e("Sorry! something went wrong.");
                });
            }
        },
        async onSubmitUpdateClient() {
            this.$v.$touch();
            if (!this.$v.$error) {
                var _currentInstance = this;
                _currentInstance.isLoading = true;
                var url = `{{ url('/myAccount/clients') }}/${_currentInstance.id}/edit`;
                const updateClientProcess = await axios({
                    method: 'post',
                    url: url,
                    data: {
                        first_name: _currentInstance.first_name,
                        last_name: _currentInstance.last_name,
                        email_id: _currentInstance.email_id,
                        phno: _currentInstance.phno,
                        country_id: _currentInstance.country_id,
                        city: _currentInstance.city,
                        note: _currentInstance.note
                    },
                    headers: {'Content-Type': 'application/json'}
                })
                .then(function (response) {
                    _currentInstance.isLoading = false;
                    if (response.data.isSuccess == 'OK') {
                        _currentInstance.$toastr.s(response.data.successMsg, response.data.successMsgTitle);
                    }
                    if (response.data.isSuccess == 'ERROR' && response.data.isEmailExist != 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sorry!',
                            text: 'This email address already exist with your account'
                        });
                    }
                })
                .catch(function (response) {
                    _currentInstance.isLoading = false;
                    _currentInstance.$toastr.e("Sorry! something went wrong.");
                });
            }
        },
        async allClients() {
            this.isLoading = true;
            setTimeout(function() { 
                window.location.href = "{{ route('myClients') }}";
            }, 2000);
        }
    }
});
</script>
@endpush