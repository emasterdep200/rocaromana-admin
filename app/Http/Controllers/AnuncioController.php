<?php

namespace App\Http\Controllers;

use App\Models\Usertokens;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\Anuncio;
use App\Models\Package;
use App\Services\ResponseService;

class AnuncioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('anuncios.index');
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
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $destinationPath = public_path('images') . config('global.PUBS_IMG_PATH');

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $name = '';

        if ($request->hasfile('image')) {
            $name = \store_image($request->file('image'), 'PUBS_IMG_PATH');
        }

        Anuncio::create([
            'titulo' => (isset($request->titulo)) ? $request->titulo : 0,
            'imagen' => ($name) ? $name : '',
            'link'   => (isset($request->link)) ? $request->link : 0,
            'estado' => (isset($request->estado)) ? $request->estado : 0
        ]);

        ResponseService::successRedirectResponse('Publicidad agregada con exito.');
    }




    public function show(Request $request){

        $offset = $request->input('offset', 0);
        $limit  = $request->input('limit', 10);
        $sort   = $request->input('sort', 'id');
        $order  = $request->input('order', 'ASC');


        $sql = Anuncio::orderBy($sort, $order);


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('titulo', 'LIKE', "%$search%");
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


        $presupuesto = 1000000;

        foreach ($res as $row) {


            $tempRow = $row->toArray();

            $operate = '<a  id="' . $row->id . '"  data-id="' . $row->id . '"class="btn icon btn-primary btn-sm rounded-pill editdata"  data-bs-toggle="modal" data-bs-target="#viewEditAnuncio"  title="Detalle"><i class="fa fa-edit"></i></a>';

            $tempRow['titulo']  = $row->titulo;
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
        if (!has_permissions('update', 'advertisement')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {
            Advertisement::find($request->id)->update(['status' => $request->edit_adv_status]);

            $adv = Advertisement::with('customer')->find($request->id);
            $status = $adv->status;
            if ($adv->customer->notification == 1) {
                if ($status == '0') {
                    $status_text  = 'Approved';
                } else if ($status == '1') {
                    $status_text  = 'Pending';
                } else if ($status == '2') {
                    $status_text  = 'Rejected';
                }
                $user_token = Usertokens::where('customer_id', $adv->customer->id)->pluck('fcm_id')->toArray();
                //START :: Send Notification To Customer
                $fcm_ids = array();
                $fcm_ids = $user_token;
                if (!empty($fcm_ids)) {
                    $registrationIDs = $fcm_ids;
                    $fcmMsg = array(
                        'title' => 'Advertisement Request',
                        'message' => 'Advertisement Request Is ' . $status_text,
                        'type' => 'advertisement_request',
                        'body' => 'Advertisement Request Is ' . $status_text,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'sound' => 'default',
                        'id' => (string)$adv->id,
                    );
                    send_push_notification($registrationIDs, $fcmMsg);
                }
                //END ::  Send Notification To Customer

                Notifications::create([
                    'title' => 'Property Inquiry Updated',
                    'message' => 'Your Advertisement Request is ' . $status_text,
                    'image' => '',
                    'type' => '1',
                    'send_type' => '0',
                    'customers_id' => $adv->customer->id,
                    'propertys_id' => $adv->id
                ]);
            }

            ResponseService::successRedirectResponse('Advertisement status update Successfully');
        }
    }

    public function updateStatus(Request $request)
    {
        if (!has_permissions('update', 'advertisement')) {
            ResponseService::errorResponse(PERMISSION_ERROR_MSG);
        } else {
            Advertisement::where('id', $request->id)->update(['is_enable' => $request->status]);
            ResponseService::successResponse($request->status ? "Advertisement Activatd Successfully" : "Advertisement Deactivatd Successfully");
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
        
        $getImage = Anuncio::where('id', $id)->first();
        $image = $getImage->getAttributes()['imagen'];

        if (Anuncio::where('id', $id)->delete()) {
            if (file_exists(public_path('images') . config('global.PUBS_IMG_PATH') . $image)) {
                unlink(public_path('images') . config('global.PUBS_IMG_PATH') . $image);
            }
            ResponseService::successRedirectResponse('slider delete successfully');
        } else {
            ResponseService::errorRedirectResponse(null, 'something is wrong !!!');
        }
        
    }
}
