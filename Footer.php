<? if(isset($_SESSION['user'])) { ?>
<script>
	<? if(isset($_SESSION['fb_user'])) { ?>
	document.getElementById('avatar_demo').style.backgroundImage = "url('<?=$_SESSION['fb_user']->picture->data->url?>')";
	<? } ?>
	<? if(isset($_SESSION['tz_user'])) { ?>
	document.getElementById('avatar_demo').style.backgroundImage = "url('images/tizen_large.png')";
	document.getElementById('avatar_demo').style.backgroundSize = "50px";
	<? } ?>
	<? if(isset($_SESSION['droid_user'])) { ?>
	document.getElementById('avatar_demo').style.backgroundImage = "url('images/tizen_large.png')";
	document.getElementById('avatar_demo').style.backgroundSize = "50px";
	<? } ?>
	document.getElementById('name_demo').innerHTML = "<?=$_SESSION['user']['name']?>";
	document.getElementById('coins_demo').innerHTML = "<?=$_SESSION['user']['gold']?>";
	document.getElementById('rank_demo').innerHTML = "<?=$_SESSION['user']['rank']?>";
</script>
<?
	}
	
	session_write_close();
?>