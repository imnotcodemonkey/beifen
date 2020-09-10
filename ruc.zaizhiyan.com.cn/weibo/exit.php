<?php
session_start();

include_once( 'inc/config.php' );
include_once( 'inc/saetv2.ex.class.php' );
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );

$c ->end_session();

session_destroy();
setcookie( 'weibojs_'.$c->client_id,'' );

//	$_SESSION['token'] = $token;
//	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );

//@session_unregister($this->keepUserIDTag);

header("location:index.php");
?>