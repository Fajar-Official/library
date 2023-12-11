<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Publisher::with('books')->get();
        return view('admin.publisher.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.publisher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:publishers,email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Kolom nama harus diisi.',
            'email.required' => 'Kolom email harus diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.unique' => 'Alamat email ini sudah digunakan.',
            'phone_number.required' => 'Kolom nomor telepon harus diisi.',
            'address.required' => 'Kolom alamat harus diisi.',
        ]);

        Publisher::create($request->all());

        return Redirect::to(route('publisher.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        return view('admin.publisher.edit', compact('publisher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            Rule::unique('publishers', 'email')->ignore($publisher->id),
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Kolom nama harus diisi.',
            'email.required' => 'Kolom email harus diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.unique' => 'Alamat email ini sudah digunakan.',
            'phone_number.required' => 'Kolom nomor telepon harus diisi.',
            'address.required' => 'Kolom alamat harus diisi.',
        ]);

        $publisher->update($request->all());

        return Redirect::to(route('publisher.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return Redirect::to(route('publisher.index'));
    }
}
