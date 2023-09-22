![Bugfish](https://img.shields.io/badge/Bugfish-Software-orange)
![Status](https://img.shields.io/badge/Status-Finished-green)
![License](https://img.shields.io/badge/License-MIT-black)
![Version](https://img.shields.io/badge/Version-3.1-white)
# Mail Relaying over DNS [MRoD]

Here you can see general informations about this software, you should check them urgently before you use it! I hope you will get an understanding what this software is for and how you can use it. If you have questions outside this documentations do not hesitate to contact me! This software will occupy the email functionality of the server it is installed on, keep this in mind - this script will break a running mail configuration and change it for its purpose! It can not be used with plesk or other software which does manage mail configurations side-by-side. It is clearly advised, to install this sofware on a dedicated server for it!

This software has been tested on different linux system with postfix in standalone mode and with bind together if auto-domain-fetching for domain relaying is needed.
Tested on: Debian 8/9/10/11
Tested on: Ubuntu 16/18/20/22
Tested on: Different Postfix Versions (Standalone)
Tested on: DIfferent Bind9 Versions (Auto-Fetch Domains)
About this Software

This websoftware makes your server to a mail backup relay! It can be used standalone, by users configuring domains which should be relay to master-mail-servers - besides that this panel can be used on a secondary dns server and fetch to registered dns domains. More informations below. To summarize, if you have a mail server which is receiving and sending mails this server will be untouched. But if you plan to have a second server as backup, so if your master-mail server goes offline and you want to store incoming mail on a secondary mail server until the master server is online again. The mail will then be forwarded to the "real" server. You can set up the related Master-Mail Server per domain and you can set up different Relay Servers, which than can be connected to Mail Domains.

## Automation with Slave DNS

If you have a secondary DNS Server already running in your infrastructure, you can use this as purpose. In this case you already have a server which contains domain informations, for domains which should maybe be relayed on this secondary dns server. You can use this websoftware on your secondary DNS Server. If you set up your settings.php with fitting configuration, if will automatically fetch the local domains from the Bind9 files and save them into database. The script will determine a relay server (you can change determination settings in settings.php) and then rewrite postfix configuration (automatically) with the cronjob, set up in the installation, so that the secondary dns domains will all be auto-registered into the webinterface. So you do not have to do anything like configure domains or anything. You can still intervene if you determined settings for a domain if wrong and change it like you need it.

## DNS Entry for 2nd Server

If you have now a master mail server and this software running on another server, you need to edit your dns records for the incoming mail domain. You need to add a second MX Entrie with lower priority, the hostname shall be the mail hostname of your secondary mail backup server (where this software is running on). In this case, if a mail is sended to this domain and your first server is not available, the mail will than be delivered to your 2ndary server and be in a waiting list and delivered in time when the master server is back online again. The mail will then be forwarded to the server, which has been set up as relay in the webinterface.
User Management

This webinterface comes with simple user management and permission system...

## Urgent Information

Do never use this software in a running mail environment! It will break the mail configuration and function if you change configuration on a running plesk or other mail system! Only use on a dedicated server for this purpose (or maybe a secondary dns server). It is advised that you have some experience in mail administration if you use this script.

## Documentation
For Documentation take a look at the index.html file in the docs directory! You can open it with any webbrowser...

You can find the Documentation here:  
https://bugfishtm.github.io/Mail-Relaying-over-DNS/  

You can find the Github Project here:  
https://github.com/bugfishtm/Mail-Relaying-over-DNS  

My General Github Project Page is here:  
https://bugfishtm.github.io


## Example Image
![plot](./_images/main.png)

## Default Login for Webinterface
Username: admin  
Passwort: changeme

## Issues
if you encounter issues or have questions using this software, do not hesitate write us at our forum on www.bugfish.eu!

----------------------------------------------------------------
##### more at www.bugfish.eu 
  
Made by Jan-Maurice Dahlmanns
  