<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\Customer;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Property;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $chat = new Chats();
        $chat->sender_id = $request->sender_by;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message ? $request->message : null;
        $chat->property_id = $request->property_id;

        if ($request->receiver_id == '' || !isset($request->receiver_id)) {
            $response['error'] = true;
            return response()->json($response);
        }



        $audio_data = $request->aud;
        if ($audio_data) {

            // Decode the data URL and extract the raw audio data
            $audio_data = str_replace('data:audio/mp3; codecs=opus;base64,', '', $audio_data);
            $audio_data = base64_decode($audio_data);

            // Save the audio data to a file
            $filename = uniqid() . '.mp3';
            $audiodestinationPath = public_path('images/chat_audio/') . $filename;
            if (!is_dir(dirname($audiodestinationPath))) {
                mkdir(dirname($audiodestinationPath), 0777, true);
            }

            file_put_contents($audiodestinationPath, $audio_data);


            $chat->audio = $filename;
        }
        $destinationPath = public_path('images') . config('global.CHAT_FILE');

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $imageName = microtime(true) . "." . $attachment->getClientOriginalExtension();
            $attachment->move($destinationPath, $imageName);
            $chat->file = $imageName;
        } else {
            $chat->file = '';
        }

        $chat->save();
        if($chat->sender_id == 0){
            $senderUserProfile = url('assets/images/faces/2.jpg');
        }else{
            $senderUserProfile = $chat->sender()->profile ?? null;
        }

        $fcm_id = [];
        $customer = Customer::select('id', 'name')->with(['usertokens' => function ($q) {
            $q->select('fcm_id', 'id', 'customer_id');
        }])->find($request->receiver_id);
        // dd($customer->usertokens);
        if ($customer && !empty($customer->usertokens)) {
            foreach ($customer->usertokens as $usertokens) {

                array_push($fcm_id, $usertokens->fcm_id);
            }

            $username = $customer->name;
        } else {
            $fcm_id = [];
        }


        $Property = Property::find($request->property_id);



        $chat_message_type = "";

        if (!empty($request->aud)) {
            $chat_message_type = "audio";
        } else if (!empty($request->file('attachment')) && $request->message == "") {
            $chat_message_type = "file";
        } else if (!empty($request->file('attachment')) && $request->message != "") {
            $chat_message_type = "file_and_text";
        } else if (empty($request->file('attachment')) && $request->message != "" && empty($request->aud)) {
            $chat_message_type = "text";
        }

        $fcmMsg = array(
            'title' => 'Message',
            'message' => $request->message,
            'type' => 'chat',
            'body' => $request->message,
            'sender_id' => (string)$request->sender_by,
            'receiver_id' => (string)$request->receiver_id,
            'username' => $username,
            'file' => $chat->file != '' ? $chat->file : '',
            'audio' => $chat->audio,
            'date' => $chat->created_at,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'sound' => 'default',
            'property_id' => (string)$Property->id,
            'property_title_image' => $Property->title_image,
            'title' => $Property->title,
            'chat_message_type' => $chat_message_type,
            'user_profile' => $senderUserProfile
        );

        send_push_notification($fcm_id, $fcmMsg);

        $response['error'] = false;
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function getChats()
    {
        // dd($_GET);
        $current_user = Auth::user()->id;

        $offset = 0;
        $limit = 10;
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

        $userListQuery = Chats::with(['sender', 'receiver', 'property'])
            ->select('id', 'sender_id', 'receiver_id', 'property_id', 'message', 'created_at')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('chats')
                    ->where('sender_id', 0)
                    ->orWhere('receiver_id', 0)
                    ->groupBy(DB::raw('IF(sender_id < receiver_id, CONCAT(sender_id, "-", receiver_id), CONCAT(receiver_id, "-", sender_id))'), 'property_id');
            })
            ->orderBy('id', 'desc');

        // User's List
        $user_list = $userListQuery->clone()->get();

        // Property ID from Chat
        $propertiesId = $userListQuery->clone()->pluck('property_id')->toArray();

        // Sender ID from Chat
        $sendersId = $userListQuery->clone()->pluck('sender_id')->toArray();

        // Receiver ID from Chat
        $receiversId = $userListQuery->clone()->pluck('receiver_id')->toArray();

        // Merge UserIds
        $user_array = array_merge($sendersId, $receiversId);

        $otherUsers = Property::select('id', 'added_by', 'title', 'title_image')->with('customer', function ($q) use ($user_array) {
            $q->whereNotIn('id', $user_array);
        })->orWhereNotIn('id', $propertiesId)->groupBy('added_by')->get();

        $tempRow = array();
        foreach ($otherUsers as $key => $row) {
            if ($row->customer) {
                // foreach ($row->customer as  $customer) {
                $tempRow[$key]['proeperty_id'] = $row->id;
                $tempRow[$key]['title_image'] = $row->title_image;
                $tempRow[$key]['title'] = $row->title;
                $tempRow[$key]['customer_id'] = $row->customer->id;
                $tempRow[$key]['profile'] = $row->customer->profile;
                $tempRow[$key]['name'] = $row->customer->name;
            }
        }

        $firebase_settings = array();

        $firebase_settings['apiKey'] = system_setting('apiKey');
        $firebase_settings['authDomain'] = system_setting('authDomain');
        $firebase_settings['projectId'] = system_setting('projectId');
        $firebase_settings['storageBucket'] = system_setting('storageBucket');
        $firebase_settings['messagingSenderId'] = system_setting('messagingSenderId');
        $firebase_settings['appId'] = system_setting('appId');
        $firebase_settings['measurementId'] = system_setting('measurementId');
        return view('chat.index', ['user_list' => $user_list, 'firebase_settings' => $firebase_settings, 'otherUsers' => $tempRow]);
    }
    public function getAllMessage(Request $request)
    {

        $property_id = $request->propert_id;
        $offset = $request->offset ? $request->offset : 0;
        $limit = $request->limit ? $request->limit : 10;


        $chat = Chats::with('sender')->with('receiver')->with('property')->select('id', 'sender_id', 'receiver_id', 'message', 'audio', 'property_id', 'file', 'created_at')->where('property_id', $request->property_id)
            ->where(function ($query) use ($request) {
                $query->where('sender_id', $_GET['client_id'])
                    ->orWhere('receiver_id', $_GET['client_id']);
            })->orderBy('id', 'DESC')->get();


        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($chat as $row) {
            // dd($row->toArray());
            if ($row->sender_id  == 0 || $row->receiver_id == 0) {
                $tempRow['message'] = $row->message;

                $current = Carbon::parse(date('Y/m/d h:i:s'), 'Asia/Kolkata');
                $test = Carbon::parse(($row->created_at), 'Asia/Kolkata');

                $tempRow['time_ago'] = $row->created_at->diffForHumans(now(), CarbonInterface::DIFF_RELATIVE_AUTO, true);

                $tempRow['attachment'] = $row->file;
                $tempRow['audio'] = !empty($row->audio) ? $row->audio : '';


                if ($row->receiver_id == 0) {
                    $customer = Customer::find($row->sender_id);
                    if ($customer) {
                        $name = $customer->name;
                        $profile = $customer->profile;
                    } else {
                        $name = "Admin";
                        $profile = '';
                    }
                    $tempRow['sendeprofile'] = $profile;

                    $tempRow['sender_type'] = 1;

                    $tempRow['sendername'] = $name;
                }
                if ($row->sender_id  == 0) {



                    // $user = User::find($row->sender_id);

                    $customer = Customer::find($row->receiver_id);
                    if ($row->property->added_by != 0) {

                        $name = $customer->name;
                        $profile = $customer->profile;
                    }
                    if ($row->property->added_by == 0) {

                        $name = "Admin";
                        $profile = '';
                    }
                    // $tempRow['attachment'] = $row->file;

                    $tempRow['ssendeprofile'] = $profile;
                    $tempRow['ssendername'] = $name;

                    $tempRow['sender_type'] = 0;
                }

                $rows[] = $tempRow;
                $count++;
            }
        }

        $bulkData['rows'] = $rows;
        return response()->json($rows);
    }
}
