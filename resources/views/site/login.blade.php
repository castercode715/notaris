
<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>Notaris Application | Login</title>
<!-- Favicon-->
<link rel="icon" href="https://alexandrettaconsulting.com/wp-content/uploads/2019/07/kisspng-cargill-organic-food-breakfast-french-toast-lawyer-5ad1218fb387c7.8447173515236550557354.png" type="image/x-icon">
<link rel="stylesheet" href="{{ asset('theme/bootstrap.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">


<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('theme/main.css') }}">    
<link rel="stylesheet" href="{{ asset('theme/color_skins.css') }}">
</head>
<body class="theme-black">
<div class="authentication">
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        <h4 class="logo"><img src="https://alexandrettaconsulting.com/wp-content/uploads/2019/07/kisspng-cargill-organic-food-breakfast-french-toast-lawyer-5ad1218fb387c7.8447173515236550557354.png" alt=""> Notaris Application</h4>
                        <h3>The ultimate <strong>Bootstrap 4</strong> Admin Dashboard</h3>
                        <p>Alpino is fully based on HTML5 + CSS3 Standards. Is fully responsive and clean on every device and every browser</p>                        
                        <div class="footer">
                            <ul  class="social_link list-unstyled">
                                <li><a href="" title="ThemeMakker"><i class="zmdi zmdi-globe"></i></a></li>
                                <li><a href="" title="Themeforest"><i class="zmdi zmdi-shield-check"></i></a></li>
                                <li><a href="" title="LinkedIn"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="" title="Facebook"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="" title="Twitter"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="" title="Google plus"><i class="zmdi zmdi-google-plus"></i></a></li>
                                <li><a href="" title="Behance"><i class="zmdi zmdi-behance"></i></a></li>
                            </ul>
                            <hr>
                            <ul>
                                
                            </ul>
                        </div>
                    </div>                    
                </div>
                <div class="col-lg-5 col-md-12 offset-lg-1">
                    <div class="card-plain">
                        <div class="header">
                            <h5>Log in</h5>
                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ $message }} 
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('login') }}" method="post">
                            @csrf

                            <div class="input-group">
                                <input type="text" id="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email / Username" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    
                                @endif

                                <div class="input-group-append" id="errorrrrr">
                                    <span class="input-group-text"><i class="zmdi zmdi-account-circle"></i></span>
                                </div>
                            </div>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                                </div>
                            </div>
                            <div class="footer">
                                <button id="submit" type="submit" class="btn btn-primary btn-round btn-block">Login</button> 
                            
                            </div>
                        </form>
                        
                        <a href="#" class="link">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Jquery Core Js -->
@if ($errors->has('email'))
    <script type="text/javascript">
        var x = document.getElementById("errorrrrr");
        x.style.display = "none";
    </script>
@endif


<script src="{{ asset('theme/libscripts.bundle.js')}}"></script>
<script src="{{ asset('theme/vendorscripts.bundle.js')}}"></script> <!-- Lib Scripts Plugin Js -->
</body>
</html>