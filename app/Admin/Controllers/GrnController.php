<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Grn;
use App\Models\GrnItems;
use App\Models\Stock;
use App\Models\Bincard;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Validator;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    use ModelForm;

    private $grnId;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Good Receive Number');
            $content->description(trans('admin.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Grn::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->grn_number('GRN Number');
            $grid->description('Description');
            $grid->status('status');

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->append('<a href=/admin/auth/grn/items/' . $actions->getKey() . '><i class="fa fa-eye"></i></a>');
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->like('grn_number', 'GRN Number');
                $filter->like('status', 'Status');
            });

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('Good Received Note');
            $content->description(trans('admin.list'));

            $products= Product::where('status', '=', 'ACTIVE')->get();
            $data = ['products'=> $products];
            $content->body(view('admin.grn', $data));
        });
    }

    public function submitPost (Request $request) {

        $validator = Validator::make($request->all(), [
            'grn_number' => 'required|unique:grn'
        ]);

        if ($validator->fails()) {
            return redirect('auth/grn/create')
                ->withErrors($validator)
                ->withInput();
        }else {

            if ($request->input('_token')) {
                $inputPost = [];
                $inputKey = 0;
                foreach ($request->input('fields') as $key => $field) {
                    if (!is_null($field['qty']) || $field['qty'] > 0) {
                        $inputPost[$inputKey]['id'] = $field['product_name'];
                        $inputPost[$inputKey]['qty'] = $field['qty'];

                        $inputKey++;
                    }
                }

                $grn = new Grn();
                $grn->grn_number = $request->input('grn_number');
                $grn->description = $request->input('description');
                $grn->warehouse_id = 1;

                $grn->save();
                $grnId = $grn->id;

                foreach ($inputPost as $product) {
                    //insert to grn items
                    $grnItems = new GrnItems();

                    $grnItems->grn_id = $grnId;
                    $grnItems->product_id = $product['id'];
                    $grnItems->warehouse_id = 1;
                    $grnItems->qty = $product['qty'];
                    $grnItems->status = 'ACTIVE';

                    $grnItems->save();

                    //get stock of main warehouse
                    $exists = Stock::where(array('product_id' => $product['id'], 'warehouse_id' => 1))->first();

                    $stock = new Stock();
                    if (!$exists) {
                        $stock->product_id = $product['id'];
                        $stock->warehouse_id = 1;
                        $stock->mutual_balance = $product['qty'];
                        $stock->actual_balance = $product['qty'];
                        $stock->status = 'ACTIVE';

                        $stock->save();
                    } else {

                        $mutual_balance = $exists['mutual_balance'] + $product['qty'];
                        $actual_balance = $exists['actual_balance'] + $product['qty'];

                        $stock->where('status', 'ACTIVE')
                            ->where(array('product_id' => $product['id'], 'warehouse_id' => 1))
                            ->update(['mutual_balance' => $mutual_balance, 'actual_balance' => $actual_balance]);


                    }

                    //add to bin card
                    $bincard = new Bincard();
                    $bincard->product_id = $product['id'];
                    $bincard->warehouse_id = 1;
                    $bincard->transaction_description = $request->input('description');
                    $bincard->credit = $product['qty'];
                    $bincard->debit = 0;
                    $bincard->status = 'ACTIVE';

                    $bincard->save();
                }


            } else {
                echo "Invalid Request";
            }
        }
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function items($id)
    {
        $this->grnId = $id;
        return Admin::content(function (Content $content) {
            $content->header('Good Receive Note - GRN No : ' . $this->grnId);
            $content->description(trans('admin.list'));
            $content->body($this->item_grid()->render());
        });

    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function item_grid()
    {
        return Admin::grid(GrnItems::class, function (Grid $grid) {

            $grid->model('GrnItems')->where('grn_id','=', $this->grnId);
            $grid->product_id('Product Id');
            $grid->column('product.sku');
            $grid->column('product.product_name');
            $grid->qty('Quantity');
            $grid->disableActions();
            $grid->disableCreateButton();

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->created_at();
            $grid->updated_at();
        });
    }

}
