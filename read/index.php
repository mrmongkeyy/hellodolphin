<?php
$id = $_GET['contentid'];
$linked = $_GET['linked'];
?>
<html>
	<script>
		//set a message for the init.
		const x = <?php echo "'".$id."'"?>;
		const islinked = <?php echo "'".$linked."'"?>;
		//now time to make a obj
		const data = {
			mode:'read',
			contentid:x,
			yeslinked:true
		}
		if(islinked=='true'){
			localStorage.setItem('hiddendata',JSON.stringify(data));
			location.href = location.origin;
		}
	</script>
</html>