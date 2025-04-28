@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Karyawan</li>
@endsection

@section('content')
    @include('karyawan.dashboard.small_box')
    @include('karyawan.dashboard.stok')
@endsection
