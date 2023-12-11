@extends('layouts.authenticated')
@section('header', 'Catalog')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <a href="{{ route('catalog.create') }}" class="btn btn-sm btn-primary">Buat Catalog
                            Baru</a>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Total Buku</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ count($item->books) }}</td>
                                    <td>{{ convert_date($item->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('catalog.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>

                                        <form action="{{ route('catalog.destroy', $item->id) }}" method="post">
                                            <input type="submit" value="Delete" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure ?')">
                                            @method('delete')
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
