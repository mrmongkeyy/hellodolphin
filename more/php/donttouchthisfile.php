<?php
//this file will be our operator.

//checking for the mode.
$valid = $_POST['validity'];
if($valid == 'true'){
	//if valid am going to load module.
	include 'module.php';
	//move to the next step.
	$mode = $_POST['mode'];
	switch($mode){
		case 'login':
			loginme($_POST['username'],$_POST['password']);
			break;
		case 'newuser':
			//make a data obj
			$newuserid = $_POST['id'];
			$data = array(
				'pass'=>$_POST['pass'],
				'username'=>$_POST['username'],
				'moto'=>$_POST['moto'],
				'age'=>$_POST['age'],
				'hobbies'=>$_POST['hobbies'],
				'idontlikeon'=>$_POST['ihateon'],
				'motivationalquote'=>$_POST['quotes'],
				'profilepic'=>$_POST['profilepic']
			);
			newuser($newuserid,$data);
			break;
		case 'givedata':
			//make a data first.
			$data = array(
				'userid'=>$_POST['userid'],
				'contentid'=>$_POST['contentid'],
				'prevlink'=>$_POST['prevlink'],
				'title'=>$_POST['title'],
				'content'=>$_POST['content'],
				'owner'=>$_POST['owner'],
				'time'=>$_POST['time'],
				'status'=>array('like'=>array(),'notlike'=>array())
			);
			givedata($data);
			break;
		case 'getdata':
			$whatineedtoget = $_POST['getdatakey'];
			getdata($whatineedtoget,array($_POST['start'],$_POST['end']));
			break;
		case 'getprofiledata':
			$wid = $_POST['wid'];
			getprofiledata($wid);
			break;
		case 'getcontentdata':
			$contentid = $_POST['contentid'];
			$linked = $_POST['linked'];
			getcontentdata($contentid,$linked);
			break;
		case 'articlecomment':
			$contentid = $_POST['contentid'];
			$data = array(
				'userid'=>$_POST['userid'],
				'time'=>$_POST['time'],
				'status'=>$_POST['status'],
				'comment'=>$_POST['newcomment'],
				'username'=>$_POST['username']
			);
			articlecomment($contentid,$data);
			break;
		case 'getcommentdata':
			$contentid = $_POST['contentid'];
			givecommentdata($contentid);
			break;
		case 'setstatus':
			$data = array(
				'contentid'=>$_POST['contentid'],
				'likes'=>strsplits($_POST['likes'],','),
				'notlikes'=>strsplits($_POST['notlikes'],',')
			);
			setstatus($data);
			break;
		case 'getcontentthisid':
			//collecting the data.
			$id = $_POST['id'];
			getcontentthisid($id);
			break;
		case 'findthis':
			findthis($_POST['key']);
			break;
		case 'changeprofileofthiswid':
			changeprofileofthiswid($_POST['wid'],$_POST['newdir']);
			break;
		case 'getdataofthisid':
			givedataofthisid($_POST['id'],$_POST['wuntg']);
			break;
	}
}

?>