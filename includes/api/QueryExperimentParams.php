<?php

namespace PlanOut\Api;

use ApiBase;
use ApiMain;
use PlanOut\Experiments\ExperimentLoader;
use PlanOut\Experiments\HookExperimentLoader;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use PlanOut\Log\EventLoggingLogger;

/**
 * Loads an experiment and then queries all of its parameters.
 */
class QueryExperimentParams extends ApiBase {

	private $experimentLoader;
	private $logger;

	/**
	 * Constructs a new instance of the \PlanOut\Api\QueryExperimentParams class.
	 *
	 * @param \ApiMain $mainModule
	 * @param \PlanOut\Experiments\ExperimentLoader $experimentLoader
	 * @param \Psr\Log\LoggerInterface $logger
	 */
	public function __construct(
		ApiMain $mainModule,
		$moduleName,
		$modulePrefix = '',
		ExperimentLoader $experimentLoader = null,
		LoggerInterface $logger = null
	) {
		parent::__construct( $mainModule, $moduleName, $modulePrefix );

		$this->experimentLoader = $experimentLoader ?: new HookExperimentLoader();
		$this->logger = $logger ?: new EventLoggingLogger();
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
			$experiment->initialize( $inputs, $this->logger );

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
