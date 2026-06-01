<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  public function register(Request $request) {
    $validation = $request->validate([
      'name'        => ['required', 'string', 'max:255'],
      'nim'         => ['required', 'string', 'size:15'],
      'major'       => ['required'],
      'email'       => ['required', 'string', 'email'],
      'whatsapp_no' => ['required', 'string', 'min:10', 'max:15'],
      'role'        => ['string', 'max:10'],
      'password'    => ['required', 'string', 'min:5', 'confirmed'],
    ]);

    if (User::where('nim', $request->nim)->exists()) {
      return redirect()->route('register')->with('toast_error', "The NIM you've inputted is already registered.");
    } else if (User::where('email', $request->email)->exists()) {
      return redirect()->route('register')->with('toast_error', "The Email you've inputted is already registered.");
    }

    // Enkripsi Password
    $validation['password'] = Hash::make($validation['password']);
    // Simpan user ke database
    $user = User::create($validation);
    // Login user (akses penuh dibatasi middleware 'verified')
    auth()->guard('web')->login($user);
    // Kirim email verifikasi ke inbox kampus
    $user->sendEmailVerificationNotification();

    // Redirect ke halaman instruksi verifikasi email
    return redirect()->route('verification.notice')
      ->with('toast_success', 'Akun berhasil dibuat! Cek inbox email kampus Anda untuk verifikasi.');
  }

  public function login(Request $request) {
    $validation = $request->validate([
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string', 'min:5']
    ]);
    
    // Check if User with the current inputted email exists
    $user = User::where('email', $request->email)->first();
    if(!$user) {
      return redirect()->route('login')->with('toast_error', 'The Email you\'ve inputted is not registered.');
    }

    // Check if the User password is correct
    if (auth()->guard('web')->attempt($validation)) {
      $request->session()->regenerate();

      // Jika email belum terverifikasi, arahkan ke halaman verifikasi
      if (!auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice')
          ->with('toast_error', 'Email Anda belum diverifikasi. Cek inbox email kampus Anda.');
      }

      return redirect()->route('product.index', ['view_type' => 'home'])->with('toast_success', 'Welcome back! ' . $user->name . '!');
    }

    // Default return, if the User password is wrong
    return redirect()->route('login')->with('toast_error', "The password you've inputted is wrong.");
  }

  public function logout(Request $request)
  {
      // 1. Proses logout dari guard
      auth()->guard('web')->logout();
      // 2. Hapus session agar tidak bisa digunakan lagi
      $request->session()->invalidate();
      // 3. Buat ulang token CSRF baru untuk keamanan
      $request->session()->regenerateToken();

      // 4. Redirect ke halaman awal atau login
      return redirect()->route('login');
  }

  public function profile($id = null)
  { 
      if ($id) {
          $user = User::findOrFail($id);
      } else {
          $user = auth()->user();
      }
      
      if (!$user) {
          return redirect()->route('login')->with('toast_error', 'Silakan login terlebih dahulu untuk melihat profil.');
      }

      $products = $user->products()->orderBy('id', 'desc')->paginate(12);

      return view('users.show_profile', compact('user', 'products'));
  }

  public function editProfile($id) {
    $user = User::findOrFail($id);

    // Hanya pemilik profil yang boleh mengedit
    if (auth()->id() !== $user->id) {
      abort(403, 'Anda tidak memiliki akses untuk mengedit profil ini.');
    }

    return view('users.edit_profile', compact('user'));
  }

  public function updateProfile(Request $request, $id) {
    $user = User::findOrFail($id);

    // Hanya pemilik profil yang boleh mengupdate
    if (auth()->id() !== $user->id) {
      abort(403, 'Anda tidak memiliki akses untuk mengubah profil ini.');
    }
    $validation = $request->validate([
      'name'        => ['required', 'string', 'max:255'],
      'nim'         => ['required', 'string', 'size:15'],
      'major'       => ['required'],
      'email'       => ['required', 'string', 'email'],
      'whatsapp_no' => ['required', 'string', 'min:10', 'max:15'],
    ]);

    if (User::where('nim', $request->nim)->exists() && $user->nim !== $request->nim) {
      return redirect()->route('users.edit_profile', $id)->with('toast_error', "The NIM you've inputted is already registered.");
    } else if (User::where('email', $request->email)->exists() && $user->email !== $request->email) {
      return redirect()->route('users.edit_profile', $id)->with('toast_error', "The Email you've inputted is already registered.");
    }

    // Deteksi apakah email berubah
    $emailChanged = $user->email !== $validation['email'];

    if ($emailChanged) {
      // Simpan data profil + reset verifikasi email
      $user->update([
        'name'             => $validation['name'],
        'nim'              => $validation['nim'],
        'major'            => $validation['major'],
        'email'            => $validation['email'],
        'whatsapp_no'      => $validation['whatsapp_no'],
        'email_verified_at' => null, // Reset status verifikasi
      ]);

      // Kirim link verifikasi ke email baru
      $user->sendEmailVerificationNotification();

      // Redirect ke halaman verifikasi dengan pesan peringatan
      return redirect()->route('verification.notice')
        ->with('toast_error', 'Email Anda telah diubah. Silakan verifikasi email baru Anda (' . $validation['email'] . ') untuk melanjutkan.');
    }

    // Email tidak berubah — update profil biasa
    $user->update([
      'name'        => $validation['name'],
      'nim'         => $validation['nim'],
      'major'       => $validation['major'],
      'email'       => $validation['email'],
      'whatsapp_no' => $validation['whatsapp_no'],
    ]);

    return redirect()->route('users.profile', $id)->with('toast_success', 'Profil berhasil diperbarui!');
  }
}
