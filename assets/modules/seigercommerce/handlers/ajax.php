<?php
/**
 * AJAX request handler
 */

evo()->documentOutput == '';
$ajax['status'] = 0;
$ajax['message'] = 'Handler not found';
switch (request()->ajax) {
    /* Callback form */
    case "callback" :
        if (request()->has('callback')) {
            $sCommerce->sendMail(evo()->getConfig('emailsender'), "callback", request()->input('callback'));
            $ajax['status'] = 1;
            $ajax['message'] = __('Message sent successfully.');
        }

        break;
    /* Buy product */
    case "buy" :
        $buy = (int)request()->product;
        if ($buy) {
            $variation = (int)request()->variation;
            $count = (int)request()->count > 1 ? (int)request()->count : 1;

            if ($count > 1) {
                $_SESSION['cart'][$buy][$variation] = $count;
            } else {
                $_SESSION['cart'][$buy][$variation] = $_SESSION['cart'][$buy][$variation] + $count;
            }

            $ajax['count'] = 0;
            foreach ($_SESSION['cart'] as $item) {
                $ajax['count'] = $ajax['count'] + array_sum($item);
            }

            $ajax['status'] = 1;
            $ajax['message'] = __('Product added to cart!');
        }
        break;
    /* Check the promo code actual */
    case "checkPromoCode":
        $ajax['status'] = 1;
        $code = sPromoCode::whereCode(request()->promoCode)
            ->where('validity_from', '<', now())
            ->where(function($query) {
                $query->where('validity_to', '>', now())
                    ->orWhereNull('validity_to');
            })
            ->wherePublished(1)
            ->first();

        if ($code) {
            $ajax['return'] = true;
            $ajax['message'] = __('Ok');
        } else {
            $ajax['return'] = false;
            $ajax['message'] = __('Promo code is not valid');
        }
        break;
}
die(json_encode($ajax));