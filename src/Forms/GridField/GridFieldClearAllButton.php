<?php

namespace SilverLeague\LogViewer\Forms\GridField;

use SilverStripe\ORM\ValidationException;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_FormAction;
use SilverStripe\Forms\GridField\GridField_HTMLProvider;
use SilverStripe\Forms\GridField\GridField_ActionProvider;

/**
 * Adds a "Clear all" button to a GridField
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class GridFieldClearAllButton implements GridField_HTMLProvider, GridField_ActionProvider
{
    /**
     * Fragment to write the button to
     *
     * @var string
     */
    protected $targetFragment;

    /**
     * @param string $targetFragment The HTML fragment to write the button into
     */
    public function __construct($targetFragment = 'after')
    {
        $this->targetFragment = $targetFragment;
    }

    /**
     * Add a "clear all" button to a <p> tag and assign it to the target fragment
     *
     * @param  GridField $gridField
     * @return array
     */
    public function getHTMLFragments($gridField)
    {
        $button = GridField_FormAction::create($gridField, 'clear', 'Clear all', 'clear', null);
        $button->setAttribute('data-icon', 'clear-all-logs');
        $button->addExtraClass('font-icon-trash-bin action_clear action action-delete');
        $button->setForm($gridField->getForm());

        return [$this->targetFragment => '<p class="grid-clear-all-button">' . $button->Field() . '</p>'];
    }

    /**
     * {@inheritDoc}
     *
     * @throws ValidationException If the current user does not have permission to delete records
     */
    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        /** @var \SilverStripe\ORM\DataList $gridField */
        $list = $gridField->getList();

        $dataClass = $list->dataClass();
        if (!$dataClass::singleton()->canDelete()) {
            throw new ValidationException(
                _t('GridFieldAction_Delete.EditPermissionsFailure', "No permission to unlink record")
            );
        }

        $gridField->getList()->removeAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getActions($gridField)
    {
        return ['clear'];
    }
}
