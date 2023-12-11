@extends('layouts.authenticated')
@section('header', 'Create Transaction')
@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Transaction</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('transactions.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Member</label>
                            <select name="member_id" class="form-control">
                                @foreach ($members as $item)
                                    <option :selected="transactions.member_id == {{ $item->id }}"
                                        value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Date Start</label>
                            <input name="date_start" type="date" class="form-control" id="">
                            @if ($errors->has('date_start'))
                                <span class="text-danger">{{ $errors->first('date_start') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Date End</label>
                            <input name="date_end" type="date" class="form-control" id="" placeholder="">
                            @if ($errors->has('date_end'))
                                <span class="text-danger">{{ $errors->first('date_end') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Books</label>
                            <select name="book_id[]" class="form-control select2" multiple="multiple">
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- /.card-body -->
                        <div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@section('js')
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>
@endsection
