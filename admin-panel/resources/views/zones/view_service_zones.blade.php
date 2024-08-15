 <meta name="csrf-token" content="{{ Session::token() }}"> 
<div class="container">
     <div class="col-md-2 col-md-offset-10">
        <a href='/admin/zones' class='btn btn-info pull-right' style='margin-right:20px;'>Back</a>
    </div>
<style>
#map {
  height: 500px;
  width: 100%;
  margin-top: 50px;
  padding: 0px
}
</style>

<script src="https://maps.googleapis.com/maps/api/js?libraries=drawing&key={{ env('MAP_KEY') }}"></script>

<div id="map"></div>
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

  const bermudaTriangle = new google.maps.Polygon({
    paths: {!! $polygon !!},
    strokeColor: "#FF0000",
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: "#FF0000",
    fillOpacity: 0.35,
  });

  bermudaTriangle.setMap(map);
 
};
google.maps.event.addDomListener(window, "load", initMap);
</script>
</div>