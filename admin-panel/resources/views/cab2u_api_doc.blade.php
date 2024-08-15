<!doctype html>
<html lang="zxx">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CAB2U</title>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:600" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/applify.min.css"/>
</head>
<body data-fade_in="on-load">

<!-- Navbar Fixed -> Indigo -->
<nav class="navbar navbar-fixed-top navbar-light bg-white">
	<div class="container">

		<!-- Navbar Logo -->
		<a class="ui-variable-logo navbar-brand" href="/" title="Menpani - CAB2U Demo">
			<!-- Default Logo -->
			<img class="logo-default" src="{{ asset('web/cab2u/logo_1.png') }}"  data-uhd>
			<!-- Transparent Logo -->
			<img class="logo-transparent" src="{{ asset('web/cab2u/logo.png')}}"  data-uhd>
		</a><!-- .navbar-brand -->


		<!-- Navbar Navigation -->
					<div class="ui-navigation navbar-right">
						<ul class="nav navbar-nav">
							<!-- Nav Item -->
							<li>
								<a href="/" >Home</a>
							</li>
							<!-- Nav Item -->
							<li>
								<a href="#" data-scrollto="how-it-works">How It Works</a>
							</li>
							<li>
								<a href="#" data-scrollto="flow_videos">Flow Videos</a>
							</li>
							<!-- Nav Item -->
							<li>
								<a href="#" data-scrollto="pricing">Pricing</a>
							</li>
							<!-- Nav Item -->
							<li class="dropdown">
								<a href="#" data-scrollto="screenshots">Screenshots</a>
							</li>
							<!-- Nav Item -->
							<li>
								<a href="#" data-scrollto="faq">FAQ</a>
							</li>
							<!-- Nav Item -->
							<li>
								<a href="#" data-scrollto="contact_us" >Contact</a>
							</li>
						</ul><!--.navbar-nav -->
					</div><!--.ui-navigation -->
		
					<!-- Navbar Toggle -->
					<a href="#" class="ui-mobile-nav-toggle pull-right"></a>
		
				</div><!-- .container -->
			</nav> <!-- nav -->

