@extends('layouts.main')
@section('title', 'HOMERGY | User Group')
@section('user', 'Admin')
@section('user_group', 'Administrator')
@section('menu')

@endsection
@section('style')
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

.dropdown-item:hover, .dropdown-item:focus {
    color: var(--falcon-dropdown-link-hover-color);
    background-color: #c9e0ef;
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
		    <li class="breadcrumb-item" aria-current="page">Localization</li>
		    <li class="breadcrumb-item"><a href="{{URL('user_group')}}">User Group</a></li>
		    <li class="breadcrumb-item active" aria-current="page">{{$label}} </li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
      		<form class="form-horizontal" enctype="multipart/form-data" action="{{URL('/')}}/user_group{{$user_group_id != '' ? '/'.$user_group_id : ''}}" method="POST">
	        	<div class="form-title">
					<button style="float: right; margin-left: .5em;" class="btn btn-success btn-flat btn-pri"><i class="fa fa-save"></i> {{$label1}}</button>

					<a style="border: 1px solid #b8c7ce; float: right;" href="{{URL('/')}}/user_group" style="float: right;" class="btn btn-info btn-flat btn-pri">
							<i class="fa fa-arrow-left"></i> Back
						</a>
				</div>
				<br>
				<br>
				@if($user_group_id != "")
				<input type="hidden" name="_method" value="PUT">
				@else
				@endif
				@csrf
				<hr>
				<?php
					$access_list = collect($access_list);
				?>
				<div class="form-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-10">
									@if ($errors->has('user_group'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ $errors->first('user_group') }}</strong>
                                    </div>
                                    @endif
									<input type="hidden" name="user_group_id" value="{{$user_group_id}}"/>
									<div class="mb-3">
										<label for="form-label">User Group</label>
									  	<input class="form-control" id="floatingInput" type="text" placeholder="User Group" value="{{Request::old('user_group') == "" ? $user_group : Request::old('user_group')}}" name="user_group"/>
									</div>
									<div class="mb-3">
										<label for="form-label">Access</label>
										( <a style="cursor: pointer;" id="select-all">Select All</a> | <a style="cursor: pointer;" id="select-no">Unselect All</a> )
										<div class="well">
	                                    @foreach($access_list as $data)
	                                      <label for="{{ $data->id }}a"><input class="view" id="{{ $data->id }}a" value="{{ $data->id }}" type="checkbox" name="list[]" 
	                                        @if(isset($editable1[$data->id]))
	                                          checked
	                                        @endif
	                                      > {{ $data->label }}</label><br>
	                                    @endforeach
	                                    </div>
									</div>
									<div class="mb-3">
										<label for="form-label">Editable</label>
										( <a style="cursor: pointer;" id="select-all1">Select All</a> | <a style="cursor: pointer;" id="select-no1">Unselect All</a> )
										<div class="well">
	                                    @foreach($access_list->where('with_editable', 1) as $data)
	                                      <label for="{{ $data->id }}b"><input class="edit" id="{{ $data->id }}b" value="{{ $data->id }}" type="checkbox" name="list1[]" 
	                                        @if(isset($editable2[$data->id]))
	                                          checked
	                                        @endif
	                                      > {{ $data->label }}</label><br>
	                                    @endforeach
	                                    </div>
									</div>
                                </div>
                            </div>
                            <hr>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
      	</div>
    </div>
</div>
<div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-6" role="document">
    <div class="modal-content border-0">
      <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
        <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
          <!-- &nbsp; -->
        </div>
        <div class="p-4">
          <div class="row">
            <div id="modalBody" class="col-lg-12">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
	 $('#select-all').click(function(event) {   
    // Iterate each checkbox
        $(':checkbox.view').each(function() {
            this.checked = true;                        
        });
    });

    $('#select-no').click(function(event) {   
            // Iterate each checkbox
            $(':checkbox.view').each(function() {
                this.checked = false;                       
        });
    });

    $('#select-all1').click(function(event) {   
            // Iterate each checkbox
            $(':checkbox.edit').each(function() {
                this.checked = true;                        
            });
    });

    $('#select-no1').click(function(event) {   
            // Iterate each checkbox
            $(':checkbox.edit').each(function() {
                this.checked = false;                       
        });
    });
</script>
<script type="text/javascript">
	$(function(){
		$('#localizationCollapseMenu').addClass('show'); //collapse sub menu
		$('#localizationSideMenu').addClass('active'); //highlight parent menu
		$("#localizationSideMenu").attr("aria-expanded","true"); //indicator that it is collapse

		$('#usersCollapseMenu').addClass('show');
		$('#usersSideMenu').addClass('active');
		$("#usersSideMenu").attr("aria-expanded","true");

		$('#userGroupSideMenu').addClass('active');
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