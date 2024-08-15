@include('templates.header')
<div id="homepage-block-1" class="bgMove" style="background-image: url({{ asset('web/images/_homepage-1-bg.jpg') }});">
		<div class="nav-wrapper" id="nav-wrapper">
			<nav class="navbar navbar-static">
				<div class="container"> 
					<a class="logo" href="/"><img src="{{ asset('web/images/logo.png')}}" alt="TaxiPark"></a>
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar top-bar"></span>
							<span class="icon-bar middle-bar"></span>
							<span class="icon-bar bottom-bar"></span>
						</button>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<button type="button" class="navbar-toggle collapsed">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar top-bar"></span>
							<span class="icon-bar middle-bar"></span>
							<span class="icon-bar bottom-bar"></span>
						</button>					
						<ul class="nav navbar-nav">
							
					<li><a href="/">Home</a></li>
					<li><a href="#services">Services</a></li>
					<li><a href="#tariffs">Tariffs</a></li>
					<li><a href="#testimonials">Testimonial</a></li>
					<li><a href="#contact">Contact US</a></li>
				</ul>
					</div>		
				</div>
			</nav>
		</div>
		<div class="container content">
			<div class="get-icon">
				<h4>Get Taxi Now</h4>
				<div class="phone">800-5-800</div>
				<span class="fa fa-phone-square"></span>
			</div>
			<div id="large-image">
				<img src="{{ asset('web/images/_car-big.png')}}" class="car" alt="Taxi">
				<img src="{{ asset('web/images/_car-splash.png')}}" class="splash" alt="Taxi">
			</div>
		</div>
	</div>
	<div class="taxi-form-full">
		<div class="container">	
			<form class="form-validate">
				<div class="menu-types">
					<a href="#" data-value="Standart" class="type-1 active">Standart</a>
					<a href="#" data-value="Business" class="type-2">Business</a>
					<a href="#" data-value="VIP" class="type-3 red">Vip</a>
					<a href="#" data-value="Bus" class="type-4">Bus-Minivan</a>
					<input type="hidden" id="type-value" name="type-value" class="type-value" value="1">
				</div>
			</form>
		</div>
	</div>

		<section id="services">
		<div class="container">
			<h4 class="yellow">Welcome</h4>
			<h2 class="h1">Our Services</h2>
			<div class="row">
				<div class="col-md-3 col-sm-6 col-ms-6 matchHeight">	
					<div class="image"><img src="{{ asset('web/images/daily_booking.png')}}" alt="Service"></div>
					<h5>Daily Booking</h5>
					<p>We will bring you quickly and comfortably to anywhere in your city</p>
				</div>
				<div class="col-md-3 col-sm-6 col-ms-6 matchHeight">	
					<div class="image"><img src="{{ asset('web/images/outstation_booking.png')}}" alt="Service"></div>
					<h5>Outstation Booking</h5>
					<p>If you need a comfortable hotel, our operators will book it for you, and take a taxi to the address</p>
				</div>
				<div class="col-md-3 col-sm-6 col-ms-6 matchHeight">	
					<div class="image"><img src="{{ asset('web/images/rental_booking.png')}}"></div>
					<h5>Rental Booking</h5>
					<p>We will bring you quickly and comfortably to anywhere in your city</p>
				</div>
				<div class="col-md-3 col-sm-6 col-ms-6 matchHeight">	
					<div class="image"><img src="{{ asset('web/images/schedule_booking.png')}}"></div>
					<h5>Schedule Booking</h5>
					<p>We will bring you quickly and comfortably to anywhere in your city</p>
				</div>
				
			</div>
		</div>
	</section>		
		<section id="download" class="parallax" style="background-image: url({{ asset('web/images/_download-bg.jpg')}});">
		<div class="container">
			<h4 class="yellow">Get More Benefits</h4>
			<h2 class="h1">Download The App</h2>
			<div class="row">
				<div class="col-md-4 col-sm-12">
					<div class="items row">
						<div class="col-md-2 visible-md visible-lg"><span class="num">01.</span></div>
						<div class="col-md-10">
							<h5 class="yellow">Fast booking</h5>
							<p>Nam ac ligula congue, interdum enim sit amet, fermentum nisi.</p>
						</div>
						<div class="col-md-2 visible-md visible-lg"><span class="num">02.</span></div>
						<div class="col-md-10">
							<h5 class="yellow">Easy to use</h5>
							<p>Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
						</div>						
					</div>
				</div>
				<div class="col-md-4 col-md-push-4 col-sm-12">
					<div class="items items-right row">
						<div class="col-md-10">
							<h5 class="yellow">GPS searching</h5>
							<p>Ut elementum tincidunt erat vel ornare. Suspendisse ac felis non diam pretium.</p>
						</div>
						<div class="col-md-2 visible-md visible-lg"><span class="num">03.</span></div>
						<div class="col-md-10">
							<h5 class="yellow">Bonuses for ride</h5>
							<p>Phasellus l et porta tortor dignissim at. Pellentesque gravida tortor at euismod mollis. </p>
						</div>						
						<div class="col-md-2 visible-md visible-lg"><span class="num">04.</span></div>
					</div>				
				</div>				
				<div class="col-md-4 col-md-pull-4 col-sm-12">
					<div class="mob">
						<a href="#"><img src="{{ asset('web/images/_app-google.png')}}" alt="App"></a>
						<a href="#"><img src="{{ asset('web/images/_app-apple.png')}}" alt="App"></a>
					</div>
				</div>

			</div>
		</div>
	</section>			
	<section id="car-block">
	 	<div class="car-right animation-block"><img src="{{ asset('web/images/_car-big-side.png')}}" alt="Car"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<h4 class="yellow">For Drivers</h4>
					<h2 class="h1">Do You Want To Earn With Us?</h2>
				</div>
				<div class="col-md-6">
					<p>Quisque sollicitudin feugiat risus, eu posuere ex euismod eu. Phasellus hendrerit, massa efficitur dapibus pulvinar, sapien eros sodales ante, euismod aliquet nulla metus a mauris. </p>

					<ul class="check two-col strong">
						<li>Luxury cars</li>
						<li>No fee</li>
						<li>Weekly payment</li>
						<li>Fixed price</li>
						<li>Good application</li>
						<li>Stable orders</li>
					</ul>

					<a href="#" class="btn btn-yellow btn-lg btn-white">Become a Driver</a>
				</div>
			</div>
		</div>
	</section>
	<section id="testimonials">
		<hr class="lg">
		<div class="container">
			<h4 class="yellow">Happy Client's</h4>
			<h2 class="h1">Testimonials</h2>
			
			<div class="swiper-container row" id="testimonials-slider">
				<div class="swiper-wrapper">
					<div class="col-md-4 col-sm-6 swiper-slide">
						<div class="inner matchHeight">
							<div class="text">
								<p>Nullam orci dui, dictum et magna sollicitudin, tempor blandit erat. Maecenas suscipit tellus sit amet augue placerat fringilla a id lacus. Fusce tincidunt in leo lacinia condimentum.</p>
							</div>
							<div class="quote">
								<span class="fa fa-quote-left"></span>
								<div class="name">Anastasia Stone</div>
								<img src="{{ asset('web/images/_client-1.jpg')}}" alt="Client">
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 swiper-slide">
						<div class="inner matchHeight">
							<div class="text">
								<p>Suspendisse nec arcu sed nibh lacinia pretium. Phasellus eros ligula, mattis id rutrum non, eleifend vitae lacus. </p>
							</div>				
							<div class="quote">		
								<span class="fa fa-quote-left"></span>
								<div class="name">Steven Rashford</div>
								<img src="{{ asset('web/images/_client-4.jpg')}}" alt="Client">
							</div>
						</div>
					</div>	
					<div class="col-md-4 col-sm-6 swiper-slide">
						<div class="inner matchHeight">
							<div class="text">
								<p>Quisque sollicitudin feugiat risus, eu posuere ex euismod eu. Phasellus hendrerit, massa efficitur dapibus pulvinar, sapien eros sodales ante, euismod aliquet nulla metus a mauris. </p>
							</div>			
							<div class="quote">			
								<span class="fa fa-quote-left"></span>
								<div class="name">Patrick James</div>
								<img src="{{ asset('web/images/_client-5.jpg')}}" alt="Client">
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 swiper-slide">
						<div class="inner matchHeight">
							<div class="text">
								<p>Nullam orci dui, dictum et magna sollicitudin, tempor blandit erat. Maecenas suscipit tellus sit amet augue placerat fringilla a id lacus. Fusce tincidunt in leo lacinia condimentum.</p>
							</div>
							<div class="quote">
								<span class="fa fa-quote-left"></span>
								<div class="name">Anastasia Stone</div>
								<img src="{{ asset('web/images/_client-1.jpg')}}" alt="Client">
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 swiper-slide">
						<div class="inner matchHeight">
							<div class="text">
								<p>Suspendisse nec arcu sed nibh lacinia pretium. Phasellus eros ligula, mattis id rutrum non, eleifend vitae lacus. </p>
							</div>				
							<div class="quote">		
								<span class="fa fa-quote-left"></span>
								<div class="name">Steven Rashford</div>
								<img src="{{ asset('web/images/_client-4.jpg')}}" alt="Client">
							</div>
						</div>
					</div>	
					<div class="col-md-4 col-sm-6 swiper-slide">
						<div class="inner matchHeight">
							<div class="text">
								<p>Quisque sollicitudin feugiat risus, eu posuere ex euismod eu. Phasellus hendrerit, massa efficitur dapibus pulvinar, sapien eros sodales ante, euismod aliquet nulla metus a mauris. </p>
							</div>			
							<div class="quote">			
								<span class="fa fa-quote-left"></span>
								<div class="name">Patrick James</div>
								<img src="{{ asset('web/images/_client-5.jpg')}}a" alt="Client">
							</div>
						</div>
					</div>					
				</div>
				<div class="arrows">
					<a href="#" class="arrow-left fa fa-caret-left"></a>
					<a href="#" class="arrow-right fa fa-caret-right"></a>
				</div>				
			</div>
		</div>
	</section>		
	<section id="contact">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12 col-ms-6">
					<h4>About us</h4>
					<p>Nullam orci dui, dictum et magna sollicitudin, tempor blandit erat. Maecenas suscipit tellus sit amet augue placerat fringilla a id lacus. Fusce tincidunt in leo lacinia condimentum. </p>
				</div>
				<div class="col-lg-5 col-md-6 col-sm-6 col-ms-6">					
					<h4>Contact us</h4>
					<p><span class="yellow">Address:</span> 43 2-nd Avenue,  New York, NY 29004-7153</p>
					<ul class="address">
						<li><span class="fa fa-phone"></span>      800-5-800</li>
						<li><span class="fa fa-envelope"></span><a href="#">      gettaxi@taxipark.co.uk</a></li>
						<li><span class="fa fa-skype"></span>      gettaxipark</li>
					</ul>					
				</div>		
			</div>
		</div>
	</section>
@include('templates.footer')
