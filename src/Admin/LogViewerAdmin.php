<?php

namespace SilverLeague\LogViewer\Admin;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;

/**
 * Creates a CMS interface for viewing log entries
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogViewerAdmin extends ModelAdmin
{
    /**
     * {@inheritDoc}
     */
    private static $url_segment = 'logs';

    /**
     * {@inheritDoc}
     */
    private static $menu_title = 'Logs';
    // private static $menu_icon = '';

    /**
     * {@inheritDoc}
     */
    private static $managed_models = [
        LogEntry::class
    ];

    /**
     * {@inheritDoc}
     */
    public $showImportForm = false;

    /**
     * Remove the "add new" button
     *
     * {@inheritDoc}
     */
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $gridField = $form->Fields()->fieldByName($this->getGridFieldName());

        /** @var \SilverStripe\Forms\GridField\GridFieldConfig $gridFieldConfig */
        $config = $gridField->getConfig();
        $config->removeComponentsByType($config->getComponentByType(GridFieldAddNewButton::class));

        return $form;
    }

    /**
     * Get the FieldList name for the GridField
     *
     * @return string
     */
    public function getGridFieldName()
    {
        return $this->sanitiseClassName($this->modelClass);
    }

    /**
     * Display newest log entries first
     *
     * {@inheritDoc}
     */
    public function getList()
    {
        $list = parent::getList();
        return $list->sort(['Created' => 'DESC']);
    }
}
