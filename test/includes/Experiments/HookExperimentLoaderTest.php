<?php

namespace Test\PlanOut\Experiments;

use PlanOut\Experiments\SimpleExperiment;
use MediaWikiTestCase;
use Hooks;
use PlanOut\Experiments\HookExperimentLoader;

class NamedExperiment extends SimpleExperiment {

	public function __construct( $name ) {
		parent::__construct();

		$this->name = $name;
	}

	public function assign( $params, $inputs ) {}

	public function name() {
		return $this->name;
	}
}

class HookExperimentLoaderTest extends MediaWikiTestCase {

	private $experimentLoader;

	public function setUp() {
		parent::setUp();

		$this->experimentLoader = new HookExperimentLoader();
	}

	public function test_it_should_return_the_specified_experiment_when_it_is_registered() {
		$experiment = new NamedExperiment( 'test_experiment' );
		$handler = function ( &$experiments ) use ( $experiment ) {
			$experiments[] = new NamedExperiment( 'a_different_test_experiment' );
			$experiments[] = $experiment;
		};
		Hooks::register( 'PlanOutRegisterExperiments', $handler );

		$actualExperiment = $this->experimentLoader->load( 'test_experiment' );

		$this->assertSame( $actualExperiment, $experiment );

		Hooks::clear( 'PlanOutRegisterExperiments' );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function test_it_should_throw_when_the_experiment_isnt_registered() {
		$this->experimentLoader->load( 'test_experiment' );
	}
}
