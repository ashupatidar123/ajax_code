function pageRefresh(pagename='')
{
    location.reload();
}

function successMessage(message='Success'){
  swal({
    position: 'center',
    title: message,
    icon: "success",
    timer: 1500
  });
  setTimeout(function() {
      location.reload();
  },1400);
}

function errorMessage(message='Opps! operation is failed...'){
  swal({
    position: 'center',
    title: message,
    icon: "warning",
    timer: 1300,
  });
}


function confirmMsg(msg=''){ 
	if(confirm("Are you sure to "+msg+" ?")==true)
    	return true;
  	else
    	return false;
}

function usernameCheck(username){
		
	remChar('username',username); // remaining charactor count
  $.ajax({
		method:"POST",
		url:"userCheck",
		data:{_token:csrf_token,username:username},
		success:function(data){
			if(data>0){
				var msg = 'Username is already exist.';
				$('.usernameError').html(msg);
			}else{
				$('.usernameError').html('');
			}
		}


	});
}

function onlyNumbers(type='')
{
    $('input[name="mobile"]').attr({minlength:"10",maxlength:"15"});
    var regex = new RegExp("^[0-9+.]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }else{
        return true;
    }
}

function onlyLatters(type='')
{
    var regex = new RegExp("^[a-zA-Z0-9 ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }else{
        return true;
    }
}

function recordDelete(id,pagename,type='')
{
    if(!confirm("Do you want to delete?")){
      return false;
    }

    if(pagename=='userList')
    {
      $.ajax({
        method:"post",
        url:'userDelete',
        data:{_token:csrf_token,id:id},
        success:function(data){
          //alert(data);
          if(data>0){            
            successMessage('Record deleted successfully...');
          }else{
            errorMessage('Opps! Deletion failed...');
          }
        }

      });
    }
    else
    {	errorMessage('Opps! Invalid details...');
    	return false;
    }
}

function addVal(filedName,val='')
{
  if(filedName='username'){
    $('input[name="username"]').attr({maxlength:20});
  }
}

function remChar(filedName,val='')
{
  if(filedName='username')
  {
    $('input[name="username"]').attr({maxlength:20});
    var maxchar = 20;
    var len = val.length;
    if (len>0) {
      $("#remUsername").html("Remaining characters: " +(maxchar-len));
    }
    else {
      $("#remUsername").html("Remaining characters: " +(maxchar));
    }
  }
}

function add_task()
{
  setTimeout(function(){$('.error').html('');},5000);

  var user_id   = $("input[name='user_id']").val();
  var task_name = $("input[name='task_name']").val();
  var title     = $("input[name='title']").val();
  if(user_id==''){
    $('.user_id').html('User ID is required...');
    return false;
  }
  if(task_name==''){
    $('.task_name').html('Task name is required...');
    return false;
  }
  else if(title==''){
    $('.title').html('Title is required...');
    return false;
  }

  var form_Data = new FormData($('#formVal')[0]);
  
  $.ajax({
    method:"POST",
    url:base_url2+"webpanel/taskAdd",
    contentType: false,
    processData: false,
    data: form_Data,
    beforeSend: function() {
      $('.addLorder').addClass('spinner-border');
    },
    success:function(resp)
    {
      if(resp>0){
        successMessage('Task added successfully...');
        $('.addLorder').removeClass('spinner-border');
      }else{
        errorMessage('Task adding failed...');
      }
    }
    
  });
}