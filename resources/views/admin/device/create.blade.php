@extends('layouts.main')
@section('title', 'HOMERGY | Device')
@section('user', 'Admin')
@section('device', 'Administrator')
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
		    <li class="breadcrumb-item" aria-current="page">Device</li>
		    <li class="breadcrumb-item active" aria-current="page">{{$label}} </li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
      		<form class="form-horizontal" enctype="multipart/form-data" action="{{URL('/')}}/device{{$device_id != '' ? '/'.$device_id : ''}}" method="POST">
	        	<div class="form-title">
					<button style="float: right; margin-left: .5em;" class="btn btn-success btn-flat btn-pri"><i class="fa fa-save"></i> {{$label1}}</button>

					<a style="border: 1px solid #b8c7ce; float: right;" href="{{URL('/')}}/device" style="float: right;" class="btn btn-info btn-flat btn-pri">
						<i class="fa fa-arrow-left"></i> Back
					</a>
				</div>
				<br>
				<br>
				@if($device_id != "")
				<input type="hidden" name="_method" value="PUT">
				@else
				@endif
				@csrf
				<hr>
				<div class="form-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-10">
									@if ($errors->has('device_name'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ $errors->first('device_name') }}</strong>
                                    </div>
                                    @endif
									<input type="hidden" name="device_id" value="{{$device_id}}"/>
									@if($device_code == "")
									<input id="DeviceCode" type="hidden" name="device_code" value="{{$device_code}}"/>
									@endif
									<div class="mb-3">
										<label for="form-label">Device Name</label>
									  	<input class="form-control" id="floatingInput" type="text" placeholder="Device" value="{{Request::old('device_name') == "" ? $device_name : Request::old('device_name')}}" name="device_name"/>
									</div>
									<div class="mb-3">
										<table style="width:100%" class="table table-bordered table-striped mb-none" id="table_id">
	                                        <thead>
	                                            <tr>
	                                                <th>Socket Name</th>
	                                                <th>Socket Code</th>
	                                                @if(isset($editable[6]))
	                                                <th style="width: 50px; max-width: 50px; min-width: 50px;"></th>
	                                                @endif
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                        	@if($query == "")
	                                        	<tr>
	                                                <td><input class="form-control" type="text" placeholder="Socket Name"  title="Socket Name" value="" name="socket_name[]" required="required"></td>
	                                                <td><input type="hidden" name="id[]" value="0"><input class="form-control" id="initialUIID" type="text" placeholder="Socket Code"  title="Socket Code" value="" name="system[]" required="required" readonly></td>
	                                                <td style="width: 50px; max-width: 50px; min-width: 50px;"></td>
	                                            </tr>
	                                            @else
	                                            <?php $x = 0; ?>
	                                            @foreach($query->socket as $result)
	                                            <tr>
	                                            	<td><input class="form-control" type="text" placeholder="Socket Name"  title="Socket Name" value="{{$result->socket_name}}" name="socket_name[]" required="required"></td>
	                                                <td><input type="hidden" name="id[]" value="{{$result->id}}"><input class="form-control" type="text" placeholder="Socket Code"  title="Socket Code" value="{{$result->socket_code}}" name="system[]" required="required" readonly></td>
	                                                <td style="width: 50px; max-width: 50px; min-width: 50px;">
	                                                @if($x != 0)
	                                                @if(count($result->readings) == 0)
	                                                <a style="border-radius: 5px;" title="Remove" style="" class="btn btn-danger"><i style="color: white;" class="fas fa-trash fa-1x icon-delete1"></i></a>

	                                                @endif
	                                                @endif
	                                                	

	                                                </td>
	                                            </tr>
	                                            <?php $x++; ?>
	                                            @endforeach
	                                            @endif
	                                        </tbody>
                                        	@if(isset($editable[6]))
	                                        <tfooter>
                                                <tr>
                                                    <td colspan="3">
                                                    <a id="addRow_member" style="margin-left: 1em; float: right; cursor: pointer; color: white;" class="addButton btn btn-small btn-info"><i style="color: white;" class="fas fa-plus"></i></a>
                                                      
                                                  </td>
                                              </tr>
                                            </tfooter>
                                            @endif
	                                    </table>
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
@endsection

@section('page-script')
<script type="text/javascript">
	$(function(){

		$('#deviceSideMenu').addClass('active');

		@if($query == "")

		test = uuidv4();

		$("#initialUIID").val(test);

		@endif

		@if($device_code == "")
		test = uuidv4();

		$("#DeviceCode").val(test);
		@endif
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

var member_table = $('#table_id').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "xScroll": true,
        "searching": false,
    	"responsive": true
});

$('#addRow_member').on( 'click', function () {

	test = uuidv4();

     member_table.row.add( [
      '<input class="form-control" id="userpleFormControlInput1" type="text" placeholder="Socket Name"  title="Socket Code" value="" name="socket_name[]" required="required">',
      '<input type="hidden" name="id[]" value="0"><input class="form-control" id="userpleFormControlInput1" type="text" placeholder="Socket Code"  title="Socket Code" value="'+test+'" name="system[]" required="required" readonly>',
      '<a style="border-radius: 5px;" title="Remove" style="" class="btn btn-danger"><i style="color: white;" class="fas fa-trash fa-1x icon-delete1"></i></a>',
      ] ).draw( false ); 

     $(".mySelect").select2();

} );



$('#table_id tbody').on( 'click', 'svg.icon-delete1', function () {
      var getid = $(this).data('id');

      var myrow = member_table
          .row( $(this).parents('tr') );

      swal("Are you sure?", "You will not be able to recover this Socket!", {
        buttons: {
          cancel: "Cancel",
          catch: {
            text: "Yes",
            value: "delete",
            className: "btn-danger",
          }
        },
      })
      .then((value) => {
        switch (value) {
       
          case "delete":
            myrow.remove().draw();
            break;
       
          default:
            swal.close();
        }
      });
      
  } );

function uuidv4() {
  return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
  );
}
</script>
@endsection