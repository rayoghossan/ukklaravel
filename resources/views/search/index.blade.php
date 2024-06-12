@extends('layouts.adm-main')

@section('content')
<div class="container">
    <h2>Hasil pencarian untuk "{{ $query }}"</h2>

    @if($resultsKategori->isEmpty())
        <p>Tidak ada Kategori ditemukan dengan kata kunci <b>'{{ $query }}'</b></p>
    @else
    <h4>Tabel Kategori</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Merk</th>
                    <th>Seri</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultsKategori as $result)
                    <tr>
                        <td>{{ $result->deskripsi }}</td>
                        <td>{{ $result->kategori }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <br>
    <hr>
    <br>
    @if($resultsBarang->isEmpty())
        <p>Tidak ada Barang ditemukan dengan kata kunci <b>'{{ $query }}'</b></p>
    @else
    <h4>Tabel Barang</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Merk</th>
                    <th>Seri</th>
                    <th>Spesifikasi</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultsBarang as $result)
                    <tr>
                        <td>{{ $result->merk }}</td>
                        <td>{{ $result->seri }}</td>
                        <td>{{ $result->spesifikasi }}</td>
                        <td>{{ $result->stok }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
</div>
@endsection