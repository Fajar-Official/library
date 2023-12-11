@extends('layouts.authenticated')
@section('header', 'New Publisher')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Publisher</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('publisher.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="InputPublisherName">Name</label>
                            <input name="name" type="text" class="form-control" id="InputPublisherName"
                                placeholder="Enter name">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="InputPublisherEmail">Email</label>
                            <input name="email" type="text" class="form-control" id="InputPublisherEmail"
                                placeholder="Enter Email">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="InputPublisherPhoneNumber">Phone Number</label>
                            <input name="phone_number" type="number" class="form-control" id="InputPublisherPhoneNumber"
                                placeholder="Enter Phone Number">
                            @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="InputPublisherAddress">Address</label>
                            <input name="address" type="text" class="form-control" id="InputPublisherAddress"
                                placeholder="Enter Adress">
                            @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
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
