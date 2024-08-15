<div style="border-width:2px;border-color:'#FFFFFF';overflow: hidden" class="col-md-12">
    @if(isset(Admin::user()->id))
    <div class="col-md-6">
        <ul class="list-group list-group-flush" wire:poll="render">
            @foreach($users as $user)
                @php
                    $not_seen = \App\Models\CustomerChatMessage::where('user_id', $user->id)->where('receiver', Admin::user()->id)->where('is_seen', false)->get() ?? null
                @endphp
               
                    <Button class="list-group-item" style="margin:10px" onclick="custom_scroll()" wire:click="getUser({{ $user->id }})" id="user_{{ $user->id }}">
                        <img class="img-fluid avatar" style="width:30px;height:30px;" src="{{ env('IMG_URL').$user->profile_picture }}">
                        {{ $user->first_name }}
                        @if(filled($not_seen))
                            <div class="badge badge-success rounded">{{ $not_seen->count() }}</div>
                        @endif
                    </Button>
                
            @endforeach 
        </ul>
    </div>
    <div class="col-md-6">
        <h2>@if(isset($clicked_user)) {{ $clicked_user->first_name }} @endif</h2>
        <div style="margin-top:10px;" />
        <div class="scroll" id="chat-scroll" wire:poll.2000ms="refresh_message()">
             @if(isset($messages))
                @foreach($messages as $message)
                    @if($message->message)
                    <div @if(!$message->is_admin) class="container" @else class="container darker" @endif>
                      <img src="{{ env('IMG_URL') }}static_images/admin_chat.png" alt="Avatar" @if($message->is_admin) class="right" @endif style="width:100%;">
                      <h5 style="color:#000;">{{ $message->message}}</h5>
                      <span @if($message->is_admin) class="time-left" @else class="time-right" @endif>{{$message->created_at->diffForHumans()}}</span>
                    </div>
                    @endif
                        @if(isPhoto($message->file))

                        <div class="row" style="width:100%">
                            <div @if($message->is_admin) class="admin_file" @else class="user_file" @endif>
                                <img class="img-fluid rounded" @if($message->is_admin) align="right" @else align="left" @endif  loading="lazy" style="height: 250px;width:200px;flex-grow: 1;" src="{{ $message->file }}">
                                <!--<p @if($message->is_admin) class="time-left" else class="time-right" @endif>11:00</p>-->
                            </div>
                        </div>
                        @elseif (isVideo($message->file))

                            <div class="row" style="width:100%">
                                <div @if($message->is_admin) class="admin_file" @else class="user_file" @endif>
                                    <video style="height: 250px" class="img-fluid rounded" controls>
                                        <source src="{{ $message->file }}">
                                    </video>
                                </div>
                                <!--<p @if($message->is_admin) class="time-left row" else class="time-right row" @endif>11:00</p>-->
                            </div>

                        @elseif ($message->file)

                            <div class="row" style="width:100%">
                                <div @if($message->is_admin) class="admin_file" @else class="user_file" @endif>
                                    <a href="{{ $message->file}}" class="bg-light p-2 rounded-pill" target="_blank"><i class="fa fa-download"></i> 
                                        {{ $message->file_name }}
                                    </a>
                                </div>
                                <!--<p @if($message->is_admin) class="time-left row" else class="time-right row" @endif>11:00</p>-->
                            </div>

                        @endif
                @endforeach
            @endif
        </div>
        @if(isset($clicked_user))
        <div class="card-footer">
            <form wire:submit.prevent="SendMessage" enctype="multipart/form-data">
                <div wire:loading wire:target='SendMessage'>
                    Sending message . . . 
                </div>
                <div wire:loading wire:target="file">
                    Uploading file . . .
                </div>
                @if($file)
                    <div class="mb-2">
                       You have an uploaded file <button type="button" wire:click="resetFile" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Remove {{ $file->getClientOriginalName() }}</button>
                    </div>
                @else
                    No file is uploaded.
                @endif
                <div class="row">
                    <div class="col-md-8">
                        <input wire:model="message" class="form-control input shadow-none w-100 d-inline-block" placeholder="Type a message" @if(!$file) required @endif>
                    </div>
                    <div class="col-md-4">
                        <button onclick="scroll_down()" class="btn btn-primary d-inline-block w-100"><i class="fa fa-paper-plane"></i> Send</button>
                    </div>
                </div>
                @if(empty($file))
                    <label for="file-upload">
                        <i class="fa fa-file-upload"></i>
                        <input type="file" wire:model="file">
                    </label>
                @endif
            </form>
        </div>
        @endif
    </div>
    @else
    <div class="col-md-12">
        <div class="scroll" id="mb-chat-scroll" wire:poll.2000ms="refresh_message()">
             @if(isset($messages))
                @foreach($messages as $message)
                    @if($message->message)
                    <div @if($message->is_admin) class="container" @else class="container darker" @endif>
                      <img src="https://www.w3schools.com/w3images/bandmember.jpg" alt="Avatar" @if(!$message->is_admin) class="right" @endif style="width:100%;">
                      <h6  @if($message->is_admin) style="color:#000;float:right;" @else style="color:#000;" @endif>{{ $message->message}}</h6>
                    </div>
                    @endif
                        @if(isPhoto($message->file))

                        <div class="row" style="width:100%">
                            <div @if($message->is_admin) class="admin_file" @else class="user_file" @endif>
                                <img class="img-fluid rounded" @if($message->is_admin) align="right" @else align="left" @endif  loading="lazy" style="height:250px;width:200px;flex-grow: 1;" src="{{ $message->file }}">
                            </div>
                        </div>
                        @elseif (isVideo($message->file))

                            <div class="row" style="width:100%">
                                <div @if($message->is_admin) class="admin_file" @else class="user_file" @endif>
                                    <video style="height:150px" class="img-fluid rounded" controls>
                                        <source src="{{ $message->file }}">
                                    </video>
                                </div>
                        
                            </div>

                        @elseif ($message->file)

                            <div class="row" style="width:100%">
                                <div @if($message->is_admin) class="admin_file" @else class="user_file" @endif>
                                    <a href="{{ $message->file}}" class="bg-light p-2 rounded-pill" target="_blank"><i class="fa fa-download"></i> 
                                        {{ $message->file_name }}
                                    </a>
                                </div>
                            </div>

                        @endif
                @endforeach
            @endif
            <div style="margin-bottom:50px;" />
        </div>
        <div class="card-footer" style="height:100px;position: fixed;bottom:0px;right:0px;width:90%;margin-right:5%;">
            
            <form wire:submit.prevent="SendMessageToAdmin" enctype="multipart/form-data">
                <div wire:loading wire:target='SendMessageToAdmin'>
                    Sending message . . . 
                </div>
                <div wire:loading wire:target="file">
                    Uploading file . . .
                </div>
                @if($file)
                    <div class="mb-2">
                       You have an uploaded file <button type="button" wire:click="resetFile" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Remove {{ $file->getClientOriginalName() }}</button>
                    </div>
                @else
                    No file is uploaded.
                @endif
                <div style="width:100%;margin-bottom:10px;flex-direction:row !important;display: flex !important;">
                    <div style="width:75%">
                        <input onChange="scroll_down()" wire:model="message" class="form-control input shadow-none w-100 d-inline-block" placeholder="Type a message" @if(!$file) required @endif>
                    </div>
                    
                    <div style="width:20%;margin-left:5%;">
                       <button onclick="scroll_down()" class="btn btn-primary"><i class="fa fa-paper-plane"></i>Send</button>
                    </div>
                    <!--<div style="width:40%">
                        <button onclick="scroll_down()" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send</button>
                    </div>-->
                </div>
                @if(empty($file))
                    <label for="file-upload">
                        <i class="fa fa-file-upload"></i>
                        <input type="file" wire:model="file">
                    </label>
                @endif
            </form>
        </div>
    </div>

    @endif
</div>

<script>

function scroll_down(){
    $('#mb-chat-scroll').animate({scrollTop: $('#mb-chat-scroll')[0].scrollHeight}, 1000);
}

function custom_scroll(){
    setTimeout(function() {
         $('#chat-scroll').animate({scrollTop: $('#chat-scroll')[0].scrollHeight}, 1000);
    }, 1000);   
}

$( document ).ready(function() {
  if('{{ $customer_id }}' > 0){
    $('#mb-chat-scroll').animate({scrollTop: $('#mb-chat-scroll')[0].scrollHeight}, 1000);
    $('#mb-chat-scroll').on('DOMSubtreeModified', function(e){
       e.stopImmediatePropagation();
       $('#mb-chat-scroll').animate({scrollTop: $('#mb-chat-scroll')[0].scrollHeight}, 1000)
    });
  }
   
   
});


</script>
