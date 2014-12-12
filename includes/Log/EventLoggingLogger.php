<?php

namespace PlanOut\Log;

use Psr\Log\AbstractLogger;
use FormatJson;
use EventLogging;

/**
 * Logs PlanOut exposure events with the PlanOutExperimentExposure schema.
 */
class EventLoggingLogger extends AbstractLogger {

    public function log( $level, $message, array $context = array() ) {
        // Note well that $context will always be populated
        // (see \Vimeo\ABLincoln\Experiments\AbstractExperimentTrait#logEvent).

        $event = array();

        foreach ( array( 'name', 'time', 'salt' ) as $key ) {
            $event[$key] = $context[$key];
        }

        $event['userToken'] = $context['inputs']['userid'];
        $event['params'] = FormatJson::encode($context['params']);

        EventLogging::logEvent( 'PlanOutExperimentExposure', 10745784, $event );
    }
}
