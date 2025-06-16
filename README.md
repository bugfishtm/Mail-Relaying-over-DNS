# Mail Relaying over DNS [MRoD]

## 🔍 Overview

> [!NOTE]
> No new features are planned for this project at this time.

> [!TIP]
> This project is actively maintained, with regular updates and prompt fixes for reported issues.

This software is designed to set up a backup MX (Mail Exchange) server for incoming emails. It acts as a mail backup relay, storing emails temporarily if the primary mail server goes offline, and forwarding them once the primary server is back online. Key points to consider include:

- **Server Requirements:** It should be installed on a dedicated server, as it will take over the email functionality of the server, disrupting any existing mail configurations. It is not compatible with Plesk or other mail management software.
  
- **DNS Setup:** A secondary "MX" DNS record must be configured for the domain, ensuring that incoming mail is relayed to the backup server when the primary server is unavailable.
  
- **Functionality:** Users can configure different domains to be relayed to the primary mail servers. The software can also operate on a secondary DNS server to manage domain mail settings.

This software is ideal for those who need a reliable backup solution for their mail server.

![plot](./_screenshots/2.png)

### Compatibility
This software has been tested on different linux system with postfix in standalone mode and with bind together if auto-domain-fetching for domain relaying is needed.  
- Tested on: Debian 8/9/10/11  
- Tested on: Ubuntu 16/18/20/22  
- Tested on: Different Postfix Versions (Standalone)  
- Tested on: DIfferent Bind9 Versions (Auto-Fetch Domains)  

Feel free to try this software in higher OS versions.   
There should be no compatibility problems, if the PHP8 Version is running.

### Automation with Slave DNS

If you have a secondary DNS server in your infrastructure, you can utilize it with this software. The setup involves:

- **Integration with Existing DNS Server:** The software can be installed on your secondary DNS server, which already handles domain information.
  
- **Automatic Configuration:** By configuring `settings.php`, the software will fetch local domains from Bind9 files and save them into the database. It will then automatically configure Postfix through a cron job, setting up domains in the web interface without manual intervention.
  
- **Customizable Settings:** You can modify relay server settings and domain configurations as needed if the automatic setup isn't perfect.

This approach automates the integration of secondary DNS domains with minimal manual configuration.

### DNS Entry for 2nd Server

If you have a master mail server and this software running on a secondary server, follow these steps:

- **DNS Configuration:** Add a secondary MX record with a lower priority in your DNS settings. The hostname should point to the mail server where this software is installed.
  
- **Mail Handling:** If the master server is unavailable, incoming mail will be delivered to the secondary server, where it will be queued. Once the master server is back online, the queued mail will be forwarded to it based on the relay settings configured in the web interface.

- **User Management:** The web interface includes a basic user management and permission system for managing access.

### Urgent Information

Do never use this software in a running mail environment! It will break the mail configuration and function if you change configuration on a running plesk or other mail system! Only use on a dedicated server for this purpose (or maybe a secondary dns server). It is advised that you have some experience in mail administration if you use this script.


### Default Login for Webinterface
Change this data after login!
  
Username: admin  
Passwort: changeme

## 🛠️ Installation 

