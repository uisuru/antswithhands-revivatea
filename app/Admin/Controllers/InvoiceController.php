<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Stock;
use App\Models\Bincard;
use App\Models\InvoiceItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Invoice;
use Validator;

class InvoiceController extends Controller
{
    use ModelForm;
    private $invoice_id;
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Invoice');
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
        return Admin::grid(Invoice::class, function (Grid $grid) {
//id	invoice_id	customer_id	invoice_date	gross_amount	discount	tax_amount	net_amount	free_items	sales_rep	status	created_at	updated_at	invoice_balance_amount
            //$grid->id('ID')->sortable();
            $grid->invoice_id('Invoice ID')->sortable();
            $grid->customer_id('Customer ID')->sortable();
            $grid->invoice_date('Invoice Date')->sortable();
            $grid->gross_amount('Gross Amount')->sortable();
            $grid->status('Status');

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->append('<a href=/admin/auth/invoice/items/' . $actions->getKey() . '><i class="fa fa-eye"></i></a>');
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->like('invoice_id', 'Invoice ID');
                $filter->like('status', 'Status');
            });

            $grid->created_at();
            $grid->updated_at();
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

            $content->header('Invoice');
            $content->description('');

            $companys= Company::where('status', '=', 'ACTIVE')->get();
            $products= Product::where('status', '=', 'ACTIVE')->get();
            $data = ['companys'=> $companys,'products'=>$products];
            $content->body(view('admin.invoice', $data));
        });
    }
    public function submitPost (Request $request) {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|unique:invoice',
            'invoice_date' => 'required',
            'customer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('auth/invoice/create')
                ->withErrors($validator)
                ->withInput();
        }else {

            if ($request->input('_token')) {
                $inputPost = [];
                $inputKey = 0;
                foreach ($request->input('fields') as $key => $field) {
                    if (!is_null($field['qty']) || $field['qty'] > 0) {
                        $inputPost[$inputKey]['id'] = $field['product_name'];
                        $inputPost[$inputKey]['retail_price'] = $field['retail_price'];
                        $inputPost[$inputKey]['qty'] = $field['qty'];
                        $inputPost[$inputKey]['total'] = $field['retail_price']*$field['qty'];
                        //Preventing inserting fake price
                        $inputKey++;
                    }
                }

                $invoice = new Invoice();
                $invoice->invoice_id = $request->input('invoice_id');
                $invoice->customer_id = $request->input('customer_id');
                $invoice->invoice_date = $request->input('invoice_date');
                $gross_amount = 0;
                $invoice->gross_amount = $gross_amount;

                $invoice->discount = 0;
                $invoice->tax_amount = 0;
                $invoice->net_amount = 0;
                $invoice->free_items = 0;
                $invoice->sales_rep = 0;
                $invoice->status = 'ACTIVE';
                $invoice->save();

                $invoiceId = $invoice->id;

                foreach ($inputPost as $product) {
                    $gross_amount = $gross_amount + $product['total']*1;
                    //insert to invoice items
                    $invoiceItems = new InvoiceItems();

                    $invoiceItems->invoice_id = $invoiceId;
                    $invoiceItems->item_id = $product['id'];
                    $invoiceItems->qty = $product['qty'];
                    $invoiceItems->unit_price = $product['retail_price'];
                    $invoiceItems->total_price = $product['total'];
                    $invoiceItems->discount_rate = 0;
                    $invoiceItems->discount_amount = 0;
                    $invoiceItems->net_price = 0;
                    $invoiceItems->package_id = 0;
                    $invoiceItems->free_items = 0;
                    $invoiceItems->status = 'ACTIVE';

                    $invoiceItems->save();

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

                        $mutual_balance = $exists['mutual_balance'] - $product['qty'];
                        $actual_balance = $exists['actual_balance'] - $product['qty'];

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
                $invoice->where('status', 'ACTIVE')
                    ->where('id' ,$invoiceId)
                    ->update(['gross_amount' => $gross_amount]);

            } else {
                echo "Invalid Request";
            }
        }
    }
    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    /*public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Invoice');
            $content->description('');

            $content->body($this->form()->edit($id));
        });
    }*/

    /**
     * Index interface.
     *
     * @return Content
     */
    public function items($id)
    {
        $this->invoice_id = $id;
        return Admin::content(function (Content $content) {
            $content->header('Invoice No : ' . $this->invoice_id);
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
        return Admin::grid(InvoiceItems::class, function (Grid $grid) {

            $grid->model('Invoice ID')->where('invoice_id','=', $this->invoice_id);
            $grid->item_id('Item ID');
            //$grid->column('product.sku');
            //$grid->column('product.product_name');
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


    /**
     * Make a form builder.
     *
     * @return Form
     */
    /*protected function form()
    {
        return Admin::form(Invoice::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }*/
}
