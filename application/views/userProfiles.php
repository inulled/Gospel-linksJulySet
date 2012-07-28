<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('vh.php'); ?>
<head>
	<link href='http://fonts.googleapis.com/css?family=Merienda+One' rel='stylesheet' type='text/css'>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Load getUrlParam Script-->
	<script src="<?=base_url().$ldr?>scripts/lib/jquery.getUrlParam.js"></script>
<title>wall</title>
	<style type="text/css">
.style2 {
	border: 2px solid #AFAA7A;
}
.style3 {
	background-color: #B3AE80;
	padding: 3px;
	font-size: 10pt;
	color: white;
	font-family: Tahoma;
}
.sideBarRegStyle1_OrigTheme {
	background-color: #F0F0F0;
}
.sideBarHeaderStyle1_OrigTheme {
	background-color: #C6CDE0;
}
.sideBarBorderBottom1_OrigTheme {
	border-width: 2px;
	border-color: #D3D9E7;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-bottom-right-radius: 3pt;
	border-bottom-left-radius: 3pt;
}
.sideBarBorderReg1_OrigTheme {
	border-width: 2px;
	border-color: #D3D9E7;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: none;
	border-left-style: solid;
}
.sideBarBorderTopStyle1_OrigTheme {
	border-width: 2px;
	border-color: #D3D9E7;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: none;
	border-left-style: solid;
	border-top-right-radius: 3px;
	border-top-left-radius: 3px;
}
</style>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	
	$("#addFriend").click(function() {
		addFriend();
	});
	
	$("#acceptRequest").live('click', function() {
		acceptFriend();
	});
	
	$('#cancelFriendship').live('click', function() {
		cancelFriendship();
	});
	
	var targetedUserId = $(document).getUrlParam("id");
	
    function addFriend() {
		jQuery.ajax({
			type: "POST",
			dataType: "JSON",
			url: "<?=base_url()?>index.php/regUserDash/addFriend",
			data: { targetedUserId: targetedUserId },
			json: {addFriendSuccess: true},
			success: function(data) {
			if(data.addFriendSuccess == true) {
				$("#addFriend").replaceWith('<input name="currentlyFriends" id="currentlyFriends" type="submit" value="Request Sent" style="width: 120px; height: 28px" class="button1" />');
			}
		  }
	   });
	} function acceptFriend() {
		jQuery.ajax({
			type: "POST",
			dataType: "JSON",
			url: "<?=base_url()?>index.php/regUserDash/acceptFriend",
			data: { targetedUserId: targetedUserId },
			json: {acceptFriendSuccess: true},
			success: function(data) {
			if(data.acceptFriendSuccess == true) {
				$("#requestAlert").replaceWith('<span class="font1">You two are now friends</span>');
			}
		  }
	   });
	} function cancelFriendship() {
		jQuery.ajax({
			type: "POST",
			dataType: "JSON",
			url: "<?=base_url()?>index.php/regUserDash/cancelFriendship",
			data: { targetedUserId: targetedUserId },
			json: {friendshipCaneled: true},
			success: function(data) {
			if(data.friendshipCanceled == true) {
				$("#requestAlert").replaceWith('<span class="font1">You two are no longer friends </span>');
			}
		  }
	   });
	}
});
</script>
<body>

<?php // the following php is the bootstrapper for the friendship connector
	$selectedId = $_REQUEST['id'];
    $myuserid = $this->session->userdata('userid');
	
	$friendshipQuery = $this->db->query("SELECT * FROM friends");
	foreach($friendshipQuery->result() as $row) {
		if ($myuserid == $selectedId) { ?>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#addFriend").hide();
				});
			</script>
<?php } }   $query1 = $this->db->query("SELECT * FROM friends WHERE relationType = 'requested' AND node2id = '${myuserid}' AND node1id = '$selectedId'");
	  foreach($query1->result() as $row) { ?>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#addFriend").replaceWith('<span id="requestAlert"><span class="font1">wants to be your friend&nbsp;</span><input type="submit" id="acceptRequest" value="Accept" style="width: 60px; height: 28px" class="button1" />&nbsp;<input type="submit" id="denyRequest" value="Deny" style="width: 50px; height: 28px" class="button1" /></span>');
				});
			</script>
<?php } $query2 = $this->db->query("SELECT * FROM friends WHERE node1id = '{$myuserid}' AND node2id = '${selectedId}'");
		foreach($query2->result() as $row) {
		if ($row->relationType == 'friends') { ?>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#addFriend").replaceWith('<span id="cancelFriendship" class="font1 area1">Ya\'ll are currently friends. <a class="link-font1" href="javascript:void(0)"><strong>Cancel friendship?</strong></a>');
				});
			</script>
<?php } }?>
<table cellpadding="0" cellspacing="0" style="width: 522px; height: 186px">
	<tr>
		<td valign="top" style="height: 148px; width: 137px;">
<?php   $query  = $this->db->query("SELECT * FROM users WHERE userid = '{$selectedId}'");
        $row = $query->row();
        foreach ($query->result() as $row) { ?>
		<table cellspacing="0" style="width: 57px">
	<tr>
		<td style="width: 90px">
		<img id="defaultImg" src="<?php echo base_url().$row->defaultImgURI; ?>" width="130" height="142" /><br />
&nbsp;<br />
	</tr>
	<tr>
		<td class="sideBarRegStyle1_OrigTheme sideBarBorderTopStyle1_OrigTheme headerFont1" style="padding: 3px; height: 22px; width: 90px">
		<strong>View My Images</strong></td>
	</tr>
	<tr>
		<td class="sideBarRegStyle1_OrigTheme sideBarBorderReg1_OrigTheme headerFont1" style="padding: 3px; width: 89px">
		<strong>View My Info</strong></td>
	</tr>
	<tr>
		<td class="sideBarHeaderStyle1_OrigTheme style3" style="width: 89px">Friends&nbsp;</td>
	</tr>
	<tr>
		<td class="sideBarBorderReg1_OrigTheme" style="width: 89px"><br />
		<br />
		<br />
		<br />
		<br />
		<br />
		</td>
	</tr>
	<tr>
		<td class="sideBarHeaderStyle1_OrigTheme style3" style="width: 89px">Mutual Friends&nbsp;</td>
	</tr>
	<tr>
		<td class="sideBarBorderBottom1_OrigTheme" style="width: 89px"><br />
		<br />
		<br />
		<br />
		<br />
		<br />
		</td>
	</tr>
</table>
</td>
		<td class="nameDisplay_userProfiles" valign="top">
			<?=$row->firstname." ".$row->lastname?>
			<input name="addFriend" id="addFriend" type="submit" value="Add Friend" style="width: 95px; height: 28px" class="button1" />
		</td>
		</tr>
	</table>
<?php } ?>
</body>

</html>