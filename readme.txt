=== DOCX to HTML Free ===
Contributors: Jacotheron/Starsites
Donate link: http://www.starsites.co.za/
Tags: doc, parse, html, generate, image, upload, extract, word, 2007, post, page
Requires at least: 2.8
Tested up to: 3.1
Stable tag: 1.3

This plugin will process an uploaded .docx file, extracting all the content as a post/page.

== Description ==

[postoffice link]: http://wordpress.org/extend/plugins/post-office/
            "Post Office"
[support link]: http://support.starsites.co.za
            "Starsites Support"
[website link]: http://www.starsites.co.za
            "Starsites Home"

[Post Office][postoffice link] Will continue with this plugin's functionality (Free Open Source for the full version).
Post Office can handle Excel files and Word files, with a lot more options than Post Office. [Upgrade Now][postoffice link].
			
Do you have a lot of Microsoft Word Documents that you want to post or create a page from? This plugin will do that for you.

In the Administration you will have a new menu item where you will be able to set a few per use settings like the
post title etc. as well as a field to upload a .docx file (Microsoft Word 2007 file). The Uploaded files as well
as all temporary files will be removed when the script completes.


Please note:
*     The normal .doc will not work. Please do not upload any other type of file, the script will not be
able to handle the file thus you waste a lot of proccessing resources for the script.

*     This script might sometimes be very Resource Intensive as well as taking a long time to complete based on your document.

*     This script will not modify the time limit for proccessing large files and it will also not modify the maximum file
size for upload.

*     You can't run both the Free and the Premium version on the same WordPress installation at the same time, you will find errors of functions 
allready defined. Please disable the Free version if you have the Premium version.

*     We only test to make sure the plugin works on the latest versions of WordPress. This plugin requires features that was first 
introduced in version 2.8 and should be able to work on WordPress 2.8 and further. The plugin, however might work on earlier versions, 
but we can't provide support for those versions.

*     Support for DOCX to HTML have been dropped. Use Post Office, and if you then require support, we will gladly help you.

This plug-in will have a Global Settings page where settings can be modified for default behaviour etc.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plug-in folder ('docx-to-html-free') into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set the Global Settings 
1. Start uploading your Documents

== Changelog ==

= 1.3 =
* Final Release: Post Office will continue this functionality.

= 1.2.2 =
* Fixed the script to create the post correctly.
* Support for Bold/Italics/Underlined/Superscripts & Subscripts/Strike-Through in Free Version.

= 1.2.1 =
* Fixed the directories (plugin not installing correctly)

= 1.2 =
* Improved the interface
* Support for Bold/Italics/Underlined/Hyperlinks/Superscripts & Subscripts/Strike-Through/Headings/Tables in Premium Version.

= 1.1 =
* Improved the plugins functionality to support Bold

= 1 =
* The Fisrt Public version

== Upgrade Notice ==

= 1.3 =
Final Release: Post Office will continue this functionality

= 1.2.2 =
Bug Fixed: Post not created due to file not existing.

= 1.2.1 =
Fixed the bug of directories.

= 1.2 =
Better functions and better interface.

= 1.1 =
Improvements in the plugin make it more user friendly and more efficient.

= 1.0.0 =
The first public version


== Frequently Asked Questions ==

= What file type can this plug-in proccess =

This plug-in can only process .docx files.

= How long does it take to parse the file =

Depending on your server and hardware allocated to your WordPress installation it can take about less than a
second for a single 8 MB file on a weak server or even faster on stronger servers. This is
much faster than any other method I could find to convert a Word Document to a WordPress Post/Page and it saves bandwidth.

= What is required on my server to run this plug-in =

This plugin was written as a PHP 5 script. It might not work on earlier versions (not tested on versions prior to PHP 5).
This plugin requires the following extensions for PHP to be active: ZLib; XML Parser; GD Image Library. These extensions 
are active in a default PHP 5 installation (it might have been disabled afterwards).

= After I uploaded a document and it was parsed, the result says other than WordPress says. What happened? =

This is for example you uploaded a document and it displays an error, but you find the content in the Posts or
It displays no error but the new post does not exist.
This can only be caused by two or more documents being uploaded and parsed at the same time and another one finishing
a moment after the first one before the last result could be shown on the user's screen and thus overwriting the
previous result. It is not advisable to parse multiple files at the same time as this will result in very high
load on the server's resources (this is also the reason for only able to parse a single file at a time). 

= When the State of the new post is set to Publish, the post/page's markup is not standards complient. Why is this? =

This is caused by the function WordPress uses to insert the post. The function tries to add tags and other information to 
the output of our script. This results in non standards complient code published in the post/page. We have tried solving it,
but then it was worse than now (we will continue to research solutions). 
If you want standards complient markup, set the state to draft and open the post/page in the edit page, where you can publish
 it from. This adds only the correct tags.

= What formattings can be recognised by this plugin? =

Currently this plugin can recognise Bold/Italics/Underlined/Strike-Through.
This plugin can also extract text from formatted WordArt text, but it is still not possible to extract the formattings.

The Premium version can recognise Headings/Links/Tables as an addition to the above mentioned features.

== Quick Features ==

1. Upload Docx file as a Post/Page
1. Uploads images inside .docx into the Post/Page
1. Fast Content Extraction
