<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;

class CategoryController extends Controller
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
            $content->header('Categories');
            $content->description(trans('admin.list'));
            $content->body(Categories::tree());
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
            $content->header('Categories');
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
            $content->header('Categories');
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }

    public function tree()
    {
        return Admin::tree(Categories::class, function (Tree $tree) {
            $tree->query(function ($model) {
                return $model->where('status', 'ACTIVE');
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Categories::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('parent_id', 'Parent Id')->options(Categories::all()->pluck('title', 'id'));
            $form->number('order', 'Order')->rules('required');
            $form->text('title', 'Title')->rules('required');

            $activeArray = ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'];
            $form->select('status', 'Status')->options($activeArray)->default('ACTIVE');

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}
