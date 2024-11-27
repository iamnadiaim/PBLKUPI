<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\pegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class signupController extends Controller
{

    public function daftarPegawai() {
        Carbon::setLocale('id');

        $daftarPegawai = User::where('id_usaha', auth()->user()->id_usaha)
            ->where('id_role', 2)
            ->select('id', 'nama', 'email', 'no_telepon', 'alamat')
            ->get();

        $aktivitas = Activity::whereIn('causer_id', $daftarPegawai->pluck('id'))
            ->where('causer_type', User::class) // Ensure it's only users causing the activity
            ->get();
        $namaPegawai = User::whereIn('id', $aktivitas->pluck('causer_id'))->get();
   

    return view('pegawai.tampilan', compact('daftarPegawai', 'aktivitas','namaPegawai'));
    }

    public function print() {
        $daftarPegawai = User::where('id_role', 2)->where('id_usaha', auth()->user()->id_usaha)->get();
        // dd($daftarPegawai);
        return view('pegawai.laporan',compact('daftarPegawai'));
    }
    
    public function index() : View  
{
    return view('pegawai.tambah');
}
public function laporan(){
    // dd("tes");
    return view('pegawai.laporan',compact('pegawais'));
}

public function register(Request $request)
{
    $validate = $request->validate([
        "nama" => "required|string|min:2|max:60",
        "no_telepon" => "required|string|min:8|max:13",
        "email" => "required|email|unique:users",
        "password" => "required|string|min:8|max:16",
        "alamat" => "required|min:4|max:100",
    ], [
        // Pesan error khusus untuk setiap atribut
        "nama.min" => "Nama harus memiliki minimal 2 karakter.",
        "nama.max" => "Nama tidak boleh lebih dari 60 karakter.",
        "no_telepon.min" => "Nomor telepon harus memiliki minimal 8 karakter.",
        "no_telepon.max" => "Nomor telepon tidak boleh lebih dari 13 karakter.",
        "email.email" => "Format email tidak valid.",
        "email.unique" => "Email telah terdaftar.",
        "password.min" => "Password harus memiliki minimal 8 karakter.",
        "password.max" => "Password tidak boleh lebih dari 16 karakter.",
        "alamat.min" => "Alamat harus memiliki minimal 4 karakter.",
        "alamat.max" => "Alamat tidak boleh lebih dari 100 karakter.",
       
    ]);
    
    $validate['id_usaha'] = auth()->user()->id_usaha;
    $validate["id_role"] = 2;   
    $validate["nama_usaha"] = auth()->user()->nama_usaha;    

    User::create($validate);
    return redirect()->route("daftarPegawai")->with('successAddSekolah', "Akun Pegawai Berhasil Ditambah");
}

public function logout(Request $request)
{
    Auth::logout();
 
    $request->session()->invalidate();
 
    $request->session()->regenerateToken();
 
    return redirect('/login');
}
public function destroy($id)
{
    try {
        // Cari data pegawai
        $pegawai = User::findOrFail($id);
        
        // Hapus data pegawai
        $pegawai->delete();

        return redirect()->route('daftarPegawai')
            ->with('hapus', 'Data Pegawai berhasil dihapus');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Tangani jika data tidak ditemukan
        return redirect()->route('daftarPegawai')
            ->with('error', 'Data Pegawai tidak ditemukan.');
    } catch (\Exception $e) {
        // Tangani error lain
        return redirect()->route('daftarPegawai')
            ->with('error', 'Terjadi kesalahan saat menghapus data.');
    }
}

}
    