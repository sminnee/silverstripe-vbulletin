<?php

/**
 * Extension to apply to Controller to authenticate new users against their VBulletin data
 */
class VBulletinAuthExtension extends Extension {
	protected static $table_prefix;

	/**
	 * Set a table prefix for the vBulletin tables, if you have used one.
	 */
	static function set_table_prefix($tp) {
		self::$table_prefix = $tp;
	}
	

	function onBeforeInit() {
		// ADMIN users are the only users that can bypass vBulletin based login
		if(Permission::check("ADMIN")) return;
		
		// Logged into vBulletin; should be logged into SilverStripe
		if($userID = $this->currentVBUserID()) {
			if(!Member::currentUserID()) {
				$member = $this->findUser($userID);
				if(!$member) $member = $this->createNewUser($userID);
				$member->logIn();
			}
			
		// Not logged into vBulletin; shoudln't be logged into SilverStripe
		} else {
			if($member = Member::currentUser()) {
				$member->logOut();
			}
		}
	}
	
	/**
	 * Return the user ID of the currently logged in VB user.
	 * @todo Make this work properly.
	 */
	function currentVBUserID() {
		// This only works if you've clicked 'remember me', and it's really insecure.
		if(isset($_COOKIE['bbuserid'])) return $_COOKIE['bbuserid'];
	}
	
	/**
	 * Fina an existing user copied from vBulletin to SilverStripe member, if one exists
	 */
	function findUser($bbUserID) {
		$SQL_bbUserID = (int)$bbUserID;
		return DataObject::get_one("Member", "VBulletinUserID = $SQL_bbUserID");
	}
	
	/**
	 * Copy user details from vBulletin to SilverStripe member
	 */
	function createNewUser($bbUserID) {
		$tablePrefix = self::$table_prefix ? self::$table_prefix.'_' : '';
		
		$SQL_userID = (int)$_COOKIE['bbuserid'];
		$userInfo = DB::query("SELECT * FROM {$tablePrefix}user WHERE userid = $SQL_userID")->record();
		
		$m = new Member();
		$m->VBulletinUserID = $userInfo['userid'];
		$m->VBulletinUsername = $userInfo['username'];
		$m->FirstName = $userInfo['username'];
		$m->Email = $userInfo['email'];
		$m->write();
		
		return $m;
	}
}