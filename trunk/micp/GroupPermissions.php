<?php
if (!defined('MEDIAWIKI')) die();
require_once("$IP/extensions/GroupPermissions/settings.php");
//extDebug is used to debug the application, will not affect the running of the
//extension in any way, will just use echo when it enters and leaves a function for example
//in some cases it will also echo values used by the aextension but arn't displayed to the user
//default is false, true will enable the debug function, useful to find where things are missed out
//or where errors occur that shouldn't
$extgDebug = false;
$wgUseAjax = true;
$wgExtensionFunctions[] = "wfGroupPermissionsExtension";
$wgAjaxExportList[] = 'isvalidgroup';
$wgAjaxExportList[] = 'isgroupsubmissionvalid';
$wgAjaxExportList[] = 'loadpage';
$wgAjaxExportList[] = 'loadmainp';
$wgAjaxExportList[] = 'loadgrouppage';
$wgAjaxExportList[] = 'creategroupP';
$wgAjaxExportList[] = 'editgroupP';
$wgAjaxExportList[] = 'permissiondescription';
$wgMICPextensions[] = "micp:grouppermissions";
$wgAjaxExportList[] = 'addperm';
$wgAjaxExportList[] = 'savegroup';
$wgAjaxExportList[] = 'errorbox';
$wgAjaxExportList[] = 'notifybox';
 $wgExtensionCredits['specialpage'][] = array(
   'name'    => 'MICP : Group Permissions Editor',
   'version' => '1.2',
   'author'  => 'Oliver Baker',
   'description' => 'Allows you to change The permissions of wiki groups internally (like disable editing for Anons) without editing the PHP files',
   'url'     => 'http://mediawiki.org/wiki/Extension:Grouppermissions'
 );
 
 function settingsfileq(){
	global $wgmicpgpsetfile;
	$html = null;
	if(@file_exists("extensions/GroupPermissions/settings.php")){
			$set = @$wgmicpgpsetfile;
			if($set !== "yes"){
				$html = $html.errorbox('<strong>WARNING: </strong>Please add require_once("$IP/extensions/GroupPermissions/settings.php"); to Localsettings.php for changes to be applied to this wiki');
			}
	}
	return $html;
}
 
 function errorbox($message){
		global $wgScriptPath;
		$numb = rand();
		$html = null;
		$html = $html.'<!--errmsg'.$numb.'--><table width="100%" id="errmsg'.$numb.'" cellpadding="0"><tr><td style="text-align:center">';
		$html = $html.'<table id="errmsg'.$numb.'" align="center" cellpadding="0"><tr><td style="text-align:center">';
		$html = $html.'<div style="min-width:10%;align:center;background:pink;border:1px solid darkred;"><div style="color:darkred;text-align:center"> '.$message.'   <!--<img title="Close Message" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/deleteT.png" height="14" width="14" onclick="fadeout(\'errmsg'.$numb.'\').style.display = \'none\'"/>--></div></div>';
		$html = $html.'</td></tr></table>';
		$html = $html.'</td></tr></table>';
		Return $html;
}
 
  function notifybox($message){
		global $wgScriptPath;
		$numb = rand();
		$html = null;
		$html = $html.'<!--errmsg'.$numb.'--><table width="100%" id="errmsg'.$numb.'" cellpadding="0"><tr><td style="text-align:center">';
		$html = $html.'<table id="errmsg'.$numb.'" align="center" cellpadding="0"><tr><td style="text-align:center">';
		$html = $html.'<div style="min-width:10%;align:center;background:yellow;border:1px solid orange;"><div style="color:black;text-align:center"> '.$message.'   <!--<img title="Close Message" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/deleteT.png" height="14" width="14" onclick="fadeout(\'errmsg'.$numb.'\').style.display = \'none\'"/>--></div></div>';
		$html = $html.'</td></tr></table>';
		$html = $html.'</td></tr></table>';
		Return $html;
}

 function populatepermissions($param1){
	global $wgGroupPermissions;
	$html = '<table name="items" style="width:100%;"><tbody id="items">';
	$lst = $wgGroupPermissions[$param1];
	foreach($wgGroupPermissions[$param1] as $item => $value){
		if ($lst[$item] == 'true') {
			$html = $html.addpermchecked($item);
		}else{
			$html = $html.addperm($item);
		}
	}
	$html = $html.'</tbody></table>';
	return $html;
}
 
 function savegroup($param1){
	$switch = false;
	$name = NULL;
	$permissions;
	$group = substr($param1,0,strpos($param1,','));
	$param1 = substr($param1,strpos($param1,',')+1);
	$html = 'Group Name: '.$group.'<br/>';
	do{
		if($switch == false){
			$switch = true;
			$html = $html.'Name: '.substr($param1,0,strpos($param1,','));
			$name = substr($param1,0,strpos($param1,','));
			$param1 = substr($param1,strpos($param1,',')+1);
		}else{
			$switch = false;
			$html = $html.' Checked: '.substr($param1,0,strpos($param1,',')).'<br/>';
			$permissions[$name] = substr($param1,0,strpos($param1,','));
			$param1 = substr($param1,strpos($param1,',')+1);
		}
	}while(strpos($param1,',') != false);
	writePermChanges($group,$permissions);
	//$html = $html.' Checked: '.substr($param1,0).'<br/>';
	return $html;
}
 
 function addperm($param1){
	global $wgScriptPath;
	$tableEntry = '<tr id="'.$param1.'" onmousedown="document.getElementById(\''.$param1.'\').style.background = \'grey\';" onmouseup="document.getElementById(\''.$param1.'\').style.background = \'lightgrey\'" onmouseover="document.getElementById(\''.$param1.'\').style.background = \'lightgrey\'" onmouseout="document.getElementById(\''.$param1.'\').style.background = \'white\'">
	<td>'.$param1.'</td><td><!--<input id="CB'.$param1.'" type="checkbox"/>-->'.CheckBox('CHK'.$param1,false).'</td>
	<td style="width:18px;text-align:center;vertical-align:middle"><img title="Remove Permission" id="PiC'.$param1.'" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/deleteT.png" height="16" width="16" onclick="removeitem(\''.$param1.'\');" onmouseover="document.getElementById(\'PiC'.$param1.'\').setAttribute(\'width\',\'18\');document.getElementById(\'PiC'.$param1.'\').setAttribute(\'height\',\'18\');" onmouseout="document.getElementById(\'PiC'.$param1.'\').setAttribute(\'width\',\'16\'); document.getElementById(\'PiC'.$param1.'\').setAttribute(\'height\',\'16\');" /></td>
	<td style="width:18px;text-align:center;vertical-align:middle"><img title="Display Description" id="HlP'.$param1.'" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/help.png" height="16" width="16" onclick="togglevisibility(\'HLP'.$param1.'\');" onmouseover="document.getElementById(\'HlP'.$param1.'\').setAttribute(\'width\',\'18\');document.getElementById(\'HlP'.$param1.'\').setAttribute(\'height\',\'18\');" onmouseout="document.getElementById(\'HlP'.$param1.'\').setAttribute(\'width\',\'16\'); document.getElementById(\'HlP'.$param1.'\').setAttribute(\'height\',\'16\');" /></td></tr>
	<tr><td colspan="4"><div id="HLP'.$param1.'" style="display:none;border:black solid 1px">'.permissiondescription($param1,0).'</div></td></tr>';
	return $tableEntry;
}

 function addpermchecked($param1){
	global $wgScriptPath;
	$tableEntry = '<tr id="'.$param1.'" onmousedown="document.getElementById(\''.$param1.'\').style.background = \'grey\';" onmouseup="document.getElementById(\''.$param1.'\').style.background = \'lightgrey\'" onmouseover="document.getElementById(\''.$param1.'\').style.background = \'lightgrey\'" onmouseout="document.getElementById(\''.$param1.'\').style.background = \'white\'">
	<td>'.$param1.'</td>
	<td><!--<input id="CB'.$param1.'" type="checkbox" checked="true"/>-->'.CheckBox('CHK'.$param1,true).'</td>
	<td style="width:18px;text-align:center;vertical-align:middle"><img title="Remove Permission" id="PiC'.$param1.'" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/deleteT.png" height="16" width="16" onclick="removeitem(\''.$param1.'\');" onmouseover="document.getElementById(\'PiC'.$param1.'\').setAttribute(\'width\',\'18\');document.getElementById(\'PiC'.$param1.'\').setAttribute(\'height\',\'18\');" onmouseout="document.getElementById(\'PiC'.$param1.'\').setAttribute(\'width\',\'16\'); document.getElementById(\'PiC'.$param1.'\').setAttribute(\'height\',\'16\');" /></td>
	<td style="width:18px;text-align:center;vertical-align:middle"><img title="Display Description" id="HlP'.$param1.'" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/help.png" height="16" width="16" onclick="togglevisibility(\'HLP'.$param1.'\');" onmouseover="document.getElementById(\'HlP'.$param1.'\').setAttribute(\'width\',\'18\');document.getElementById(\'HlP'.$param1.'\').setAttribute(\'height\',\'18\');" onmouseout="document.getElementById(\'HlP'.$param1.'\').setAttribute(\'width\',\'16\'); document.getElementById(\'HlP'.$param1.'\').setAttribute(\'height\',\'16\');" /></td></tr>
	<tr><td colspan="4"><div id="HLP'.$param1.'" style="display:none;border:black solid 1px">'.permissiondescription($param1,0).'</div></td></tr>';
	return $tableEntry;
}
 function permissiondescription($param1,$param2){
	require_once( "UserRights.php" );
		$UR = new UserRights;
		$perms = $UR->returnPermissions();
		$htm = null;
		foreach($perms as $item => $value){
			foreach($perms[$item] as $obj){
				if($obj->name == $param1){
					return '<!--'.$param2.'-->'.$obj->description;
				}
			}
		}
		$htm = $htm.'</select>';
		return $htm;
}
 
 function isvalidgroup($param1,$param2){
	global $wgGroupPermissions,$wgScriptPath;
	if($param1 == NULL){return '<!--'.$param2.'-->';}
	foreach($wgGroupPermissions as $item => $value){
		if($item == $param1){return '<!--'.$param2.'--><span id="cor" style="color:red"><img src="'.$wgScriptPath.'/extensions/GroupPermissions/images/deleteT.png" height="16" width="16" /><strong> Group Name Already in Use</strong></span>';}
	}
	return '<!--'.$param2.'--><span id="cor" style=\'text-color:green\'><img src="'.$wgScriptPath.'/extensions/GroupPermissions/images/check.jpg" height="16" width="16" /></span>';
}
function isgroupsubmissionvalid($param1){
	global $wgScriptPath;
	if (isvalidgroup($param1) == '<span id="cor" style="color:green"><img src="'.$wgScriptPath.'/extensions/GroupPermissions/images/check.jpg" height="16" width="16" /></span>'){
		Return true;
	}
	return false;
}
function creategroupP($param1){
	return ExampleSpecialPage::creategroup($param1);
}
function editgroupP($param1){
	return ExampleSpecialPage::editgroup($param1);
}
function loadmainp(){
	return ExampleSpecialPage::showMainForm();
}
function loadpage($param1){
	global $wgScriptPath;
	return
	'
	<br/><br/><br/><br/><div style="background-color:white;text-align:center;width:20%;border:solid 1px darkblue" align="center"><span style="width:50%;background-color:white;text-align:center"><img src="'.$wgScriptPath.'/extensions/GroupPermissions/images/ajax-loader.gif" height="16" width="16" /><br/>'.$param1.'</span></div><br/><br/><br/><br/>
	';
}

function CheckBox($id,$checked = false){
	global $wgScriptPath;
	if($checked == true){
	return '<img id="'.$id.'" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/ybox.jpg" height="18" width="18" onclick="ToggleSpecialCHK(\''.$id.'\');"/>';
	}elseif($checked == false){
		return '<img id="'.$id.'" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/xbox.jpg" height="18" width="18" onclick="ToggleSpecialCHK(\''.$id.'\');"/>';
	}else{
		return "FAIL CHECKBOX CHECKED ATTRIBUTE";
	}
}

function addresize($ElementID,$OriginalSize,$NewSize){
	return 'onmouseover="document.getElementById(\''.$ElementID.'\').setAttribute(\'width\',\''.$NewSize.'\');document.getElementById(\''.$ElementID.'\').setAttribute(\'height\',\''.$NewSize.'\');" onmouseout="document.getElementById(\''.$ElementID.'\').setAttribute(\'width\',\''.$OriginalSize.'\'); document.getElementById(\''.$ElementID.'\').setAttribute(\'height\',\''.$OriginalSize.'\');"';
}

function writePermChanges($group,$items){
	global $wgGroupPermissions;
	$permissions = $wgGroupPermissions;
	foreach($items as $i => $value){
		$v = $items[$i];
		$permissions[$group][$i] = $v;
	}
	$html = "<?php\n";
	$html = $html.'$wgmicpgpsetfile = "yes";'."\n";
	foreach($permissions as $gperm => $value){
		foreach($permissions[$gperm] as $perm => $value){
			$html = $html.'$wgGroupPermissions[\''.$gperm.'\'][\''.$perm.'\'];'."\n";
		}
	}
	$html = $html."?>";
	$settingsfile = "extensions/GroupPermissions/settings.php";
	$fh = fopen($settingsfile, 'w') or die("can't open file");
	fputs($fh,$html);
	fclose($fh);
	//echo $html;
	//print_r($permissions);
	return null;
}
function wfGroupPermissionsExtension() {
	global $IP, $wgMessageCache;
	require_once( "$IP/includes/SpecialPage.php" );

		$wgMessageCache->addMessages(
				array(
// Here you should define the article name that contains the Special Page's Title as shown in [[:metawikipedia:Special:Specialpages|Specialpages]]
// Where 'specialpagename' will be MediaWiki:<specialpagename> eg. Special:Allpages might be 'allpages'
// The part after '=>' is the default value of the title so again, using Special:Allpages as an example you would have...
// 'allpages' => 'All Pages';
// the part BEFORE the => must be all Lowercase.
					'micp:grouppermissions' => 'Group Permissions'
					)
				);

	class ExampleSpecialPage extends SpecialPage {
				function ExampleSpecialPage() {
							SpecialPage::SpecialPage( 'micp:grouppermissions' );
							parent::__construct( 'micp:grouppermissions', 'editinterface' ); // restrict to sysops
							$this->includable( true );
				}

				function execute( $par = NULL ) {
            global $wgOut,$wgRequest,$wgScriptPath,$wgmicpgpsetfile;
				$set = @$wgmicpgpsetfile;
				$html = null;
				$html = settingsfileq();
				if (ExampleSpecialPage::isBureaucrat() == false){
				$html = $html.'<div id="msgboxes" style="text-align:center" align="center"></div><script type="text/javascript">setTimeout("bureaucrat()",500)</script>';
				}else{
				$html = $html.'<div id="msgboxes"></div>';
				}
				$wgOut->addHTML('
				<script type="text/javascript" src="/js/prototype.js"></script> 
        <script type="text/javascript" src="/js/scriptaculous.js"></script> 
		<H1>GroupPermissions Manager</H1>'.$html.'<div id="GroupPerms" style="width:100%" align="center"></div><script type="text/javascript" src="'.$wgScriptPath.'/extensions/grouppermissions/images/slide.js"></script><script type="text/javascript">setTimeout("AJAXDisabled()", 5000);sajax_do_call(\'loadpage\', ["Loading Group Permissions"], loadmain); 
				var aja = false;
				function loadmain(request){
					aja = true;
					document.getElementById("GroupPerms").innerHTML = request.responseText;
					sajax_do_call(\'loadmainp\', [], display);
				}
				function display(request){
					document.getElementById("GroupPerms").innerHTML = request.responseText;
				}
				function AJAXDisabled(){
					if(aja == false){
						document.getElementById("GroupPerms").innerHTML = \'<div style="border:1px solid red">Ajax is currently disabled for this mediawiki installation<br/>This extension requires Ajax to Run.<br/> If you wish to use one of these that does not use Ajax, Please use a previous version</div>\';
					}
				}
				
				function bureaucrat(){
					var txt = "You Must Be Logged in as a Bureaucrat to make any changes";
					sajax_do_call(\'errorbox\', [txt], displaymsgbox);
				}
				
				//function startfadeout(id){
				//	var fade=setTimeout("fadeout(\'" + id + "\')", 3600);
				//	var hide=setTimeout("$(\'" + id + "\').hide()", 4800);
				//}
				
				function fadeout(id){
					var fade=setTimeout("fadeout2(\'" + id + "\')", 8000);
				}
				
				function fadeout2(id){
					new Effect.Opacity(id, {duration:1.0, from:1.0, to:0.0});
					//var fade=setTimeout("fadeout(\'" + id + "\')", 1600);
					var hide=setTimeout("$(\'" + id + "\').hide()", 1000);
				}
				function ToggleSpecialCHK(element){
					str = document.getElementById(element).src;
					if(str.indexOf("xbox.jpg") != -1){
						document.getElementById(element).src = "'.$wgScriptPath.'/extensions/GroupPermissions/images/ybox.jpg";
					}else{
						document.getElementById(element).src = "'.$wgScriptPath.'/extensions/GroupPermissions/images/xbox.jpg";
					}
				}
				
				function creategroup(){
					if (document.getElementById("cor").innerHTML == \'<img src="'.$wgScriptPath.'/extensions/GroupPermissions/images/check.jpg" height="16" width="16">\')
					{
						var groupname = document.getElementById("create");
						var groupname = groupname.value;
						sajax_do_call(\'creategroupP\', [groupname], display);
						//sajax_do_call(\'loadpage\', ["Loading Group Creation page: " + groupname], ng);
					}
				}
				
				function jserrorbox(message){
					sajax_do_call(\'errorbox\', [message], displaymsgbox);
				}
				function jsnotifybox(message){
					sajax_do_call(\'notifybox\', [message], displaymsgbox);
				}
				function displaymsgbox(request){
					var txt = request.responseText;
					var lol = txt.substring(4,txt.indexOf("-->"));
					//alert(lol);
					var tmp = document.getElementById("msgboxes").innerHTML;
					document.getElementById("msgboxes").innerHTML = tmp + request.responseText;
					fadeout(lol);
				}
				function disableSelection(target){
				
				if (typeof target.onselectstart!="undefined") //IE route
				
					target.onselectstart=function(){return false}
				
				else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
				
					target.style.MozUserSelect="none"
				
				else //All other route (ie: Opera)
				
					target.onmousedown=function(){return false}
					target.style.cursor = "default"
				
				}
				
				function togglevisibility(element){
					if(document.getElementById(element).style.display == "none"){
						document.getElementById(element).style.display = "block";
					}else{
						document.getElementById(element).style.display = "none";
					}
				}
				
				function editgroup(){
					var e = document.getElementById("group");
					var txt = e.options[e.selectedIndex].value;
					sajax_do_call(\'editgroupP\', [txt], editgroupArray);
				}
				
				function editgroupArray(request){
					document.getElementById("GroupPerms").innerHTML = request.responseText;
					var td_list = document.getElementById("items").getElementsByTagName("TR");
					if(td_list){
						for(var i=0; i<td_list.length;++i){
							var str = td_list[i].id;
							if(str.length != 0){
								Divs[Divs.length] = str;
								//alert(Divs);
							}
						}
					}
					//alert("done");
				}
				function permissionhelp(form){
					current +=1;
					document.getElementById("helptext").innerHTML = \'<img src="'.$wgScriptPath.'/extensions/GroupPermissions/images/ajax-loader.gif" height="16" width="16" /> Loading Permission Help...<br/>\'
					var e = form.permissionselection;
					var txt = e.options[e.selectedIndex].value;
					sajax_do_call(\'permissiondescription\', [txt,current], displaypermhelp);
				}
				function displaypermhelp(request){
					var txt = request.responseText;
					if (txt.indexOf("<!--" + current + "-->") == -1){}else{
						document.getElementById("helptext").innerHTML = request.responseText;
					}
					return;
				}
				function ng(request){
					
					document.getElementById("GroupPerms").innerHTML = request.responseText;
					
				}
				var Divs = Array();
				function addpermission(form){
					var e = form.permissionselection;
					var txt = e.options[e.selectedIndex].value;
					if (document.getElementById(txt)){
					jserrorbox("Permission Already Exists: " + txt);
					}else{
					Divs[Divs.length] = txt;
					sajax_do_call(\'addperm\', [txt], jsaddperm);
					}
				}
				function jsaddperm(request){
					document.getElementById("items").innerHTML = document.getElementById("items").innerHTML + request.responseText;
				}
				function removeitem(id){
					var d = document.getElementById("items");
					var olddiv = document.getElementById(id);
					
					d.removeChild(olddiv);
				}
				
				function submitnewgroup(group){
					var td_list = document.getElementById("items").getElementsByTagName("IMG");
					if(td_list){
						var arr = new Array(td_list.length + 1);
						arr[0] = group;
						var c = 1;
						for(var i=0; i<td_list.length;++i){
							var str = td_list[i].id;
							if(str.indexOf("CHK") == 0){
								//alert(td_list[i].id);
								var e = document.getElementById(td_list[i].id);
								var arra = new Array(2);
								arra[0] = str.substring(3);
								arra[1] = CheckBoxChecked(td_list[i].id);
								//alert(str.substring(3) + " " + CheckBoxChecked(td_list[i].id));
								arr[c] = arra;
								++c;
							}
						}
						//alert(arr.length);
						sajax_do_call(\'savegroup\', [arr], nodisplay);
					}
					jsnotifybox("Successfully made changes");
				}
				
				function nodisplay(result){
					
				}
				
				function CheckBoxChecked(id){
					str = document.getElementById(id).src;
					if(str.indexOf("xbox.jpg") == -1){
						return true;
					}else{
						return false;
					}
				}
				disableSelection(document.getElementById("GroupPerms"));
				</script>
				<script type="text/javascript">
				var current = 0;
function docall(){
	current += 1;
	//alert(current);
	//document.getElementById("validatenewitem").innerHTML = \'<img src="'.$wgScriptPath.'/extensions/GroupPermissions/images/ajax-loader.gif" height="16" width="16" /> Validating Group Name...<br/>\'
	var groupname = document.getElementById("create");
	var groupname = groupname.value;
	sajax_do_call(\'isvalidgroup\', [groupname,current], callback);
	return;
}
function callback(request){
	var txt = request.responseText;
	if (txt.indexOf("<!--" + current + "-->") == -1){}else{
	document.getElementById("validatenewitem").innerHTML = request.responseText;
	}
	return;
}
</script>');
				
				return null;
    }
	
	/*function loadform(form, name = null){
		
	{*/
	
	function showMainForm() {
		global $wgGroupPermissions,$wgUser, $wgScriptPath;
		$html = '<H2>Please Select or create a Permissions Group</H2>';
		//$html = $html.'<FORM name="Selection" method="GET">';
		$html = $html.'<fieldset><legend>Edit a Group</legend>';
		$html = $html.'<select id="group" name="group">';
		foreach($wgGroupPermissions as $item => $value){
			$html = $html.'<option value="'.$item.'">'.$item.'</option>';
		}
		$html = $html.'</select>';
		$html = $html.'<!--<input type="button" value="Edit group" onclick="editgroup()"/>--><img id="edit" src="'.$wgScriptPath.'/extensions/GroupPermissions/images/edit.png" height="16" width="16" onclick="editgroup();" title="Edit Group" '.addresize("edit",16,18).'/>';
		$html = $html.'</fieldset>';
		//$html = $html.
		//'</select></form>';
		//<FORM name="Selection" method="GET">
		$html = $html.'


	<fieldset>
	<legend>Create New Permissions Group</legend>
	<table style="width:100%"><tr><td style="width:50%;text-align:right"><label for="create">Group Name</label><input type name="create" id="create" value="" onkeyup="javascript:docall()"/></td>
	<td style="text-align:left;width:50%"><span id="validatenewitem"></span></td></tr></table>
	<br/>
	<input type="button" value="Create Group" id="submit" onclick="javascript:creategroup()" />
	</fieldset>'; //</form>
		return $html;
	}

	function creategroup($groupname){
		$html = 
		'
		<form name="CreateGroup" method="GET">
			<fieldset>
			<legend>New Group: '.$groupname.'</legend>
				<div style="overflow: auto; max-height: 250px;border:1px solid black"><table name="items" id="items" style="width:100%;"></table></div>
				'.ExampleSpecialPage::submitButton($groupname).'
			</fieldset>
			<fieldset>
				<legend>Add New Permission</legend>
				'.ExampleSpecialPage::createPermissionsBox().'
				<div style="width:100%;text-align:center;border:solid 1px grey">
					<span id="helptext">Help text will display here</span>
				</div><br/>
				<input type="button" id="addperm" name="addperm" onclick="javascript:addpermission(this.form)" value="Add Permission"></input>
			</fieldset>
		</form>
		';
		return $html;
	}
	
	function editgroup($groupname){
		$html = 
		'
		<form name="CreateGroup" method="GET">
			<fieldset>
			<legend>Edit Group: '.$groupname.'</legend>
				<div style="overflow: auto; max-height: 250px;border:1px solid black">'.populatepermissions($groupname).'</div>
				'.ExampleSpecialPage::submitButton($groupname).'
			</fieldset>
			
			<fieldset>
				<legend>Add New Permission</legend>
				'.ExampleSpecialPage::createPermissionsBox().'
				<div style="width:100%;text-align:center;border:solid 1px grey">
					<span id="helptext">Help text will display here</span>
				</div><br/>
				<input type="button" id="addperm" name="addperm" onclick="javascript:addpermission(this.form)" value="Add Permission"></input>
			</fieldset>
		</form>
		';
		return $html;
	}
	
	function submitButton($group){
		if(ExampleSpecialPage::isBureaucrat() == true){
		return '<input type="button" onclick="submitnewgroup(\''.$group.'\');" value="Create Group"/>';
		}else{
		return '<input disabled type="button" onclick="submitnewgroup(\''.$group.'\');" value="Create Group"/>';
		}
	}
	
	function createPermissionsBox(){
		require_once( "UserRights.php" );
		$UR = new UserRights;
		$perms = $UR->returnPermissions();
		$htm = '<select id="permissionselection" name="permissionselection" onchange="permissionhelp(this.form)">';
		foreach($perms as $item => $value){
			foreach($perms[$item] as $obj){
				if($obj->name == null){ die('Name is Null');}
				if($obj->isAllowed == true){
					$htm = $htm.'<option value="'.$obj->name.'">'.$item.' | '.$obj->name.'</option>';
				}
			}
		}
		$htm = $htm.'</select>';
		return $htm;
	}
	
	function isBureaucrat() {
		global $wgUser;
		$Bureaucrat = false;
		$groups = $wgUser->getGroups();
		foreach($groups as $item => $value){
			if ($item == 1) {$Bureaucrat = true;}
		}
		return $Bureaucrat;
	}
	}
	
SpecialPage::addPage( new ExampleSpecialPage );
}
?>