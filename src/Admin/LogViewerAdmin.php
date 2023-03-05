<?php

namespace SilverLeague\LogViewer\Admin;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverLeague\LogViewer\Forms\GridField\GridFieldClearAllButton;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Requirements;

/**
 * Creates a CMS interface for viewing log entries
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogViewerAdmin extends ModelAdmin
{
    private static string $url_segment = 'logs';

    private static string $menu_title = 'Logs';

    private static string $menu_icon_class = 'font-icon-list';

    private static array $managed_models = [
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
        Requirements::css('silverleague/logviewer:client/dist/styles/logviewer.css');
    }

    /**
     * Remove the "add new" button
     *
     * {@inheritDoc}
     */
    public function getEditForm($id = null, $fields = null): Form
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
    public function getGridFieldName(): string
    {
        return $this->sanitiseClassName($this->modelClass);
    }

    /**
     * Display newest log entries first
     *
     * {@inheritDoc}
     */
    public function getList(): DataList
    {
        $list = parent::getList();
        return $list->sort(['Created' => 'DESC']);
    }
}
