<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Tampilkan halaman instruksi verifikasi email.
     * Hanya bisa diakses oleh user yang sudah login.
     */
    public function showNotice(Request $request)
    {
        // Jika email sudah terverifikasi, langsung ke home
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('product.index', ['view_type' => 'home'])
                ->with('toast_success', 'Email Anda sudah terverifikasi!');
        }

        return view('auth.verify');
    }

    /**
     * Proses verifikasi email menggunakan OTP.
     */
    public function verify(Request $request)
    {
        // Jika sudah terverifikasi sebelumnya, redirect langsung
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('product.index', ['view_type' => 'home'])
                ->with('toast_success', 'Email Anda sudah terverifikasi sebelumnya!');
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus terdiri dari 6 digit.',
        ]);

        $user = $request->user();

        // Periksa apakah OTP cocok dan belum kadaluarsa
        if ($user->email_verify_otp !== $request->otp) {
            return back()->with('toast_error', 'Kode OTP yang Anda masukkan salah.');
        }

        if ($user->email_verify_otp_expires_at && now()->greaterThan($user->email_verify_otp_expires_at)) {
            return back()->with('toast_error', 'Kode OTP telah kadaluarsa. Silakan kirim ulang.');
        }

        // Tandai email sebagai terverifikasi (isi email_verified_at)
        $user->markEmailAsVerified();

        // Hapus OTP setelah berhasil diverifikasi
        $user->forceFill([
            'email_verify_otp' => null,
            'email_verify_otp_expires_at' => null,
        ])->save();

        return redirect()->route('product.index', ['view_type' => 'home'])
            ->with('toast_success', 'Email berhasil diverifikasi! Selamat datang di SellOn, ' . $user->name . '!');
    }

    /**
     * Kirim ulang email verifikasi.
     * Di-throttle oleh route (1x per menit).
     */
    public function resend(Request $request)
    {
        // Jika sudah verified, tidak perlu kirim ulang
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('toast_error', 'Email Anda sudah terverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('toast_success', 'Email verifikasi telah dikirim ulang. Cek inbox Anda.');
    }
}
