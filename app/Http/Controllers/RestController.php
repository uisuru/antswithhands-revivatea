<?php

namespace App\Http\Controllers;


use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use JWTAuth;
use App\Repository\UserTransformer;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Validator;
use Auth;
use DB;
Use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\CoreConfigData;
use \Illuminate\Http\Response as Res;
use Tymon\JWTAuth\Exceptions\JWTException;

class RestController extends ApiController
{
    /**
     * @var \App\Repository\UserTransformer
     * */
    protected $userTransformer;

    public function __construct(userTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @description: Api user authenticate method
     * @param: email, password
     * @return: Json String response
     */
    public function authenticate(Request $request)
    {
        $rules = array(
            'username' => 'required',
            'password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        } else {
            $user = Administrator::where('username', $request['username'])->first();

            $api_token = $user->remember_token;
            if ($api_token == NULL) {
                return $this->_login($request['username'], $request['password']);
            }

            if ($user) {
                try {
                    $user = JWTAuth::toUser($user);
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'User Authenticated',
                        'user' => $this->userTransformer->transform($user)
                    ]);
                } catch (JWTException $e) {
                    $user->remember_token = NULL;
                    $user->save();
                    return $this->respondInternalError("Login Unsuccessful. An error occurred while performing an action!");
                }
            } else {
                return $this->respondWithError("Invalid Email or Password");
            }
        }
    }

    private function _login($username, $password)
    {
        $credentials = ['username' => $username, 'password' => $password];

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->respondWithError("User does not exist!");
        }

        $user = JWTAuth::toUser($token);

        $user->remember_token = $token;
        $user->save();
        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Login successful!',
            'data' => $this->userTransformer->transform($user)
        ]);
    }

    /**
     * @description: Api user logout method
     * @param: null
     * @return: Json String response
     */
    public function logout($api_token)
    {
        try {
            $user = JWTAuth::toUser($api_token);
            $user->remember_token = NULL;
            $user->save();

            JWTAuth::setToken($api_token)->invalidate();

            $this->setStatusCode(Res::HTTP_OK);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Logout successful!',
            ]);

        } catch (JWTException $e) {
            return $this->respondInternalError("An error occurred while performing an action!");
        }
    }

    /**
     * Api get products
     */
    public function getProducts(Request $request)
    {
        try {
            /*if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {*/
            // get all products
            $products_data = DB::table('product')->get();

            foreach ($products_data as $product) {
                $products[] = array(
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'sku' => $product->sku,
                    'retail_price' => $product->retail_price,

                );
            }

            $id = $request['id'];

            // If the id parameter doesn't exist return all the users

            if ($id === NULL) {
                // Check if the users data store contains users (in case the database result returns NULL)
                if ($products) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Product List',
                        'data' => $products
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            // Find and return a single record for a particular user.

            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                return $this->respondInternalError('Id Not found');
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $product = NULL;

            if (!empty($products)) {
                foreach ($products as $key => $value) {
                    if (isset($value['id']) && $value['id'] == $id) {
                        $product = $value;
                    }
                }
            }

            if (!empty($product)) {
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Product List',
                    'data' => $product
                ]);
            } else {
                return $this->respondNotFound('Records not found');
            }
            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }


    /**
     * Api get Customers
     */
    public function getCustomers(Request $request)
    {
        try {
            /* if (!$user = JWTAuth::parseToken()->authenticate()) {
                 return response()->json(['user_not_found'], 404);
             } else {*/
            // get all customers
            $customer_data = DB::table('company')->get();

            foreach ($customer_data as $customer) {
                $customers[] = array(
                    'id' => $customer->id,
                    'company_name' => $customer->company_name,

                );
            }

            $id = $request['id'];

            // If the id parameter doesn't exist return all the users
            if ($id === NULL) {
                // Check if the customer data store contains users (in case the database result returns NULL)
                if ($customers) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Customer List',
                        'data' => $customers
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            // Find and return a single record for a particular user.

            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                return $this->respondInternalError('Id Not found');
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $customer = NULL;

            if (!empty($customers)) {
                foreach ($customers as $key => $value) {
                    if (isset($value['id']) && $value['id'] == $id) {
                        $customer = $value;
                    }
                }
            }

            if (!empty($customer)) {
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Customer List',
                    'data' => $customer
                ]);
            } else {
                return $this->respondNotFound('Records not found');
            }
            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }

    /**
     * Api get Packages
     */
    public function getPackages(Request $request)
    {
        try {
            /* if (!$user = JWTAuth::parseToken()->authenticate()) {
                 return response()->json(['user_not_found'], 404);
             } else {*/
            // get all customers
            $packages_data = DB::table('packages')->get();

            foreach ($packages_data as $package) {
                $packages[] = array(
                    'id' => $package->id,
                    'name' => $package->package_name,
                    'discount' => $package->package_discount,
                    'free' => $package->free_items,
                    'minimum_qty' => $package->minimum_qty,

                );
            }

            $id = $request['id'];

            // If the id parameter doesn't exist return all the users
            if ($id === NULL) {
                // Check if the customer data store contains users (in case the database result returns NULL)
                if ($packages) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Package List',
                        'data' => $packages
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            // Find and return a single record for a particular user.

            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                return $this->respondInternalError('Id Not found');
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $package = NULL;

            if (!empty($packages)) {
                foreach ($packages as $key => $value) {
                    if (isset($value['id']) && $value['id'] == $id) {
                        $package = $value;
                    }
                }
            }

            if (!empty($package)) {
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Package List',
                    'data' => $package
                ]);
            } else {
                return $this->respondNotFound('Records not found');
            }
            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }

    /**
     * Api get Invoices
     */
    public function getInvoices(Request $request)
    {
        try {
            /* if (!$user = JWTAuth::parseToken()->authenticate()) {
                 return response()->json(['user_not_found'], 404);
             } else {*/
            // get all customers
            $table_data = DB::table('invoice')->get();
            $records = [];
            foreach ($table_data as $data) {
                $records[] = array(
                    'id' => $data->id,
                    'invoice_id' => $data->invoice_id,
                    'invoice_date' => $data->invoice_date,
                    'gross_amount' => $data->gross_amount,
                    'discount' => $data->discount,
                    'net_amount' => $data->net_amount,
                );
            }

            $id = $request['id'];

            // If the id parameter doesn't exist return all the users
            if ($id === NULL) {
                // Check if the records data store contains users (in case the database result returns NULL)
                if ($records) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Invoice List',
                        'data' => $records
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            // Find and return a single record for a particular record.

            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                return $this->respondInternalError('Id Not found');
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $record = NULL;

            if (!empty($records)) {
                foreach ($records as $key => $value) {
                    if (isset($value['id']) && $value['id'] == $id) {
                        $record = $value;
                    }
                }
            }

            if (!empty($record)) {
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Package List',
                    'data' => $record
                ]);
            } else {
                return $this->respondNotFound('Records not found');
            }
            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }


    /**
     * Api get Invoices
     */
    public function getRoutes(Request $request)
    {
        try {
            // get all routes
            $table_data = DB::table('route')->get();
            $records = [];
            foreach ($table_data as $data) {
                $records[] = array(
                    'id' => $data->id,
                    'route_name' => $data->route_name,

                );
            }

            $id = $request['id'];

            // If the id parameter doesn't exist return all the routes
            if ($id === NULL) {
                // Check if the records data store contains route (in case the database result returns NULL)
                if ($records) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Invoice List',
                        'data' => $records
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            // Find and return a single record for a particular record.

            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                return $this->respondInternalError('Id Not found');
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $record = NULL;

            if (!empty($records)) {
                foreach ($records as $key => $value) {
                    if (isset($value['id']) && $value['id'] == $id) {
                        $record = $value;
                    }
                }
            }

            if (!empty($record)) {
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Routes List',
                    'data' => $record
                ]);
            } else {
                return $this->respondNotFound('Records not found');
            }
            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }


    /**
     * Api get Customers by Route Id
     */
    public function getCustomersByRouteId(Request $request)
    {
        try {
            // get all customers by route id
            $route_id = $request['route_id'];
            $customer_data = DB::table('company')->where('route_id', $route_id)->get();
            foreach ($customer_data as $customer) {
                $customers[] = array(
                    'id' => $customer->id,
                    'company_name' => $customer->company_name,

                );
            }

            $id = $request['id'];

            // If the id parameter doesn't exist return all the customers
            if ($id === NULL) {
                // Check if the customer data store contains customers (in case the database result returns NULL)
                if ($customers) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Customer List',
                        'data' => $customers
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            // Find and return a single record for a particular user.

            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                return $this->respondInternalError('Id Not found');
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $customer = NULL;

            if (!empty($customers)) {
                foreach ($customers as $key => $value) {
                    if (isset($value['id']) && $value['id'] == $id) {
                        $customer = $value;
                    }
                }
            }

            if (!empty($customer)) {
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Customer List',
                    'data' => $customer
                ]);
            } else {
                return $this->respondNotFound('Records not found');
            }
            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }

    /**
     * Api get Invoices By Customer
     */
    public function getInvoicesByCustomer(Request $request)
    {
        try {
            // get all invoices by customer
            $customer_id = $request['customer_id'];
            $table_data = DB::table('invoice')->where('customer_id', $customer_id)->get();
            $records = [];
            foreach ($table_data as $data) {
                $records[] = array(
                    'id' => $data->id,
                    'invoice_id' => $data->invoice_id,
                    'invoice_date' => $data->invoice_date,
                    'gross_amount' => $data->gross_amount,
                    'discount' => $data->discount,
                    'net_amount' => $data->net_amount,

                );
            }

            $id = $request['id'];

            // If the id parameter doesn't exist return all the users
            if ($id === NULL) {
                // Check if the records data store contains users (in case the database result returns NULL)
                if ($records) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Invoice List',
                        'data' => $records
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            // Find and return a single record for a particular record.

            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                return $this->respondInternalError('Id Not found');
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $record = NULL;

            if (!empty($records)) {
                foreach ($records as $key => $value) {
                    if (isset($value['id']) && $value['id'] == $id) {
                        $record = $value;
                    }
                }
            }

            if (!empty($record)) {
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Customers List',
                    'data' => $record
                ]);
            } else {
                return $this->respondNotFound('Records not found');
            }
            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }

    /**
     * Api get Invoices
     */
    public function getInvoiceItems(Request $request)
    {
        try {
            /* if (!$user = JWTAuth::parseToken()->authenticate()) {
                 return response()->json(['user_not_found'], 404);
             } else {*/
            // get all customers

            $id = $request['id'];
            $table_data = DB::table('invoice_items')->where('invoice_id', $id)->get();

            foreach ($table_data as $data) {
                $records[] = array(
                    'item_id' => $data->item_id,
                    'qty' => $data->qty,
                    'unit_price' => $data->unit_price,
                    'total_price' => $data->total_price,
                    'discount_rate' => $data->discount_rate,
                    'discount_amount' => $data->discount_amount,
                    'net_price' => $data->net_price,
                    'free_items' => $data->free_items,

                );
            }


            // If the id parameter doesn't exist return all the records
            if ($id !== NULL) {
                // Check if the records data store contains users (in case the database result returns NULL)
                if ($records) {
                    // Set the response and exit
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Invoice Items',
                        'data' => $records
                    ]);
                } else {
                    // Set the response and exit
                    return $this->respondNotFound('Records not found');
                }
            }

            //}
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }

    /**
     * Create Invoices
     */
    public function saveInvoices(Request $request)
    {

        $rules = array(
            'customer_id' => 'required',
            'invoice_date' => 'required',
            'gross_amount' => 'required',
            'discount' => 'required',
            'tax_amount' => 'required',
            'net_amount' => 'required',
            'free_items' => 'required',
            'sales_rep' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        $lastValue = CoreConfigData::where('code', 'INV')->first();
        $invoiceId = $lastValue['value'] + 1;

        if ($validator->fails()) {
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        } else {

            $invoice = new Invoice();
            $invoice->invoice_id = $invoiceId;
            $invoice->customer_id = $request['customer_id'];
            $invoice->invoice_date = $request['invoice_date'];
            $invoice->gross_amount = $request['gross_amount'];
            $invoice->discount = $request['discount'];
            $invoice->tax_amount = $request['tax_amount'];
            $invoice->net_amount = $request['net_amount'];
            $invoice->free_items = $request['free_items'];
            $invoice->sales_rep = $request['sales_rep'];
            $invoice->invoice_balance_amount = $request['net_amount'];
            $invoice->status = 'ACTIVE';

            $invoice->save();


            foreach ($request['items'] as $item) {
                $invoiceItems = new InvoiceItems();

                $invoiceItems->invoice_id = $invoiceId;
                $invoiceItems->item_id = $item['item_id'];
                $invoiceItems->qty = $item['qty'];
                $invoiceItems->package_id = $item['package_id'];
                $invoiceItems->free_items = $item['free_items'];
                $invoiceItems->unit_price = $item['unit_price'];
                $invoiceItems->total_price = $item['total_price'];
                $invoiceItems->discount_rate = $item['discount_rate'];
                $invoiceItems->discount_amount = $item['discount_amount'];
                $invoiceItems->net_price = $item['net_price'];
                $invoiceItems->status = $item['status'];

                $invoiceItems->save();
            }

            CoreConfigData::where('code', '=', 'INV')->update(array('value' => $invoiceId));

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Invoice save',
                'data' => $invoiceId
            ]);

        }

    }

    /**
     * Create Customers
     */
    public function saveCustomers(Request $request)
    {

        $rules = array(
            'company_name' => 'required',
            //'company_code' => 'required',
            //'phone_number' => 'required',
            //'discount' => 'required',
            //'address_line1' => 'required',
            //'city' => 'required',
            //'location_latitude' => 'required',
            //'location_latitude' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        } else {
            $company = new Company();
            $company->company_name = $request['company_name'];;
            $company->company_code = $request['company_code'];
            $company->tax_number = $request['tax_number'];
            $company->phone_number = $request['phone_number'];
            $company->fax_number = $request['fax_number'];
            $company->website = $request['website'];
            $company->email_address = $request['email_address'];
            $company->description = $request['description'];
            $company->default_price_list_id = $request['default_price_list_id'];
            $company->default_tax_type_id = $request['default_tax_type_id'];
            $company->default_payment_term_id = $request['default_payment_term_id'];
            $company->default_payment_method_id = $request['default_payment_method_id'];
            $company->discount_rate = $request['discount_rate'];
            $company->minimum_order_value = $request['minimum_order_value'];
            $company->address_line1 = $request['address_line1'];
            $company->address_line2 = $request['address_line2'];
            $company->city = $request['city'];
            $company->post_code = $request['post_code'];
            $company->location_latitude = ($request['location_latitude'] == '') ? 0 : $request['location_latitude'];
            $company->location_longitude = ($request['location_longitude'] == '') ? 0 : $request['location_longitude'];
            $company->route_id = ($request['route_id'] == '') ? 0 : $request['route_id'];

            $company->status = 'ACTIVE';

            $company->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Customer save',
                'data' => $company->id
            ]);

        }

    }

    /**
     * Api get Payment Methods
     */
    public function getPaymentMethods(Request $request)
    {
        try {
            // get all payment methods
            $payment_methods = array(
                ['code' => 'cash', 'title' => 'Cash'],
                ['code' => 'cheque', 'title' => 'Cheque'],
            );

            if ($payment_methods) {
                // Set the response and exit
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Payment Methods List',
                    'data' => $payment_methods
                ]);
            } else {
                // Set the response and exit
                return $this->respondNotFound('Records not found');
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

    }

    /**
     * Create Payment
     */
    public function savePayments(Request $request)
    {

        $rules = array(
            'payment_code' => 'required',
            'invoice_number' => 'required',
            'paid_amount' => 'required',
            'cheque_number' => 'required_if_attribute:payment_code,==,cheque',
            'bank_code' => 'required_if_attribute:payment_code,==,cheque',
            'branch_code' => 'required_if_attribute:payment_code,==,cheque',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        } else {

            $payment = new Payment();
            $payment->invoice_id = $request['invoice_id'];
            $payment->invoice_number = $request['invoice_number'];
            $payment->payment_code = $request['payment_code'];
            $payment->paid_amount = $request['paid_amount'];
            $payment->cheque_number = $request['cheque_number'];
            $payment->bank_code = $request['bank_code'];
            $payment->branch_code = $request['branch_code'];
            $payment->status = 'ACTIVE';

            $payment->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Payment save',
                'data' => ''
            ]);

        }

    }
}