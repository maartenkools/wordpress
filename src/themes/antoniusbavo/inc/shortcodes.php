<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 9/20/2016
 * Time: 9:55 PM
 */
add_shortcode('postlink', 'ab_postlink_impl');
function ab_postlink_impl($atts)
{
    extract(shortcode_atts(array(
        "title" => "Read More"
    ), $atts));

    $link = get_permalink();
    return "<a href='{$link}'>{$title}</a>";
}
