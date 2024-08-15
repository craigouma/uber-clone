<?php

namespace App\Admin\Controllers;

use App\Models\DispatchTrip;
use Illuminate\Support\MessageBag;
use App\Customer;
use App\Driver;
use App\Models\Zone;
use App\TripRequest;
use App\PromoCode;
use DateTime;
use DateTimeZone;
use App\DriverVehicle;
use App\VehicleCategory;
use App\PaymentMethod;
use App\BookingStatus;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
class CreateTripController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Create Trip';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */ 
    protected function grid()
    {
        $grid = new Grid(new DispatchTrip());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('customer_name', __('Customer Name'));
        $grid->column('customer_phone', __('Customer Phone'));
        $grid->column('pickup_address', __('Pickup  address'));
        $grid->column('drop_address', __('Drop  address'));
        $grid->column('pickup_lat', __('Pickup  latitude'));
        $grid->column('pickup_lng', __('Pickup  longitute'));
        $grid->column('drop_lat', __('Drop  latitude'));
        $grid->column('drop_lng', __('Drop  longitute'));
       
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->disableExport();
       
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('customer_phone', 'Customer Phone');
        });

        return $grid;
    }

   
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DispatchTrip());
        $vehicles = VehicleCategory::where('vehicle_mode',18)->pluck('vehicle_type', 'id');
         
        $form->text('customer_name', __('Customer Name'))->rules('required|max:150');
        $form->text('customer_phone', __('Customer Phone'))->rules(function ($form) {
                return 'numeric|digits_between:6,20|required';
        })->help('Enter phone number without country code');
        $form->select('vehicle_type', __('Vehicle Type'))->options($vehicles)->rules('required');
        $form->latlong('pickup_lat', 'pickup_lng', 'Pickup Address')->default(['lat' => 9.827749, 'lng' => 78.310363])->rules('required');
        $form->latlong('drop_lat', 'drop_lng', 'Drop Address')->default(['lat' => 9.827749, 'lng' => 78.310363])->rules('required');
        
        $form->saved(function (Form $form) {
            $data = $this->get_distance($form->model()->pickup_lat,$form->model()->pickup_lng,$form->model()->drop_lat,$form->model()->drop_lng);
            $data['pickup_lat'] = $form->model()->pickup_lat;
            $data['pickup_lng'] = $form->model()->pickup_lng;
            $data['drop_lat'] = $form->model()->drop_lat;
            $data['drop_lng'] = $form->model()->drop_lng;
            $data['customer_name'] = $form->model()->customer_name;
            $data['phone_number'] = $form->model()->customer_phone;
            $data['vehicle_type'] = $form->model()->vehicle_type;
            $data['promo'] = 0;
            $this->update_dispatch_booking($data);
            $success = new MessageBag([
                'title'   => 'Created',
                'message' => 'Booking successfully created!',
            ]);
        
            return back()->with(compact('success'));
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete(); 
            $tools->disableView();
            $tools->disableList();
        });
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        return $form;
    }
    
    public function update_dispatch_booking($input){

        $customer = Customer::where('phone_number',$input['phone_number'])->first();
        $app_setting = DB::table('app_settings')->first();
    
        if(!is_object($customer)){
            $customer['first_name'] = $input['customer_name'];
            $customer['phone_number'] = $input['phone_number'];
            $customer['country_code'] = $app_setting->phone_code;
            $customer['phone_with_code'] = $app_setting->phone_code.$input['phone_number'];
            $customer['status'] = 1;
            Customer::create($customer);
            $customer = Customer::where('phone_number',$input['phone_number'])->first();
        }
        
        $data['km'] = $input['km'];
        $data['vehicle_type'] = $input['vehicle_type'];
        $data['customer_id'] = $customer->id;
        $data['booking_type'] = 2;
        $data['promo'] = 0;
        $data['pickup_address'] = $input['pickup_address'];
        $data['pickup_lat'] = $input['pickup_lat'];
        $data['pickup_lng'] = $input['pickup_lng'];
        $data['drop_address'] = $input['drop_address'];
        $data['drop_lat'] = $input['drop_lat'];
        $data['drop_lng'] = $input['drop_lng'];
        
         $url = 'https://maps.googleapis.com/maps/api/staticmap?center='.$input['pickup_lat'].','.$input['pickup_lng'].'&zoom=16&size=600x300&maptype=roadmap&markers=color:red%7Clabel:L%7C'.$input['pickup_lat'].','.$input['pickup_lng'].'&key='.env('MAP_KEY');
            $img = 'trip_request_static_map/'.md5(time()).'.png';
        file_put_contents('uploads/'.$img, file_get_contents($url));
        
        $fares = $this->calculate_daily_fare($data['vehicle_type'],$data['km'],$data['promo']);
        
        $booking_request = $data;
        $booking_request['distance'] = $data['km'];
        $booking_request['zone'] = $this->get_zone($data['pickup_lat'],$data['pickup_lng']);
        unset($booking_request['km']);
        $booking_request['total'] = $fares['total_fare'];
        $booking_request['sub_total'] = $fares['fare'];
        $booking_request['discount'] = $fares['discount'];
        $booking_request['tax'] = $fares['tax'];
        $booking_request['trip_type'] = 1;
        $booking_request['status'] = 2;
        $booking_request['booking_type'] = 2;
        $booking_request['package_id'] = 0;
        $booking_request['static_map'] = $img;
        $current_date = $this->get_date();
        $booking_request['pickup_date'] = date("Y/m/d H:i:s", strtotime($current_date."+30 minutes"));
        
        $id = TripRequest::create($booking_request)->id;
        
    }
    
    public function get_distance($pickup_lat, $pickup_lng, $drop_lat, $drop_lng){
        $url= 'https://maps.googleapis.com/maps/api/directions/json?origin='.$pickup_lat.','.$pickup_lng.'&destination='.$drop_lat.','.$drop_lng.'&key='.env('MAP_KEY');
        $data = [];
        
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_POST, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
         $response = curl_exec ($ch);
         $err = curl_error($ch); 
         curl_close ($ch);
         $result = json_decode($response);
         if(@$result->routes[0]->legs[0]->distance->text){
             
             $distance = str_replace(" km","",$result->routes[0]->legs[0]->distance->text);
             $distance = str_replace(" m","",$distance);
             $data['pickup_address'] = $result->routes[0]->legs[0]->start_address;
             $data['drop_address'] = $result->routes[0]->legs[0]->end_address;
             $data['km'] = $distance;
             return $data;
         }else{
             $data['pickup_address'] = '';
             $data['drop_address'] = '';
             $data['km'] = 0;
             return $data;
         }
    }
    
    public function calculate_daily_fare($vehicle_type,$km,$promo){
        
        $data = [];
        $vehicle = DB::table('daily_fare_management')->where('vehicle_type',$vehicle_type)->first();
        
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $additional_fare = number_format((float)$data['km'],2,'.','') * number_format((float)$data['price_per_km'],2,'.','');
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        return $data;
    }
    
    public function get_zone($lat,$lng){
        $zone = $this->find_in_polygon($lat,$lng);
        return $zone;
    }
    
    public function find_in_polygon($longitude_x,$latitude_y){
        $id = 1;
        $all_locations = Zone::all();
        foreach($all_locations as $key => $value){
            if($value->polygon){
                $polygon = explode(";",$value->polygon);
                $vertices_x = [];
                $vertices_y = [];
                foreach($polygon as $key => $value1){
                   $value1 = explode(",",$value1);
                   if(@$value1[1]){
                    $vertices_x[$key] = floatval($value1[0]);
                    $vertices_y[$key] = floatval($value1[1]);
                   }
                }

                $points_polygon = count($vertices_x); 

                if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                  return $value->id;
                } 
            }
            
        }
        return 0;
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
            $c = !$c;
      }

      return $c;
    }
    
    public function get_date(){
        $date = new DateTime();
        return $date->format('Y-m-d H:i:s');
    }
}
