( function ( mw ) {

	var DEFAULT_PARAMS = {
		in_experiment: true
	};

	/**
	 * Constructs a new instance of the `mw.PlanOutExperiment` class, which
	 * presents the same interface as the `\Vimeo\ABLincoln\Experiments\SimpleExperiment`
	 * class but simply wraps the return value of the `queryexperimentparams` API.
	 *
	 * @class
	 *
	 * @constructor
	 * @param {Object} params The experimental parameters returned by the
	 *  `queryexperimentparams` API.
	 */
	mw.PlanOutExperiment = function ( params ) {
		params = $.extend( {}, DEFAULT_PARAMS, params );

		return {

			/**
			 * Gets the value of the experiment parameter.
			 *
			 * @param {string} name
			 * @param {Object} [defaultValue=null] The value to return if the
			 *  parameter doesn't exist.
			 * @return {Object}
			 */
			get: function ( name, defaultValue ) {
				defaultValue = defaultValue || null;

				return params.hasOwnProperty( name )
					? params[ name ]
					: defaultValue;
			},

			/**
			 * Gets all of the experiment parameters.
			 *
			 * @return {Object}
			 */
			getParams: function () {
				return params;
			},

			/**
			 * Gets whether or not the user is in the experiment.
			 *
			 * @return {boolean}
			 */
			inExperiment: function () {
				return params.in_experiment;
			}
		};
	};

} ( mediaWiki ) );