<!-- Main Wrapper -->
<div class="main" role="main">

	<!-- Api Docs -->
	<div id="ui-api-docs" class="section bg-light">
		<div class="container">

			<div class="ui-card">
				<div class="card-body">
					<div class="row">

						<!-- Docs Sidebar -->
						<div class="docs-sidebar col-md-3">
							<h6 class="heading">Usage</h6>
							<ul>
								<li>
									<a href="#">Overview</a>
								</li>
								<li>
									<a href="#" data-scrollto="document">Getting Started</a>
								</li>
								<li>
									<a href="#" data-scrollto="setup">Setup and Install</a>
								</li>
							</ul>
							<h6 class="heading">CAB2U</h6>
							<ul>
								<li>
									<a>UI Elements</a>
								</li>
								<li>
									<a>Software</a>
								</li>
								<li>
									<a>Attributes</a>
								</li>
								<li>
									<a>About Us</a>
								</li>
							</ul>
							<h6 class="heading">Admin Portal</h6>
							<ul>
								<li>
									<a href="https://domain.com">
										<i class="icon icon-speedometer text-indigo"></i> Dashboard
									</a>
								</li>
								<li>
									<a href="#">
										<i class="icon icon-chart text-lime"></i> Analytics
									</a>
								</li>
								<li>
									<a href="#" data-scrollto="contact_us">
										<i class="icon icon-support text-purple"></i> Support
									</a>
								</li>
							</ul>
						</div>

						<!-- Docs Content -->
						<div class="docs-content col-md-9">

							<!-- Docs Section -->
							<div class="docs-section doc-action-cards">
								<h4 class="heading">CAB2U Documentation</h4>
								<p>
									CAB2U Documentation should simplify the processes and make it easy to understand how the application works. 
									It should provide instructions on running the application and explain the functionality of the mobile application.
								</p>
								<div class="row mt-2">
									<div class="col-md-6">
										<a href="#" class="ui-card ui-action-card">
											<div class="card-body">
												<h6 class="heading text-indigo">UI Elements</h6>
												<p>Cab2U have awesome UI Designs used in the mobile Application newly elements.</p>
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#" class="ui-card ui-action-card">
											<div class="card-body">
												<h6 class="heading text-indigo">Software</h6>
												<p>The specific software components and technologies used in an application.
												</p>
											</div>
										</a>
									</div>
									<div class="row">
										<div class="col-md-6">
											<a href="#" class="ui-card ui-action-card">
												<div class="card-body">
													<h6 class="heading text-indigo">Attributes</h6>
													<p>Key attributes are create a reliable and convenient ride-hailing experience for all users.
													</p>
												</div>
											</a>
										</div>
									<div class="col-md-6">
										<a href="#" class="ui-card ui-action-card">
											<div class="card-body">
												<h6 class="heading text-indigo">About Us</h6>
												<p> Cab2u offers all functions for its users' convenience. It offers
													options for gender filtering and real-time tracking.
												</p>
											</div>
										</a>
									</div>
									
									</div>
								</div>
							</div><!-- .docs-section -->

							<!-- Docs Section -->
							<div class="section docs-section" id="document" >
								<h4 class="heading">Getting Started</h4>
								<p>
									Our cab2u mobile application is extremely beneficial to taxi services. 
									Simplicity and ease of application in your project can be achieved through 
									careful planning and the use of the right tools and frameworks.
								</p>
								<pre class="language-markup mt-2 mb-2">
                                        <code class="language-markup">
                                            $ npm install 
                                            $ adb devices
                                            $ npx react-native run-android
                                        </code>
                                    </pre>
								<h6 class="heading mt-2">Download</h6>
								<p>
									Our cab2u mobile application is extremely beneficial to taxi services. 
									Several platforms, including Android and iOS, can be used to develop
                                    mobile applications.
								</p>
								<div class="actions">
									<a class="btn ui-gradient-blue shadow-xl" href="">Download Trial</a>
									<a class="btn ui-gradient-green shadow-xl" href="">Buy Now</a>
								</div>
							</div><!-- .docs-section -->

							<!-- Docs Section -->
							<div class="section docs-section" id="setup">
								<h4 class="heading mt-2">Setup and Installation</h4>
								<p>
									Setup the application to Check for and install any available software updates. 
									It's crucial to keep the application up to date to benefit from bug fixes and security enhancements.
									Always refer to the provided installation instructions and documentation.Configure the application's settings and preferences according to your needs. 
									This may include customizing display options, notifications, and privacy settings. 
									for the application for the most accurate guidance.
								</p>

								<!-- UI Tabs -->
								<div class="ui-tabs">
									<!-- Nav Tabs -->
									<ul class="nav nav-tabs nav-vertical mb-0 mt-2" role="tablist">
										<!-- Nav Tab 1 -->
										<li role="presentation" class="nav-item">
											<a class="nav-link active" href="#js" role="tab" data-toggle="tab"
											   data-toggle_screen="1" data-toggle_slider="device-slider-2"
											   aria-expanded="false">
												Javascript
											</a>
										</li>
										<!-- Nav Tab 2 -->
										<li role="presentation" class="nav-item">
											<a class="nav-link" href="#swift" role="tab" data-toggle="tab"
											   data-toggle_screen="2" data-toggle_slider="device-slider-2"
											   aria-expanded="false">
												AdminPanel
											</a>
										</li>
										<!-- Nav Tab 3 -->
										<li role="presentation" class="nav-item">
											<a class="nav-link" href="#java" role="tab" data-toggle="tab"
											   data-toggle_screen="3" data-toggle_slider="device-slider-2"
											   aria-expanded="false">
												Java
											</a>
										</li>
										<!-- Nav Tab 4 -->
										<li role="presentation" class="nav-item">
											<a class="nav-link" href="#obj-c" role="tab" data-toggle="tab"
											   data-toggle_screen="4" data-toggle_slider="device-slider-2"
											   aria-expanded="true">
												FireBase
											</a>
										</li>
									</ul>

									<!-- Tab Panels -->
									<div class="tab-content">
										<!-- Tab 1 -->
										<div role="tabpanel" class="tab-pane fade active show" id="js">
                                                 <pre class="language-javascript line-numbers">
                                                    <code class="language-javascript">
                                                        // Private key
                                                        var privateKey = get('BQokikJOvBiI2HlWgH4olfQ2');

                                                        // Create a payment
                                                        var payment = charge({
                                                          key: privateKey,
                                                          amount: 599,
                                                          currency: 'usd',
                                                          source: 'visa',
                                                          description: 'My first payment'
                                                        });
                                                    </code>
                                                </pre>
										</div>
										<!-- Tab 2 -->
										<div role="tabpanel" class="tab-pane fade" id="swift">
                                                <pre class="language-swift line-numbers">
                                                    <code class="language-swift">
														Software Dependencies For Admin Panel
														Configuration
														● XAMPP php above version
														● Android Studio
														● Node js
														●Laravel with composer
														.env to change the mode in the PRODUCTION 
                                                    </code>
                                                </pre>
										</div>
										<!-- Tab 3 -->
										<div role="tabpanel" class="tab-pane fade" id="java">
                                                <pre class="language-java line-numbers">
                                                    <code class="language-java">
                                                          // Private key
                                                          PaymentsApi.PRIVATE_KEY = "BQokikJOvBiI2HlWgH4olfQ2";

                                                          // Create a payment
                                                          Payment payment = Payment.create(new PaymentsMap()
                                                            .set("currency", "USD")
                                                            .set("token", "f21da65e-f0ab-45cb-b8e6-40b493c3671f")
                                                            .set("amount", 599)
                                                            .set("description", "My first payment")
                                                          );
                                                    </code>
                                                </pre>
										</div>
										<!-- Tab 4 -->
										<div role="tabpanel" class="tab-pane fade" id="obj-c">
											<pre class="language-objectivec line-numbers">
												<code class="language-objectivec">
													// Firebase Details
													- Create a web app in firebase.
													Copy the firebase SDK details, On successful registration,
													just copy the firebase configuration details and paste in firebaseConfiguation.

													Add your Firebase in your android application and google services.json file downloaded.Copy
													and paste the google services.json file in the android application. 
												</code>
											</pre>
									</div>
									</div><!-- .tab-content -->
								</div><!-- .ui-tabs -->

							</div><!-- .docs-section -->

							<!-- Docs Section -->
							<div class="docs-section">
								<h4 class="heading">Addons</h4>
								<p class="pb-4">
									Add-ons can greatly enhance the functionality and usability of software, 
									so exploring available options can be beneficial.
								</p>
								<div class="row ui-icon-blocks ui-blocks-h icons-lg">
									<div class="col-6 ui-icon-block">
										<div class="icon icon-lg icon-paper-plane"></div>
										<h6>Wordpress Addon</h6>
										<p>
											Plugins add specific functionality of the application, while themes control its appearance. 
										</p>
									</div>
									<div class="col-6 ui-icon-block">
										<div class="icon icon-lg icon-rocket"></div>
										<h6>Magenta Addon</h6>
										<p>
											 To provide more details about the application is associated with more information
											 gathered in the application.
										</p>
									</div>
								</div>
							</div><!-- .docs-section -->

							<!-- Docs Section -->
							<div class="docs-section">
								<h4 class="heading">Code Configuration</h4>
								<p>
									An application that accelerates the coding process, saving time and streamlining coding methods.
								</p>
								<pre class="language-node line-numbers mt-2">
                                        <code>
                                            //Install Node.js
                                             - Download Node JS from browser, and click on .exe file to install.
                                             - Download Android Studio and install in your System.

                                            //Mobile Configuration
                                            Open command prompt  and application npm install, then 
                                            Run the Command. To run the mobile Application.

                                            //FireBase Configuration
                                            Add Your Project in the Firebase and in the project settings,
                                            you can find important information such as API keys, Web API Key, Firebase configuration options, and other credentials.
                                        </code>
                                    </pre>
							</div><!-- .docs-section -->

							<!-- Docs Section -->
							<div class="docs-section">
								<h4 class="heading">Important Notice</h4>
								<p>
									Our Mobile Application is currently undergoing scheduled maintenance. During this time, some features may be temporarily unavailable. 
									We apologize for any inconvenience and appreciate your understanding. Please check back shortly.
								</p>
								<div class="docs-notice mt-2">
									<h6>Remember!</h6>
									<p>
										"We are aware of a service disruption affecting our mobile application. 
										Our team is actively working to resolve the issue. We apologize for any inconvenience and thank you for your patience.
									    We will provide updates as soon as the problem is resolved."
									</p>
								</div>
							</div><!-- .docs-section -->

						</div><!-- .docs-content -->
					</div><!-- .row -->
				</div><!-- .card-body -->
			</div><!-- .ui-card -->

		</div><!-- .container -->
	</div><!-- #ui-api-docs -->

    <!--  Section -->
	<div class="section contact-section" id="contact_us">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="ui-card form-card shadow-xl bg-indigo">
						<div class="card-header pb-0">
							<h3 class="heading">Contact Info</h3>
						</div>
						<div class="card-body">
							<ul class="ui-icon-blocks ui-blocks-v">
								<li class="ui-icon-block">
									<span class="icon icon-clock"></span>
									<p>Mon - Fri 08:00 - 19:00</p>
								</li>
								<li class="ui-icon-block">
									<span class="icon icon-phone"></span>
									<p>+254 9363671699 / +254 8072979472</p>
								</li>
								<li class="ui-icon-block">
									<span class="icon icon-envelope"></span>
									<p>craigcarlos95@gmail.com</p>
								</li>
								<li class="ui-icon-block">
									<span class="icon icon-pin"></span>
									<p>193/1& 194/3, Mullai Street, Madkulam Main Road,Nehru nagar, <br>Madurai,<br>Tamilnadu </p>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!-- .container -->
	</div><!-- .section -->


	<!-- Footer -->
	<footer class="ui-footer bg-gray">
		<!-- Footer Copyright -->
		<div class="footer-copyright bg-dark-gray">
			<div class="container">
				<div class="row">
					<!-- Copyright -->
					<div class="col-sm-6 center-on-sm">
						<p>
							&copy; 2020 <a href="http://codeytech.com" target="_blank" title="Menpani-Cab2u Home">Menpani Technology</a>
						</p>
					</div>
					<!-- Social Icons -->
					<div class="col-sm-6 text-right">
						<ul class="footer-nav">
							<li>
								<a href="#">
									Privacy Policy
								</a>
							</li>
							<li>
								<a href="#">
									Terms &amp; Conditions
								</a>
							</li>
							<li>
								<a href="#">
									FAQ
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div><!-- .container -->
		</div><!-- .footer-copyright -->

	</footer><!-- .ui-footer -->

</div><!-- .main -->

<!-- Scripts -->
<script src="assets/js/libs/jquery/jquery-3.2.1.min.js"></script>
<script src="assets/js/libs/slider-pro/jquery.sliderPro.min.js"></script>
<script src="assets/js/libs/owl.carousel/owl.carousel.min.js"></script>
<!--
# Google Maps
# Add Your Google Maps API Key Below !!
-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5B2iXEELo6aIReGYLJdVKBlzHnrM0YLU"></script>
<script src="assets/js/applify/ui-map.js"></script>
<script src="assets/js/libs/form-validator/form-validator.min.js"></script>
<script src="assets/js/libs/bootstrap.js"></script>
<script src="assets/js/libs/prism/prism.js"></script>
<script src="assets/js/applify/build/applify.js"></script>
</body>
</html>
