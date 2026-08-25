<?
ob_start();
function setInclude($include,$path=''){
	$input = explode(",",$include);
	if(in_array('htmlCore',$input)){
		include_once($path.'php/htmlCore.php');
	}
	if(in_array('htmlControl',$input)){
		include_once($path.'indyEngine/htmlControl.php');
	}
	if(in_array('dbControl',$input)){
		include_once($path.'indyEngine/dbControl.php');
	}	
	if(in_array('sessionControl',$input)){
		include_once($path.'indyEngine/sessionControl.php');
	}
	
	if(in_array('IDUControl',$input)){
		setPlugin('IDU');
	}
	echo '
		<link rel="stylesheet" type="text/css" href="css/engine.css">
		<link href="https://fonts.googleapis.com/css?family=Quicksand|Kanit" rel="stylesheet">
	';
}
function setPlugin($plugin){
	$input = explode(",",$plugin);
	if(in_array('jquery',$input)){
		echo '
			<script src="js/jquery.min.js"></script>
		';
	}	
	if(in_array('jquery-form',$input)){
		echo '
			<script src="js/jquery.form.js"></script>
		';
	}	
	if(in_array('jquery-ui',$input)){
		echo '
			<script type="text/javascript" src="js/jquery-ui.js" ></script>
			<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui.css">
		';
	}
	if(in_array('alert',$input)){
		echo '
			<script type="text/javascript" src="js/bootbox.js" ></script>
			<script type="text/javascript" src="js/jquery.toastmessage.js" ></script>
			<link rel="stylesheet" type="text/css" href="css/jquery.toastmessage.css">
		';
	}
	if(in_array('loaderbar',$input)){
		echo '
			<script src="js/nprogress.js"></script>
			<link href="css/nprogress.css" rel="stylesheet" />
		';
	}
	if(in_array('IDU',$input)){
		echo '
			<script src="indyEngine/IDUControl.js"></script>
		';
	}
	if(in_array('ckeditor',$input)){
		echo '
			<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
		';
	}
	if(in_array('hrefDefault',$input)){
		echo '
			<script>
				$(function(){
					$("a[href=#]").click(function(event){
						event.preventDefault();
					});						
				});	
			</script>
		';
	}
	if(in_array('loaderbar',$input)){
		echo '
			<script>
				NProgress.start();
				setTimeout(function() { NProgress.done(); $(".fade").removeClass("out"); }, 1000);
			</script>
		';
	}
	if(in_array('syntaxhighlighter',$input)){
		echo '
			<script type="text/javascript" src="syntaxhighlighter/scripts/shCore.js"></script>
			<script type="text/javascript" src="syntaxhighlighter/scripts/shBrushJScript.js"></script>
			<link type="text/css" rel="stylesheet" href="syntaxhighlighter/styles/shCoreDefault.css"/>
			<script type="text/javascript">SyntaxHighlighter.all();</script>
		';
	}
}
function setPermission($include){
	$input = explode(",",$include);
	if(in_array('login',$input)){
		if($_COOKIE["user"]==''){
			echo '<script>
					window.location="login.php";
				</script>';
		}
	}
	if(in_array('admin',$input)){
		
	}
	
}
echo '<div id="show_error"></div>';
		
?>