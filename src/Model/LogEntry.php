<?php

namespace SilverLeague\LogViewer\Model;

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
     * Permissions
     */
    public function providePermissions()
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
     * Log entries are created programmatically, they should never be created manually
     *
     * {@inheritDoc}
     */
    public function canCreate($member = null, $context = [])
    {
        return false;
    }

    /**
     * We should never edit log entries
     *
     * {@inheritDoc}
     */
    public function canEdit($member = null)
    {
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function canDelete($member = null)
    {
        return Permission::checkMember($member, ['DELETE_ENTRY', 'CMS_ACCESS_LogViewerAdmin']);
    }

    /**
     * {@inheritdoc}
     */
    public function canView($member = null)
    {
        return Permission::checkMember($member, ['VIEW_ENTRY', 'CMS_ACCESS_LogViewerAdmin']);
    }
}
