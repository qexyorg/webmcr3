<form method="post" class="comment-form">
    <div class="wrapper">
        <div class="avatar-block">
            <a href="{{__META__.site_url}}profile/" style="background-image: url('{{__USER__.avatar|avatar}}?{{random(99999)}}');" target="_blank" class="window avatar avatar-target-bg"></a>
        </div>

        <input type="hidden" name="comment_id">

        <input type="hidden" class="add-action-url" value="{{__META__.site_url}}comments/{{ WIDGET_COMMENTS.type }}/{{ WIDGET_COMMENTS.value }}/add">
        <input type="hidden" class="edit-action-url" value="{{__META__.site_url}}comments/{{ WIDGET_COMMENTS.type }}/{{ WIDGET_COMMENTS.value }}/{COMMENT_ID}/edit">
        <input type="hidden" class="save-action-url" value="{{__META__.site_url}}comments/{{ WIDGET_COMMENTS.type }}/{{ WIDGET_COMMENTS.value }}/{COMMENT_ID}/save">
        <input type="hidden" class="quote-action-url" value="{{__META__.site_url}}comments/{{ WIDGET_COMMENTS.type }}/{{ WIDGET_COMMENTS.value }}/{COMMENT_ID}/quote">
        <input type="hidden" class="remove-action-url" value="{{__META__.site_url}}comments/{{ WIDGET_COMMENTS.type }}/{{ WIDGET_COMMENTS.value }}/{COMMENT_ID}/remove">

        <div class="center-block pl-16 pr-8">
            <textarea data-ctrl="true" name="text" placeholder="Текст комментария"></textarea>
        </div>

        <div class="right-block">
            <button type="submit" class="comment-add-trigger" data-type="add"><i class="fa fa-paper-plane"></i></button>
            <button type="submit" class="comment-save-trigger" data-type="save"><i class="fa fa-check"></i></button>
            <button type="submit" class="comment-cancel-trigger" data-type="cancel"><i class="fa fa-ban"></i></button>
        </div>
    </div>
</form>