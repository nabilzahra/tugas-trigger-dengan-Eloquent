
     @extends('layouts.apps')
     @section('content')
     <main class="container-fluid">
     <h3 class="text-center mt-3 mb-5">Daftar Produk</h3>
     <a href='{{ route('produk.create') }}' class="btn text-black" href="produk/tambah" style="background-color: pink; border-color: pink; margin-top: -10px;">Tambah
</a>

    </a>

     <table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Produk</th>
            <th>Stok</th>
            <th>Aksi</th> <!-- Tambah kolom Aksi -->
        </tr>
    </thead>
    <tbody>
        @foreach($produk as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->stok }}</td>
            <td>
                <!-- Tombol Edit -->
                <a href="{{ route('produk.edit', $item->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>

                <!-- Tombol Hapus -->
                <form action="{{ route('produk.destroy', $item->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus produk ini?')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

     </main>
     @endsection