<?php

namespace App\Http\Controllers;
use App\Models\Gedung;
use App\Models\KategoriGedung;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    //
    // app/Http/Controllers/BerandaController.php

    public function index(Request $request)
    {
        $query = Gedung::query()->with('kategori');


        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%'.$searchTerm.'%')
                  ->orWhere('lokasi', 'like', '%'.$searchTerm.'%')
                  ->orWhere('fasilitas', 'like', '%'.$searchTerm.'%')
                  ->orWhere('deskripsi', 'like', '%'.$searchTerm.'%');
            });
        }
        
        // Filter daerah
        if ($request->has('daerah') && $request->daerah != '') {
            $query->where('daerah', $request->daerah);
        }
        
        // Filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }
        
        // Filter kapasitas
        if ($request->has('kapasitas') && $request->kapasitas != '') {
            $query->where('kapasitas', '>=', $request->kapasitas);
        }
        
        $gedungs = $query->get();
        $kategories = KategoriGedung::all();
        
        return view('beranda', compact('gedungs', 'kategories'));
    }
}
