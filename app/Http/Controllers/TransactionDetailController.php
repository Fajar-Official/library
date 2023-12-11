<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionDetailController extends Controller
{
    function api()
    {
        $transactions = TransactionDetail::select([
            'transaction_details.*',
            DB::raw('COUNT(transaction_details.transaction_id) as transaction_count'),
            DB::raw("DATEDIFF(transactions.date_end, transactions.date_start) as duration"),
            DB::raw('SUM(books.price) as total_price')
        ])
            ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->leftJoin('books', 'transaction_details.book_id', '=', 'books.id')
            ->groupBy('transaction_details.id');

        $datatables = datatables()->of($transactions)->addIndexColumn();

        return $datatables->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }
}
