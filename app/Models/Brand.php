<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'brands';

    public function books()
    {
        return $this->hasMany(Book::class, 'brand_id', 'id');
    }
}
