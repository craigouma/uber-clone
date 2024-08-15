

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<meta name="csrf-token" content="vxT0BT4kE7H08APtUEWNYFXSaJemYGD6zjMzlxMh">
<title>Admin  | Dispatch System</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="{{ env('APP_URL') }}/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ env('APP_URL') }}http://127.0.0.1:8000/vendor/laravel-admin/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ env('APP_URL') }}http://127.0.0.1:8000/vendor/laravel-admin/laravel-admin/laravel-admin.css">
<link rel="stylesheet" href="{{ env('APP_URL') }}http://127.0.0.1:8000/vendor/laravel-admin/bootstrap3-editable/css/bootstrap-editable.css">
<link rel="stylesheet" href="{{ env('APP_URL') }}http://127.0.0.1:8000/vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css">
<script src="{{ env('APP_URL') }}/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    
<style>
#map {
  height: 500px;
  width: 100%;
}
</style>
</head>
<body>
<div>
<div id="map"></div>
<script src="https://maps.googleapis.com/maps/api/js?&sensor=false&libraries=drawing&key={{ env('MAP_KEY') }}"></script>
<script>

   (function()
{
  if( window.localStorage )
  {
    if( !localStorage.getItem('firstLoad') )
    {
      localStorage['firstLoad'] = true;
      window.location.reload();
    }  
    else
      localStorage.removeItem('firstLoad');
  }
})();

var coordStr = "";

function initMap() {

  var map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: {!! $capital_lat !!},
      lng: {!! $capital_lng !!}
    },
    zoom: 10,
    
  });

  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.POLYGON,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: ['polygon'],
    },
    polygonOptions: {
      fillColor: "#FA8072 ",
      fillOpacity: 0.5,
      strokeWeight: 3,
      clickable: false,
      editable: true,
      zIndex: 1,
    },
  });

  google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
   drawingManager.setMap(null);
    
    for (var i = 0; i < polygon.getPath().getLength(); i++) {
      coordStr += polygon.getPath().getAt(i).toUrlValue(6) + ";";
    }
    save_polygon();
    console.log(coordStr);
  });
  drawingManager.setMap(map);
};


function save_polygon(){
   $.post('../../../save_polygon',
    {
        _token: "{{ csrf_token() }}",
        id: {!! $id !!},
        polygon: coordStr,
    })
    .error(
        
     )
    .success(
        
     );
}


google.maps.event.addDomListener(window, "load", initMap);
</script>
</div>
</body>
</html>
   

        