<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Rules\Sum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CafateriaPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $categoryIds = $this->getCategoryData();
        $categoryCounter = count($categoryIds);
        $categoryIdsString = implode(",",$categoryIds->pluck('id')->toArray());

        return [
            'cafateria' => ['required','array','size:12', $this->totalAmount() , $this->totalAmountByCategory()],
            'cafateria.*' => ['required','array:month,categories','size:2'],
            'cafateria.*.month' => ['required','numeric','between:1,12'],
            'cafateria.*.categories' => ['required','array','size:'.$categoryCounter],
            'cafateria.*.categories.*' => ['required','array:category,amount','size:2'],
            'cafateria.*.categories.*.amount' => ['required','numeric','lte:200000','gte:0'],
            'cafateria.*.categories.*.category' => ['required','numeric','in:'.$categoryIdsString]
        ];
    }

    protected function getCategoryData(){
        return Category::all('id');
    }

    protected function totalAmount(){
        return function($attribute, $value, $fail){
            $sum = 0;
            foreach ($value as $month){
                foreach ($month['categories'] as $category){
                    $sum += $category['amount'];
                }
            }

            if ($sum > 400000){
                $fail('A teljes összeg nem lehet nagyobb mint 400.000 Ft.');
            }
        };
    }

    protected function totalAmountByCategory(){
        return  function($attribute, $value, $fail){
            $sum = [];
            foreach ($value as $month){
                foreach ($month['categories'] as $category){
                    if (!isset($sum[$category['category']])) $sum[$category['category']] = 0;

                    $sum[$category['category']] += $category['amount'];
                }
            }

            if (max($sum) > 200000){
                $fail('Egy kategóriában nem lehet az összeg nagyobb mint 200.000 Ft.');
            }
        };
    }
}
