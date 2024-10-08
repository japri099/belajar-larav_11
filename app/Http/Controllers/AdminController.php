<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Index function to show list of items and total count
    public function index()
{
    $items = Item::all();
    $jumlahItems = $items->count();  // Definisikan jumlah item

    $user = auth()->user(); // Ambil user yang sedang login

    return view('admin.dashboard', compact('items', 'jumlahItems', 'user'));  // Kirim variabel ke view
}



    // Show form for creating new item
    public function create()
    {
        return view('admin.create');
    }

    // Store newly created item in storage
    public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'ket' => 'required|string|max:255',
    ]);

    // Generate kode otomatis
    $lastItem = Item::orderBy('kode', 'desc')->first();
    $nextKode = 'PK' . str_pad(($lastItem ? intval(substr($lastItem->kode, 2)) + 1 : 1), 3, '0', STR_PAD_LEFT);

    // Proses upload gambar atau simpan URL
    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('items', 'public');
    } else {
        $path = $request->input('gambar_url');
    }

    // Simpan item ke database
    Item::create([
        'kode' => $nextKode,  // kode otomatis
        'gambar' => $path,
        'ket' => $validated['ket'],
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Item berhasil ditambahkan.');
}



public function update(Request $request, Item $item)
{
    // Validasi input
    $validatedData = $request->validate([
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'gambar_url' => 'nullable|url',
        'ket' => 'required|string|max:255',
    ]);

    // Proses gambar: jika file diupload, simpan; jika URL disediakan, gunakan URL; jika tidak ada yang diubah, tetap gambar lama
    if ($request->hasFile('gambar')) {
        if ($item->gambar && !filter_var($item->gambar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($item->gambar);  // Hapus gambar lama jika bukan URL
        }
        $gambar = $request->file('gambar')->store('items', 'public');
    } else {
        $gambar = $request->input('gambar_url') ?? $item->gambar;
    }

    // Update item di database
    $item->update([
        'gambar' => $gambar,
        'ket' => $validatedData['ket'],
    ]);

    return redirect()->route('admin.items.index')->with('success', 'Item berhasil diperbarui.');
}


    // Remove the specified item from storage
    public function destroy(Item $item)
    {
        // Delete image if not a URL
        if ($item->gambar && !filter_var($item->gambar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($item->gambar);
        }

        // Delete item from database
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item berhasil dihapus.');
    }

    // Lindungi metode dengan middleware auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan form edit profil
    public function editProfile()
{
    $user = auth()->user(); // Ambil user yang sedang login
    return view('admin.profile-edit', compact('user')); // Kirim $user ke view
}

    // Memproses update profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update profil
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika ada
        if ($request->password) {
            $user->password = Hash::make($request->password); // Hash password
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully!');
    }
}
