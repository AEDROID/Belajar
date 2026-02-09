<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|max:255|unique:users',
            'role'=>'required|string|in:admin,user,petugas',
            'password'=>'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'role'=>$request->role,
            'password'=>hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'Anda berhasil menambah user!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrfail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::findOrfail($id);
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|max:255|unique:users',
            'role'=>'required|string|in:admin,user,petugas',
            'password'=>'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if($request->filled('password')){
            $user->password = hash::make($request->password);
        }

        $user->save();
        

        return redirect()->route('user.index')->with('success', 'Anda berhasil mengubah user!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrfail($id);

        if($user->id === auth()->id){
            return redirect()->route('user.index')->with('error', 'Anda tidak bisa menghapus diri anda sendiri!');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Anda berhasil menghapus data');
    }
}
