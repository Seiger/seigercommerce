<?php
/**
 * Plugin for Seiger Commerce Management Module for Evolution CMS admin panel.
 */

use EvolutionCMS\Models\SiteModule;

$e = evo()->event;

/**
 * Add icon to tree
 */
if ($e->name == 'OnManagerNodePrerender') {
    if (is_array($e->params) && count($e->params)) {
        switch ($e->params['ph']['id']) {
            case evo()->getConfig('catalog_root') :
                $e->params['ph']['icon'] = '<i class="fa fa-store"></i>';
                $e->params['ph']['icon_folder_open'] = "<i class='fa fa-store'></i>";
                $e->params['ph']['icon_folder_close'] = "<i class='fa fa-store'></i>";
                break;
        }
        $e->addOutput(serialize($e->params['ph']));
    }
}

/**
 * Add Menu item
 */
if ($e->name == 'OnManagerMenuPrerender') {
    $module = SiteModule::whereName('sCommerce')->first();

    if ($module) {
        global $_lang;

        if (is_file(MODX_BASE_PATH . 'assets/modules/seigercommerce/lang/' . evo()->getConfig('manager_language', 'uk') . '.php')) {
            require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/lang/' . evo()->getConfig('manager_language', 'uk') . '.php';
        }

        $menu['scommerce'] = [
            "scommerce",
            "main",
            "<i class=\"fa fa-store\"></i> <span class=\"menu-item-text\">" . $_lang['scommerce_menu'] . "</span>",
            "index.php?a=112&id={$module->id}",
            $_lang['scommerce_menu'],
            "",
            "",
            "main",
            0,
            11,
            "",
        ];

        $e->addOutput(serialize(array_merge($e->params['menu'], $menu)));
    }
}