<?php

/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 2:15 PM
 */
class WPU_SinglePost
{
    public static function render($params)
    {
        $params = shortcode_atts(array(
            'id' => 0
        ), $params);

        query_posts('p=' . $params['id']);
        while (have_posts()) : the_post();
            $content = apply_filters('the_content', get_the_content());
            $content = str_replace(']]>', ']]&gt;', $content);

            return $content;
        endwhile;

        $dom = new DOMDocument();
        $root = $dom->createElement('div');
        $dom->appendChild($root);

        $root->setAttribute('class', 'error');
        $root->appendChild(new DOMText(__('An error occurred while loading the post. Or the post is not supported.', WPU_Plugin::$textDomain)));

        return $dom->saveHTML();
    }
}
