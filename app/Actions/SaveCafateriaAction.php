<?php namespace App\Actions;

use App\Models\Cafateria;

class SaveCafateriaAction
{
    public function call(array $data){

        foreach ($data['cafateria'] as $monthData){
            foreach ($monthData['categories'] as $categoryData){
                Cafateria::updateOrCreate(
                    ['month' => $monthData['month'], 'category_id' => $categoryData['category']],
                    ['amount' => $categoryData['amount']]
                );
            }
        }

        return true;
    }
}
