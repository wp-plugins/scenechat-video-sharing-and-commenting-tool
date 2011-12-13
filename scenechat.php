<?php
/*
Plugin Name: SceneChat Video Annotator
Description: This plugin enables SceneChat on all compatible on-site videos (currently YouTube; Vimeo coming soon)
Author: SceneChat
Version: 1.1.4
Author URI: http://scenechat.com/
*/

ob_start();
if ( ! defined( 'SCENECHAT_PLUGIN_BASENAME' ) )
	define( 'SCENECHAT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'SCENECHAT_PLUGIN_NAME' ) )
	define( 'SCENECHAT_PLUGIN_NAME', trim( dirname( SCENECHAT_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'SCENECHAT_PLUGIN_DIR' ) )
	define( 'SCENECHAT_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . SCENECHAT_PLUGIN_NAME );

if ( ! defined( 'SCENECHAT_PLUGIN_URL' ) )
	define( 'SCENECHAT_PLUGIN_URL', WP_PLUGIN_URL . '/' . SCENECHAT_PLUGIN_NAME );


if ( ! defined( 'SCENECHAT_ADMIN_CAPABILITY' ) )
	define( 'SCENECHAT_ADMIN_CAPABILITY', 'manage_options' );


if ( is_admin() ) {
	require_once SCENECHAT_PLUGIN_DIR . '/admin/admin.php';
}

function scenechat_admin_url( $query = array() ) {
	global $plugin_page;

	if ( ! isset( $query['page'] ) )
		$query['page'] = $plugin_page;

	$path = 'admin.php';

	if ( $query = build_query( $query ) )
		$path .= '?' . $query;

	$url = admin_url( $path );

	return esc_url_raw( $url );
}

function scenechat_plugin_path( $path = '' ) {
	return path_join( SCENECHAT_PLUGIN_DIR, trim( $path, '/' ) );
}

function scenechat_plugin_url( $path = '' ) {
	return plugins_url( $path, SCENECHAT_PLUGIN_BASENAME );
}

$query="CREATE TABLE IF NOT EXISTS wp_scenechat (account_id VARCHAR(255))";
$result=mysql_query($query) or die(mysql_error());
$query1="select * from wp_scenechat";
$result1=mysql_query($query1) or die(mysql_error());
if(mysql_affected_rows()>0) {
add_action('wp_head', 'scenechat_header');
function scenechat_header()
{
$query2="select * from wp_scenechat";
$result2=mysql_query($query2) or die(mysql_error());
while($row=mysql_fetch_array($result2))
	     {
	     $uValue=$row[0];
	     }
?>
<!-- SceneChat BEGIN --> 
<script type="text/javascript"> 
setTimeout(function(){var ns=document.createElement("script");
 ns.type="text/javascript";
 ns.src="http://scenechat.com/embed/init.js?u=<?php echo $uValue ?>";
 var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ns, s);},1);
</script> 
<!-- SceneChat END --> 
<?php
}
}

?>
