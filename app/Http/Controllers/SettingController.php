<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = last(request()->segments());
        $type1 = str_replace('-', '_', $type);
        $data = Setting::select('data')->where('type', $type1)->pluck('data')->first();

        $stripe_currencies = ["USD", "AED", "AFN", "ALL", "AMD", "ANG", "AOA", "ARS", "AUD", "AWG", "AZN", "BAM", "BBD", "BDT", "BGN", "BIF", "BMD", "BND", "BOB", "BRL", "BSD", "BWP", "BYN", "BZD", "CAD", "CDF", "CHF", "CLP", "CNY", "COP", "CRC", "CVE", "CZK", "DJF", "DKK", "DOP", "DZD", "EGP", "ETB", "EUR", "FJD", "FKP", "GBP", "GEL", "GIP", "GMD", "GNF", "GTQ", "GYD", "HKD", "HNL", "HTG", "HUF", "IDR", "ILS", "INR", "ISK", "JMD", "JPY", "KES", "KGS", "KHR", "KMF", "KRW", "KYD", "KZT", "LAK", "LBP", "LKR", "LRD", "LSL", "MAD", "MDL", "MGA", "MKD", "MMK", "MNT", "MOP", "MRO", "MUR", "MVR", "MWK", "MXN", "MYR", "MZN", "NAD", "NGN", "NIO", "NOK", "NPR", "NZD", "PAB", "PEN", "PGK", "PHP", "PKR", "PLN", "PYG", "QAR", "RON", "RSD", "RUB", "RWF", "SAR", "SBD", "SCR", "SEK", "SGD", "SHP", "SLE", "SOS", "SRD", "STD", "SZL", "THB", "TJS", "TOP", "TTD", "TWD", "TZS", "UAH", "UGX", "UYU", "UZS", "VND", "VUV", "WST", "XAF", "XCD", "XOF", "XPF", "YER", "ZAR", "ZMW"];
        $languages = Language::all();
        return view('settings.' . $type, compact('data', 'type', 'languages', 'stripe_currencies'));
    }

    public function settings(Request $request)
    {


        if (!has_permissions('update', $request->data)) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {

            $request->validate([
                'data' => 'required',
            ]);

            $type1 = $request->type;
            if ($type1 != '') {
                $message = Setting::where('type', $type1)->first();
                if (empty($message)) {
                    Setting::create([
                        'type' => $type1,
                        'data' => $request->data
                    ]);
                } else {
                    $data['data'] = $request->data;
                    Setting::where('type', $type1)->update($data);
                }
                return redirect(str_replace('_', '-', $type1))->with('success', 'Setting Update');
            } else {
                return redirect(str_replace('_', '-', $type1))->with('error', 'Something Wrong');
            }
        }
    }

    public function system_settings(Request $request)
    {

        $input = $request->except(['_token', 'btnAdd']);

        $destinationPath = public_path('assets/images/logo');
        $destinationPath1 = public_path('assets/images/bg');

        $input['favicon_icon'] = handleFileUpload($request, 'favicon_icon', $destinationPath, 'favicon.png');
        $input['company_logo'] = handleFileUpload($request, 'company_logo', $destinationPath, 'logo.png');
        $input['login_image'] = handleFileUpload($request, 'login_image', $destinationPath1, 'Login_BG.jpg');
        if ($request->hasFile('web_logo')) {
            $file = $request->file('web_logo');


            $input['web_logo'] = handleFileUpload($request, 'web_logo', $destinationPath, $file->getClientOriginalName());
        }


        $envUpdates = [
            'APP_NAME' => $request->company_name,
            'PLACE_API_KEY' => $request->place_api_key,
            'UNSPLASH_API_KEY' => $request->unsplash_api_key,
            'PRIMARY_COLOR' => $request->system_color,
            'PRIMARY_RGBA_COLOR' => $request->rgb_color,
        ];

        $envFile = file_get_contents(base_path('.env'));

        foreach ($envUpdates as $key => $value) {
            // Check if the key exists in the .env file
            if (strpos($envFile, "{$key}=") === false) {
                // If the key doesn't exist, add it
                $envFile .= "\n{$key}=\"{$value}\"";
            } else {
                // If the key exists, replace its value
                $envFile = preg_replace("/{$key}=.*/", "{$key}=\"{$value}\"", $envFile);
            }
        }

        // Save the updated .env file
        file_put_contents(base_path('.env'), $envFile);


        // Create or update records in the 'settings' table
        foreach ($input as $key => $value) {

            Setting::updateOrCreate(['type' => $key], ['data' => $value]);
        }

        return redirect()->back()->with('success', 'Setting Update');
    }

    public function firebase_settings(Request $request)
    {
        if (!has_permissions('update', 'firebase_setting')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $input = $request->all();

            unset($input['btnAdd1']);
            unset($input['_token']);
            foreach ($input as $key => $value) {
                $result = Setting::where('type', $key)->first();
                if (empty($result)) {
                    Setting::create([
                        'type' => $key,
                        'data' => $value
                    ]);
                } else {
                    $data['data'] = ($value) ? $value : '';
                    Setting::where('type', $key)->update($data);
                }
            }
        }
        return redirect()->back()->with('success', 'Setting Update');
    }
    public function system_version()
    {
        return view('settings.system_version');
    }


    public function show_privacy_policy()
    {
        $privacy_policy = Setting::select('data')->where('type', 'privacy_policy')->first();

        return view('settings.show_privacy_policy', compact('privacy_policy'));
    }

    public function show_terms_conditions()
    {
        $terms_conditions = Setting::select('data')->where('type', 'terms_conditions')->first();

        return view('settings.show_terms_conditions', compact('terms_conditions'));
    }
    public function system_version_setting(Request $request)
    {
        // dd($request->toArray());
        $destinationPath = public_path() . '/update/tmp/';

        if (!has_permissions('customer', 'edit')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            'purchase_code' => 'required',
            'file' => 'required|file|mimes:zip',
        ]);

        if ($validator->fails()) {


            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $app_url = (string)url('/');
        $app_url = preg_replace('#^https?://#i', '', $app_url);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wrteam.in/validator/ebroker_validator?purchase_code=' . $request->purchase_code . '&domain_url=' . $app_url . '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        // dd(curl_getinfo($curl));

        curl_close($curl);
        // dd($response);
        $response = json_decode($response, true);
        if ($response['error']) {
            $response = array(
                'error' => true,
                'message' => $response["message"],
                'info' => $info
            );

            return redirect()->back()->with('error', $response["message"]);
        } else {
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, TRUE);
            }

            // zip upload
            $zipfile = $request->file('file');
            $fileName = $zipfile->getClientOriginalName();
            $zipfile->move($destinationPath, $fileName);

            $target_path = base_path();


            $zip = new ZipArchive();
            $filePath = $destinationPath . '/' . $fileName;
            $zipStatus = $zip->open($filePath);
            if ($zipStatus) {
                $zip->extractTo($destinationPath);
                $zip->close();
                unlink($filePath);

                $ver_file = $destinationPath . 'version_info.php';
                $source_path = $destinationPath . 'source_code.zip';
                if (file_exists($ver_file) && file_exists($source_path)) {
                    $ver_file1 = $target_path . 'version_info.php';
                    $source_path1 = $target_path . 'source_code.zip';
                    if (rename($ver_file, $ver_file1) && rename($source_path, $source_path1)) {
                        $version_file = require_once($ver_file1);

                        $current_version = Setting::select('data')->where('type', 'system_version')->pluck('data')->first();
                        // dd($current_version);
                        if ($current_version == $version_file['current_version']) {
                            $zip1 = new ZipArchive();
                            $zipFile1 = $zip1->open($source_path1);
                            if ($zipFile1 === true) {
                                $zip1->extractTo($target_path); // change this to the correct site path
                                $zip1->close();

                                Artisan::call('migrate');
                                unlink($source_path1);
                                unlink($ver_file1);
                                Setting::where('type', 'system_version')->update([
                                    'data' => $version_file['update_version']
                                ]);

                                return redirect()->back()->with('success', 'system_update_successfully');
                            } else {
                                unlink($source_path1);
                                unlink($ver_file1);

                                return redirect()->back()->with('error', 'something_wrong_try_again');
                            }
                        } else if ($current_version == $version_file['update_version']) {
                            unlink($source_path1);
                            unlink($ver_file1);


                            return redirect()->back()->with('error', 'system_already_updated');
                        } else {
                            unlink($source_path1);
                            unlink($ver_file1);

                            return redirect()->back()->with('error', $current_version . ' ' . trans('your_version_update_nearest'));
                        }
                    } else {

                        return redirect()->back()->with('error', 'invalid_zip_try_again');
                    }
                } else {

                    return redirect()->back()->with('error', 'invalid_zip_try_again');
                }
            } else {
                return redirect()->back()->with('error', 'something_wrong_try_again');
            }
        }
    }

    public function app_settings(Request $request)
    {
        if (!has_permissions('update', 'app_setting')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $input = $request->except(['_token', 'btnAdd']);

            // dd($input);
            $destinationPath = public_path('assets/images/logo');


            if ($request->hasFile('splash_logo')) {
                $file = $request->file('splash_logo');
                if ($file->getClientOriginalExtension() != 'svg') {
                    return redirect()->back()->with('error', 'Image must be svg');
                }

                $input['splash_logo'] = handleFileUpload($request, 'splash_logo', $destinationPath, $file->getClientOriginalName());
            }
            if ($request->hasFile('app_home_screen') && $request->file('app_home_screen')->isValid()) {
                $file = $request->file('app_home_screen');
                if ($file->getClientOriginalExtension() != 'svg') {
                    return redirect()->back()->with('error', 'Image must be svg');
                }


                $input['app_home_screen'] = handleFileUpload($request, 'app_home_screen', $destinationPath, $file->getClientOriginalName());
            }
            if ($request->hasFile('placeholder_logo') && $request->file('placeholder_logo')->isValid()) {
                $file = $request->file('placeholder_logo');
                if ($file->getClientOriginalExtension() != 'svg') {
                    return redirect()->back()->with('error', 'Image must be svg');
                }
                $input['placeholder_logo'] = handleFileUpload($request, 'placeholder_logo', $destinationPath, $file->getClientOriginalName());
            }

            foreach ($input as $key => $value) {

                Setting::updateOrCreate(['type' => $key], ['data' => $value]);
            }
        }

        return redirect()->back()->with('success', 'Setting Update');
    }


    public function web_settings(Request $request)
    {
        if (!has_permissions('update', 'web_setting')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $input = $request->except(['_token', 'btnAdd']);

            // dd($input);
            $destinationPath = public_path('assets/images/logo');


            if ($request->hasFile('web_logo')) {
                $file = $request->file('web_logo');


                $input['web_logo'] = handleFileUpload($request, 'web_logo', $destinationPath, $file->getClientOriginalName());
            }
            if ($request->hasFile('web_placeholder_logo') && $request->file('web_placeholder_logo')->isValid()) {
                $file = $request->file('web_placeholder_logo');
                $input['web_placeholder_logo'] = handleFileUpload($request, 'web_placeholder_logo', $destinationPath, $file->getClientOriginalName());
            }
            if ($request->hasFile('web_favicon') && $request->file('web_favicon')->isValid()) {
                $file = $request->file('web_favicon');
                $input['web_favicon'] = handleFileUpload($request, 'web_favicon', $destinationPath, $file->getClientOriginalName());
            }
            if ($request->hasFile('web_footer_logo') && $request->file('web_footer_logo')->isValid()) {
                $file = $request->file('web_footer_logo');
                $input['web_footer_logo'] = handleFileUpload($request, 'web_footer_logo', $destinationPath, $file->getClientOriginalName());
            }

            foreach ($input as $key => $value) {

                Setting::updateOrCreate(['type' => $key], ['data' => $value]);
            }
        }

        return redirect()->back()->with('success', 'Setting Update');
    }

    public function notificationSettingIndex(){
        $firebaseProjectId = Setting::where('type', 'firebase_project_id')->pluck('data')->first();
        $firebaseServiceJsonFile = Setting::where('type', 'firebase_service_json_file')->pluck('data')->first();
        return view('settings.notification-settings', compact('firebaseProjectId','firebaseServiceJsonFile'));
    }
    public function notificationSettingStore(Request $request){
        if (!has_permissions('update', 'notification')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            // Declare the variables
            $directType = ['firebase_project_id'];
            $fileType = ['firebase_service_json_file'];

            // Loop to other than file data
            foreach ($directType as $type) {
                $data = $request->$type;
                Setting::updateOrCreate(['type' => $type], ['data' => $data]);
            }

            // Loop to file data
            foreach ($fileType as $type) {
                $destinationPath = public_path('assets');
                $file = $request->file($type);

                if($type == 'firebase_service_json_file'){
                    // When Type is firebase service file then pass custom name
                    if($request->hasFile($type)){
                        $name = handleFileUpload($request, $type, $destinationPath, 'firebase-service.json');
                        Setting::updateOrCreate(['type' => $type], ['data' => $name]);
                    }
                }else{
                    // When other file then pass the filename
                    if($request->hasFile($type)){
                        $name = handleFileUpload($request, $type, $destinationPath, $file->getClientOriginalName());
                        Setting::updateOrCreate(['type' => $type], ['data' => $name]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', trans('Data Updated Successfully'));
    }
}
