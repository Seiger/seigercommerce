@if(in_array($get, ['product']))
    <input type="hidden" name="product" value="{{$product->product ?? 0}}">
    <input type="hidden" name="rating" value="0">
    <input type="hidden" name="weight" value="0.00">
    </form>
@endif

@if(in_array($get, ['filter']))
    <input type="hidden" name="filter" value="{{$filter->filter ?? 0}}">
    </form>
@endif