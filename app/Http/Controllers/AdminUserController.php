<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Memastikan hanya Administrator yang berwenang mengelola pengguna.
     */
    private function checkAdminAuth()
    {
        if (!Auth::check() && !session('admin_logged_in')) {
            redirect()->route('login')->send();
            exit;
        }

        $user = Auth::user();
        $isAdmin = ($user && in_array($user->role, ['admin', 'admin_lppm']))
            || session('admin_logged_in')
            || in_array(session('user_role'), ['admin', 'admin_lppm']);

        if (!$isAdmin) {
            abort(403, 'Akses Terlarang. Modul Manajemen Akun Pengguna hanya diperuntukkan bagi Administrator.');
        }
    }

    /**
     * Tampilkan daftar akun pengguna (Admin, Staf, Dosen, Reviewer).
     */
    public function index(Request $request)
    {
        $this->checkAdminAuth();

        $searchQuery = trim($request->get('q', ''));
        $roleFilter = trim($request->get('role', 'all'));

        $query = User::query()->with('dosen');

        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('email', 'like', "%{$searchQuery}%");
            });
        }

        if (!empty($roleFilter) && $roleFilter !== 'all') {
            if ($roleFilter === 'admin') {
                $query->whereIn('role', ['admin', 'admin_lppm']);
            } elseif ($roleFilter === 'staf') {
                $query->whereIn('role', ['staf', 'staff']);
            } else {
                $query->where('role', $roleFilter);
            }
        }

        $users = $query->latest('id')->paginate(15)->withQueryString();

        // Statistik Pengguna
        $totalUsers = User::count();
        $totalAdmin = User::whereIn('role', ['admin', 'admin_lppm'])->count();
        $totalStaf = User::whereIn('role', ['staf', 'staff'])->count();
        $totalDosen = User::where('role', 'dosen')->count();

        // Daftar Dosen untuk dropdown penghubung akun
        $dosens = Dosen::orderBy('nama', 'asc')->get(['id', 'nama', 'nidn', 'prodi', 'email', 'user_id']);

        return view('admin.users.index', compact(
            'users',
            'dosens',
            'totalUsers',
            'totalAdmin',
            'totalStaf',
            'totalDosen',
            'searchQuery',
            'roleFilter'
        ));
    }

    /**
     * Tampilkan formulir halaman pembuatan akun pengguna baru.
     */
    public function create()
    {
        $this->checkAdminAuth();

        $dosens = Dosen::orderBy('nama', 'asc')->get(['id', 'nama', 'nidn', 'prodi', 'email', 'user_id']);

        return view('admin.users.create', compact('dosens'));
    }

    /**
     * Simpan akun pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $this->checkAdminAuth();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staf,dosen,reviewer',
            'dosen_id' => 'nullable|exists:dosens,id',
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar pada akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'role.required' => 'Level akses (role) wajib dipilih.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'dosen_id' => $request->role === 'dosen' ? $request->dosen_id : null,
            'is_reviewer' => $request->role === 'reviewer',
        ]);

        // Hubungkan akun dosen dengan profil dosen di tabel dosens
        if ($request->role === 'dosen') {
            $dosen = null;
            if (!empty($request->dosen_id)) {
                $dosen = Dosen::find($request->dosen_id);
            }
            if (!$dosen) {
                // Auto-link berdasarkan kesamaan email atau nama
                $dosen = Dosen::where('email', $request->email)->first() 
                    ?? Dosen::where('nama', $request->name)->first();
            }

            if ($dosen) {
                $user->update(['dosen_id' => $dosen->id]);
                $dosen->update(['user_id' => $user->id]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun ' . ucfirst($user->role) . ' baru atas nama "' . $user->name . '" berhasil dibuat!');
    }

    /**
     * Perbarui data akun pengguna.
     */
    public function update(Request $request, $id)
    {
        $this->checkAdminAuth();

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,staf,dosen,reviewer',
            'dosen_id' => 'nullable|exists:dosens,id',
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'role.required' => 'Level akses (role) wajib dipilih.',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_reviewer' => $request->role === 'reviewer',
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->role === 'dosen') {
            $targetDosenId = $request->dosen_id;
            if (!$targetDosenId) {
                $autoDosen = Dosen::where('email', $request->email)->first() 
                    ?? Dosen::where('nama', $request->name)->first();
                $targetDosenId = $autoDosen ? $autoDosen->id : $user->dosen_id;
            }

            $updateData['dosen_id'] = $targetDosenId;

            // Lepas relasi lama jika dosen_id berganti
            if ($user->dosen_id && $user->dosen_id != $targetDosenId) {
                Dosen::where('id', $user->dosen_id)->update(['user_id' => null]);
            }

            // Tautkan relasi baru pada profil dosen
            if ($targetDosenId) {
                Dosen::where('id', $targetDosenId)->update(['user_id' => $user->id]);
            }
        } else {
            // Jika role diubah bukan dosen, putus relasi dosen_id
            if ($user->dosen_id) {
                Dosen::where('id', $user->dosen_id)->update(['user_id' => null]);
            }
            Dosen::where('user_id', $user->id)->update(['user_id' => null]);
            $updateData['dosen_id'] = null;
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data akun pengguna "' . $user->name . '" berhasil diperbarui!');
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy($id)
    {
        $this->checkAdminAuth();

        $user = User::findOrFail($id);

        // Pencegahan menghapus akun sendiri yang sedang login
        if (Auth::id() == $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal menghapus: Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif login.');
        }

        // Putus tautan pada tabel dosens jika terhubung
        if ($user->dosen_id) {
            Dosen::where('id', $user->dosen_id)->update(['user_id' => null]);
        }
        Dosen::where('user_id', $user->id)->update(['user_id' => null]);

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna "' . $userName . '" berhasil dihapus dari sistem.');
    }
}
