<?php

namespace PlanOut;

class Hooks {

	/**
	 * Handler for the ResourceLoaderGetConfigVars hook.
	 *
	 * Fires the `PlanOutRegisterExperiments` hook to collect the set of active
	 * experiments, then queries all of the experiment parameters and merges those
	 * parameters with the Resource Loader configuration variables.
	 *
	 * @param array &$vars The Resource Loader configuration variables
	 */
	public static function onResourceLoaderGetConfigVars( &$vars ) {
		global $wgUser;

		if ( $wgUser->isAnon() ) {
			return true;
		}

		$experiments = array();
		wfRunHooks( 'PlanOutRegisterExperiments', array( &$experiments ) );

		$inputs = array(
			'userid' => $wgUser->getId(),
		);
		$logger = null;

		foreach ( $experiments as $experiment ) {
			$experiment->initialize($inputs, $logger);
			$vars += $experiment->getParams();
		}

		return true;
	}
}
