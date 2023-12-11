<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    function index()
    {
        // SmallBox
        $total_buku = Book::count();
        $total_anggota = Member::count();
        $total_peminjaman = Transaction::count();
        $total_penerbit = Publisher::count();

        // Chart Donut
        $data_donut = Book::select(DB::raw('COUNT(books.publisher_id) as total'))->groupBy('books.publisher_id')->orderBy('books.publisher_id', 'asc')->pluck('total');
        $label_donut = Publisher::select('publishers.id', 'publishers.name')->orderBy('publishers.id', 'asc')->join('books', 'books.publisher_id', '=', 'publishers.id')->groupBy('publishers.id', 'publishers.name')->pluck('publishers.name');

        // Chart Bar
        $label_bar = ['Peminjaman', 'Pengembalian'];
        $data_bar = [];

        foreach ($label_bar as $key => $value) {
            $data_bar[$key]['label'] = $label_bar[$key];
            $data_bar[$key]['backgroundColor'] = $key == 0 ? 'rgba(60,141,188,0.9)' : 'rgba(210,214,222,1)';
            $data_month = [];
            foreach (range(1, 12) as $month) {
                if ($key == 0) {
                    $data_month[] = Transaction::select(DB::raw('count(*) as total'))->whereMonth('date_start', $month)->first()->total;
                } else {
                    $data_month[] = Transaction::select(DB::raw('count(*) as total'))->whereMonth('date_end', $month)->first()->total;
                }
            }
            $data_bar[$key]['data'] = $data_month;
        }

        return view('admin.dashboard.index', compact('total_buku', 'total_anggota', 'total_peminjaman', 'total_penerbit', 'data_donut', 'label_donut', 'data_bar'));
    }

    function test_spatie()
    {
        $role = Role::create(['name' => 'petugas']);
        $permission = Permission::create(['name' => 'index transaction']);

        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $user = auth()->user();
        $user->assignRole('petugas');

        $user = User::with('roles')->get();
        return $user;
    }
}
