<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index() {

        $months = [];
        $date = Carbon::now()->locale('hu');
        for ($i = 1; $i < 13; $i++){
            $date->month($i);
            $months[] = [
                'name'  => $date->monthName,
                'month' => $date->month
            ];
        }

        $categories = Category::all(['id','name']);

        return Inertia::render('Cafateria', [
            'monthNames' =>  $months,
            'categories' => $categories
        ]);
    }
}
