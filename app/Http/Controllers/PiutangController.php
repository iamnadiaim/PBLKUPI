<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use App\Models\piutang;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function index(): View
    {
        $piutangs = piutang::where('id_usaha', auth()->user()->id_usaha)->get();
        return view('piutang.index', compact('piutangs'));
    }

    public function create()
    {
        return view('piutang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|min:2|max:60|unique:piutangs,nama',
            'catatan' => 'required|string|min:5|max:60',
            'jumlah_piutang' => 'required|numeric|min:1000',
            'jumlah_cicilan' => 'required|numeric|min:1|max:36',
            'tanggal_pinjaman' => 'required|date|before_or_equal:today',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:today|after_or_equal:tanggal_pinjaman',
        ], [
            'nama.min' => 'Nama harus memiliki minimal 2 karakter.',
            'nama.max' => 'Nama tidak boleh lebih dari 60 karakter.',
            'nama.unique' => 'Nama Customer telah digunakan. Silakan gunakan nama lain.',
            'catatan.min' => 'Catatan harus memiliki minimal 5 karakter.',
            'catatan.max' => 'Catatan tidak boleh lebih dari 60 karakter.',
            'jumlah_piutang.min' => 'Nominal tidak boleh kurang dari 1000.',
            'jumlah_cicilan.min' => 'Jumlah cicilan minimal 1.',
            'jumlah_cicilan.max' => 'Jumlah cicilan tidak boleh lebih dari 36.',
        ]);

        $validatedData = $request->all();
        $validatedData['id_usaha'] = auth()->user()->id_usaha;
        $validatedData['sisa_piutang'] = $validatedData['jumlah_piutang'];
        $validatedData['status'] = false;

        piutang::create($validatedData);

        return redirect()->route('piutang.index')
                         ->with('success', 'Piutang berhasil ditambahkan.');
    }
}
