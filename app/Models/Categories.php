<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Categories extends Model
{

    use ModelTree, AdminBuilder;

    protected $fillable = ['parent_id', 'order', 'title', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('categories');

        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('order');
        $this->setTitleColumn('title');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'category_id', 'id');
    }
}
