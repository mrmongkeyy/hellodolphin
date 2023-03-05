<?php
//this is module file.
//i will put my function here.
//and this will be ok.

//make fundamental function.
//function read.
$globaldburl = '../db/base/db.json';
function read($dburl){
	//open the file.
	$file = fopen($dburl,'r') or die('unable to open this file>'.$dburl);
	$data = fread($file,filesize($dburl));
	fclose($file);
	return json_decode($data,true);
}
//function write
function write($dburl,$data){
	//get old data.
	//open the file
	//before that we need to use the global variable.
	$file = fopen($dburl,'w') or die('unable to open this file');
	fwrite($file,json_encode($data));
	fclose($file);
}
function datapoint($arr){
	//the value of arr, is obj.
	$newdata = array();
	for($x=0;$x<count($arr);$x++){
		$point = $arr[$x]['view'];
		$newdata[$x] = $point;
	}
	arsort($newdata);
	//echo json_encode($newdata);
	$enddata = array();
	foreach($newdata as $x=>$y){
		$enddata[count($enddata)] = $arr[$x];
	}
	return $enddata;
}
function getdata($key,$len){
	global $globaldburl;
	$data = read($globaldburl);
	$bucketsofdata = array();
	$arrdata = array();
	foreach($data[$key] as $contentid=>$y){
		$y['contentid'] = $contentid;
		$arrdata[count($arrdata)] = $y;
	}
	//point our data.
	$arrdata = datapoint($arrdata);
	//check for the limitation of our data.
	$datalen = count($arrdata);
	if($len[1]>$datalen){
		$len[1] = $datalen;
	}
	for($x=$len[0];$x<$len[1];$x++){
		//if(!$arrdata[$x])break;
		$bucketsofdata[count($bucketsofdata)] = $arrdata[$x];
	}
	echo json_encode($bucketsofdata);
}
function givedata($data){
	//in the end i can write the data back.
	global $globaldburl;
	$olddata = read($globaldburl);
	//updating userdata first.
	$contentslen = count($olddata['userdata'][$data['userid']]['contents']);//get the len of user content list.
	$olddata['userdata'][$data['userid']]['contents'][$contentslen] = $data['contentid'];
	//next am going to update contents.
	$olddata['contents'][$data['contentid']] = array(
		'title'=>$data['title'],
		'owner'=>$data['owner'],
		'prevlink'=>$data['prevlink'],
		'content'=>$data['content'],
		'time'=>$data['time'],
		'userid'=>$data['userid'],
		'status'=>$data['status'],
		'share'=>0,
		'comment'=>array(),
		'view'=>0
	);
	write($globaldburl,$olddata);
	echo 'thanks';
}
function getcontentdata($contentid,$linked){
	global $globaldburl;
	$data = read($globaldburl);
	if($linked==='true'){
		//i wanna update the data.
		$data['contents'][$contentid]['share'] += 1;
	}
	$data['contents'][$contentid]['view'] += 1;
	write($globaldburl,$data); 
	echo json_encode($data['contents'][$contentid]);
}
function setstatus($data){
	//this function needed a id of the content;
	global $globaldburl;
	$olddata = read($globaldburl);
	$olddata['contents'][$data['contentid']]['status']['like'] = $data['likes'];
	$olddata['contents'][$data['contentid']]['status']['notlike'] = $data['notlikes'];
	write($globaldburl,$olddata);
	echo 'thanks';
}
function articlecomment($id,$newcomment){
	//simple function for put a comment to the data.
	global $globaldburl;
	$data = read($globaldburl);

	//get the comment array len
	$commentlen = count($data['contents'][$id]['comment']);
	//put new comment to the data
	$data['contents'][$id]['comment'][$commentlen] = $newcomment;
	//i will write the data.
	write($globaldburl,$data);
	echo 'thanks';
}
//function for returning the comment data.
function givecommentdata($cid){
	global $globaldburl;
	$data = read($globaldburl);
	echo json_encode($data['contents'][$cid]['comment']);
}
function getprofiledata($wid){
	global $globaldburl;
	$data = read($globaldburl);
	echo json_encode($data['userdata'][$wid]);
}
function newuser($newuserid,$data){
	global $globaldburl;
	$olddata = read($globaldburl);
	$obj = array(
		'data'=>$data,
		'contents'=>array()
	);
	$olddata['userdata'][$newuserid] = $obj;
	write($globaldburl,$olddata);
	echo 'thanks';
}
function strsplits($src,$key){
	if($src=='')return array();
	$bucketsarr = array();
    while(true){
    	$lenarr = count($bucketsarr);
    	$index = strpos($src,$key);
        if($index==false){
        	$bucketsarr[$lenarr] = $src;
            break;
        }
        //append the data to the bucketsarr
        $str_i = '';
        for($x=0;$x<$index;$x++){
        	$str_i = $str_i.$src[$x];
        }
        $bucketsarr[$lenarr] = $str_i;
        $newsrc = '';
        $forxnum = $index+1;
        for($x=0;$x<strlen($src)-($index+1);$x++){
        	$newsrc = $newsrc.$src[$forxnum+$x];
        }
        $src = $newsrc;
    }
    return $bucketsarr;
}
function getcontentthisid($id){
	global $globaldburl;
	$contentbucket = read($globaldburl);
	//now this is the process of algorithm gonna work.
	//i wann get the arr of this id content.
	$bunchofc = $contentbucket['userdata'][$id]['contents'];
	$newdatacontent = [];
	foreach($bunchofc as $x){
		$data = $contentbucket['contents'][$x];
		$data['contentid'] = $x;
		$newdatacontent[count($newdatacontent)] = $data;
	}
	echo json_encode($newdatacontent);
}
function findthis($key){
	global $globaldburl;
	//make my own search engine algorithm. i will try.
	$keyarr = strsplits($key,' ');
	$contentbuckets = array();//place for storing the relevan content.
	$data = read($globaldburl);//general src
	$data = $data['contents'];//make it more spesipic.
	foreach($data as $name => $value){
		if(findeval($keyarr,$value['title'])>0 or findeval($keyarr,$value['owner'])){
			$contentbuckets[count($contentbuckets)] = $name;
		}
	}
	$dataarr = array();
	foreach($contentbuckets as $x){
		$len = count($dataarr);
		$data[$x]['contentid'] = $x;
		$dataarr[$len] = $data[$x];
	}
	echo json_encode($dataarr);
}
function findeval($a,$b){
	$counting = 0;
	$dataarr = strsplits($b,' ');
	foreach($a as $x){
		foreach($dataarr as $y){
			$len = 0;
			if(strlen($x)>strlen($y)){
				$len = strlen($y);
			}else{
				$len = strlen($x);
			}
			$c = 0;
			for($i=0;$i<$len;$i++){
				if(strtolower($x[$i])==strtolower($y[$i])){$c++;}
			}
			if($c>=strlen($x)/2){
				$counting++;
			}
		}
	}
	return $counting;
}
function changeprofileofthiswid($wid,$newdir){
	global $globaldburl;
	$data = read($globaldburl);
	//old file name
	$oldfname = $data['userdata'][$wid]['data']['profilepic'];
	$oldfname = str_replace('more','..',$oldfname);
	if(file_exists($oldfname) && !unlink($oldfname)){
		echo 'bad';
		return;
	}
	$data['userdata'][$wid]['data']['profilepic'] = $newdir;
	write($globaldburl,$data);
	echo 'ok';
}
function loginme($username,$pass){
	global $globaldburl;
	$userdata = read($globaldburl)['userdata'];
	if($userdata[$username]){
		echo json_encode($userdata[$username]);
		return 0;
	}
	echo '000';
}
function givedataofthisid($id,$wuntg){
	global $globaldburl;
	$data = read($globaldburl)['contents'];
	echo json_encode($data[$id][$wuntg]);
}
?>