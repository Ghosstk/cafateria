<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafateria extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'amount',
        'category_id',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
