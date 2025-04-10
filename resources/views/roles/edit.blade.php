@extends('layouts.master')

@section('css')
<!-- Internal Font Awesome -->
<link href="{{URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!-- Internal treeview -->
<link href="{{URL::asset('assets/plugins/treeview/treeview-rtl.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
تعديل الصلاحيات - مورا سوفت للادارة القانونية
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الصلاحيات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل الصلاحيات</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('roles.update', $role->id) }}">
    @csrf
    @method('PATCH')
    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        <div class="form-group">
                            <p>اسم الصلاحية :</p>
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $role->name }}">
                        </div>
                    </div>
                    <div class="row">
                        <!-- col -->
                        <div class="col-lg-4">
                            <ul id="treeview1">
                                <li><a href="#">الصلاحيات</a>
                                    <ul>
                                        <li>
                                            <!-- حقل مخفي لضمان إرسال البيانات حتى لو لم تُحدد -->
                                            <input type="hidden" name="permissions" value="">
                                            @foreach($permission as $value)
                                            <label>
                                                <input type="checkbox" name="permissions[]" value="{{ $value->id }}" class="name" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $value->name }}
                                            </label>
                                            <br />
                                            @endforeach
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-main-primary">تحديث</button>
                        </div>
                        <!-- /col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
</form>
<!-- Container closed -->
@endsection

@section('js')
<!-- Internal Treeview js -->
<script src="{{URL::asset('assets/plugins/treeview/treeview.js')}}"></script>
@endsection
