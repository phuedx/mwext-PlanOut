<?php

namespace PlanOut\Experiments;

/**
 * Defines the interface for an object capable of loading experiments.
 */
interface ExperimentLoader {

	/**
	 * Loads an experiment by name.
	 *
	 * @param string $name The name of the experiment
	 * @return \PlanOut\Experiments\SimpleExperiment
	 * @throws \InvalidArgumentException If there's no experiment with the given
	 *  name
	 */
	function load( $name );
}
