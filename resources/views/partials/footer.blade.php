@php
    $setting = \App\Models\Setting::first();
@endphp

<footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="/"></a>.</strong>
    {{ $setting->nama_toko }}
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.1
    </div>
</footer>
