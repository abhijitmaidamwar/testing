<!doctype html>
<html lang="en">
	<?php
	error_reporting(0);
	include 'header.php';
	$lib = new library;
	$hostname = $lib->hostname();
	
	$urlArray = explode("/",$_GET['url']);
	$volno = $urlArray[1];
	$split = explode(".",$volno); 
	$seprate = explode("-",$split[0]);
	
	$volume = $seprate[1];
	$number = $seprate[3];
	
	//print_r($split[0]);
	$finalscripts = $lib->select("finialmanuscript",array("volume" => $volume, "number" => $number, 'is_delete' => 0),"AND");
	
	$sr = 1;
	?>
	<body>
		<!-- Header Section Starts -->
		<?php
		include 'nav.php';
		?>
		<!-- Header Section Ends -->
		<div class="container-main">
			<div class="row">
				<!--Left Sidebar Start -->
				<?php
				include 'left-sidebar.php';
				?>
				<!--Left Sidebar End -->
				<div class="col-xs-12 col-md-9">
					<h4><?php echo "Vol. ".$finalscripts[0]['volume']." No.".$finalscripts[0]['number'].", ".$finalscripts[0]['year'];?></h4>
					<table class="table table-strped journal-list">
						<tbody>
                        <?php if(!empty($finalscripts )){
							foreach($finalscripts as $key => $val) { ?>
                        <tr>
								<td><?php echo $sr++."."; ?></td>
								<td><a href="<?php echo $hostname; ?>finial-docs/finial-pdf/<?php echo  $val['finalscript']; ?>" target="_blank" download><h5 style="margin: 0; padding: 0; font-weight:bold;"><?php echo $val['title']; ?></h5></a> (<?php echo $val['nameofauthor']; ?>,<?php echo $val['coauthor']; ?>)<br> <a href="<?php echo $hostname; ?>finial-docs/finial-pdf/<?php echo  $val['finalscript']; ?>" target="_blank" download><h5 style="margin: 0; padding: 0;"><?php echo $val['doinumber']; ?></h5></a> <h5 style="margin: 0; padding: 0; color: #041E41"><?php echo $val['publishonline']; ?></h5> </td>
							</tr>
                        <?php } } else { ?>
                        <h4>No results found!</h4>
                        <?php } ?>
							
						</tbody>
					</table>

				</div>
				<!-- Left Sidebar Start -->

				<!-- Left Sidebar End -->
			</div>
		</div>
		<!-- Footer Section Starts -->
		<?php
		include 'footer.php';
		?>
	</body>
</html>