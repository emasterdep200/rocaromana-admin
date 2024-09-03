@extends('layouts.main')

@section('title')
    {{ __('Messages ') }}
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-md-12">
                <h4>@yield('title')</h4>

            </div>

        </div>
    </div>
@endsection

@section('content')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <div class="container-fluid">
        <div class="panel messages-panel">
            <div class="contacts-list">
                <div class="tab-content">
                    <div id="inbox" class="contacts-outter-wrapper tab-pane active">

                        <div class="contacts-outter">
                            <ul class="list-unstyled contacts">
                                @foreach ($user_list as $key => $value)
                                    @empty($value->receiver)
                                        <li data-toggle="tab" data-target="#inbox-message-1"
                                            id="{{ 'tabs' . $value->sender->id }}"
                                            onclick="setallMessage({{ $value->property_id }}, {{ $value->sender->id }});"
                                            style="display: flex;">

                                            <img alt="" class="img-circle medium-image"
                                                src="{{ $value->sender->profile ? $value->sender->profile : 'https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon-png.png' }}">

                                            <div class="vcentered info-combo">
                                                <h3 class="no-margin-bottom name"> <b>{{ $value->sender->name }}</b> </h3>

                                                <h5> {{ $value->property->title }}</h5>
                                            </div>

                                        </li>
                                    @endempty
                                @endforeach

                                @foreach ($user_list as $key => $value)
                                    @empty($value->sender)
                                        <li data-toggle="tab" data-target="#inbox-message-1"
                                            id="{{ 'tabs' . $value->receiver->id }}"
                                            onclick="setallMessage({{ $value->property_id }}, {{ $value->receiver->id }});"
                                            style="display: flex;">

                                            <img alt="" class="img-circle medium-image"
                                                src="{{ $value->receiver->profile ? $value->receiver->profile : 'https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon-png.png' }}">

                                            <div class="vcentered info-combo">
                                                <h3 class="no-margin-bottom name"> <b>{{ $value->receiver->name }} </b></h3>

                                                <h5> {{ $value->property->title }}</h5>

                                            </div>

                                        </li>
                                    @endempty
                                @endforeach
                                @foreach ($otherUsers as $key => $value)
                                    <li data-toggle="tab" data-target="#inbox-message-1"
                                        id="{{ 'tabs' . $value['customer_id'] }}"
                                        onclick="setallMessage({{ $value['proeperty_id'] }}, {{ $value['customer_id'] }});"
                                        style="display: flex;">

                                        <img alt="" class="img-circle medium-image"
                                            src="{{ $value['profile'] ? $value['profile'] : 'https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon-png.png' }}">

                                        <div class="vcentered info-combo">
                                            <h3 class="no-margin-bottom name"> <b>{{ $value['name'] }}</b> </h3>
                                            <h5> {{ $value['title'] }}</h5>

                                        </div>

                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-content">

                <div class="tab-pane message-body" id="inbox-message-1">
                    <div class="chat_header">

                    </div>

                    <div class="message-chat" id="myscroll" style="background-color: #ffffff;">
                        <div class="chat-body myscroll" id="chat">

                        </div>

                        <div class="chat-footer">

                            <form method="POST" id='chat_form'>

                                @csrf

                                <input type="hidden" name="prop_id" id="prop_id">
                                <input type="hidden" name="inquiry_by" id="inquiry_by">
                                <input type="hidden" name="inquiry" id="inquiry">

                                <input type="hidden" name="receiver_id" id=receiver_id>
                                <input type="hidden" name="sender_id" id="sender_id" value="0">

                                <input type="hidden" name="apiKey" id="apiKey"
                                    value="{{ $firebase_settings['apiKey'] ? $firebase_settings['apiKey'] : '' }}">

                                <input type="hidden" name="authDomain" id="authDomain"
                                    value="{{ $firebase_settings['authDomain'] ? $firebase_settings['authDomain'] : '' }}">

                                <input type="hidden" name="projectId" id="projectId"
                                    value="{{ $firebase_settings['projectId'] ? $firebase_settings['projectId'] : '' }}">

                                <input type="hidden" name="storageBucket" id="storageBucket"
                                    value="{{ $firebase_settings['storageBucket'] ? $firebase_settings['storageBucket'] : '' }}">

                                <input type="hidden" name="messagingSenderId" id="messagingSenderId"
                                    value="{{ $firebase_settings['messagingSenderId'] ? $firebase_settings['messagingSenderId'] : '' }}">

                                <input type="hidden" name="appId" id="appId"
                                    value="{{ $firebase_settings['appId'] ? $firebase_settings['appId'] : '' }}">

                                <input type="hidden" name="measurementId" id="measurementId"
                                    value="{{ $firebase_settings['measurementId'] ? $firebase_settings['measurementId'] : '' }}">

                                <input type="text" class="send-message-text" id="Onmessage" />

                                <label class="audio-button">
                                    <input type="button" value="" id="start-btn" class="js-start"
                                        style="display: none">
                                    <input type="hidden" name="aud" id="aud" style="display:none">

                                    <i class="bi bi-mic"></i>
                                    <h6 id='record'>Recording</h6>
                                    <div class="audio-wrapper" style="display: none">
                                        <audio src="" controls class="js-audio audio" name="audio_file"></audio>
                                    </div>
                                </label>

                                <label class="upload-file">
                                    <input type="file" name="attachment" id="Homeattachment">
                                    <i class="fa fa-paperclip"></i>
                                </label>
                                <button type="button" class="send-message-button chat-send btn-info"
                                    onclick="OnsendMessage();">
                                    Send <i class="fa fa-send"></i>
                                </button>
                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script>
            $(document).ready(function() {
                $('.list-unstyled.contacts li:first').attr('class', 'active');
                $('.active').click();

                $('.send-message-text').keydown(function(e) {

                    if (e.keyCode == 13) {
                        e.preventDefault();
                        $('.send-message-button').click();
                    }
                });

                $('#record').hide();

                var isToggled = false;
                var apiKey = $('#apiKey').val();
                var authDomain = $('#authDomain').val();
                var projectId = $('#projectId').val();
                var storageBucket = $('#storageBucket').val();
                var messagingSenderId = $('#messagingSenderId').val();
                var measurementId = $('#measurementId').val();
            });

            const chunks = [];

            // We will set this to our MediaRecorder instance later.
            let recorder = null;

            // We'll save some html elements here once the page has loaded.
            let audioElement = null;
            let startButton = null;
            let stopButton = null;

            /**
             * Save a new chunk of audio.
             * @param  {MediaRecorderEvent} event
             */
            const saveChunkToRecording = (event) => {
                chunks.push(event.data);
            };

            /**
             * Save the recording as a data-url.
             * @return {[type]}       [description]
             */
            const saveRecording = () => {
                const blob = new Blob(chunks, {
                    type: 'audio/mp3; codecs=opus'
                });
                const url = URL.createObjectURL(blob);

                audioElement.setAttribute('src', url);
                const input = document.querySelector('.js-audio');
                input.value = url;

                // Convert Blob to data URL
                const reader = new FileReader();
                reader.onload = () => {
                    const dataUrl = reader.result;
                    const hiddenInput = document.querySelector('#aud');
                    hiddenInput.value = dataUrl;
                };
                reader.readAsDataURL(blob);
            };


            /**
             * Start recording.
             */
            const startRecording = () => {
                recorder.start();
            };

            /**
             * Stop recording.
             */
            const stopRecording = () => {
                recorder.stop();
            };


            // Wait until everything has loaded
            (function() {
                audioElement = document.querySelector('.js-audio');



                btn = document.querySelector('#start-btn');

                startButton = document.querySelector('.js-start');
                stopButton = document.querySelector('.js-stop');

                // We'll get the user's audio input here.
                navigator.mediaDevices.getUserMedia({
                    audio: true // We are only interested in the audio
                }).then(stream => {

                    recorder = new MediaRecorder(stream);


                    recorder.ondataavailable = saveChunkToRecording;
                    recorder.onstop = saveRecording;
                });

                isToggled = false;
                $('#start-btn').click(function() {

                    var recordBtn = $('#start-btn');
                    recordBtn.removeClass('js-start');

                    if (!isToggled) {
                        recordBtn.removeClass('js-stop');
                        recordBtn.addClass('js-start');
                        isToggled = true;
                        $('#record').show();
                        recorder.start();
                    } else {
                        recordBtn.removeClass('js-start');
                        recordBtn.addClass('js-stop');
                        isToggled = false;
                        $('#record').hide();
                        recorder.stop();
                    }
                });

            })();



            // Add event listeners to the start and stop button


            function getAllMessage(offset, limit) {
                $.ajax({
                    url: 'getAllMessage',
                    type: "GET",
                    dataType: 'json',
                    data: {
                        property_id: $('#prop_id').val(),
                        client_id: $('#receiver_id').val(),
                        offset: offset,
                        limit: limit
                    },
                    async: true,
                    cache: false,
                    success: function(data) {
                        if (data != '') {
                            var html = '';
                            $("#chat").empty();
                            $.each(data.reverse(), function(i, item) {
                                if (item.attachment == "") {
                                    file = "";
                                } else {
                                    file = '<img alt="Pic" src="' + item.attachment +
                                        '" style="height: 216px;width: 216px;"/><br>'
                                }
                                if (item.audio == "") {
                                    audio = "";

                                } else {

                                    audio = '<audio controls>' +
                                        '<source src="' + item.audio + '" type="audio/ogg">' +
                                        '<source src="' + item.audio + '" type="audio/mpeg">' +
                                        'Your browser does not support the audio element.' +
                                        '</audio>';

                                }

                                // Create a Message div according to condition
                                let dataMessageDiv;
                                if(item.message){
                                    dataMessageDiv = `<div class="message-text">  ${audio}  ${file}  ${item.message} </div>`
                                }else{
                                    dataMessageDiv = `<div class="message-text">  ${audio}  ${file} </div>`
                                }
                                if (item.sender_type == '0') {

                                    html +=
                                        `<div class="message my-message">
                                            <img alt="" class="img-circle medium-image" src="https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon-png.png">
                                            <div class="message-body">
                                                <div class="message-body-inner" style="border-radius: 4px;background-color: #f5f5f4;padding:16px;">
                                                    ${dataMessageDiv}
                                                </div>
                                                <div style="background: #FFFFFF;">  ${item.time_ago} </div>
                                            </div>
                                        <br>
                                        </div>`;
                                } else {
                                    profile = item.sendeprofile ? item.sendeprofile : 'https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon-png.png';
                                    html +=
                                       `<div class="message info">
                                            <img alt="" class="img-circle medium-image" src="${profile}">
                                            <div class="message-body">
                                                <div class="message-body-inner" style="border-radius: 4px;background-color: #ECF5F5;padding:16px;">
                                                    ${dataMessageDiv}
                                                </div>
                                                <div style="background: #FFFFFF;justify-content: end;display:flex;">${item.time_ago}</div>
                                            </div>
                                        <br>
                                        </div>`;
                                }

                            });

                            $("#chat").html(html);
                            $('.myscroll').animate({
                                scrollTop: $('.myscroll').get(0).scrollHeight
                            }, 1500);

                        } else {
                            // $("#Onmessage").html("");
                            $("#chat").html("");
                            $("#chat").append("No message");

                        }
                    }
                });
            }


            function OnsendMessage() {
                var submitButton = ($('.chat-send'));

                submitButton.text('Sending...');
                submitButton.attr('disabled', 'disabled');

                var sender_by = $('#sender_id').val();
                var receive_by = $('#receiver_id').val();

                var message = $("#Onmessage").val();
                var attachment = $('#Homeattachment')[0].files;
                $('.progress').show();
                var fd = new FormData();

                fd.append('attachment', attachment[0]);
                fd.append('receiver_id', receive_by);
                fd.append('message', message);
                fd.append('property_id', $('#prop_id').val());
                fd.append('sender_type', 0);
                fd.append('sender_by', sender_by);
                fd.append('aud', $('#aud').val());


                if (message == "" && attachment.length == 0 && audio == "") {
                    alert('message not be empty');


                    $("#Onmessage").val("");
                    submitButton.text('Send');
                    return false;
                }


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(percentComplete + '%');
                                $(".progress-bar").html(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    url: 'store_chat',
                    enctype: 'multipart/form-data',
                    type: "POST",
                    dataType: 'json',
                    data: fd,
                    processData: false,
                    contentType: false,
                    async: true,
                    cache: false,

                    success: function(data) {
                        if (data.error == false) {
                            $('#Homeattachment').val('');
                            $('.custom-file-label').html('');
                            $("#Onmessage").val("");
                            getAllMessage(0, 10);
                            submitButton.text('Send');
                            submitButton.removeAttr('disabled');
                            $('#aud').val('');
                        }
                        if (data.error == true) {
                            $("#Onmessage").val("");
                            submitButton.text('Send');
                            alert('there is No Chat');
                        }
                    }
                });
            }


            function setallMessage(id, c_id) {

                $('.list-unstyled.contacts li.active').css('borderRight', '');
                $('#receiver_id').val(c_id);
                $('#prop_id').val(id);
                $("#chat").html("");
                $(".issueTitle").html("");

                // var issue = atob($('#' + id).data('issue'));
                property_id = id;
                client_id = c_id;

                $.ajax({
                    url: 'getAllMessage',
                    type: "GET",
                    dataType: 'json',
                    data: {
                        property_id: property_id,
                        client_id: c_id,
                        offset: 0,
                        limit: 10
                    },
                    async: true,
                    cache: false,
                    success: function(data) {


                        if (data != '') {
                            // Get the active tab
                            var activeTab = document.querySelector('#tabs' + c_id);

                            $('.list-unstyled.contacts li.active').css('borderRight',
                                '3px solid var(--bs-primary)');

                            // Check if the active tab exists and contains the desired elements
                            var username = activeTab.childNodes[3].childNodes[1].innerHTML;
                            var img_src = activeTab.childNodes[1].src;



                            var chatHeader = document.querySelector('.chat_header');
                            if (chatHeader) {
                                chatHeader.innerHTML =
                                    '<img alt="" class="img-circle medium-image" src="' + (
                                        img_src ? img_src : '') + '">' +
                                    '<div>' + (username ? username : '') + '</div>';
                            }




                            var html = '';
                            $("#chat").empty();


                            $.each(data.reverse(), function(i, item) {
                                if (item.attachment == "") {
                                    file = "";
                                } else {
                                    file = '<img alt="Pic" src="' + item.attachment + '"style="height: 216px;width: 216px;"/><br>'
                                }
                                if (item.audio == "") {
                                    audio = "";
                                } else {

                                    audio = '<audio controls>' +
                                        '<source src="' + item.audio + '" type="audio/ogg">' +
                                        '<source src="' + item.audio + '" type="audio/mpeg">' +
                                        'Your browser does not support the audio element.' +
                                        '</audio>';

                                }

                                // Create a Message div according to condition
                                let dataMessageDiv;
                                if(item.message){
                                    dataMessageDiv = `<div class="message-text">  ${audio}  ${file}  ${item.message} </div>`
                                }else{
                                    dataMessageDiv = `<div class="message-text">  ${audio}  ${file} </div>`
                                }
                                if (item.sender_type == '0') {

                                    html +=
                                        `<div class="message my-message">
                                            <img alt="" class="img-circle medium-image" src="https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon-png.png">
                                            <div class="message-body">
                                                <div class="message-body-inner" style="border-radius: 4px;background-color: #f5f5f4;padding:16px;">
                                                    ${dataMessageDiv}
                                                </div>
                                                <div style="background: #FFFFFF;">  ${item.time_ago} </div>
                                            </div>
                                        <br>
                                        </div>`;
                                } else {
                                    profile = item.sendeprofile ? item.sendeprofile : 'https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon-png.png';
                                    html +=
                                       `<div class="message info">
                                            <img alt="" class="img-circle medium-image" src="${profile}">
                                            <div class="message-body">
                                                <div class="message-body-inner" style="border-radius: 4px;background-color: #ECF5F5;padding:16px;">
                                                    ${dataMessageDiv}
                                                </div>
                                                <div style="background: #FFFFFF;justify-content: end;display:flex;">${item.time_ago}</div>
                                            </div>
                                        <br>
                                        </div>`;
                                }

                            });

                            $("#chat").html(html);
                            $('.myscroll').animate({
                                scrollTop: $('.myscroll').get(0).scrollHeight
                            }, 1500);
                        } else {
                            // Get the active tab
                            var activeTab = document.querySelector('#tabs' + c_id);


                            $('.list-unstyled.contacts li.active').css('borderRight',
                                '3px solid var(--bs-primary)');


                            var username = activeTab.childNodes[3].childNodes[1].innerHTML;
                            var img_src = activeTab.childNodes[1].src;



                            var chatHeader = document.querySelector('.chat_header');
                            if (chatHeader) {
                                chatHeader.innerHTML =
                                    '<img alt="" class="img-circle medium-image" src="' + (
                                        img_src ? img_src : '') + '">' +
                                    '<div>' + (username ? username : '') + '</div>';
                            }

                            // $("#Onmessage").html("");
                            $("#chat").html("");
                            $("#chat").append("No message");

                        }
                    }
                });
            }
        </script>
        <script type="text/javascript">
            messaging.onMessage(function(payload) {
                if (payload.data.property_id == $('#prop_id').val()){
                    if (payload.data.file == "") {
                        file = "";
                    } else {
                        file = '<img alt="Pic" src="' + payload.data.file +'" / style="height: 216px;width: 216px;"><br>'
                    }
                    if (payload.data.audio == "") {
                        audio = "";
                    } else {
                        audio = '<audio controls>' +
                            '<source src="' + payload.data.audio + '" type="audio/ogg">' +
                            '<source src="' + payload.data.audio + '" type="audio/mpeg">' +
                            'Your browser does not support the audio element.' +
                            '</audio>';


                    }
                    if(payload.data.user_profile){
                        profile = payload.data.user_profile;
                    }else{
                        profile = 'https://www.nicepng.com/png/detail/128-1280406_view-user-icon-png-user-circle-icon.png';
                    }
                    if (payload.data.type == 'chat') {
                        html1 = `<div class="message info">
                                    <img alt="" class="img-circle medium-image" src="${profile}">
                                    <div class="message-body">
                                        <div class="message-body-inner" style="border-radius: 4px;background-color: #ECF5F5;padding:16px;">
                                            <div class="message-text">
                                                ${audio}${file}${payload.data.message} <br>
                                            </div>
                                        </div>
                                        <div style="background: #FFFFFF;justify-content: end;display:flex;">
                                            ${payload.data.time_ago}
                                        </div>
                                    </div>
                                    <br>
                                </div>`;

                        $("#chat").append(html1);
                        $('.myscroll').animate({
                            scrollTop: $('.myscroll').get(0).scrollHeight
                        }, 1500);
                    }
                }
            })
        </script>
    @endsection
