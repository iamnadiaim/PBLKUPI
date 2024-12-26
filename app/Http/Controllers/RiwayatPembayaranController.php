<?php

namespace App\Http\Controllers;

use App\Models\BayarPiutang;
use App\Models\BayarHutang;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class RiwayatPembayaranController extends Controller
{
    public function index(Request $request): View
    {
        Carbon::setLocale('id');
        $selectedMonth = $request->input('month', Carbon::now()->format('F')); // Default to current month
        $selectedDate = Carbon::parse($selectedMonth);

        try {
            $bayarhutang = BayarHutang::where('id_usaha', auth()->user()->id_usaha)
                ->whereYear('tanggal_pembayaran', $selectedDate->year)
                ->whereMonth('tanggal_pembayaran', $selectedDate->month)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->get();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to retrieve payment history.');
        }

        $bulan = [
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

        return view('pembayaran.riwayathutang', compact('bayarhutang', 'bulan','selectedDate'));
    }

    public function indexBayarPiutang(Request $request): View
    {
        Carbon::setLocale('id');
        $selectedMonth = $request->input('month', Carbon::now()->format('F')); // Default to current month
        $selectedDate = Carbon::parse($selectedMonth);

        try {
            $bayarpiutang = BayarPiutang::where('id_usaha', auth()->user()->id_usaha)
                ->whereYear('tanggal_pembayaran', $selectedDate->year)
                ->whereMonth('tanggal_pembayaran', $selectedDate->month)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->get();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to retrieve payment history.');
        }

        $bulan = [
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

        return view('pembayaran.riwayatpiutang', compact('bayarpiutang', 'bulan','selectedDate'));
    }
}
