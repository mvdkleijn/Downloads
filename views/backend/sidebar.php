<?php if (Dispatcher::getAction() != 'view'): ?>
<p class="button"><a href="<?php echo get_url('plugin/downloads'); ?>"><img src="images/download.png" align="middle" alt="snippet icon" />Downloads</a></p>
<p class="button"><a href="<?php echo get_url('plugin/downloads/add'); ?>"><img src="images/add-download.png" align="middle" alt="snippet icon" />Add Download</a></p>
<?php endif; ?>