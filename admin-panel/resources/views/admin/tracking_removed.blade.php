
<head>
    <style>
        #map {
            width:100%;
            height: 100vh;
        }

        #over_map {
            position: absolute;
            top: 10px;
            left: 89%;
            z-index: 99;
            background-color: #ccffcc;
            padding: 10px;
        }
        
        .AnyUnusedClassName {
            color: #000000;
        }
    </style>
</head>
   <div class="AnyUnusedClassName" id="map"></div>

        <div id="over_map">
            <div>
                <span>Total Cars: </span><span id="cars">0</span>
            </div>
        </div>

        <!-- Firebase -->
        <script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script>
        <script>
            // Replace your Configuration here..
            var config = {
                  apiKey: "AIzaSyCMggptvLrwp08HETXQrCzz_Vtjf1JMeEY",
                  authDomain: "c2d-pro.firebaseapp.com",
                  databaseURL: "https://c2d-pro-default-rtdb.firebaseio.com",
                  projectId: "c2d-pro",
                  storageBucket: "c2d-pro.appspot.com",
                  messagingSenderId: "657662148125",
                  appId: "1:657662148125:web:358d3b5f34a5c97abc10f4"
            };
            firebase.initializeApp(config);
        </script>

        <script>
            
            // counter for online cars...
            var cars_count = 0;

            // markers array to store all the markers, so that we could remove marker when any car goes offline and its data will be remove from realtime database...
            var markers = [];
            var map;
            var path_list = JSON.parse("{!!$path!!}");
            path_list.map(load_cars)
            function initMap() { // Google Map Initialization... 
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 8,
                    center: new google.maps.LatLng(11.645320, 78.160546),
                    mapTypeId: 'terrain'
                });
            }

            // This Function will create a car icon with angle and add/display that marker on the map
            function AddCar(data) {
                var color = "";
                var status = "";
                if(data.val().online_status == 0){
                    color = "#C0C0C0";
                    status = "Offline";
                }else{
                    color = "#008000";
                    status = "Online";
                }
                
                if(data.val().booking.booking_status != 0){
                    color = "#FF0000";
                    status = "On Booking";
                }
                var icon = { // car icon
                    path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336   h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
                    scale: 0.6,
                    fillColor: color, //<-- Car Color, you can change it 
                    fillOpacity: 1,
                    strokeWeight: 1,
                    anchor: new google.maps.Point(0, 5),
                    rotation: data.val().geo.bearing //<-- Car angle
                };

                var uluru = { lat: data.val().geo.lat, lng: data.val().geo.lng };

                var marker = new google.maps.Marker({
                    position: uluru,
                    icon: icon,
                    map: map
                });
    
                markers[data.key] = marker; // add marker in the markers array...
                
                marker['infowindow'] = new google.maps.InfoWindow({
                    content: 'Driver Name : '+data.val().driver_name+'<br /> Status : '+status
                });
        
                google.maps.event.addListener(marker, 'click', function() {
                    this['infowindow'].open(map, this);
                });
                
                document.getElementById("cars").innerHTML = cars_count;
            }
            
            function load_cars(data,index){
                
                var index = firebase.database().ref(data);
                index.on('child_added', function (data) {
                    cars_count++;
                    AddCar(data);
                });
    
                
                index.on('child_changed', function (data) {
                    markers[data.key].setMap(null);
                    AddCar(data);
                });
    
               
                index.on('child_removed', function (data) {
                    markers[data.key].setMap(null);
                    cars_count--;
                    document.getElementById("cars").innerHTML = cars_count;
                });
            }
            

        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_KEY') }}&callback=initMap">
        </script>