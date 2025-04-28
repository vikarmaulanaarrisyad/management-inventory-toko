@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Admin</li>
@endsection

@section('content')
    @include('admin.dashboard.small_box')
    @include('admin.dashboard.stok')
    @include('admin.dashboard.grafik')
@endsection
