@extends('layouts.apps')
@section('content')
<main class="container mt-5">
    <h3 class="mb-4">Form Input Produk</h3>

    <form action="{{ route('produk.store') }}" method="POST">
        @csrf

        <!-- Nama Barang -->
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" name="nama" class="form-control" id="nama" required>
        </div>

        <!-- Harga -->
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" id="harga" required>
        </div>

        <!-- Stok -->
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" id="stok" required>
        </div>

        <!-- Keterangan -->
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" id="keterangan" rows="3"></textarea>
        </div>

        <!-- Tombol Submit dan Cancel -->
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ url('produk') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</main>
@endsection

