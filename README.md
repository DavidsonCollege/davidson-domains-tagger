# Davidson Domains Community Portal

## About

This is a Wordpress plugin that exposes metadata about sites to [Site Maker](https://github.com/woodwardtw/sites), a Wordpress plugin that grabs data about Davidson Domains sites and displays them on the [Davidson Domains Community Portal](http://domains.davidson.edu/community).

### How it works

#### Plugin 1: Davidson Domains Tagger

`Davidson Domains Tagger` is the plugin that users install to their Wordpress websites. It lets `Site Maker` know that the user's site should appear in the Community Portal. Further, this plugin allows users to and allows users to provide `Site Maker` with meta data about the site, like content type, student graduation year, etc.

##### To install the plugin on a Wordpress site:

- User logs into their Wordpress site.
- User uploads the [plugin .zip file](https://github.com/DavidsonCollege/davidson-domains-meta/archive/master.zip) to their Wordpress site and [enables](https://codex.wordpress.org/Managing_Plugins) the plugin.
- In the admin panel, user travels to `Tools->Davidson-Domains-Meta` and selects the applicatable tags.
- Once selected, the plugin exposes these tags to api via the `/wp-json/` route. (Example: myblog.com/wp-json/)
- User waits a day or two for Site Maker to add or update their site on the Domains Community Portal.

##### ~~To update the tags displayed in the plugin:~~ OUTDATED

- Admin logs into GitHub and navigates to [/davidson-domains-meta/blob/master/tags.json](https://github.com/DavidsonCollege/davidson-domains-meta/blob/master/tags.json)
- Admin clicks on the pencil `edit this file`
- Admin makes changes to the JSON object, abiding by [JSON syntax](https://www.w3schools.com/js/js_json_syntax.asp) of the name/value pairs. The `name` of a name/value pair corresponds with a category. And the `value` (in this case, an array of strings) is a list of tags that relate to the category. For instance: `"What is your class year?": ["2011","2012","2013"]`.
- After admin makes changes, they run the changes through [JSONLint](https://jsonlint.com/) to ensure they have not made a fatal typo.
- Once changes are confirmed, admin clicks `Commit Changes`.
- The new (or removed) tag will appear across all instances of the plugin within about 5 minutes.

#### Plugin 2: Site Maker

This is the plugin that runs on the Community Portal. It searches Davidson Domains for sites with `Davidson Domains Tagger` plugin installed. If it finds a site with the plugin installed, it adds it to the Community Portal and tags the site according to the plugin's specification. It searches for new sites and updates old sites every 24 hours, however, an admin can trigger an update by navigating to `https://domains.davidson.edu/community/load`.

##### To Enable:

- Admin downloads [plugin .zip file](https://github.com/woodwardtw/sites/archive/master.zip).
- Admin logs into `domains.davidson.edu/community/wp-admin` and installs and enables the plugin.
- Admin ensures that the maximum script timeout is >15 minutes.
- Admin communicates with Reclaim Hosting to setup a cron job that dumps a list of websites registered in Davidson Domains into `/home/devreclaim/davidsoninstalls.json` of the Community Portal file system.
