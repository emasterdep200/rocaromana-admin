<?php

namespace App\Http\Controllers;

use App\Libraries\Paypal;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payments;
use App\Models\UserPurchasedPackage;
use App\Models\Comision;
use App\Models\Asesor;
use App\Models\Venta;
use App\Models\Setting;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;


class WebhookController extends Controller
{
    public function paystack()
    {


        $input = @file_get_contents("php://input");
        $currentTime = Carbon::now()->toDateString();
        Log::debug('\n Current Time ' . $currentTime);
        // $PAYSTACK = config('paystack.secretKey');
        http_response_code(200);
        $input = json_decode($input, true);

        Log::debug('\n paystack webhook called ---- 123' . json_encode($input, JSON_PRETTY_PRINT));
        // Log::debug('\n paystack webhook called ---- 123' . var_export($input['data']));


        switch ($input['event']) {
            case 'charge.success':
                $user_id = $input['data']['metadata']['user_id'];
                $package_id = $input['data']['metadata']['package_id'];

                $payment = new Payments();
                $payment->transaction_id = $input['data']['id'];
                $payment->amount = ($input['data']['amount'])/100;
                $payment->package_id = $package_id;
                $payment->customer_id = $user_id;
                $payment->status = 1;
                $payment->payment_gateway = "paystack";
                $payment->save();
                $start_date =  Carbon::now();

                $user = Customer::find($user_id);
                $package = Package::find($package_id);


                if ($package) {
                    $user_package = new UserPurchasedPackage();
                    $user_package->modal()->associate($user);
                    $user_package->package_id = $package_id;
                    $user_package->start_date = $start_date;
                    $user_package->end_date = $package->duration != 0 ? Carbon::now()->addDays($package->duration) : NULL;
                    $user_package->save();
                // if ($data_exists) {
                //       UserPurchasedPackage::where('modal_id', $user_id)->where('package_id','!=',$user_package->id)->delete();
                //      }
                    $user->subscription = 1;
                    $user->update();
                }
                break;
        }
    }


    public function razorpay()
    {
        $input = @file_get_contents("php://input");
        $data = json_decode($input, true);
        Log::debug('\n razorpay webhook called ---- 123' . json_encode($data, JSON_PRETTY_PRINT));

        switch ($data['event']) {
            case 'payment.authorized':
                $user_id = $data['payload']['payment']['entity']['notes']['user_id'];
                $package_id = $data['payload']['payment']['entity']['notes']['package_id'];

                $payment = new Payments();
                $payment->transaction_id = $data['payload']['payment']['entity']['id'];
                $payment->amount = ($data['payload']['payment']['entity']['amount'])/100;
                $payment->package_id = $package_id;
                $payment->customer_id = $user_id;
                $payment->status = 1;
                $payment->payment_gateway = "razorpay";
                $payment->save();
                $start_date =  Carbon::now();

                $user = Customer::find($user_id);
                $package = Package::find($package_id);


                if ($package) {
                     Log::debug("save");
                    $user_package = new UserPurchasedPackage();
                    $user_package->modal()->associate($user);
                    $user_package->package_id = $package_id;
                    $user_package->start_date = $start_date;
                    $user_package->end_date = $package->duration != 0 ? Carbon::now()->addDays($package->duration) : NULL;
                    $user_package->save();
                    // if ($data_exists) {
                    //           UserPurchasedPackage::where('modal_id', $user_id)->where('package_id','!=',$user_package->id)->delete();
                    //   }
                    $user->subscription = 1;
                    $user->update();
                }
                break;
        }
    }


 public function paypal(Request $request)
    {
        $input = file_get_contents('php://input');
        Log::debug('paypal webhook called: ' . var_export($input, true));

        $paypal = new Paypal();
        // Check if $input is not empty
          parse_str($input, $arr);
            Log::debug('parsed input: ' . var_export($arr, true));
            $ipnCheck = $paypal->validate_ipn($arr);
             if($ipnCheck){
                      Log::debug('paypal IPN valid');
             }
             else{
  Log::debug('paypal IPN Invalid');
             }


        if (!empty($input)) {
           $data= parse_str($input, $arr);




                 switch ($arr['payment_status']) {

            case 'Completed':


                $custom_data=explode(',',$arr['custom']);
                $package_id=$custom_data[0];
                $user_id=$custom_data[1];
                  Log::debug('custom' . var_export($custom_data, true));
                  Log::debug('package: ' . var_export($package_id, true));

                $payment = new Payments();
                $payment->transaction_id = $arr['txn_id'];
                $payment->amount = ($arr['payment_gross']);
                $payment->package_id = $package_id;
                $payment->customer_id = $user_id;
                $payment->status = 1;
                $payment->payment_gateway = "paypal";
                $payment->save();
                $start_date =  Carbon::now();

                $user = Customer::find($user_id);
                $package = Package::find($package_id);
                $data_exists = UserPurchasedPackage::where('modal_id', $user_id)->get();

                if ($package) {
                     Log::debug("save");
                    $user_package = new UserPurchasedPackage();
                    $user_package->modal()->associate($user);
                    $user_package->package_id = $package_id;
                    $user_package->start_date = $start_date;
                    $user_package->end_date = $package->duration != 0 ? Carbon::now()->addDays($package->duration) : NULL;
                    $user_package->save();

                    $user->subscription = 1;
                    $user->update();
                }
                break;
        }

        } else {
            Log::debug('input is empty');
        }
    }


