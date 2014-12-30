( function ( mw, $ ) {

    QUnit.module( 'ext.planOut: API', {
        setup: function () {
            this.api = new mw.Api();
            this.deferred = $.Deferred();

            this.stub( this.api, 'get' );
            this.api.get.returns( this.deferred.promise() );

            this.planOut = new mw.PlanOut( this.api );

            this.queryExperimentParams = function () {
                return this.planOut.queryExperimentParams( 'test_experiment', '1234567890abcdef' );
            };
        }
    } );

    QUnit.test( 'it should call the "queryexperimentparams" API method', 1, function ( assert ) {
        this.queryExperimentParams();

        assert.assertTrue( this.api.get.calledWith( {
            action: 'queryexperimentparams',
            experiment_name: 'test_experiment',
            user_token: '1234567890abcdef'
        } ) );
    } );

    QUnit.test( 'it should resolve with the experiment parameters when the API call succeeds', 1, function ( assert ) {
        var expectedExperimentParams = {
            foo: 'bar',
            baz: [ 'quux ']
        };

        this.deferred.resolve( {
            query: {
                experiment_params: expectedExperimentParams
            }
        } );

        this.queryExperimentParams()
            .then( function ( experiment ) {
                assert.strictEqual( experiment.getParams(), expectedExperimentParams );
            } );
    } );

    QUnit.test( 'it should reject when the API call fails', 1, function ( assert ) {
        var expectedError = new Error();

        this.deferred.reject( expectedError );

        this.queryExperimentParams()
            .fail( function ( error ) {
                assert.strictEqual( error, expectedError );
            } );
    } );

} ( mediaWiki, jQuery ) );
