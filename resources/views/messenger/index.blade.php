<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Material Messaging App Concept</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

</head>
<body>
<!-- partial:index.partial.html -->
<body>
  <div class="container">
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <x-dropdown-link :href="route('logout')"
                onclick="event.preventDefault();
                            this.closest('form').submit();">
            {{ __('Log Out') }}
        </x-dropdown-link>
    </form>

    <div class="row">
      <nav class="menu">
        <ul class="items">
          <li class="item">
            <i class="fa fa-home" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-user" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </li>
          <li class="item item-active">
            <i class="fa fa-commenting" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-file" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-cog" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-cog" aria-hidden="true"></i>
          </li>
        </ul>
      </nav>
      <section class="discussions" id="discussions1" >
        <div class="discussion search">
          <div class="searchbar">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" placeholder="Search...">
          </div>
        </div>
        @foreach ($conversations as $conversation)
        <a href="{{route('messenger', $conversation->id)}}">
            <div class="discussion message-active">
                <div class="photo"></div>
                <div class="desc-contact">
                    <p class="name">{{ $conversation->participants[0]->name}}</p>
                    <p class="message">{{Str::words($conversation->lastMessage->body, 10)}}</p>
                </div>
                {{-- <div class="timer">{{$conversation->lastMessage->created_at->diffForHumans()}}</div> --}}
            </div>
        </a>
        @endforeach
      </section>
      <section class="discussions" id="discussions2" style="display: none">
        <div class="discussion search">
          <div class="searchbar">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" placeholder="Search...">
          </div>
        </div>
        <div class="friends">
            @php
                $prevLetter = '';
            @endphp

            @foreach ($friends as $friend)
                @php
                    $currentLetter = strtoupper(substr($friend->name, 0, 1));
                @endphp

                @if ($currentLetter != $prevLetter)
                    <div class="my-1">
                        <small class="text-uppercase text-muted">{{ $currentLetter }}</small>
                    </div>
                    @php
                        $prevLetter = $currentLetter;
                    @endphp
                @endif

                <div class="discussion message-active">
                    <div class="photo"></div>
                    <div class="desc-contact">
                        <p class="name">{{ $friend->name }}</p>
                    </div>
                    <div class="timer">12 sec</div>
                </div>
            @endforeach
        </div>
      </section>
      <section class="chat">
        <div class="header-chat">
        <i class="icon fa fa-user-o" aria-hidden="true"></i>
        <p class="name">Megan Leib</p>
        <i class="icon clickable fa fa-ellipsis-h right" aria-hidden="true"></i>
        </div>
        <div class="messages-chat">

            @foreach ($messages as $message)
                @if (Auth::id() == $message->user_id)
                <div class="message">
                    {{-- <small class="extra-small text-muted">{{$message->created_at->diffForHumans()}}</small> --}}

                    <p class="text"> {{$message->body}} </p>
                    <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);"></div>
                </div>

                @else

                <div class="message text-only">
                    <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);"></div>

                      <p class="text"> {{$message->body}}</p>
                      {{-- <small class="extra-small text-muted">{{$message->created_at->diffForHumans()}}</small> --}}

                    </div>
                @endif

            @endforeach


        </div>

        <form class="form-chat footer-chat" method="POST" action="{{route('api.messages.store')}}">
            @csrf
            <input type="hidden" name="conversation_id" class="conversation_id" value="{{$activeChat->id}}">
          <i class="icon fa fa-smile-o clickable" style="font-size:25pt;" aria-hidden="true"></i>
          <input type="text" class="write-message" name="message" placeholder="Type your message here">
          <input type="submit" >
        </div>
      </section>
    </div>


  </div>
</body>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{-- <script  src="{{asset('assets/js/script.js')}}"></script> --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>

function scrollToBottom() {
    var messagesContainer = document.querySelector('.messages-chat');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}
$('.form-chat').submit(function (e) {
    e.preventDefault();
    var form = $(this);
    $.post(form.attr('action'), form.serialize(), function(response){
        let msg = form.find('.write-message').val(); // Get the value of the input field
        let message = `
            <div class="message">
                <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);"></div>
                <p class="text">${msg}</p>
                <small class="extra-small text-muted"></small>
            </div>
        `;
        $('.messages-chat').append(message);
        scrollToBottom();
    });
});
$(document).ready(function () {
    $('.fa-user').click(function () {
        $('#discussions1').hide();
        $('#discussions2').show();
    });
    $('.fa-commenting').click(function () {
        $('#discussions1').show();
        $('#discussions2').hide();
    });
});
    const userID =  '{{Auth::id()}}'
    Pusher.logToConsole = true;
    var pusher = new Pusher('aee13f4e712c19249b1d', {
    cluster: 'ap2',
    authEndpoint: "/broadcasting/auth"
    });
    var channelName = 'presence-Messenger.' + userID;
    var channel = pusher.subscribe(channelName);
    channel.bind('new-message', function(data) {
        var message = data.message;
        var messageHTML = `
            <div class="message text-only">
                <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);"></div>
                <p class="text">${message.body}</p>
                <small class="extra-small text-muted"></small>
            </div>
        `;
        $('.messages-chat').append(messageHTML);
        scrollToBottom();
    });

</script>

</body>
</html>
