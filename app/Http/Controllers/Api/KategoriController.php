<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        // return view('beban.kategori');
        $kategoris = Kategori::where('id_usaha', Auth::user()->usaha->id)->orWhere('id_usaha', null)->get();

        return response()->json($kategoris);
    }
    public function store(Request $request)
    {
        
        $validate = $request->validate([
            "nama" => "required"
        ]);

        $validate['id_usaha'] = auth()->user()->id_usaha;
        
        Kategori::create($validate);
        return response()->json(["message" => "Berhasil Ditambahkan"]);
    
    }
}