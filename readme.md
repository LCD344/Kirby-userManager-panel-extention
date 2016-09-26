# Kirby User Manager Panel Extention

![Version](https://img.shields.io/badge/version-0.25.0-green.svg) ![License](https://img.shields.io/badge/license-MIT-green.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-2.3%2B-red.svg)

*Version 0.25.0*

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

[Mailer](https://github.com/LCD344/kirby-mailer-wrapper) - Installing this plugin will add a premitive mailer that will work with your users, when you click the "send email" button in a user's screen the mailer will open, mail options depend on setup described below.


## Usage

You can use the panel side normally - all the options are identical to the kirby users page except for the fact the users are in a [datatable](https://datatables.net/).

On the site you can get a user instance (in case you want to use an extension or you use a custom folder) by instatiating a new user instance with

```php
new lcd344\user()
```

You can also get a users collection with

```php
new lcd344\users()
```

If you have the Mailer extension then you can also use the next syntax to email the user

```php
$user->createMail() //this line returns a Mailer instance with the $to field set to the users email you can then use any method on the Mailer class
    ->attach($page->file('bla.log')->root())
    ->send("subject","body");
    
//equally you can do
$mailer = $user->createMail($driver,$options)
$mailer->send('subject','body');
```

Note - the attachments in the mailer screen in panel only work with phpmailer driver.

For the full instructions for the Mailer go to the [Mailer](https://github.com/LCD344/kirby-mailer-wrapper) git page.

## Options

The following options can be set in your `/site/config/config.php` file:

```php
c::set("userManager.folder","users"); // set a custom folder

c::set('userManager.mailer.editor','yakme'); // set the mailers body field (only with mailer extention)
c::set('userManager.mailer.drivers',["phpmailer" => "PHP Mailer","log" => "Logger"]); //set which drivers are availble to the mailer (by default it will include every possible one)

```



## Changelog

**0.25.0**

- Added a readme
- Added support for mailer extension
- Reorganized folder structure

## Todo

- [ ] Mailchimp Extension
- [ ] Stripe Extension
- [ ] Users in db
- [ ] Set table fields from options
- [ ] Enable/disable mailer page from options

## Requirements

- [**Kirby**](https://getkirby.com/) 2.3+

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/username/plugin-name/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.