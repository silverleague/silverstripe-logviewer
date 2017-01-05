<?php

namespace SilverLeague\LogViewer\Model;

use SilverStripe\ORM\DataObject;

/**
 * A LogEntry is a set of data provided from Monolog via the DataObjectHandler
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogEntry extends DataObject
{
    /**
     * {@inheritDoc}
     */
    private static $table_name = 'LogEntry';

    /**
     * {@inheritDoc}
     */
    private static $db = [
        'Entry'    => 'Text',
        'Level'    => 'Varchar'
    ];

    /**
     * {@inheritDoc}
     */
    private static $summary_fields = [
        'Entry',
        'Created',
        'Level'
    ];

    /**
     * We should never need to edit log entries
     *
     * {@inheritDoc}
     */
    public function canEdit($member = false, $context = [])
    {
        return false;
    }
}
