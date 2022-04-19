<?php
/**
 * Plugin for Seiger Commerce Management Module for Evolution CMS admin panel.
 */

$e = evo()->event;

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