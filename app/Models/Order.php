<?php

namespace App\Models;

use App\Models\Orderitem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'tracking_no',
        'fullname',
        'email',
        'phone',
        'pincode',
        'address',
        'status_message',
        'payment_mode',
        'payment_id',
        'confirm',
        'seller_fullname',
        'seller_email',
        'seller_phone'


    ];

   /**
    * Get all of the comments for the Order
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function orderItems(): HasMany
   {
       return $this->hasMany(Orderitem::class, 'order_id', 'id');
   }
}
