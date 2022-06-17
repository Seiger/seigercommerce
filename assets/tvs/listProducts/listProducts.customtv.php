<?php if (IN_MANAGER_MODE != true) { die('Hacker?'); } //dd(get_defined_vars());

require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/models/sProduct.php';
use sCommerce\Models\sProduct;
$products = sProduct::lang('ru')->wherePublished(1)->get();
$values = explode('||', $row['value']);
?>

<select id="tv<?php echo $row['id']; ?>" name="tv<?php echo $row['id']; ?>[]" class="form-control select2" multiple onchange="documentDirty=true;">
    <?php if ($products->count()) { ?>
        <?php foreach ($products as $item) { ?>
            <option value="<?php echo $item->product; ?>" <?php if (in_array($item->product, $values)) { echo 'selected'; } ?>><?php echo $item->pagetitle; ?></option>
        <?php } ?>
    <?php } else { ?>
        <option value=""></option>
    <?php } ?>
</select>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();

        $(document).on('click', '[data-target]', function () {
            $('.select2').select2();
        });
    });
</script>