<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Member;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengecek apakah pengguna memiliki peran 'petugas'
        if (auth()->user()->role('petugas')) {
            return view('admin.transaction.index');
        } else {
            // Menampilkan halaman error 403 jika pengguna tidak memiliki peran 'petugas'
            abort(403);
        }
    }

    public function api(Request $request)
    {
        $transactions = Transaction::with('transaction_detail', 'member')
            ->select([
                'transactions.*',
                DB::raw('TIMESTAMPDIFF(DAY, date_start, date_end) AS duration'),
                DB::raw('(SELECT COUNT(*) FROM transaction_details WHERE transaction_details.transaction_id = transactions.id) as transaction_count'),
                DB::raw('(SELECT SUM(price) FROM transaction_details INNER JOIN books ON transaction_details.book_id = books.id WHERE transaction_details.transaction_id = transactions.id) as total_price'),
            ]);

        if ($request->has('status')) {
            $transactions->where('status', $request->status);
        }

        if ($request->has('date_start')) {
            $transactions->whereDate('date_start', $request->date_start);
        }

        return datatables()->of($transactions)->addIndexColumn()->make(true);
    }


    public function create()
    {
        // Mengambil semua member dan buku yang memiliki qty lebih dari 0
        $members = Member::all();
        $books = Book::where('qty', '>', 0)->get();

        // Menampilkan view form pembuatan transaksi
        return view('admin.transaction.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        // Memvalidasi request transaksi
        // $this->validate($request, [
        //     'member_id' => 'required',
        //     'date_start' => 'required|date',
        //     'date_end' => 'required|date|after:date_start',
        //     'book_id' => 'required|array|min:1', // At least one book must be selected
        //     'status' => 'required|in:0,1', // Assuming status is either 0 or 1
        // ]);

        // Membuat transaksi baru
        $transaction = Transaction::create([
            'member_id' => $request->input('member_id'),
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
            'status' => 0,
        ]);

        // Mengupdate detail transaksi dan mengurangi jumlah buku
        $transaction->transaction_detail()->createMany(
            array_map(function ($bookId) {
                return ['book_id' => $bookId];
            }, $request->input('book_id'))
        );

        // Mengupdate jumlah buku
        Book::whereIn('id', $request->input('book_id'))->decrement('qty');

        // Redirect ke halaman index transaksi
        return Redirect::route('transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        // Mengambil semua member dan buku
        $members = Member::all();
        $books = Book::all();

        // Menampilkan view form edit transaksi
        return view('admin.transaction.edit', compact('transaction', 'members', 'books'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Memvalidasi request transaksi
        $this->validate($request, [
            'member_id' => 'required',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
            'book_id' => 'required|array|min:1', // At least one book must be selected
            'status' => 'required|in:0,1', // Assuming status is either 0 or 1
        ]);

        // Mengupdate transaksi
        $transaction->update([
            'member_id' => $request->input('member_id'),
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
            'status' => $request->input('status'),
        ]);

        // Mengupdate detail transaksi, menambah atau menghapus
        $transaction->transaction_detail()->delete();
        $transaction->transaction_detail()->createMany(
            array_map(function ($bookId) {
                return ['book_id' => $bookId];
            }, $request->input('book_id'))
        );

        // Mengupdate jumlah buku, menambah atau mengurangi
        Book::whereIn('id', $transaction->transaction_detail->pluck('book_id'))->increment('qty');
        Book::whereIn('id', $request->input('book_id'))->decrement('qty');

        // Jika status transaksi diubah menjadi 1 (selesai), maka tambahkan jumlah buku
        if ($request->input('status') == 1) {
            Book::whereIn('id', $request->input('book_id'))->increment('qty');
        }

        // Redirect ke halaman index transaksi dengan pesan sukses
        return Redirect::route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        try {
            // Mengambil ID buku yang terkait dengan transaksi
            $bookIds = $transaction->transaction_detail->pluck('book_id')->toArray();

            // Menghapus transaksi
            if ($transaction->delete()) {
                // Menambah jumlah buku hanya jika transaksi berhasil dihapus
                Book::whereIn('id', $bookIds)->increment('qty');

                // Menghapus detail transaksi yang terkait
                $transaction->transaction_detail()->delete();

                // Mengembalikan respons JSON jika request dari Vue.js
                if (request()->wantsJson()) {
                    return new JsonResponse(['message' => 'Transaction deleted successfully!']);
                }

                return Redirect::route('transactions.index')->with('success', 'Transaction deleted successfully!');
            } else {
                // Redirect ke halaman index transaksi dengan pesan error
                // Mengembalikan respons JSON jika request dari Vue.js
                if (request()->wantsJson()) {
                    return new JsonResponse(['message' => 'Failed to delete transaction!'], 500);
                }

                return Redirect::route('transactions.index')->with('error', 'Failed to delete transaction!');
            }
        } catch (\Exception $e) {
            // Tangani kesalahan
            // Mengembalikan respons JSON jika request dari Vue.js
            if (request()->wantsJson()) {
                return new JsonResponse(['message' => $e->getMessage()], 500);
            }

            return Redirect::route('transactions.index')->with('error', $e->getMessage());
        }
    }
}
