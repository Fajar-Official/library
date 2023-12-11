@extends('layouts.authenticated')
@section('header', 'Book')

@section('content')
    <div id="controller">
        <div class="row">

            <div class="col-md-5 offset-md-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Search from title"
                        v-model="search">
                </div>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary" @click="addData()"> Create New Book</button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12" v-for="item in filteredList" :key="item.id">
                <div class="info-box" v-on:click="editData(item)">
                    <div class="info-box-content">
                        <span class="info-box-text h3">@{{ item.title }}(@{{ item.qty }})</span>
                        <span class="info-box-number">Rp @{{ formatNumberWithDot(item.price) }},- <small></small></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="" autocomplete="off">
                        <div class="modal-header">
                            <h4 class="modal-title">Form New Book</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="_method" v-if="editStatus" value="PUT">
                            <div class="form-group">
                                <label>ISBN</label>
                                <input type="number" name="isbn" class="form-control" :value="book.isbn">
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" :value="book.title">
                            </div>
                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="number" name="year" class="form-control" :value="book.year">
                            </div>
                            <div class="form-group">
                                <label>Publisher</label>
                                <select name="publisher_id" class="form-control">
                                    @foreach ($publishers as $item)
                                        <option :selected="book.publisher_id == {{ $item->id }}"
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Catalog</label>
                                <select name="catalog_id" class="form-control">
                                    @foreach ($catalogs as $item)
                                        <option :selected="book.catalog_id == {{ $item->id }}"
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Author</label>
                                <select name="author_id" class="form-control">
                                    @foreach ($authors as $item)
                                        <option :selected="book.author_id == {{ $item->id }}"
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="qty" class="form-control" :value="book.qty">
                            </div>
                            <div class="form-group">
                                <label>Harga Pinjam</label>
                                <input type="number" name="price" class="form-control" :value="book.price">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" v-if="editStatus"
                                v-on:click="deleteData(book.id)">Delete</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        var actionUrl = '{{ url('books') }}';
        var apiUrl = '{{ url('api/books') }}';

        var app = new Vue({
            el: '#controller',
            data: {
                books: [],
                search: '',
                book: {},
                editStatus: false,
            },
            mounted() {
                this.get_books();
            },
            methods: {
                get_books() {
                    const _this = this;
                    $.ajax({
                        url: apiUrl,
                        method: 'GET',
                        success: function(data) {
                            _this.books = JSON.parse(data);
                        },
                        error: function(error) {
                            console.log(error);
                        },
                    })
                },
                formatNumberWithDot(number) {
                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
                addData() {
                    this.book = {};
                    this.editStatus = false;
                    $('#modal-default').modal();
                },
                editData(item) {
                    this.book = item;
                    this.editStatus = true;
                    $('#modal-default').modal();
                },
                deleteData(id) {
                    const _this = this;
                    if (confirm('Are you sure you want to delete this book?')) {
                        $.ajax({
                            url: `${actionUrl}/${id}`,
                            method: 'DELETE',
                            success: function(data) {
                                alert('Book deleted successfully.');
                                _this.get_books();
                            },
                            error: function(error) {
                                console.log(error);
                                alert('An error occurred while deleting the book.');
                            },
                        });
                    }
                }

            },
            computed: {
                filteredList() {
                    return this.books.filter(book => {
                        return book.title.toLowerCase().includes(this.search.toLowerCase());
                    });
                }
            }
        });
    </script>
@endsection
