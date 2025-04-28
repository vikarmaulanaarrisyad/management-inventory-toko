<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return view('admin.setting.index', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $rules = [
            'nama_toko' => 'required',
            'nama' => 'required',
            'nomor' => 'required|string|min:11|max:17',
            'tentang' => 'required',
            'deskripsi' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->except('favicon', 'logo_login', 'logo');

        if ($request->hasFile('logo') && $setting->logo) {
            if (Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }

            $data['logo'] = upload('setting', $request->file('logo'), 'setting');
        }

        if ($request->hasFile('favicon') && $setting->favicon) {
            if (Storage::disk('public')->exists($setting->favicon)) {
                Storage::disk('public')->delete($setting->favicon);
            }

            $data['favicon'] = upload('setting', $request->file('favicon'), 'setting');
        }

        if ($request->hasFile('logo_login') && $setting->logo_login) {
            if (Storage::disk('public')->exists($setting->logo_login)) {
                Storage::disk('public')->delete($setting->logo_login);
            }

            $data['logo_login'] = upload('setting', $request->file('logo_login'), 'setting');
        }

        $setting->update($data);

        return back()->with([
            'message' => 'Pengaturan berhasil diperbarui',
            'success' => true
        ]);
    }
}
