<?php

namespace App\Http\Controllers\Mainstay\DashBoard;

use Illuminate\Http\Request;

class DashboardController
{
    public function DashBoardAdmin()
    {
        return view('admin.dashboard.DashBoardPage');
    }
}
