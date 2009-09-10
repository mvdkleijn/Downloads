<?php

	/**
		TODO :	Write uninstall scripts	
		When JS issue is resolved in Wolf
		http://code.google.com/p/wolfcms/issues/detail?id=53
	**/

	if (Plugin::deleteAllSettings('downloads') === false) {
		Flash::set('error', __('We had a problem uninstalling the plugin settings.'));
		redirect(get_url('setting'));
	}
	else {
		Flash::set('success', __('You\'ve succesfully uninstalled the Downloads plugin.'));
		redirect(get_url('setting'));
	}