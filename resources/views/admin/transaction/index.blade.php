@extends('layouts.authenticated')
@section('header', 'Transactions')

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
                    <div class="d-flex justify-content-between align-items-center">
                        <button @click="addData()" type="button" class="btn btn-primary">
                            Create New Author
                        </button>
                        <div class="d-flex">
                            <div class="form-group mr-2">
                                <label for="status" class="mr-2">Filter Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="0">Masih Dipinjam</option>
                                    <option value="1">Dikembalikan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="date_start" class="mr-2">Filter Tanggal Pinjam:</label>
                                <input type="date" name="date_start" id="date_start" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-striped table-bordered">
                    <table id="datatable" class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Member</th>
                                <th>Lama Pinjam</th>
                                <th>Total Buku</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
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
        var actionUrl = "{{ url('/transactions') }}";
        var apiUrl = "{{ url('/api/transactions') }}";

        var columns = [{
                data: 'DT_RowIndex',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'date_start',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'date_end',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'member.name',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'duration',
                class: 'text-center',
                orderable: true,
                render: function(data, type, row, meta) {
                    return `${row.duration} hari`;
                }
            },
            {
                data: 'transaction_count',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'total_price',
                class: 'text-center',
                orderable: true,
                render: function(data, type, row, meta) {
                    return `Rp.${row.total_price}`;
                }
            },
            {
                data: 'status',
                class: 'text-center',
                orderable: true,
                render: function(data, type, row, meta) {
                    return data == 0 ? 'Masih Dipinjam' : 'Dikembalikan';
                }
            },
            {
                render: function(data, type, row, meta) {
                    if (row.status == 1) {
                        return `
                     <a href="#" class="btn btn-info btn-sm">Detail</a>
                   <a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(${row.id})">Delete</a>

                `;
                    } else {
                        return `
                    <a href="#" class="btn btn-info btn-sm">Detail</a>
                    <a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${row.id})">Edit</a>
                    <a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(${row.id})">Delete</a>

                `;
                    }
                },
                orderable: false,
                width: '200px',
                class: 'text-center'
            },
        ];

        var controller = new Vue({
            el: '#controller',
            data: {
                data: null,
                datas: [],
                actionUrl,
                apiUrl,
            },
            mounted: function() {
                this.datatable();
            },
            methods: {
                datatable() {
                    const _this = this;
                    _this.data = $('#datatable').DataTable({
                        ajax: {
                            url: _this.apiUrl,
                            type: 'GET'
                        },
                        columns: columns,
                    }).on('xhr', function() {
                        _this.datas = _this.data.ajax.json().data;
                    });
                },
                addData: function() {
                    window.location.href = `${this.actionUrl}/create`;
                },
                editData: function(event, id) {
                    window.location.href = `${this.actionUrl}/${id}/edit`;
                },
                deleteData: function(id) {
                    axios.delete("{{ url('/transactions') }}/" + id)
                        .then(function(response) {
                            console.log(response.data);
                            window.location.reload();
                        })
                        .catch(function(error) {
                            console.error(error.response.data);
                        });

                }

            }
        });
    </script>
    <script type="text/javascript">
        // $('select[name=status]').on('change', function() {
        //     var status = $('select[name=status]').val();
        //     console.log('Selected status:', status);

        //     if (!status) {
        //         controller.data.ajax.url(apiUrl).load();
        //     } else {
        //         var url = apiUrl + '?status=' + status;
        //         console.log('Filter URL:', url);
        //         controller.data.ajax.url(url).load();
        //     }
        // });
        // $('input[name=date_start]').on('change', function() {
        //     var dateStart = $('input[name=date_start]').val();
        //     console.log('Selected date_start:', dateStart);

        //     if (!dateStart) {
        //         controller.data.ajax.url(apiUrl).load();
        //     } else {
        //         var url = apiUrl + '?date_start=' + dateStart;
        //         console.log('Filter URL:', url);
        //         controller.data.ajax.url(url).load();
        //     }
        // });
        $('input[name=date_start], select[name=status]').on('change', function() {
            var dateStart = $('input[name=date_start]').val();
            var status = $('select[name=status]').val();

            console.log('Selected date_start:', dateStart);
            console.log('Selected status:', status);

            var url = apiUrl;

            if (dateStart) {
                url += '?date_start=' + dateStart;
            }

            if (status) {
                url += (url.includes('?') ? '&' : '?') + 'status=' + status;
            }

            console.log('Filter URL:', url);

            controller.data.ajax.url(url).load();
        });
    </script>
@endsection
