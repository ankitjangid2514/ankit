
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>

<div id="alert" class="alertss"></div>
<div class="container-fluid">
	<div class="row" style="background: var(--primary);padding: 10px;">
		<div class="col-12">
			<span style="color:var(--white);">Google Pay</span>
		</div>
	</div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
	<form method="post" id="game_form">
		<div class="row">
		
			<div class="col-12">
				<div class="form-group">
				    <label class="form-label" for="from_date">Google Pay Number</label>
				    <input type="hidden" id="name" name="name" value="gpay"/>
				    <input type="text" class="form-control" maxlength="10" name="value" id="value" value="" placeholder="Enter Number" required="required" />
				</div>
			</div>

		</div>
		<div style="position:fixed; bottom:0;width:100%;"  class="row">
		    <input type="submit" class="btn btn-primary" style="width: 100%;margin: 15px;background: var(--primary);" value="Submit" id="submit" name="submit" />
		</div>
	</form>
	
	<span id="output"></span>
</div>



	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>

<script>


   $(document).ready(function(e){
   $("#game_form").on('submit',(function(e) {
       e.preventDefault(); 
       if($("#value").val().length!="10")
       {
           alertt("Invalid Mobile Number");
          
       }else
       {
  
   $.ajax({
     url: "data/payment.php",
     type: "POST",
     data:  new FormData(this),
     contentType: false,
     cache: false,
     processData:false,
     success: function(html){
     $("#output").text("");  
     $("#output").prepend(html);
     },
     error: function(){}           
   });
       }
   }));
   });

var txt;
function alertt(txt)
{
   $("#alert").text(txt);
   $("#alert").show('slow');
   setTimeout(function () {
   $("#alert").hide('slow');
}, 2000);
}

</script>

</body>
</html>