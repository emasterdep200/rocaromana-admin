<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\parameter;
use App\Models\Type;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!has_permissions('read', 'categories')) {
            ResponseService::errorRedirectResponse(PERMISSION_ERROR_MSG);
        } else {
            $parameters = parameter::all();
            return view('categories.index', ['parameters' => $parameters]);
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
        if (!has_permissions('create', 'categories')) {
            ResponseService::errorRedirectResponse(null, PERMISSION_ERROR_MSG);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:svg|max:2048',
                'category' => 'required'
            ]);
            $saveCategories = new Category();



            if ($request->hasFile('image')) {
                $saveCategories->image = store_image($request->file('image'), 'CATEGORY_IMG_PATH');
            } else {
                $saveCategories->image  = '';
            }

            $saveCategories->category = ($request->category) ? $request->category : '';
            $saveCategories->parameter_types = ($request->parameter_type) ? implode(',', $request->parameter_type) : '';
            $title = $request->category;
            $saveCategories->slug_id = generateUniqueSlug($title,3);
            $saveCategories->meta_title = $request->meta_title;
            $saveCategories->meta_description = $request->meta_description;
            $saveCategories->meta_keywords = $request->meta_keywords;
            $saveCategories->save();
            ResponseService::successRedirectResponse('Category Added Successfully');
        }
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

        $request->validate([
            'image' => 'mimes:svg|max:2048', // Adjust max size as needed
        ], [

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a PNG, JPG, JPEG, or SVG file.',
            'image.max' => 'The image size should not exceed 2MB.', // Adjust as needed
        ]);

        if (!has_permissions('update', 'categories')) {

            ResponseService::errorRedirectResponse(PERMISSION_ERROR_MSG);
        } else {




            $arr = [];
            $parameters = parameter::all();
            foreach ($parameters as $par) {

                if ($request->has($par->name)) {
                    $arr = $arr + [$par->id => $request->input($par->name)];
                }
            }

            $Category = Category::find($request->edit_id);



            $destinationPath = public_path('images') . config('global.CATEGORY_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            // image upload


            if ($request->hasFile('edit_image')) {

                unlink_image($Category->image);
                $Category->image = store_image($request->file('edit_image'), 'CATEGORY_IMG_PATH');
            }


            $Category->category = $request->edit_category;

            $title = $request->edit_category;
            $Category->slug_id = generateUniqueSlug($title,3);
            $Category->meta_title = $request->edit_meta_title;
            $Category->meta_description = $request->edit_meta_description;
            $Category->meta_keywords = $request->edit_keywords;

            $Category->sequence = ($request->sequence) ? $request->sequence : 0;
            $Category->parameter_types = $request->update_seq;


            $Category->update();
            ResponseService::successRedirectResponse('Category Updated Successfully');
        }
    }



    public function categoryList(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'sequence');
        $order = $request->input('order', 'ASC');



        $sql = Category::orderBy($sort, $order);
        // dd($sql->toArray());
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('category', 'LIKE', "%$search%");
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


        $operate = '';
        $tempRow['type'] = '';

        foreach ($res as $row) {

            $tempRow = $row->toArray();
            $tempRow['edit_status_url'] = 'categorystatus';
            $parameter_type_arr = explode(',', $row->parameter_types);
            $arr = [];

            if ($row->parameter_types) {
                foreach ($parameter_type_arr as $p) {
                    $par = parameter::find($p);
                    if ($par) {
                        $arr = array_merge($arr, [$par->name]);
                    }
                }
            }
            $tempRow['type'] = implode(',', $arr);

            $ids = isset($row->parameter_types) ? $row->parameter_types : '';

            $operate = BootstrapTableService::editButton('', true, null, null, $row->id, null, $ids);
            $tempRow['operate'] = $operate;

            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }



    public function updateCategory(Request $request)
    {
        if (!has_permissions('delete', 'categories')) {
            ResponseService::errorResponse(PERMISSION_ERROR_MSG);
        } else {
            Category::where('id', $request->id)->update(['status' => $request->status]);
            ResponseService::successResponse($request->status ? "Category Activatd Successfully" : "Category Deactivatd Successfully");
        }
    }
}
