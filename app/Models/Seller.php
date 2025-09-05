<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Seller extends Model
{



    protected $table = 'sellers';

    protected $fillable = [
        'user_id',
        'store_name',
        'store_description',
        'store_logo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Product::class, 'seller_id', 'id', 'id', 'id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.product_id', '=', DB::raw('products.id'));
    }

    public function totalSales()
    {
        return $this->orders()->where('status', 'completed')->sum('total_amount');
    }
}
