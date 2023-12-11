@extends('layouts.authenticated')
@section('header', 'Members')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div id="controller" class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <button @click="addData()" type="button" class="btn btn-primary">
                            Create New Member
                        </button>
                    </div>
                    <div class="float-right">
                        <label for="gender" class="mr-2">Filter Gender:</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">Semua</option>
                            <option value="1">L</option>
                            <option value="0">P</option>
                        </select>
                    </div>
                </div>

                <div class="card-body table-striped table-bordered">
                    <table id="datatable" class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Phone Number</th>
                                <th>Addres</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" :action="actionUrl" autocomplete="off">
                    <div class="modal-header">
                        <h4 class="modal-title">Form New Member</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf

                        {{-- <input type="hidden" name="_method" value="PUT" v-if="editStatus"> --}}

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" name="name" id="name" :value="data.name">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select name="gender" id="gender">
                                <option value="1">Laki Laki</option>
                                <option value="2">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" id="email" :value="data.email">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="number" class="form-control" name="phone_number" id="phone_number"
                                :value="data.phone_number">
                            @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" name="address" id="address" :value="data.address">
                            @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script type="text/javascript">
        var actionUrl = `{{ url('members') }}`;
        var apiUrl = `{{ url('api/members') }}`;

        var columns = [{
                data: 'DT_RowIndex',
                className: 'text-center',
                orderable: true
            },
            {
                data: 'name',
                className: 'text-center',
                orderable: true
            },
            {
                data: 'gender',
                className: 'text-center',
                orderable: true
            },
            {
                data: 'phone_number',
                className: 'text-center',
                orderable: true
            },
            {
                data: 'address',
                className: 'text-center',
                orderable: true
            },
            {
                data: 'email',
                className: 'text-center',
                orderable: true
            },
            {
                render: function(index, raw, data, meta) {
                    return `
                    <a href="#" class="btn btn-warning" onclick="controller.editData(event,${meta.row})">Edit</a>
                    <a href="#" class="btn btn-danger" onclick="controller.deleteData(event,${data.id})">Delete</a>
                    `;
                },
                orderable: false,
                width: '200px',
                className: "text-center"
            }
        ];

        var controller = new Vue({
            el: '#controller',
            data: {
                datas: [],
                data: {

                },
                actionUrl: actionUrl,
                apiUrl: apiUrl,
                editStatus: false
            },
            mounted: function() {
                this.datatable();
            },
            methods: {
                datatable() {
                    const _this = this;
                    _this.table = $('#datatable').DataTable({
                        ajax: {
                            url: _this.apiUrl,
                            type: 'GET',
                        },
                        columns: columns
                    }).on('xhr', function() {
                        _this.datas = _this.table.ajax.json().data;
                    });
                },
                addData() {
                    this.data = {};
                    this.editStatus = false;
                    $('#modal-default').modal();
                },
                editData(item) {
                    this.data = item;
                    this.editStatus = true;
                    $('#modal-default').modal();
                },
                deleteData(id) {
                    const _this = this;
                    if (confirm('Are you sure you want to delete this member?')) {
                        $.ajax({
                            url: `${actionUrl}/${id}`,
                            method: 'DELETE',
                            success: function(data) {
                                alert('Member deleted successfully.');
                                _this.datatable();
                            },
                            error: function(error) {
                                console.log(error);
                                alert('An error occurred while deleting the book.');
                            },
                        });
                    }
                },
            }
        });
    </script>
    {{-- <script src="{{ asset('js/data.js') }}"></script> --}}
    <script type="text/javascript">
        $('select[name=gender]').on('change', function() {
            gender = $('select[name=gender]').val();
            console.log('Selected gender:', gender);

            if (!gender) {
                controller.table.ajax.url(apiUrl).load();
            } else {
                const url = apiUrl + '?gender=' + gender;
                console.log('Filter URL:', url);
                controller.table.ajax.url(url).load();
            }
        });
    </script>

@endsection
