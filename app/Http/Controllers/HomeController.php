<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Publisher;
use App\Models\Book;
use App\Models\Author;
use App\Models\Catalog;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Eloquent Relationship
        $members = Member::with('user','transactions')->get();
        $books = Book::with('publisher','author','catalog','transaction_detail')->get();
        $publisher = Publisher::with('books')->get();

        $author = Author::with('books')->get();
        $catalog = Catalog::with('books')->get();

        $transaction = Transaction::with('transaction_detail','member')->get();
        $transaction_detail = TransactionDetail::with('books','transaction')->get();

        // QueryBuilder
        $data1 = Member::select('*')
                    ->join('users','users.member_id','members.id')
                    ->get();

        $data2 = Member::select('*')
                    ->leftJoin('users','users.member_id','members.id')
                    ->where('users.id',NULL)
                    ->get();

        $data3 = Transaction::select('members.id','members.name')
                    ->rightJoin('members','members.id','=','transactions.member_id')
                    ->where('transactions.member_id',NULL)
                    ->get();

        $data4 = Member::select('members.id', 'members.name','members.phone_number')
                    ->join('transactions', 'transactions.member_id','=', 'members.id')
                    ->orderBy('members.id','asc')
                    ->get();

        $data5 = Member::join('transactions', 'transactions.member_id', '=', 'members.id')
                    ->groupBy('members.id', 'members.name', 'members.phone_number')
                    ->having(DB::raw('COUNT(transactions.id)'), '>=', 1)
                    ->select('members.id', 'members.name', 'members.phone_number')
                    ->get();

        $data6 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end')
                    ->get();

        $data7 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->whereMonth('transactions.date_end', 9)
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end')
                    ->get();

        $data8 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->whereMonth('transactions.date_start', 8)
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end')
                    ->get();

        $data9 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->whereMonth('transactions.date_start', 8)
                    ->whereMonth('transactions.date_end', 9)
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end')
                    ->get();

        $data10 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->where('members.address', 'LIKE', '%Makassar%')
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end')
                    ->get();

        $data11 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->where('members.address', 'LIKE', '%Makassar%')
                    ->where('members.gender', 0)
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end','members.gender')
                    ->get();

        $data12 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
                    ->join('books', 'books.id', '=', 'transaction_details.book_id')
                    ->where('transaction_details.qty', '>', 1)
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end', 'books.isbn', 'transaction_details.qty')
                    ->get();

        $data13 = Member::join('transactions', 'members.id', '=', 'transactions.member_id')
                    ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
                    ->join('books', 'books.id', '=', 'transaction_details.book_id')
                    ->select('members.name', 'members.phone_number', 'members.address', 'transactions.date_start', 'transactions.date_end',
                                'books.isbn','transaction_details.qty', 'books.title', 'books.price',
                                DB::raw('transaction_details.qty * books.price AS total_price'))->get();

        $data14 = Member::select('members.name', 'members.phone_number', 'members.address','transactions.date_start', 'transactions.date_end',
                'books.isbn','transaction_details.qty', 'books.title',
                'publishers.name', 'authors.name', 'catalogs.name AS catalog_name')
            ->join('transactions', 'members.id', '=', 'transactions.member_id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('books', 'books.id', '=', 'transaction_details.book_id')
            ->join('publishers', 'publishers.id', '=', 'books.publisher_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->join('catalogs', 'catalogs.id', '=', 'books.catalog_id')
            ->get();

        $data15 = Catalog::join('books', 'books.catalog_id', '=', 'catalogs.id')
            ->select('catalogs.*', 'books.title')
            ->get();

        $data16 = Book::leftJoin('publishers', 'books.publisher_id', '=', 'publishers.id')
            ->select('books.*', 'publishers.name')
            ->get();

        $data17 = Book::where('author_id', 12)->count();

        $data18 = Book::where('price', '>', 15000)->get();

        $data19 = Book::where('publisher_id', 19)
        ->where('qty', '>', 13)
        ->get();

        $data20 = Member::whereMonth('created_at', 9)->get();
        // return $data20;
        return view('home');
    }
}
