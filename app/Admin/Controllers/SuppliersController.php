<?php

namespace App\Admin\Controllers;

use App\Models\Suppliers;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SuppliersController extends Controller
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
            $content->header('Suppliers');
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
            $content->header('Suppliers');
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
            $content->header('Suppliers');
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
        return Admin::grid(Suppliers::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('Name');
            $grid->address('Address');
            $grid->city('City');
            $grid->phone_number('Phone');
            $grid->email_address('Email');
            $grid->contact_person('Contact');

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->like('name', 'Name');
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
        return Admin::form(Suppliers::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', 'Name')->rules('required');
            $form->text('address', 'Address')->rules('required');
            $form->text('city', 'City')->rules('required');
            $form->text('post_code', 'Post Code')->rules('required');
            $form->text('phone_number', 'Phone');
            $form->text('email_address', 'Email');
            $form->text('contact_person');

            $activeArray = ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'];
            $form->select('status', 'Status')->options($activeArray)->default('ACTIVE');

        });
    }
}
