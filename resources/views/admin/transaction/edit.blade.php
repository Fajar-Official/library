@extends('layouts.authenticated')
@section('header', 'Edit Transaction')
@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Transaction</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('transactions.update', $transaction->id) }}" method="post">
                    @csrf
                    @method('put') <!-- Specify the method as PUT -->

                    <div class="card-body">
                        <div class="form-group">
                            <label>Member</label>
                            <select name="member_id" class="form-control">
                                @foreach ($members as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $transaction->member_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_start">Date Start</label>
                            <input name="date_start" type="date" class="form-control"
                                value="{{ $transaction->date_start }}">
                            @if ($errors->has('date_start'))
                                <span class="text-danger">{{ $errors->first('date_start') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="date_end">Date End</label>
                            <input name="date_end" type="date" class="form-control" value="{{ $transaction->date_end }}">
                            @if ($errors->has('date_end'))
                                <span class="text-danger">{{ $errors->first('date_end') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Books</label>
                            <select name="book_id[]" class="form-control select2" multiple="multiple">
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}"
                                        {{ in_array($book->id, $transaction->transaction_detail->pluck('book_id')->toArray()) ? 'selected' : '' }}>
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="0"
                                    {{ $transaction->status == 0 ? 'checked' : '' }}>
                                <label class="form-check-label">Peminjaman</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="1"
                                    {{ $transaction->status == 1 ? 'checked' : '' }}>
                                <label class="form-check-label">Pengembalian</label>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Transaction</button>
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
            // Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>
@endsection
