@if(in_array($get, ['product']))
    <input type="hidden" name="product" value="@if(isset($product->product)) {{(int)$product->product}} @else 0 @endif">
    <input type="hidden" name="rating" value="0">
    <input type="hidden" name="weight" value="0.00">
    </form>
@endif