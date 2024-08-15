<body>
<div class="row">
    <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Collapsable</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fa fa-users"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text" style="color:#333333"><b>Total Drivers</b></span>
                  <span class="info-box-number" style="color:#333333"><b>{{ $total_drivers }}</b></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-orange"><i class="fa fa-user"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text" style="color:#333333"><b>Active Drivers</b></span>
                  <span class="info-box-number" style="color:#333333"><b>{{ $active_drivers }}</b></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
    
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
    
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-maroon"><i class="fa fa-money"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text" style="color:#333333"><b>Today Trips</b></span>
                  <span class="info-box-number" style="color:#333333"><b>{{ $total_trips }}</b></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-usd"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text" style="color:#333333"><b>Today Customers</b></span>
                  <span class="info-box-number" style="color:#333333"><b>{{ $total_customers }}</b></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>
          
          <div class="col-md-12">
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/create-trips/create" target="_blank"  class="btn btn-app" style="width:100%">
                    <i class="fa fa-plus"></i> Create Trip
                </a> 
            </div>
            <div class="col-md-2">
               <a class="btn btn-app" data-toggle="modal" data-target="#modal-search-trip"  style="width:100%">
                    <i class="fa fa-search"></i> Search Trip
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/trips?status=1&status=2&status=3" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-spinner"></i> Ongoing Trips
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/trips?status=6&status=7" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-ban"></i> Cancelled Trips
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/trips?status=5" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-check"></i> Completed Trips
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/trips?booking_type=1" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-clock-o"></i> Instant Trips 
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/trips?booking_type=2" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-times-circle"></i> Scheduled Trips
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/drivers?&online_status=1" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-user"></i> Online Driver
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/drivers?&online_status=0" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-user-times"></i> Offline Drivers
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/drivers" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-navicon"></i> All Drivers
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/customers?&status=1" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-search"></i> Search Customer
                </a> 
            </div>
            <div class="col-md-2">
               <a href="{{ env('APP_URL')}}/admin/customers" target="_blank" class="btn btn-app" style="width:100%">
                    <i class="fa fa-users"></i> All Customers
                </a> 
            </div>
          </div>
        
        </div>
        <!-- /.box-body -->
      </div>
</div>
<iframe src="../dispatch_panel" width="100%" height="500" title="description"></iframe>
<div class="modal fade" id="modal-add-trip">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 style="color:#000000;">Add Trip</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <label for="pickup_address">Pickup address</label>
                  <input type="text" autocomplete="on" runat="server" class="form-control" id="pickup_address" name="pickup_address" placeholder="Pickup Address">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Drop address</label>
                  <input type="text" class="form-control" id="drop_address" name="drop_address" placeholder="Drop address">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Customer name</label>
                  <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Customer phone number</label>
                  <input type="email" class="form-control" id="phone_number" name="phone_number" placeholder="Customer phone number">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <label for="driver_id">Driver</label>
                  <input type="text" class="form-control" id="driver_id" name="driver_id" placeholder="Driver Name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label for="Fare">Fare</label>
                  <input type="text" readonly class="form-control" id="fare" name="fare" placeholder="0">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <button type="button" onclick="find_trip()" class="btn btn-primary btn-block">Create Booking</button>
            </div>
        </div>
        
    </div>
    <div class="modal-footer justify-content-between">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="button" onclick="reset_find_trip()" class="btn btn-default">Reset</button>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-search-trip">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 style="color:#000000;">Search Trip</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-offset-3 col-md-3">
                    <input type="text" class="form-control" id="find_trip_id" name="find_trip_id" placeholder="Enter Trip ID ..."> 
                </div>
                <div class="col-md-3">
                    <button type="button" onclick="find_trip()" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
                </div>
                <div style="padding:30px;" />
            </div>
            <div id="load_find_trip_data">
                <h4 style="color:#000"><center>Search your trip here...</center></h4>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="button" onclick="reset_find_trip()" class="btn btn-default">Reset</button>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    //Find trip
    function find_trip(){
        var trip_id = $("#find_trip_id").val();
        $.ajax({
            url: '/find_trip',
            type: 'POST',
            data: {trip_id : trip_id, '_token': "{{ csrf_token() }}"},
            success: function(data) {
                
                $("#load_find_trip_data").html(data);
            }       
        })
    }
    
    function reset_find_trip(){
        $("#find_trip_id").val("");
        $("#load_find_trip_data").html('<h4 style="color:#000"><center>Search your trip here...</center></h4>');
    }
    
    function open_tab(url) {
        window.open(url, '_blank').focus();
    }

</script>
</body>
   