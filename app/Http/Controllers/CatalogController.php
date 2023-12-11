<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Catalog::with('books')->get();
        return view('admin.catalog.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.catalog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Kolom nama harus diisi.',
        ]);

        $this->validate($request, [
            'name' => ['required'],
        ]);
        Catalog::create($request->all());

        return Redirect::to(route('catalog.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Catalog $catalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catalog $catalog)
    {
        return view('admin.catalog.edit', compact('catalog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catalog $catalog)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Kolom nama harus diisi.',
        ]);

        $catalog->update($request->all());

        return Redirect::to(route('catalog.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catalog $catalog)
    {
        $catalog->delete();

        return Redirect::to(route('catalog.index'));
    }
}