    public function stripe(Request $request){
          $input = file_get_contents('php://input');
           $data = json_decode($input, true);

      Log::debug('ebroker stripe webhook called: ' . var_export($data['data'], true));
          switch ($data['type']) {
              case "payment_intent.succeeded":


                $package_id=$data['data']['object']['metadata']['packageId'];
                $user_id=$data['data']['object']['metadata']['userId'];
                  Log::debug('custom' . var_export($package_id, true));
                  Log::debug('package: ' . var_export($user_id, true));

                $payment = new Payments();
                $payment->transaction_id = $data['data']['object']['id'];
                $payment->amount =  $data['data']['object']['amount']/100;
                $payment->package_id = $package_id;
                $payment->customer_id = $user_id;
                $payment->status = 1;
                $payment->payment_gateway = "stripe";
                $payment->save();
                $start_date =  Carbon::now();

                $user = Customer::find($user_id);
                $package = Package::find($package_id);
                $data_exists = UserPurchasedPackage::where('modal_id', $user_id)->get();

                if ($package) {
                     Log::debug("save");
                    $user_package = new UserPurchasedPackage();
                    $user_package->modal()->associate($user);
                    $user_package->package_id = $package_id;
                    $user_package->start_date = $start_date;
                    $user_package->end_date = $package->duration != 0 ? Carbon::now()->addDays($package->duration) : NULL;
                    $user_package->save();

                    $user->subscription = 1;
                    $user->update();
                }
                break;
          }

    }

     public function qonversion(Request $request)
    {
          $input = file_get_contents('php://input');
           $data = json_decode($input, true);

      Log::debug('qonversion webhook called: ' . var_export($data['data'], true));
    }


    public function payu(Request $request){
        $input = file_get_contents('php://input');
        $data  = json_decode($input, true);

        if($data['state_pol'] == 4){

                $referenceCode = $data['reference_sale'];
                list($user_id, $package_id, $asesor_id) = explode('-', $referenceCode);



                $payment = new Payments();
                $payment->transaction_id = $data['transaction_id'];
                $payment->amount =  $data['value'];
                $payment->package_id = $package_id;
                $payment->customer_id = $user_id;
                $payment->status = 1;
                $payment->payment_gateway = "payu";
                $payment->save();
                $start_date =  Carbon::now();

                $user = Customer::find(intval($user_id));
                $package = Package::find(intval($package_id));
                $data_exists = UserPurchasedPackage::where('modal_id', $user_id)->get();

                if ($package) {
                     Log::debug("save");
                    $user_package = new UserPurchasedPackage();
                    $user_package->modal()->associate($user);
                    $user_package->package_id = $package_id;
                    $user_package->start_date = $start_date;
                    $user_package->end_date = $package->duration != 0 ? Carbon::now()->addDays($package->duration) : NULL;
                    $user_package->save();

                    $user->subscription = 1;
                    $user->update();
                }


                if($asesor_id){
                    $asesor   = Asesor::where(['referencia' => $asesor_id])->first();
                    $setting_comision = Setting::where(['type' => 'comision_asesores'])->first();

                    $comision = new Comision();
                    $comision->id_asesor = $asesor->id;
                    $comision->comision  = $package->price * ($setting_comision->data / 100);
                    $comision->estado    = "P";
                    $comision->save();

                    $venta = Venta();
                    $venta->id_asesor   = $asesor->id;
                    $venta->id_cliente  = $user->id;
                    $venta->plan        = $package_id;
                    $venta->city        = $asesor->ciudad;
                    $venta->save();
                }

        }

        Log::debug('payu webhook called: ' . var_export($input, true));


    }

}

