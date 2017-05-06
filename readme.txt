=== Stop Signup Spam ===
Contributors: lelandf
Tags: spam
Requires at least: 2.7.0
Tested up to: 4.7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: LICENSE

Check email addresses from new registrations against the Stop Forum Spam database.

== Description ==

Stop Signup Spam will prevent user registration of anyone trying to sign up with an the email address that has been reported to [Stop Forum Spam](http://stopforumspam.com/).

Contrary to what the name implies, Stop Forum Spam can be used with any web service, not just forums.

At this time, Stop Signup Spam integrates with:

* The core WordPress registration form
* Restrict Content Pro

There is no settings page. Just activate and you are all set.

By installing this plugin, you consent to an API call sent from your WordPress site to Stop Forum Spam after every attempted user registration. It is a similar concept to [Akismet](https://wordpress.org/plugins/akismet/), except instead of checking for blog comment spam, it checks for user signup spam.

Unlike Akismet, no API key is required to query Stop Forum Spam’s database.

Please note that Stop Forum Spam is a third-party service that I have no affiliation with. I just found it, could not find any existing WordPress plugins that worked with it, and built one myself.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/stop-spam-signups` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. That is it. There is no settings page.

== Frequently Asked Questions ==

= Stop Forum Spam thinks my email address is spam. Can you help me? =

No. I am not affiliated with Stop Forum Spam. You can request removal on their [Removal Request](http://stopforumspam.com/removal) page.

= I want to temporarily disable the plugin. Can you add a settings page so I can do that? =

No. Just deactivate the plugin to temporary disable it.

= I use another plugin to handle registrations to my WordPress site. Will you integrate with it? =

Please understand that this is a free plugin, which I built out of necessity because spammers were flooding my RCP-powered membership site with bogus registrations. I do not have time to integrate with every other membership plugin under the sun. With that said, I am not opposed to [pull requests](https://github.com/lelandf/stop-spam-signups) if you would like to contribute an integration of your own.

= A spammer signed up to my site even though this plugin was active. Why? =

At this time, all this plugin does is check the registration email address against the Stop Forum Spam database. Nothing more. If Stop Forum Spam reports a false negative, feel free to [report it](http://stopforumspam.com/add) to them so they can have a more accurate database.

= Will your plugin ever do anything more than simply check email addresses against Stop Forum Spam’s database? =

As I discover new, accessible ways to thwart spam signups, I may amend this plugin to reflect those. In the meantime, if you have any ideas, I am not opposed to [pull requests](https://github.com/lelandf/stop-spam-signups).

= I am confused. What is the difference between Stop Forum Spam and Stop Signup Spam? =
Stop Forum Spam is a third-party service that maintains a database of spam reports. Stop Signup Spam is a WordPress plugin that integrates with that service.

== Changelog ==

= 1.0 =
* Initial release
