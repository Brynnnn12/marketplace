<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'price',
        'image',
        'file_path',
    ];
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
