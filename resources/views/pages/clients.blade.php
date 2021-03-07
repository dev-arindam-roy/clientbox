@extends('layout.layout')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery-datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/sweetalert2/sweetalert2.css') }}">
@endpush

@section('page_content')

<div class="container-fluid mt-3" style="margin-bottom: 50px;" id="app-myClients" v-cloak>
  @include('layout.includes.loading')
  @include('layout.includes.account-infobar')
  <hr/>
  
  <div class="row">
    <div class="col-md-3">
        @include('layout.includes.account-sidemenu')
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-5">
                <a href="{{ route('addNewClient') }}" class="btn btn-sm btn-info"><i class="fas fa-plus"></i> Add New Client</a>
                <a href="javascript:void(0);" id="deleteSetected" class="btn btn-sm btn-danger disabled" @click="bulkDeleteAction"><i class="fas fa-trash-alt"></i> Delete Selected</a> 
            </div>
            <div class="col-md-7 text-right">
                <a href="{{ route('clientExportExcel') }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export Excel</a>
                <a href="javascript:void(0);" id="importExcelBtn" class="btn btn-sm btn-warning"><i class="fas fa-file-upload"></i> Import Excel</a>
                <a href="{{ route('clientImportPdf') }}" class="btn btn-sm btn-primary"><i class="fas fa-file-pdf"></i> Export PDF</a>
            </div>
        </div>
        <form name="frmx_listing" id="frmx_listing" action="{{ route('clientListingBulkAction') }}" method="POST">
            @csrf
            <div class="row mt-2">
                <div class="col-md-12">
                    <table id="clientDT" class="table table-striped table-bordered" style="width:100%">
                        <thead class="table-headerbg">
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Email-Id</th>
                                <th>Phone No</th>
                                <th>Country</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($clients) && count($clients))
                                @php $sl = 1; @endphp
                                @foreach($clients as $v)
                                    <tr id="clientTr_{{ $v->id }}">
                                        <td>
                                            <input type="checkbox" v-model="selectedIds" name="clientIds[]" class="child_ckb" value="{{ $v->id }}">
                                            {{ $sl }}
                                        </td>
                                        <td>{{ $v->first_name . ' ' . $v->last_name }}</td>
                                        <td>{{ $v->email_id }}</td>
                                        <td>{{ $v->phno }}</td>
                                        <td>
                                            @if(isset($v->countryInfo) && !empty($v->countryInfo))
                                                {{ $v->countryInfo->name }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" @click="editClient({{ $v->id }})"><i class="fas fa-edit text-success"></i></a>
                                            <a href="javascript:void(0);" @click="deleteClient({{ $v->id }})"><i class="fas fa-trash-alt text-danger"></i></a>
                                        </td>
                                    </tr>
                                @php $sl++; @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
  </div>

    <!-- The Import Excel Modal -->
    <div class="modal" id="importExcelModal" v-cloak>
        <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Import your clients by an Excel</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <form name="frmImportClient" id="frmImportClientExcel" action="{{ route('clientImportExcel') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="form-label">Upload Excel File: <em>*</em></label><br/>
                            <input type="file" v-model="import_client_excel" name="import_client_excel" @change="onImportClientExcelChange($event)" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            <button type="button" class="btn btn-info" @click="ImportClientExcel">Upload</button>
                            <div class="text-danger" v-if="!$v.import_client_excel.required && $v.import_client_excel.$error">Please select an excel file.</div>
                            <div class="text-danger" v-if="!isExcelExtValid">File extension is wrong.</div>
                            <div class="text-danger" v-if="!isExcelSizeValid">File size is grater than 2mb.</div>
                        </div>
                    </form>
                    <hr/>
                </div>
                <div class="col-md-12 text-center">
                    <span><small><code>Supported extension .xls & .xlsx & size should be less than 2 MB.</code></small></span><br/> 
                    <a href="{{ asset('public/SampleImport/ClientBox_Sample_Client_Import.xlsx') }}" download><i class="fas fa-download"></i> Download Sample Import Excel Format For Client</a>
                </div>
            </div>
            </div>
            
        </div>
        </div>
    </div>
    <!-- End The Import Excel Modal -->
</div>

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery-datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/jquery-datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
const swalTheme = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-info',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})

$(document).ready(function() {
    $('#clientDT').DataTable({
        "info": false,
        "ordering": false,
        "lengthChange": false
    });
    $('.child_ckb').on('change', function () {
        var ckbs = 0;
        $('.child_ckb').each(function () {
            if($(this).is(':checked')) {
                ckbs++; 
            }
        });
        if(ckbs > 0) {
            $('#deleteSetected').removeClass('disabled');
        } else {
            $('#deleteSetected').addClass('disabled');
        }
    });
    $('#importExcelBtn').on('click', function() {
        $('.modal').modal('hide');
        $('#importExcelModal').modal('show');
    });
} );
let appMyClients = new Vue({
    el: '#app-myClients',
    data() {
      return {
        isLoading: false,
        selectedIds: [],
        import_client_excel: '',
        isExcelExtValid: true,
        isExcelSizeValid: true
      }
    },
    mounted() { 
    },
    validations: {
        import_client_excel: {
            required
        }
    },
    methods: {
        async bulkDeleteAction() {
            swal.fire({
                title: 'Are you sure?',
                text: "You want to delete",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete Selected!'
            }).then((actionResult) => {
                if (actionResult.value) {
                    //this.isLoading = true;
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
                    this.bulkDeleteActionProcess(); 
                }
            })
        },
        async bulkDeleteActionProcess() {
            var _currentInstance = this;
            var url = document.getElementById('frmx_listing').action;
            const bulkDeleteProcess = await axios({
                method: 'post',
                url: url,
                data: {
                    ids: _currentInstance.selectedIds,
                    tab: 'clients',
                    act: 'delete'
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                if (response.data.isSuccess == 'OK') {
                    var idsArr = response.data.responseData;
                    if (idsArr.length > 0) {
                        for (var i = 0; i < idsArr.length; i++) {
                            $('#clientTr_' + idsArr[i]).remove();
                        }
                        $('#deleteSetected').addClass('disabled');
                        swalTheme.fire(
                            'Deleted!',
                            'Records deleted successfully',
                            'success'
                        )
                    }
                }
            })
            .catch(function (response) {
                Swal.close(); 
                _currentInstance.$toastr.e("Sorry! something went wrong.");
            });
        },
        async deleteClient(id) {
            if (id != 'undefined' && id != '') {
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
                        this.deleteClientProcess(id); 
                    }
                })
            }
        },
        async deleteClientProcess(clientId) {
            var _currentInstance = this;
            var url = "{{ route('clientDelete') }}";
            const deleteProcess = await axios({
                method: 'post',
                url: url,
                data: {
                    id: clientId,
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                if (response.data.isSuccess == 'OK') {
                    var id = response.data.responseData;
                    if (id != 'undefined' && id != '') {
                        $('#clientTr_' + id).remove();
                        swalTheme.fire(
                            'Deleted!',
                            'Records deleted successfully',
                            'success'
                        )
                    }
                }
                if (response.data.isSuccess == 'ERROR') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sorry!',
                        text: 'System unable to identify the client'
                    });
                }
            })
            .catch(function (response) {
                Swal.close(); 
                _currentInstance.$toastr.e("Sorry! something went wrong.");
            });
        },
        async editClient(id) {
            this.isLoading = true;
            setTimeout(function() { 
                window.location.href = `{{ url('/myAccount/clients') }}/${id}/edit`;
            }, 2000);
        },
        async onImportClientExcelChange(event) {
            var _this = this;
            const files = event.target.files;
            const fileLength = files.length;
            const fileName = files[0].name;
            const fileSize = files[0].size;
            const fileNameArr = fileName.split('.');
            const fileExt = fileNameArr[fileNameArr.length - 1].toLowerCase();
            if(fileExt != 'xls' && fileExt != 'xlsx') {
                _this.isExcelExtValid = false;
            }
            if(fileSize >= 2000000) {
                _this.isExcelSizeValid = false;
            }
            //console.log(fileExt);
        },
        async ImportClientExcel() {
            var _this = this;
            this.$v.$touch();
            if (!this.$v.$error) {
                if (_this.isExcelExtValid) {
                    if (_this.isExcelSizeValid) {
                        _this.isLoading = true;
                        document.getElementById('frmImportClientExcel').submit();
                    } else {
                        _this.$toastr.e("File size is invalid! only support less than 2 MB");
                    }
                } else {
                    _this.$toastr.e("File extension is wrong! only support .xls & .xlsx");
                } 
            }
        }
    }
});
</script>
@endpush