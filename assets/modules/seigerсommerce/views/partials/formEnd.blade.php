@if(in_array($get, ['post', 'postAdd']))
    <input type="hidden" name="post" value="@if(isset($post['post'])) {{(int)$post['post']}} @else 0 @endif">
</form>
@endif