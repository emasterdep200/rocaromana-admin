<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AssignedOutdoorFacilities;
use App\Models\AssignParameters;
use App\Models\Category;
use App\Models\Chats;
use App\Models\Customer;


use App\Models\Notifications;
use App\Models\OutdoorFacilities;
use App\Models\parameter;
use App\Models\Property;
use App\Models\PropertyImages;
use App\Models\PropertysInquiry;
use App\Models\Setting;
use App\Models\Slider;

use App\Models\Usertokens;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PropertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!has_permissions('read', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $category = Category::all();
            return view('property.index', compact('category'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!has_permissions('create', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $category = Category::where('status', '1')->get();



            $parameters = parameter::all();
            $currency_symbol = Setting::where('type', 'currency_symbol')->pluck('data')->first();
            $facility = OutdoorFacilities::all();
            return view('property.create', compact('category', 'parameters', 'currency_symbol', 'facility'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $arr = [];
        $count = 1;
        if (!has_permissions('read', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $request->validate([
                'gallery_images.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'title_image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);


            $Saveproperty = new Property();
            $Saveproperty->category_id = $request->category;
            $Saveproperty->title = $request->title;
            $title = $request->title;
            $Saveproperty->slug_id = generateUniqueSlug($title,1);


            $Saveproperty->description = $request->description;
            $Saveproperty->address = $request->address;
            $Saveproperty->client_address = $request->client_address;
            $Saveproperty->propery_type = $request->property_type;
            $Saveproperty->price = $request->price;
            $Saveproperty->package_id = 0;
            $Saveproperty->city = (isset($request->city)) ? $request->city : '';
            $Saveproperty->country = (isset($request->country)) ? $request->country : '';
            $Saveproperty->state = (isset($request->state)) ? $request->state : '';
            $Saveproperty->latitude = (isset($request->latitude)) ? $request->latitude : '';
            $Saveproperty->longitude = (isset($request->longitude)) ? $request->longitude : '';
            $Saveproperty->video_link = (isset($request->video_link)) ? $request->video_link : '';
            $Saveproperty->post_type = 0;
            $Saveproperty->added_by = 0;
            $Saveproperty->meta_title = isset($request->meta_title) ? $request->meta_title : $request->title;
            $Saveproperty->meta_description = $request->meta_description;
            $Saveproperty->meta_keywords = $request->keywords;

            $Saveproperty->rentduration = $request->price_duration;
            $Saveproperty->is_premium = $request->is_premium;

            if ($request->hasFile('title_image')) {

                $Saveproperty->title_image = store_image($request->file('title_image'), 'PROPERTY_TITLE_IMG_PATH');
                // $Saveproperty->title_image_hash = $image_hash;
            } else {
                $Saveproperty->title_image  = '';
            }
            if ($request->hasFile('3d_image')) {


                $Saveproperty->threeD_image = store_image($request->file('3d_image'), '3D_IMG_PATH');
            } else {
                $Saveproperty->threeD_image  = '';
            }

            if ($request->hasFile('meta_image')) {


                $Saveproperty->meta_image = store_image($request->file('meta_image'), 'PROPERTY_SEO_IMG_PATH');
            }


            $Saveproperty->save();

            $facility = OutdoorFacilities::all();
            foreach ($facility as $key => $value) {
                if ($request->has('facility' . $value->id) && $request->input('facility' . $value->id) != '') {
                    $facilities = new AssignedOutdoorFacilities();
                    $facilities->facility_id = $value->id;
                    $facilities->property_id = $Saveproperty->id;
                    $facilities->distance = $request->input('facility' . $value->id);
                    $facilities->save();
                }
            }
            $parameters = parameter::all();
            foreach ($parameters as $par) {

                if ($request->has('par_' . $par->id)) {

                    $assign_parameter = new AssignParameters();
                    $assign_parameter->parameter_id = $par->id;
                    if (($request->hasFile('par_' . $par->id))) {
                        $destinationPath = public_path('images') . config('global.PARAMETER_IMG_PATH');
                        if (!is_dir($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }
                        $imageName = microtime(true) . "." . ($request->file('par_' . $par->id))->getClientOriginalExtension();
                        ($request->file('par_' . $par->id))->move($destinationPath, $imageName);
                        $assign_parameter->value = $imageName;
                    } else {

                        $assign_parameter->value = is_array($request->input('par_' . $par->id)) ? json_encode($request->input('par_' . $par->id), JSON_FORCE_OBJECT) : ($request->input('par_' . $par->id));
                    }
                    $assign_parameter->modal()->associate($Saveproperty);
                    $assign_parameter->save();
                    $arr = $arr + [$par->id => $request->input('par_' . $par->id)];
                }
            }
            /// START :: UPLOAD GALLERY IMAGE


            $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $Saveproperty->id;
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if ($request->hasfile('gallery_images')) {
                // dd("in");
                foreach ($request->file('gallery_images') as $file) {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move($destinationPath, $name);
                    PropertyImages::create([
                        'image' => $name,
                        'propertys_id' => $Saveproperty->id
                    ]);
                }
            }
            /// END :: UPLOAD GALLERY IMAGE
            ResponseService::successRedirectResponse('Property Added Successfully');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!has_permissions('update', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $category = Category::all()->where('status', '1')->mapWithKeys(function ($item, $key) {
                return [$item['id'] => $item['category']];
            });
            $category = Category::where('status', '1')->get();
            $list = Property::with('assignParameter.parameter')->where('id', $id)->get()->first();

            $categoryData = Category::find($list->category_id);

            $categoryParameterTypeIds = explode(',', $categoryData['parameter_types']);

            $parameters = parameter::all();
            $edit_parameters = parameter::with(['assigned_parameter' => function ($q) use ($id) {
                $q->where('modal_id', $id);
            }])->whereIn('id',$categoryParameterTypeIds)->get();

            // Sort the collection by the order of IDs in $categoryParameterTypeIds.
            $edit_parameters = $edit_parameters->sortBy(function ($parameter) use ($categoryParameterTypeIds) {
                return array_search($parameter->id, $categoryParameterTypeIds);
            });

            // Reset the keys on the sorted collection.
            $edit_parameters = $edit_parameters->values();




            $facility = OutdoorFacilities::with(['assign_facilities' => function ($q) use ($id) {
                $q->where('property_id', $id);
            }])->get();

            $assignFacility = AssignedOutdoorFacilities::where('property_id', $id)->get();

            $arr = json_decode($list->carpet_area);
            $par_arr = [];
            $par_id = [];
            $type_arr = [];
            foreach ($list->assignParameter as  $par) {
                $par_arr = $par_arr + [$par->parameter->name => $par->value];
                $par_id = $par_id + [$par->parameter->name => $par->value];
            }
            $currency_symbol = Setting::where('type', 'currency_symbol')->pluck('data')->first();
            return view('property.edit', compact('category', 'facility', 'assignFacility', 'edit_parameters', 'list', 'id', 'par_arr', 'parameters', 'par_id', 'currency_symbol'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!has_permissions('update', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            DB::beginTransaction();

            $UpdateProperty = Property::with('assignparameter.parameter')->find($id);

            $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $UpdateProperty->category_id = $request->category;
            $UpdateProperty->title = $request->title;
            $UpdateProperty->description = $request->description;
            $UpdateProperty->address = $request->address;
            $UpdateProperty->client_address = $request->client_address;
            $UpdateProperty->propery_type = $request->property_type;
            $UpdateProperty->price = $request->price;
            $UpdateProperty->propery_type = $request->property_type;
            $UpdateProperty->price = $request->price;
            $UpdateProperty->state = (isset($request->state)) ? $request->state : '';
            $UpdateProperty->country = (isset($request->country)) ? $request->country : '';
            $UpdateProperty->city = (isset($request->city)) ? $request->city : '';
            $UpdateProperty->latitude = (isset($request->latitude)) ? $request->latitude : '';
            $UpdateProperty->longitude = (isset($request->longitude)) ? $request->longitude : '';
            $UpdateProperty->video_link = (isset($request->video_link)) ? $request->video_link : '';
            $UpdateProperty->is_premium = $request->is_premium;
            $UpdateProperty->meta_title = (isset($request->edit_meta_title)) ? $request->edit_meta_title : '';
            $UpdateProperty->meta_description = (isset($request->edit_meta_description)) ? $request->edit_meta_description : '';
            $UpdateProperty->meta_keywords = (isset($request->Keywords)) ? $request->Keywords : '';

            $UpdateProperty->rentduration = $request->price_duration;
            if ($request->hasFile('title_image')) {

                \unlink_image($UpdateProperty->title_image);

                $UpdateProperty->title_image = \store_image($request->file('title_image'), 'PROPERTY_TITLE_IMG_PATH');
            }

            if ($request->hasFile('3d_image')) {

                \unlink_image($UpdateProperty->threeD_image);

                $UpdateProperty->threeD_image = \store_image($request->file('3d_image'), '3D_IMG_PATH');
            }


            if ($request->hasFile('meta_image')) {




                \unlink_image($UpdateProperty->meta_image);

                $UpdateProperty->meta_image = \store_image($request->file('meta_image'), 'PROPERTY_SEO_IMG_PATH');
            }


            $UpdateProperty->update();
            AssignedOutdoorFacilities::where('property_id', $UpdateProperty->id)->delete();
            $facility = OutdoorFacilities::all();
            foreach ($facility as $key => $value) {

                if ($request->has('facility' . $value->id) && $request->input('facility' . $value->id) != '') {

                    $facilities = new AssignedOutdoorFacilities();
                    $facilities->facility_id = $value->id;
                    $facilities->property_id = $UpdateProperty->id;
                    $facilities->distance = $request->input('facility' . $value->id);
                    $facilities->save();
                }
                # code...
            }
            $parameters = parameter::all();

            AssignParameters::where('modal_id', $id)->delete();

            foreach ($parameters as $par) {

                if ($request->has('par_' . $par->id)) {
                    $update_parameter = new AssignParameters();
                    $update_parameter->parameter_id = $par->id;


                    if (($request->hasFile('par_' . $par->id))) {
                        $update_parameter->value = \store_image($request->file('par_' . $par->id), 'PARAMETER_IMG_PATH');
                    } else {
                        $update_parameter->value = is_array($request->input('par_' . $par->id)) || $request->input('par_' . $par->id) == null ? json_encode($request->input('par_' . $par->id), JSON_FORCE_OBJECT) : ($request->input('par_' . $par->id));
                    }

                    $update_parameter->modal()->associate($UpdateProperty);
                    $update_parameter->save();
                }
            }

            /// START :: UPLOAD GALLERY IMAGE

            $FolderPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
            if (!is_dir($FolderPath)) {
                mkdir($FolderPath, 0777, true);
            }


            $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $UpdateProperty->id;
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if ($request->hasfile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move($destinationPath, $name);

                    PropertyImages::create([
                        'image' => $name,
                        'propertys_id' => $UpdateProperty->id
                    ]);
                }
            }
            DB::commit();
            ResponseService::successRedirectResponse('Property Updated Successfully');
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
        if (env('DEMO_MODE') && Auth::user()->email != "superadmin@gmail.com") {
            return redirect()->back()->with('error', 'This is not allowed in the Demo Version');
        }
        if (!has_permissions('delete', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $property = Property::find($id);

            if ($property->delete()) {

                $chat = Chats::where('property_id', $property->id);
                if ($chat) {
                    $chat->delete();
                }



                $slider = Slider::where('propertys_id', $property->id);
                if ($slider) {
                    $slider->delete();
                }


                $notifications = Notifications::where('propertys_id', $property->id);
                if ($notifications) {
                    $notifications->delete();
                }

                $advertisements = Advertisement::where('property_id', $property->id);
                if ($advertisements) {
                    $advertisements->delete();
                }


                if ($property->title_image != '') {




                    $url =
                        $property->title_image;
                    $relativePath = parse_url($url, PHP_URL_PATH);

                    if (file_exists(public_path()  . $relativePath)) {
                        unlink(public_path()  . $relativePath);
                    }
                }
                foreach ($property->gallery as $row) {
                    if (PropertyImages::where('id', $row->id)->delete()) {
                        if ($row->image != '') {
                            $url =
                                $row->image;
                            $relativePath = parse_url($url, PHP_URL_PATH);

                            if (file_exists(public_path()  . $relativePath)) {
                                unlink(public_path()  . $relativePath);
                            }
                        }
                    }
                }

                Notifications::where('propertys_id', $id)->delete();
                ResponseService::successRedirectResponse('Property Deleted Successfully');
            } else {
                ResponseService::errorRedirectResponse('Something Wrong');
            }
        }
    }



    public function getPropertyList(Request $request)
    {

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'sequence');
        $order = $request->input('order', 'ASC');



        $sql = Property::with('category')->with('customer')->with('assignParameter.parameter')->with('interested_users')->with('advertisement')->orderBy($sort, $order);


        if ($_GET['status'] != '' && isset($_GET['status'])) {
            $status = $_GET['status'];
            $sql = $sql->where('status', $status);
        }


        if ($_GET['category'] != '' && isset($_GET['category'])) {
            $category_id = $_GET['category'];
            $sql = $sql->where('category_id', $category_id);
        }


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql = $sql->where('id', 'LIKE', "%$search%")->orwhere('title', 'LIKE', "%$search%")->orwhere('address', 'LIKE', "%$search%")->orwhereHas('category', function ($query) use ($search) {
                $query->where('category', 'LIKE', "%$search%");
            })->orwhereHas('customer', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")->orwhere('email', 'LIKE', "%$search%");
            });
        }

        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }



        $res = $sql->get();

        //return $res;
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';
        $currency_symbol = Setting::where('type', 'currency_symbol')->pluck('data')->first();

        foreach ($res as $row) {
            $tempRow = $row->toArray();

            if (has_permissions('update', 'property')) {
                $operate = BootstrapTableService::editButton(route('property.edit', $row->id), false);
            }


            if (has_permissions('delete', 'property')) {
                $operate .= BootstrapTableService::deleteButton(route('property.destroy', $row->id));
            }


            $interested_users = array();


            foreach ($row->interested_users as $interested_user) {

                if ($interested_user->property_id == $row->id) {

                    array_push($interested_users, $interested_user->customer_id);
                }
            }
            $price = null;
            if(!empty($row->propery_type) && $row->propery_type == 1){
                $price = !empty($row->rentduration) ?  $currency_symbol . '' . $row->price . '/' . $row->rentduration : $row->price;
            }else{
                $price = $currency_symbol . '' .$row->price;
            }

            $count = "  " . count($interested_users);
            $operate1 = BootstrapTableService::editButton('', true, null, null, $row->id, null, '', 'bi bi-eye-fill edit_icon', $count);


            $tempRow['total_interested_users'] = count($interested_users);
            $tempRow['edit_status_url'] = 'updatepropertystatus';
            $tempRow['price'] = $price;
            $featured = count($row->advertisement) ? '<div class="featured_tag"><div class="featured_lable">Featured</div></div>' : '';
            $tempRow['Property_name'] = '<div class="propetrty_name d-flex"><img class="property_image" alt="" src="' . $row->title_image . '"><div class="property_detail"><div class="property_title">' . $row->title . '</div>' . $featured . '</div></div></div>';
            $tempRow['interested_users'] = $operate1;

            if ($row->added_by != 0) {
                $tempRow['added_by'] = $row->customer->name;
                $tempRow['mobile'] = $row->customer->mobile;
            }
            if ($row->added_by == 0) {


                $mobile = Setting::where('type', 'company_tel1')->pluck('data');


                $tempRow['added_by'] = 'Admin';
                $tempRow['mobile'] =   $mobile[0];
            }
            $tempRow['customer_ids']
                = $interested_users;
            $tempRow['interested_users'] = $operate1;

            foreach ($row->interested_users as $interested_user) {

                if ($interested_user->property_id == $row->id) {

                    $tempRow['interested_users_details'] = Customer::Where('id', $interested_user->customer_id)->get()->toArray();
                }
            }
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        // $cities =  json_decode(file_get_contents(public_path('json') . "/cities.json"), true);

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
    public function updateStatus(Request $request)
    {
        if (!has_permissions('update', 'property')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {
            Property::where('id', $request->id)->update(['status' => $request->status]);
            $Property = Property::with('customer')->find($request->id);

            if (!empty($Property->customer)) {
                if ($Property->customer->fcm_id != '' && $Property->customer->notification == 1) {

                    $fcm_ids = array();

                    $customer_id = Customer::where('id', $Property->customer->id)->where('isActive', '1')->where('notification', 1)->get();
                    if (!empty($customer_id)) {
                        $user_token = Usertokens::where('customer_id', $Property->customer->id)->pluck('fcm_id')->toArray();
                    }

                    $fcm_ids[] = $user_token;

                    $msg = "";
                    if (!empty($fcm_ids)) {
                        $msg = $Property->status == 1 ? 'Activate now by Adminstrator ' : 'Deactive now by Adminstrator ';
                        $registrationIDs = $fcm_ids[0];

                        $fcmMsg = array(
                            'title' =>  $Property->name . 'Property Updated',
                            'message' => 'Your Property Post ' . $msg,
                            'type' => 'property_inquiry',
                            'body' => 'Your Property Post ' . $msg,
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'sound' => 'default',
                            'id' => (string)$Property->id,

                        );
                        send_push_notification($registrationIDs, $fcmMsg);
                    }
                    //END ::  Send Notification To Customer

                    Notifications::create([
                        'title' => $Property->name . 'Property Updated',
                        'message' => 'Your Property Post ' . $msg,
                        'image' => '',
                        'type' => '1',
                        'send_type' => '0',
                        'customers_id' => $Property->customer->id,
                        'propertys_id' => $Property->id
                    ]);
                }
            }
            $response['error'] = false;
            ResponseService::successResponse($request->status ? "Property Activatd Successfully" : "Property Deactivatd Successfully");
        }
    }


    public function removeGalleryImage(Request $request)
    {

        if (!has_permissions('delete', 'slider')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $id = $request->id;

            $getImage = PropertyImages::where('id', $id)->first();


            $image = $getImage->image;
            $propertys_id =  $getImage->propertys_id;

            if (PropertyImages::where('id', $id)->delete()) {
                if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image)) {
                    unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image);
                }
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }

            $countImage = PropertyImages::where('propertys_id', $propertys_id)->get();
            if ($countImage->count() == 0) {
                rmdir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id);
            }
            return response()->json($response);
        }
    }



    public function getFeaturedPropertyList()
    {

        $offset = 0;
        $limit = 4;
        $sort = 'id';
        $order = 'DESC';

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }

        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }

        $sql = Property::with('category')->with('customer')->whereHas('advertisement')->orderBy($sort, $order);

        $sql->skip($offset)->take($limit);

        $res = $sql->get();

        $bulkData = array();

        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';

        foreach ($res as $row) {

            if (count($row->advertisement)) {

                if (has_permissions('update', 'property')) {
                    $operate = '<a  href="' . route('property.edit', $row->id) . '"  class="btn icon btn-primary btn-sm rounded-pill mt-2" id="edit_btn" title="Edit"><i class="fa fa-edit edit_icon"></i></a>';
                }




                $status = $row->status == '1' ? 'checked' : '';
                $enable_disable =   '<div class="form-check form-switch center" style="margin-top: 10%;padding-left: 5.2rem;">
            <input class="form-check-input switch1" id="' . $row->id . '"  onclick="chk(this);" type="checkbox" role="switch"' . $status . '>

            </div>';
                $interested_users = array();
                $currency_symbol = Setting::where('type', 'currency_symbol')->pluck('data')->first();

                $tempRow['total_interested_users'] = count($interested_users);

                $tempRow['enble_disable'] = $enable_disable;

                $tempRow['id'] = $row->id;


                $featured = count($row->advertisement) ? '<div class="featured_tag">
                                                                 <div class="featured_lable">Featured</div>
                                                    </div>' : '';
                if ($row->propery_type == 0) {
                    $type = 'Sell';
                } elseif ($row->propery_type == 1) {
                    $type = 'Rent';
                } elseif ($row->propery_type == 2) {
                    $type = "Sold";
                } elseif ($row->propery_type == 3) {
                    $type = "Rented";
                }
                $tempRow['title'] = '<div class="featured_property">' .
                    '<div class="image-container">' .
                    '<img src="' . $row->title_image . '" alt="Your Image">' .
                    '<div class="text-overlay-top">featured</div><div class="text-overlay-bottom">' . $type . '</div>' .
                    '</div>' .
                    '<div>' .
                    '<div class="d-flex">' .
                    '<img src="' . $row->category->image . '" alt="Your Image" height="24px" width="24px">' .
                    '<div class="category">' . $row->category->category . '</div>' .
                    '</div>' .
                    '<div class="title">' . $row->title . '</div>' .
                    '<div class="price">' . $row->price . '</div>' .
                    '<div class="city">' .
                    '<i class="bi bi-geo-alt"></i>' . $row->city .

                    '</div>' .
                    '</div>' .
                    '</div>';


                $tempRow['status'] = ($row->status == '0') ? '<span class="badge rounded-pill bg-danger">Inactive</span>' : '<span class="badge rounded-pill bg-success">Active</span>';
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
                $count++;
            }
        }
        // $cities =  json_decode(file_get_contents(public_path('json') . "/cities.json"), true);
        $total = $sql->count();
        $bulkData['total'] = $total;
        $bulkData['rows'] = $rows;

        return response()->json($bulkData);
    }
    public function updateaccessability(Request $request)
    {
        if (!has_permissions('update', 'property')) {
            ResponseService::errorResponse(PERMISSION_ERROR_MSG);
        } else {
            Property::where('id', $request->id)->update(['is_premium' => $request->status]);
            ResponseService::successResponse("Property Updated Successfully");
        }
    }
}
