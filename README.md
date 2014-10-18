#Meela
## Simple URL Shortener with UMS and Admin Panel


![](https://raw.githubusercontent.com/sufian/MeeLa-Premium-URL-Shortener/master/artwork/big-temp.png)


* Version: 3.0.2
* [Website](http://mee.la/)

### INSTALL

NOTE: Migrating data from V2 to V3 please make sure you create a new blank database for v3. Installing on the same db as v2 will cause errors.

1. Upload all files.
2. Follow onscreen instructions. 
	- Use installer for both upgrade ( V2 - V3.0.2 ONLY ) and new installation. 
3. If you are upgrading from v2 and are bringing in a large amount of urls you may get an error saying "Fatal error: Allowed memory size of" this is fine just ignore it and revisit /install (don't reload the page) This error is caused by short memory allocated to the script which the server can handle and will not show any further errors to you.

3. Once installed you will be auto logged in to the settings page or taken to the login page. Visit the settings page and please scroll down and click update settings.

4. Make sure you rename install.php to install.php.old (fuel/app/classes/controller) (security purposes)
4. Enjoy.







### UPGRADE 

- V3.0.1 to V3.0.2
1. Nothing to do only installer was changed.


- V3.0.0 to V3.0.1
1. Replace the following:
	- /fuel/app/classes/controller/api.php


- V3-RC2 to V3
1. Upload new code making sure you move fuel/app/config/development/db.php to fuel/app/config/production/db.php
2. Rename upgrade.php.old to upgrade.php (fuel/app/classes/controller)
3. Visit /upgrade
4. Change upgrade.php back to upgrade.php.old and also rename install.php in the same directory to install.php.old to prevent hacking.



It's that simple :)



Settings Page Preview:
![](https://raw.githubusercontent.com/sufian/MeeLa-Premium-URL-Shortener/master/artwork/settings.png)
