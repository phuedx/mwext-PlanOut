<?php

namespace Test\PlanOut;

use PlanOut\Experiments\SimpleExperiment;
use MediaWikiTestCase;
use User;
use Hooks;
use PlanOut\Hooks as PlanOutHooks;

class StubExperiment extends SimpleExperiment {

	public function assign( $params, $inputs ) {
		$params['inputs'] = $inputs;
	}
}

class HooksResourceLoaderGetConfigVarsTest extends MediaWikiTestCase {

	private $vars;

	public function setUp() {
		global $wgUser;

		parent::setUp();

		$this->vars = array();
		$wgUser = User::newFromId( 1 );
		$this->experiment = new StubExperiment();
	}

	public function tearDown() {
		parent::tearDown();

		Hooks::clear( 'PlanOutRegisterExperiments' );
	}

	public function test_it_should_initialise_a_registered_experiment() {
		$this->registerExperiment( $this->experiment );

		PlanOutHooks::onResourceLoaderGetConfigVars( $this->vars );

		$this->assertEquals( $this->experiment->getParams(), array(
			'inputs' => array(
				'userid' => 1,
			),
		) );
	}

	public function test_it_should_merge_the_experiment_parameters_with_the_resource_loader_configuration_variables() {
		$this->registerExperiment( $this->experiment );

		PlanOutHooks::onResourceLoaderGetConfigVars( $this->vars );

		$this->assertEquals( $this->vars, array(
			'inputs' => array(
				'userid' => 1,
			),
		) );
	}

	private function registerExperiment( $experiment ) {
		$handler = function ( &$experiments ) use ( $experiment ) {
			$experiments[] = $experiment;
		};

		Hooks::register( 'PlanOutRegisterExperiments', $handler );
	}

	public function test_it_shouldnt_fire_the_PlanOutRegisterExperiments_hook_if_the_user_is_anonymous() {
		global $wgUser;

		$wgUser = new User();

		// FIXME: There must be a cleaner way of doing this.
		$handlerHasBeenCalled = false;
		$handler = function ( $experiments ) use ( $handlerHasBeenCalled ) {
			$handlerHasBeenCalled = true;
		};

		Hooks::register( 'PlanOutRegisterExperiments', $handler );

		PlanOutHooks::onResourceLoaderGetConfigVars( $this->vars );

		$this->assertEquals( $handlerHasBeenCalled, false );
	}
}
