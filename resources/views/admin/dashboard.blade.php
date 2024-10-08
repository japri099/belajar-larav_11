@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main content (col-md-12 because sidebar is already in app.blade) -->
        <div class="col-md-12 mt-3">
            <h1>Dashboard Admin</h1>

            <!-- Jumlah Items -->
            <div class="alert alert-info">
                Jumlah Items: {{ $jumlahItems }}
            </div>

            <!-- Tabel List Items -->
            <table class="table table-dark table-striped table-hover">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Kode</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><img src="{{ $item->gambar ? (filter_var($item->gambar, FILTER_VALIDATE_URL) ? $item->gambar : asset('storage/' . $item->gambar)) : 'https://salonlfc.com/wp-content/uploads/2018/01/image-not-found-1-scaled-1150x647.png' }}"
                                     class="img-thumbnail" style="width: 100px;" alt="{{ $item->kode }}">
                            </td>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->ket }}</td>
                            <td>
                                <a href="{{ route('admin.items.edit', $item->kode) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('admin.items.destroy', $item->kode) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tombol untuk memicu modal pop-up -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItemModal">
    Tambah Item
</button>

<!-- Modal Pop-up -->
<div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createItemModalLabel">Tambah Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Kode, otomatis diisi -->
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" class="form-control" id="kode" value="{{ old('kode', $kode ?? '') }}" disabled>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label for="ket" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="ket" id="ket" rows="3" required>{{ old('ket') }}</textarea>
                    </div>

                    <!-- Opsi Gambar: Link atau File -->
                    <div class="mb-3">
                        <label for="gambar">Gambar</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gambar_type" id="gambar_url_radio" value="url" checked>
                            <label class="form-check-label" for="gambar_url_radio">URL</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gambar_type" id="gambar_file_radio" value="file">
                            <label class="form-check-label" for="gambar_file_radio">File</label>
                        </div>

                        <!-- Input URL Gambar -->
                        <div class="mb-3" id="gambar_url_input">
                            <input type="text" class="form-control" name="gambar_url" placeholder="Masukkan URL gambar">
                        </div>

                        <!-- Input File Gambar -->
                        <div class="mb-3" id="gambar_file_input" style="display: none;">
                            <input type="file" class="form-control" name="gambar_file">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Tambah Item</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Form Edit Profile -->
<div id="profileForm" class="container mt-5">
    <h1>Edit Profile for {{ $user->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" >
        </div>

        <div class="form-group mb-3">
            <label for="role">Role</label>
            <input type="text" id="role" class="form-control" value="{{ $user->role }}" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="password">Password (optional)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>


<!-- Script untuk mengubah input gambar -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gambarUrlRadio = document.getElementById('gambar_url_radio');
        const gambarFileRadio = document.getElementById('gambar_file_radio');
        const gambarUrlInput = document.getElementById('gambar_url_input');
        const gambarFileInput = document.getElementById('gambar_file_input');

        gambarUrlRadio.addEventListener('change', function() {
            gambarUrlInput.style.display = 'block';
            gambarFileInput.style.display = 'none';
        });

        gambarFileRadio.addEventListener('change', function() {
            gambarUrlInput.style.display = 'none';
            gambarFileInput.style.display = 'block';
        });
    });

    // Toggle Sidebar visibility
document.getElementById("toggleSidebar").addEventListener("click", function() {
    document.getElementById("sidebar").classList.toggle("active");
});

// Scroll ke form profile saat link di sidebar diklik
document.querySelector('a[href="#profileForm"]').addEventListener("click", function(event) {
    event.preventDefault();
    document.getElementById("profileForm").scrollIntoView({
        behavior: "smooth"
    });
});

</script>


        </div> <!-- End of Main Content -->
    </div> <!-- End of Row -->
</div> <!-- End of Container -->
@endsection
