=== WP Photo Album Plus ===
Contributors: opajaap
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=OpaJaap@OpaJaap.nl&item_name=WP-Photo-Album-Plus&item_number=Support-Open-Source&currency_code=USD&lc=US
Tags: photo, album, photoalbum, gallery, slideshow, sidebar widget, photowidget, photoblog, widget, qtranslate, cubepoints, multisite, network, lightbox, comment, watermark, iptc, exif, responsive, mobile
Version: 4.9.18
Stable tag: trunk
Author: J.N. Breetvelt
Author URI: http://www.opajaap.nl/
Requires at least: 3.1
Tested up to: 3.5

This plugin is designed to easily manage and display your photo albums and slideshows in a single as well as in a network WordPress site.

== Description ==

This plugin is designed to easily manage and display your photo albums and slideshows within your WordPress site. 

* You can create various albums that contain photos as well as sub albums at the same time.
* There is no limitation to the number of albums and photos.
* There is no limitation to the nesting depth of sub-albums.
* You have full control over the display sizes of the photos.
* You can specify the way the albums are ordered.
* You can specify the way the photos are ordered within the albums, both on a system-wide as well as an per album basis.
* The visitor of your site can run a slideshow from the photos in an album by a single mouseclick.
* The visitor can see an overview of thumbnail images of the photos in album.
* The visitor can browse through the photos in each album you decide to publish.
* Individual thumbnails and slides can be linked to off site urls.
* You can add a Photo of the day Sidebar Widget that displays a photo which can be changed every hour, day or week.
* You can add a Search Sidebar Widget which enables the visitors to search albums and photos for certain words in names and descriptions.
* You can enable a rating system and a supporting Top Ten Photos Sidebar Widget that can hold a configurable number of high rated photos.
* You can enable a comment system that allows visitors to enter comments on individual photos.
* You can add a recent comments on photos Widget.
* Apart from the full-size slideshows you can add a Sidebar Widget that displays a mini slideshow.
* There is a widget to display a number of most recently uploaded photos. It can be configured systemwide and/or on an album basis.
* There is a General Purpose widget that is a text widget wherein you can use wppa+ script commands.
* There is an album widget that displays thumbnail images that link to album contents.
* There is a QR code widget that will be updated when the content of the page changes.
* There is a tag cloud widget and a multi tag widget to quickly get a selection of photos with (a) certain tag(s).
* Almost all appearance settings can be done in the settings admin page. No php, html or css knowledge is required to customize the appearence of the photo display.
* International language support for static text: Currently included foreign languages files: Dutch, Japanese, French(outdated), Spanish, German.
* International language support for dynamic text: Album and photo names and descriptions fully support the qTranslate multilanguage rules.
* Suports lightbox 3.
* You can add watermarks to the photos.
* The plugin supports IPTC and EXIF data.
* Supports WP supercache. The cache will be cleared whenever required for wppa+.
* Supports Cube Points. You can assign points to comments and votes.

Plugin Admin Features:

You can find the plugin admin section under Menu Photo Albums on the admin screen.

* Photo Albums: Create and manage Albums.
* Upload photos: To upload photos to an album you created.
* Import photos: To bulk import photos to an album that are previously been ftp'd.
* Settings: To control the various settings to customize your needs.
* Sidebar Widget: To specify the behaviour for an optional sidebar photo of the day widget.
* Help & Info: Much information about how to...

Translations:

