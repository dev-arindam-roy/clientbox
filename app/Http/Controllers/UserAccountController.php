<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\BulkActionTrait;
use App\Traits\ImageDeleteTrait;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;
use Maatwebsite\Excel\HeadingRowImport;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Client;
use App\Models\Country;
use Session;
use Image;
use Excel;
use Hash;
use Auth;
use PDF;

class UserAccountController extends Controller
{
    use BulkActionTrait;
    use ImageDeleteTrait;

    public function index(Request $request)
    {
        $DataBag = [];
        return view('app', $DataBag);
    }

    public function createUserAccount(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->business_name = $request->input('business_name');
        $user->password = Hash::make($request->input('password'));
        if ($user->save()) {
            session()->flash('msg', 'Hi, ' . $user->name .', <br/>Your account created successfully.');
            session()->flash('msg_class', 'alert alert-success');
            session()->flash('msg_title', 'Success!');
        }
        return back();
    }

    public function loginUserAccount(Request $request)
    {
        $returnArr = [];
        $requestData = $request->all();
        if (Auth::attempt(['email' => $requestData['login_email'], 'password' => $requestData['login_password']])) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = Auth::user();
            $returnArr['successMsgTitle'] = "Login Success!.";
            $returnArr['successMsg'] = "Hi, " . Auth::user()->name . ",<br/>Welcome to your account.System redirect to your account.<br/>Please wait...";
        } else {
            $returnArr['isSuccess'] = 'ERROR';
            $returnArr['responseData'] = [];
            $returnArr['errorMsgTitle'] = "Login Failed!.";
            $returnArr['errorMsg'] = "Sorry! Email & Password combination is wrong.";
        }
        return response()->json($returnArr);
    }

    public function logoutUserAccount()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('app.index');
    }

    public function myAccount()
    {
        $DataBag = [];
        $DataBag['pageName'] = 'MyAccount';
        $DataBag['pageTitle'] = 'My Account';
        $DataBag['myInfo'] = Auth::user();
        $DataBag['countries'] = Country::select('name', 'id')->get()->toArray();
        $DataBag['userDetails'] = UserDetails::where('user_id', Auth::user()->id)->first();
        return view('pages.my-account', $DataBag);
    }

    public function quickDashBoard()
    {
        $DataBag = [];
        $DataBag['pageName'] = 'QuickDashboard';
        $DataBag['pageTitle'] = 'Quick Dashboard';
        return view('pages.quick-dashboard', $DataBag);
    }

    public function myClients()
    {
        $DataBag = [];
        $DataBag['pageName'] = 'MyClients';
        $DataBag['pageTitle'] = 'Client Management';
        $DataBag['clients'] = Client::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('pages.clients', $DataBag);
    }

    public function addNewClient()
    {
        $DataBag = [];
        $DataBag['pageName'] = 'MyClients';
        $DataBag['pageTitle'] = 'Client Management';
        $DataBag['countries'] = Country::all();
        return view('pages.add-client', $DataBag);
    }

    public function addNewClientSave(Request $request)
    {
        $returnArr = [];
        $isEmailExist = Client::where('email_id', $request->input('email_id'))
            ->where('user_id', Auth::user()->id)
            ->count();
        if ($isEmailExist > 0) {
            return response()->json(['isSuccess' => 'ERROR', 'isEmailExist' => true]);
        }
        $client = new Client();
        $client->user_id = Auth::user()->id;
        $client->first_name = $request->input('first_name');
        $client->last_name = $request->input('last_name');
        $client->email_id = $request->input('email_id');
        $client->phno = $request->input('phno');
        $client->country_id = $request->input('country_id');
        $client->city = $request->input('city');
        $client->note = $request->input('note');
        if ($client->save()) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = [];
            $returnArr['successMsgTitle'] = "Success!.";
            $returnArr['successMsg'] = "New client added successfully, thankyou!";
        }
        return response()->json($returnArr);
    }

    public function clientListingBulkAction(Request $request)
    {
        $returnArr = [];
        $ids = $request->input('ids');
        $tab = $request->input('tab');
        $act = $request->input('act');
        $responseTrait = $this->bulkAction($ids, $tab, $act);
        if ($responseTrait['isExecuted']) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = $ids;
        }
        return response()->json($returnArr);
    }

    public function clientDelete(Request $request)
    {
        $returnArr = [];
        $client = Client::find($request->input('id'));
        if (empty($client)) {
            $returnArr['isSuccess'] = 'ERROR';
            $returnArr['responseData'] = [];
            return response()->json($returnArr);
        }
        $client->delete();
        $returnArr['isSuccess'] = 'OK';
        $returnArr['responseData'] = $request->input('id');
        return response()->json($returnArr);
    }

    public function clientEdit($id)
    {
        $DataBag = [];
        $DataBag['pageName'] = 'MyClients';
        $DataBag['pageTitle'] = 'Client Management';
        $DataBag['countries'] = Country::all();
        $DataBag['clientData'] = Client::findOrFail($id);
        return view('pages.add-client', $DataBag);
    }

    public function clientUpdate(Request $request, $id)
    {
        $returnArr = [];
        $isEmailExist = Client::where('email_id', $request->input('email_id'))
            ->where('user_id', Auth::user()->id)
            ->where('id', '!=', $id)
            ->count();
        if ($isEmailExist > 0) {
            return response()->json(['isSuccess' => 'ERROR', 'isEmailExist' => true]);
        }
        $client = Client::find($id);
        $client->user_id = Auth::user()->id;
        $client->first_name = $request->input('first_name');
        $client->last_name = $request->input('last_name');
        $client->email_id = $request->input('email_id');
        $client->phno = $request->input('phno');
        $client->country_id = $request->input('country_id');
        $client->city = $request->input('city');
        $client->note = $request->input('note');
        if ($client->save()) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = [];
            $returnArr['successMsgTitle'] = "Success!.";
            $returnArr['successMsg'] = "Client updated successfully, thankyou!";
        }
        return response()->json($returnArr);
    }

    public function clientExportExcel()
    {
        $downloadFileName = 'ClientBox_' . date('d-m-Y H:i:s') . '.xls';
        $getData = Client::select(
            'clients.first_name as first_name',
            'clients.last_name as last_name',
            'clients.email_id as email_id',
            'clients.phno as phno',
            'countries.name as country',
            'clients.city as city',
            'clients.note as note',
            'clients.created_at as created_at',
            'clients.updated_at as updated_at'
        )
            ->join('countries', 'countries.id', '=', 'clients.country_id')
            ->where('clients.user_id', Auth::user()->id)
            ->orderBy('clients.id', 'desc')
            ->get()
            ->toArray();

        $export = new ClientsExport($getData);
        return Excel::download($export, $downloadFileName);
    }

    public function clientImportExcel(Request $request)
    {
        $excelHeaders = [
            'first_name',
            'last_name',
            'email_id',
            'mobile_number',
            'country',
            'city',
            'note'
        ];

        $errorMsg = '';
        if ($request->hasFile('import_client_excel')) {
            $uploadFile = $request->file('import_client_excel');
            $extension = strtolower($uploadFile->getClientOriginalExtension());
            $size = $uploadFile->getSize();

            if ($extension != 'xls' && $extension != 'xlsx') {
                $errorMsg = "Uploaded file extension incorrect. It should be csv format.";
            }

            if ($size >= 2000000) {
                $errorMsg = "Uploaded file size is greater than 2mb. It should be less than 2mb.";
            }

            $headings = (new HeadingRowImport)->toArray($uploadFile);
            $headings = $headings[0];
            foreach ($headings as $col) {
                foreach ($col as $v) {
                    if (!in_array(trim($v), $excelHeaders)) {
                        $errorMsg = 'Excel heading format incorrect. Please download sample format and verify.';
                    }
                }
            }
            if ($errorMsg != '') {
                session()->flash('msg', $errorMsg);
                session()->flash('msg_class', 'alert alert-danger');
                session()->flash('msg_title', 'Error!');
                return back();
            }

            if ($extension == 'xlsx') {
                Excel::import(new ClientsImport, $uploadFile, null, \Maatwebsite\Excel\Excel::XLSX);
            }
            if ($extension == 'xls') {
                Excel::import(new ClientsImport, $uploadFile, null, \Maatwebsite\Excel\Excel::XLS);
            }
            session()->flash('msg', 'Clients imported successfully to your account.');
            session()->flash('msg_class', 'alert alert-success');
            session()->flash('msg_title', 'Success!');
            return redirect()->back();
        }

        return back();
    }

    public function clientImportPdf()
    {
        $viewData = [];
        $clients = Client::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        
        $viewData['clients'] = $clients;
        $viewData['userInfo'] = Auth::user();
        $pdf = PDF::loadView('pages/clients-importpdf', $viewData);
        $downloadFileName = 'ClientBox_' . date('d-m-Y H:i:s') . '.pdf';
        return $pdf->download($downloadFileName);
    }

    public function updateProfile(Request $request)
    {
        $returnArr = [];
        $id = Auth::user()->id;
        $isEmailExist = User::where('email', $request->input('email'))
            ->where('id', '!=', $id)
            ->count();
        if ($isEmailExist > 0) {
            return response()->json(['isSuccess' => 'ERROR', 'isEmailExist' => true]);
        }

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->business_name = $request->input('business_name');
        $user->whatsapp_no = $request->input('whatsapp_no');
        $userDetails = UserDetails::where('user_id', Auth::user()->id)->first();
        if (empty($userDetails)) {
            $userDetailsObj = new UserDetails;
            $userDetailsObj->user_id = Auth::user()->id;
        } else {
            $userDetailsObj = UserDetails::find($userDetails->id);
        }
        $userDetailsObj->address = $request->input('address');
        $userDetailsObj->city = $request->input('city');
        $userDetailsObj->country_id = $request->input('country_id');
        if ($user->save() && $userDetailsObj->save()) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = [];
            $returnArr['successMsgTitle'] = "Success!.";
            $returnArr['successMsg'] = "Your profile updated successfully, thankyou!";
        }
        return response()->json($returnArr);
    }

    public function changePassword()
    {
        $DataBag = [];
        $DataBag['pageName'] = 'MyAccount';
        $DataBag['pageTitle'] = 'Change Password';
        $DataBag['myInfo'] = Auth::user();
        return view('pages.change-password', $DataBag);
    }

    public function changePasswordChange(Request $request)
    {
        $returnArr = [];
        $current_password = $request->input('current_password');
        $new_password = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');
        $user = User::find(Auth::user()->id);
        if (!Hash::check($current_password, $user->password)) {
            return response()->json(['isSuccess' => 'ERROR', 'isCurrentPasswordNotMatch' => true]);
        }
        if ($new_password != $confirm_password) {
            return response()->json(['isSuccess' => 'ERROR', 'isConfirmPasswordNotMatch' => true]);
        }
        $user->password = Hash::make($request->input('new_password'));
        if ($user->save()) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = [];
            $returnArr['successMsgTitle'] = "Success!.";
            $returnArr['successMsg'] = "Your account password changed successfully, thankyou!";
        }
        return response()->json($returnArr);
    }

    public function uploadProfileImage()
    {
        $DataBag = [];
        $DataBag['pageName'] = 'MyAccount';
        $DataBag['pageTitle'] = 'Profile Image';
        $DataBag['myInfo'] = Auth::user();
        return view('pages.profile-image', $DataBag);
    }

    public function uploadProfileImageChange(Request $request)
    {
        $errorMsg = '';
        $imgExtArr = ['jpg', 'jpeg', 'png', 'gif'];
        if ($request->hasFile('profile_image')) {
            $uploadFile = $request->file('profile_image');
            $extension = strtolower($uploadFile->getClientOriginalExtension());
            $size = $uploadFile->getSize();

            if (!in_array($extension, $imgExtArr)) {
                $errorMsg = "Uploaded image extension incorrect.";
            }

            if ($size >= 2000000) {
                $errorMsg = "Uploaded file size is greater than 2mb. It should be less than 2mb.";
            }

            $img = Image::make($uploadFile->path());
            $imgNewName = md5(microtime(true) . Auth::user()->id) . '.' . $extension;
            $destinationPath = public_path('/uploads/images');
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/resize/' . $imgNewName);
    
            $uploadFile->move($destinationPath . '/', $imgNewName);

            if ($errorMsg != '') {
                session()->flash('msg', $errorMsg);
                session()->flash('msg_class', 'alert alert-danger');
                session()->flash('msg_title', 'Error!');
                return back();
            }

            $user = User::findOrFail(Auth::user()->id);
            $user->profile_image = $imgNewName;
            $user->save();

            session()->flash('msg', 'Profile image uploaded successfully.');
            session()->flash('msg_class', 'alert alert-success');
            session()->flash('msg_title', 'Success!');
            return redirect()->back();
        }

        return back();
    }

    public function deleteProfileImage(Request $request)
    {
        $returnArr = [];
        $tab = $request->input('tabName');
        $fld = $request->input('fldName');
        
        $isDel = $this->deleteImage($tab, $fld, 0, true);
        if (!empty($isDel) && $isDel['isExecuted']) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = [];
            $returnArr['successMsgTitle'] = "Success!.";
            $returnArr['successMsg'] = "Your profile image deleted successfully, thankyou!";
        }
        return response()->json($returnArr);
    }

    public function uploadBusinessLogo()
    {
        $DataBag = [];
        $DataBag['pageName'] = 'MyAccount';
        $DataBag['pageTitle'] = 'Business Logo';
        $DataBag['myInfo'] = Auth::user();
        return view('pages.business-logo', $DataBag);
    }

    public function uploadBusinessLogoChange(Request $request)
    {
        $errorMsg = '';
        $imgExtArr = ['jpg', 'jpeg', 'png', 'gif'];
        if ($request->hasFile('business_image')) {
            $uploadFile = $request->file('business_image');
            $extension = strtolower($uploadFile->getClientOriginalExtension());
            $size = $uploadFile->getSize();

            if (!in_array($extension, $imgExtArr)) {
                $errorMsg = "Uploaded image extension incorrect.";
            }

            if ($size >= 2000000) {
                $errorMsg = "Uploaded file size is greater than 2mb. It should be less than 2mb.";
            }

            $img = Image::make($uploadFile->path());
            $imgNewName = md5(microtime(true) . Auth::user()->id) . '.' . $extension;
            $destinationPath = public_path('/uploads/images');
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/resize/' . $imgNewName);
    
            $uploadFile->move($destinationPath . '/', $imgNewName);

            if ($errorMsg != '') {
                session()->flash('msg', $errorMsg);
                session()->flash('msg_class', 'alert alert-danger');
                session()->flash('msg_title', 'Error!');
                return back();
            }

            $user = User::findOrFail(Auth::user()->id);
            $user->business_image = $imgNewName;
            $user->save();

            session()->flash('msg', 'Business logo uploaded successfully.');
            session()->flash('msg_class', 'alert alert-success');
            session()->flash('msg_title', 'Success!');
            return redirect()->back();
        }

        return back();
    }

    public function deleteBusinessLogo(Request $request)
    {
        $returnArr = [];
        $tab = $request->input('tabName');
        $fld = $request->input('fldName');
        
        $isDel = $this->deleteImage($tab, $fld, 0, true);
        if (!empty($isDel) && $isDel['isExecuted']) {
            $returnArr['isSuccess'] = 'OK';
            $returnArr['responseData'] = [];
            $returnArr['successMsgTitle'] = "Success!.";
            $returnArr['successMsg'] = "Your business logo deleted successfully, thankyou!";
        }
        return response()->json($returnArr);
    }
}
