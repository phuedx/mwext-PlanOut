( function ( mw ) {

	/**
	 * Constructs a new instance of the `mw.PlanOut` class, which interacts with
	 * the `queryexperimentparams` API.
	 *
	 * @class
	 *
	 * @constructor
	 * @param {mediaWiki.Api} [api] An instance of the `mediaWiki.Api` class that
	 *  will be used to interact with the API
	 */
	mw.PlanOut = function( api ) {
		api = api || new mw.Api();

		return {

			/**
			 * Queries all of the experiment parameters.
			 *
			 * @param {string} experimentName The name of the experiment
			 * @param {string} userToken The user's token
			 * @return {jQuery.Promise} A jQuery Promise that will resolve with all of
			 *  the experiment parameters
			 */
			queryExperimentParams: function ( experimentName, userToken ) {
				var deferred = $.Deferred();

				api.get( {
					action: 'queryexperimentparams',
					experiment_name: experimentName,
					user_token: userToken
				} )
				.done( function ( data ) {
					deferred.resolve( data['query']['experiment_params'] );
				} )
				.fail( deferred.reject );

				return deferred.promise();
			}
		};
	}

} ( mediaWiki ) );
