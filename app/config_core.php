<?php
//PHP engine setting 
ini_set("magic_quotes_gpc", "0ff");
ini_set("display_errors", "on");
ini_set('error_reporting', E_ALL & ~E_NOTICE); //running on php 7
ini_set('max_file_upload',"20M");
ini_set("max_execution_time","3000");
ini_set("max_input_time","6000");
ini_set("max_input_vars","10000");
ini_set("memory_limit","512M");
ini_set("post_max_size","64M");
ini_set("register_globals","off");
ini_set("session.gc_maxlifetime","600");  //running on php 7
ini_set("date.timezone", "Asia/Hong_Kong");

?>