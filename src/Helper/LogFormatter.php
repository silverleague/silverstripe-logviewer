<?php

namespace SilverLeague\LogViewer\Helper;

/**
 * Class LogFormatter formats the array to a readable unordered list
 *
 * @package SilverLeague\LogViewer\Helper
 *
 * @author Simon Erkelens <simon@casa-laguna.net>
 */
class LogFormatter
{

    /**
     * Recursive method, will call itself if a sub element is an array
     * @param array $data
     * @param bool $first start with an opening bracket? if true, skip (counterintuitive, but naming)
     * @return string formatted <ul><li> of the array;
     */
    public static function entryToUl($data, $first = true)
    {
        $out = $first ? '<span style="color: #007700">[</span>' : '';
        $out .= '<ul style="margin-bottom: 0">';
        foreach ($data as $key => $arrayItem) {
            if (!is_array($arrayItem)) {
                $out .= '<li class="list-unstyled">' .
                    '<span style="color: #0000BB">' . $key . '</span>' .
                    '<span style="color: #007700">: </span>' .
                    '<span style="color: #DD0000">' . $arrayItem . '</span>'.
                    '</li>';
            } else {
                $out .= '<li class="list-unstyled">' .
                    '<span style="color: #0000BB">' . $key . '</span>' .
                    '<span style="color: #007700">: </span>' .
                    self::entryToUl($arrayItem) .
                    '</li>';
            }
        }
        $out .= '</ul>';
        $out .= $first ? '<span style="color: #007700">]</span>' : '';

        return $out;
    }

}