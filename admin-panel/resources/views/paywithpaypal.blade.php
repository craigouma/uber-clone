<html>
<head>
	<meta charset="utf-8">
	<title>How to integrate paypal payment in Laravel - Techsolutionstuff</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
    <div class="row">    	
        <div class="col-md-8 col-md-offset-2">        	
            <div class="panel panel-default" style="margin-top: 60px;">

                @if ($message = Session::get('success'))
                <div class="custom-alerts alert alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('success');?>
                @endif

                @if ($message = Session::get('error'))
                <div class="custom-alerts alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('error');?>
                @endif
                <div class="panel-heading"><b>Paywith Paypal</b></div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL::route('paypal') !!}" >
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="amount" type="hidden" class="form-control" name="amount" value="{{ $amount }}" autofocus>

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <center>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    PayNow
                                </button>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>