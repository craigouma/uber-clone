<meta name="csrf-token" content="{{ Session::token() }}"> 
<div class="container">
<div class="col-md-2 col-md-offset-10">
    <a href='/admin/zones' class='btn btn-info pull-right' style='margin-right:20px;'>Back</a>
</div>
<div style="margin-top:100px;"></div>
<iframe src="{{env('APP_URL')}}/create_zone/{{$id}}/{{$capital_lat}}/{{$capital_lng}}" width="100%" height="500" title="description"></iframe>
</div>