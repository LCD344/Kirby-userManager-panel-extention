# Kirby User Manager Panel Extention

![Version](https://img.shields.io/badge/version-0.4.5-green.svg) ![License](https://img.shields.io/badge/license-MIT-green.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-2.3%2B-red.svg)

*Version 0.4.5*

This plugin adds a user manager pages to the kirby panel. It uses datatables to allow you to search and filter your users, it also allows you to decide on a   custom user folder (To seperate users from admins and editors), also if you use the custom folder you can have usernames that include @ and .

Also there are a bunch of extentions planned the will make this panel extention even more useful.

## Installation

Use one of the alternatives below.

### 1. Kirby CLI

If you are using the [Kirby CLI](https://github.com/getkirby/cli) you can install this plugin by running the following commands in your shell:

```
$ kirby plugin:install lcd344/Kirby-userManager-panel-extention
```

### 2. Clone or download

1. [Clone](https://github.com/lcd344/Kirby-userManager-panel-extention.git) or [download](https://github.com/lcd344/Kirby-userManager-panel-extention/archive/master.zip)  this repository.
2. Unzip the archive if needed and rename the folder to `userManager`.

**Make sure that the plugin folder structure looks like this:**

```
site/plugins/userManager/
```

### 3. Git Submodule

If you know your way around Git, you can download this plugin as a submodule:

```
$ cd path/to/kirby
$ git submodule add https://github.com/lcd344/Kirby-userManager-panel-extention site/plugins/userManager
```

## Setup

This plugin works out of the books by adding a User Manager widget to your panel.

Current list of extensions:

[Mailer](https://github.com/LCD344/kirby-mailer-wrapper) - Installing this plugin will add a primitive mailer inside the panel that will work with your users, when you click the "send email" button in a user's screen the mailer will open, mail options depend on setup described below.


## Usage

You can use the panel side normally - all the options are identical to the kirby users page except for the fact the users are in a [datatable](https://datatables.net/).

On the site you can get a user instance (in case you want to use an extension or you use a custom folder) by instatiating a new user instance with

```php
new lcd344\user($username)
```

You can also get a users collection with

```php
new lcd344\users()
```

If a user has a field called expiration, then when you try to log in the user:

```php
$user = new lcd344\user($username);
$user->login($password);
```
Then the login function will return false if the current date is bigger than the user expiration field.

#### Modules:

###### Mailer
If you have the Mailer extension then you can also use the next syntax to email the user

```php
$user->createMail() //this line returns a Mailer instance with the $to field set to the users email you can then use any method on the Mailer class
    ->attach($page->file('bla.log')->root())
    ->send("subject","body");
    
//equally you can do
$mailer = $user->createMail($driver,$options)
$mailer->send('subject','body');
```

The data of the user will be automatically bound to the mailer and you can inject it to the body using mustaches.
 For example the next code snippet will email the username to the user's email.

```php
$user->createMail() 
    ->send("subject","{{username}}");    
```

Note - the attachments in the mailer screen in panel only work with phpmailer driver.

For the full instructions for the Mailer go to the [Mailer](https://github.com/LCD344/kirby-mailer-wrapper) git page.


###### Database

Now you can save users into a database! For now we are limited only to a table called "users" but I will look into it (or you can edit the source code just a little bit).

I rewrote the lcd344/user and lcd344/users classes to keep the same API that kirby naturally uses, to prevent breakages. But this db layer is built on top of [illuminate/database](https://github.com/illuminate/database) which is an excellent ORM in my opinion. 

If you want to use the ORM directly, then you can use it with UserModel class for example

```php
UsersModel::where('username','john')->first();
```

Full documentation of the ORM is in [Laravel eloquent](https://laravel.com/docs/5.3/eloquent)

Details about enabling the bd are in the options section of this readme.

## Options

The following options can be set in your `/site/config/config.php` file:

User Manager in general

```php
c::set("userManager.folder","users"); // set a custom folder

c::set("userManager.fields",[ //
    "Username" => ["name" => "Username", 'action' => "edit", 'element' => "strong", 'class' => "item-title"],
    "Email" => ['name' => "Email", 'action' => ((class_exists(Mailer::class)) ? "email" : "edit")],
    "Role" => "Role"
])
```

Mailer extension

```php
c::set('userManager.mailer',false); // Turn of the panel side of the mailer extension.
c::set('userManager.mailer.editor','yakme'); // set the mailers body field (only with mailer extention)
c::set('userManager.mailer.drivers',["phpmailer" => "PHP Mailer","log" => "Logger"]); //set which drivers are availble to the mailer (by default it will include every possible one)
```

Database Extention

```php
	c::set("userManager.database",true); //enable extension, defult false
	c::set("userManager.database.driver","mysql"); //db driver, available are: mysql, sqlite and pgsql, defults to mysql
	c::set("userManager.database.host","localhost"); // db host, default localhost
	c::set("userManager.database.db","userManager"); // database name
	c::set("userManager.database.username","user"); // database username
	c::set("userManager.database.password","pass"); // database password

    // you need to set the next thing if you intend on using structures in the user form. The field in the db will have to be of type text.
    
	c::set("userManager.database.structures",[
		'structureFieldName1' => 'array',
		'structureFieldName2' => 'array'
		...
	]);

```

## Notable Options


```php
c::set("userManager.fields",[ 
    "Avatar" => "Avatar",
    "Username" => ["name" => "Username", 'action' => "edit", 'element' => "strong", 'class' => "item-title"],
    "Email" => ['name' => "Email", 'action' => ((class_exists(Mailer::class)) ? "email" : "edit")],
    "Role" => "Role"
])
```

This option gets an array of the fields to show in the datatable.
There are two options:
1) A normal key value pair, in this case, the kay represents the text at the top of the table, the value is the name of the value on the user object.
2) A key and an array, the key is the label at the top of the table, while the array sets up the display element.
 - The name will stand for the name on the object.
 - Action will create a link (you can choose email or edit)
 - Element will be the element type
 - Class will be the elements class. (this is available only when specifying element).
 
 All of the options are optional. Note: Avatar value is set especially to display the avatar.

## Changelog

**0.4.5**
- Added full structure support to all users.
- Added DB extension.

**0.3.1**

- Automatically bound user's data to email when using mailer. 

**0.3.0**

- Added an option to set datatable fields
- Added User expiration support

**0.26.5**

 - Fixed datatable pagination problem

**0.26.0**

- Option to turn off the mailer panel page, even if the extension exists

**0.25.0**

- Added a readme
- Added support for mailer extension
- Reorganized folder structure

## Todo

- [ ] Mailchimp Extension
- [ ] Stripe Extension
- [x] Users in db
- [ ] Include user permissions once kirby 2.4 is released
- [x] Set table fields from options
- [x] Add user expiration field
- [x] Enable/disable mailer page from options

## Requirements

- [**Kirby**](https://getkirby.com/) 2.3+

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/username/plugin-name/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
