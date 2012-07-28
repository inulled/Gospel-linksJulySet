<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>

	<!-- Load View Data-->
	<?php include('vh.php'); ?>
	<!-- Load Elastic Script-->
	<script src="<?=base_url().$ldr?>scripts/lib/jquery.elastic.source.js"></script>
	<!-- Load Timeago Script-->
	<script src="<?=base_url().$ldr?>scripts/lib/jquery.timeago.js"></script>
	<!-- Load getUrlParam Script-->
	<script src="<?=base_url().$ldr?>scripts/lib/jquery.getUrlParam.js"></script>
    <!-- Add CSS library -->
    <link rel="stylesheet" type="text/css" href="<?=base_url().$ldr?>reg-userdash.php" />
    
    <link rel="stylesheet" type="text/css" href="<?=base_url().$ldr?>dashSidebar.php" />
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gospel-links</title>

<script type="text/javascript" language="javascript">
$(document).ready(function() {
	setInterval(function() {
		jQuery.getJSON("<?=base_url()?>index.php/regUserDash/sessionExpire", function(data) {
			var sessionState = jQuery.parseJSON('{"sessionExpired":"true","sessionExpired":"false"}');
			if(sessionState.sessionExpired === "true") { // if session is expired run the following code
				var dataString = 'true';
				jQuery.ajax({	// send the expired signal to Ci so that it knows the session has expired
					type: 'POST',
					dataType: 'JSON',
					url: '<?=base_url()?>index.php/regUserDash/extendSession',
					data: {'dataString': true},
					success: function(data) {
						if (data.extendedSession == true) {
							alert('success');
						} else {
							return false;
						}
					}
				});
			} else if(sessionState.sessionExpired == "false") {
				return;
			}
		});
    }, 120000); // loop through every 2 minutes
    
    if ($(document).getUrlParam("requestedPageType") === 'wall_1') {
    	// loads the wall when the user firsts logs in
		jQuery('#main').load('<?=base_url()?>index.php/routers/wall_1');
    } else if ($(document).getUrlParam("requestedPageType") === 'userProfiles') {
    	// loads the wall when the user firsts logs in
    	var ide = $(document).getUrlParam("id");
		jQuery('#main').load('<?=base_url()?>index.php/routers/userProfiles',{id:ide});
    }
    
  
	// when the home link is clicked, fadeIn in the wall
	jQuery("#home").click(function() {
		window.location.href = '<?=base_url()?>index.php/routers/regUserDash?requestedPageType=wall_1';
	});
	jQuery("#logout").click(function() {
		window.location = '<?=base_url()?>index.php/home/kill_sess';
	});
	jQuery("#password").keyup(function(event) {
    	var keyCode = event.keyCode || event.which;
    	if(keyCode === 13) {
        	logsig();
    	}
	});
	jQuery("#reset").click(function() {
		jQuery("#username, #password").val('');
		jQuery("#username").focus();
		jQuery("#login_failure").fadeOut(400);
	});
	jQuery("#login").click(function() {
		logsig();
	});
	function logsig() {
		var username = jQuery("#username").val();
		var password = jQuery("#password").val();
		var dataString = '&username=' + username + '&password=' + password;
		if(username=='' ||  password=='') {
			jQuery('#success').fadeOut(400).hide();
			jQuery('#error').fadeOut(400).show();
		} else {
			jQuery.ajax({
			type: "POST",
			dataType: "JSON",
			url: "<?=base_url()?>index.php/home/logsig",
			data: dataString,
			json: {session_state: true},
			success: function(data) {
			if(data.session_state == true) {
				window.location = "<?=base_url()?>";
			} else if(data.session_state == false) {
				jQuery("#login_failure").fadeIn(400);
		   	}
		  }
	   });
	}
	}
});
</script>
	<style type="text/css">
.navbarStyle {
	border-style: none none solid none;
	border-width: 5px;
	border-color: #8E442B;
	position: fixed;
	z-index: auto;
	width: 100%;
	top: 0px;
	right: 0px;
	left: 0px;
	background-color: #B65B3B;
}
.textbox1_OrigTheme {
	padding: 3px;
	border-radius: 3pt;
	font-family: Verdana;
	font-size: 8pt;
	outline: none;
	border-style: none;
	background-image: url('<?=base_url()?><?=$ldr?>images/searchBox.fw.png');
}
.navBarStyle1 {
	font-family: georgia;
	font-size: 10pt;
	color: #FFFFFF;
	padding: 3pt;
	text-align: center;
}

