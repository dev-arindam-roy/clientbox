@php
    $isHaveProfileImage = false;
    $profileImgSrc = $defaultProfileSrc = asset('public/images/user.png');
    if(Auth::user()->profile_image != '' && Auth::user()->profile_image != null) {
        $profileImgSrc = asset('public/uploads/images/resize/' . Auth::user()->profile_image);
        $isHaveProfileImage = true;
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
            <form name="frmx_cngProImg" id="frmx_cngProImg" action="{{ route('uploadProfileImageChange') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Upload Profile Image: <em>*</em></label>
                            <input type="file" v-model="profile_image" name="profile_image" id="profile_image" accept="image/*" @change="onUploadProImgChange($event)">
                            <div class="text-danger" v-if="!$v.profile_image.required && $v.profile_image.$error">Please add profile image.</div>
                            <div class="text-danger" v-if="!isExcelExtValid">File extension is wrong.</div>
                            <div class="text-danger" v-if="!isExcelSizeValid && isExcelExtValid">File size is grater than 2mb.</div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-info" :disabled="isSubmitDisabled" @click="changeProfileImage">Upload</button>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5 class="text-info">Profile Image</h5>
                        <img :src="profileImgSrc" style="width: 100px; max-height: 100px; border-radius: 6px;">
                        <div v-if="isHaveProfileImage"><a href="javascript:void(0);" class="btn btn-sm btn-danger mt-2" @click="deleteProfileImage">Delete Profile Image</a></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mt-4">
                            <ul>
                                <li><a href="{{ route('myAccount') }}" class="text-info">Back To My Account</a></li>
                            </ul>
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
        isSubmitDisabled: true,
        isExcelExtValid: true,
        isExcelSizeValid: true,
        isHaveProfileImage: "{!! $isHaveProfileImage !!}",
        defaultProfileSrc: "{!! $defaultProfileSrc !!}",
        profileImgSrc: "{!! $profileImgSrc !!}",
        profile_image: ''
      }
    },
    validations: {
        profile_image: {
            required
        }
    },
    mounted() {
        
    },
    computed: {
        
    },
    methods: {
        defaultInitData() {
            this.profile_image = '';
        },
        onUploadProImgChange(event) {
            var _this = this;
            _this.isExcelExtValid = true;
            _this.isExcelSizeValid = true;
            _this.isSubmitDisabled = true;
            const imgExtArr = ['jpg', 'png', 'jpeg', 'gif'];
            const files = event.target.files;
            const fileLength = files.length;
            const fileName = files[0].name;
            const fileSize = files[0].size;
            const fileNameArr = fileName.split('.');
            const fileExt = fileNameArr[fileNameArr.length - 1].toLowerCase();
            
            if(!imgExtArr.includes(fileExt)) {
                _this.isExcelExtValid = false;
                _this.profileImgSrc =_this.defaultProfileSrc;
            }
            
            if(fileSize >= 2000000) {
                _this.isExcelSizeValid = false;
                _this.profileImgSrc =_this.defaultProfileSrc;
            }

            if(_this.isExcelExtValid && _this.isExcelSizeValid) {
                _this.isSubmitDisabled = false;
                var reader = new FileReader();
                reader.onload = function(e) {
                    _this.profileImgSrc = e.target.result;
                }
                reader.readAsDataURL(files[0]);
            }
            //console.log(fileExt);
        },
        async changeProfileImage() {
            this.$v.$touch();
            var _this = this;
            if (!this.$v.$error) {
                if (_this.isExcelExtValid) {
                    if (_this.isExcelSizeValid) {
                        _this.isLoading = true;
                        document.getElementById('frmx_cngProImg').submit();
                    } else {
                        _this.$toastr.e("File size is invalid! only support less than 2 MB");
                    }
                } else {
                    _this.$toastr.e("File extension is wrong! only support .jpg, .jpeg, .png, .gif");
                } 
            }
        },
        deleteProfileImage() {
            swal.fire({
                title: 'Are you sure?',
                text: "You want to delete",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((actionResult) => {
                if (actionResult.value) {
                    Swal.fire({
                        title: 'Please Wait ...',
                        onBeforeOpen () {
                            Swal.showLoading ()
                        },
                        onAfterClose () {
                            Swal.hideLoading()
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    })
                    this.deleteProfileImageActionProcess(); 
                }
            })
        },
        async deleteProfileImageActionProcess() {
            var _this = this;
            var url = "{{ route('deleteProfileImage') }}";
            const delProImg = await axios({
                method: 'post',
                url: url,
                data: {
                    tabName: 'users',
                    fldName: 'profile_image',
                    isAuth: true
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                if (response.data.isSuccess == 'OK') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile image deleted successfully',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    setTimeout(function() { 
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(function (response) {
                Swal.close(); 
                _this.$toastr.e("Sorry! something went wrong.");
            });
        }
    }
});
</script>
@endpush