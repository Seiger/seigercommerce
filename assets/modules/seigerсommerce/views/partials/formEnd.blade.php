@if(in_array($get, ['product']))
    <input type="hidden" name="product" value="@if(isset($product->product)) {{(int)$product->product}} @else 0 @endif">
    </form>
@endif