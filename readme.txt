=== Stop Donor Spam ===
Contributors: webdevmattcrom
Tags: spam
Requires at least: 2.7.0
Tested up to: 4.7.5
Stable tag: 1.2.0
License: GPLv2 or later
License URI: LICENSE

Check user registration info against the Stop Forum Spam database before allowing registration

== Description ==

Stop Signup Spam will prevent user registration of anyone trying to sign up with an the email address or IP address that has been reported to [Stop Forum Spam](http://stopforumspam.com/).

Contrary to what the name implies, Stop Forum Spam can be used with any web service, not just forums.

At this time, Stop Signup Spam integrates with:

* The core WordPress registration form
* Restrict Content Pro
* MemberPress
* Give

By installing this plugin, you consent to an API call sent from your WordPress site to Stop Forum Spam after every attempted user registration. The API call includes the user’s email address and IP address.

It is a similar concept to [Akismet](https://wordpress.org/plugins/akismet/), except instead of checking for blog comment spam, it checks for user signup spam.

Unlike Akismet, no API key is required to query Stop Forum Spam’s database. Partly because of this, I decided not to include a settings page in the Stop Signup Spam plugin. Just activate and you are all set.

The plugin does not log any user registration info. Any data sent to Stop Forum Spam would fall under their [privacy policy](https://www.stopforumspam.com/privacy).

Please note that Stop Forum Spam is a third-party service that I have no affiliation with. I just found it, could not find any existing WordPress plugins that worked with it, and built one myself.

It has helped me cut down on signup spam on a free membership site that I run, and I hope it will help you do the same.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/stop-signup-spam` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. That is it. There is no settings page.

== Frequently Asked Questions ==

= Stop Forum Spam thinks I am a spammer. Can you help me? =

No. I am not affiliated with Stop Forum Spam. You can request removal on their [Removal Request](http://stopforumspam.com/removal) page.

= I want to temporarily disable the plugin. Can you add a settings page so I can do that? =

No. Since the plugin has a very specific purpose, and I would like to keep setup as seamless as possible, it would be best if you deactivated the plugin if you wanted to temporarily disable it.

= I use another plugin to handle registrations to my WordPress site. Will you integrate with it? =

Please understand that this is a free plugin, which I built out of necessity because spammers were flooding my RCP-powered membership site with bogus registrations. I do not have time to integrate with every membership plugin under the sun.

With that said, I am not opposed to [pull requests](https://github.com/lelandf/stop-signup-spam) if you would like to contribute an integration of your own.

= A spammer signed up to my site even though this plugin was active. Why? =

At this time, all this plugin does is check the registration email address and IP against the Stop Forum Spam database. If either the email address or the IP address appear to be spam according to Stop Forum Spam, this plugin will not allow registration.

If Stop Forum Spam reports a false negative, feel free to [report it](http://stopforumspam.com/add) to them so they can have a more accurate database.

= Will your plugin ever do anything more than simply check email and IP addresses against Stop Forum Spam’s database? =

As I discover new, accessible ways to thwart signup spam, I may amend this plugin to reflect those. In the meantime, if you have any ideas, I am not opposed to [pull requests](https://github.com/lelandf/stop-signup-spam).

= Can I block spam bots from ever accessing my site with this plugin? =
No. It is probably best you block this traffic at the DNS level anyway. Services that provide web application firewalls, such as Cloudflare and Sucuri, may be able to help.

= I am confused. What is the difference between Stop Forum Spam and Stop Signup Spam? =
Stop Forum Spam is a third-party service that maintains a database of spam reports. Stop Signup Spam is a WordPress plugin that integrates with that service.

== Changelog ==

= 1.2.0 =
* Changed name to "Stop Donor Spam" to continue this fork independently
* Added validation on first name field to prevent email addresses from being used as the first name
* Added IP address collection on the donation details screen with links to Stop Registratration Spam website and IP info to act on potentially fraudulent donations.

= 1.1.0 =

Add Give integration. Props: https://github.com/lelandf/stop-signup-spam/pull/4

= 1.0.3 =

Add MemberPress integration. Props: https://wordpress.org/support/users/cartpauj/

= 1.0.2 =

Use HTTPS for Stop Forum Spam API URL to prevent HTTPS sites from throwing connection errors. Props: https://github.com/wolffe

= 1.0.1 =
* No longer trust HTTP_X_FORWARDED_FOR, which can be spoofed 

= 1.0.0 =
* Initial release
