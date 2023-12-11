<?php

use App\Models\Transaction;
use Carbon\Carbon;

function convert_date($value)
{
    return date('H:i:s - d M Y', strtotime($value));
}

function getOverdueTransactions()
{
    return Transaction::where('date_end', '<', now())->where('status', '!=', 1)->get();
}
