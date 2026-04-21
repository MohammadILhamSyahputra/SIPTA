<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $roles = ['admin', 'owner', 'kasir'];
        
        return view('user.index', compact('users', 'roles'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = ['admin', 'owner', 'kasir'];
        
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')->with('error', 'Tidak dapat mengedit peran sendiri.');
        }

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'userType' => 'required|in:admin,owner,kasir',
        ]);
        
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengedit peran sendiri.');
        }

        $user->userType = $request->userType;
        $user->save();

        return redirect()->route('user.index')->with('success', 'Peran pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri!');
        }
        
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
