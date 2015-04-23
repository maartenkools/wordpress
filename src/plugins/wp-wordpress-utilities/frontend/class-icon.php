<?php

/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/19/2015
 * Time: 6:57 PM
 */
class WPU_Icon
{
    public static function render($params)
    {
        $params = shortcode_atts(array(
            'type' => '',
            'icon' => '',
            'size' => ''
        ), $params);

        $dom = new DOMDocument();
        $root = null;

        $classes = array();

        if($params['type'] == 'fa') {
            array_push($classes, 'fa');
            if (!empty($params['size'])) array_push($classes, $params['size']);
            if (!empty($params['icon'])) array_push($classes, $params['icon']);
        } else if($params['type'] == 'social') {
            array_push($classes, 'social');
            if (!empty($params['size'])) array_push($classes, $params['size']);
            if (!empty($params['icon'])) array_push($classes, $params['icon']);
        }

        $element = $dom->createElement('icon');
        $dom->appendChild($element);
        $element->setAttribute('class', implode(' ', $classes));

        return $dom->saveHTML();
    }
}

