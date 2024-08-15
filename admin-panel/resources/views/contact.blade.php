

@include('templates.header')
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
        </ul>
          </div>    
        </div>
      </nav>
    </div>
  <header class="page-header" style="background-image: url(web/images/_homepage-1-bg.jpg);">
    <div class="container">
      <ol class="bread">
        <li>
          <a href="index.html"><span>Home</span></a>
        </li>
        <li class="divider"><span> | </span></li>
        <li>
          <span>Contacts</span>
        </li>
      </ol>   
      <h1>Contacts</h1>
    </div>
  </header>

  <section id="page-contacts">
    <div class="container">

      <div class="row">
      	 @if(@$message)
                    <div class="alert alert-success" role="alert">
                        {{ $message }}
                    </div>
                    @endif
        <div class="col-lg-6 col-md-6">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6">

              <h2 class="spanned"><span>Opening Hours:</span> 24/7</h2>
              <p>Nam eu mi eget velit vulputate tempor gravida quis massa. In malesuada condimentum ultrices. Sed et mauris a purus fermentum elementum. Sed tristique semper enim, et gravida orci iaculis et. Nulla facilisi. </p>
            </div>          
            <div class="col-lg-12 col-md-12 col-sm-6">
              <ul class="address">
                <li class="large"><span class="fa fa-phone"></span>800-5-800</li>
                <li><span class="fa fa-skype"></span>gettaxipark</li>
                <li><span class="fa fa-map-marker"></span>43 2-nd Avenue,  New York,  29004-7153</li>
              </ul>     
            </div>
            <div class="col-lg-12 col-sm-12">
              <strong>Social:</strong>
              <ul class="social social-big">
                <li><a href="#" class="social-fb fa fa-facebook"></a></li>
                <li><a href="#" class="social-twitter fa fa-twitter"></a></li>
                <li><a href="#" class="social-youtube fa fa-youtube"></a></li>
                <li><a href="#" class="social-inst fa fa-instagram"></a></li>
              </ul>
              <a href="#" class="btn btn-black-bordered btn-lg">Get Taxi Online</a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 contact-form">
        	   
               <form class="form form-sm " method="POST" action="/contact">
                        @csrf
            <h3 class="aligncenter">Send Message</h3>
            <div class="form-group">
              <label>Your name <span class="red">*</span></label>
              <input type="text" id="name" name="name" placeholder="Your name" class="ajaxField required @error('name') is-invalid @enderror">
              @error('name')
                                        <span class="invalid-feedback alert-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
            </div>
            <div class="form-group">
              <label>E-mail <span class="red">*</span></label>
              <input type="text" id="email" name="email" placeholder="E-mail" class=" ajaxField required @error('email') is-invalid @enderror">
              @error('email')
                                        <span class="invalid-feedback alert-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
            </div>
            <div class="form-group">            
              <label>Message <span class="red">*</span></label>
              <textarea id="text" name="text" placeholder="Enter Message" class=" ajaxField required @error('text') is-invalid @enderror"></textarea>
              @error('text')
                                        <span class="invalid-feedback alert-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
            </div>
            <input type="submit" name="send" value="Send" class="btn btn-yellow aligncenter btn-lg">
          </form>           
        </div>
      </div>
    </div>
  </section>

  <!-- Set google map coords and API key to yours -->
    <div id="map" data-lat="40.7529789" data-lng="-74.0044417" data-zoom="11"></div>


  <section id="partners">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-12">
          <h4 class="black margin-0">Our partners</h4>
          <h2 class="white margin-0">and clients</h2>
        </div>
        <div class="col-md-9 col-sm-12">
          <div class="row items">
              <div class="col-md-5ths col-sm-3 col-ms-4 col-xs-6"><a href="#"><img src="{{ asset('web/images/_partner-1.png')}}" alt="Partner"></a></div>
              <div class="col-md-5ths col-sm-3 col-ms-4 col-xs-6"><a href="#"><img src="{{ asset('web/images/_partner-2.png')}}" alt="Partner"></a></div>
              <div class="col-md-5ths col-sm-3 col-ms-4 col-xs-6"><a href="#"><img src="{{ asset('web/images/_partner-3.png')}}" alt="Partner"></a></div>
              <div class="col-md-5ths col-sm-3 col-ms-4 col-xs-6"><a href="#"><img src="{{ asset('web/images/_partner-4.png')}}" alt="Partner"></a></div>
              <div class="col-md-5ths col-sm-3 col-ms-4 col-xs-6"><a href="#"><img src="{{ asset('web/images/_partner-5.png')}}" alt="Partner"></a></div>
          </div>        
        </div>        
      </div>
    </div>
  </section>
@include('templates.footer')