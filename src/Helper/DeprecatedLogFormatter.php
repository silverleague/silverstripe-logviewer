<?php

namespace SilverLeague\LogViewer\Helper;

use SilverStripe\Core\Convert;

/**
 * Class DeprecatedLogFormatter is used to format entries that are not full JSON.
 * This is to support older formatting that's not used anymore
 * @description purely exists to support older log formatting
 * @package SilverLeague\LogViewer\Deprecated
 *
 * @deprecated
 */
class DeprecatedLogFormatter
{

    /**
     * @param string $text
     * @return string
     */
    public static function formatLegacyEntry($text)
    {
        // Extract all the JSON-blocks from the message. Anything from `{"` to `} `
        preg_match_all("/(\{\".*?}+)\s/", $text, $matches);
        $entry = '';
        if (count($matches[1])) {
            $entry .= self::createLegacyEntry($matches, $text, $entry);
        } else {
            $entry .= '<p>' . $text . '</p>';
        }

        return $entry;
    }

    /**
     * @param array $matches
     * @param string $text
     * @param string $entry
     * @return string
     */
    private static function createLegacyEntry($matches, $text, $entry)
    {
        foreach ($matches[1] as $key => $match) {
            list($pretext, $matchToArray, $text) = self::extractValues($text, $match);
            $entry .= ($key > 0 ? "\n" : '') . trim($pretext) . ': ';
            if (is_array($matchToArray)) {
                $entry .= LogFormatter::entryToUl($matchToArray);
            } else {
                $entry .= "\n" . $match;
            }
        }
        $leftOver = Convert::json2array(trim($text));
        if (is_array($leftOver)) {
            $entry .= "\nOther: " . LogFormatter::entryToUl($leftOver);
        } elseif (strlen(trim($text))) { // add the leftover if there is any
            $entry .= "\nOther:\n" . $text;
        }

        return $entry;
    }

    /**
     * @param string $text
     * @param string $match
     * @return array
     */
    private static function extractValues($text, $match)
    {
        $pretext = substr($text, 0, strpos($text, $match)); // Header of the error
        $matchToArray = Convert::json2array($match); // Convert the rest to array
        $text = substr($text, strlen($pretext . $match)); // Prepare for the next entry

        return array($pretext, $matchToArray, $text);
    }
}