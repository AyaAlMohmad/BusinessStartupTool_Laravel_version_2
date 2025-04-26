<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }


    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found');
        }

        return view('admin.users.show', compact('user'));
    }
    public function edit($id)
{
    $user = User::findOrFail($id); 
    return view('admin.users.edit', compact('user')); 
}

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'status' => 'required|in:active,blocked,inactive',
        ]);
    
        $user = User::findOrFail($id);
        $user->update($data);
    
        return redirect()->back()->with('success', 'User updated successfully!');
    }
    

    public function changeStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found');
        }

        $user->status = $user->status === 'active' ? 'blocked' : 'active';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully');
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', ' User deleted successfully');
    }
}
