<?php
/**
 * AJAX request handler
 */

evo()->documentOutput == '';
$ajax['status'] = 0;
$ajax['message'] = 'Handler not found';
switch (request()->ajax) {
    case "callback" :
        if (request()->has('callback')) {
            $sCommerce->sendMail(evo()->getConfig('emailsender'), "callback", request()->input('callback'));
            $ajax['status'] = 1;
            $ajax['message'] = __('Message sent successfully.');
        }

        break;
}
die(json_encode($ajax));