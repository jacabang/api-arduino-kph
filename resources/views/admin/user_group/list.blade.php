@extends('layouts.main')
@section('title', 'HOMERGY | User Group')
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
		    <li class="breadcrumb-item active" aria-current="page">User Group</li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
        	<div class="form-title">
        		@if(isset($editable[4]))
						<a href="{{URL('/')}}/user_group/create" style="float: right;" class="btn btn-primary btn-flat btn-pri">
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
				            <th>User Group</th> 
				            <th># of Members</th>
				            <th>Created By</th>
				            <th>Created Date</th>
				            @if(isset($editable[3]))
				            <th>Action</th>
				            @endif
				        </tr>
				    </thead>
					<tbody>
					</tbody>
				</table>
			</di
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
<script>

  function viewTemplate(id){
		$("#modalBody").html('');
		$.ajax({
			url: "{{URL('/')}}/user_groupView/"+id,
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}"
			},
			success: function(data){

				$("#modalBody").html(data);

			}        
	   });
	}

	$(function(){
		$('#localizationCollapseMenu').addClass('show'); //collapse sub menu
		$('#localizationSideMenu').addClass('active'); //highlight parent menu
		$("#localizationSideMenu").attr("aria-expanded","true"); //indicator that it is collapse

		$('#usersCollapseMenu').addClass('show');
		$('#usersSideMenu').addClass('active');
		$("#usersSideMenu").attr("aria-expanded","true");

		$('#userGroupSideMenu').addClass('active');
	});

    var table = $('#table_id').DataTable( {
			"order": ['0', 'asc'],
			// "bPaginate": false,
			// "processing": true,	
			dom: 'Bfrtip',
	    // scrollX: true,
	    buttons: [
	      {
	          extend: 'pageLength', 
	          className: 'datatable_button',
	          title: 'User Group List as of {{date('M d, Y')}}'
	      },
	      {
	          extend: 'csv', 
	          className: 'datatable_button',
	          text: 'Export',
	          title: 'User Group List as of {{date('M d, Y')}}',
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
	        "url": "{{URL('/')}}/fetchUserGroup",
	        "type": "POST",
	        "data" : {
	            "_token": "{{ csrf_token() }}",
	        }
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

			swal("Are you sure?", "You will not be able to recover this User Group!", {
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
							url: "{{URL('/')}}/user_group/"+getid,
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