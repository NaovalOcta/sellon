<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
     * Proses klik link verifikasi dari email.
     * Menggunakan EmailVerificationRequest bawaan Laravel yang sudah
     * memvalidasi signature dan memastikan ID cocok dengan user yang login.
     */
    public function verify(EmailVerificationRequest $request)
    {
        // Jika sudah terverifikasi sebelumnya, redirect langsung
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('product.index', ['view_type' => 'home'])
                ->with('toast_success', 'Email Anda sudah terverifikasi sebelumnya!');
        }

        // Tandai email sebagai terverifikasi (isi email_verified_at)
        $request->fulfill();

        return redirect()->route('product.index', ['view_type' => 'home'])
            ->with('toast_success', 'Email berhasil diverifikasi! Selamat datang di SellOn, ' . $request->user()->name . '!');
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
