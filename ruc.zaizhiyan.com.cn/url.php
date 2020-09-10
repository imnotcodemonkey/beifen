<?php
$t=time() + (3600*24*2);
if(isset($_GET["seo"])){setcookie("seo", $_GET['seo'],$t); }
if(isset($_COOKIE["seo"])){echo '
document.writeln("<!--'.$_COOKIE["seo"].'-->");';}

?>