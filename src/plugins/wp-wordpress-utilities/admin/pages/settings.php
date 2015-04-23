<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 */
?>
<div class="wrap" id="<?=WPU_Plugin::current()->get_admin()->get_pageName()?>">
    <h2><?=__('Settings', WPU_Plugin::$textDomain)?></h2>
    <form method="post" action="options.php" enctype="multipart/form-data">
        <ul id="settings-sections" class="subsubsub hide-if-no-js">
            <li><a href="#all" class="tab current all"><?=__('All', WPU_Plugin::$textDomain)?></a></li>
            <?php foreach(WPU_Plugin::current()->get_options()->get_optionDefinitions() as $section=>$data) : ?>
                <li><a href="#<?=$section?>" class="tab"><?=$data['title']?></a></li>
            <?php endforeach; ?>
        </ul>
        <div class="clear"></div>
        <?php
        settings_fields(WPU_Plugin::current()->get_admin()->get_optionGroup());
        do_settings_sections(WPU_Plugin::current()->get_admin()->get_pageName());
        ?>
        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?=esc_attr(__('Save Settings', WPU_Plugin::$textDomain))?>" />
        </p>
    </form>
</div>
