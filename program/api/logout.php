<?
ob_start();

setcookie("user_id",'',time()-3600,'/');					
echo '<script>  window.location="../index.php"; </script>';
?>