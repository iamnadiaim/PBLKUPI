<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hutang;
use App\Models\piutang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class laporanhpController extends Controller
{
    public function lihatHutangPiutang(Request $request)
    {
        Carbon::setLocale('id');
        $selectedMonth = $request->input('month', Carbon::now()->format('F')); // Default to current month
        
        try {
            $selectedDate = Carbon::parse($selectedMonth);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date format.');
        }

        $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha);

        // Fetch hutang and piutang for the current user's business
        $idUsaha = Auth::user()->usaha->id;

        $hutangs = hutang::where('id_usaha', $idUsaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->get();

        $piutangs = piutang::where('id_usaha', $idUsaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->get();
        // $tes = piutang::where('id_usaha', $idUsaha)->get();
        // Calculate total hutang (payables)
        $totalHutang = $hutangs->sum('jumlah_hutang');

        // Calculate total piutang (receivables)
        $totalPiutang = $piutangs->sum('jumlah_piutang');

        // Calculate total transactions
        $totalTransaksi = $hutangs->count() + $piutangs->count();

        // Array of months in Indonesian and English for formatting
        $bulan = [
            ['indo' => "Januari", 'inggris' => "January"],
            ['indo' => "Februari", 'inggris' => "February"],
            ['indo' => "Maret", 'inggris' => "March"],
            ['indo' => "April", 'inggris' => "April"],
            ['indo' => "Mei", 'inggris' => "May"],
            ['indo' => "Juni", 'inggris' => "June"],
            ['indo' => "Juli", 'inggris' => "July"],
            ['indo' => "Agustus", 'inggris' => "August"],
            ['indo' => "September", 'inggris' => "September"],
            ['indo' => "Oktober", 'inggris' => "October"],
            ['indo' => "November", 'inggris' => "November"],
            ['indo' => "Desember", 'inggris' => "December"],
        ];

        return view('laporanhutang', compact(
            'selectedDate',
            'namaUsaha',
            'hutangs',
            'piutangs',
            'totalHutang',
            'totalPiutang',
            'totalTransaksi',
            'bulan',
          
        ));
    }

    public function print(Request $request)
    {
        Carbon::setLocale('id');
        $selectedMonth = $request->input('month', Carbon::now()->format('F')); // Default to current month

        try {
            $selectedDate = Carbon::parse($selectedMonth);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date format.');
        }

        $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha);

        // Fetch hutang and piutang for the current user's business
        $idUsaha = Auth::user()->usaha->id;

        $hutangs = hutang::where('id_usaha', $idUsaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->get();

        $piutangs = piutang::where('id_usaha', $idUsaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->get();
        // dd($hutangs);
        $assumasi = piutang::where('id_usaha', $idUsaha)->get();
        // Calculate total hutang (payables)
        $totalHutang = $hutangs->sum('jumlah_hutang');

        // Calculate total piutang (receivables)
        $totalPiutang = $piutangs->sum('jumlah_piutang');

        // Calculate total transactions (optional, assuming one transaction per hutang/piutang)
        $totalTransaksi = $hutangs->count() + $piutangs->count();

        $bulan = [ // Array of months in Indonesian and English for formatting
            [
                'indo' => "Januari",
                'inggris' => "January"
            ],
            [
                'indo' => "Februari",
                'inggris' => "February"
            ],
            [
                'indo' => "Maret",
                'inggris' => "March"
            ],
            [
                'indo' => "April",
                'inggris' => "April"
            ],
            [
                'indo' => "Mei",
                'inggris' => "May"
            ],
            [
                'indo' => "Juni",
                'inggris' => "June"
            ],
            [
                'indo' => "Juli",
                'inggris' => "July"
            ],
            [
                'indo' => "Agustus",
                'inggris' => "August"
            ],
            [
                'indo' => "September",
                'inggris' => "September"
            ],
            [
                'indo' => "Oktober",
                'inggris' => "October"
            ],
            [
                'indo' => "November",
                'inggris' => "November"
            ],
            [
                'indo' => "Desember",
                'inggris' => "December"
            ],
        ];

        return view('cetakhutang', compact(
            'selectedDate',
            'selectedMonth',
            'namaUsaha',
            'hutangs',
            'piutangs',
            'totalHutang',
            'totalPiutang',
            'totalTransaksi',
            'bulan'
        ));
    }
}
