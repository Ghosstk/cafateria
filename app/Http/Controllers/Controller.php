<?php

namespace App\Http\Controllers;

use App\Actions\SaveCafateriaAction;
use App\Http\Requests\CafateriaPostRequest;
use App\Models\Cafateria;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;
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

        $cafaterias = Cafateria::all(['category_id','month','amount'])->keyBy(function ($item){
           return $item->month.'-'.$item->category_id;
        });

        return Inertia::render('Cafateria', [
            'monthNames' =>  $months,
            'categories' => $categories,
            'cafateria' => $cafaterias
        ]);
    }

    public function store(CafateriaPostRequest $request, SaveCafateriaAction $action){

        $action->call($request->validated());

        return Redirect::route('cafateria.index');
    }

    public function exportCSV()
    {
        $filename = "cafateria.csv";
        $cafaterias = Cafateria::with('category:id,name')->get(['month','amount','category_id']);

        $downloadCallback = function () use ($cafaterias){
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Hónap','Kategória','Mennyiség']);

            $date = Carbon::now()->locale('hu');
            foreach ($cafaterias as $caf){
                fputcsv($file, [
                    strtolower($date->month($caf['month'])->monthName),
                    strtolower($caf['category']['name']),
                    $caf['amount']
                ]);
            }

            fclose($file);
        };

        return response()->stream($downloadCallback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        ]);
    }
}
