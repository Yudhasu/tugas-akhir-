<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    public function create()
    {
        return view('add.member-add');
    }
    
    public function store(Request $request)
    {
        $rules = [];
        $messages = [];
        $rules['nama'] = 'unique:users|max:50|required';
        $messages['nama.unique'] = 'Username sudah ada!';
        $messages['nama.max'] = 'Username tidak boleh lebih dari :max karakter!';
        $messages['nama.required'] = 'Username wajib diisi!';

        $rules['password'] = 'required';
        $messages['password.required'] = 'Password wajib diisi!';

        $rules['password_c'] = 'required|same:password';
        $messages['password_c.required'] = 'Konfirmasi Password wajib diisi!';
        $messages['password_c.same'] = 'Konfirmasi Password tidak sesuai!';

        $request->validate($rules, $messages);

        // Hanya ambil field yang diperlukan untuk menghindari mass assignment
        $userData = [
            'nama' => $request->nama,
            'password' => Hash::make($request->password)
        ];

        $result = User::create($userData);

        if ($result) {
            Session::flash('succMessage', 'Akun berhasil ditambahkan!');
        } else {
            Session::flash('errMessage', 'Akun gagal ditambahkan!');
        }

        return redirect('/member-create');
    }
}