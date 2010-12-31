=== DOCX to HTML Premium ===
Contributors: Jacotheron/starsites
Donate link: http://starsites.co.za/
Tags: doc, parse, html, generate, image, upload
Requires at least: 3.0
Tested up to: 3.0.3
Stable tag: 1.2

This plugin will process an uploaded .docx file, extracting all the content as a post.

== Description ==

In the Administration you will have a new menu item where you will be able to set a few per use settings like the
post title etc. as well as a field to upload a .docx file (Microsoft Word 2007 file). The Uploaded files as well
as all temporary files will be removed when the script completes.

Please note:
*     The normal .doc will not work. Please do not upload any other type of file, the script will not be
able to handle the file thus you waste a lot of proccessing resources for the script.

*     This script might sometimes be very Resource Intensive as well as taking a long time.

*     This script will not modify the time limit for proccessing large files and it will also not modify the maximum file
size for upload.

*     You can't run both the Free and the premium version on the same WordPress installation, you will find errors of functions 
allready defined. Please disable the Free version if you have the Premium version.

This plug-in will have a Global Settings page where settings can be modified for default behaviour etc.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plug-in folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set the Global Settings and start uploading your Documents

== Frequently Asked Questions ==

= What file type can this plug-in proccess =

This plug-in can only process .docx files.

= How long does it take to parse the file =

Depending on your server and hardware allocated to your WordPress installation it can take about 3
seconds for a single 7 MB file on a weak server or less than a second for much larger files. This is
still a lot faster than any other method I could find to convert a Word Document to a WordPress Post/Page.

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

== Quick Features ==

1. Upload Docx file as a Post/Page
1. Uploads images inside .docx into the Post/Page
1. Fast Content Extraction
