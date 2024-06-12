<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table ='kategori';

    protected $fillable = [
        'deskripsi',
        'kategori',
    ];

    protected $appends = ['kat'];

    // Accessor untuk mendapatkan deskripsi kategori
    public function getKatAttribute()
    {
        $mapping = [
            'M' => 'Barang Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai',
        ];

        return $mapping[$this->kategori] ?? 'Unknown';
    }
}