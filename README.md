# Mail Relaying over DNS [MRoD]

![framework](./_images/logo.jpg)

This software is designed to set up a backup MX (Mail Exchange) server for incoming emails. It acts as a mail backup relay, storing emails temporarily if the primary mail server goes offline, and forwarding them once the primary server is back online. Key points to consider include:

- **Server Requirements:** It should be installed on a dedicated server, as it will take over the email functionality of the server, disrupting any existing mail configurations. It is not compatible with Plesk or other mail management software.
  
- **DNS Setup:** A secondary "MX" DNS record must be configured for the domain, ensuring that incoming mail is relayed to the backup server when the primary server is unavailable.
  
- **Functionality:** Users can configure different domains to be relayed to the primary mail servers. The software can also operate on a secondary DNS server to manage domain mail settings.

This software is ideal for those who need a reliable backup solution for their mail server.

## Compatibility
This software has been tested on different linux system with postfix in standalone mode and with bind together if auto-domain-fetching for domain relaying is needed.  
- Tested on: Debian 8/9/10/11  
- Tested on: Ubuntu 16/18/20/22  
- Tested on: Different Postfix Versions (Standalone)  
- Tested on: DIfferent Bind9 Versions (Auto-Fetch Domains)  

Feel free to try this software in higher OS versions.   
There should be no compatibility problems, if the PHP8 Version is running.

## Requirements
- Mailserver running with Postfix
- If you want to fetch Mail Relaying Information over DNS this is working only together with Bind9 (optional)
- Webserver with PHP8 Support
- Mysql Database


## Installation

For installation information see the included documentation in the "docs" folder or at https://bugfishtm.github.io/Mail-Relaying-over-DNS.

## Example Image
![plot](./_images/1.png)

## Automation with Slave DNS

If you have a secondary DNS server in your infrastructure, you can utilize it with this software. The setup involves:

- **Integration with Existing DNS Server:** The software can be installed on your secondary DNS server, which already handles domain information.
  
- **Automatic Configuration:** By configuring `settings.php`, the software will fetch local domains from Bind9 files and save them into the database. It will then automatically configure Postfix through a cron job, setting up domains in the web interface without manual intervention.
  
- **Customizable Settings:** You can modify relay server settings and domain configurations as needed if the automatic setup isn't perfect.

This approach automates the integration of secondary DNS domains with minimal manual configuration.

## DNS Entry for 2nd Server

If you have a master mail server and this software running on a secondary server, follow these steps:

- **DNS Configuration:** Add a secondary MX record with a lower priority in your DNS settings. The hostname should point to the mail server where this software is installed.
  
- **Mail Handling:** If the master server is unavailable, incoming mail will be delivered to the secondary server, where it will be queued. Once the master server is back online, the queued mail will be forwarded to it based on the relay settings configured in the web interface.

- **User Management:** The web interface includes a basic user management and permission system for managing access.

## Urgent Information

Do never use this software in a running mail environment! It will break the mail configuration and function if you change configuration on a running plesk or other mail system! Only use on a dedicated server for this purpose (or maybe a secondary dns server). It is advised that you have some experience in mail administration if you use this script.

## Example Image
![plot](./_images/main.png)

## Default Login for Webinterface
Change this data after login!
  
Username: admin  
Passwort: changeme

## Support and Assistance

If you encounter any issues or require assistance, please visit [bugfish.eu/forum](https://www.bugfish.eu/forum) for additional resources. You can also contact us at [request@bugfish.eu](mailto:request@bugfish.eu), and we will do our best to assist you.

This Android WebApp Example project offers a convenient way to deploy customized apps related to your website, enhancing your online presence and user experience.

## Powered by Bugfish Framework

![plot](./_images/bugfish-framework-banner.jpg)

## License Information

The license details for this Mail Relaying over DNS project can be found in the "license.md" file within the project repository. Please review this file to understand the terms and conditions of use and distribution. It is essential to comply with the project's license to ensure legal and ethical usage of the provided resources.