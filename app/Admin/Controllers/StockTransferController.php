<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Bincard;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class StockTransferController extends Controller
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
            $content->header('Stock Transfer');
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
            $content->header('Stock Transfer');
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
            $content->header('Stock Transfer');
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
        return Admin::grid(StockTransfer::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('warehouseFrom.name');
            $grid->column('warehouseTo.name');
            $grid->column('product.product_name');
            $grid->qty('Quantity');
            $grid->status('status');

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
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
        return Admin::form(StockTransfer::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('from_warehouse_id', 'From Warehouse')->rules('required')->options(Warehouse::all()->pluck('name', 'id'));
            $form->select('to_warehouse_id', 'To Warehouse')->rules('required')->options(Warehouse::all()->pluck('name', 'id'));
            $form->select('product_id', 'Product')->rules('required')->options(Product::all()->pluck('product_name', 'id'));
            $form->number('qty')->rules('required|');
            $form->text('description', 'Description');

            $activeArray = ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'];
            $form->select('status', 'Status')->options($activeArray)->default('ACTIVE');

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));

            $form->saving(function (Form $form) {
                $fromExists = Stock::where(array('product_id' => $_POST['product_id'], 'warehouse_id' => $_POST['from_warehouse_id']))->first();
                if($fromExists && $fromExists['mutual_balance'] < $_POST['qty']) {
                    return back()->with(compact('Requested transfer quantity not available.'));
                }else{
                    $fromMutualStock = $fromExists['mutual_balance'] - $_POST['qty'];
                    $fromActualStock = $fromExists['actual_balance'] - $_POST['qty'];

                    $toExists = Stock::where(array('product_id' => $_POST['product_id'], 'warehouse_id' => $_POST['to_warehouse_id']))->first();
                    $stock = new Stock();

                    $stock->where('status', 'ACTIVE')
                        ->where(array('product_id' => $_POST['product_id'], 'warehouse_id' => $_POST['from_warehouse_id']))
                        ->update(['mutual_balance' => $fromMutualStock, 'actual_balance' => $fromActualStock]);


                    if(!$toExists) {
                        $toMutualStock = $toExists['mutual_balance'] + $_POST['qty'];
                        $toActualStock = $toExists['actual_balance'] + $_POST['qty'];

                        $toStock = new Stock();
                        $toStock->product_id = $_POST['product_id'];
                        $toStock->warehouse_id = $_POST['to_warehouse_id'];
                        $toStock->mutual_balance = $toMutualStock;
                        $toStock->actual_balance = $toActualStock;
                        $toStock->status = 'ACTIVE';

                        $toStock->save();

                    }else{
                        $toStock = new Stock();
                        $toMutualStock = $_POST['qty'];
                        $toActualStock = $_POST['qty'];

                        $toStock->where('status', 'ACTIVE')
                            ->where(array('product_id' => $_POST['product_id'], 'warehouse_id' => $_POST['to_warehouse_id']))
                            ->update(['mutual_balance' => $toMutualStock, 'actual_balance' => $toActualStock]);
                    }

                    //add to bin card
                    $fromBincard = new Bincard();
                    $fromBincard->product_id = $_POST['product_id'];
                    $fromBincard->warehouse_id = $_POST['from_warehouse_id'];
                    $fromBincard->transaction_description = $_POST['description'];
                    $fromBincard->credit = 0;
                    $fromBincard->debit = $_POST['qty'];
                    $fromBincard->status = 'ACTIVE';

                    $fromBincard->save();

                    $toBincard = new Bincard();
                    $toBincard->product_id = $_POST['product_id'];
                    $toBincard->warehouse_id = $_POST['to_warehouse_id'];
                    $toBincard->transaction_description = $_POST['description'];
                    $toBincard->credit = $_POST['qty'];
                    $toBincard->debit = 0;
                    $toBincard->status = 'ACTIVE';

                    $toBincard->save();

                }


            });
        });
    }
}
