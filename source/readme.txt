Structure:
"flooding" folder is the directory for php codes for the web layer
"nodeMCU" folder has the nodeMCU code. The folder "code" within has sketch to run the nodeMCU. The folder "calibration" has code that was used to calibrate as documented in the report.
"flooding.sql" is the SQL schema for the database. Import it using phpmyadmin

Prerequisites:
1. Install XAMPP in your computer.
2. Install Arduino IDE in your computer. Install ESP8266 library then.

TODO sections (marked //TODO: in code):
1. in file: flooding>web_service>post_log.php:
	- Create your Twilio account and enter the credentials in the areas marked todo

2. in file: nodeMCU>code>code:
	- Enter your wifi ssid and password
	- Replace the fixed IP with your computer's IP

3. in file: flooding>config.php
	- Enter your access credentials