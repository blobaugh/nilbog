<?php
//new dBug($ext_params);

if(!isset($ext_params['format'])) {
	$ext_params['format'] = 'Y-m-d';
}


echo date($ext_params['format']);