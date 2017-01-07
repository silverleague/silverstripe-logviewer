<?php

namespace SilverLeague\LogViewer\Tests\Forms\GridField\Stub;

use SilverStripe\ORM\DataObject;
use SilverStripe\Dev\TestOnly;

/**
 * A stubbed DataObject for testing permission checks
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SomeDataObject extends DataObject implements TestOnly
{
    /**
     * {@inheritDoc}
     */
    public function canDelete($member = null)
    {
        return false;
    }
}
