<?php
/**
 * Plugin for Seiger Commerce Management Module for Evolution CMS admin panel.
 */

require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/sCommerce.class.php';

$e = evo()->event;
$sCommerce = new sCommerce();
$_lang = $sCommerce->managerLanguage();

/**
 * Base functionality
 */
if (in_array($e->name, ['OnPageNotFound', 'OnWebPageInit'])) {
    if (request()->ajax() && request()->has('ajax')) {
        require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/handlers/ajax.php';
    }
}

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
    $menu['scommerce'] = [
        "scommerce",
        "main",
        "<i class=\"fa fa-store\"></i> <span class=\"menu-item-text\">" . $_lang['scommerce_menu'] . "</span>",
        $sCommerce->url,
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