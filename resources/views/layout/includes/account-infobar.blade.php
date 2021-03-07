<div class="row">
    <div class="col-md-9">
        <h3>Hi, <strong>{{ Auth::user()->name }}</strong>, welcome to your account </h3>
        <span><i>Start to manage your business and your clients informations.</i></span>
    </div>
    <div class="col-md-3 text-right">
        @if(isset($pageTitle))<h3 style="color:#17a2b8; font-weight:700;">{{ $pageTitle }}</h3>@endif
    </div>
</div>