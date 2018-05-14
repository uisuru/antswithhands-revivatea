<?php

namespace App\Admin\Controllers;

use App\Models\Currency;
use App\Models\PriceLists;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PriceListsController extends Controller
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
            $content->header('Price');
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
            $content->header('Price');
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
            $content->header('Price');
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
        return Admin::grid(PriceLists::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('Name');
            $grid->code('Code');
            $grid->column('currency.name');
            $grid->rate('Rate');
            $grid->is_default('Is Default');
            $grid->status('status');

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->like('status', 'Status');
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
        return Admin::form(PriceLists::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text('name', 'Name')->rules('required');
            $form->text('code', 'Code')->rules('required');
            $form->select('currency_id', 'Currency')->rules('required')->options(Currency::all()->pluck('name', 'id'));
            $form->text('rate', 'Rate');

            $isDefaultArray = ['Yes'=>'Yes','No'=>'No'];
            $form->radio('is_default', 'Is Default')->options($isDefaultArray);

            $activeArray = ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'];
            $form->select('status', 'Status')->options($activeArray)->default('ACTIVE');

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}
