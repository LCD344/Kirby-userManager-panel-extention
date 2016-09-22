# Kirby User Manager Panel Extention

This is a panel extention that allows you to see users in a datatable. it also allows you to set a custom folder for users (in case you don't want to mix your users with your admins) - lastly if you use a custom folder you can use @ and . in user names.

It will add a widget to your panel that will link you to it. from there everything functions like normal users page.
To access the users you will need to use lcd344/User and lcd344/Users classes.

## Installation

Put the contents of the folder in the site/plugins/userManager folder of your project

or using the kirby cli you can write

````
kirby plugin:install lcd344/Kirby-userManager-panel-extention
````

##settings

To set up a custom folder, you will need to create it as a folder in the site folder and set up the next line in the config page

````
c::set("userManager.folder", userFolderName);
````

## Todo

 * [ ] stripe integration
 * [ ] programmatically email users easily 
 * [ ] set which fields you want to show in the datatable
 * [ ] Other suggestions?


