<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stokin;
use App\Models\Produk;
// use App\Notifications\StokInNotification;
// use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class StokinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all stokins with pagination
        $stokins = Stokin::with('produk')->latest()->paginate(10);
        return view('stokins.index', compact('stokins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::all();
        return view('stokins.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), Stokin::$rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi data.');
        }

        // Create the stokin record
        $stokin = Stokin::create([
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_masuk' => $request->tanggal_masuk ?? now(),
            'keterangan' => $request->keterangan,
            'satuan' => $request->satuan ?? 'pcs',
        ]);

        // Get the product
        // $produk = Produk::find($request->produk_id);

        // Send notification
        // Notification::route('mail', config('mail.from.address'))
        //     ->notify(new StokInNotification($stokin, $produk));

        return redirect()->route('stokins.index')
            ->with('success', 'Stok masuk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stokin $stokin)
    {
        $stokin->load('produk');
        return view('stokins.show', compact('stokin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stokin $stokin)
    {
        $produks = Produk::all();
        return view('stokins.edit', compact('stokin', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stokin $stokin)
    {
        // Validate the request
        $validator = Validator::make($request->all(), Stokin::$rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi data.');
        }

        // Get the old jumlah value
        $oldJumlah = $stokin->jumlah;
        $newJumlah = $request->jumlah;
        $produkId = $stokin->produk_id;

        // Update the stokin record
        $stokin->update([
            'produk_id' => $request->produk_id,
            'jumlah' => $newJumlah,
            'tanggal_masuk' => $request->tanggal_masuk ?? $stokin->tanggal_masuk,
            'keterangan' => $request->keterangan,
            'satuan' => $request->satuan ?? $stokin->satuan,
        ]);
 
        // If the product ID or quantity changed, we need to manually update the product stock
        if ($produkId != $request->produk_id || $oldJumlah != $newJumlah) {
            // Update old product stock if product changed
            if ($produkId != $request->produk_id) {
                $oldProduk = Produk::find($produkId);
                $oldProduk->update([
                    'stok' => $oldProduk->stok - $oldJumlah
                ]);

                $newProduk = Produk::find($request->produk_id);
                $newProduk->update([
                    'stok' => $newProduk->stok + $newJumlah
                ]);
            } else {
                // Just update the quantity difference
                $produk = Produk::find($produkId);
                $produk->update([
                    'stok' => $produk->stok - $oldJumlah + $newJumlah
                ]);
            }
        }

        // Get the product
        // $produk = Produk::find($request->produk_id);

        // Send notification
        // Notification::route('mail', config('mail.from.address'))
        //     ->notify(new StokInNotification($stokin, $produk, 'update'));

        return redirect()->route('stokins.index')
            ->with('success', 'Data stok masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stokin $stokin)
    {
        // Get the product and jumlah before deleting
        $produk = $stokin->produk;
        $jumlah = $stokin->jumlah;

        // Delete the stokin record
        $stokin->delete();

        // Manually update the product stock
        $produk->update([
            'stok' => $produk->stok - $jumlah
        ]);

        // Send notification
        // Notification::route('mail', config('mail.from.address'))
        //     ->notify(new StokInNotification($stokin, $produk, 'delete'));

        return redirect()->route('stokins.index')
            ->with('success', 'Data stok masuk berhasil dihapus.');
    }
}
