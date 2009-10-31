VBulletin Authenticator
=======================

This is a SilverStripe module for authenticating against a vBulletin user directory.

Installation
------------

 * Install the module and run dev/build
 * Ensure that vBulletin is installed into the same database as SilverStripe
 * If you have set a vBulletin table prefix, define it by putting this line in your `_config.php`

		VBulletinAuthExtension::set_table_prefix('vb');