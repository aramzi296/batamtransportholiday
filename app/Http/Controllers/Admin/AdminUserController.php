<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'customer', 'member'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'customer', 'member'])],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Show the profile form for the specified user.
     */
    public function profile(User $user)
    {
        $dataAnggota = $user->data_anggota ?? [];
        return view('admin.users.profile', compact('user', 'dataAnggota'));
    }

    /**
     * Update the profile for the specified user.
     */
    public function updateProfile(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nomor_hp' => 'nullable|string|max:20',
            'nomor_whatsapp' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'alamat_rumah' => 'nullable|string|max:500',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'kategori_anggota' => 'nullable|array',
            'kategori_anggota.*' => 'in:member,driver',
            'sim' => 'nullable|array',
            'sim.*' => 'in:A,B1,B2,C',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                if (Storage::disk('public')->exists($user->foto_profil)) {
                    Storage::disk('public')->delete($user->foto_profil);
                }
            }
            
            // Upload ke public storage
            $path = $file->storeAs('profiles', $filename, 'public');
            $user->foto_profil = $path;
        }

        // Update nomor whatsapp (tetap disimpan di kolom nomor_whatsapp untuk backward compatibility)
        $user->nomor_whatsapp = $request->nomor_whatsapp;

        // Update data anggota (JSON) - termasuk nomor_hp dan nomor_whatsapp
        $dataAnggota = [
            'nomor_hp' => $request->nomor_hp,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat_rumah' => $request->alamat_rumah,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'kategori_anggota' => $request->kategori_anggota ?? [],
            'sim' => $request->sim ?? [],
        ];

        $user->data_anggota = $dataAnggota;
        $user->save();

        return redirect()->route('admin.users.profile', $user->id)
            ->with('success', 'Profil anggota berhasil diperbarui!');
    }
}
