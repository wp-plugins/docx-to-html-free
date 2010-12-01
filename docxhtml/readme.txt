=== DOCX to HTML Free ===
Contributors: starsites
Donate link: http://starsites.co.za/
Tags: doc, parse, html, generate, image, upload
Requires at least: 3.0
Tested up to: 3.0.2

This plugin will process an uploaded .docx file, extracting all the contents as a post.

== Description ==

Everybody uses Word documents or related Word Processing files. Now you can quickly post your .docx files as posts!

In the Administration you will have a new menu item where you will be able to set a few per use settings like the
post title etc. as well as a field to upload a .docx file (Microsoft Word 2007 file). The Uploaded files as well
as all temporary files will be removed when the script completes, saving you disk space.

Get the Premium version of DOCX to HTML from [WP Plugins](http://wpplugins.com/plugin/305/docx-to-html-premium "Premium WordPress Plugins"). Premium version can do automatic formatting based on the Word file provided.
Created and maintained by [Starsites/Jacotheron](http://www.starsites.co.za). We will answer queries and provide support from our website [Starsites.co.za](http://www.starsites.co.za).

Please note:
*     The normal .doc will not work. Please do not upload any other type of file, the script will not be
able to handle the file thus you waste a lot of proccessing resources for the script.

*     This script might sometimes be very Resource Intensive as well as taking a long time. On a 7MB file it took about 3
seconds with the CPU usage raising quite a lot.

*     This script will not modify the time limit for proccessing large files and it will also not modify the maximum file
size for upload.

*     You can't run both the Free and the premium version on the same WordPress installation, you will find errors of functions allready defined

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
seconds for a single 7 MB file. This is still a lot faster than any other method I could find to
convert a Word Document to a WordPress Post.

= What is required on my server to run this plug-in =

This plugin was written as a PHP5 script. It might not work on earlier versions.
This plugin requires the following extensions for PHP to be active: ZLib; XML Parser; GD Image Library.

= After I uploaded a document and it was parsed, the result says other than what WordPress says. What happened? =

This is for example you parsed a document and it displays an error, but you find the content in the Posts or
It displays no error but the new post does not exist.
This can only be caused by two or more documents being uploaded and parsed at the same time and another one finishing
a moment after the first one before the last result could be shown on the user's screen and thus overwriting the
previous result. It is not advisable to parse multiple files at the same time as this will result in very high
load on the server's resources (this is also the reason for only able to parse a single file at a time). 

= What additional Features does the Premium version, which the Free version does not have =

Most importantly, the Premium version have support. If you have a problem we will work with you to help solve the problem ASAP.
The Premium version also includes image Resizing. You can specify a maximum width and all images will be smaller or equal to that width.
Further the Premium version includes easy Tagging of posts, overwriting existing posts, Schedule posts, custom Slugs for posts, Date of 
post modifications and up to 2 Categories for the post.

= Does this plugin add anything to front pages as advertising =

No. This plugin does not advertise itself on your blog's front-end, and does not provide any method for anyone else to know 
that you are using this plugin. However there will be a notification at the top of each of this plugin's own pages with a 
link to the Premium version. This plugin will continue to work (it is not a trail), even if you do not buy the Premium version.

= I can't find the images listed in the Media Library =

We are currently working on fixing this problem, but till then the images will remain in the folder: wp-content/uploads/media/. The 
plugin provides a page that will list all the folders inside that media folder as well as all the images. This is so that you can 
use those images again, by linking to them directly.

Previously the media folder have been inside this plugin's directory and resulted in lost images after an upgrade.

== Changelog ==

= 1.1 =
* Fixes Bug: Images not dispolayed in the correct order.
* Fixes Bug: Images not in a global folder, but in a folder inside plugin (resulting in loss of images when plugin is removed or upgraded).
* Feature Add: View list of images.
* Feature Add: Help included.

= 1.0 =
* Inital Release

== Upgrade Notice ==

= 1.1 =
This upgrade fixes a few bugs, and add a few new features

= 1.0 =
Initial Release

== Quick Features ==

1. Upload Docx file as a post
1. Uploads images inside .docx into the post
1. Fast Content Extraction
