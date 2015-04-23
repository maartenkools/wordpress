<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 */

/**
 * Enumerates the possible data types of an option.
 */
abstract class WPU_DataType
{
    /**
     * The interface will be rendered as a text input control. The option value will be text.
     */
    const Text = 0;

    /**
     * The interface will be rendered as a number input control. The option value will be an integer.
     */
    const Integer = 1;

    /**
     * The interface will be rendered as a checkbox control. The option value will be a boolean.
     */
    const Checkbox = 2;
}
