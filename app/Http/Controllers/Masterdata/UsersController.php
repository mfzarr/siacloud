<?php

// File: app/Http/Controllers/Masterdata/UsersController.php

namespace App\Http\Controllers\Masterdata;

use App\Models\Masterdata\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = DB::table('users')
            ->leftJoin('user_role', 'users.id_role', '=', 'user_role.id_role')
            ->join('perusahaan', 'users.id_perusahaan', '=', 'perusahaan.id_perusahaan')
            ->select('users.*', 'user_role.nama_role', 'perusahaan.nama')
            ->get();

        $perusahaan = DB::table('perusahaan')->get();
        $roles = DB::table('user_role')->get();

        return view('masterdata.users.index', [
            'items' => $users,
            'perusahaan' => $perusahaan,
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'id_role' => 'required|exists:user_role,id_role',
            'status' => 'required|in:aktif,nonaktif',
            'detail' => 'nullable|string',
        ]);

        User::create([
            'id_perusahaan' => $request->id_perusahaan,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'status' => $request->status,
            'detail' => $request->detail,
        ]);

        return response()->json(['message' => 'User created successfully']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $perusahaan = DB::table('perusahaan')->get();
        $roles = DB::table('user_role')->get();

        return view('masterdata.users.edit', compact('user', 'perusahaan', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'id_role' => 'required|exists:user_role,id_role',
            'status' => 'required|in:aktif,nonaktif',
            'detail' => 'nullable|string',
        ]);

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->id_perusahaan = $request->id_perusahaan;
        $user->id_role = $request->id_role;
        $user->status = $request->status;
        $user->detail = $request->detail;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }
}
