<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\SalesTransactionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesTransaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customers_id',
        'code',
        'sale_date',
        'total_price',
        'status',
        'paid_off',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(SalesTransactionItem::class, 'sales_transaction_id', 'id');
    }
}
