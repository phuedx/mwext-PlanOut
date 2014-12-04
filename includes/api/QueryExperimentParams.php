<?php

namespace PlanOut\Api;

use ApiBase;
use ApiMain;
use PlanOut\Experiments\ExperimentLoader;
use InvalidArgumentException;

/**
 * Loads an experiment and then queries all of its parameters.
 */
class QueryExperimentParams extends ApiBase {

	/**
	 * Constructs a new instance of the \PlanOut\Api\QueryExperimentParams class.
	 *
	 * @param \ApiMain $mainModule
	 * @param \PlanOut\Experiments\ExperimentLoader $experimentLoader
	 */
	public function __construct(
		ApiMain $mainModule,
		ExperimentLoader $experimentLoader = null
	) {
		parent::__construct( $mainModule, 'queryexperimentparams' );

		$this->experimentLoader = $experimentLoader;
	}

	/**
	 * Executes the action.
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$experimentName = $params['experiment_name'];
		$inputs = array(
			'userid' => $params['user_token'],
		);

		try {
			$experiment = $this->experimentLoader->load( $experimentName );
			$experiment->initialize( $inputs, null );

			$this->getResult()->addValue( 'query', 'experiment_params', $experiment->getParams() );
		} catch ( InvalidArgumentException $e ) {
			$this->dieUsage( "Couldn't load the \"{$experimentName}\" experiment.", 'couldnt_load_experiment' );
		}
	}

	/**
	 * Gets the allowed parameters.
	 *
	 * @return array
	 */
	protected function getAllowedParams() {
		return array(
			'experiment_name' => array(
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_TYPE => 'string',
			),
			'user_token' => array(
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_TYPE => 'string',
			),
		);
	}

	/**
	 * Gets the descriptions of the allowed parameters.
	 *
	 * @return array
	 */
	public function getParamDescription() {
		return array(
			'experiment' => 'The name of the experiment',
			'user_token' => "The user's token",
		);
	}
}