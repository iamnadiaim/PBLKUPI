<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hutang;
use App\Models\piutang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class laporanhpController extends Controller
{
    public function lihatHutangPiutang(Request $request)
    {
        Carbon::setLocale('id'); // Set locale for Indonesian formatting

        $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha); // Get logged-in user's business name
        $selectedMonth = $request->get('month'); // Get selected month from request
        $hutangs = hutang::where('id_usaha', auth()->user()->id_usaha)->get();
        $piutangs = piutang::where('id_usaha', auth()->user()->id_usaha)->get();
        
        // Validate the selected month to avoid potential issues
        try {
            $selectedDate = Carbon::parse($selectedMonth);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date format.');
        }

        // Calculate total hutang (payables)
        $totalHutang = hutang::where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->sum('jumlah_hutang');

        // Calculate total piutang (receivables)
        $totalPiutang = piutang::where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->sum('jumlah_piutang');

        // Calculate total transactions (optional, assuming one transaction per hutang/piutang)
        $totalTransaksi = hutang::where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->count() +
            piutang::where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->count();

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

        return view('laporanhutang', compact(
            'selectedDate',
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
