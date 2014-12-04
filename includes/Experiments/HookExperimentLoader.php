<?php

namespace PlanOut\Experiments;

use Hooks;
use InvalidArgumentException;

class HookExperimentLoader implements ExperimentLoader {

	/**
	 * Collects experiments by running the PlanOutRegisterExperiments hook and
	 * then iterates over the collection to find an experiment with the specified
	 * name.
	 */
	public function load( $name ) {
		$experiments = array();
		Hooks::run( 'PlanOutRegisterExperiments', array( &$experiments ) );

		foreach ( $experiments as $experiment ) {
			if ( $experiment->name() === $name ) {
				return $experiment;
			}
		}

		throw new InvalidArgumentException(
			"Couldn't load the \"{$name}\" experiment. Have you registered your PlanOutRegisterExperiments handler?"
		);
	}
}
