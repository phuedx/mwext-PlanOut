<?php

namespace Test\PlanOut\Api;

use PlanOut\Experiments\SimpleExperiment;
use Vimeo\ABLincoln\Operators\Random\UniformChoice;
use MediaWikiTestCase;
use RequestContext;
use PlanOut\Api\QueryExperimentParams;
use ApiMain;
use FauxRequest;
use InvalidArgumentException;

class StubExperiment extends SimpleExperiment {

	public function assign( $params, $inputs ) {
		$params['inputs'] = $inputs; // The PlanOut/ABLincoln equivalent of echo.
	}
}

/**
 * @group PlanOut
 */
class QueryExperimentParamsTest extends MediaWikiTestCase {

	private $context;
	private $experimentLoader;
	private $api;

	public function setUp() {
		parent::setUp();

		$this->context = new RequestContext();
		$this->context->setRequest( new FauxRequest( array(
			'experiment_name' => 'test_experiment',
			'user_token' => '1234567890abcdef',
		) ) );
		$this->experimentLoader = $this->getMock('\\PlanOut\\Experiments\\ExperimentLoader');
		$this->api = new QueryExperimentParams(
			new ApiMain( $this->context ),
			'queryexperimentparams',
			'',
			$this->experimentLoader
		);
		$this->experiment = new StubExperiment();
	}

	public function test_it_should_load_the_experiment() {
		$this->experimentLoader->expects( $this->once() )
			->method( 'load' )
			->with( 'test_experiment' )
			->will( $this->returnValue( $this->experiment ) );

		$this->api->execute();
	}

	/**
	 * @expectedException \UsageException
	 */
	public function test_it_should_throw_when_theres_no_experiment_with_the_given_name() {
		$this->experimentLoader->expects( $this->once() )
			->method( 'load' )
			->will( $this->throwException( new InvalidArgumentException() ) );

		$this->api->execute();
	}

	public function test_it_should_return_the_experiment_parameters() {
		$this->experimentLoader->expects( $this->once() )
			->method( 'load' )
			->will( $this->returnValue( $this->experiment ) );

		$this->api->execute();

		$queryData = $this->api->getResult()->getResultData( 'query' );

		$this->assertEquals( $queryData, array(
			'experiment_params' => array(
				'inputs' => array(
					'userid' => '1234567890abcdef',
				),
			)
		) );
	}
}
