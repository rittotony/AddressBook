<?php
session_start();

if(!isset($_SESSION['user_id']))
{
	header('Location: ../index.php');   
    exit;
}  

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require('templates/head.php'); ?>
        <style>
            .address-card{
                max-width: 400px;
                margin: 50px auto;
                padding: 40px;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
			.table-card{
			     max-width: 800px;
                margin: 50px auto;
                padding: 40px;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			}
        </style>
    </head>
    <body>
	    <h3 class="text-center text-primary"><?php echo "Welcome ".$_SESSION['user_name']; ?></h3>
		<div class="text-center">
		   <a class="text-danger" href="../index.php">Home</a>
		</div>
        <div class="row">
            <div class="col-6">
                <div class="address-card">
                    <h2 class="text-center mb-4" id="frmHeader">Add Address</h2>
                    <form id="frmAddAddress">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" />
                        </div>
                        <div class="form-group">
                            <label for="phone">phone number</label>
                            <input type="number" class="form-control" name="phone" id="phone" placeholder="Enter phone number" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="btn-save">Save</button>
						<button type="submit" class="btn btn-success btn-block" id="btn-update">Update</button>
                    </form>
                </div>
            </div>
            <div class="col-6">
			  <div class="table-card">
                <table class="table table-bordered" id="tbl_address">
                    <thead>
                        <tr>
                            <th scope="col">SINO</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
							<th scope="col">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
			</div>
        </div>
    </body>
</html>
<script>
    $(document).ready(function () {
		
		var tbl_address = $('#tbl_address').DataTable({});
		
		load_tbl_address(<?php echo $_SESSION['user_id']; ?>);
		
		function load_tbl_address(userId){
		
			tbl_address.destroy();
			
			tbl_address = $('#tbl_address').DataTable({
				
				"ajax": {
						'type': 'POST',
						'url': '../controller/MainController.php',
						'data': {
							action: 'list_user_address',
							ids:userId
						}
					},
					"language": {
						"zeroRecords": "No records available",
						"infoEmpty": "No records available",
					},
					
					order: [[0, 'desc']],
					
					"columns": [
					{ "data": null },
					{  "data":"name" },
					{  "data":"email" },
					{  "data":"phone" },
					{  "data":null,
						 "render":function ( data, type, rows, meta )
						{
						   return '<button class="btn-sm btn-success" name="edit">Edit</button>';
						}
					}
				 ],
				 "fnRowCallback" : function(nRow, aData, iDisplayIndex){
					$('td:eq(0)', nRow).html(iDisplayIndex +1);
				   return nRow;
				},
			});
	   }
	    
		var rowId='';
		$('#btn-update').hide();
	    $('#tbl_address tbody').on('click', 'button', function () {
			var $row = $(this).closest('tr');
			var rowData = tbl_address.row($row).data();
			rowId = rowData.ids;
			if($(this).attr("name")=='edit')
			{
				$('#btn-save').hide();
				$('#btn-update').show();
				$('#name').val(rowData.name);
				$('#email').val(rowData.email);
				$('#phone').val(rowData.phone);
			}
	    });
	   
	   
	   $('#btn-update').click(function(e){
		      e.preventDefault();
			  var formData = $('#frmAddAddress').serializeArray().reduce(function(obj, item){
								obj[item.name] = item.value;
								return obj;
							}, {});
			formData.ids = rowId;				
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
				$.post("../controller/MainController.php",
				{ action:"edit_address", formdata:formData },
				function(result){
					if(result>=1)
					{
						load_tbl_address(<?php echo $_SESSION['user_id']; ?>);
						$('#frmAddAddress').trigger("reset");
						$('#btn-update').hide();
						$('#btn-save').show();
					}
					else
					{
						alert("something went to wrong!");
					}
				})
			}
	   });
	   
	   
	   
	   
	   $('#btn-save').click(function(e){
		   e.preventDefault();
		  var formData = $('#frmAddAddress').serializeArray().reduce(function(obj, item){
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
			$.post("../controller/MainController.php",
			{ action:"add_address", formdata:formData },
			function(result){
				if(result>=1)
				{
					load_tbl_address(<?php echo $_SESSION['user_id']; ?>);
					$('#frmAddAddress').trigger("reset");
				}
				else
				{
					alert("something went to wrong!");
				}
			})
		}
	   });
		
	});
</script>
