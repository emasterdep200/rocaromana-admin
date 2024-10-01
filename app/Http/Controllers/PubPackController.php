<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Usertokens;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\PubPack;
use App\Models\Package;
use App\Services\ResponseService;


class PubPackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('pubpacks.index');
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

        PubPack::create([
            'nombre'       => $request->nombre,
            'price'        => $request->price,
            'days_valid'   => $request->days_valid
        ]);

        ResponseService::successRedirectResponse('Plan publicitario creado con exito.');
    }




    public function show(Request $request){

        $offset = $request->input('offset', 0);
        $limit  = $request->input('limit', 10);
        $sort   = $request->input('sort', 'id');
        $order  = $request->input('order', 'ASC');


        $sql = PubPack::orderBy($sort, $order);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('nombre', 'LIKE', "%$search%");
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

        try {

            $pubpack = PubPack::where(['id' => $request->id])->first();
            $pubpack->nombre     = $request->nombre;
            $pubpack->price      = $request->price;
            $pubpack->days_valid = $request->days_valid;          
            $pubpack->save();

            ResponseService::successRedirectResponse('Pub Package Actualizado.');
        } catch (\Throwable $th) {
            ResponseService::errorResponse("Error al actualizar el anuncio.  ".$th);
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
