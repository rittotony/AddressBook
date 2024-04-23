<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <?php require('views/templates/head.php'); ?>
   <style>
    .login-card, .register-card{
      max-width: 400px;
      margin: 50px auto;
      padding: 40px;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
	
  
<div class="register-card">
  <h2 class="text-center mb-4" id="frmHeader">Register</h2>
  <form id="frmRegister">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
    </div>
	<div class="form-group">
      <label for="phone">phone number</label>
      <input type="number" class="form-control" name="phone" id="phone" placeholder="Enter phone number">
    </div>
	<div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
    </div>
    <button type="submit" class="btn btn-primary btn-block" id="btn-register">Register</button>
  </form>
  <button class="btn btn-success btn-sm mt-1" style="float:right;" id="btn-login-card">Login</button>
</div>

<div class="login-card" style="display:none;">
  <h2 class="text-center mb-4">Login</h2>
  <form id="frmLogin">
    <div class="form-group">
      <label for="username">Email</label>
      <input type="email" class="form-control" name="email" id="username" placeholder="Enter email">
    </div>
	<div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
    </div>
    <button type="submit" class="btn btn-success btn-block" id="btn-login">Login</button>
  </form>
  <button class="btn btn-primary btn-sm mt-1" style="float:right;" id="btn-register-card">Register</button>
</div>



</body>
</html>
<script>
$(document).ready(function(){
	
  $('#btn-login-card').click(function(){
	  $('.register-card').hide();
	  $('.login-card').show();
  });
  
  $('#btn-register-card').click(function(){
	  $('.login-card').hide();
	  $('.register-card').show();
  });
  
  $('#btn-register').click(function(e){
	  e.preventDefault();
	   var formData = $('#frmRegister').serializeArray().reduce(function(obj, item){
                            obj[item.name] = item.value;
                            return obj;
                        }, {});
						
		var isEmpty = false;	

         for (var key in formData) {
            if (formData[key] === "") {
                isEmpty = true;
                break;
            }
         }	

        if(isEmpty)	
		{
			alert(key+" is required!");
			return;
		}
		else
		{
			$.post("controller/MainController.php",
			{ action:"registration", formdata:formData },
			function(result){
				if(result>=1)
				{
					window.location.href="views/add-address.php";
				}
				else
				{
					alert("something went to wrong!");
				}
			})
		}
  });
  
  $('#btn-login').click(function(e){
	  e.preventDefault();
	   var formData = $('#frmLogin').serializeArray().reduce(function(obj, item){
                            obj[item.name] = item.value;
                            return obj;
                        }, {});
						
		var isEmpty = false;	

         for (var key in formData) {
            if (formData[key] === "") {
                isEmpty = true;
                break;
            }
         }	

        if(isEmpty)	
		{
			alert(key+" is required!");
			return;
		}
		else
		{
			$.post("controller/MainController.php",
			{ action:"login", formdata:formData },
			function(result){
				if(result=="redirect-success")
				{
					window.location.href="views/add-address.php";
				}
				else
				{
					alert(result);
				}
			})
		}
		
  });
	
});
</script>