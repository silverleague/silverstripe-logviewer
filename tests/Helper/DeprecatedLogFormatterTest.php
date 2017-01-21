<?php

namespace SilverLeague\LogViewer\Tests\Deprecated;

use ReflectionClass;
use SilverLeague\LogViewer\Helper\DeprecatedLogFormatter;
use SilverLeague\LogViewer\Tests\Helper\Invoker;
use SilverStripe\Dev\SapphireTest;

/**
 * @author Simon Erkelens <simon@casa-laguna.net>
 */
class DeprecatedLogFormatterTest extends SapphireTest
{

    /**
     * Check if old entries go through the entire precess neatly
     */
    public function testFormatLegacyEntry()
    {
        $arrayEntry = 'E_WARNING: Invalid argument supplied for foreach() {"code":2,"message":"Invalid argument ' .
            'supplied for foreach()","file":"/data/site/docroot/logviewer/src/Model/LogEntry.php","line":89} []';

        $result = DeprecatedLogFormatter::formatLegacyEntry($arrayEntry);
        $expectedJSON = 'E_WARNING: Invalid argument supplied for foreach(): <span style="color: #007700">[</span>' .
            '<ul style="margin-bottom: 0"><li class="list-unstyled"><span style="color: #0000BB">code</span>' .
            '<span style="color: #007700">: </span><span style="color: #DD0000">2</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">message</span>' .
            '<span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">Invalid argument supplied for foreach()</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">file</span><span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">/data/site/docroot/logviewer/src/Model/LogEntry.php</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">line</span><span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">89</span></li></ul><span style="color: #007700">]</span>' .
            "\n" .
            'Other: <span style="color: #007700">[</span><ul style="margin-bottom: 0"></ul>' .
            '<span style="color: #007700">]</span>';
        $this->assertEquals($expectedJSON, $result);

        $stringEntry = 'E_WARNING: Invalid argument supplied for foreach() {"code":2,"message":"Invalid argument ' .
            'supplied for foreach()","file":"/data/site/docroot/logviewer/src/Model/LogEntry.php","line":89} OOPS';

        $result = DeprecatedLogFormatter::formatLegacyEntry($stringEntry);
        $expectedString = 'E_WARNING: Invalid argument supplied for foreach(): <span style="color: #007700">[</span>' .
            '<ul style="margin-bottom: 0"><li class="list-unstyled"><span style="color: #0000BB">code</span>' .
            '<span style="color: #007700">: </span><span style="color: #DD0000">2</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">message</span>' .
            '<span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">Invalid argument supplied for foreach()</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">file</span><span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">/data/site/docroot/logviewer/src/Model/LogEntry.php</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">line</span><span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">89</span></li></ul><span style="color: #007700">]</span>' .
            "\nOther:\n OOPS";
        $this->assertEquals($expectedString, $result);

        $noJsonEntry = "We're making water!";
        $result = DeprecatedLogFormatter::formatLegacyEntry($noJsonEntry);
        $this->assertEquals('<p>' . $noJsonEntry . '</p>', $result);
    }

    /**
     * Check if the entries are created correctly from array.
     */
    public function testCreateLegacyEntry()
    {
        $entry = 'E_WARNING: Invalid argument supplied for foreach() {"code":2,"message":"Invalid argument ' .
            'supplied for foreach()","file":"/data/site/docroot/logviewer/src/Model/LogEntry.php","line":89} []';
        $entryArray = [
            0 =>
                [
                    0 => '{"code":2,"message":"Invalid argument supplied for foreach()","file":"/data/site/docroot/logviewer/src/Model/LogEntry.php","line":89} ',
                ],
            1 =>
                [
                    0 => '{"code":2,"message":"Invalid argument supplied for foreach()","file":"/data/site/docroot/logviewer/src/Model/LogEntry.php","line":89}',
                ],
        ];
        $expectedResult = 'E_WARNING: Invalid argument supplied for foreach(): <span style="color: #007700">[</span>' .
            '<ul style="margin-bottom: 0"><li class="list-unstyled"><span style="color: #0000BB">code</span>' .
            '<span style="color: #007700">: </span><span style="color: #DD0000">2</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">message</span>' .
            '<span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">Invalid argument supplied for foreach()</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">file</span><span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">/data/site/docroot/logviewer/src/Model/LogEntry.php</span></li>' .
            '<li class="list-unstyled"><span style="color: #0000BB">line</span><span style="color: #007700">: </span>' .
            '<span style="color: #DD0000">89</span></li></ul><span style="color: #007700">]</span>' .
            "\n" .
            'Other: <span style="color: #007700">[</span><ul style="margin-bottom: 0"></ul>' .
            '<span style="color: #007700">]</span>';
        $formatter = new DeprecatedLogFormatter();
        $result = Invoker::invokeMethod($formatter, 'createLegacyEntry', array($entryArray, $entry, ''));
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test if the JSON data is extracted correctly
     */
    public function testExtractValues()
    {
        $entry = 'E_WARNING: Invalid argument supplied for foreach() {"code":2,"message":"Invalid argument ' .
            'supplied for foreach()","file":"/data/site/docroot/logviewer/src/Model/LogEntry.php","line":89} []';
        $match = '{"code":2,"message":"Invalid argument supplied for foreach()","file":"/data/site/' .
            'docroot/logviewer/src/Model/LogEntry.php","line":89} ';
        $formatter = new DeprecatedLogFormatter();
        $result = Invoker::invokeMethod($formatter, 'extractValues', array($entry, $match));
        $expectedResult = [
            0 => 'E_WARNING: Invalid argument supplied for foreach() ',
            1 =>
                [
                    'code'    => 2,
                    'message' => 'Invalid argument supplied for foreach()',
                    'file'    => '/data/site/docroot/logviewer/src/Model/LogEntry.php',
                    'line'    => 89
                ],
            2 => '[]'
        ];
        $this->assertEquals($expectedResult, $result);
    }

}