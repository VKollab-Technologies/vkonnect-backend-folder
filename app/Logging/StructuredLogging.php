<?php

namespace App\Logging;

use Illuminate\Log\Logger;

/**
 * Formats a log channel as one JSON object per line.
 *
 * Applied as a `tap` rather than a bare `formatter` entry so the stack-trace
 * setting can be turned on explicitly: JsonFormatter drops exception traces by
 * default, and losing them would make this a downgrade on the one kind of line
 * where detail matters most.
 *
 * Shape (one line, expanded here for readability):
 *
 *   {
 *     "datetime":   "2026-08-03T14:57:16.123456+00:00",
 *     "level_name": "ERROR",
 *     "channel":    "local",
 *     "message":    "Notification mail dispatch failed: ...",
 *     "request_id": "0f2c…",      // from AssignRequestId
 *     "user_id":    "019f88f9-…", // when authenticated
 *     "role":       "hr_admin",
 *     "method":     "POST",
 *     "path":       "/api/v1/hr/documents",
 *     "ip":         "127.0.0.1",
 *     "context":    { … },        // whatever the caller passed
 *     "extra":      { … }
 *   }
 *
 * request_id, user_id, role, method, path and ip are promoted from the context
 * to the top level: they identify the request rather than describing the
 * event, and nesting them under `context` alongside caller-supplied keys means
 * every query for them has to know they are one level down — and they collide
 * with any caller who happens to log their own `path`.
 */
class StructuredLogging
{
    public function __invoke(Logger $logger): void
    {
        $formatter = new StructuredJsonFormatter;

        // JsonFormatter drops exception traces by default. Losing them would
        // make this a downgrade on exactly the lines where detail matters
        // most, so turn them back on explicitly.
        $formatter->includeStacktraces();

        foreach ($logger->getLogger()->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
        }

        $logger->getLogger()->pushProcessor(new RequestContextProcessor);
    }
}