There are translations in many languages. The frontend and admin sides are separately translatable. 
* Dutch translation by OpaJaap himself (<a href="http://www.opajaap.nl">Opa Jaap's Weblog</a>) (both)
* Slovak translation by Branco Radenovich (<a href="http://webhostinggeeks.com/user-reviews/">WebHostingGeeks.com</a>) (frontend)
* Polish translation by Maciej Matysiak (both)

== Installation ==

= Requirements =

* The plugin requires at least wp version 3.1.
* The theme should have a call to wp_head() in its header.php file and wp_footer() in its footer.php file. 
* The theme should load enqueued scripts in the header if the scripts are enqueued without the $in_footer switch (like wppa.js and jQuery). 
* The theme should not prevent this plugin from loading the jQuery library in its default wp manner, i.e. the library jQuery in safe mode (uses jQuery() and not $()). 
* The theme should not use remove_action() or remove_all_actions() when it affects actions added by wppa+.
Most themes comply with these requirements. 
However, check these requirements in case of problems with new installations with themes you never had used before with wppa+ or when you modifies your theme.
* The server should have at least 32MB of memory, 64MB or more is recommended.

= Upgrade notice =
This version is: Major rev# 4, Minor rev# 9, Fix rev# 6, Hotfix rev# 000.
If you are upgrading from a previous Major or Minor version, note that:
* If you modified wppa_theme.php and/or wppa_style.css, you will have to use the newly supplied versions. The previous versions are NOT compatible.
* If you set the userlevel to anything else than 'administrator' you may have to set it again. Note that changing the userlevel can be done by the administrator only!
* You may have to activate the sidebar widget again.

= Standard installation when not from the wp plugins page =
* Unzip and upload the wppa plugin folder to wp-content/plugins/
* Make sure that the folder wp-content/uploads/ exists and is writable by the server (CHMOD 755, some systems need CHMOD 777)
* Activate the plugin in WP Admin -> Plugins.
* If, after installation, you are unable to upload photos, check the existance and rights (CHMOD 755, some systems need CHMOD 777) of: 
for the single site mode installation: the folders .../wp-content/uploads/wppa/ and .../wp-content/uploads/wppa/thumbs/, 
and for the multisite mode installation (example for blog id 92): the folders path: .../wp-content/blogs.dir/92/wppa/ and .../wp-content/blogs.dir/92/wppa/thumbs/.
In rare cases you will need to create them manually. You can see the actual pathnames and urls in the lowest table of the Photo Albums -> Settings page.
* If you upgraded from WP Photo Album (without plus) and you had copied wppa_theme.php and/or wppa_style.css 
to your theme directory, you must remove them or replace them with the newly supplied versions. The fullsize will be reset to 640 px. 
See Table I-A1 and Table I-B1,2 of the Photo Albums -> Settings admin page.

== Frequently Asked Questions ==

= Which other plugins do you recommand to use with WPPA+, and which not? =

* Recommanded plugins: qTranslate, WP Super Cache, Cube Points, Simple Cart & Buy Now.
* Plugins that break up WPPA+: My Live Signature.
* Google Analytics for WordPress will break the slideshow in most cases when *Track outbound clicks & downloads:* has been checked in its configuration.

= Which themes have problems with wppa+ ? =

* Photocrati has a problem with the wppa+ embedded lightbox when using page templates with sidebar.

= Are there special requirements for responsive (mobile) themes? =

* Yes! Go to the Photo Albums -> Settings admin page. Enter *auto* in Table I-A1. Lowercase letters, no quotes.
* Do not use %%size=[any number]%%, unless you want a fixed width display. This setting is inherited to the next %%wppa%%, 
so use %%size=auto%% in the next %%wppa%% occurrence to go back to automatic.
* If you use the Slideshow widget, set the width also to *auto*, and the vertical alignment to *fit*.
* You may also need to change the thumbnail sizes for widgets in *Table I-F 2,4,6 and 8*. Set to 75 if you want 3 columns in the theme *Responsive*.

= After update, many things seem to go wrong =

* After an update, always clear your browser cache (CTRL+F5) and clear your temp internetfiles, this will ensure the new versions of js files will be loaded.
* And - most important - if you use a server side caching program (like WP Total Cavhe) clear its cache. 
* Make sure any minifying plugin (like W3 Total Cache) is also reset to make sure the new version files are used.
* Visit the Photo Albums -> Settings page -> Table VII-A1 and press Do it!
* When upload fails after an upgrade, one or more columns may be added to one of the db tables. In rare cases this may have been failed. 
Unfortunately this is hard to determine. 
If this happens, make sure (ask your hosting provider) that you have all the rights to modify db tables and run action Table VII-A1 again.

= What do i have to do when converting to multisite? =

* After the standard WP conversion procedure the photos and thumbnails must be moved to a different location on the server.
You have to copy all files and subdirectories from .../wp-content/uploads/wppa/ to .../wp-content/blogs.dir/1/wppa/
This places all existing photos to the 'upload' directory that belongs to blog id 1.
Make sure the files are accessable by visitors (check CHMOD and .htaccess).
Further, activate the plugin for all other blogs that require it.

= How does the search widget work? =

* A space between words means AND, a comma between words means OR.
Example: search for 'one two, three four, five' gives a result when either 'one' AND 'two' appears in the same (combination of) name and description. 
If it matches the name and description of an album, you get the album, and photo vice versa.
OR this might apply for ('three' AND 'four') OR 'five'. Albums and photos are returned on one page, regardless of pagination settings, if any. 
That's the way it is designed.

= How can i translate the plugin into my language? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=1349

= How do i install a hotfix? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=823

= What to do if i get errors during upload or import photos? =

* It is always the best to downsize your photos to the Full Size before uploading. It is the fastest and safest way to add photos tou your photo albums.
Photos that are way too large take unnessesary long time to download, so your visitors will expierience a slow website. 
Therefor the photos should not be larger (in terms of pixelsizes) than the largest size you are going to display them on the screen.
WP-photo-album-plus is capable to downsize the photos for you, but very often this fails because of configuration problems. 
Here is explained why:
Modern cameras produce photos of 7 megapixels or even more. To downsize the photos to either an automaticly downsized photo or
even a thumbnail image, the server has to create internally a fullsize fullcolor image of the photo you are uploading/importing.
This will require one byte of memory for each color (Red, Green, Blue) and for every pixel. 
So, apart form the memory required for the server's program and the resized image, you will need 21 MB (or even more) of memory just for the intermediate image.
As most hosting providers do not allow you more than 64 MB, you will get 'Out of memory' errormessages when you try to upload large pictures.
You can configure WP to use 128 MB (That would be enough in most cases) by specifying *define('WP_MEMORY_LIMIT', '128M');* in wp-config.php, 
but, as explained earlier, this does not help when your hosting provider does not allows the use of that much memory.
If you have control over the server yourself: configure it to allow the use of enough memory.
Oh, just Google on 'picture resizer' and you will find a bunch of free programs that will easily perform the resizing task for you.


== Changelog ==

See for additional information: http://wppa.opajaap.nl/?page_id=1459

= 4.9.18 =

= Bug Fixes =

* Ajax new url from a thumbnail to a slideshow is now also converted to pretty if pretty links is on.

= New Features =

* The inside page history tracking in modern browsers ( not being IE ) can now be switched off in Table IV-A4: Update addressline.
* A spinner is displayed during an ajax action so visitors know that there is something happening after their mouseclick.

= 4.9.17 =

= Bug Fixes =

* Fixed some obsolete information in the Help & Info admin page; changed the scripts to the shortcodes, to help beginners into the right direction.

= Other Changes =

* You can now put your customized wppa-style.css in a child themes directory ( stylesheet directory ). 
This will have the highest priority. If not found there, the themes dir wil be searched ( template directory ).
If still nothing: the wppa-style.css in the plugins dir wil be used (standard).
* Language files update.

= 4.9.16 =

= Other Changes =

* Cosmetic changes to Album admin for non administrators

= 4.9.15 =

= Bug Fixes =

* Create new empty album stopped working. Fixed.

= 4.9.14 =

= Bug Fixes =

* All widget contents end with a div clear:both now.
* The stars of the rating have display: inline now.

= New Features =

* Sub albums can be created on the Album admin table.
* You can limit the number of albums a user not having administrator rights can create in Table IX-B8.
* Table IX-B9: if checked: selecting alt thumbsize in album admin requires administrator rights.
* Table IX-B10: if checked: selecting a link page in album admin requires administrator rights.

= Other Changes =

* If owner only is on, a user not having administrator rights will only see the flat album table.
* If owner only is on, grant an album is on and a grant parent is defined, the user not having administrator rights can no longer create top-level albums.
* The ownership of an album can only be changed by an administrator.
* If owner only is on, a user not having administrator rights can set the parent of his albums only to other albums he owns or to a public album.

= 4.9.13 =

= New Features =

* Pagination on Manage photos. Pagesize settable in Table IX-A14.
* Page size of Comment admin settable in Table IX-A15.
* Default tags on album basis.
* Treecount. Table II-D5. If checked: displays on the album cover in parenthesis the total number of albums and photos in the album subtree if the numbers differ from the content of the album.

= Other Changes =

* Cosmetic and performance improvements Comment Admin.

= 4.9.12 =

= Bug Fixes =

* On admin upload: If upload moderate is off and the user has no album admin rights, the initial status of new uploads was pending, is now publish.
* On admin upload: The checkboxes 'After upload go to the edit album admin page' will read 'After upload go to the edit photo admin page' if the user has no album admin rights and will work as indicated.
* On admin upload: Box C ( Upload zip ) will only show up now if the user has import rights.
* On admin upload: If no album is selected, a warning will be displayed and no upload done before an unknown album error message is displayed.
* Fixed erroneous return url after frontend upload. The visitor will come back to the same page in all circumstances.
* Fixed the fact that a photo number in the querystring was applied to all slideshows on the page that contained the photo. 
Now it works only on the occurrence it is intented for.
* Shortcodes are no longer displayed in share descriptions.

= New Features =

* Implemented an interface to the plugin **Simple Cart And Buy Now**, enabling a smooth co-operation of a shopping cart system.
See <a href="http://wppa.opajaap.nl/simple-cart-and-buy-now/">This documentation page</a> for detailed information.

= Other Changes =

* The covers of empty albums will only be displayed for administrators and for the owner of the albums.

= 4.9.11 =

= Other Changes =

* Setting IX-A7 ( Allow foreign shortcodes ) has been split into 7.1: for fullsize (slideshow) descriptions and 7.2: for thumbnails (in an album view).
Shortcodes that are registered by add_shortcode() will be expanded where allowed in Table IX, or removed where not. 
Shortcodes that are not registerd will display as is.

= 4.9.10 =

= Bug Fixes =

* Due to a typo it was not possible to restore a settings back-up file. Fixed.

= New Features =

* The visitor can now select wether photos should have *all* tags (and) or *any* tag (or) set in the Photo Tags Filter widget.
* Setting Table II-A1: Show breadcrumb, has been split into 1a (for posts) and 1b (for pages).
* If Table IX-C2.1 is checked, searching will include the tags to be searched.
* HTML img alt attribute type can be set in Table IX-A13.

= 4.9.9 =

= Bug Fixes =

* Under some circumstances like in the Photo Tags widget, the querystring was not interpreted. Fixed.

= 4.9.8 =

= Bug Fixes =

* Fixed a spurious error that caused the shortcode generator to display an empty dialogbox (pathed during 4.9.7).
* Under some circumstances the language setting was lost during navigation. Fixed.

= New Features =

* If Table II-B5.1 is checked: add the uploaders display name in parenthesis to the photo name in the slideshow photo name box.
* If Table II-C1.1 is checked: add the uploaders display name in parenthesis to the photo name under the thumbnail.
* Comment notify ( Table IV-F5 ) has been extended with 3 new selection options: Uploader, Uploader and admin, Uploader and Albumowner. 
There will be no duplicate emails to the same user when there is more than one reasons to send him an email notification.
* If Table II-A9 is checked: display in the breadcrumb box an iconic link to a thumbnail display of the photos in a slideshow.

= 4.9.7 =

= Bug Fixes =

* When the shortcode specifies a slideshow, and rating does not use ajax, the returnurl showed a single image in stead of the slideshow. Fixed.
* The number of comments shown on the thumbnail popup is now for the approved comments only.
* The selection list for album in upload box A is now properly filtered on albums that has the user access to.

= New Features =

* If Checked in Table VII-B5, Any logged in user with upload rights can edit the photo information of his own photos, regardless of album admin rights.
If he has no album admin rights, he will have a menuitem: *Edit Photos* that enables him to edit all his photos.

= Other Changes =

* Table VII-B has been renumbered.
* Pagination now also works for searchresults and selecting on tags.

= 4.9.6 =

= Bug Fixes =

* Zooming out in the browser while the albums are displayed in more than one column corrupts the display. Fixed.
* On some installations it was no longer possible to create new albums. Fixed now also for Grant an Album.

= 4.9.5 =

= Bug Fixes =

* On some installations it was no longer possible to create new albums. Fixed.

= Other Changes =

* Cosmetic changes to notification emails
* Language files updates and new languages

= 4.9.4 =

= Bug Fixes =

* Cube points frontend upload was calculated wrong. Fixed.
* Fixed (removed) CR for proper utf8 encoding of email notifications.
* The texts *Average rating* and *My rating* now also have shorthands for small container widths.

= New Features =

* There is a red cross in the rating bar. Clicking will register a 'dislike'. 
After a certain 'dislikes' by different users, admin will receive a notification email.
This feature is configurable in Table II-B7.1. Entering 0 will disble this feature. Default is 5.
* You can display the number of comments on the popup image ( Table II-C6.1 )
* You can specify that admin receives a notification by email at a frontend photo upload. ( Table VII-B3.0 ).

= Other Changes =

* On entering the Settings page, a check is done if the selected linkpages still exist.
* Cosmetic changes to email notifications.

= 4.9.3 =

= Bug Fixes =

* Fixed a few translatable sentences.
* Fixed a few cosmetic and security issues around multitags and empty slideshows.
* Fixed a pagination problem with multitag thumbnail results.

= Other Changes =

* Added header data in notification emails for charset utf-8
* Added Polish language files, updated others.

= 4.9.2 =

= Bug Fixes =

* When the selection in a slideshow is #all ( all photos ), 
refreshing the page or returning from voting or commenting resulted in a slideshow with the photos of the photos album only. Fixed.
* The input to the Search Widget is now properly filterd to prevent cross-site scripting attacks.

= New Features =

* There is a new linktype from thumbnails ( generic thumbnails, tumbnail-, lasten-, topten- and comten-widget thumbnails ) to <i>the single photo in the style of a slideshow</i>.
* There is a new widget: <strong>Photo Tags Filter</strong> that allows the visitor to select photos having more than one tags at the same time.

= 4.9.1 =

= Bug Fixes = 

* Admin will have the Edit link now always in the fullsize photo description.
* Fixed breadcrumb bug preventing the display of album=#all contents.

= New Features =

* Cube Points added for frontend uploads (Table IX-F3).

= Other Changes =

* The supply of an album id that does not exist will always produce an errormessage rather than processing the default generic album.
* Orphan albums will automaticly have the parent set to --- separate --- on entering the album admin page.
* Cover linkpage will automaticly be reset to --- same page or post --- when the page is moved to trash on entering the album admin page.

= 4.9.0 =

= Bug Fixes =

* The filmstrip content is now adjusted when the size of the container changes.
* Fixed an admin hang when there was a deleted parentalbum and Table IX-A12 was checked.

= New Features =

* Tags on photos. See Album admin -> Manage photos: input field and selection box, <b>Photo Tag Cloud</b> widget and settings: Table II-A3.3, Table VI-C3.
* There is a button on the album admin -> manage album information screen to go to the upload page directly if the user has the rights to do so.
* If Table IX-B3 is set: There is a button on the album admin -> manage album information screen to set all photo descriptions 
to the New photo description as set in Table IX-B4.
* You can specify an alternative thumbnail size on a per album basis. Size settings are in Table I-C1a,3a,4a.
* If you are logged in and are the owner of the photo ( or admin ) and have album admin rights, 
there will be an Edit link in the fullsize photo description that allows you to edit the photo information directly.

= Other Changes =

* The album selection in the shortcode generator now also respects the hi�rarchical display setting in Table IX-A12.
* Changing the status of a comment in the comment admin page is Ajaxified and no longer requires pressing the Save Changes button.
* Changed treatment of missing IPTC and EXIF data. 
In the IPTC and EXIF boxes: if the status is set to *optional* in Table X and XI, the itemlines will be omitted.
In the photo description the missing tag values will no longer show the tag but will be replaced by <b>n.a.</b>.
* The option settings are 'cached' into a single option to improve initialisation performance.
* The HTML tags in album and photo desciptions will be balanced prior to displaying, regardless of the setting in <strong>Table IX-A2</strong></li>.

= 4.8.12 =

= Bug Fixes =

* Security release

= 4.8.11 =

= Bug Fixes =

* Fixed a problem in the pagelinks in the breadcrumb.

= New Features =

* New Album keyword #comten. Represents the last commented photos. Acts similar as #topten and #lasten.
* Two settings added in Table II-A: show breadcrumb on comten displays: II-A3.2, and show pages in breadcrumb: II-A4.1.
* You can specify to what width of the slideshow small texts must be used. 
This is a dynamical setting i.e. the textsize changes on the fly for responsive themes. Table I-B7: Mini treshold.

= 4.8.10 =

= Bug Fixes =

* The slideshow did not come back after adding a comment when the original slideshow came frm the comment widget. Fixed.

= New Features =

* The album slection box in admin pages can display the album hierarchy to facilitate locating the right album. Table IX-A12.

= 4.8.9 =

= Bug Fixes =

* Grant an album could not be turned off. Fixed.

= 4.8.8 =

= Bug Fixes =

* Due to recent code changes, the information on which album to be used in the sidebar slideshow was lost. Fixed.
* The sharelink to Pinterest initially showed the wrong button icon. Fixed.

= 4.8.7 =

= Bug Fixes =

* RSS feeds for Single image slideshows did not show an image. Fixed.
* The ampersand character is now correctly processed in shares when it appears in the blogtitle.
* Links now always work when the lading page contains a shortcode/script of other types than generic or landing.
* When pretty links are enabled, spaces were removed in a searchstring. Fixed by replacing them with underscores.
* Transparency of png images is now preserved in thumbnails.

= New Features =

* You can have the album description in the custom box. Add **w#albdesc** as a shortcode for the album description.
* Added Social media share buttons for **Google+** and **Pinterest** (Table II-B21.5,6).

= 4.8.6 =

= New Features = 

* Grant an album (Table IX-B6,7). If checked: for any user who has upload rights a default album will be created 
if there is not yet an album with him as owner as soon as he logs in. (Requires Table VII-B1: Owners only).
* Coversize is height (Table I-D3.1) works only when Coverphoto pos is top or bottom (Table IV-D3). 
This makes it easyer to make the covers equal in height.
* Share a single image (Table II-B21.99). The sharelink points to a page with a *single image like a slideshow* rather than *the photo in the slideshow*.

= Other Changes =

* Changes made to be compatible with WP 3.5: Fix in TinyMCE shortcode generator and fixes to warning prepare() arg 2.
* To prevent disappointments, the settings that have no help text do no longer show a questionmark button.

= 4.8.5 =

= Bug Fixes =

* Thumbnail popups were vertically misplaced when Table II-C8 (display album description on thumbnail area) was set to *top*. Fixed.
* The lightbox background was not large enough for an ipad in portrait position. Fixed.
* The photo of the day, when set to *day of month is order#* will now display the correct image and change on 24.00 based on the blogs timezone set in wp *Settings -> General*.

= New features =

* You can switch off the display of the "Comment Added/Edited" alert box in Table IV-F6.
* The album cover photo can now link to lightbox, giving sets of the album contents.
* The Album widget thumbnails can now link to lightbox, giving sets of the respective album contents.

= Other Changes =

* The date/time of upload on the album admin -> edit -> manage photos page is now based on the blogs timezone set in wp *Settings -> General*.
* The Twitter Intent Tweet now displays the working link to the image you wanted to share.
* The Hyves Tip now shows spaces where spaces are expected in place of plus signs.
* The links form standard thumbnails to slideshow are Ajaxified for normal albums 
(not for special photo selections like topten, lasten or search results) where possible (same page/post, no new tab, no pso).

= 4.8.4 =

= Bug Fixes =

* Fix for special chars in photo names.

= New Features =

* The photo of the day display method selction *Change every* has been expanded with *day of month is order#*

= Other changes =

* Pretty links are automaticly disabled when the permalink structure is default or when the super global $_ENV["SCRIPT_URI"] is not set and will no longer result in not found errors.

= 4.8.3 =

= Bug Fixes =

* Removed spurious html tags from the description used for social media sharing.
* Fix for special chars in album names.

= New Features = 

* You can set the behaviour of the filmstrip thumbnails to go to the indicated slide on hovering. Table IV-B13.

= Other Changes =

* If a file wppa.min.js exists, it will be loaded in place of wppa.js. A minified wppa.min.js is supplied and saves approx. 25 kb data load.
* The Share box can be switched on for slideshows in widgets, not being slideonly slideshows like the slideshow widget.
* The size of the social media icons in the Share box is selectable: 16 or 32 pixels. In widgets it is 16 px always.

= 4.8.2 =

= New Features =

* New box in the slideshow: Share box. Contains links to social media that work. 
Shares the url showing the photo, name and description of the photo where the box is displayed under.
You can configure the box in **Table II-B21**, and move it upwards in Table IX-E.
* The creation and interpretation of 'Pretty Links' is supported now. Enable in Table IV-A3. **Table IV-A2 must be unchecked for this setting to work properly!**

= Other Changes =

* Discontinued support of AddThis. addthis.update() does not work properly for url and title, and does not exist for image and description. 
Also the documentation of addthis.update() is no longer findable in the addthis api documentation. The use of addThis is no longer encouraged.
The dynamic updates from wppa+ have been removed.
* Removed diagnostics and some code that is no longer required.

= 4.8.1 =

= Bug Fixes =

* Due to a reorganisation, the link selections were mixed up in Table VI. Fixed.

= 4.8.0 =

= New Features =

* There is a new widget: **Thumbnail Albums** widget. It shows album cover image thumbnails that link to 
either the content of the album (sub-albums and thumbnails) or to a slidshow of the content photos.
* There is a new widget: **QR Widget**. It shows a qr code that will be updated at wppa+ Ajax operations and slideshow browsing and running.
* The qrcode can alternatively be displayed in the photo description box in the slideshow. All QR code settings are in Table IX-G.
* You can now have the album description at the top or at the bottom of the thumnail area display. (Table II-C8).
* You can switch off the automatic opening of the comment box when the slideshow does not run. (Table IV-B12).

= Other Changes =

* Table VI has been reorganized and sub-devided into separate sections.
* wppa-theme.php and wppa-style.css have been changed. If you have them, check your optional copies in your theme dir.

= 4.7.19 =

= Other Changes =

* For users who do not like the collapsable album table: You can now switch to the 'old style' flat table, back and forth.
* A link to the manage photo's page has been added in the comment moderation email.
* Pages with new style shortcode can now also be selected as target page in the comment admin.

= 4.7.18 =

= Bug Fixes =

* Album names containing double quotes caused layout problems in IE8. Fixed.
* Clearing the ratings of the photos in an empty album no longer causes an error message.

= Other Changes =

* The display of the album list in the main screen of the Album Admin page now shows only top-level albums. 
Clicking the arrow expands the subalbum tree one level etc.
* You can now check boxes on the upload screen to go to the album admin after upload directly. The most recent state is remembered.

= 4.7.17 =

= Bug Fixes =

* Fixed return urls after commenting from topten or lasten widgets.
* Fixed layout issues of comments.

= 4.7.16 =

= Bug Fixes =

* Due to a recent code change the share urls and history pushstate urls contained & amp; in stead of simple &. Fixed.

= Other Changes =

* The tooltip "Zoom in" can be switched off in Table II-F.
* Cosmetic changes to the slideshow comment area.

= 4.7.15 =

= Bug Fixes =

* Admin did not always get a moderate link on a comment notification email. Fixed.

= Other changes =

* Table II-F has been expanded with settings for single and single media type photos.
* Cosmetic changes to the comment admin page while moderating comment from email notification.
* cosmetic changes to the comment notification email.

= 4.7.14 =

= Bug Fixes =

* When using qTranslate and being switched to a non-default language, the content is now always in the right language independant of the ajax switch.
* Fixed bugs in AddThis interface.
* The popup of a portrait image is horizontally centered during animation now.

= New Features =

* The name and description can be set to display in two separate boxes in the slideshow. Table IX-E13.
* If the name and description are displayed in separate boxes, the display of an empty description box can be suppressed in Table II-B6.1.
* Type of sharelink for AddThis is now configurable in Table IX-F3.
* You can select which user(s) will receive a notification by email on entring a new comment. Table IV-F5. 
If the user has the right to admin comments, a moderatelink is included in the mail.

= Other Changes =

* Further reduction on database queries to an average of approx 20% compared with version 4.7.12

= 4.7.13 =

= Other changes =

* Check on sufficient memory on upload can be switched off (Table VII-B3.1 -B3.2) for systems where the computation fails and uploads are no longer possible.
If during the calculation it becomes obvious that the result is unreliable, this check takes no action.
* Significant performance improvements when there are no exif and iptc tags in photo descriptions.
* Significant performance improvements due to the reduction of database queries to approx 35% in comparision with the previous version.
* Exif values like: 'On, Red-eye reduction' are now translatable.
* Added debug info on queries.
* Added statistics under the album table in the Photo albums -> Album admin page

= 4.7.12 =

= Bug Fixes =

* When ajax is on and the frontend language is different from the admin language, some words in the slideshow like Faster and Slower became untranslated. Fixed.
* Fix for memory check on upload where memory_limit set to -1 (unlimited), revised calculation.
* Fix for link error in coverimage when ajax resulting in scrolling up the page.

= 4.7.11 =

= Bug Fixes =

* All users that can edit posts or edit pages will have the wppa+ album shortcode button above the visual editor.

= New Features =

* Network administrators can decide to run wppa+ in single site mode. Add to wp-config.php: define ('WPPA_MULTISITE_GLOBAL', true); Do not apply in existing installations!

= Other Changes =

* Improved check on sufficient memory during upload. Also added messages on what the max uploadeable photo pixelsize is. You should no longer get crashes due to out of memory errors.
* Added Turkish translation frontend.

= 4.7.10 =

= Bug Fixes =

* Fixed errors in comment admin when no linkpage was selected.
* The frontend upload dialog will now have the correct initial width in responsive themes and where size is auto.

= New Features =

* You can apply a watermark to an existing photo on the Album admin -> Manage photos admin screen. 
To enable the user to select a watermark on the manage photos screen, check Table IX-D1: Watermark 
AND - if the user has no right to change settings (See Table VII-A) - Table IX-D2: User watermark.
* You can preview the fullsize image in a new browsertab by clicking the thumbnail on the Album admin -> Manage photos admin screen. 
After applying a watermark, press CTRL+F5 to see the changes you made.

= Other Changes =

* The rule above that a watermark is selectable not only if Table IX-D2 is checked but also if the user has 
the right to change settings (e.g. the default watermark) applies to all corresponding locations (front-end and upload/import screen).
* When using the wp editor (Table IX-A11) the update buttons in the album admin page show a spinner while processing the update request.

= 4.7.9 =

= New Features =

* Download button added to the fullsize popup with print button. Save image or zip under its original name (Art Monkey type of link).
* New wppa display type added: slphoto. Displays a single photo in the style of a slideshow, without navigation boxes, 
but with rating and comment as configured for slideshows.
* If you specify both album and photo in a shortcode for a slideshow, the slideshow will start at the specified photo. 
Works in new shortcode only as scripts do not allow the specification of both album and photo.
* The display of the x/y counter in lightbox displays is now settable in Table II-F9
* Added an option to run wpautop on fullsize descriptions Table IV-B11.

= 4.7.8 =

= Bug Fixes =

* Fixed js error that stopped slideshow under some circumstances

= 4.7.7 =

= Bug Fixes =

* Filmstrip pagesize fixed when size is fraction.
* Swipe on slideshow (left-right) is supposed to work on all mobile devices now.

= New Features =

* You can now link the fullsize name to an imagefile or a zipfile containing the photo with the name as filename. Table VI-12: Art Monkey Link.
* Swipe on lightbox (left-right) is supposed to work on all mobile devices now.

= 4.7.6 =

= Bug Fixes =

* New tab on link from slide to plain file now works.
* Link form slide to lightbox now displays the selected magnifier cursor.
* New style and old style shortcodes can now also be mixed in a single page/post when they contain slideshows and Table IX-A7 is checked (Allow foreign shortcodes).

= New Features =

* Font characteristics for wppa lightbox overlay text are now settable in Table V-9.

= Other Changes =

* Pages containing the new style shortcode are now also selectable as target page in Table VI
* The state of the 'Create new style shortcoe' selectionbox is now remembered in a cookie.

= 4.7.5 =

= Bug Fixes =

* Sidebar slideshow now displays (or not) name and description as asked for in the widget activation screen.

= Other Changes =

* Added padding 0 to slidshow elements in wppa-style.css
* Added order by timestamp for album order method (Table IV-D1)
* The previous wppa+ version is now recorded for support purposes

= 4.7.4 =

= Bug Fixes =

* Thumbnail popup failed under some circumstances in chrome and safari. Fixed.
* Removed erroneous #'s from links when ajax is enabled, causing ajax and some links not to work properly.
* The update of the url (and hence the history.pusState) no longer happens on a single slidechange when there is no album in the url.
* Fixed a fimwindow alignment problem that only showed up in sites that could not provide a link to show it.
* Fixed a problem where links at the top of the page did no longer work after closure of a lightbox display.

= New Features =

* The space between avg and my rating is now settable in Table II-E4
* The shortcode generator now optionally creates new style shortcodes. 
The use of new style shortcodes is experimental, it is encouraged to be used by experienced users, but not yet granted to work always.
* You can now decide to use the wp editor in places of multiline inputs in Table IX-A11.
* You can select one out of three sizes magnifier cursor for lightbox in Table I-G2.
* You can specify a fraction for the %%width=..%% script token. It implies auto.

= Other Changes =

* Plugin load time and initialisation time measurements added in diagnostic mode.

= 4.7.3 =

= Bug Fixes =

* Resizing the browser window when lightbox is open does the image resize when it is part of a set, but not when it is a single image. Fixed.

= New Features =

* You can now specify what you want in the subtitle of a lightbox display in Table II-F3..8

= 4.7.2 =

= Bug Fixes =

* Photo of the day: when linked to lightbox, the name of the photo was Zoom in. Fixed.

= 4.7.1 =

= New Features =

* Now supports the plugin Cube Points. You can assign points to Comments and Rating votes in Table IX-F.

= 4.7.0 =

= Bug Fixes =

* The Settings page now passes W3C validation.
* The embedded lightbox now also initializes when it is active on any linksources but not on slideshows.
* The thumbnail popups popped down to zero dimensions in Chrome. Fixed.
* The links to lightbox in the slideshow did not function for even numbered slides when animation type was a fading type. Fixed.
* In lightbox: If the picture is very landscape, so the width is the limiting factor, it will be properly downsized now.
* It is no longer required to have Table II-D2 checked in order to make Table VI-11 set to slideshow window to work.

= New Features =

* The search widget has an optional textfield before the edit box at widget activation. It may contain HTML.
* Single photos and Media type photos can link to lightbox.
* The photos album name is displayed on the comment admin and the comment edit screens.
* If the (admin) user has the rights to admin comments, he will be able to change the comment status on the Album Admin-> Manage Photos screen.
The status 'trash' has the meaning: delete when the screen is refreshed/reopened or when the comment admin page is opened.

= Other Changes = 

* Cosmetic changes to the Settings screen. All tables and subtables can be toggled on/of by clicking the header bar.
* The concept of single photos has been re-implemented. Links are settable in Table VI-0.
This implementation skips all superfluous overhead and it is possible now to have multiple photos in a row without vertical alignment problems;
just use %%align=left%% and put all script tokens together without newlines.
* The Big Browse Buttons are now each 1/3 of the slideframe wide, allowing the middle part to link to lightbox or a Photo Specific link.
* Links to lightbox show a magnifier cursor and tooltip: Zoom in. (Only in browsers that support url cursors).
* Table II-D has been slightly changed. Item 1 (Covertext) applies to the album description only now. 
Item 4 has been added to switch the View link on/off separately. 
Please review the settings in Table II-D if the display has changed after the update to this version.

= Known Restrictions =

* Resizing the browser window when lightbox is open does the image resize when it is part of a set, but not when it is a single image.
If anybody can supply me a fix for this, i would be very glad.

= 4.6.12 =

= Other Changes =

* The Tinymce shortcode generator now supports all possible script combinations, including the most simple 'generic album'
and the extended features on #topten and #lasten.

= 4.6.11 =

= Bug Fixes =

* The Tinymce shortcode generator did not show album previews when the photo order was descending. Fixed.
* Fixed redundant slashes in thumbnail popup description causing html attributes not to act as intented.

= New Features =

* The E-mail adresses in the comment admin page are now links to the mail program to enable quick replies.

= Other Changes =

* Almost all static texts in the Tinymce shortcode generator are now translatable.
* The Tinymce shortcode generator now also displays previews of --- special --- albums like #topten etc.
* Minor cosmetic changes to the shortcode dialog.
* If the plugin Ultimate TinyMCE is installed (and is up to rev 2.7.1) the WPPA+ Shortcode button can be positiond in that plugins Settings Page.

= 4.6.10 =

= Bug fixes =

* IPTC and EXIF tags were not converted in thumbnail popup descriptions. Fixed.
* Fixed a number of layout problems for ver 4 browsers (IE7,8) when Table I-A1 is set to auto.
* Size and position of the images in the wppa lightbox overlay are now correct for IE7,8.

= New Features =

* The visual editor has an extra button: WPPA+ Gallery Shortcode. This makes the creation of the wppa+ script tags easy.
* The display of time since upload can now be switched on/off at activation of the LasTen widget.
* You can select a combination of albums in the LasTen widget.

= Known Restrictions =

* Animation type 'turnover' for slideshows does not work in IE7,8; the slideshow will not run and single images are not displayed properly.

= 4.6.9 =

= Bug Fixes =

* Fixed sidebar slideshow layout when portrait only for version 4 browsers (IE7,8).
* Fixed lightbox layout for ver. 4 browsers.
* Fixed a qTranslate problem i.c.w. Table II-C5.1 being checked.

= 4.6.8 =

= Bug Fixes =

* Improved lightbox display in IE < 9, its not great yet
* Fixed unknown v align and h aligh errors

= 4.6.7 =

= Bug Fixes =

* There were a few slideshow display and alignment problems. Fixed.
* The settings in Table I-B1 and 2 (Fullsize width and height) now also limit the maximum display size when Table I-A1 is set to 'auto', i.e. in responsive (mobile) themes.

= 4.6.6 =

= Bug Fixes =

* Fixed a slideshow layout issue where column width not auto and fullsize smaller than column width.

= New Features =

* The Edit Album display now shows the comments at each photo.

= 4.6.5 =

= Bug Fixes =

* Changed 'javascript://' to 'javascript:void();' for validation reasons.

= New Features =

* There is a new widget: LasTen. It displays the n most recently uploaded photos. Its settings are like those for the topten widget.
* There is a new script keyword: %%album=#lasten%% optional album id and count like: %%album=#lasten,4,7%%.

= Other Changes =

* WPPA+ is now compatible with responsive themes (mobile themes). Tested with Responsive, Fluid blogging and SimpleX.
To use wppa+ on a responsive theme: Set Table I-A1 to *auto*, Set the width in the slideshow widget to *auto* and 
do not use any %%size=...%% script tokens unless you incidently want a fixed width display.
* You can select *month* as the period in the photo of the day admin.

= 4.6.4 =

= Bug Fixes =

* Fixed filmstrip length problem in auto column width.
* Fixed slideframe width problem in auto column width.

= New Features =

* To reduce the overhead of SEO code you can now switch the two metatag mechanisms off independantly. (Table IX-A9 and 10).

= Other Changes = 

* You can now only select pages that contain %%wppa%% as the page to display search results on. (Table IX-C1).
* You can now only select pages that contain %%wppa%% as the page to link to in Table VI.

= 4.6.3 =

= Bug Fixes =

* The animation in the embedded lightbox for images that were smaller than the maximum possible ended with a shift to the left. Fixed.
* Fixed a layout issue for the left/right arrows in the embedded lightbox for some installations.
* Fixed a pagination problem that caused the first page to be empty and redundant when %%album=#topten...%% was used.
* The subtitles in %%mphoto=..%% are displayed properly now even when they contain html and html is allowed.

= New Features =

* There is a maximum upload system in place. See Table IX-B5 and the Edit Album admin page. 
Limits can be set on an album basis (with a default on creation time set in Table IX-B5) and are only changeable by administrators.
* You can set whether slideonly starts running or not in Table IV-B3.1.

= Other Changes =

* All the 7 widgets can now have an empty title and will hide the widget title box when the title is blank.

= 4.6.2 =

= Bug Fixes =

* A layout issue of the navigation arrows in the filmstrip for certain font families in firefox fixed. (Hotfix 4.6.1.001).
* The admin bar at the frontend did not always have the proper submenu items. Fixed (Hotfix 4.6.1.002).
* Only administrators can now edit or delete ---public--- albums.

= 4.6.1 =

= New Features =

* The fade-in speed of the lightbox overlay image can now be set in Table IV-G3.
* Frontend upload now also allows the input of photo name.

= Other Changes =

* Prevented a possible error when converting from old wp photo album (without plus)
* Made a change that will enable the use of google libraries (in 4.6.0)

= 4.6.0 =

= Bug Fixes =

* Special characters will now be processed as expected when editing album and photo names and descriptions as well as in text on the Settings screen.
* Fixed a typo (camara) in the default new photo description. This helps only for new installations or when you reset all settings to default values on Table VIII-A3.

= New Features =

* You can now strip html anchor tags in descriptions under thumbnail popups in Table II-C5.1.
* You can select the location(s) where the pagelink bar will be placed, on top of the album content display, at the bottom(default) or both.
This feature requires the use of the newly supplied wppa-theme.php. Setting: Table II-A8.
* You can set the vertical wppa+ box spacing in Table I-A7.

= Other Changes =

* There are built-in checks for a few theme and initialisation requirements. 
In case of non-compliance an errormessage will be displayed if one of three possible debug switches are in effect: 
WPPA_DEBUG set true in wppa.php, WP_DEBUG is set true in wp-config.php or (?|&)debug is appended to the url.

= 4.5.7 =

= Other Changes =

* The removal of normally unwanted spaces caused by p and br tags when Table IX-A7 is checked (foreign shortcodes) 
is now optional and can be set on Table IV-B10.
* Importand server side and page load performance improvement. The IPTC data, EXIF data and photo description for fullsize photos and 
slideshows is now only generated and loaded when it is actually needed. I.e not in cases of slideonly(f).
* Exif tag E#9204 is now formatted (if not empty) by appending ' EV'.

= 4.5.6 =

= Bug Fixes =

* Number of lines set to auto in Table I-G1 now also works.

= 4.5.5 =

= Bug Fixes =

* The spinner image is now displayed only when there is no image visible in the slide frame.

= New Features =

* Scrolling by 'page' in filmstrip added (double angle brackets, the single angle brackets act as next and previous now).
* Added configuration settings to the new wppa-embedded lightbox functionality: 1: number of lines in description (Table I-G1),
2: Label text to the Close cross (Table II-F1), 3: Background opacity (Table IV-G1), 4: Action on click on background (Table IV-G2).
* You can select ---all--- albums in the slideshow widget.
* Added album id keyword #all. You can use %%album=#all%% and %%slide=#all%% etc. %%cover=#all%% is meaningless and will return nothing.
* In the topten widget you can select order method either 'By mean value' (as before) or 'By number of votes' (new). 
To the options in Table IV-C1 order by number of votes has been added.

= Other Changes =

* Global photo order select can now also be by Timestamp in Table IV-C1.
* Improved and detailed error reporting in case of wppa database problems after a (partially) failed plugin update.

= 4.5.4 =

= Bug Fixes =

* Frontend upload should support .png files but returned an error. Fixed.
* Fixed a pagelink album number where the script indicated an album number and we are looking to paginated thumbnails of a (grand)child album.

= New Features =

* A new lightbox module has been implememted. Just set Table IX-A6 to *wppa* to enable it. No other plugin or library required.
When applied to the full-size slide image (Table VI-8a set to *lightbox*) the entire slideshow will be browseable.
* You can uncheck the *User upload login* switch (Table VII-B0) to enable anonymus uploads. Be carefull, read the Help (?) first!

= Other Changes =

* Changed the default lightbox keyword to *wppa*

= 4.5.3 =

= Bug Fixes =

* Changing fontsize for Numbar active elements will do it now.

= 4.5.2 =

= Other Changes =

* You can specify font specs for Numbar Active element. A known restriction is that changing the fontsize does not work.

= 4.5.1 =

= Bug Fixes =

* The photo of the day album selection finally works as designed.
* Random topten photo of the day now works also!
* The lightbox on a thumbnail widget will show the collection of the photos of the widget involved only, no longer of all the thumbnail widgets together.
* Same for the topten widget.

= New Features =

* The upload screen Box A now also displays a list of the selected files.
* You can set the search mechanism to search for photos only (Table IX-C3).

= Other changes =

* The 3 js files for the frontend are now combined into one: wppa.js. This reduces page load file accesses.
* Improved check on filetype when uploading watermark file.
* When Ajax is enabled and the browser supports history.pushState the stack is maintained also when the slideshow is the only non-widget running show.
* Same for update of addthis linkurl and title. (despite bugs in addthis code).

= 4.5.0 =

= Bug Fixes =

* When the delete checkbox was unchecked for import photos, the files were deleted anyway. 
This also generated a warning message during import on the attempt to remove a tempfile that was already removed.

= New Features =

* You can link filmstrip images to lightbox containing the full range of photos in the slideshow, as opposed to the standard direct goto feature. Table VI-10.
* Of all combinations of user roles and wppa+ menuitems the capability can be set to grant or deny. This fully implements the WP role/capability feature. 
If you changed the standard access configuration it may be required to visit Table VII-A and check/modify the configuration. Administrators will have all accessrights always.
* There is a switch that enables the check on correctly closing the html tags when entering album and photo descriptions. (Table IX-A2).

= Other Changes =

* Restructured and renumbered Settings tables. Please visit the settings page to get used to the improved lay-out.
* Removed obsolete settings and actions.
* Changed the example new photo description.
* When the requested display size of any photo is not larger than the thumbnail image file, the thumnbnail image file will be used.
If you have set Table I-C2 to anything different from the default --- same as fullsize --- you may wish to switch this off on Table I-C9.
This will dramatically improve the page load performance.
* If the display of comments, iptc and/or exif data is switched off, the code for these boxes is no longer generated.
This will dramatically improve the server response time in case one or more of these features are switched off.

= 4.4.8 =

= Bug Fixes =

* The thumbnail popup showed the name twice. Fixed.
* When using ajax (Table IV-33) and lightbox on thumbnails (Table VI-2a), the thumbnail display required refreshment for lightbox to work properly. Fixed.

= New Features =

* You can now do a multiple selection on photos to upload. Both on the Upload admin screen as on the frontend upload.
This feature requires a modern browser that supports HTML-5 and will not work on I.E. including I.E.9.
* You can now set the rating display to Numeric as opposed to Graphic in Table II-13a. Especially usefull when the rating is set to Extended (10) in Table I-28.
* A new widget has been added: Thumbnail widget. It displays a settable number of thumbnails from one album or from the system. See Table I-30,31; VI-9abcd.

= Other Changes =

* Temp files will be removed after upload.

= 4.4.7 =

= Bug Fixes =

* Supplied Tools VIII-13a and 13b to correct ratings.

= 4.4.6 =

= New Features =

* You can set the rating system to Extended, i.e. 10 stars as opposed to the standard 5. Table I-28.
* You can specify the display precision for avarage ratings from one up to 4 decimal places. Table I-29.

= 4.4.5 =

= Bug Fixes =

* The use of IPTC and EXIF tags in photo descriptions now also processes multivalues tags properly.
* If your php config does not support zipping the export results, you will get a warning message and exporting will continue.
* On some systems copy photo produced an error. Fixed.
* Local avatars are finally displayed properly.

= New Features =

* There is a simple calculate captcha for comments on photos. Table VII-8. A wrong answer makes the comment spam. It will be editable to corrrect the captcha.
It is not a very secure method, but better than nothing.
* Comments that are marked as spam can now automaticly be deleted after a configurable lifetime. Table VII-9.
* The photo specific links can now be set - on an individual basis - to be opened in a new tab.

= Other Changes = 

* The support of the non-autosave versions of the settings page and the album admin page has been discontinued.

= 4.4.4 =

= Bug Fixes =

* It is no longer possible to set Tanble I-2 and 3 to 'auto'. Only Item I-1 may be set to 'auto'.
* The captions of the IPTC and EXIF boxes now obey the settings in Table V-6.
* The Big Browse Buttons have explicitly background-color: transparent now, to cope with themes that have a white background behind all images.

= New Features =

* You can specify New Tab in Table VI for all links independantly when appropriate. Note: When using Lightbox-3 the plain file will open in a new tab as a lightbox image, 
a specified lightbox link will open in the same tab (with possible browsing) regardless of the New Tab setting.

= Other Changes =

* Uploaded zip file may now contain sub-directories with photos. They can also be imported. This fixes also a spurious unzip problem.
* Added support for EXIF UndefinedTags.

= 4.4.3 =

= Bug Fixes =

* The IPTC and EXIF shortcodes in photo descriptions for items that are not present in the photo info will no longer appear untranslated but will print nothing.
* The most commonly used EXIF tag values are now properly formatted. e.g. 56/10 for F-stop will print: f/5,6.

= New Features =

* Photos have a new property: status. Status can be one of; pending (awaiting moderation), publish (standard) or featured.
Featured photos will be easily found by search engines by means of meta tags in the page header.
Status can be changed on the Photo Albums -> Album Admin -> Edit Album information -> Manage Photos admin screen.
* Uploads can be set to require moderation (Table IV-36). Users who have Album Admin access rights, can change the status; photos uploaded by them will initially have status publish.
* In the sentences 'You must login to enter a comment' and 'You must login to vote' the words 'login' are now a link to the wp login screen.
* You can now select a page in the comment admin page to display the photo with all its comments. Just click the thumbnail image.

= 4.4.2 =

= Bug Fixes =

* PHP Warnings during Ajax operations from the Settings autosave and album admin autosave admin pages
will now produce an alertbox and report success or fail correctly.

= New Features =

* When WP Supercache is installed and activated, the cache will be cleared when needed.
* Slideshow Pause on mouse hover (Table IV-35).

= 4.4.1 =

= Other Changes =

* When you use http://wordpress.org/extend/plugins/lightbox-3/ as the external lightbox, everything works even better as before!

= 4.4.0 =

= Bug fixes =

* A missing post/page name in the breadcrumb when using Ajax has been fixed.
* The photo search will now also work on iptc and exif tags used in descriptions.
* Tapping on a mobile divece on the Big Browse Bars is believed to work now.
* Cosmetic changes and a few 'forgottn' translations.

* Quotes in searchstrings work properly now

= New features =

* You can select - topten - for the album selection in the photo of the day widget. 
The photo selected is chosen from the number of top rated photos as specified in Table I-15, according to the specified Display method.
* You may use names for albums and photos in urls. 
Example: http://wppa.opajaap.nl/?page_id=1246&wppa-album=Piet%27s%20child&wppa-slide&wppa-occur=1&wppa-photo=OV-chip_saldo-%27corrector%27
is now a valid url.
* You can use photo names in scripting the same way as album names.
Example: %%wppa%% %%photo=$OV-chip_saldo-'corrector'%% %%size=400%% is a valid script sequence.
Like for albums: the name must be preceded by a dollar sign ($). 
If the photo does not exist an errormessage will be displayed.
However, if the photo with the given name exists more than once, the first found will be used.
* You can now set photo names in urls rather than photo numbers during browse full size images while Ajax is on. See settings IV-34.
* The use of shortcodes that refer to other plugins in photo descriptions is now possible. If you want this feature, check Table IX-20.
* You can set the watermark opacity in Table IX-21.
* You can switch off the display of the breadcrumb for search results in Table II-1a.
* You can switch off the display of the breadcrumb for topten displays in posts/pages in Table II-1b.
* If you have addThis installed, the reference url is now updated during ajax and slide browse operations.
* You can now select one out of 6 animation types as opposed to 2 types of fading in Table IV-4.
* Swipe left/right should work now on mobile devices (next/previous photo in slideshow display).

= Other changes =

* The embedded lightbox has been removed due to licencing problems. 
You can still specify links to lightbox but you will need a separate lightbox plugin ( such as http://wordpress.org/extend/plugins/wp-jquery-lightbox/ or http://wordpress.org/extend/plugins/lightbox-plus/ ) to make it work.

= 4.3.10 =

= Other changes =

* Errors during upload caused by unwilling exif or iptc extraction are now suppressed when they are not fatal in standard mode (non-debug).

= 4.3.9 =

= Bug fixes =

* Fixed a erroneous link to a different given page.

= Other changes =

* Language files update (French)
* Various cosmetic fixes

= 4.3.8 =

= Bug fixes =

* Fixed link from covertitle/coverimage to slideshow. (Stopped working as per 4.3.6).
The computation of all links in the cover have been throughly revised and should function properly now
in both cases either Table IV-33 is checked or not (Ajax).

= Other changes =

* The use of Big Browse Buttons will now also change the url when ajax is enabled in Table IV-33.
* The urls created during browsing a slideshow are now equal to the respective single photo urls under the same conditions.
This means that after browsing a slideshow, the content of the browser addressline can be saved 
and used later to enter the slideshow at the specified point.

= 4.3.7 =

= Bug fixes =

* Fixed error in wppa-common-functions.php causing a fatal error during update.
* Fixed problem in wppa_get_permalink() causing many links to point to the homepage.

= 4.3.6 =

= Bug fixes =

* Photo of the day admin and Album admin: display of thumbs: link errors in multisite environment fixed.
* In a widget there will no longer be an empty box at the location of the comment box.

= New features =

* You can now switch BBB's separately for widgets (Table II-19).

= Other changes =

* Improved userfriendlyness of the selection of albums in the potd widget admin
* Increased ajaxification. You are strongly recommended to test your site with Table IV-33 checked.
* IPTC and EXIF tages can now be set to Optional: display when the content is not empty.
* The photo of the day will link to lightbox if lightbox is activated and if the link is set to: the plain photo(file).
* Simplified qTranslate interface.
* Importing photos that were previously exported will now properly import into albums with quotes in the name.
* Importing photos will no longer stop at an error, but will attempt to continue.

= 4.3.5 =

= Bug fixes =

* The name of the commenter will be properly displayed in the comment widget, even when the comment contains html.

= New Features =

* An editable copyright warning message can be added on the user upload section. Table II-31,32.
* The fullsize photo description can be aligned left, center or right. Table IV-32.
* New installations will have default chracter set utf8 and do not need to run Table VIII-2.

= Other changes =

* The tooltip on fullsize images now shows the photo name rather than the description.
* The Avatar can now be local.

= 4.3.4 =

= Bug fixes =

* Added a few forgotten translations

= New features =

* You can now recuperate IPTC and EXIF data from photo files that are already in wppa+ without updating them.
This will only work on photos not resized during the original upload/import. Table VIII item 12.

= Other changes =

* Built in a safety check and removal of linebreaks that will prevent many causes of broken slideshows.

= 4.3.3 =

= Bug fixes =

* Fixed a hangup on 16 bit servers when uploading/importing photo.
* The spinner image is now approx in the center of the fullsize image.
* A lot of issues when size=auto are fixed. Still no 100% guarantee for all (old) browsers that it works as desired.

= Other changes =

* All new features and improvements will only be implemented in the Auto Save versions of the Settings page and the Edit Album pages.
The old versions will get phased out. If you can not run the autosave versions please report that on the Forum: http://wordpress.org/extend/plugins/wp-photo-album-plus/
* Edit Album Autosave: The table is now sortable by clicking on the caption items.
A subsequent click on the same caption toggles up/down sort.
It also displays the number of subalbums and photos each album contains.
* Increased configurability for moderation of comments on photos. Table IV-30.
* Email address can be set to not required for comments on photos. Table IV-31.
* Titles in widgets display photo names rather than descriptions for fullsized photos. This is neater while descriptions often contain html code that can not be rendered in a tooltip.
* Captions of slidewidget and photo of the day widget now behave as expected during widget activation. 
You do no longer need to enter a html special space to have no title.
* Lightbox fullsize images (slideshow) will display the photo descriptions as subtitle.

= 4.3.2 =

= New features =

* Avatar default is now configurable (Table II-30,30a)
* Avatar size is now configurable (Table I-27)

= Bug fixes =

* Fixed errors in avatar code

= 4.3.1 =

= New features =

* IPTC and EXIF support has been added. This is configurable in Table II-28..29 and Table X and Table XI of the Settings page Auto save version only.
* You can set the display of avatars at the comments in Table II-30 on the Autosave settings page.
* The display of name, desc, rating in the thumbnail popup are now switcheable (Table II-25..27).
* Font weights are now settable in Table V.
* You can now optionally force the aspect ratio of thumbnails to a fixed value. either by clipping or by padding.

= Other changes =

* If you upgrade from a version prior to 4.2.11 and you used the wppa+ supplied lightbox, the configurable fullsize linktype (Table VI-8) will now be initialized to lightbox.

= Bug fixes =

* Fixed a fatal error in the potd widget when album selection was all-sep.

= 4.2.11 =

= Bug Fixes =

* Clear ratings on the edit album page now reports correct.
* Fixed a possible hangup during ajax rating auto next at end of slide cycle.

= New Features =

* If you do not use the wppa+ embedded lightbox but want to use a different lightbox(plugin) the required keyword used in 'rel="lightbox"' can now be set when it differs from 'lightbox'. (Table IX-9a).

= Other changes =

* The thumbnail popups will now popdown at mouseout.
* The inconsistency checks in the Settings Autosave are now dynamic and will change the moment you change the settings involved.
* You can select the link from fullsize to be one of: no link, plain file and lightbox. If yoy used lightbox on fullsize images, you will have to reset the setting in Table VI-8. 

= 4.2.10 =

= Bug Fixes =

* Clicking an item that was a result of a search operation caused the search creteria to be lost. Fixed.

= Other changes =

* This version is to make sure you will have hotfix 4.2.9 001: Fixed a slideshow problem when My Rating was not shown.
* The search results may now be retrieved in a direct link using &wppa-searchstring=.
* Tested on WP 3.3

= 4.2.9 =

= New Features =

* You can now decide if comments entered by logged in visitors are immediately approved (as before) or they need moderation like comments entered by 
not logged in visitors.

= Other changes =

* If you selected -first at no rated- for the slideshow start (Table IV - 3) and next after vote (Table IV - 26),
the show will indeed start at the first unrated slide as well as the already voted slides will successively 
be skipped as long as there are unrated photos. This works only with ajax voting on (Table IV - 27).
* You will get a confirmation box on actions in the auto-save settings screen.

= Hot Fixes =

001: Fixed a slideshow problem when My Rating was not shown.

= 4.2.8 =

= Bug Fixes =

* You can now enter the special characters & # and + in album and photo descriptions in the autosave version of album admin.
* Fixed a warning in the admin bar when logged in user has no rights on any wppa+ admin activity.
* Fixed a missing tag end in an img tag in photo of the day widget.

= New Features =

* There is now an Auto Update version of the Settings page. The default is 'on'. If you want to go back to the classic version, uncheck Table IX item 19.
* You can select a new way to start the slideshow: Still at the first photo the visitor did not rate. Table IV item 3.
* You can now switch off the wrapping around of the slideshow: Table IV item 29.

= 4.2.7 =

= Bug Fixes =

* The helptext of Table IX item 16 and 17 did not show up. Fixed.
* Fixed spurious error 106 in rating with ajax enabled while WP Supercache is activated.
* The green checkmark will now always show up when a vote is issued.

= Other changes =

* There is an alternate Album Admin page that updates album info and photo details immediately without the need to press a Save Changes button.
Enable this by checking Table IX item 18: Album Admin Autosave. This option is especially usefull in editing albums with very many photos.

= 4.2.6 =

= Other Changes =

* You can set 'Rating use Ajax' for the fastest way to rate photos. The page is not reloaded, but updated. Table IV item 27.
* The Rating star transparency in the off state can be set in Table IV item 28.
* The errormessage stating that the db tables do not exists for systems that do not properly respond to SHOW TABLES is now suppressed.

= 4.2.5 =

= Bug Fixes =

* If rating multi is enabled (Table IV item 18), My Rating is now correctly displayed as my avarage rating for this photo.

= New Features =

* You can set 'Next after vote' to jump directly to the next image of a slideshow after voting. See Table IV item 26.
* You can switch off the display of the avarage rating. See Table II item 24.

= 4.2.4 =

= Hotfix =

* 001: Pagetitles in breadcrumb will be processed by qTranslate.

= New Features =

* You can PS Overrule the fullsize images in the slideshow.

= Other Changes =

* An activation hook is supplied for those who trust on the healing effects of de- and re-activation of the plugin.
It acts the same as Table VIII item 3.
* Database table entry ids will not be re-used after deletion. Except of the import of previously exported photos and albums, their original ids will still be used if they are available.
* The existance of the required database tables and directories as well as the writability of those direcories is checked on entering the Settings admin page.
If anything misses or is not useable an errormessage will be displayed.
* The default value of the filter priority (Table IX item 10) has been changed from 10 (WP default) to 1001.

= 4.2.3 =

= New features =

* There is a new widget: Recent comments on photos.
* The Yellow stars ar split into two different items: star.png for the rating system, new.png for the new indicator.
A new.png is supplied. These images will have no border, padding, margin or box-shadow.

= Other changes =

* You will now see the existing comments even if entering comments are allowed when logged in only.
* A popuped thumbnail will now pop down by a rightclick.

= 4.2.2 =

= Bug fixes =

* Link to slideshow from topten widget linked to only one fullsize photo when topten was systemwide. Fixed.

= New features =

* You can apply a watermark to the fullsize image during upload/import. See Table IX item 14 .. 17.
* You can give album admin rights and upload rights to subscribers. See Table VII item 4 and 5.
If you use this feature, it is strongly recommended to set the album access to 'owners only' (Table VII item 2).
* The owner of an album can be set to --- public ---. When album access is set to 'Owners only' (Table VII item 2),
and upload rights are granted to certain roles, the corresponding users can upload to all their 'own' albums as well as
to --- public --- albums.

= Other changes =

* You will still get a warning message if you are uploading/importing images that are smaller than the thumbnail size, but they will be there. 
The thumbnails will be stretched to their minimum required size.

= 4.2.1 =

= Bug fixes =

* Fatal error on upload with update switch. Fixed.
* Under some circumstances it looked like photos were imported, but they were lost. Fixed.
* Delete album with move photos now works as designed.

= Other changes =

* Improved error handling and reporting during import / upload.

= 4.2.0 =

= Bug fixes =

* A security issue has been fixed
* Minor fix in filmstrip when size=auto.

= New features =

* There is an additional navigation tool: Number bar. See Table I-24, III-11&12, V-22&23&24. This requires the newly supplied wppa-style.css

= Other changes =

* Auto fix db can now be switched on or off
* More diagnostics in upload

= 4.1.1 =

= Bug fixes =

* When using album names in script shortcodes, quotes and html special characters are handled correctly now.
* Minor fixes and enhancements in the display of the Settings page.

= New features =

* You can specify a screensize different from the Full Size width and height when resize on upload is checked. Nice when you use lightbox!
* Photo Albums menu added to the admin bar, including a pending comments indicator.
* You can now select a linktype for an album cover (on a per album basis on the edit album admin page). 

= Other changes =

* The auto_increment clause has been removed from the id field of all 4 wppa db tables. 

= 4.1.0 =

= Bug fixes =

* Previous page link acts as next pagelink in comment admin page. Fixed.
* Repaired form validation in submit comment.

= New features =

* You can upload photos from the album cover and/or the thumbnail area display if this feature is enabled, you are logged in and have access to the album.
* Smilies will be displayed in the comments on photos if this feature is enabled in wp core.
* You can use names in album script shortcode tags like %%album=$My Album%% %%slide=, %%cover= and %%slideonly=. Note that the name is preceeded by a dollar sign.

= Other changes =

* All get-variables have a wppa- prefix. This increases the immunity to conflicts with certain themes and other plugins.
The old syntax is maintained to render properly for backward compatibility, i.e. saved urls with &album= etc. as opposed to the new &wppa-album= will still give the right results.
* Small changes and some additions to wp-photo-album-plus/theme/theme.css
* Fixed additional small collapse issues (see 4.0.12).
* Added IP field in comment admin to ease the finding of spam sources.
* Changed submit method for comments from 'get' to 'post'.

= 4.0.12 =

= Bug fixes =

* Copy photo error 4 fixed.
* Sql warning in create album fixed.
* Fixed various layout issues for browsers that do not support style property visibility:collapse on table(elements): in Settings screen and in comments display.

= New features =

* If you enable lightbox and disable big browse buttons, the fullsize images are clickable to a lightbox overlay.
* You can reverse order the comments on photos now. See Table IV item 25.

= Other changes =

* There are still users that have #content .img { max-width: 640px; } and Table I item 1 larger than 640, so we now increase max-width inline to column_width when it is not auto.
* You can now enter a photo description template that can be set to apply for new added photos. See Table IX item 11 and 12.

= 4.0.11 =

= Bug fixes =

* The slideframe height was 2 times the border width too small when v-align is set to 'fit'. Fixed.
* The BBB's overlapped downwards when v-align is set to 'fit'. Fixed.
* In IE9 the thumbnail popup links did not work. Fixed.

= New Features =

* The height of the slidefame in the slideshow widget is now explicitly settable as opposed to the calculated value from Table I item 2 and 3, vertical align 'fit' will still overrule, a value of 0 defaults to the old method.
* The ability to update existing photos with new versions. You can chek 'Update' in the Import Photos admin screen.
* There is now a custom box in the slideshow box list that you can fill with any html. See Table II item 21 and 22, Table III item 10.

= Other changes =

* In spurous situations the auto increment generated database key returned MAXINT, preventing us from further adding records.
The associated error message was: Could not insert photo. query=INSERT INTO wp_wppa_photos (id, ...
The new incremented key is now calculated outside mysql.

= Wish List =

* The ability to automaticly import photos from a given directory to a given album.

= 4.0.10 =

= New Features =

* There is now a tool to regenerate ratings (Table VIII item 8)

= Bug fixes =

* Changed the CDATA declarations to a form that will hopefully work in all themes.

= Other changes =

* The Create new album mechanism has been simplified.
* Scrolling back to the (previous) photo position after delete, copy or rotate in the album admin screen.
* Check/uncheck all in import admin page.

= 4.0.9 =

= New Features =

* Name and description in the sidebar slideshow widget.

= Bug Fixes =

* Removed blue color of comment age.
* Photo of the day widget defined link stopped working. Fixed.

= Other Changes =

* You can set the wppa+ filter priority value. This may be usefull to prevent conflicts with certain themes and/or plugins.

= 4.0.8 =

= New features =

* Lightbox configuration possibilities. See Settings page Table I item 23, III 8 & 9, IV 24, V 19 & 20 & 21.
* Order sequence settable for fullsize name and description. (See Table IX item 6.9)

= Bug Fixes =

* Popups pop down again at mouse leave.
* Under some circumstances, possible link page selection box was shown in Table VI items 2 and 3 where not appropriate. Fixed.
* All script is now embedded in CDATA blocks. This will fix certain causes of slideshow not functioning in certain themes.

= Other changes =

* Got rid of z-indexes, you need no longer change the menu css for overlapping slides.
* Improved errormessages and messages on inconsistent settings.

= Open Wish List =

* Name and description in the sidebar slideshow.

= 4.0.7 =

= New Features =

* lightbox support on thumbnails and topten thumbnails (See Table VI items 2 and 3, Table II item 20)

= Bug Fixes =

* Setting upload rights to contributors failed due to a typo. Fixed.
* Possible further fix to IE8 narrow images problem.
* Sites without qTranslate active would sometimes get qTranslate tags in names and descriptions. Fixed.

= 4.0.6 =

= New Features =

* Configurable New indicators on album covers and thumbnail images (See Settings page Table IX item 7 and 8).
* You can now easily import setting files other than your own backup. See OpaJaap-green.skin in Table VIII item 5. The file is located in wp-photo-album-plus/theme.

= Bug Fixes =

* The wppa+ admin menu structure has been revised to cope with several problems that made it impossible to save changes on wppa+ admin pages on some installations.

= Other Changes =

* Various cosmetic and functional improvements on the settings screen.

= 4.0.5 =

= New Features =

* Borders around fullsize images. See Settings page Table I item 22 and Table III item 7.
* You can now execute bulk actions on comments.
* Hebrew theme language files added

= Bug Fixes =

* Rating system stopped working at 4.0.4, fixed.

= 4.0.4 =

= Bug Fixes =

* When the coverwidth is set so that there will be more than 3 covers in a row, they will show up no longer in one column.

= Other changes = 

* Added height and width attributes to img tags. This may fix some layout problems with old browsers.

= 4.0.3 =

= Bug Fix =

* Repaired using get_bloginfo('wpurl') as opposed to get_bloginfo('url') to fix problem where sites using a non-default site address stopped displaying photos.

= Other changes =

* Changed display of phpinfo (Table X)

= 4.0.2 =

= Bug Fixes =

* Photo of the day admin caused a fatal error, fixed

= New Features =

* You can select *Top* and *Bottom* additionally to Right and Left for coverphoto display position (Table IV item 13). 
A spinoff of this enhancement are the folowing:
* The 2 and 3 column treshold values (Table I items 17 and 18) have been replaced by Maximum cover width (item 17).
This basically does the same as the 2 column treshod value, but is more user understandable and makes the 3col treshold superfluous.
Note: If you had set the 2 column treshold exactly to the column width before, 
you may need to change this setting as the old value (that will be used) will result in one column instead of two.
* There is a new item 18: Minimal cover text frame height, that makes it easier to get the covers equal in height. 
Additionally will you need to keep the coverphotos all landscape (with the same aspect ratio) or portrait to keep the covers equal in height.

= 4.0.1 =

* The Big Browse Buttons are now optional (Table II item 19).
* The BBB's will have no border.


= 4.0.0 =

= New features =

* WPPA+ Now supports multisite installations.

= Bug fixes =

* A clicking monkey will no longer be able to get the slideshow into a hangup state.

= Other changes =

* The sequence order of the slideshow parts (bars and photoframe) is now settable in the Settings screen (Table IX item 6.x). 
There is no longer a known reason as of to modify wppa-theme.php.
* The Big Browse Buttons are now invisible, but have a title and a cursor and have the size of half the slideframe each.
* The Filenames now comply with the wp coding standards.
* The Photo Links can be set to overrule with the photo specific link - if any. 
This behaviour can be set for all photo link types independantly. 
The 'Use photo specific link' linktype is hereby obsolete and has been removed as a selection option.
* Table X has been extended with WPPA+ constants and all other PHP settings.
* Border radius in css3 format added (IE9)

= Wish List =

* Cover photo above or below the text (Vertical shape of cover).
* Indication of NEW for photos and albums with configurable NEW period.


= 3.1.8 =

= Bug fixes = 

* fixed an errormessage in debug mode

= New features = 

* You can set the thumbnail popup image size now explicitly. (Before it was the unscaled thumbnail image)


= 3.1.7 =

= Bug Fixes =

* After introduction of the link with print option, all other linktypes failed. Fixed.


= 3.1.6 =

= New features =

* New link type added for thumbnails and topten thumbnails: the fullsize photo with a print button. 
This will open the fullsize photo in a new browser window and enables you to print the photo with the description below it.

= Bug Fixes =

* Fixed an RSS bug in displaying thumbnails.

= Other changes =

* Reverted the change made in version 3.1.3 for the algorithm to decide if the indicator must be printed. 
It turned out to create a bigger problem than it solved. (This change was made in 3.1.5 but not yet documented as such.


= 3.1.4 =

= Bug Fixes =

* The static text in the photo comment form and alert boxes is now properly translatable.
* The behaviour after input of incomplete comment has been corrected.

= Other changes =

* Cosmetic and reliability enhancements in slideshow.
* Update text 3.1.3 Other changes to fix the first item issue.


= 3.1.3 =

= Other changes =

* The algoritm to decide if the indicator [WPPA+ Photo display] must be printed has been improved. 
Only the first in a list of excerpts (archive or search results when the_excerpt() is used as opposed to the_content()) may be wrong.
You can correct this by adding the following line of code just prior to *the_excerpt();* in the template files involved: *global $wppa; $wppa['is_excerpt'] = true;*
* Uses display name rather than login name in comments on photos.

= 3.1.2 =

= Bug Fixes =

* Fixed breaking js execution caused by a newline in an comment edit.

= 3.1.1 =

= Bug Fixes =

* You can have single quotes in comments now.

= Other changes =

* Removed changelog prior to version 3.0.0
* Minor cosmetic changes

= 3.1.0 =

= New Features =

* A per photo based comment system has been added.
* Big Browsing Buttons. When hovering near the left and right edges of the fullsize image when the slideshow is stopped, big left (previous) and right (next) browse buttons appear.

= Enhancements =

* Admin pages load only when used, this results in less server memory usage and speed-up of all admin pages.
* The name and description under the fullsize images is now combined in a wppa+ box. You can still set fonts individually, you can also switch them on/of individually.
If you like the 'old' display method, this is still possible; see the explanation in /theme/wppa_theme.php.

= Bug Fixes =

* You can manipulate and delete Albums and Photos now even when their id is greater then 2147483647.

= 3.0.7 =

= Enhancements =

* The way the plugin is re-activated after an update has been changed due to the fact that wp does no longer run the activation hook after update.
You should no longer get the messages that the 'database rev is not yet updated' and 'i fixed that for you'. 
Manual re-initialization still remains possible with the settings page table VIII item 3.
* The horizontal alignment of the photo of the day widget content can be set to none, left, center or right on the photo of the day admin page.
The text goes along; if you want the photo and the text align differently, set alignment to --- none --- and use css (classes wppa-widget-photo and wppa-widget-text).
* Added script keyword: #last. %%photo=#last%% or %%mphoto=#last%% gives the last added photo. %%album, %%cover, %%slide=#last%% etc gives the last added album.
* Better qTranslate support for the photo of the day admin page.

= Bug fixes =

* In an archive, you will get a marker at the place of an wppa+ invocation rather than the display of javascript.

= 3.0.6 =

= New features =

* You can now easily disable the display of all text except the album title from the albumcover. Table II item 17.
* You can append &debug (?debug if it is the first argument) in the adress bar of the browser to switch debug mode on.
An optional integer can be set to set the php error reporting switches. Default = 6143 (E_ALL). Example: &debug=-1 (switches everything on: wppa debug, php's E_ALL and E_STRICT).
This feature can be anabled/disabled by the setting in Table IX item 5.
If switched on, the WPPA+ system will produce diagnostic messages, together with the normal php errors and warnings.
It works for both admin as well as site views. Links within the WPPA+ system include the debug switch (and optional value).
The main wp admin menu items are beyond the scope of this feature. Press the menuitem, append &debug to the adressbar here.
* You can optionally switch the filmstrip and/or the browsebar on in the slideshow widget.
* Clicking the counter (Photo xx of yy, or xx / yy in the mini version) will start/stop the slideshow.
* You can specify an album for the topten widget. Now it is usefull to have more than one topten widget by using different albums.
* A start has been made with 'keywords' in places of numbers. You can issue the script command: %%photo=#potd%% to use the photo of the day in a page or post.

= Enhancements =

* In a widget, the album cover text will appear above or below the cover photo. This can be set by the coverphoto left/right switch. Table IV item 13.
This works also for "thumbnail as covers".
* The Photo of the day widget photo will be centered horizontally, no padding setting is required anymore.
* The filmstrip will be half the normal size in widgets.

= 3.0.5 =

= Bug fixes =

* IMPORTANT Fix: All problems that are related to pre-rendering are fixed. 
The problems with themes like Thesis and plugins like the face-book-meta-tags-plugin that 
perform a pre-rendering of a post or excerpt are solved now. 
The restrictions on using the rating system (that did not work anyway) are no longer applicable.
* Under some circumstances when using qTranslate, the proper language file was not loaded. Fixed.

= Hot fixes after initial release =

* 001: Fixed erroneous link in albumcover

= 3.0.4 =

= New features = 

* You can back-up and restore the settings and reset them to default values.
* Added table X in the settings panel, being a read only table displaying the php configuration.

= Enhancements =

* Improved error reporting and documentation of limitations in admin pages.

= Bug fixes =

* Fixed an no harmfull warning in photo of the day widget admin page.
* Removed a superfluous p-opening tag.

= Known problems =

* The Thesis theme has a problem with the <input > field that is required for the rating system. (nonce field).
The rating system should be disabled in that case (using Thesis).

= Hotfixes after initial release =

* 001: Added class wppa-slideshow-browse-link to enable hiding it with display: none. This was a special cutomer request and not an error.
* 002: Photo specific link will now also be copied during a copy photo action.
* 003: Removed an empty <p></p> right before a wppa invocation. 
* 004: Fix for facebook plugin (?)

= 3.0.3 =

= New features = 

* Increase configurability of links from album cover photo.
* A re-initializing action (Table VIII, item 3) has been added. This will be helpfull in multiblog (network) sites.

= Bug fixes =

* Includes all hot-fixes since 3.0.2.000.
* Minor cosmetic changes in the new settings page.

= Hot fixes after initial release =

= Known problems =

* The Thesis theme has a problem with the <input > field that is required for the rating system. (nonce field).
The rating system should be disabled in that case (using Thesis).


= 3.0.2 =

= New features = 

* The Settings page has been rewritten to make it more user friendly. 
All settinges are grouped into tables, and are identifiable by its table number and item number.
* Increased link configurability. You can link mphotos and thumbnails now also to the plain file. 
You can define photo specific links: All photos can have a unique link url and title. 
You can choose to use that link in all 5 different places where a photo link can be configured. 
Please check the link settings in the Settings screen, Table VI. You might want to change something there.
* Additionally to the family and size you can now also set the colors for the fonts used in wppa+.

= Bug fixes =

* Includes all hot-fixes since 3.0.1.000.
* The mouseover effect now also works on TopTen thumbnail images.
* Fix for Column width = auto. This works now the same like %%size=auto%%

= Hot fixes after initial release =

* 001: Made noncefield conditional to rating system enabled
* 002: Admin functions now also work in SSL admin
* 003: If an image has a link configured, the cursor will be a pointer (hand).


= 3.0.1 =

= New features =

* WPPA+ Now supports Multi language sites that use qTranslate. 
Both album and photo names and descriptions follow the qTranslate multilanguage rules.
In the Album Admin page all fields that are multilingual have separate edit fields for each activated language.
For more information on multilanguage sites, see the documentation of the qTranslate plugin.

= Enhancements =

* You can link media-like photos (those made with %%mphoto=..%%) to a different (selectable) page, either to a full-size photo on its own or in a slideshow/browseable.
* You will now get a warning message inclusive an uncheck of the box if your jQuery version does not support delay and therefor not the fadein after fadeout feature.
* Improved consistency in the layout of the different types of navigation bars.

= Pending enhancement requests =

* Multisite support
* More than one photo of the day
* Fullscreen slideshow

= Known bugs =

* None, if you find one, please let me know and i will fix 'm

= Hot fixes since the initial release =

* 001: HTML in photo of the day widget fixed
* 002: Fixed 'Start undefined'
* 003: You can now rotate images when they are already uploaded
* 004: Photo of the day option change every pageview added
* 005: Photo of the day split padding top and left
* 006: If Filmstrip is off you can overrule display filmstrip by using %%slidef=.. and %%slideonlyf=..
* 007: Clear:both added to thumbnail area
* 008: Fixed a problem where photos were not found if the number of found photos was less than or equal to the photocount treshold value
* 009: You can now upload zipfiles with photos if your php version is at least 5.2.7.
* 010: Fixed a Invalid argument supplied for foreach() warning in upload.
* 011: Fixed a wrong link from thumbnail to slideshow.
* 012: Changed the check for minimal size of thumbnail frame.
* 013: Fixed a problem where a bullet was displayed as &bull in some browsers.
* 014: Fixed a problem where the navigation arrows in the filmstrip were not hidden if the startstop bar was disabled.
* 015: New feature: If slideshow is enabled, double clicks on filmthumbs toggles Start/stop running slideshow. Tooltip documents it.
* 016: Slides and filmthumbs have the same sequence now when ordering is Random.
* 017: Some people do not read the settings page and get in panic when they see two or three colums of album covers after an upgrade, so i changed the defaults for the columns tresholds to 1024.
* 018: TopTen widget initializes runtime also now, just in case it is the first.
* 019: Fixed alignment problem in multi column, unequal cover heights.
* 020: Photo of the day widget now also initializes runtime.
* 021: Fix for pre-rendering themes like thesis.

= 3.0.0 =

= New features =

* You can link thumbnails to different (selectable) page, either to a full-size photo on its own or in a slideshow/browseable.
* You can link the photo of the day to a full-size photo on its own or in a slideshow/browseable or to the current photos album contents display (thumbnails).
* You can set the thumbnail display type to --- none ---. This removes the 'View .. photos' link on album covers, while keeping the 'View .. albums' link.
* When the Slideshow is disabled and there are more than the photocount treshold photos, the 'Slideshow'-link is changed to 'Browse photos' with the corresponding action.
* The front end (theme) is now seperately translatable. Only 43 words/small sentences need translation. A potfile is included (wppa_theme.pot).
* You can now easy copy a single photo to an other album in the Photo Albums -> Edit album admin page.
* There is a new script command: %%mphoto=..%%. This is an alternative for %%photo=..%% and displays the single photo with the same style as normal media photos with background and caption. No associated links yet.

= Bug fixes =

* The 'Slideshow' and 'Browse photos' link now also point to the page selected in the edit album form.

= Hot fixes after initial release =

* 001: [caption] is not allowed to have html (wp restriction), tags are now removed from photo description for use with [caption]
* 002: Fixed a breadcrumb nav that did not want to hide itself when Display breadcrumb was unchecked
* 003: You can now import media photos from the upload directory you specified in the wp media settings page also when it is not the default dir.
* 004: Fixed a problem where, when pagination is off, in a mixed display of covers and thumbs, the covers were not shown.
* 005: added class size-medium to mphotos ([caption])


= Notes =

* Due to internal changes, there is a speed-up of apprix 30% with respect to earlier versions.
* Due to internal changes, you will have to re-modify wppa_theme.php if you used a modified one. wppa_theme is now a function.
* Due to internal changes, it is most likely that this problem will be fixed: http://wordpress.org/support/topic/plugin-wp-photo-album-plus-page-drops-when-activated-on-page?replies=24#post-1965780
* If you had set *No Links* for thumbnails, you will have to set it again.

== Known issues ==

* The Big Browse Buttons are transparent. IE 6 does not know about transparency. Therefor the slidshow will not display properly in IE6 with BBB's enabled.
* The plugin My Live Signature completely destroys the display from wppa+ and also damages other filters. DO NOT INSTALL My Live Signature!
* The theme Moses from Churchthemer.com uses jQuery in unsafe mode. This conflicts with prototype. Therefor you can NOT use WPPA+ embedded lightbox.
* The plugin Shortcodes Ultimate formats the content and thereby damages the wppa+ generated code by a filter at priority 99. 
Set the wppa+ filter priority to at least 100 to deal with this conflicting situation. (Table IX item 10)

== About and Credits ==

* WP Photo Album Plus is extended with many new features and is maintained by J.N. Breetvelt, ( http://www.opajaap.nl/ ) a.k.a. OpaJaap
* Thanx to R.J. Kaplan for WP Photo Album 1.5.1, the basis of this plugin.

== Licence ==

WP Photo Album is released under the GNU GPL licence. ( http://www.gnu.org/copyleft/gpl.html )