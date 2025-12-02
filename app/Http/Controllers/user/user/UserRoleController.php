<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // PAGINATION UNTUK USER ROLE
        if ($perPage === 'all') {
            $dataUserRole = UserRole::orderBy('created_at', 'desc')->get();
            $dataUserRole = new \Illuminate\Pagination\LengthAwarePaginator(
                $dataUserRole,
                $dataUserRole->count(),
                $dataUserRole->count(),
                1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'userRole_page',
                ]
            );
        } else {
            $dataUserRole = UserRole::orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'userRole_page');
        }

        return view('user.indexUserRole', compact('dataUserRole'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:user_roles,username',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,teknisi,marketing',
            'status' => 'required|in:aktif,tidak_aktif',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'nama.required' => 'Nama wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'status.required' => 'Status wajib dipilih',
        ]);

        $validated['password_hint'] = encrypt($request->password);
        $validated['password'] = bcrypt($request->password);

        UserRole::create($validated);

        return redirect()->route('UserRole.user-role.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = UserRole::findOrFail($id);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_roles', 'username')->ignore($user->id)
            ],
            'nama' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:super_admin,teknisi,marketing',
            'status' => 'required|in:aktif,tidak_aktif',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'nama.required' => 'Nama wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'status.required' => 'Status wajib dipilih',
        ]);

        if (!empty($validated['password'])) {
            $validated['password_hint'] = encrypt($request->password);
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('UserRole.user-role.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = UserRole::findOrFail($id);

        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('UserRole.user-role.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('UserRole.user-role.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
