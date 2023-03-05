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
     * @param string $targetFragment The HTML fragment to write the button into
     */
    public function __construct(protected string $targetFragment = 'after')
    {

    }

    /**
     * Add a "clear all" button to a <p> tag and assign it to the target fragment
     *
     * @param  GridField $gridField
     * @return array
     */
    public function getHTMLFragments($gridField): array
    {
        $button = GridField_FormAction::create($gridField, 'clear', 'Clear all', 'clear', null)
            ->setAttribute('data-icon', 'clear-all-logs')
            ->addExtraClass('font-icon-trash-bin action_clear no-ajax action-delete btn btn-secondary')
            ->setForm($gridField->getForm());

        return [
            $this->targetFragment => $button->Field()
        ];
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

        return $gridField->redirectBack();
    }

    /**
     * {@inheritDoc}
     */
    public function getActions($gridField): array
    {
        return ['clear'];
    }
}
