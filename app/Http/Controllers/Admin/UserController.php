<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Show the user management page with all users (excluding current logged-in).
     */
    public function index(Request $request)
    {
        $query = User::query()->where('role', '!=', 'student');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->orderByRaw("CASE WHEN role = 'admin' THEN 1 WHEN role = 'staff' THEN 2 ELSE 3 END")
                       ->orderBy('name')
                       ->get();

        return view('admin.users', compact('users'));
    }

    /**
     * Store a new user in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username|regex:/^\S+$/',
            'email'    => 'required|email|max:255|unique:users,email',
            'role'     => ['required', Rule::in(['admin', 'staff'])],
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'username.regex'    => 'Username tidak boleh mengandung spasi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users')
                         ->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:100', 'regex:/^\S+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', Rule::in(['admin', 'staff'])],
            'password' => 'nullable|string|min:6',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'username.regex'    => 'Username tidak boleh mengandung spasi.',
            'email.unique'      => 'Email sudah digunakan oleh akun lain.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        $data = [
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users')
                         ->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Delete a user (cannot delete own account).
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Toggle user active status (using email_verified_at as active flag).
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        // We use email_verified_at as active/inactive toggle
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now(),
        ]);

        return redirect()->route('admin.users')
                         ->with('success', 'Status pengguna berhasil diubah.');
    }
}
