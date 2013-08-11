chris.lu
========

https://chris.lu

My personal development blog, using PHP and the Zend Framework 1 for the backend. The frontend uses the Twitter Bootstrap 3 and and jQuery Mobile 1.4.

In production the blog is hosted on NGINX with php-fpm and apc cache. The database is MongoDB.

Install
-------

In "/application/configs/", rename the "application_default.ini" into "application.ini" and use your own values where needed

Do the same for each config.xml in "/application/modules/MODULE_NAME/configs/"

Create the folder "caches", "searchindexes" and "logs" in the "application" folder and in the "/public/" folder create an "upload" folder

Put a copy of the zend framework 1 into "/library/Zend/" (this repository got tested using zf1 1.12.3)

Check out the dev-vhost.txt file to setup an apache development vhost

To update the javascript packages check out the updating.txt file

TODO
----

* DONE: upgrade Twitter Bootstrap to version 3
* DONE: upgrade jQuery mobile to version 1.4
* DONE: responsive layout improvements, for example the main area is too small on tablets
* DONE: use Zend_Feed to read GitHub activity feed
* use GitHub API to retrieve repositories list from GitHub (with zend file cache)
* use Stackoverflow API to retrieve some public data (with zend file cache)
* add zend file cache for GitHub feed in projects module
* rewrite of the bookmarks module that is useless as it is right now
* comments system for articles (remove disqus)
* form to allow visitors to suggest bookmarks, article topics and readinglist entries

TODO (maybe):
-------------

* update / improve mongodb models
* improve tags system
* create about page
* add the option to switch to MariaDB
* upgrade to zf2 if I have time to do it some free time left in the future ;)