<?php
	// User logged in
	require_once 'config.php';
	session_start();
	if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
		header("location: login.php");
		exit;
	}
	
	// User not teacher
	if(($_SESSION['permission']) == "t"){
		header("location: index.php");
		exit;
	}
	
$orderedBy = array('subject', 'name', 'author', 'id', 'subject desc', 'name desc', 'author desc', 'id desc');
$order = 'subject';
if (isset($_GET['orderedBy']) && in_array($_GET['orderedBy'], $orderedBy)) {
	$order = $_GET['orderedBy'];
}
	
$sql = 'SELECT * FROM archiv ORDER BY '.$order;
		
$query = mysqli_query($link, $sql);

if (!$query) {
	die ('SQL chyba: ' . mysqli_error($link));
}
?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <title>Archiv | Výpisky</title>
	
	<?php
		// Set design
		include("parts/head.php");
		if (isset($_SESSION['design'])){
			echo "<link href='assets/css/bootstrap-".$_SESSION['design'].".css' rel='stylesheet'>
			<link href='assets/css/main-".$_SESSION['design'].".css' rel='stylesheet'>";
		}else{
			echo "<link href='assets/css/bootstrap-default.css' rel='stylesheet'>
			<link href='assets/css/main-default.css' rel='stylesheet'>";
		}
	?>
		
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
  </head>

  <body class="x-body">
	
	<?php
		include("header.php");
	?>
	<div class="content">
		<div class="centered">
			<h3>ARCHIVNÍ VÝPISKY</h3><br>
		</div>
		<?php
			if (mysqli_num_rows($query)!=0){
				echo "<div class='container'>
						<div class='form-group col-md-2'>
							<label for='Order'>Seřadit dle:</label>
							<select id='Order' name='OrderBy' class='form-control'>
								<option default value='?orderedBy=subject'>Předmětu &#9661;</option>
								<option value='?orderedBy=id'>Stáří &#9661;</option>
								<option value='?orderedBy=name'>Názvu &#9661;</option>
								<option value='?orderedBy=author'>Autora &#9661;</option>
								<option value='?orderedBy=subject desc'>Předmětu &#9651;</option>
								<option value='?orderedBy=id desc'>Stáří &#9651;</option>
								<option value='?orderedBy=name desc'>Názvu &#9651;</option>
								<option value='?orderedBy=author desc'>Autora &#9651;</option>
							</select>
						</div>
					</div>";
				}
		?>
		<script>
		 $(function(){
			  $('#Order').on('change', function () {
				  var url = $(this).val();
				  if (url) {
					  window.location = url;
				  }
				  return false;
			  });
			});
		</script>
		<script>
		var $_GET = {};

		document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
			function decode(s) {
				return decodeURIComponent(s.split("+").join(" "));
			}

			$_GET[decode(arguments[1])] = decode(arguments[2]);
		});
		$("#Order option[value='?orderedBy="+$_GET["orderedBy"]+"']").attr('selected', 'selected');
		</script>
		
		<br>
		<div class="container-w">
		
		<div class="table-responsive">
		<table class='table table-hover table-striped'>
			<?php
			if (mysqli_num_rows($query)!=0){
				
				if(($_SESSION['permission'])=="o"){
					echo "<thead>
							  <tr>
							    <th><i class='fa fa-trash-o'></i></th>
								<th>PŘ.</th>
								<th>NÁZEV</th>
								<th><i class='fa fa-inbox'></i></th>
								<th>AUTOR</th>
								<th><i class='fa fa-download'></i></th>
							  </tr>
						</thead>";
					while ($row = mysqli_fetch_array($query))
						{
							echo "<tr>
									<td><a href='file.php?action=archdel&id=".$row['id']."'><i class='fa fa-trash-o'></i></a></td>
									<td>".$row['subject']."</td>
									<td>".$row['name']."</td>
									<td><a href='file.php?action=dearch&id=".$row['id']."'><i class='fa fa-inbox'></i></a></td>
									<td>".$row['author']."</td>	
									<td><a href='".$row['address']."' target='_blank'><i class='fa fa-download'></i></a></td>
								</tr>";		
						}
					}else{
					echo "<thead>
		  <tr>
			<th>PŘ.</th>
			<th>NÁZEV</th>
			<th>AUTOR</th>
			<th><i class='fa fa-download'></i></th>
		  </tr>
		</thead>";
		
			while ($row = mysqli_fetch_array($query))
			{
				echo "<tr>
						<td>".$row['subject']."</td>
						<td>".$row['name']."</td>
						<td>".$row['author']."</td>	
						<td><a href='".$row['address']."' target='_blank'><i class='fa fa-download'></i></a></td>";	
					echo '</tr>';
			}
				}
			}else{
				echo"</table><div class='centered'><h3>Nejsou k dispozici žádné výpisky</h3></div><table>";
				}
			?>
			</table></div>
			<br>
		</div>
	</div>
	<?php
		include("footer.php");
	?>
  </body>
</html>