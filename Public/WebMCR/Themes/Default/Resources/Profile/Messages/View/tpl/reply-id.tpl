<div class="comment-id" id="comment-{{reply.id}}" data-id="{{reply.id}}">
	<div class="wrapper">
		<div class="avatar-block">
			<a href="{{__META__.site_url}}user/{{reply.user_login_create}}" style="background-image: url('{{reply.user_avatar_create|avatar}}?{{random(99999)}}');" target="_blank" class="window avatar {% if reply.user_id_create==__USER__.id %}avatar-target-bg{% endif %}"></a>
		</div>

		<div class="right-block">
			<div class="header"><a href="{{__META__.site_url}}user/{{reply.user_login_create}}">{{reply.user_login_create}}</a></div>
			<div class="text">{{reply.text_html|raw}}</div>
			<div class="footer">
				<ul>
					<li><a class="col-gray" href="#comment-{{reply.id}}">{{reply.date_create|dateToFormat}}</a></li>

					{% if not message.is_close|boolean %}
                        {% if __PERMISSION__.profile_messages_reply_add %}
							<li><a class="comment-quote-trigger" href="#quote-{{reply.id}}">Цитировать</a></li>
                        {% endif %}

                        {% if (__USER__.id==reply.user_id_create and __PERMISSION__.profile_messages_reply_edit) or __PERMISSION__.profile_messages_reply_edit_all %}
							<li><a class="comment-edit-trigger" href="#edit-{{reply.id}}">Редактировать</a></li>
                        {% endif %}

                        {% if (__USER__.id==reply.user_id_create and __PERMISSION__.profile_messages_reply_remove) or __PERMISSION__.profile_messages_reply_remove_all %}
							<li><a class="comment-remove-trigger" href="#remove-{{reply.id}}">Удалить</a></li>
                        {% endif %}
					{% endif %}
				</ul>
			</div>
		</div>
	</div>
</div>