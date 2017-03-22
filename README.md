# Warcontact Mangement System
Tool for mangement of scout alts for highsec wars using EvE SSO and Crest api

# Features
- Checking of active and declared wars hourly

- Adding the wartargets, including allies, automatic to your scout characters 

- Manage your groups and see all used characters by yourself and if admin by your group

- Manually remove wars, if they are not valid due to f.e. a nap

# Demo
A demo can be found here https://warcontacts.tobiasgabriel.de, if you want to test is please send send me a mail to Shegox Gabriel ingame.

# Usage
Login to the tool with EvE SSO, which allowes me to identify the selcted charcter and only that. For scout alts the access to contacts (read and write) is needed.

# Revoking of access
If you want to revoke the access of the tool you can simply log in and remove the char. The token and all information with this alt will be deleted aswell.
Additional you can revoke the access though CCPs website: https://community.eveonline.com/support/third-party-applications/

# Technolgy
The following thigs are needed to run it on your own:
- PHP 5.6 or higher
- MySQL database
- EvE developer account
- Apache webserver

# Setup
1) Download the repository and go to the /php/examples.constants.php and rename it to constants.php. Fill in the needed information.
  The EVE Appliaction need as scope contacts read and contacts write. You can find the applications here: https://developers.eveonline.com/
  Make sure that the callback url is http(s)://&lt;domain&gt;/php/addChar.php.
  
2) Import the database sheme (structure.sql) into your database and fill the database name and access information into to constants.php file

3) Point your webserver to the /public directory.

4) Try to access the service, no errors should pop up.

5) Create a hourly cron job which points to /php/Update.php
