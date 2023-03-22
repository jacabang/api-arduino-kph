@extends('layouts.main')
@section('title', 'HOMERGY | Device')
@section('user', 'Admin')
@section('user_group', 'Administrator')
@section('menu')

@endsection

@section('myMenu')
	{!! $menu !!}
@endsection

@section('style')
<style>
td.details-control {
    background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
}
</style>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-end">
        <nav aria-label="breadcrumb">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{URL('newsfeed')}}"><i class="fas fa-home"></i></a></li>
		    <li class="breadcrumb-item active" aria-current="page">Device</li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
        	<div class="form-title">
        		@if(isset($editable[7]))
						<a href="{{URL('/')}}/device/create" style="float: right;" class="btn btn-primary btn-flat btn-pri">
							<i class="fa fa-plus"></i> Add
						</a>
						@endif
				</div>
			<br>
			<br>
			<div class="form-body" style="font-size: 12px;">
				<table style="width:100%" class="table table-bordered table-striped mb-none" id="table_id">
					<thead>
				        <tr>
				            <th></th> 
				            <th>Device Name</th>
				            <th>Device Code</th>
				            <th># of Socket</th>
				            <th>Total Current KHW</th>
				            <th>Create Date</th>
				            <th>Created By</th>
				            @if(isset($editable[7]))
				            <th>Action</th>
				            @endif
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
		$('#deviceSideMenu').addClass('active');
	});

	function format ( d ) {
	    // `d` is the original data object for the row
	    return d.product
	}

    var table = $('#table_id').DataTable( {
			"order": ['1', 'asc'],
			// "bPaginate": false,
			// "processing": true,	
			dom: 'Bfrtip',
	    scrollX: true,
	    buttons: [
	      {
	          extend: 'pageLength', 
	          className: 'datatable_button',
	          title: 'Activity List as of {{date('M d, Y')}}'
	      },
	      {
	          extend: 'csv', 
	          className: 'datatable_button',
	          text: 'Export',
	          title: 'Activity List as of {{date('M d, Y')}}',
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
	    // responsive: true,
	    "ajax": {
	        "url": "{{URL('/')}}/fetchDevice",
	        "type": "POST",
	        "data" : {
	            "_token": "{{ csrf_token() }}",
	        }
	    },
	    "columns": [
            {
            	"class":          "details-control",
                "orderable":      false,
                "data":           null,
                "defaultContent": ""
            },{
            	"data": "device_name"
            },{
            	"data": "device_code"
            },{
            	"data": "count"
            },{
            	"data": "current_kwh"
            },{
            	"data": "created_at"
            },{
            	"data": "created_by"
            },{
            	"data": "action"
            }
        ]
	});



	$('#table_id tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

	$('#table_id tbody').on( 'click', 'button.icon-delete', function () {
				var getid = $(this).data('id');

				var row;

				if($(this).closest('table').hasClass("collapsed")) {
				    var child = $(this).parents("tr.child");
				    row = $(child).prevAll(".parent");
				  } else {
				    row = $(this).parents('tr');
			  	}

			  	myrow = table
			        .row(row);

			swal("Are you sure?", "You will not be able to recover this Device!", {
				icon: 'warning',
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

				     $.ajax({
							url: "{{URL('/')}}/device/"+getid,
							type: "POST",
							data: {
								_token: "{{ csrf_token() }}",
								"_method": "DELETE",
							},
							success: function(data){
								 // console.log(data);

							}        
					   });
			      break;
			 
			    default:
			      swal.close();
			  }
			});
		    
		} );

</script>
@endsection