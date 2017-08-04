# Android-Translator
A web-based tool to quickly and easily create translations for your android app.
![Screenshot](http://i.imgur.com/kq8r320.png)
### Features
* no signup required for users
* supports xml comments to help explain each string to your translators 
* auto-saves progress
* strips strings where  translatable="false"
### Limitations
* currently no string-array support

#### How it works
The script scans all the files and folders placed in /xml/. Anything in /xml/values/ will be used as the base language for translation. Other languages will fill in the text boxes so the user can modify any existing translations for the app.
##### Tabs
Each file corrosponds to a tab in the app. 'strings_' is stripped from the title for convenience:
![Screenshot](http://i.imgur.com/epzI1Po.png)
Becomes:
![Screenshot](http://i.imgur.com/gSlkrzh.png)

##### Comments
Add comments to any string.

![Screenshot](http://i.imgur.com/sjvNPTH.png)
![Screenshot](http://i.imgur.com/5oUvtiT.png)

##### Exporting as XML
Once the user clicks submit on the last page, the xml will be created and emailed to the address coded in submit.php

### Set up instructions
1. Delete everything in /xml/ and replace with your own string resource files.
2. Edit index.php changing the app name to your own, the email address and any text you wish to edit.
3. Edit submit.php to configure PHPMailer to work with your server, and to send to your own email address.

### Contributing
Feel free to refactor this and clean it up! Any commits welcome.