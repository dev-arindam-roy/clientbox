@php
    $isHaveBusinessImage = false;
    $businessImgSrc = $defaultBusinessSrc = asset('public/images/image.png');
    if(Auth::user()->business_image != '' && Auth::user()->business_image != null) {
        $businessImgSrc = asset('public/uploads/images/resize/' . Auth::user()->business_image);
        $isHaveBusinessImage = true;
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
            <form name="frmx_cngBrandImg" id="frmx_cngBrandImg" action="{{ route('uploadBusinessLogoChange') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Upload Business/Brand Logo: <em>*</em></label>
                            <input type="file" v-model="business_image" name="business_image" id="business_image" accept="image/*" @change="onUploadBusinessImgChange($event)">
                            <div class="text-danger" v-if="!$v.business_image.required && $v.business_image.$error">Please add business logo.</div>
                            <div class="text-danger" v-if="!isExcelExtValid">File extension is wrong.</div>
                            <div class="text-danger" v-if="!isExcelSizeValid && isExcelExtValid">File size is grater than 2mb.</div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-info" :disabled="isSubmitDisabled" @click="changeBusinessImage">Upload</button>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5 class="text-info">Business Logo</h5>
                        <img :src="businessImgSrc" style="width: 100px; max-height: 100px; border-radius: 6px;">
                        <div v-if="isHaveBusinessImage"><a href="javascript:void(0);" class="btn btn-sm btn-danger mt-2" @click="deleteBusinessImage">Delete Business Logo</a></div>
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
        isHaveBusinessImage: "{!! $isHaveBusinessImage !!}",
        defaultBusinessSrc: "{!! $defaultBusinessSrc !!}",
        businessImgSrc: "{!! $businessImgSrc !!}",
        business_image: ''
      }
    },
    validations: {
        business_image: {
            required
        }
    },
    mounted() {
        
    },
    computed: {
        
    },
    methods: {
        defaultInitData() {
            this.business_image = '';
        },
        onUploadBusinessImgChange(event) {
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
                _this.businessImgSrc =_this.defaultBusinessSrc;
            }
            
            if(fileSize >= 2000000) {
                _this.isExcelSizeValid = false;
                _this.businessImgSrc =_this.defaultBusinessSrc;
            }

            if(_this.isExcelExtValid && _this.isExcelSizeValid) {
                _this.isSubmitDisabled = false;
                var reader = new FileReader();
                reader.onload = function(e) {
                    _this.businessImgSrc = e.target.result;
                }
                reader.readAsDataURL(files[0]);
            }
            //console.log(fileExt);
        },
        async changeBusinessImage() {
            this.$v.$touch();
            var _this = this;
            if (!this.$v.$error) {
                if (_this.isExcelExtValid) {
                    if (_this.isExcelSizeValid) {
                        _this.isLoading = true;
                        document.getElementById('frmx_cngBrandImg').submit();
                    } else {
                        _this.$toastr.e("File size is invalid! only support less than 2 MB");
                    }
                } else {
                    _this.$toastr.e("File extension is wrong! only support .jpg, .jpeg, .png, .gif");
                } 
            }
        },
        deleteBusinessImage() {
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
                    this.deleteBusinessImageActionProcess(); 
                }
            })
        },
        async deleteBusinessImageActionProcess() {
            var _this = this;
            var url = "{{ route('deleteBusinessLogo') }}";
            const delBusiImg = await axios({
                method: 'post',
                url: url,
                data: {
                    tabName: 'users',
                    fldName: 'business_image',
                    isAuth: true
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                if (response.data.isSuccess == 'OK') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Business logo deleted successfully',
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