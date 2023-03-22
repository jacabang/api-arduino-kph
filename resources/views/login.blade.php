<!DOCTYPE HTML>
<html>
<head>
<title>HOMERGY | Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="{{URL('/')}}/upload/logo1.png"> 
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{URL('/')}}/upload/logo1.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{URL('/')}}/upload/logo1.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{URL('/')}}/upload/logo1.png">
<meta name="keywords" content="Modern Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="assets/css/style.css" rel='stylesheet' type='text/css' />
<link href="assets/css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="assets/js/jquery.min.js"></script>
<!----webfonts--->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<!---//webfonts--->  
<!-- Bootstrap Core JavaScript -->
<script src="assets/js/bootstrap.min.js"></script>
<style>
h1.form-heading {
    margin: 0;
    text-align: center;
    color: #0357a8;
    /*font-size: 18px;*/
    text-transform: uppercase;
    display: inline-block;
    width: 100%;
    font-weight: bold;

}

h1.form-heading span{
   color: black;

}
</style>
</head>
<body id="login">
  <div class="login-logo">
    <center><img style="width: 8em;" src="upload/logo1.png" /></center>
  </div>
  <h1 class="form-heading">HOMERGY</h1>
  <div class="app-cam">
	  <form action="authenticate" method="POST">
      @csrf
      @if (Session::has('notification'))
      <div class="alert alert-danger">{{ Session::get('notification') }}</div>
      @endif
		<input type="text" name="username" value="{{Session::get('username')}}" class="text" placeholder="Username" required="required">
		<input type="password" name="password" placeholder="Password" required="required">
		<div class="submit"><input type="submit" value="Login"></div>
	</form>
  </div>
   <div class="copy_layout login">
      <p>Copyright &copy; 2023 Homergy. All Rights Reserved</p>
   </div>
</body>
</html>
