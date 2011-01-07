<?php
/*
Plugin Name: DOCX to HTML Free
Plugin URI: http://www.starsites.co.za/
Description: This plugin will upload a docx file to extract all the contents (text/images) and then post the contents.
Version: 1.2.2
Author: Jaco Theron
Author URI: http://www.starsites.co.za/
*/
/*
DOCX to HTML Free WordPress Plugin
Copyright (C) 2010  Jacotheron(Starsites) - info@starsites.co.za

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
// create custom plugin settings menu
add_action('admin_menu','docxhtml_create_menu');
function docxhtml_create_menu() {
    //create new top-level menu
    add_menu_page('DOCX to HTML','DOCX to HTML','publish_posts','docxhtml','docxhtml_upload_page',plugins_url('/images/icon.png',__FILE__));
    add_submenu_page('docxhtml','DOCX to HTML Results','Last Result','publish_posts','docxhtml_result','docxhtml_results_page');
    add_submenu_page('docxhtml','DOCX to HTML Logs','Result Logs','manage_options','docxhtml_logs','docxhtml_logs_page');
    add_submenu_page('docxhtml','DOCX to HTML Settings','Settings','manage_options','docxhtml_settings','docxhtml_settings_page');
    add_submenu_page('docxhtml','DOCX to HTML Help','Help','publish_posts','docxhtml_help','docxhtml_help_page');//call register settings function
    add_action('admin_init','docxhtml_register_settings');
}
function docxhtml_register_settings() {
    //register our settings
    register_setting('docxhtml-settings-group','docxhtml_publish');
    register_setting('docxhtml-results-group','docxhtml_last_result');
}
function docxhtml_upload_page() {
    ?>
    <div class="wrap">
        <div class="updated"><p><strong>You are using the Free version. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium</a> version now!</strong></p></div>
        <h2>DOCX to HTML Upload</h2>
    <form method="post" action="<?php echo plugins_url()."/docx-to-html-free/upload.php"; ?>" enctype='multipart/form-data'>
        <?php settings_fields( 'docxhtml-upload-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row" style="">New Post Title <span style="color:red;">*</span></th>
                <td style=""><input id="post_title" type="text" size="30" name="docxhtml_post_title" value=""
                    style="font-size:1.7em; line-height: 100%; outline: medium none; padding: 3px 4px; width:300px;" /></td>
                <td style=""><span class="description">The name of the new Post/Page if the proccess was succesful.<br />Required.</span></td>
            </tr>
            <tr valign="top">
                <th scope="row">Publish State <span style="color:red;">*</span></th>
                <?php $post_publish = get_option('docxhtml_publish'); ?>
                <td><select name="docxhtml_post_status" style="width:100px;" >
                    <option value="draft" <?php echo ($post_publish == "draft") ? 'selected="selected"':"" ?>>Draft</option>
                    <option value="publish" <?php echo ($post_publish == "publish") ? 'selected="selected"':"" ?>>Publish</option>
                    <option value="pending" <?php echo ($post_publish == "pending") ? 'selected="selected"':"" ?>>Pending</option>
                    <option value="future" <?php echo ($post_publish == "future") ? 'selected="selected"':"" ?>>Future</option>
                </select></td>
                <td><span class="description">Post/Page's published state. This is the state of the Post/Page after the content is processed.</span></td>
            </tr>
            <tr valign="top">
                <th scope="row">Page or Post <span style="color:red;">*</span></th>
                <td><select name="docxhtml_post_type" style="width:100px;" >
                    <option value="post">Post</option>
                    <option value="page">Page</option>
                </select></td>
                <td><span class="description">Is the current document a Page or a Post.<br />Default to Post.</span></td>
            </tr>
            <tr valign="top">
                <th scope="row">New Post Categories <span style="color:red;">*</span></th>
                <td><?php wp_dropdown_categories(array('hide_empty'=>0,'name'=>'docxhtml_post_cat1','selected'=>$category->parent,'hierarchical'=>true)); ?>
                </td>
                <td><span class="description">Select the Category that should contain your Post.<br />
                        Required. Default to First Category of your Blog</span></td>
            </tr>
            <tr valign="top">
                <th scope="row">Docx File <span style="color:red;">*</span></th>
                <td><input type="file" name="docxhtml_file" size="30" value="" style="line-height: 100%; outline: medium none; padding: 3px 4px; width:300px;" /></td>
                <td><span class="description">Select the .docx file on your computer that should be processed.<br /><strong>Your Maximum File Size (according to the server) is: <?php
                    $UPLOAD_MAX_SIZE = ini_get('upload_max_filesize');
                    $unit = strtoupper(substr($UPLOAD_MAX_SIZE,-1));
                    if(!is_int($unit)){
                        echo $UPLOAD_MAX_SIZE;
                    } else {
                        $gigabytes = $UPLOAD_MAX_SIZE/1073741824;
                        $megabytes = $UPLOAD_MAX_SIZE/1048576;
                        $kilobytes = $UPLOAD_MAX_SIZE/1024;
                        $bytes = $UPLOAD_MAX_SIZE;
                        if($gigabytes > 1){
                            echo $gigabytes."G";
                        } elseif($megabytes > 1){
                            echo $megabytes."M";
                        } elseif($kilobytes > 1){
                            echo $kilobytes."K";
                        } else {
                            echo $bytes;
                        }
                    }
                ?></strong></span></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Create Post Now!') ?>" /></p>
    </form>
    </div>
    <?php
}
function docxhtml_results_page() {
    $data = get_option('docxhtml_last_result');
    ?>
    <div class="wrap">
        <div class="updated"><p><strong>You are using the Free version. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium</a> version now!</strong></p></div>
        <h2>DOCX to HTML Results</h2>
        <table class="widefat fixed">
            <tr valign="top">
                <th scope="row" style="width:150px;">Last Upload Result:</th>
                <td><?php echo $data; ?></td>
            </tr>
        </table>
        <p><span style="padding-top:10px;" ><a href="./admin.php?page=docxhtml" class="button-primary" >Create Another One</a></span></p>
    </div>
    <?php
}
function docxhtml_logs_page() {
    $dir = dirname(__FILE__);
    $length = strlen($dir);
    if($dir[$length -1] != "/"){
        $open = fopen(dirname(__FILE__)."/docxhtml-log.csv", "r");
    } else {
        $open = fopen(dirname(__FILE__)."./docxhtml-log.csv", "r");
    }
    $contentarr = array();
    while(!feof($open)){
        $contentarr = array_merge($contentarr, array(fgetcsv($open)));
    }
    $close = fclose($open);
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Logs</h2>
        <div class="updated"><p><strong>You are using the Free version. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium</a> version now!</strong></p></div>
        <div class="updated"><p><strong>When experiencing problems, you can contact me through my website and copy this  into the email. 
            This will allow me to identify your problem very quickly and then also solve it a lot faster.</strong></p></div>
        <table class="widefat fixed" style="margin-bottom:4px;">
            <thead valign="top"><tr>
                <th scope="row" style="width:40px;">Nr:</th>
                <th scope="col" style="width:150px;">Date:</th>
                <th scope="col">Result:</th>
                <th scope="col">File Name:</th>
                <th scope="col">File Size:</th>
                <th scope="col" style="max-width:100px;">PHP Version:</th>
                </tr>
            </thead>
            <tfoot valign="top"><tr>
                <th scope="col">Nr</th>
                <th scope="col">Date</th>
                <th scope="col">Result</th>
                <th scope="col">File Name</th>
                <th scope="col">File Size</th>
                <th scope="col">PHP Version</th>
            </tr></tfoot>
            <?php 
            foreach($contentarr as $key => $value){
                if(is_array($value)){
                    if($key != 0){
                        echo "<tbody><tr>";
                        echo "<td>".$key."</td>";
                        echo "<td>".$value[0]."</td>";
                        echo "<td>".$value[1]."</td>";
                        echo "<td>".$value[2]."</td>";
                        echo "<td>".$value[3]."</td>";
                        echo "<td>".$value[4]."</td>";
                        echo "</tr></tbody>";
                    }
                }
            }
            ?>
        </table>
    </div>
    <?php
}
function docxhtml_settings_page() {
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Settings</h2>
        <div class="updated"><p><strong>You are using the Free version. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium</a> version now!</strong></p></div>
        <?php if(isset($_GET['updated'])=="true"){ ?>
            <div class="updated"><p><strong>DOCX to HTML: Your settings have been saved successfully.</strong></p></div>
        <?php }elseif(isset($_GET['updated']) && $_GET['updated'] != "true"){ ?>
                <div class="updated"><p><strong>DOCX to HTML: Your settings could not be updated.</strong></p></div>
        <?php } ?>
        <form method="post" action="options.php">
            <?php settings_fields('docxhtml-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="docxhtml_publish">New Post Publish State</label></th>
                    <?php $post_publish = get_option('docxhtml_publish'); ?>
                    <td><select id="docxhtml_publish" name="docxhtml_publish" style="width:100px;" >
                        <option value="draft" <?php echo ($post_publish == "draft") ? 'selected="selected"':"" ?>>Draft</option>
                        <option value="publish" <?php echo ($post_publish == "publish") ? 'selected="selected"':"" ?>>Publish</option>
                    </select></td>
                    <td><span class="description">Post's published state. This is the state of the post after the content is proccessed.<br />
                        This Setting is overwritable form the Upload form.</span></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
        </form>
    </div>
    <?php
}
function docxhtml_help_page() {
    ?>
    <div class="wrap">
        <h2>DOCX to HTML Help</h2>
        <div class="updated"><p><strong>You are using the Free version. Get the <a href="http://wpplugins.com/plugin/305/docx-to-html-premium">Premium</a> version now!</strong></p></div>
        <table class="widefat fixed" style="margin-bottom:4px;">
            <thead><tr valign="top">
                <th scope="col" style="width:20%;"><strong>Question:</strong></th>
                <th scope="col"><strong>Answer:</strong></th>
            </tr></thead>
            <tfoot><tr valign="top">
                <th scope="col"><strong>Question:</strong></th>
                <th scope="col"><strong>Answer:</strong></th>
            </tr></tfoot>
            <tbody><tr valign="top">
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
                        <li>"You are not authorised to upload files."<br />
                            <span class="description">You are not logged in or your account does not allow you to create a post.</span></li>
                        <li>"POST exceeded maximum allowed size. Post size is: some_size - Maximum allowed is: another_size"<br />
                            <span class="description">Your server is set to not handle more than the second amount of data in a single request.
                            The first amount is the amount that is required for the file you are trying to upload.</span></li>
                        <li>"No upload found in $_FILES for 'docxhtml_file'"<br />
                            <span class="description">The uploaded file could not be found.</span></li>
                        <li>Upload Related Errors
                            <ol>
                                <li>"The uploaded file exceeds the upload_max_filesize directive in php.ini"<br />
                                    <span class="description">Your server is set to not allow the upload of files as large as the one you are trying to upload.</span></li>
                                <li>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."<br />
                                    <span class="description">The form is set to handle only smaller file sizes that what you are trying to upload.</span></li>
                                <li>"The uploaded file was only partially uploaded."<br />
                                    <span class="description">Somehow the file did not finish uploading.</span></li>
                                <li>"No file was uploaded."<br />
                                    <span class="description">The file was not uploaded.</span></li>
                                <li>"Missing a temporary folder."<br />
                                    <span class="description">The folder where the uploaded file was supposed to be is not found.</span></li>
                            </ol>
                        </li>
                        <li>"Upload failed is_uploaded_file test."<br />
                            <span class="description">The test to make sure that the file was uploaded, failed.</span></li>
                        <li>"File has no name."<br />
                            <span class="description">Uploaded file have no name and thus it can't be a file.</span></li>
                        <li>"File exceeds the maximum allowed size."<br />
                            <span class="description">The file is larger than what the system can handle.</span></li>
                        <li>"File size outside allowed lower bound."<br />
                            <span class="description">The file size is negative and tus it can't be a file.</span></li>
                        <li>"Invalid file extension."<br />
                            <span class="description">This plugin requires an extension of .docx. If it is not a docx, this plugin can't work.</span></li>
                        <li>"The post could not be inserted. An unknown error occured."<br />
                            <span class="description">While adding the post into the database, an error occured.</span></li>
                        <li>"Not enough information provided to parse the file."<br />
                            <span class="description">There are required information that was not found.</span></li>
                        <li>"The file's contents could not be extracted to use."<br />
                            <span class="description">The content could not be extracted to start the operation.</span></li>
                        <li>"The temporary files created during the parse could not be deleted. The contents, however, might still have been extracted."
                            <br /><span class="description">While this plugin works, temporary directories are created which should be removed afterwards.</span></li>
                    </ol>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">What is the main purpose of this plugin's log?</th>
                <td>If you are experiencing problems with this plugin (maybe after a WordPress upgrade), the issues will most probably be logged inside the Logs and 
                    when contacting me for support, you can provide the log so that I can see what went wrong and release a fix in record time. Information collected by the log is: 
                    Date &amp; Time; The result of the upload; The file name; The file size; The PHP version. Information is collected at each upload. 
                    Information will not be sold provided to any third party without your consent.<br />
                    <span class="description">This plugin does not distribute this information automatically to any server. This information is stored in a file.</span></td>
            </tr>
            <tr valign="top">
                <th scope="row">How can I be contacted?</th>
                <td>If you have suggestions for future releases or have a problem, you can contact me from my website: <a href="http://www.starsites.co.za" target="_blank">http://www.starsites.co.za</a>.<br />
                You can also request help from our Support Site: <a href="http://support.starsites.co.za" target="_blank">http://support.starsites.co.za</a>. Free version users' queries will have a lower priority
                than Premium users' queries, but we do offer support for the Free users.</td>
            </tr></tbody>
        </table>
    </div>
    <?php
}