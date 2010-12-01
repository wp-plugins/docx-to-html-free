<?php
/*
Plugin Name: DOCX to HTML Free
Plugin URI: http://starsites.co.za/
Description: This plugin will upload a docx file to extract all the contents (text/images) and then post the contents.
Version: 1.1
Author: Jaco Theron
Author URI: http://starsites.co.za/
*/
// create custom plugin settings menu
add_action('admin_menu','docxhtml_create_menu');
register_deactivation_hook(__FILE__,'docxhtml_deactivate');
register_activation_hook(__FILE__,'docxhtml_activate');
function docxhtml_create_menu() {
    //create new top-level menu
    add_menu_page('DOCX to HTML','DOCX to HTML','publish_posts','docxhtml','docxhtml_upload_page',plugins_url('/images/icon.png',__FILE__));
    add_submenu_page('docxhtml','DOCX to HTML Results','Last Result','publish_posts','docxhtml_result','docxhtml_results_page');
    add_submenu_page('docxhtml','DOCX to HTML Files','Files','manage_options','docxhtml_files','docxhtml_files_page');
    add_submenu_page('docxhtml','DOCX to HTML Settings','Settings','manage_options','docxhtml_settings','docxhtml_settings_page');
    add_submenu_page('docxhtml','DOCX to HTML Help','Help','publish_posts','docxhtml_help','docxhtml_help_page');
    //call register settings function
    add_action('admin_init','docxhtml_register_settings');
}
function docxhtml_register_settings() {
    //register our settings
    register_setting('docxhtml-settings-group','docxhtml_publish');
    register_setting('docxhtml-results-group','docxhtml_last_result');
}
function docxhtml_deactivate() {
    //register our settings
    unregister_setting('docxhtml-settings-group','docxhtml_publish','docxhtml_unregister_void');
    unregister_setting('docxhtml-results-group','docxhtml_last_result','docxhtml_unregister_void');
}
function docxhtml_activate() {
    //register our settings
    //unregister_setting('docxhtml-settings-group','docxhtml_publish','docxhtml_unregister_void');
    //unregister_setting('docxhtml-results-group','docxhtml_last_result','docxhtml_unregister_void');
}
function docxhtml_unregister_void(){
    return "";
}
function docxhtml_settings_page() {
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Settings</h2>
        <div id="free" class="updated"><p><strong>You are currently using the Free version of DOCX to HTML. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium version</a> Now!</strong><br/>By <a href="http://www.starsites.co.za">Starsites</a></p></div>
        <?php if(isset($_GET['updated'])=="true"){ ?>
            <div id="message" class="updated"><p><strong>DOCX to HTML: Your settings have been saved successfully.</strong></p></div>
        <?php }elseif(isset($_GET['updated']) && $_GET['updated'] != "true"){ ?>
                <div id="message" class="updated"><p><strong>DOCX to HTML: Your settings could not be updated.</strong></p></div>
        <?php } ?>
        <form method="post" action="options.php">
            <?php settings_fields('docxhtml-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">New Post Publish State</th>
                    <?php $post_publish = get_option('docxhtml_publish'); ?>
                    <td><select name="docxhtml_publish" style="width:100px;" >
                        <option value="draft" <?php echo ($post_publish == "draft") ? 'selected="selected"':"" ?>>Draft</option>
                        <option value="publish" <?php echo ($post_publish == "publish") ? 'selected="selected"':"" ?>>Publish</option>
                    </select></td>
                    <td>Post's published state. This is the state of the post after the content is proccessed.</td>
                </tr>
            </table>
            <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
        </form>
    </div>
    <?php
}
function docxhtml_results_page() {
    $data = get_option('docxhtml_last_result');
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Results</h2>
        <div id="free" class="updated"><p><strong>You are currently using the Free version of DOCX to HTML. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium version</a> Now!</strong><br/>By <a href="http://www.starsites.co.za">Starsites</a></p></div>
        <form method="post" action="">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Last Upload Result:</th>
                <td><?php echo $data; ?></td>
            </tr>
        </table>
        </form>
    </div>
    <?php
}
function docxhtml_help_page() {
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Help</h2>
        <div id="free" class="updated"><p><strong>You are currently using the Free version of DOCX to HTML. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium version</a> Now!</strong><br/>By <a href="http://www.starsites.co.za">Starsites</a></p></div>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><strong>Question:</strong></th>
                <td><strong>Answer:</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">How do I use this plugin?</th>
                <td>Firstly the administrator of the blog should set a few settings on the Settings page.<br />
                    If that is done, you can just go to the DOCX to HTML page fill in the required information, select a .docx file on your computer and click on "Create Post Now!"</td>
            </tr>
            <tr valign="top">
                <th scope="row">What does this plugin do?</th>
                <td>This plugin reads your .docx file, extracting all the content from the document and posting it as a WordPress post.</td>
            </tr>
            <tr valign="top">
                <th scope="row">What formatting can this plugin extract?</th>
                <td>This plugin can extract all text from your document. Currently it can format Headings, Bold and normal. This plugin can also extract the images (in jpeg, png and gif formats) 
                    and resize them for your blog (according to the Image Maximum Width setting).</td>
            </tr>
            <tr valign="top">
                <th scope="row">Why can't the plugin format other types of data?</th>
                <td>These are the important formattings and you can always edit the created post and add the formatting there. Due to the complex inside of the docx files, it will take longer
                    to proccess the file if it should do more formatting.</td>
            </tr>
            <tr valign="top">
                <th scope="row">What is the main purpose of this plugin?</th>
                <td>This plugin is created to save time and thus money. Step 1: Enter the required information into the fields. Step 2: Select your file. Step 3: Click on "Create Post Now!" 
                    This plugin even displays the time it took to create the post from the file, giving you a way to measure the time being saved by using this plugin.</td>
            </tr>
            <tr valign="top">
                <th scope="row">What does the Error codes mean?</th>
                <td>
                    <ol>
                        <li>"You are not authorised to upload files."<br />You are not logged in or your account does not allow you to create a post.</li>
                        <li>"POST exceeded maximum allowed size. Post size is: some_size - Maximum allowed is: another_size"<br />Your server is set to not handle more than the second amount of data in a single request.
                            The first amount is the amount that is required for the file you are trying to upload.</li>
                        <li>"No upload found in \$_FILES for 'docxhtml_file'"<br />The uploaded file could not be found.</li>
                        <li>Upload Related Errors
                            <ol>
                                <li>"The uploaded file exceeds the upload_max_filesize directive in php.ini"<br />Your server is set to not allow the upload of files as large as the one you are trying to upload.</li>
                                <li>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."<br />The form is set to handle only smaller file sizes that what you are trying to upload.</li>
                                <li>"The uploaded file was only partially uploaded."<br />Somehow the file did not finish uploading.</li>
                                <li>"No file was uploaded."<br />The file was not uploaded.</li>
                                <li>"Missing a temporary folder."<br />The folder where the uploaded file was supposed to be is not found.</li>
                            </ol>
                        </li>
                        <li>"Upload failed is_uploaded_file test."<br />The test to make sure that the file was uploaded, failed.</li>
                        <li>"File has no name."<br />Uploaded file have no name and thus it can't be a file.</li>
                        <li>"File exceeds the maximum allowed size."<br />The file is larger than what the system can handle.</li>
                        <li>"File size outside allowed lower bound."<br />The file size is negative and tus it can't be a file.</li>
                        <li>"Invalid file extension."<br />This plugin requires an extension of .docx. If it is not a docx, this plugin can't work.</li>
                        <li>"The post could not be inserted. An unknown error occured."<br />While adding the post into the database, an error occured.</li>
                        <li>"Not enough information provided to parse the file."<br />There are required information that was not found.</li>
                        <li>"The file's contents could not be extracted to use."<br />The content could not be extracted to start the operation.</li>
                        <li>"The temporary files created during the parse could not be deleted. The contents, however, might still have been extracted."
                            <br />While this plugin works, temporary directories are created which should be removed afterwards.</li>
                    </ol>
                </td>
            </tr>
        </table>
    </div>
    <?php
}
function docxhtml_files_page() {
    include("filehandler.php");
    $list = dirList(dirname(__FILE__)."/../../uploads/media/", "filetree");
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Files</h2>
        <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript" ></script>
        <link rel="stylesheet" href="http://jquery.bassistance.de/treeview/demo/screen.css" type="text/css" />
        <link rel="stylesheet" href="http://jquery.bassistance.de/treeview/jquery.treeview.css" type="text/css" />
        <script type="text/javascript" src="http://jquery.bassistance.de/treeview/jquery.treeview.js" ></script>
        <script type="text/javascript" >
            $(document).ready(function(){
                $("#filetree").treeview();
            });
        </script>
        <div id="free" class="updated"><p><strong>You are currently using the Free version of DOCX to HTML. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium version</a> Now!</strong><br/>By <a href="http://www.starsites.co.za">Starsites</a></p></div>
        <div id="message" class="updated"><p><strong>Folders are created based on the name of the .docx file.</strong></p></div>
        <p>Files are located at: <code><?php echo dirname(dirname(dirname(__FILE__)))."/uploads/media/" ?></code></p>
        <?php echo $list; ?>
    </div>
    <?php
}
function docxhtml_upload_page() {
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Upload</h2>
        <div id="free" class="updated"><p><strong>You are currently using the Free version of DOCX to HTML. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium version</a> Now!</strong><br/>By <a href="http://www.starsites.co.za">Starsites</a></p></div>
    <form method="post" action="<?php echo plugins_url()."/docxhtml/upload.php"; ?>" enctype='multipart/form-data'>
        <table class="form-table">
            <?php settings_fields( 'docxhtml-upload-group' ); ?>
            <tr valign="top">
                <th scope="row">New Post Title</th>
                <td><input id="post_title" type="text" size="30" name="docxhtml_post_title" value=""
                    style="font-size:1.7em; line-height: 100%; outline: medium none; padding: 3px 4px; width:300px;" /></td>
                <td>The name of the new post if the proccess was succesful. This is required.</td>
            </tr>
            <tr valign="top">
                <th scope="row">New Post Categories</th>
                <td><?php wp_dropdown_categories(array('hide_empty'=>0,'name'=>'docxhtml_post_cat1','selected'=>$category->parent,'hierarchical'=>true)); ?></td>
                <td>Select the Category that should contain your post.</td>
            </tr>
            <tr valign="top">
                <th scope="row">Docx File</th>
                <td><input type="file" name="docxhtml_file" size="30" value="" style="line-height: 100%; outline: medium none; padding: 3px 4px; width:300px;" /></td>
                <td>Select the .docx file on your computer that should be parsed.</td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Create Post Now!') ?>" /></p>
    </form>
    </div>
    <?php
}