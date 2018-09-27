<!DOCTYPE html>
<html>
<head>
	<title></title>
	 <script src="../scripts/jquery-3.3.1.min.js"></script>
 <!-- Include all compiled plugins (below), or include individual files as needed -->
 <script src="../scripts/bootstrap.min.js"></script>
 <script src="../scripts/custom.js"></script> 
</head>

<body>
<div class="content"></div>

<script>
      $(function(){
      	alert(I am here);
      			$('.content').load('/pages/mgt_portal.php .decryptedMsg');

      			window.print();
      	      });
</script> 
</body>
</html>
     	