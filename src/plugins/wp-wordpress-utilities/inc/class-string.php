<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 */

/**
 * Provides methods to manipulate strings.
 */
class WPU_String
{
    /**
     * Replaces each format item in a specified string with the corresponding object's value.
     * @param $format string A composite format string.
     * @param $args array An array that contains zero or more objects to format.
     * @return string A copy of format in which the format items have been replaced by the corresponding objects in args.
     */
    public static function format($format, $args)
    {
        preg_match_all('/(?=\{)\{(\d+)\}(?!\})/', $format, $matches, PREG_OFFSET_CAPTURE);
        $offset = 0;
        foreach ($matches[1] as $data) {
            $i = $data[0];
            $format = substr_replace($format, @$args[$i], $offset + $data[1] - 1, 2 + strlen($i));
            $offset += strlen(@$args[$i]) - 2 - strlen($i);
        }

        return $format;
    }
} 
