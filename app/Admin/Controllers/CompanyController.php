<?php

namespace App\Admin\Controllers;

use App\Models\Company;
use App\Models\PaymentMethods;
use App\Models\PaymentTerms;
use App\Models\PriceLists;
use App\Models\Route;
use App\Models\Tax;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CompanyController extends Controller
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
            $content->header('Customer');
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
            $content->header('Customer');
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
            $content->header('Customer');
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
        return Admin::grid(Company::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->company_name('Name');
            $grid->company_code('Code');
            $grid->address_line1('Address Line 2');
            $grid->city('City');
            $grid->phone_number('Phone');

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->like('status', 'Status');
                $filter->like('company_name', 'Company Name');
                $filter->like('phone_number', 'Phone');
            });

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Company::class, function (Form $form) {

            $form->tab('Company info', function ($form) {

                $form->display('id', 'ID');
                $form->text('company_name', 'Name')->rules('required');
                $form->text('company_code', 'Code')->rules('required');
                $form->image('logo');
                $form->textarea('description');
                $form->text('address_line1', 'Address Line 1')->rules('required');
                $form->text('address_line2', 'Address Line 2');
                $form->text('suburb', 'Suburb');
                $form->text('city', 'City')->rules('required');
                $form->text('post_code', 'Post Code');
                $form->text('tax_number');
                $form->select('route_id', 'Routes')->rules('required')->options(Route::all()->pluck('route_name', 'id'));

                $activeArray = ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'];
                $form->select('status', 'Status')->options($activeArray)->default('ACTIVE');

            })->tab('Contact Details', function ($form) {

                $form->text('phone_number')->rules('required');
                $form->text('fax_number');
                $form->text('website');
                $form->email('email_address');

            })->tab('Finance Details', function ($form) {
                $form->select('default_price_list_id', 'Default Price List')->rules('required')->options(PriceLists::all()->pluck('name', 'id'));
                $form->select('default_tax_type_id', 'Default Tax Type')->rules('required')->options(Tax::all()->pluck('name', 'id'));
                $form->select('default_payment_term_id', 'Default Payment Term')->rules('required')->options(PaymentTerms::all()->pluck('name', 'id'));
                $form->select('default_payment_method_id', 'Default Payment Method')->rules('required')->options(PaymentMethods::all()->pluck('name', 'id'));
                $form->decimal('discount_rate', 'Discount Rate');
                $form->currency('minimum_order_value', 'Minimum Order Value');
            });

        });
    }
}
