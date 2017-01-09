<?php

namespace SilverLeague\LogViewer\Task;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;

/**
 * This is purely for demo purposes, and will log a message at every level for local testing or demo purposes.
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class CreateLogsTask extends BuildTask
{
    /**
     * {@inheritDoc}
     */
    private static $segment = 'CreateLogsTask';

    /**
     * {@inheritDoc}
     */
    public function run($request)
    {
        $logger = Injector::inst()->get('Logger');
        $logger->addDebug('Detailed debug information');
        $logger->addInfo('Interesting events. Examples: User logs in, SQL logs.');
        $logger->addNotice('Uncommon events');
        $logger->addWarning(
            'Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, '
            . 'undesirable things that are not necessarily wrong.'
        );
        $logger->addError('Runtime errors');
        $logger->addCritical('Critical conditions. Example: Application component unavailable, unexpected exception.');
        $logger->addAlert(
            'Action must be taken immediately. Example: Entire website down, database unavailable, etc. '
            . 'This should trigger the SMS alerts and wake you up.'
        );
        $logger->addEmergency('Urgent alert.');
        echo 'Finito.', PHP_EOL;
    }
}
