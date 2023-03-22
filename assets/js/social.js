var this_js_script = $('script[src*=social]');

// console.log(this_js_script);

var stop = 0;

$(function(){
	fetchNewPost()
});

function fetchPost(id){
	$("#myPost").html('');
	$("#staticBackdropLabel").html('');
	$("#myContent").html('');
	$.ajax({
		url: this_js_script.attr('data-url')+"/fetchPost/"+id,
		type: "POST",
		data: {
			_token: this_js_script.attr('data-token'),
		},
		success: function(data){
			// console.log(data.post.title)
			$("#myPost").html(data.view);
			$("#staticBackdropLabel").html(data.post.title);
			$("#myContent").html(data.post.content);
			$("#postedBy").html(data.posted);
			$("#timed").html(data.timed);
		}        
   });
}

$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

$("#file-upload").change(function(){

	if($(this).get(0).files.length){
	$.each($(this).get(0).files, function(index, file) {

		var reader = new FileReader(); 
		reader.onload = function (e) {
			var html = "<div class='item col-sm-3' ondblclick='removeDiv(this)' style='position:relative;'><a class='close AClass' onClick='removeDiv1(this)' style='cursor: pointer'><span>&times;</span></a>";
			html +=      " <img class='img-thumbnail' src='"+ reader.result+"'>";
			html +=      " <input type='hidden' name='image_type[]' value='"+ file.type+"'>"; 
			html +=      " <input type='hidden' name='image_name[]' value='"+ file.name+"'>"; 
			html +=      " <input type='hidden' name='image[]' value='"+ e.target.result+"'>"; 
			html +=      " <input type='hidden' name='image_id[]' value='0'>"; 
			html += "</div>";

			$(".img-selected-wrapper").prepend(html);
		} 
		reader.readAsDataURL(file); 
	}); 
	$("#file-upload").val('');
	}
});

$("#file-upload1").change(function(){

	if($(this).get(0).files.length){
	$.each($(this).get(0).files, function(index, file) {

		var reader = new FileReader(); 
		reader.onload = function (e) {
			var html = "<span style='margin: 4px 0px; font-size: 12px;' class='badge bg-primary item'><span aria-hidden='true' style='cursor: pointer' onClick='removeDiv2(this)'>&times;</span> "+file.name+"</span>";
			html +=      " <input type='hidden' name='file_type[]' value='"+ file.type+"'>"; 
			html +=      " <input type='hidden' name='file_name[]' value='"+ file.name+"'>"; 
			html +=      " <input type='hidden' name='file[]' value='"+ e.target.result+"'>"; 
			html +=      " <input type='hidden' name='file_id[]' value='0'>"; 
			html += "</div>";

			$(".file-selected-wrapper").prepend(html);
		} 
		reader.readAsDataURL(file); 
	}); 
	$("#file-upload1").val('');
	}
});

function fetchNewPost(){
	$("#news_feed_loading").show();

	post_ids = $("#post_ids").val();

	$.ajax({
        url: this_js_script.attr('data-url')+"/fetchNewPost",
        type: "POST",
        data: {
          _token: this_js_script.attr('data-token'),
          "post_ids": post_ids,
          "user_id": this_js_script.attr('data-auth_user_id')

        },
        success: function(data){
        	$("#post_ids").val(data.post_ids);

        	if(data.view != ""){
        		$("#new_feed").append(data.view);

        	} else {
        		swal("", "No other posts found!");
        		stop = 1;
        		$("#news_feed_stop").show();
        	}

        	$("#news_feed_loading").hide();
        }        
      });
	}

function removeDiv(test){

	swal("Are you sure?", "You will not be able to recover this Photo!", {
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

		test.remove();
		break;

		default:
		swal.close();
		}
	});
}

function removeDiv1(test){

	swal("Are you sure?", "You will not be able to recover this Photo!", {
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

			test.closest('.item').remove();
		break;

		default:
		swal.close();
		}
	});
}

function removeDiv2(test){

	swal("Are you sure?", "You will not be able to recover this File!", {
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

			test.closest('.item').remove();
		break;

		default:
		swal.close();
		}
	});
}

function clearForm(){
	$(".img-selected-wrapper").html('');
	$(".file-selected-wrapper").html('');
	$('#workload-selector').val(null).trigger('change');
	changePostType('','NONE');
	if(this_js_script.attr('data-auth_user_id') == this_js_script.attr('data-user_id')){

		changePost('globe-americas','public');
	} else {
		changePost('users','follow');
	}

	$("#post_content").val('');
	$("#post_title").val('');
	CKEDITOR.instances['post_content'].setData('');
}

function postMe(){
  var formData = new FormData($("#IAmAForm")[0]);
  formData.append('content', CKEDITOR.instances['post_content'].getData());

  formData.append('_token', this_js_script.attr('data-token'));

  if($("#post_title").val() != ""){
  	$("#post_new_feed_loading").show();

  	clearForm();
  	if(stop != 0){
  		$("#news_feed_stop").hide();
  	}

  	$.ajax({
      url: this_js_script.attr('data-url')+"/post",
      type: "POST",
      processData: false,
      contentType: false,
      data: formData,
      success: function(data){
      	if(post_ids != ""){
      		$("#post_ids").val(post_ids+","+data.post_ids);
      	} else {
      		$("#post_ids").val(data.post_ids);
      	}

  		$("#new_feed").prepend(data.view);  

  		$("#post_new_feed_loading").hide();

  		if(stop != 0){
	  		$("#news_feed_stop").show();
	  	}
      	
      }        
    });

  } else {
  	swal("", "Title is Required","warning");
  	$("#post_title").focus();
  }

  
}

function changePost(icon, post_type){
	$("#post_icon").attr('data-icon',icon)
	$("#post_type").val(post_type);
}
function changePostType(id, value){
	$("#post_type_text").html(value);
	$("#text_post_type").val(id);
}

$('#my-button').click(function(){
    $('#file-upload').click();
});

$('#my-button1').click(function(){
    $('#file-upload1').click();
});

var post_content = CKEDITOR.replace('post_content', {
	filebrowserBrowseUrl: this_js_script.attr('data-url')+"/../kcfinder/browse.php?type=files"
});

$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() == $(document).height()) {
   	// $("#news_feed_loading").show();
   	if(stop == 0){
       fetchNewPost();
   	} else {
   		$("#news_feed_loading").hide();
   	}
   }
});