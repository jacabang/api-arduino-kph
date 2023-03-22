@extends('layouts.main')
@section('title', 'HOMERGY | Users')
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
		    <li class="breadcrumb-item active" aria-current="page">User</li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
        	<div class="form-title">
        		@if(isset($editable[3]))
						<a href="{{URL('/')}}/user/create" style="float: right;" class="btn btn-primary btn-flat btn-pri">
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
				            <th>Name</th> 
				            <th>Email</th>
				            <th>Username</th>
				            <th>User Group</th>
				            <th>Created By</th>
				            <th>Created At</th>
				            @if(isset($editable[3]))
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
		
		$('#localizationCollapseMenu').addClass('show'); //collapse sub menu
		$('#localizationSideMenu').addClass('active'); //highlight parent menu
		$("#localizationSideMenu").attr("aria-expanded","true"); //indicator that it is collapse

		$('#usersCollapseMenu').addClass('show');
		$('#usersSideMenu').addClass('active');
		$("#usersSideMenu").attr("aria-expanded","true");

		$('#userSideMenu').addClass('active');
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
	    responsive: true,
	    "ajax": {
	        "url": "{{URL('/')}}/fetchUser",
	        "type": "POST",
	        "data" : {
	            "_token": "{{ csrf_token() }}",
	        }
	    }
	});

  $('#table_id tbody').on( 'click', 'button.icon-reset', function () {
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

		swal("Are you sure?", "You want to reset this Users password!", {
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
					url: "{{URL('/')}}/resetPassword/"+getid,
					type: "POST",
					data: {
						_token: "{{ csrf_token() }}",
					},
					success: function(data){
						 // console.log(data);
						 swal("123qwe", "Reset Password Completed", "success");

					}        
			   });
		      break;
		 
		    default:
		      swal.close();
		  }
		});
	    
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

			swal("Are you sure?", "You will not be able to recover this User!", {
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
							url: "{{URL('/')}}/user/"+getid,
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