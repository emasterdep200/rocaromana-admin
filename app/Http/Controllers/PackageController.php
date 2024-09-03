<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\UserPurchasedPackage;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (!has_permissions('read', 'package')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        }
        $slider = Slider::select('id', 'image', 'sequence')->orderBy('sequence', 'ASC')->get();

        $category = Category::select('id', 'category')->where('status', 1)->get();
        $currency_symbol = Setting::where('type', 'currency_symbol')->pluck('data')->first();

        return view('packages.index', compact('slider', 'category', 'currency_symbol'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        if (!has_permissions('create', 'package')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {


            $package = new Package();


            $package->name = $request->name;
            $package->duration = isset($request->duration) ? $request->duration : 0;
            $package->price = $request->price;
            if (isset($request->typep)) {
                $package->property_limit =  $request->property_limit == NULL ? NULL : $request->property_limit;
            } else {
                $package->property_limit = 0;
            }

            if (isset($request->typel)) {
                $package->advertisement_limit =  $request->advertisement_limit == NULL ? NULL : $request->advertisement_limit;
            } else {
                $package->advertisement_limit = 0;
            }
            $package->ios_product_id = $request->ios_product_id;
            $package->type = $request->package_type;
            $package->extra = $request->extra;
            $package->save();

            ResponseService::successRedirectResponse("Package Added Successfully ");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'sequence');
        $order = $request->input('order', 'ASC');



        $sql = Package::orderBy($sort, $order);
        // dd($sql->toArray());


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('name', 'LIKE', "%$search%")->orwhere('duration', 'LIKE', "%$search%");
        }


        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }


        $res = $sql->get();
        // return $res;
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';
        $tempRow['type'] = '';
        $parameter_name_arr = [];


        foreach ($res as $row) {

            $tempRow = $row->toArray();

            $tempRow['property_limit'] = $row->property_limit == '' ?  "unlimited" : ($row->property_limit == 0 ? "Not Available" : $row->property_limit);
            $tempRow['advertisement_limit'] = $row->advertisement_limit == '' ? "unlimited" : ($row->advertisement_limit == 0 ? "Not Available" : $row->advertisement_limit);

            $operate = BootstrapTableService::editButton('', true, null, null, $row->id);

            $tempRow['operate'] = $operate;

            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if (!has_permissions('update', 'package')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $id = $request->edit_id;
            $name =  $request->edit_name;
            $duration =  $request->edit_duration;
            $price =  $request->edit_price;

            $package = Package::find($id);

            $package->name = $name;
            $package->duration = $duration;
            $package->price = $price;
            $package->extra = $request->extra;
            $package->property_limit = $request->property_limit === NULL ? NULL : $request->property_limit;
            $package->advertisement_limit = $request->advertisement_limit ===  NULL ? NULL : $request->advertisement_limit;
            $package->status = $request->status;
            $package->ios_product_id = $request->edit_ios_product_id;
            if (isset($request->edit_typep)) {
                $package->property_limit =  $request->property_limit == NULL ? NULL : $request->property_limit;
            } else {
                $package->property_limit = 0;
            }

            if (isset($request->edit_typel)) {
                $package->advertisement_limit =  $request->advertisement_limit == NULL ? NULL : $request->advertisement_limit;
            } else {
                $package->advertisement_limit = 0;
            }
            $package->type = $request->edit_package_type;

            $package->update();


            ResponseService::successRedirectResponse('Package Updated Successfully ');
        }
    }
    public function updateStatus(Request $request)
    {
        if (!has_permissions('update', 'package')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {
            Package::where('id', $request->id)->update(['status' => $request->status]);
            $response['error'] = false;
            return response()->json($response);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function get_user_package_list(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'sequence');
        $order = $request->input('order', 'ASC');



        $sql = UserPurchasedPackage::with('package')->with('customer')->orderBy($sort, $order);


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwherehas('customer', function ($q1) use ($search) {
                $q1->where('name', 'LIKE', "%$search%");
            })->orwherehas('package', function ($q1) use ($search) {
                $q1->where('name', 'LIKE', "%$search%")->orwhere('duration', 'LIKE', "%$search%");
            });;
        }


        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }


        $res = $sql->get();
        // return $res;
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';
        $tempRow['type'] = '';

        foreach ($res as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['start_date'] = date('d-m-Y', strtotime($row->start_date));
            $tempRow['end_date'] = date('d-m-Y', strtotime($row->end_date));
            $tempRow['subscription'] = '-';//$row->customer->subscription == 1 ? 'On' : 'Off';
            $tempRow['name'] = $row->package->name;
            $tempRow['customer_name'] = !empty($row->customer) ? $row->customer->name : '';
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
}

