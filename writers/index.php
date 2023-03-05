<?php
//ijusttakesomeinfo
$wid = $_GET['wid'];
?>
<html>
	<script>
		const wid = '<?php echo $wid ?>';
		const data = {
			mode:'viewprofile',
			wid
		}
		localStorage.setItem('hiddendata',JSON.stringify(data));
		location.href = 'http://localhost/apps/thearticle';
	</script>
</html>