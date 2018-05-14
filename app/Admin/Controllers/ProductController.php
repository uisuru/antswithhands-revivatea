<?php

namespace App\Admin\Controllers;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Product;
use App\Models\ProductTypes;
use App\Models\Suppliers;
use Encore\Admin\Form;
use Encore\Admin\Tree;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ProductController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Products');
            $content->description(trans('admin.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Products');
            $content->description(trans('admin.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
         return Admin::content(function (Content $content) {
            $content->header('Products');
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Product::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->product_name('Name')->editable();
            $grid->sku('Sku')->editable();
            $grid->column('productTypes.type')->getLabel('Type');
            //$grid->column('categories.title')->getLabel('Category');
            $grid->status('status');

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->created_at();
            //$grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Product::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text('product_name', 'Name')->rules('required');
            $form->text('sku')->rules(function ($form) {
                if (!$id = $form->model()->id) {
                    return 'unique:product,sku|required';
                }
            });
            $form->textarea('description', 'Description');
            $form->select('product_type_id', 'Product Type')->
                options(ProductTypes::all()->pluck('type', 'id'))->rules('required');
            $form->select('supplier_id', 'Supplier')->
                options(Suppliers::all()->pluck('name', 'id'));
            /*$form->select('brand_id', 'Brand')->
                options(Brands::all()->pluck('type', 'id'));*/
            $form->decimal('cost', 'Cost');
            //$form->decimal('buying_price', 'Buying Price');
            //$form->decimal('wholesale_price', 'Wholesale Price');
            $form->decimal('retail_price', 'Retail Price')->rules('required');

            $stockArray = ['YES'=>'YES','NO'=>'NO'];
            $form->select('manage_stock_level', 'Manage Stock')
                ->options($stockArray)->default('YES');

            //$form->decimal('opening_stock', 'Opening Stock');
            $form->decimal('re_order_level', 'Re order Level')->rules('required');
            /*$form->select('category_id', 'Category')->
                options(Categories::selectOptions());*/

            $activeArray = ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'];
            $form->select('status', 'Status')->options($activeArray)->default('ACTIVE');

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}
