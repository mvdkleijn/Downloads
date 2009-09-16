<h1>Downloads Help</h1>

<h2>Set Up the plugin:</h2>
<p><strong>1.</strong> Be sure to CHMOD the folder plugins/downloads/download_files to 777.</p>
<p><strong>2.</strong> Use the <a href="<?php echo get_url('plugin/downloads/settings'); ?>">settings page</a> to update your site wide settings to suit.<br /><strong>Important: This plugin runs in two modes - Basic and Advanced. When first installed, Basic is the default setting. You can change this in the settings page.</strong></p>
<p><strong>3.</strong> Create a new page (or choose an old page) that we'll use to serve out files from. Give it the Page Type "<strong>Download Page</strong>".</p>

<h2>Categories</h2>
<p>You can add categories to help organise your downloads.</p>
<p>Each category can have a description and you can choose whether to publish this category to the front end of the site (covered in more detail below).</p>
<p>Use the sidebar to navigate through the category section</p>

<h2>Downloads</h2>
<p>There are a number of configuration options for each download. The best way to learn them is to get stuck in and start trying variations. <strong>It may be worth switching to advanced mode if you haven't done so already.</strong> This is where the real power of this plugin is over other similar offerings.</p>

<h2>Displaying Downloads on the front of your site:</h2>
<p>To display your downloads on the front use the <small>listDownloads()</small> function.</p>
<p>You can use this function out of the box to display all downloads and categories in a big list:</p>
<pre>listDownloads();</pre>
<p>Or you can pass along the following variables:</p>
<pre>listDownloads(downloads_id[INT], category_id[INT], displayCategoryName[BOOLEAN], displayCategoryDescription[BOOLEAN], displayDownloadDescription[BOOLEAN]);</pre>
<p>So this code:</p>
<pre>listDownloads('1', '', FALSE, FALSE, TRUE);</pre>
<p>Will display a link to the download which has an ID of '1'. It won't show the Category or describe the category that it belongs to, but it will show the description you have on file for the download</p>
<p>And this:</p>
<pre>listDownloads('', '2', TRUE, TRUE, FALSE);</pre>
<p>Will show all downloads in the Category with an id of '2'. It won't show any description of the file but will provide a header and description of the category.</p>