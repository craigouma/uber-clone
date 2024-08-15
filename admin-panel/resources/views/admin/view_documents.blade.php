<div class="container">
    <div class="col-lg-8">
        <div class="table-responsive">  
        <?php
                 if($id_proof != '' || $id_proof !== null){
                   $profile_image = env('APP_URL').'/uploads/'.$id_proof; 
                 }else{
                   $profile_image = env('IMG_URL').'uploads/images/avatar.png'; 
                 }
                 
                 $vehicle_image = env('APP_URL').'/uploads/'.$vehicle_image; 
                 $vehicle_certificate = env('APP_URL').'/uploads/'.$vehicle_certificate; 
                 $vehicle_insurance = env('APP_URL').'/uploads/'.$vehicle_insurance;
        ?>
          <table class="table">
              <tbody>
              <tr>
                <th>Id Proof</th>
                <td><img src="{{$profile_image}}" alt="tag"></td>
              </tr>
              <tr>
                <th>Vehicle Image</th>
                <td><img src="{{$vehicle_image}}" alt="tag"></td>
              </tr>
              </tbody>
              <tr>
                <th>Vehicle Certificate</th>
                <td><img src="{{$vehicle_certificate}}" alt="tag"></td>
              </tr>
              <tr>
                <th>Vehicle Insurance</th>
                <td><img src="{{$vehicle_insurance}}" alt="tag"></td>
              </tr>
             
          </table>
        </div>
        
    </div>
    
    <div class="col-md-2 col-md-offset-2">
        <a href='/admin/drivers' class='btn btn-info pull-right' style='margin-right:20px;'>Back</a>
    </div>
    
</div>