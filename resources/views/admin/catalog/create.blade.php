@extends('layouts.authenticated')
@section('header', 'New Catalog')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Catalog</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('catalog.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="InputCatalog">Name</label>
                            <input name="name" type="text" class="form-control" id="InputCatalog"
                                placeholder="Enter name">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
