<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $setting = PaymentSetting::first();

        return view('admin.payments', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'bank_name' => ['nullable', 'string', 'max:60'],
            'account_number' => ['nullable', 'string', 'max:40'],
            'account_holder' => ['nullable', 'string', 'max:100'],
            'remove_qris' => ['nullable', 'boolean'],
        ]);

        $setting = PaymentSetting::firstOrCreate([]);

        if ($request->boolean('remove_qris')) {
            $this->deleteImage($setting->qris_image);
            $data['qris_image'] = null;
        } elseif ($request->hasFile('qris_image')) {
            $this->deleteImage($setting->qris_image);
            $data['qris_image'] = $this->storeImage($request);
        } else {
            unset($data['qris_image']);
        }

        $setting->update([
            'qris_image' => $data['qris_image'] ?? $setting->qris_image,
            'bank_name' => $request->filled('bank_name') ? trim($data['bank_name']) : null,
            'account_number' => $request->filled('account_number') ? trim($data['account_number']) : null,
            'account_holder' => $request->filled('account_holder') ? trim($data['account_holder']) : null,
        ]);

        return redirect()
            ->route('admin.payment-settings')
            ->with('status', 'Pengaturan pembayaran berhasil disimpan.');
    }

    private function storeImage(Request $request): string
    {
        $directory = public_path('uploads/payments');
        File::ensureDirectoryExists($directory);

        $file = $request->file('qris_image');
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/payments/'.$filename;
    }

    private function deleteImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        $absolute = public_path($path);

        if (File::exists($absolute)) {
            File::delete($absolute);
        }
    }
}
