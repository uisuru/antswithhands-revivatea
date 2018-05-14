<?php

namespace App\Admin\Controllers;

use App\Models\Bincard;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{

    private $_product;
    private $_warehouse;
    /**
     * Index interface.
     *
     * @return Content
     */
    public function getBincard()
    {
        return Admin::content(function (Content $content) {
            $content->header('Bin Card');
            $content->description(trans('admin.list'));

            $products = Product::where('status', '=', 'ACTIVE')->get();
            $warehouses = Warehouse::where('status', '=', 'ACTIVE')->get();
            $data = ['products'=> $products, 'warehouses' => $warehouses];
            $content->body(view('admin.bincard', $data));
        });
    }

    public function postBincard(Request $request)
    {
        $this->_product = $request->input('product_name');
        $this->_warehouse = $request->input('warehouse');

        return Admin::content(function (Content $content) {
            $content->header('Bin Card');
            $content->description(trans('admin.list'));

            $records = Bincard::where(array('status' => 'ACTIVE',
                'product_id' => $this->_product,
                'warehouse_id' => $this->_warehouse))->get();
            $data = ['records'=> $records];
            $content->body(view('admin.bin_report', $data));
        });

    }

    public function getStock()
    {
        return Admin::content(function (Content $content) {
            $content->header('Stock');
            $content->description(trans('admin.list'));

            $products = Product::where('status', '=', 'ACTIVE')->get();
            $warehouses = Warehouse::where('status', '=', 'ACTIVE')->get();
            $data = ['products'=> $products, 'warehouses' => $warehouses];
            $content->body(view('admin.stock', $data));
        });
    }

    public function postStock(Request $request)
    {
        $this->_product = $request->input('product_name');
        $this->_warehouse = $request->input('warehouse');

        return Admin::content(function (Content $content) {
            $content->header('Stock Report');
            $content->description(trans('admin.list'));

            $records = DB::table('stock')
                ->join('product', 'product.id', '=', 'stock.product_id')
                ->where(array('product.status' => 'ACTIVE',
                    'warehouse_id' => $this->_warehouse))
                ->get();

            $data = ['records'=> $records];
            $content->body(view('admin.stock_report', $data));
        });

    }
}