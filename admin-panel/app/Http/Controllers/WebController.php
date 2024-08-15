<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Redirect;
use App\Models\Zone;
use Mail;
use App\VehicleCategory;
use App\AppSetting;
class WebController extends Controller
{
    public function doRegister(Request $request)
    {   
         $input = $request->all();
        $validator = Validator::make($input, [
          'name' => 'required', // make sure the email is an actual email
          'email' => 'required',
          'text' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::to('contact')->withErrors($validator);
        }
        else
        {
        $data = array();
        // $email= AppSettig::where('id',1)->value('email');
        
        // $data['name'] = $input['name'];
        // $data['email'] = $input['email'];
        // $data['text'] = $input['text'];
        // $mail_header = array("data" => $data);
        // $this->contact_register($mail_header,'Mail received',$input['email']);
          return view('contact',['message' => 'Mail Sent Successfully']);
        }
    }
    
    public function save_polygon(Request $request){
        $input = $request->all();
        Zone::where('id',$input['id'])->update([ 'polygon' => $input['polygon']]);
    }
    
    public function create_zone($id,$capital_lat,$capital_lng){
        return view('zones.zone_map',[ 'id' => $id, 'capital_lat' => $capital_lat, 'capital_lng' => $capital_lng ]);
    }
    
    public function dispatch_panel(){
        //$default_country = AppSetting::where('id',1)->value('default_country');
        $categories = VehicleCategory::where('status',1)->get();
        $path = [];
        $i = 0;
        foreach($categories as $key => $value){
                $path[$i] = '/drivers/'.$value->id;
                $i++;
        }
        $data['path'] = json_encode($path);
        $data['path'] = preg_replace("_\\\_", "\\\\\\", $data['path']);
        $data['path'] = preg_replace("/\"/", "\\\"", $data['path']);

        return view('admin.dispatch_map',$data);
    }
}
