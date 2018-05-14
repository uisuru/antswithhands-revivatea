<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Company extends Model
{

    protected $fillable = ['company_name', 'company_code','phone_number', 'default_price_list_id',
        'default_tax_type_id', 'default_payment_term_id', 'default_payment_method_id', 'discount_rate',
    'minimum_order_value', 'address_line1', 'city','location_longitude','location_latitude', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('company');

        parent::__construct($attributes);
    }

    public function priceLists()
    {
        return $this->belongsTo('App\Models\PriceList', 'default_price_list_id', 'id');
    }

    public function taxTypes()
    {
        return $this->belongsTo('App\Models\Tax', 'default_tax_type_id', 'id');
    }

    public function paymentMethods()
    {
        return $this->belongsTo('App\Models\PaymentMethods', 'default_payment_method_id', 'id');
    }

    public function paymentTerms()
    {
        return $this->belongsTo('App\Models\PaymentTerms', 'default_payment_term_id', 'id');
    }

    public function routes()
    {
        return $this->belongsTo('App\Models\Route', 'route_id', 'id');
    }
}
