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
<style>
    /* placing the footer on top */
    tfoot {
        display: table-header-group;
    }}
</style>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-end">
        <nav aria-label="breadcrumb">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{URL('newsfeed')}}"><i class="fas fa-home"></i></a></li>
		    <li class="breadcrumb-item active" aria-current="page">Records</li>
			</ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
			<div class="form-body" style="font-size: 12px;">
				<table style="width:100%" class="table table-bordered table-striped mb-none" id="table_id">
					<thead>
				        <tr>
				            <th>Device Name</th>
				            <th>Socket Name</th>
				            <th>Date</th>
				            <th>Kwh Consumption</th>
				            <th>Total Khw Recorded</th>
				            @if(isset($editable[8]))
				            <th>Action</th>
				            @endif
				        </tr>
				    </thead>
					<tfoot>
				        <tr>
				            <th></th>
				            <th></th>
				            <th></th>
				            <th></th>
				            <th></th>
				            @if(isset($editable[8]))
				            <th></th>
				            @endif
				        </tr>
				    </tfoot>
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
	$('#deviceCollapseMenu').addClass('show'); //collapse sub menu
	$('#deviceSideMenu').addClass('active'); //highlight parent menu
	$("#deviceSideMenu").attr("aria-expanded","true"); //indicator 

	$('#deviceListSideMenu').addClass('active');

	fetchRecords();
});

function fetchRecords(){

		var table = $('#table_id').DataTable();

		table.destroy();

	    var table = $('#table_id').DataTable( {
				"order": [['0', 'asc'],['1', 'asc'],['2', 'desc']],
				// "bPaginate": false,
				// "processing": true,	
				dom: 'Bfrtip',
		    // scrollX: true,
		    buttons: [
		      {
		          extend: 'pageLength', 
		          className: 'datatable_button',
		          title: 'Record List as of {{date('M d, Y')}}'
		      },
		      {
		          extend: 'csv', 
		          className: 'datatable_button',
		          text: 'Export',
		          title: 'Record List as of {{date('M d, Y')}}',
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
		        "url": "{{URL('/')}}/fetchRecord",
		        "type": "POST",
		        "data" : {
		            "_token": "{{ csrf_token() }}",
		        }
		    },
		    initComplete: function () {
		    	var x = 0;
		        this.api().columns().every( function () {
		            var column = this;
		            x++;
		            console.log(x)
		            if(x != 6){
		            var select = $('<select><option value=""></option></select>')
		                .appendTo( $(column.footer()).empty() )
		                .on( 'change', function () {
		                    var val = $.fn.dataTable.util.escapeRegex(
		                        $(this).val()
		                    );

		                    column
		                        .search( val ? '^'+val+'$' : '', true, false )
		                        .draw();
		                } );

		            column.data().unique().sort().each( function ( d, j ) {
		                select.append( '<option value="'+d+'">'+d+'</option>' )
		            } );
		        }
		        } );

		        $('select').select2();
		    }
		});

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

		swal("Are you sure?", "You will not be able to recover this Reading!", {
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
		      // myrow.remove().draw();

			     $.ajax({
						url: "{{URL('/')}}/records/"+getid,
						type: "POST",
						data: {
							_token: "{{ csrf_token() }}",
							"_method": "DELETE",
						},
						success: function(data){
							 // console.log(data);
							fetchRecords();

						}        
				   });
		      break;
		 
		    default:
		      swal.close();
		  }
		});
	    
	} );
}

</script>
@endsection