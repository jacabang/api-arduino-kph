@extends('layouts.main')
@section('title', 'HOMERGY | KWPH')
@section('user', 'Admin')
@section('user_group', 'Administrator')
@section('menu')

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
		    <li class="breadcrumb-item active" aria-current="page">KWPH</li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
    	@if(isset($editable[9]))
			<form class="form-horizontal" enctype="multipart/form-data" action="{{URL('/')}}/kwph{{$kwph_id != '' ? '/'.$kwph_id : ''}}" method="POST">
				@if($kwph_id != "")
				<input type="hidden" name="_method" value="PUT">
				@else
				@endif
				@csrf
				<div class="form-body">
					<div class="row">
						<div class="col-sm-12">
							@if(session('notification'))
								  @foreach(session('notification') as $result)
								  <div class="alert alert-{{ $result['type'] }}" role="alert">
			                <strong>{{ $result['message'] }}</strong>
			            </div>
								  @endforeach
							@endif
							<div class="row">
								<div class="col-md-9">
									<div class="mb-3">
										<label for="form-label">Amount per Kwh</label>
									  	<input class="form-control" id="floatingInput" type="number" step="any" placeholder="Amount per Kwh" value="{{Request::old('kwph') == "" ? $kwph->kwph : Request::old('kwph')}}" name="kwph"/>
									</div>
                </div>
								<div class="col-md-3">
									<div class="mb-3">
										<label for="form-label">&nbsp;</label>
										<br>
										<button style="margin-left: .5em;" class="btn btn-success btn-flat btn-pri"><i class="fa fa-save"></i> {{$label1}}</button>
									</div>
                </div>
              </div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
			<hr>
			@endif
			<div class="form-body" style="font-size: 12px;">
				<table style="width:100%" class="table table-bordered table-striped mb-none" id="table_id">
					<thead>
				        <tr>
				            <th>Amount per Kwh</th>
				            <th>Status</th>
				            <th>Created By</th>
				            <th>Created At</th>
				        </tr>
				    </thead>
					<tbody>
					</tbody>
				</table>
			</div>
      	</div>
    </div>
</div>

@endsection

@section('page-script')
<script>
	$(function(){
		
		$('#localizationCollapseMenu').addClass('show'); //collapse sub menu
		$('#localizationSideMenu').addClass('active'); //highlight parent menu
		$("#localizationSideMenu").attr("aria-expanded","true"); //indicator that it is collapse

		$('#kwphSideMenu').addClass('active');
	});

    var table = $('#table_id').DataTable( {
			"order": ['3', 'desc'],
			// "bPaginate": false,
			// "processing": true,	
			dom: 'Bfrtip',
	    // scrollX: true,
	    buttons: [
	      {
	          extend: 'pageLength', 
	          className: 'datatable_button',
	          title: 'KWPH List as of {{date('M d, Y')}}'
	      },
	      {
	          extend: 'csv', 
	          className: 'datatable_button',
	          text: 'Export',
	          title: 'KWPH List as of {{date('M d, Y')}}',
	          exportOptions: {
              // columns: ":visible",
              columns: ':not(:last-child)'
            },
	      },
	      // {
	      //     extend: 'pdfHtml5', className: 'datatable_button',
	      //     title: 'Activity List as of {{date('M d, Y')}}'
	      // },
	      // {
	      //     extend: 'print', className: 'datatable_button',
	      //     title: 'Activity List as of {{date('M d, Y')}}'
	      // },
	      {
	        extend: 'colvis',
	        // columnText: function ( dt, idx, title ) {
	        //     return (idx+1)+': '+title;
	        // },
	        // columns: 'th:nth-child(n+2)'
		    }
	    ],
	    responsive: true,
	    "ajax": {
	        "url": "{{URL('/')}}/fetchKwph",
	        "type": "POST",
	        "data" : {
	            "_token": "{{ csrf_token() }}",
	        }
	    }
	});

</script>
@endsection