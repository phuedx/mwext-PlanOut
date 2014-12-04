<?php

namespace PlanOut\Experiments;

use Vimeo\ABLincoln\Experiments\SimpleExperiment as BaseSimpleExperiment;

/**
 * A convenience class that disables the
 * `\Vimeo\ABLincoln\Experiments\SimpleExperiment` constructor so that the
 * experiments can be initialised and experiment parameters queried immediately
 * after the `PlanOutRegisterExperiments` hook has run.
 */
abstract class SimpleExperiment extends BaseSimpleExperiment {

	public function __construct() {

		// Force \Vimeo\ABLincoln\Experiments\AbstractExperimentTrait to initialise,
		// amongst other things, the name of the experiment.
		parent::__construct( array() );
	}
}
