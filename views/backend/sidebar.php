<?php if (Dispatcher::getAction() != 'view'): ?>
<p class="button"><a href="<?php echo get_url('plugin/downloads'); ?>"><img src="../wolf/plugins/downloads/images/download.png" align="middle" alt="Downloads" /> Downloads</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/add'); ?>"><img src="../wolf/plugins/downloads/images/download-add.png" align="middle" alt="Add a Download" /> Add Download</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/categories'); ?>"><img src="../wolf/plugins/downloads/images/download-categories.png" align="middle" alt="Download Categories" /> Categories</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/categories/add'); ?>"><img src="../wolf/plugins/downloads/images/download-categories-add.png" align="middle" alt="Add a Download Category" /> Add Category</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/settings'); ?>"><img src="../wolf/plugins/downloads/images/download-settings.png" align="middle" alt="Download Settings" /> Settings</a></p>
<?php endif; ?>