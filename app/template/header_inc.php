<!DOCTYPE html>
<html lang="us">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?php echo PORTAL_NAME ?></title>

		<meta http-equiv="expires" content="0">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">

		<link href="/css/stylesheet.css" rel="stylesheet">
		<link href="/css/default.css" rel="stylesheet">
		<link href="/css/dialog.css" rel="stylesheet">
		<link href="/css/jquery-ui.css" rel="stylesheet">


		<script language="javascript" type="text/javascript" src="/js/jquery.js.download"></script>
		<script language="javascript" type="text/javascript" src="/js/axios.min.js.download"></script>
		<script language="javascript" type="text/javascript" src="/js/vue.js.download"></script>
		<script language="javascript" type="text/javascript" src="/js/jquery-ui.js.download"></script>
	</head>

	<?php
		if($body_photo_preview_is==true) echo '<body class="previewOpen">';
		if($body_photo_preview_is==false) echo '<body>';
	?>
		
	<body>
		<div class="header" id="HeaderDiv">
			<div class="appInfo" id="AppInfoDiv">
				<span class="projectLogo"><?php echo PORTAL_NAME ?></span>
				<a class="menuBtn commonTextBtn" href="
				<?php 
					list($goto_app,$goto_module) = getMainMenu($_SESSION["policy_module"]);
					$url = '/'.$goto_app.'/'.IS_LANG.'/'.$goto_module;
					echo $url;
				?>">Main Menu</a>
				<span class="label" style="color: #f00;">
				<?php if(ENV=='UAT') echo '(UAT)';?>
				</span>
				
			</div>
			<div class="quickMenu" id="QuickMenuDiv">
				<?php 
					$url = '/'.'erp'.'/'.IS_LANG.'/'.'sys_security'.'/view_company';
					echo '<a class="menuBtn commonTextBtn" href="'.$url.'"';
					echo 'Target > ';
					echo $_SESSION["target_comp_name"];
					echo '</a>';
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="changePwBtn commonTextBtn" href="
				<?php 
					$url = '/'.'erp'.'/'.IS_LANG.'/'.'sys_security'.'/edit_password';
					echo $url;?>">Change password</a>
				<a class="logoutBtn commonTextBtn" href="
				<?php 
					$url = '/'.'erp'.'/'.IS_LANG.'/'.'sys_security'.'/logout';
					echo $url;?>">Logout</a>				
			</div>
		</div>
		
