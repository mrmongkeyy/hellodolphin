<?php
	//we gonna updload file
	//get the i user id
	$wid = $_POST['wid'];
	$dburl = '../../db/img/';
	$filename = $_FILES['file']['name'];
	if($filename==''){
		echo 'no file selected';
		return 0;
	}
	$file = $dburl.$wid.$filename;//define the file
	//check for file exist
	if(!file_exists($file)){
		if(move_uploaded_file($_FILES['file']['tmp_name'],$file)){
			echo $wid.$filename;
		}else{
		    echo "error";
		}
		return 0;
	}
	echo '9098';
?>
