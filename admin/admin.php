<?PHP 

add_action( 'admin_menu', 'scenechat_admin_menu', 9 ); // 1.5+

function scenechat_admin_menu() {
  if ( !current_user_can(SCENECHAT_ADMIN_CAPABILITY) ) {return ;}
/*
	add_menu_page( __( 'Custom Plugin', 'scenechat' ), __( 'Custom', 'scenechat' ),
		SCENECHAT_ADMIN_CAPABILITY, 'scenechat', 'scenechat_admin_management_page' );
*/

	$pagename = add_submenu_page( 'scenechat', __( 'Edit SceneChat Plugin', 'scenechat' ), __( 'Edit', 'scenechat' ),
		SCENECHAT_ADMIN_CAPABILITY, 'scenechat', 'scenechat_admin_management_page' );
        add_action( "admin_head-$pagename", 'scenechat_admin_header'); 
}


// $screen = get_current_screen();
// $prefix = $screen->is_network ? 'network_admin_' : '';
$prefix = ""; // not valid for network-installs; support maybe later
add_filter( $prefix.'plugin_action_links_'.SCENECHAT_PLUGIN_BASENAME, 'scenechat_plugin_action_links', 10, 2 );

function scenechat_plugin_action_links( $links, $file ) {
	if ( $file != SCENECHAT_PLUGIN_BASENAME )
		return $links;

	if ( !current_user_can(SCENECHAT_ADMIN_CAPABILITY) ) {return $links;}
	$url = scenechat_admin_url( array( 'page' => 'scenechat' ) );

	$settings_link = '<a href="' . esc_attr( $url ) . '">'
		. esc_html( __( 'Setup', 'scenechat' ) ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}


function scenechat_admin_header() {
if ( !current_user_can(SCENECHAT_ADMIN_CAPABILITY) ) {return ;}
?>
<script type="text/javascript">

function SC_clearField(clearvalue,msg) {
  if(document.getElementById(clearvalue).value==msg) { 
    document.getElementById(clearvalue).value =""; 
    document.getElementById(clearvalue).color = "#000";
  } 
}

function SC_sameValue(same) {
  if(document.getElementById(same).value=="") { 
    document.getElementById(same).value = "Your SceneChat Account ID"; 
  } 
}

function SC_validate_form() {
  var valid = true;
  var val =  document.scaccount.SC_account_id.value;
  if (val=="") {
    mesg="* Please enter your account ID in the text field."; 
    valid=false; 
  }
  if (!val.match(/[a-zA-Z0-9]{8,}/)) {
    mesg="* Error: account ID should be alphanumeric and at least 8 characters."; 
    valid=false;
  }
  if (!valid) {
  document.getElementById("SC_message").innerHTML=mesg; 
  }
  return valid;
}

</script>
<link rel="stylesheet" type="text/css" href="<?php echo SCENECHAT_PLUGIN_URL.'/admin/style1.css' ?>" />
<?php
} // end of function scenechat_admin_header

function scenechat_admin_management_page() {

if ( !current_user_can(SCENECHAT_ADMIN_CAPABILITY) ) {return ;}

if(isset($_POST['Submit'])) {
	$uValue=$_POST['SC_account_id'];
	$query="select * from wp_scenechat";
	$result1=mysql_query($query) or die(mysql_error());
	if(mysql_affected_rows()==0)
	{
		$query="INSERT INTO wp_scenechat (account_id)
			VALUES (0) ";
		$result=mysql_query($query) or die(mysql_error());
	}
	$query="UPDATE wp_scenechat set account_id='".$uValue."'";
	$result=mysql_query($query) or die(mysql_error());
}

$query="select * from wp_scenechat";
$result = mysql_query($query) or die(mysql_error());
$uValue = "";
if (mysql_num_rows()==0) {
  $row = mysql_fetch_array($result);
  $uValue = $row[0];
}
?>


<div class='admin_scenechat'>
<h2>SceneChat Setup for Wordpress</h2>
<form method='POST' name='scaccount'  enctype='multipart/form-data' onsubmit='return SC_validate_form();'>
<?PHP if ($uValue) { ?>
<p><strong>All compatible videos on this site should now be enabled with SceneChat's dropdown.  </strong></p>
<p>If you want to disable SceneChat on individual videos, add the class 'noscenechat' to the video's iframe.  You can get more information about your account by logging in at <a href='http://scenechat.com/analytics/'>http://scenechat.com/analytics/</a></p>
<?PHP } else { ?>
<p>If you don't already have a SceneChat account, you'll need to <a href="http://scenechat.com">sign up here</a>.</p>
<?PHP } ?>

<div id='SC_message' style="font-weight: bold;"></div>

<div class='admin_scenechat_left'>
Your SceneChat Account ID:
<p>(Available in your signup confirmation email from SceneChat.)</p>
</div><!-- admin_scenechat_left -->
<div class='admin_scenechat_right'>

<input type="textbox" size="50" name="SC_account_id" id="SC_account_id" value="<?PHP print $uValue ?>">

<div class='admin_scenechat_submit'>
<input type="submit" value="Submit" name="Submit">
</div><!-- admin_scenechat_submit -->

</div><!-- admin_scenechat_right -->

</form>
</div><!-- admin_scenechat -->
<?php
} // end of function scenechat_admin_management_page