</style>
</head>
<?php	$userid = $this->session->userdata('userid');
        $query1 = $this->db->query("SELECT firstname, lastname FROM users WHERE userid = '{$userid}'");
        $row = $query1->row();
        foreach ($query1->result() as $row1) {
?>
<body style="background-color: #FFFFFF">

<table style="width: 100%" align="center" class="navbarStyle">
	<tr>
		<td>
		<table style="width: 754px" cellspacing="0" cellpadding="0" align="center" class="navBarStyle1">
			<tr>
				<td id="home" style="border-left: 1px solid #D6957E; border-right: 1px none #D6957E; border-top: 1px none #D6957E; border-bottom: 1px none #D6957E; width: 100px" class="style1 area1">Home</td>
				<td style="border-left: 1px solid #D6957E; border-right: 1px none #D6957E; border-top: 1px none #D6957E; border-bottom: 1px none #D6957E; width: 100px" class="area1">Profiles</td>
				<td style="border-style: none none none solid; border-width: 1px; border-color: #D6957E; width: 100px" class="area1">Mail</td>
				<td id="logout" style="	border-style: none none none solid; border-width: 1px; border-color: #D6957E; width: 100px" class="area1">Logout</td>
				<td>
				<table style="width: 100%" cellspacing="0" cellpadding="0">
					<tr>
						<td style="width: 100pt">&nbsp;</td>
						<td>
			<input placeholder="Begin Searching" name="Text2" spellcheck="false" autocomplete="off" class="textbox1_OrigTheme" type="text" style="width: 191px; height: 17px" /></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<p>&nbsp;</p>

<table id="main2" cellpadding="0" cellspacing="0" style="width: 773px; height: 617px" align="center">
	<!-- MSTableType="layout" -->
	<tr>
		<td valign="top" style="height: 32px">
		<table style="width: 99%" align="center">
			<tr>
				<td style="width: 515px">
		<img id="logo" alt="" src="<?=base_url()?><?=$ldr?>images/logo.png" /></td>
			</tr>
		</table>
		</td>
		</tr>
	<tr>
		<td valign="top" class="mainAreaStyle1" style="height: 585px; width: 773px">
		<table cellpadding="0" cellspacing="0" style="width: 773px; height: 396px">
			<tr>
				<td valign="top" style="width: 522px">
				<div class="leftAlign1">
					<table cellpadding="3" style="width: 100%">
						<tr>
							<td id="main">
							</td>
						</tr>
					</table>
				&nbsp;</div>
				
				</td>
<?php } $userid = $this->session->userdata('userid');
        $query = $this->db->query("SELECT * FROM churchMembers WHERE cMuserId = '{$userid}'");
        $row = $query->row();
		if ($query->num_rows() != 0	) {
		$membersChurchId = $row->cMchurchId;
        $query = $this->db->query("SELECT * FROM church_repo WHERE churchId = '{$membersChurchId}'");
        foreach ($query->result() as $row) {
?>
				<td valign="top" style="height: 396px; width: 245px; padding: 3px;">
				<table cellpadding="0" cellspacing="0" style="width: 242px; height: 547px">

					<tr>
						<td valign="top" style="height: 19px">
						<span class="headerFont1"><strong><?php echo $row->church_name ?></strong></span> <span class="black-font1">
						(Your a Member of this Church)</span></td>
					</tr>
					<tr>
						<td valign="top" style="height: 19px">
					<!--	<div class="headerFont1"><strong>Blah Blah </strong></div> <div class="black-font1">(Your Following this Church)</div></td> -->
					</tr>
					<tr>
						<td valign="top" style="height: 251px">
						</td>
					</tr>
					<tr>
		<!--				<td valign="top" colspan="3" style="height: 196px">
							<a class="font1">
								<?php $verse = $this->db->query("SELECT * FROM bible_verses ORDER BY RAND() LIMIT 5");
									  foreach ($verse->result() as $row) {
						  			  echo "<i>".$row->verseLocation.nl2br(": </i>").$row->verseEntry.nl2br("\n \n");
									  }
								?>
							</a>
						</td>
						-->
								<?php } } else { } ?>
					</tr>
				</table></td>
			</tr>
		</table>
		</td>
		</tr>
</table>

</body>
</html>