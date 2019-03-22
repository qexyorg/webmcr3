<div class="comment-id" id="comment-{{comment.id}}" data-id="{{comment.id}}">
	<div class="wrapper">
		<div class="avatar-block">
			<a href="{{__META__.site_url}}user/{{comment.user_login_create}}" style="background-image: url('{{comment.avatar|avatar}}?{{random(99999)}}');" target="_blank" class="window avatar {% if comment.user_id_create==__USER__.id %}avatar-target-bg{% endif %}"></a>
		</div>

		<div class="right-block">
			<div class="header"><a href="{{__META__.site_url}}user/{{comment.user_login_create}}">{{comment.user_login_create}}</a></div>
			<div class="text">{{comment.text_html|raw}}</div>
			<div class="footer">
				<ul>
					<li><a class="col-gray" href="#comment-{{comment.id}}">{{comment.date_create|dateToFormat}}</a></li>

					{% if __PERMISSION__.news_comment_add %}
						<li><a class="comment-quote-trigger" href="#quote-{{comment.id}}">Цитировать</a></li>
					{% endif %}

					{% if (__USER__.id==comment.user_id_create and __PERMISSION__.news_comment_edit) or __PERMISSION__.news_comment_edit_all %}
						<li><a class="comment-edit-trigger" href="#edit-{{comment.id}}">Редактировать</a></li>
					{% endif %}

					{% if (__USER__.id==comment.user_id_create and __PERMISSION__.news_comment_remove) or __PERMISSION__.news_comment_remove_all %}
						<li><a class="comment-remove-trigger" href="#remove-{{comment.id}}">Удалить</a></li>
					{% endif %}
				</ul>
			</div>
		</div>
	</div>
</div>