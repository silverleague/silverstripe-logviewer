<?php

namespace SilverLeague\LogViewer\Model;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

/**
 * A LogEntry is a set of data provided from Monolog via the DataObjectHandler
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogEntry extends DataObject implements PermissionProvider
{
    private static string $table_name = 'LogEntry';

    private static array $db = [
        'Entry'    => 'Text',
        'Level'    => 'Varchar'
    ];

    private static array $summary_fields = [
        'Entry',
        'Created',
        'Level'
    ];

    /**
     * Whether the cron functionality should run. This does not affect use as a BuildTask.
     * Note: you need to configure silverstripe/crontask yourself.
     *
     * @config
     * @var bool
     */
    private static bool $cron_enabled = true;

    /**
     * How often the cron should run (default: 4am daily)
     *
     * @config
     * @var string
     */
    private static string $cron_schedule = '0 4 * * *';

    /**
     * The maximum age in days for a LogEntry before it will be removed
     *
     * @config
     * @var int
     */
    private static int $max_log_age = 30;

    /**
     * Which Monolog\Logger levels (numeric) to start handling from (see class for examples)
     *
     * @config
     * @var integer
     */
    private static int $minimum_log_level = 300;

    /**
     * Permissions
     */
    public function providePermissions(): array
    {
        return [
            'DELETE_ENTRY' => [
                'name' => _t('LogEntry.PERMISSION_DELETE_DESCRIPTION', 'Delete log entries'),
                'category' => _t('Permissions.LOGENTRY_CATEGORY', 'Log entry permissions'),
                'help' => _t('LogEntry.PERMISSION_DELETE_HELP', 'Permission required to delete existing log entries.')
            ],
            'VIEW_ENTRY' => [
                'name' => _t('LogEntry.PERMISSION_VIEW_DESCRIPTION', 'View log entries'),
                'category' => _t('Permissions.LOGENTRY_CATEGORY', 'Log entry permissions'),
                'help' => _t('LogEntry.PERMISSION_VIEW_HELP', 'Permission required to view existing log entries.')
            ]
        ];
    }

    /**
     * Format the log entry as JSON
     *
     * {@inheritDoc}
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $data = json_decode($this->getField('Entry'));
        $fields->addFieldToTab(
            'Root.Main',
            LiteralField::create(
                'Entry',
                '<pre class="logviewer-logentry-entry"><code>'
                . json_encode($data, JSON_PRETTY_PRINT)
                . '</code></pre>'
            )
        );

        return $fields;
    }

    /**
     * Log entries are created programmatically, they should never be created manually
     *
     * {@inheritDoc}
     */
    public function canCreate($member = null, $context = []): bool
    {
        return false;
    }

    /**
     * We should never edit log entries
     *
     * {@inheritDoc}
     */
    public function canEdit($member = null): bool
    {
        return false;
    }

    public function canDelete($member = null): bool | int
    {
        return Permission::checkMember($member, ['DELETE_ENTRY', 'CMS_ACCESS_LogViewerAdmin']);
    }

    public function canView($member = null): bool | int
    {
        return Permission::checkMember($member, ['VIEW_ENTRY', 'CMS_ACCESS_LogViewerAdmin']);
    }
}
