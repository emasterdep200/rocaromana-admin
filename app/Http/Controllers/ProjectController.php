<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Notifications;
use App\Models\Projects;
use App\Models\Setting;
use App\Models\Usertokens;
use App\Models\Category;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $category = Category::all();
        return \view('project.index',compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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


        $sql = Projects::with('category')->with('gallary_images')->with('documents')->with('plans')->with('customer')->orderBy($sort, $order);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql = $sql->where('id', 'LIKE', "%$search%")->orwhere('title', 'LIKE', "%$search%")->orwhere('location', 'LIKE', "%$search%")->orwhereHas('category', function ($query) use ($search) {
                $query->where('category', 'LIKE', "%$search%");
            });
        }

        if ($_GET['status'] != '' && isset($_GET['status'])) {
            $status = $_GET['status'];
            $sql = $sql->where('status', $status);
        }


        if ($_GET['category'] != '' && isset($_GET['category'])) {
            $category_id = $_GET['category'];
            $sql = $sql->where('category_id', $category_id);
        }

        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }

        $res = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        $currency_symbol = Setting::where('type', 'currency_symbol')->pluck('data')->first();

        foreach ($res as $row) {
                       $action = BootstrapTableService::editButton('', true, null, null, $row->id, null, '', 'bi bi-eye edit_icon');

            $tempRow = $row->toArray();
            $tempRow['edit_status_url'] = 'updateProjectStatus';

            $tempRow['price'] = $currency_symbol . '' . $row->price . '/' . (!empty($row->rentduration) ? $row->rentduration : 'Month');
            $tempRow['action'] = $action;
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
    public function update(Request $request, $id)
    {
        //
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

 public function updateStatus(Request $request)
    {
        if (!has_permissions('delete', 'projects')) {
            ResponseService::errorResponse(PERMISSION_ERROR_MSG);
        } else {
            Projects::where('id', $request->id)->update(['status' => $request->status]);




            $project = Projects::with('customer')->find($request->id);

            if ($project->customer) {

                if ($project->customer->fcm_id != '' && $project->customer->notification == 1) {

                    $fcm_ids = array();

                    $customer_id = Customer::where('id', $project->customer->id)->where('isActive', '1')->where('notification', 1)->get();
                    if (count($customer_id)) {
                        $user_token = Usertokens::where('customer_id', $project->customer->id)->select('id', 'fcm_id')->get()->pluck('fcm_id')->toArray();
                    }

                    $fcm_ids[] = $user_token;

                    $msg = "";
                    if (!empty($fcm_ids)) {
                        $msg = $project->status == 1 ? 'Activate now by Adminstrator ' : 'Deactive now by Adminstrator ';
                        $registrationIDs = $fcm_ids[0];

                        $fcmMsg = array(
                            'title' =>  $project->name . 'project Updated',
                            'message' => 'Your project Post ' . $msg,
                            'type' => 'project_inquiry',
                            'body' => 'Your project Post ' . $msg,
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'sound' => 'default',
                            'id' => (int)$project->id,
                        );
                        send_push_notification($registrationIDs, $fcmMsg);
                    }
                    //END ::  Send Notification To Customer

                    Notifications::create([
                        'title' => $project->name . 'project Updated',
                        'message' => 'Your project Post ' . $msg,
                        'image' => '',
                        'type' => '1',
                        'send_type' => '0',
                        'customers_id' => $project->customer->id,
                        'projects_id' => $project->id
                    ]);
                }
            }
            $response['error'] = false;
            ResponseService::successResponse($request->status ? "project Activatd Successfully" : "project Deactivatd Successfully");





            ResponseService::successResponse($request->status ? "Project Activatd Successfully" : "Project Deactivatd Successfully");
        }
    }
}
