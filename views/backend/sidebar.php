<?php
if (Dispatcher::getAction() != 'view'):
	$settings = Plugin::getAllSettings('downloads');
?>
<p class="button"><a href="<?php echo get_url('plugin/downloads/'); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download.png" align="middle" alt="Downloads" /> Downloads</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/files/add'); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-add.png" align="middle" alt="Add a Download" /> Add Download</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/history'); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-history.png" align="middle" alt="History" /> History</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/categories'); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-categories.png" align="middle" alt="Download Categories" /> Categories</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/categories/add'); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-categories-add.png" align="middle" alt="Add a Download Category" /> Add Category</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/settings'); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-settings.png" align="middle" alt="Download Settings" /> Settings</a></p>
<?php endif; ?>