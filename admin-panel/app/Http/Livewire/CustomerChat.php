<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads; 
use App\Customer;
use Illuminate\Support\Facades\DB;
use Admin;
class CustomerChat extends Component
{
    use WithFileUploads;

    public $message = '';
    public $users = [];
    public $clicked_user;
    public $customer_id = 0;
    public $messages = [];
    public $file;
    public $admin;
    public function render()
    {
        return view('livewire.customer-chat', [
            'users' => $this->users,
            'admin' => $this->admin
        ]);
    }

    public function mount()
    {
        $this->mountComponent();
    }

    public function mountComponent() {
        if(isset(Admin::user()->id)){
            $this->users = Customer::where('status', 1)->orderBy('id', 'DESC')->get();
            if($this->clicked_user){
                $this->messages = \App\Models\CustomerChatMessage::where('user_id', $this->clicked_user->id)
                                                    ->orWhere('receiver', $this->clicked_user->id)
                                                    ->orderBy('id', 'ASC')
                                                    ->get();
            }
        }else{
            $url_data = explode('/',url()->full());
            $id = (int) $url_data[count($url_data)-1];
            if(is_int($id) && $id != 0){
                $this->customer_id = $id;
                $this->messages = \App\Models\CustomerChatMessage::where('user_id', $id)
                                                    ->orWhere('receiver', $id)
                                                    ->get();
            }
        }
    }
    
    public function refresh_message(){
        if(isset(Admin::user()->id)){
            if($this->clicked_user){
               \App\Models\CustomerChatMessage::where('user_id', $this->clicked_user->id)->update(["is_seen" => 1 ]);
                $this->messages = \App\Models\CustomerChatMessage::where('user_id', $this->clicked_user->id)
                                                    ->orWhere('receiver', $this->clicked_user->id)
                                                    ->orderBy('id', 'ASC')
                                                    ->get();
            }
        }else{
            $this->messages = \App\Models\CustomerChatMessage::where('user_id', $this->customer_id)
                                                ->orWhere('receiver', $this->customer_id)
                                                ->get();
        }
    }
    
    public function SendMessage() {
        /*$new_message = new \App\Models\Message();
        $new_message->message = $this->message;
        $new_message->user_id = auth()->id();
        if (!auth()->user()->is_admin == true) {
            $admin = User::where('is_admin', true)->first();
            $this->user_id = $admin->id;
        } else {
            $this->user_id = $this->clicked_user->id;
        }
        $new_message->receiver = $this->user_id;

        // Deal with the file if uploaded
        if ($this->file) {
            $file = $this->file->store('public/files');
            $path = url(Storage::url($file));
            $new_message->file = $path;
            $new_message->file_name = $this->file->getClientOriginalName();
        }
        $new_message->save();

        // Clear the message after it's sent
        $this->reset(['message']);
        $this->file = '';*/

        $new_message = new \App\Models\CustomerChatMessage();
        $new_message->message = $this->message;
        $new_message->user_id = Admin::user()->id;
        $new_message->is_admin = 1;
        $this->user_id = $this->clicked_user->id;
        $new_message->receiver = $this->user_id;
        $this->test = 1;
        // Deal with the file if uploaded
        if ($this->file) {
            $file = $this->file->store('public/files');
            $path = url(Storage::url($file));
            $new_message->file = $path;
            $new_message->file_name = $this->file->getClientOriginalName();
        }
        $new_message->save();
        
        $this->messages->push(\App\Models\CustomerChatMessage::where('id',$new_message->id)->first());
        // Clear the message after it's sent
        $this->reset(['message']);
        $this->file = '';
    }
    
    public function SendMessageToAdmin() {
        $new_message = new \App\Models\CustomerChatMessage();
        $new_message->message = $this->message;
        $new_message->user_id = $this->customer_id;
        $new_message->is_admin = 0;
        $new_message->receiver = 1;

        // Deal with the file if uploaded
        if ($this->file) {
            $file = $this->file->store('public/files');
            $path = url(Storage::url($file));
            $new_message->file = $path;
            $new_message->file_name = $this->file->getClientOriginalName();
        }
        $new_message->save();
        $this->messages->push(\App\Models\CustomerChatMessage::where('id',$new_message->id)->first());
        // Clear the message after it's sent
        $this->reset(['message']);
        $this->file = '';
    }

    public function getUser($user_id) 
    {
        $this->clicked_user = Customer::find($user_id);
        \App\Models\CustomerChatMessage::where('user_id', $user_id)->update(["is_seen" => 1 ]);
        $this->messages = \App\Models\CustomerChatMessage::where('user_id', $user_id)->orWhere('receiver', $user_id)->get();
    }

    public function resetFile() 
    {
        $this->reset('file');
    }
}
