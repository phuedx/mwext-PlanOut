( function ( mw, $ ) {

	QUnit.module( 'ext.planOut: Experiment', {
		setup: function () {
			this.params = {
				foo: 'bar'
			};
			this.experiment = new mw.PlanOutExperiment( this.params );
		}
	} );

	QUnit.test( 'it should return the parameter', 1, function ( assert ) {
		assert.strictEqual( this.experiment.get( 'foo' ), this.params.foo );
	} );

	QUnit.test( "it should return null when the parameter doesn't exist", 1, function ( assert ) {
		assert.strictEqual( this.experiment.get( 'baz' ), null );
	} );

	QUnit.test( "it should return the default value when it's given and the parameter doesn't exist", 1, function ( assert ) {
		assert.strictEqual( this.experiment.get( 'baz', 'quux' ), 'quux' );
	} );

	QUnit.test( 'the user should be in the experiment by default', 1, function ( assert ) {
		assert.strictEqual( this.experiment.inExperiment(), true );
	} );

	QUnit.test( "the user shouldn't be in the experiment when the in_experiment parameter is false", 1, function ( assert ) {
		var params = $.extend( {}, this.params, {
				in_experiment: false
			} ),
			experiment = new mw.PlanOutExperiment( params );

		assert.strictEqual( experiment.inExperiment(), false );
	} );

} ( mediaWiki, jQuery ) );
