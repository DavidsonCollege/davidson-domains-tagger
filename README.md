# Davidson Domains Community Portal

## About
This is a Wordpress plugin that exposes metadata about sites to [Site Maker](https://github.com/woodwardtw/sites), a Wordpress plugin that grabs data about Davidson Domains sites and displays them on the [Davidson Domains Community Portal](http://domains.davidson.edu/community).

### How it works

#### Plugin 1: Davidson Domains Tagger

[TODO: background information on plugin 1]

##### To Enable:
* User logs into their Wordpress site.
* User uploads the [plugin .zip file](https://github.com/DavidsonCollege/davidson-domains-meta/archive/master.zip) to their Wordpress site and [enables](https://codex.wordpress.org/Managing_Plugins) the plugin.  
* In the admin panel, user travels to `Tools->Davidson-Domains-Meta` and selects the applicatable tags.
* Once selected, the plugin exposes these tags to api via the `/wp-json/` route. (Example: myblog.com/wp-json/)
* User waits a day or two for Site Maker to add or update their site on the Domains Community Portal.

#### Plugin 2: Site Maker

[TODO: background information on plugin 1]

##### To Enable:
* Admin downloads [plugin .zip file](https://github.com/woodwardtw/sites/archive/master.zip).
* Admin logs into `domains.davidson.edu/community/wp-admin` and installs and enables the plugin.
* Admin ensures that the maximum script timeout is >15 minutes. (TODO: link out to a how to.)
* Admin communicates with reclaim hosting to setup a cron job that dumps a list of Davidson Domains to `/home/devreclaim/davidsoninstalls.json` in `domains.davidson.edu/community`

----

## FAQ
How do we update the tags in the plugin?
What if we wanted to add a new section to the tags?
How can we manually refresh the list of sites?
How often does the cron job run?
What if something breaks?
