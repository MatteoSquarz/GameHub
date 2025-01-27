<?php

function pulisciInput($value){
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}

function pulisciCognome($value){
    $value = trim($value);
    $value = strip_tags($value);
    return $value;
}

function pulisciCampiAdmin($value){
    $tagPermessi ='<span>';
 	$value = trim($value); 
  	$value = strip_tags($value,$tagPermessi);
  	return $value;
}

?>