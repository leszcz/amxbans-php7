<?php

/* 	
	commentariy inogda bol'we, chem fail, zaebalo -_-'
	a.aqua
*/

require_once("config.inc.php");
$id = $_GET["id"];
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="../templates/_css/amxxhelper.css" />

	<script type="text/javascript"> 
	<!--
	function GetAccess(id) {
		var access = opener.document.getElementById(id).value;
		SetBox( "chka" , access , "a" );
		SetBox( "chkb" , access , "b" );
		SetBox( "chkc" , access , "c" );
		SetBox( "chkd" , access , "d" );
		SetBox( "chke" , access , "e" );
		SetBox( "chkf" , access , "f" );
		SetBox( "chkg" , access , "g" );
		SetBox( "chkh" , access , "h" );
		SetBox( "chki" , access , "i" );
		SetBox( "chkj" , access , "j" );
		SetBox( "chkk" , access , "k" );
		SetBox( "chkl" , access , "l" );
		SetBox( "chkm" , access , "m" );
		SetBox( "chkn" , access , "n" );
		SetBox( "chko" , access , "o" );
		SetBox( "chkp" , access , "p" );
		SetBox( "chkq" , access , "q" );
		SetBox( "chkr" , access , "r" );
		SetBox( "chks" , access , "s" );
		SetBox( "chkt" , access , "t" );
		SetBox( "chku" , access , "u" );
		SetBox( "chkz" , access , "z" );
	}
	function SetBox( id , access , flag ) {
		var pos = access.indexOf(flag);
		if ( pos >= 0) {
			document.getElementById(id).checked=true;
		} else {
			document.getElementById(id).checked=false;
		}
		return true;
	}
	function SaveAccess( id ) {
		var access = "";
		access += GetBox( "chka" , "a");
		access += GetBox( "chkb" , "b");
		access += GetBox( "chkc" , "c");
		access += GetBox( "chkd" , "d");
		access += GetBox( "chke" , "e");
		access += GetBox( "chkf" , "f");
		access += GetBox( "chkg" , "g");
		access += GetBox( "chkh" , "h");
		access += GetBox( "chki" , "i");
		access += GetBox( "chkj" , "j");
		access += GetBox( "chkk" , "k");
		access += GetBox( "chkl" , "l");
		access += GetBox( "chkm" , "m");
		access += GetBox( "chkn" , "n");
		access += GetBox( "chko" , "o");
		access += GetBox( "chkp" , "p");
		access += GetBox( "chkq" , "q");
		access += GetBox( "chkr" , "r");
		access += GetBox( "chks" , "s");
		access += GetBox( "chkt" , "t");
		access += GetBox( "chku" , "u");
		access += GetBox( "chkz" , "z");
		
		opener.document.getElementById(id).value = access;
	}
	function GetBox( id , flag ) {
		if ( document.getElementById(id).checked == true ) {
			return flag;
		} else {
			return "";
		}
	}
	function setChecked ( obj ) {
		var check = document.getElementsByName ( "access" );
		for ( var i=0; i<check.length; i++ ) {
			check[ i ].checked = obj.checked;
		}
	}
	-->
	</script>
</head>
<body id="top">
	<div id="site">
		<div class="center-wrapper" style="width:500px;">
			<div class="main">
				<div class="spacer">&nbsp;</div>
				<div class="post">
					<form name="amxhfrm" method="post" action=""> 
						<input type="checkbox" name="set" onclick="setChecked(this)" /><br />
							<input type="checkbox" name="access" id="chka"/> 
							a - Immunity (cant be kicked / banned etc.)<br />
							<input type="checkbox" name="access" id="chkb"/> 
							b - Reserved Slots (can use reserved Slots)<br />
							<input type="checkbox" name="access" id="chkc"/> 
							c - amx_kick Command <br />
							<input type="checkbox" name="access" id="chkd"/> 
							d - amx_ban and amx_unban Command <br />
							<input type="checkbox" name="access" id="chke"/> 
							e - amx_slay and amx_slap Command <br />
							<input type="checkbox" name="access" id="chkf"/> 
							f - amx_map Command <br />
							<input type="checkbox" name="access" id="chkg"/> 
							g - amx_cvar Command (not all CVARS available) <br />
							<input type="checkbox" name="access" id="chkh"/> 
							h - amx_cfg Command <br />
							<input type="checkbox" name="access" id="chki"/> 
							i - amx_chat and other Chat-Commands <br />
							<input type="checkbox" name="access" id="chkj"/> 
							j - amx_vote and other Vote-Commands <br />
							<input type="checkbox" name="access" id="chkk"/> 
							k - Access to sv_password cvar (through amx_cvar Command) <br />
							<input type="checkbox" name="access" id="chkl"/> 
							l - Access to amx_rcon command and rcon_password cvar (through amx_cvar Command) <br />
							<input type="checkbox" name="access" id="chkm"/> 
							m - Userdefined Level A (for other Plugins) <br />
							<input type="checkbox" name="access" id="chkn"/> 
							n - Userdefined Level B <br />
							<input type="checkbox" name="access" id="chko"/> 
							o - Userdefined Level C <br />
							<input type="checkbox" name="access" id="chkp"/> 
							p - Userdefined Level D <br />
							<input type="checkbox" name="access" id="chkq"/> 
							q - Userdefined Level E <br />
							<input type="checkbox" name="access" id="chkr"/> 
							r - Userdefined Level F <br />
							<input type="checkbox" name="access" id="chks"/> 
							s - Userdefined Level G <br />
							<input type="checkbox" name="access" id="chkt"/> 
							t - Userdefined Level H<br /> 
							<input type="checkbox" name="access" id="chku"/> 
							u - Menu-Access <br />
							<input type="checkbox" name="access" id="chkz"/> 
							z - User (no Admin)<br />
							<img src="../images/accept.png" style="cursor:pointer;" title="Accept" onclick="SaveAccess('<?php echo $id ?>');"/>
							<img src="../images/delete.png" style="cursor:pointer;" title="Delete" onclick="opener.document.getElementById('<?php echo $id ?>').value = '';self.close();"/>
							<img src="../images/cancel.png" style="cursor:pointer;" title="Cancel" onclick="self.close();"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>