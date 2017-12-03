<?php


function ifEmpty($str){
	
	if($str){
		return $str;
	}else{
		return "-";
	}
	
}
function checkLogin(){
	
	if(!$_SESSION['login_mark']){
		dump(1111);exit;
	}
}

?>