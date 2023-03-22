@extends('layouts.main')
@section('title', 'HOMERGY | Dashboard')
@section('user', 'Admin')
@section('user_group', 'Administrator')
@section('menu')

@endsection

@section('myMenu')
	{!! $menu !!}
@endsection

@section('content')
<style>
.col-12 {
	padding: 0;
}
</style>

<div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-end">
        <nav aria-label="breadcrumb">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{URL('dashboard')}}"><i class="fas fa-home"></i></a></li>
		    <li class="breadcrumb-item active" aria-current="page">Webhooks</li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
        	<div class="form-title">
        		@if(isset($editable[13]))
						<!-- <a href="{{URL('/')}}/ads/create" style="float: right;" class="btn btn-primary btn-flat btn-pri"> -->
							<!-- <i class="fa fa-plus"></i> Add -->
						<!-- </a> -->
						@endif
				</div>
			<br>
			<br>
			<div class="form-body" style="font-size: 12px;">
				<?= view('swagger/index'); ?>
			</div>
      	</div>
    </div>
</div>

@endsection

@section('page-script')webHooksSideMenu
<script type="text/javascript">
	$(function(){

		$('#webHooksSideMenu').addClass('active');
		
	});
</script>
@endsection