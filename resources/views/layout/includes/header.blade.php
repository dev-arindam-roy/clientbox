<!DOCTYPE html>
<html lang="en">
<head>
  <title>Client Box</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" type="image/png" href="{{ asset('public/images/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('public/assets/bootstrap/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/vue/vue-loading.css') }}">
  @stack('page_css')
  <style type="text/css">
  .form-label {
    color: #a09e9e;
    font-weight:600;
  }
  .form-group em {
    color: #e20c0c;
  }
  div.text-danger {
    font-size: 14px;
  }
  .account-menu-list {
    background-color: #17a2b8!important;
    border: 1px solid #069ab1!important;;
  }
  .page-item.active .page-link {
    background-color: #17a2b8!important;
    border-color: #17a2b8!important;
    color: #fff!important;
  }
  .page-link {
    color: #17a2b8!important;
  }
  .table-headerbg {
    background-color: #17a2b8; color: #fff; border: 1px solid #17a2b8;
  }
  a:hover {
    cursor: pointer!important;
  }
  </style>
</head>
<body>