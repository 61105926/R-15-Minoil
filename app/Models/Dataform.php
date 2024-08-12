<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataform extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'city',
        'chain',
        'room',
        'group',
        'line',
        'product',
        'expiration_date',
        'quantity',
    ];
    public function producto()
    {
        return $this->belongsTo(Product::class, 'product');
    }

    public function sala()
    {
        return $this->belongsTo(Rooms::class, 'room');
    }
}
