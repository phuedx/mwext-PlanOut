( function ( mw ) {

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

} ( mediaWiki ) );
