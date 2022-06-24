<?php namespace sCommerce\Models;

use EvolutionCMS\Facades\UrlProcessor;
use Illuminate\Database\Eloquent;
use ReflectionClass;

class sPromoCode extends Eloquent\Model
{
    /**
     * Type constants
     */
    const PTYPE_PERCENT = 0;
    const PTYPE_FIXED = 1;

    /**
     * Return list of type discount and labels
     *
     * @return array
     */
    public static function listType(): array
    {
        $list = [];
        global $_lang;
        $class = new ReflectionClass(__CLASS__);
        foreach ($class->getConstants() as $constant => $value) {
            if (str_starts_with($constant, 'PTYPE_')) {
                $const = strtolower($constant);
                $list[$value] = ($_lang['scommerce_'.$const] ?? $const);
            }
        }
        return $list;
    }
}