For installation instructions, please refer to our documentation, which can be found in the "Documentation" section of this README. You can access the instructions online at [https://bugfishtm.github.io/Mail-Relaying-over-DNS/installation.html](https://bugfishtm.github.io/Mail-Relaying-over-DNS/installation.html) or locally at [./docs/installation.html](./docs/installation.html).

## 📖 Documentation

The following documentation is intended for both end-users and developers.


| **Description**                                                       | **Link**                                                                                         |
|----------------------------------------------------------------------|-------------------------------------------------------------------------------------------------|
| A playlist or video related to this project. | [https://www.youtube.com/playlist?list=PL6npOHuBGrpBLUtcVpC5Wf1mvb3pawdXU](https://www.youtube.com/playlist?list=PL6npOHuBGrpBLUtcVpC5Wf1mvb3pawdXU)|
| If this repository contains a _videos folder, you can check that as well. | |
| Access the online documentation for this project. | [https://bugfishtm.github.io/Mail-Relaying-over-DNS/index.html](https://bugfishtm.github.io/Mail-Relaying-over-DNS/index.html)  |
| If you'd prefer to access the documentation locally, you can find it at. | [./docs/index.html](./docs/index.html) |

The following documentation is intended for developers.

| Description | Link  |
|----------------|----------------------------|
| Documentation for the integrated framework - for developers.                                                                                        | [https://bugfishtm.github.io/bugfish-framework/](https://bugfishtm.github.io/bugfish-framework/)  |

## ❓ Support Channels

If you encounter any issues or have questions while using this software, feel free to contact us:

- **GitHub Issues** is the main platform for reporting bugs, asking questions, or submitting feature requests: [https://github.com/bugfishtm/Mail-Relaying-over-DNS/issues](https://github.com/bugfishtm/Mail-Relaying-over-DNS/issues)
- **Discord Community** is available for live discussions, support, and connecting with other users: [Join us on Discord](https://discord.com/invite/xCj7AEMmye)  
- **Email support** is recommended only for urgent security-related issues: [security@bugfish.eu](mailto:security@bugfish.eu)

## 📢 Spread the Word

Help us grow by sharing this project with others! You can:  

* **Tweet about it** – Share your thoughts on [Twitter/X](https://twitter.com) and link us!  
* **Post on LinkedIn** – Let your professional network know about this project on [LinkedIn](https://www.linkedin.com).  
* **Share on Reddit** – Talk about it in relevant subreddits like [r/programming](https://www.reddit.com/r/programming/) or [r/opensource](https://www.reddit.com/r/opensource/).  
* **Tell Your Community** – Spread the word in Discord servers, Slack groups, and forums.  

## 📁 Repository Structure 

This table provides an overview of key files and folders related to the repository. Click on the links to access each file for more detailed information. If certain folders are missing from the repository, they are irrelevant to this project.

|Document Type|Description|
|----|-----|
| .github | Folder with github setup files. |
| [.github/CODE_OF_CONDUCT.md](./.github/CODE_OF_CONDUCT.md) | The community guidelines. |
| _changelogs | Folder for changelogs. |
| _images | Folder for project images. |
| _releases | Folder for releases. |
| _screenshots | Folder with project screenshots. |
| _source | Folder with the source code. |
| _videos | Folder with videos related to the project. |
| docs | Folder for the documentation. | 
| .gitattributes | Repository setting file. Only for development purposes. |
| .gitignore | Repository ignore file. Only for development purposes. |
| README.md | Readme of this project. You are currently looking at this file. |
| repository_reset.bat | File to reset this repository. Only for development purposes. |
| repository_update.bat | File to update this repository. Only for development purposes. |
| [CONTRIBUTING.md](CONTRIBUTING.md) | Information for contributors. | 
| [CHANGELOG.md](CHANGELOG.md) | Information about changelogs. | 
| [SECURITY.md](SECURITY.md) | How to handle security issues. |
| [LICENSE.md](LICENSE.md) | License of this project. |

## 📑 Changelog Information

Refer to the `_changelogs` folder for detailed insights into the changes made across different versions. The changelogs are available in **HTML format** within this folder, providing a structured record of updates, modifications, and improvements over time. Additionally, **GitHub Releases** follow the same structure and also include these changelogs for easy reference.

## 🌱 Contributing to the Project

I am excited that you're considering contributing to our project! Here are some guidelines to help you get started.

**How to Contribute**

1. Fork the repository to create your own copy.
2. Create a new branch for your work (e.g., `feature/my-feature`).
3. Make your changes and ensure they work as expected.
4. Run tests to confirm everything is functioning correctly.
5. Commit your changes with a clear, concise message.
6. Push your branch to your forked repository.
7. Submit a pull request with a detailed description of your changes.
8. Reference any related issues or discussions in your pull request.

**Coding Style**

- Keep your code clean and well-organized.
- Add comments to explain complex logic or functions.
- Use meaningful and consistent variable and function names.
- Break down code into smaller, reusable functions and components.
- Follow proper indentation and formatting practices.
- Avoid code duplication by reusing existing functions or modules.
- Ensure your code is easily readable and maintainable by others.

## 🤝 Community Guidelines

We’re on a mission to create groundbreaking solutions, pushing the boundaries of technology. By being here, you’re an integral part of that journey. 

**Positive Guidelines:**
- Be kind, empathetic, and respectful in all interactions.
- Engage thoughtfully, offering constructive, solution-oriented feedback.
- Foster an environment of collaboration, support, and mutual respect.

**Unacceptable Behavior:**
- Harassment, hate speech, or offensive language.
- Personal attacks, discrimination, or any form of bullying.
- Sharing private or sensitive information without explicit consent.

Let’s collaborate, inspire one another, and build something extraordinary together!

## 🛡️ Warranty and Security

I take security seriously and appreciate responsible disclosure. If you discover a vulnerability, please follow these steps:

- **Do not** report it via public GitHub issues or discussions. Instead, please contact the [security@bugfish.eu](mailto:security@bugfish.eu) email address directly.   
- Provide as much detail as possible, including a description of the issue, steps to reproduce it, and its potential impact.  

I aim to acknowledge reports within **2–4 weeks** and will update you on our progress once the issue is verified and addressed.

This software is provided as-is, without any guarantees of security, reliability, or fitness for any particular purpose. We do not take responsibility for any damage, data loss, security breaches, or other issues that may arise from using this software. By using this software, you agree that We are not liable for any direct, indirect, incidental, or consequential damages. Use it at your own risk.

## 📜 License Information

The license for this software can be found in the [LICENSE.md](LICENSE.md) file. Third-party licenses are located in the ./_licenses folder. The software may also include additional licensed software or libraries.

🐟 Bugfish 
