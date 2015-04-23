<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 */

class WPU_NumericRenderer {
    /**
     * Renders the control for a numeric field.
     * @param $id string The Id for the control. This must be unique.
     * @param $name string The name of the control. This reflects to the setting name.
     * @param $value int The current value of the option.
     * @param $option array The option definition.
     */
    public static function render($id, $name, $value, $option)
    {
        ob_start()
        ?>
        <input type="number" id="<?=$id?>" name="<?=$name?>" value="<?=$value?>" />
        <?php if (strlen($option['description']) != 0) : ?>
        <label for="<?=$id?>"><span class="description"><?=$option['description']?></span></label>
    <?php endif; ?>
        <?php
        echo ob_get_clean();
    }
} 
