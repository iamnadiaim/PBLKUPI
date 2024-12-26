<?php
namespace App\Http\Controllers;

use App\Models\BayarPiutang;
use Illuminate\Http\Request;
use App\Models\piutang;

class BayarPiutangController extends Controller
{
    public function index(Request $request)
    {
        $piutang = piutang::findOrFail($request->id);
        $bayarpiutang = BayarPiutang::all();
        $piutangs = piutang::where('id_usaha', auth()->user()->id_usaha)->get(); 
        return view('pembayaran.piutang', compact('bayarpiutang','piutangs')); 
    }

    public function create($id)
    {
        $piutang = piutang::findOrFail($id);
        return view('pembayaran.piutang', compact('piutang'));
    }

    public function store(Request $request)
{
    // Validate input fields
    $request->validate([
        'tanggal_pembayaran' => 'required|date|before_or_equal:today',
        'nama' => 'required|string',
        'pembayaran' => 'required|string|min:4|max:20',
        'jumlah' => 'required|numeric',
    ]);

    // Retrieve the Piutang based on ID
    $piutang = Piutang::where('id', $request->id)->first();

    // Check if the installments are completed
    if ($piutang->jumlah_cicilan <= 0) {
        return redirect()->back()->with('error', 'Jumlah cicilan sudah habis.');
    }

    // Check if the payment amount exceeds the remaining debt (sisa_piutang)
    if ($request->jumlah > $piutang->sisa_piutang) {
        return redirect()->back()->withErrors(['jumlah' => 'Jumlah melebihi sisa piutang.']);
    }

    // Custom validation for when there is only 1 installment remaining
    if ($piutang->jumlah_cicilan == 1 && $request->jumlah != $piutang->sisa_piutang) {
        return redirect()->back()->withErrors([
            'jumlah' => 'Nominal pembayaran harus sama dengan sisa piutang karena ini adalah cicilan terakhir.'
        ]);
    }

    // Custom validation for minimum installment payment
    $minimum_payment = $piutang->sisa_piutang / $piutang->jumlah_cicilan;
    if ($request->jumlah < $minimum_payment) {
        return redirect()->back()->withErrors([
            'jumlah' => 'Pembayaran tidak mencukupi jumlah angsuran yang ditentukan.'
        ]);
    }

    // Update the remaining debt after payment
    $sisa_piutang = $piutang->sisa_piutang - $request->jumlah;
    $piutang->sisa_piutang = $sisa_piutang;

    // If the debt is fully paid off, update the status
    if ($sisa_piutang <= 0) {
        $piutang->status = true; // Mark as paid off
    }
    $piutang->save();

    // Create a new BayarPiutang entry
    BayarPiutang::create([
        'id_piutang' => $piutang->id,
        'tanggal_pembayaran' => $request->tanggal_pembayaran,
        'nama' => $request->nama,
        'pembayaran' => $request->pembayaran,
        'jumlah' => $request->jumlah,
        'id_usaha' => $piutang->id_usaha,
    ]);

    return redirect()->route('piutang.index')->with('success', 'Pembayaran piutang berhasil disimpan.');
    }
}