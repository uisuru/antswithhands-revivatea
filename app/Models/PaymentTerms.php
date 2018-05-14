<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class PaymentTerms extends Model
{

    protected $fillable = ['name', 'due_in_days', 'is_default', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('payment_terms');

        parent::__construct($attributes);
    }

    public function company()
    {
        return $this->hasMany('App\Models\Company', 'default_payment_term_id', 'id');
    }
}
