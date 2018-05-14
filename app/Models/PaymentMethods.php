<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class PaymentMethods extends Model
{

    protected $fillable = ['name', 'is_default', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('payment_methods');

        parent::__construct($attributes);
    }

    public function company()
    {
        return $this->hasMany('App\Models\Company', 'default_payment_method_id', 'id');
    }
}
