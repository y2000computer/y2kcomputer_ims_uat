<?php
$policy_module = $_SESSION["policy_module"];
$module = explode(",", $policy_module);

$moudule_count = count($module);

for($x=0; $x<$moudule_count; $x++){
 	 $result = $module[$x];
	 $module_result = explode("-", $result);
	$module_code_array[] = $module_result[0];
 }
?>


<div class="sidebar" id="MainMenuDiv">
	<ul>
		<li class="main_menu_selected"><span>Main Menu</span></li>
		<?php
		if(in_array("ARTICLE", $module_code_array)){  
		echo '<LI class="main_menu_unselected"><a href="/'.'article'.'/'.IS_LANG.'/article_home/">'.'Article'.'&nbsp;'.'&raquo;'.'</a></LI>';
		}
		?>		
		<?php
		if(in_array("PHOTO", $module_code_array)){  
		echo '<LI class="main_menu_unselected"><a href="/'.'photolibrary'.'/'.IS_LANG.'/photo_home/">'.'Photo Library'.'&nbsp;'.'&raquo;'.'</a></LI>';
		}
		?>		
		<?php
		if(in_array("GL", $module_code_array)){  
		echo '<LI class="main_menu_unselected"><a href="/'.'erp'.'/'.IS_LANG.'/gl_home/">'.'General Ledger'.'&nbsp;'.'&raquo;'.'</a></LI>';
		}
		?>

		<?php
		if(in_array("PROP", $module_code_array)){  
		echo '<LI class="main_menu_unselected"><a href="/'.'prop'.'/'.IS_LANG.'/prop_home/">'.'Properly Rent/Maint.'.'&nbsp;'.'&raquo;'.'</a></LI>';
		}
		?>

		<?php
		if(in_array("SYS", $module_code_array)){  
		echo '<LI class="main_menu_unselected"><a href="/'.'erp'.'/'.IS_LANG.'/sys_home/">'.'System'.'&nbsp;'.'&raquo;'.'</a></LI>';
		 }
		?>

	
	</ul>
</div>


