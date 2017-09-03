<?php

namespace SilverLeague\LogViewer\Admin;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverLeague\LogViewer\Forms\GridField\GridFieldClearAllButton;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\View\Requirements;

/**
 * Creates a CMS interface for viewing log entries
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogViewerAdmin extends ModelAdmin
{
    private static $url_segment = 'logs';

    private static $menu_title = 'Logs';

    private static $menu_icon_class = 'font-icon-list';

    private static $managed_models = [
        LogEntry::class
    ];

    public $showImportForm = false;

    /**
     * Add log viewer custom CSS styles
     *
     * {@inheritDoc}
     */
    protected function init()
    {
        parent::init();
        Requirements::css('silverleague/logviewer:assets/scss/logviewer.css');
    }

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
        $config->addComponent(new GridFieldClearAllButton('buttons-before-left'));

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
