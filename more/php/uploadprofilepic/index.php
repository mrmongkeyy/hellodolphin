<?php
	$wid = $_GET['wid'];
?>
<style>
	body{
		margin-top: 15px;
	}
</style>
<div id=parent>
	<input id=file type=file>
	<button style=display:none;>Upload</button>
</div>
<script>
	const get = function(key){return document.querySelector(key)}
	const fileinput = get('input[type="file"]');
	var formdata = new FormData();
	fileinput.addEventListener('change',function(){
		formdata.append("file",fileinput.files[0]);
		formdata.append('wid',<?php echo "'".$wid."'"?>);
		get('button').style.display = 'inline-block';	})
	get('button').addEventListener('click',function(){
		const x = new XMLHttpRequest();
		x.open('post','upload.php');
		x.send(formdata);
		x.onload = function(x){
		    console.log(x);
		    if(x.target.responseText == '9098'){
		        get('#parent').innerHTML = '<b>FileExist. Click on cancel and Try it again.</b>';
		        return;
		    }
			if(x.target.responseText=="error"){
				get('#parent').innerHTML = x.target.responseText;
			}else{
				get('#parent').innerHTML = '<b>Uploaded</b>';
				localStorage.setItem('imgname',x.target.responseText);
			}
		}
	})
</script>