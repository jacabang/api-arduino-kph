@extends('layouts.main')
@section('title', 'HOMERGY | Edit Profile')
@section('user', 'Admin')
@section('user_group', 'Administrator')
@section('menu')

@endsection
@section('style')
<style>
	img.output:hover {
	cursor: pointer;
	}

	img.output1:hover {
	cursor: pointer;
	}

	label span input {
		z-index: 999;
		line-height: 0;
		font-size: 50px;
		position: absolute;
		top: -6px;
		left: 0px;
		opacity: 0;
		filter: alpha(opacity = 0);
		-ms-filter: "alpha(opacity=0)";
		cursor: pointer;
		_cursor: hand;
		margin: 0;
		padding:0;
	}
</style>

<style>
.myInputH1{
    border: none !important;
    font-size: 1.5em !important;
    /*line-height: 10px !important;*/
    resize: none !important;
    margin-bottom: 5px !important;
}

.myInputP{
    border: none !important;
    /*line-height: 10px !important;*/
    resize: none !important;
}

.myInputText{
    border: none !important;
    /*line-height: 10px !important;*/
    resize: none !important;
}

.radioMy{
    float: right !important;
    margin-top: 10px !important;
}
</style>
@endsection

@section('myMenu')
	{!! $menu !!}
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-end">
        <nav aria-label="breadcrumb">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{URL('newsfeed')}}"><i class="fas fa-home"></i></a></li>
		    <li class="breadcrumb-item active" aria-current="page">Edit Profile </li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
      		<form class="form-horizontal" enctype="multipart/form-data" action="{{URL('/')}}/editProfileChange" method="POST">
	        	<div class="form-title">
					<button style="float: right; margin-left: .5em;" class="btn btn-success btn-flat btn-pri"><i class="fa fa-save"></i> {{$label1}}</button>

					<!-- <a style="border: 1px solid #b8c7ce; float: right;" href="{{URL('/')}}" style="float: right;" class="btn btn-info btn-flat btn-pri">
							<i class="fa fa-arrow-left"></i> Back
						</a> -->
				</div>
				<br/>
				<br/>
				@csrf
				<hr>
				<div class="form-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-10">
									@if ($errors->has('uname'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Username is already used</strong>
                                    </div>
                                    @endif
									@if ($errors->has('email'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Email is already used</strong>
                                    </div>
                                    @endif
									@if (Session::has('success'))
                                    <div class="alert alert-success" role="alert">
                                        <strong>{!! Session::get('success')[0] !!}</strong>
                                    </div>
                                    @endif
                                    @if (Session::has('error'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{!! Session::get('error')[0] !!}</strong>
                                    </div>
                                    @endif
                                    @if ($errors->has('password'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                    @endif
                                    <div class="mb-3">
                                    	<div class="well" style="width: 150px;">
	                                        <center>
	                                            <label class="filebutton" style="width: 100%;">
	                                            <img style="width: 100%;" id="output" src="{{URL('uploads')}}/{{$image_file}}"/>
	                                            <span><input style="width: 100%;" type="file" id="myfile" name="banner" onchange="loadFile(event)" accept=".jpg, .png, .PNG, .JPG" 

	                                            ></span>
	                                            </label>
	                                        </center>
	                                        <script>
	                                          var loadFile = function(event) {
	                                            var output = document.getElementById('output');
	                                            output.src = URL.createObjectURL(event.target.files[0]);
	                                          };
	                                        </script>
	                                    </div>
                                    </div>
									<div class="mb-3">
									  <label class="form-label" for="userpleFormControlInput1">Fullname</label>
									  <input class="form-control" id="userpleFormControlInput1" type="text" placeholder="John Doe" value="{{Request::old('fname') == "" ? $fname : Request::old('fname')}}" name="fname" required="required"/>
									</div>
									<div class="mb-3">
									  <label class="form-label" for="userpleFormControlInput1">Email</label>
									  <input class="form-control" id="userpleFormControlInput1" type="email" placeholder="johndoe@yopmail.com" value="{{Request::old('email') == "" ? $email : Request::old('email')}}" name="email"/>
									</div>
									<div class="mb-3">
									  <label class="form-label" for="userpleFormControlInput1">Username</label>
									  <input class="form-control" id="userpleFormControlInput1" type="text" placeholder="j.doe" value="{{Request::old('uname') == "" ? $uname : Request::old('uname')}}" name="uname"/>
									</div>
								</div>
								<hr>
								<div class="col-md-10">

									<div class="mb-3">
									  <label class="form-label" for="userpleFormControlInput1">Old Password</label>
									  <input class="form-control" id="userpleFormControlInput1" type="password" placeholder="Old Password" name="old_password"/>
									</div>
									<div class="mb-3">
									  <label class="form-label" for="userpleFormControlInput1">New Desired Password</label>
									  <input class="form-control" id="userpleFormControlInput1" type="password" placeholder="New Desired Password" name="password"/>
									</div>
									<div class="mb-3">
									  <label class="form-label" for="userpleFormControlInput1">Comfirm Password</label>
									  <input class="form-control" id="userpleFormControlInput1" type="password" placeholder="Comfirm Password" name="password_confirmation"/>
									</div>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
						</div>
						<!-- <div class="col-sm-3">
							<div class="well">

							</div>
						</div> -->
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
      	</div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
	$(function(){
		$('#localizationCollapseMenu').addClass('show'); //collapse sub menu
		$('#localizationSideMenu').addClass('active'); //highlight parent menu
		$("#localizationSideMenu").attr("aria-expanded","true"); //indicator that it is collapse

		$('#usersCollapseMenu').addClass('show');
		$('#usersSideMenu').addClass('active');
		$("#usersSideMenu").attr("aria-expanded","true");

		$('#userSideMenu').addClass('active');
	});
</script>
<script>
$(function(){
	$('.mySelect').select2();

	$('#horizontalTab').responsiveTabs({
	    rotate: false,
        startCollapsed: 'accordion',
        collapsible: 'accordion',
        setHash: false,
        activate: function(e, tab) {
            $('.info').html('Tab <strong>' + tab.id + '</strong> activated!');
        },
        activateState: function(e, state) {
            //console.log(state);
            $('.info').html('Switched from <strong>' + state.oldState + '</strong> state to <strong>' + state.newState + '</strong> state!');
        }
	});

});
</script>
@endsection