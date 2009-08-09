<?php
	class UserRights{
		var $UserRights;
		public Function returnPermissions(){
			//global $wgDebug;
			//$wgDebug->debugmessage('Entering Function returnPermissions');
			$permissions['Reading'] = $this->rgReading();
			$permissions['Editing'] = $this->rgEditing();
			$permissions['Management'] = $this->rgManagement();
			$permissions['Administration'] = $this->rgAdministration();
			$permissions['Technical'] = $this->rgTechnical();
			//$wgDebug->debugmessage('Finished Function returnPermissions');
			return $permissions;
		}
		//$permissions['edit'] = new UserRight('edit','allows editing unprotected pages.',1.5);
	private function rgReading(){
		//global $wgDebug;
		//$wgDebug->debugmessage('Entering Function rgReading');
		$perm2['read'] = new UserRight('read','allows viewing pages (when set to false, override for specific pages with $wgWhitelistRead).',1.5);
		//$wgDebug->debugmessage('Exiting Function rgReading');
		return $perm2;
	}
	private function rgEditing(){
		//global $wgDebug;
		//$wgDebug->debugmessage('Entering Function rgEditing');
		$permissions['edit'] = new UserRight('edit','allows editing unprotected pages.',1.5);
		$permissions['createpage'] = new UserRight('createpage','allows the creation of new pages (requires the edit right).',1.6);
		$permissions['createtalk'] = new UserRight('createtalk','allows the creation of new talk pages (requires the edit right).',1.6);
		$permissions['move'] = new UserRight('move','allows renaming the titles of unprotected pages (requires the edit right).',1.5);
		$permissions['movefile'] = new UserRight('movefile','allows renaming pages in the "File" namespace (requires the move right and $wgAllowImageMoving to be true).',1.14);
		$permissions['move-subpages'] = new UserRight('move-subpages','move subpages along with page (requires the move right).',1.13);
		$permissions['move-rootuserpages'] = new UserRight('move-rootuserpages','can move root pages in the "User" namespace (requires the move right).',1.14);
		$permissions['createaccount'] = new UserRight('createaccount','allows the creation of new user accounts.',1.5);
		$permissions['upload'] = new UserRight('upload','allows the creation of new images and files.',1.5);
		$permissions['reupload'] = new UserRight('reupload','allows overwriting existing images and files (requires the upload right).',1.6);
		$permissions['reupload-own'] = new UserRight('reupload-own','allows overwriting existing images and files uploaded by oneself (requires the upload right).',1.11);
		$permissions['reupload-shared'] = new UserRight('reupload-shared','allows replacing images and files from a shared repository (if one is set up) with local files (requires the upload right).',1.6);
		$permissions['upload_by_url'] = new UserRight('upload_by_url','allows uploading by entering the URL of an external image (requires the upload right).',1.8);
		$permissions['editprotected'] = new UserRight('editprotected','allows to edit protected pages (without cascading protection).',1.13);
		//$wgDebug->debugmessage('Exiting Function rgEditing');
		return $permissions;
	}

	private function rgManagement(){
		//global $wgDebug;
		//$wgDebug->debugmessage('Entering Function rgManagement');
		$permissions['delete'] = new UserRight('delete','1.5–1.11: allows the deletion or undeletion of pages. <br/>1.12+: allows the deletion of pages. For undeletions, there is now the \'undelete\' right, see below.',1.5);
		$permissions['bigdelete'] = new UserRight('bigdelete','allows deletion of pages with larger than $wgDeleteRevisionsLimit revisions',1.12);
		$permissions['deletedhistory'] = new UserRight('deletedhistory','allows viewing deleted revisions, but not restoring.',1.6);
		$permissions['undelete'] = new UserRight('undelete','allows the undeletion of pages.',1.12);
		$permissions['mergehistory'] = new UserRight('mergehistory','allows access to Special:MergeHistory, to merge non-overlapping pages.<br/>Note: currently disabled by default, including on Wikimedia projects.',1.12);
		$permissions['protect'] = new UserRight('protect','allows locking a page to prevent edits and moves, and editing or moving locked pages.',1.5);
		$permissions['block'] = new UserRight('block','allows the blocking of IP addresses, CIDR ranges, and registered users. Block options include preventing editing and registering new accounts, and autoblocking other users on the same IP address.',1.5);
		$permissions['blockemail'] = new UserRight('blockemail','allows preventing use of the Special:Emailuser interface when blocking.',1.11);
		$permissions['hideuser'] = new UserRight('hideuser','allows hiding the user/IP from the block log, active block list, and user list when blocking. (not available by default)',1.10);
		$permissions['userrights'] = new UserRight('userrights','allows the use of the user rights interface, which allows the assignment or removal of all* groups to any user.<br/>* With $wgAddGroups and $wgRemoveGroups you can set the possibility to add/remove certain groups instead of all.',1.5);
		$permissions['userrights-interwiki'] = new UserRight('userrights-interwiki','allows changing user rights on other wikis.',1.12);
		$permissions['rollback'] = new UserRight('rollback','allows one-click reversion of edits.',1.5);
		$permissions['markbotedits'] = new UserRight('markbotedits','allows rollback to be marked as bot edits (see Manual:Administrators#Rollback).',1.12);
		$permissions['patrol'] = new UserRight('patrol','allows marking edits as legitimate ($wgUseRCPatrol must be true).',1.5);
		$permissions['editinterface'] = new UserRight('editinterface','allows editing the MediaWiki namespace, which contains interface messages.',1.5);
		$permissions['editusercssjs'] = new UserRight('editusercssjs','allows editing user\'s own monobook.css, monobook.js, ... subpages.',1.12);
		$permissions['supressrevision'] = new UserRight('supressrevision','allows preventing deleted revision information from being viewed by sysops and prevents sysops from undeleting the hidden info. Prior to 1.13 this right was named hiderevision (not available by default)',1.6);
		$permissions['deleterevision'] = new UserRight('deleterevision','allows deleting/undeleting information (revision text, edit summary, user who made the edit) of specific revisions (not available by default)',1.6);
		//$wgDebug->debugmessage('Exiting Function rgManagement');
		return $permissions;
	}
	private function rgAdministration(){
		//global $wgDebug;
		//$wgDebug->debugmessage('Entering Function rgAdministration');
		$permissions['siteadmin'] = new UserRight('siteadmin','allows locking and unlocking the database (which blocks all interactions with the web site except viewing). Deprecated by default.',1.5);
		$permissions['import'] = new UserRight('import','allows user to import one page per time from another wiki ("transwiki").',1.5);
		$permissions['importupload'] = new UserRight('importupload','allows user to import several pages per time from XML files. This right was called \'importraw\' in and before version 1.5.',1.5);
		$permissions['trackback'] = new UserRight('trackback','allows removal of trackbacks (if $wgUseTrackbacks is true).',1.7);
		$permissions['unwatchedpages'] = new UserRight('unwatchedpages','allows access to Special:Unwatchedpages, which lists pages that no user has watchlisted.',1.6);
		//$wgDebug->debugmessage('Exiting Function rgAdministration');
		return $permissions;
	}
	private function rgTechnical(){
		//global $wgDebug;
		//$wgDebug->debugmessage('Entering Function rgTechnical');
		$permissions['bot'] = new UserRight('bot','hides edits from recent changes lists and watchlists by default (can optionally be viewed).',1.5);
		$permissions['purge'] = new UserRight('purge','allows purging a page without a confirmation step (URL parameter "&action=purge").',1.10);
		$permissions['minoredit'] = new UserRight('minoredit','allows marking an edit as \'minor\'.',1.6);
		$permissions['nominornewtalk'] = new UserRight('nominornewtalk','blocks new message notification when making minor edits to user talk pages (requires minor edit right).',1.9);
		$permissions['noratelimit'] = new UserRight('noratelimit','not affected by rate limits (prior to the introduction of this right, the configuration variable $wgRateLimitsExcludedGroups was used for this purpose)',1.13);
		$permissions['ipblock-exempt'] = new UserRight('ipblock-exempt','makes user immune to blocks applied to his IP address or a range (CIDR) containing it.',1.9);
		$permissions['proxyunbannable'] = new UserRight('proxyunbannable','makes user immune to the open proxy blocker, which is disabled by default ($wgBlockOpenProxies).',1.7);
		$permissions['autopatrol'] = new UserRight('autopatrol','automatically marks all edits by the user as patrolled ($wgUseRCPatrol must be true).',1.9);
		$permissions['apihighlimits'] = new UserRight('apihighlimits','allows user to use higher limits for API queries',1.12);
		$permissions['writeapi'] = new UserRight('writeapi','controls access to the write API ($wgEnableWriteAPI must be true)',1.13);
		$permissions['suppressredirect'] = new UserRight('suppressredirect','Allows moving a page without automatically creating a redirect.',1.12);
		$permissions['autoconfirmed'] = new UserRight('autoconfirmed','used for the \'autoconfirmed\' group, see the other table below for more information.',1.6);
		$permissions['emailconfirmed'] = new UserRight('emailconfirmed','used for the \'emailconfirmed\' group, see the other table below for more information.',1.7,1.13);
		//$wgDebug->debugmessage('Exiting Function rgTechnical');
		return $permissions;
	}
	}
	class UserRight{
		
		public $name = null;
		public $description = null;
		public $startversion = null;
		public $endversion = null;
		
		public function __get($name = NULL){ return $this->name;}
		public function __construct($n,$d,$sv,$ev = NULL){
			global $wgVersion;
			$this->name = $n;
			$this->description = $d;
			$this->startversion = $sv;
			if($ev == NULL){ $this->endversion = $wgVersion;}else{$this->endversion = $ev;}
		}

		public function asArray(){
			return array('name' => $this->name, 'description' => $this->description, 'startversion' => $this->startversion, 'endversion' => $this->endversion);
		}
		Public Function isAllowed(){
			global $wgVersion;
			$btsv = $this->isBiggerOrSame($wgVersion,$this->startversion);
			$ltend = $this->isBiggerOrSame($this->endversion,$wgVersion);
			
			//echo 'Name: '.$this->name.' btsv: '.$btsv.' ,ltend: '.$ltend.'<br/>';
			if($btsv == true && $ltend == true){return true;}
			return false;
			//if ($currentversion >= $this->startversion && $currentversion <= $this->endversion ){ return true;}else{return false;}
		}
		
		private function isBiggerOrSame($version1/* Version we are testing */,$version2 /* Version we are testing against */){
			$version1mb  = $this->MainBuild($version1);
			$version1sb  = $this->SubVersion($version1);
			$version1ssb = $this->SubSubVersion($version1);
			$version2mb  = $this->MainBuild($version2);
			$version2sb  = $this->SubVersion($version2);
			$version2ssb = $this->SubSubVersion($version2);
			if ($version1mb < $version2mb){return false;}
			if ($version1sb < $version2sb){return false;}
			if ($version1ssb < $version2ssb){return false;}
			return true;
		}
		private function MainBuild($version){
			return substr(strval($version),0,strpos(strval($version),'.'));	
		}
		private function SubVersion($version){
			if(strrpos(strval($version),'.') == strpos(strval($version),'.')){ return substr(strval($version),strpos(strval($version),'.')+1);}
			return substr(strval($version),strpos(strval($version),'.')+1,strrpos(strval($version),'.'));
		}
		private function SubSubVersion($version){
			if(strrpos(strval($version),'.') == strpos(strval($version),'.')){return 0;}
			return substr(strval($version),strrpos(strval($version),'.')+1);
		}
	}
$wgGPUserRights = new UserRights;
?>