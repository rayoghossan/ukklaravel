<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProviders;
use Illuminate\Pagination\Paginator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
    {
        $search = $request->search;
        $query = DB::table('barang')
                   ->select('id', 'merk', 'seri', 'spesifikasi', 'stok', 'kategori_id');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhere('merk', 'like', '%' . $search . '%')
                  ->orWhere('seri', 'like', '%' . $search . '%')
                  ->orWhere('spesifikasi', 'like', '%' . $search . '%')
                  ->orWhere('kategori_id', 'like', '%' . $search . '%');
            });
        }

        $rsetBarang = $query->paginate(5);

        Paginator::useBootstrap();
        return view('v_barang.index', compact('rsetBarang'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();

        $aKategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Barang Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );

            
        return view('v_barang.create',compact('aKategori', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required|unique:barang', // Menambahkan aturan unik untuk kolom 'merk'
            'seri' => 'required',
            'spesifikasi' => 'required',
            'kategori_id' => 'required',
           'stok' => 'nullable|integer|min:0', // Validasi stok untuk memastikan nilai positif atau nol
        ], [
            'merk.required' => 'Merk harus diisi.',
            'merk.unique' => 'Merk sudah ada, gunakan merk lain.',
            'seri.required' => 'Seri harus diisi.',
            'spesifikasi.required' => 'Spesifikasi harus diisi.',
            'kategori_id.required' => 'Kategori harus dipilih.',
            'stok.required' => 'Stok harus diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok minimal adalah 0.',
        ]);
    
        try {
            // Proses penyimpanan data ke dalam basis data
            Barang::create([
                'merk' => $request->merk,
                'seri' => $request->seri,
                'spesifikasi' => $request->spesifikasi,
                'stok' => $request->stok,
                'kategori_id' => $request->kategori_id,
            ]);
    
            // Redirect ke halaman indeks dengan pesan sukses
            return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            // Tangani kesalahan saat menyimpan data
            return back()->withErrors(['message' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarang = Barang::find($id);
        $deskripsiKategori = Barang::with('kategori')->where('id', $id)->first();
        return view('v_barang.show', compact('rsetBarang', 'deskripsiKategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $aKategori = array('blank'=>'Pilih Kategori',
        //                 'M'=>'Barang Modal',
        //                 'A'=>'Alat',
        //                 'BHP'=>'Bahan Habis Pakai',
        //                 'BTHP'=>'Bahan Tidak Habis Pakai'
        //             );

        $rsetBarang = Barang::find($id);
        $kategoriID = Kategori::all();
        //return $rsetBarang;
        return view('v_barang.edit', compact('rsetBarang', 'kategoriID'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'merk' => 'required',
        'seri' => 'required',
        'spesifikasi' => 'required',
        'stok' => 'required',
        'kategori_id' => 'required',
    ]);

    $rsetBarang = Barang::find($id);
    $rsetBarang->update($request->all());

    return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Diubah!']);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (DB::table('barangmasuk')->where('barang_id', $id)->exists() || DB::table('barangkeluar')->where('barang_id', $id)->exists()) {
            return redirect()->route('barang.index')->with(['Gagal' => 'Gagal dihapus']);
        } else {
            $rsetBarang = Barang::find($id);
            $rsetBarang->delete();
            return redirect()->route('barang.index')->with(['Success' => 'Berhasil dihapus']);
        }
    }

    
    // public function search(Request $request)
    // {
    //     $query = $request->input('search');
        
    //     // Call the stored procedure
    //     $rsetResults = DB::select('CALL SearchBarang(?)', [$query]);

    //     return view('v_barang.search', compact('rsetResults', 'query'));
    // }
}