<?php

return [
	'main' => [
		'pattern' => '/',
		'controller' => 'Main',
	],
	'profile' => [
		'pattern' => '/profile',
		'controller' => 'Profile',
		'aliases' => [
			'/profile/comments/page-:int' => ['page_id' => 1],
		],
		'params' => ['page_id' => '1'],
	],
	'profile_avatar_change' => [
		'pattern' => '/profile/avatar/change',
		'methods' => 'POST',
		'controller' => 'Profile',
		'action' => 'avatarChange',
	],
	'users' => [
		'pattern' => '/users',
		'controller' => 'Users',
		'aliases' => [
			'/users/page-:int' => ['page_id' => 2],
			'/users/search/:string' => ['search' => 1],
			'/users/search/:string/page-:int' => ['search' => 1, 'page_id' => 2],
		],
		'params' => ['page_id' => '1'],
	],
	'user' => [
		'pattern' => '/user/:string',
		'controller' => 'Users',
		'action' => 'view',
		'aliases' => [
			'/user/:string/comments/page-:int' => ['login' => 1, 'page_id' => 2],
		],
		'params' => ['login' => 1, 'page_id' => '1'],
	],
	'login' => [
		'pattern' => '/profile/login',
		'controller' => 'Profile',
		'methods' => 'POST',
		'action' => 'auth'
	],
	'logout' => [
		'pattern' => '/profile/logout',
		'controller' => 'Profile',
		'action' => 'logout'
	],
	'register' => [
		'pattern' => '/profile/register',
		'controller' => 'Profile',
		'methods' => 'POST',
		'action' => 'register'
	],
	'restore' => [
		'pattern' => '/profile/restore',
		'controller' => 'Profile',
		'methods' => 'POST',
		'action' => 'restore'
	],
	'restore_page' => [
		'pattern' => '/restore/:string',
		'controller' => 'Profile',
		'methods' => 'GET',
		'action' => 'restorePage',
		'params' => ['token' => 1]
	],
	'restore_complete' => [
		'pattern' => '/restore/complete',
		'controller' => 'Profile',
		'methods' => 'POST',
		'action' => 'restoreComplete'
	],
	'profile_messages' => [
		'pattern' => '/profile/messages',
		'controller' => 'Profile',
		'action' => 'messages',
		'aliases' => [
			'/profile/messages/page-:int' => ['page_id' => 1]
		],
	],
	'profile_message_new' => [
		'pattern' => '/profile/messages/new',
		'controller' => 'Profile',
		'methods' => 'GET',
		'aliases' => [
			'/profile/messages/new/:string' => ['login' => 1],
		],
		'action' => 'messageNew',
	],
	'profile_message_new_submit' => [
		'pattern' => '/profile/messages/new/create',
		'controller' => 'Profile',
		'methods' => 'POST',
		'action' => 'messageNewCreate'
	],
	'profile_message' => [
		'pattern' => '/profile/messages/:int',
		'controller' => 'Profile',
		'action' => 'message',
		'params' => ['message_link_id' => 1],
		'aliases' => [
			'/profile/messages/:int/page-:int' => ['message_link_id' => 1, 'page_id' => 2]
		],
	],
	'profile_message_remove' => [
		'pattern' => '/profile/message/remove',
		'controller' => 'Profile',
		'methods' => 'POST',
		'action' => 'messageRemove',
	],
	'profile_message_lock' => [
		'pattern' => '/profile/message/lock',
		'controller' => 'Profile',
		'methods' => 'POST',
		'action' => 'messageLock',
	],
	'profile_message_reply_add' => [
		'pattern' => '/profile/message/reply/add',
		'methods' => 'POST',
		'controller' => 'Profile',
		'action' => 'messageReplyAdd'
	],
	'profile_message_reply_remove' => [
		'pattern' => '/profile/message/reply/:int/remove',
		'methods' => 'POST',
		'controller' => 'Profile',
		'action' => 'messageReplyRemove',
		'params' => ['reply_id' => 1]
	],
	'profile_message_reply_quote' => [
		'pattern' => '/profile/message/reply/:int/quote',
		'methods' => 'POST',
		'controller' => 'Profile',
		'action' => 'messageReplyQuote',
		'params' => ['reply_id' => 1]
	],
	'profile_message_reply_edit' => [
		'pattern' => '/profile/message/reply/:int/edit',
		'methods' => 'POST',
		'controller' => 'Profile',
		'action' => 'messageReplyEdit',
		'params' => ['reply_id' => 1]
	],
	'profile_message_reply_edit_save' => [
		'pattern' => '/profile/message/reply/:int/save',
		'methods' => 'POST',
		'controller' => 'Profile',
		'action' => 'messageReplySave',
		'params' => ['reply_id' => 1]
	],
	'profile_activity' => [
		'pattern' => '/profile/activity',
		'controller' => 'Profile',
		'action' => 'activity',
		'aliases' => [
			'/profile/activity/page-:int' => ['page_id' => 1]
		],
	],
	'profile_settings' => [
		'pattern' => '/profile/settings',
		'controller' => 'Profile',
		'action' => 'settings',
	],
	'profile_settings_save' => [
		'pattern' => '/profile/settings/save',
		'controller' => 'Profile',
		'action' => 'settingsSave',
		'methods' => 'POST'
	],
	'profile_settings_security_' => [
		'pattern' => '/profile/settings/security/:string',
		'controller' => 'Profile',
		'action' => 'settingsSecurityComplete',
		'params' => ['token' => 1]
	],
	'news' => [
		'pattern' => '/news',
		'aliases' => [
			'/news/page-:int' => ['page_id' => 1],
			'/news/tags-:string' => ['tags' => 1],
			'/news/tags-:string/page-:int' => ['tags' => 1, 'page_id' => 2],
			'/news/search-:string' => ['search' => 1],
			'/news/search-:string/page-:int' => ['search' => 1, 'page_id' => 2],
		],
		'controller' => 'News',
	],
	'news_view' => [
		'pattern' => '/news/:string.:int',
		'aliases' => [
			'/news/:int' => ['id' => 1],
			'/news/:int/comments/page-:int' => ['id' => 1, 'page_id' => 2],
			'/news/:string.:int/comments/page-:int' => ['name' => 1, 'id' => 2, 'page_id' => 3]
		],
		'controller' => 'News',
		'action' => 'view',
		'params' => ['name' => 1, 'id' => 2]
	],
	'news_like' => [
		'pattern' => '/news/:int/like',
		'methods' => 'POST',
		'controller' => 'News',
		'action' => 'newsLike',
		'aliases' => [
			'/news/:string.:int/like' => ['new_id' => 2],
		],
		'params' => ['new_id' => 1]
	],

	'comments_add' => [
		'pattern' => '/comments/:string/:int/add',
		'methods' => 'POST',
		'controller' => 'Comments',
		'action' => 'addSubmit',
		'params' => ['mod' => 1, 'value' => 2]
	],
	'comments_remove' => [
		'pattern' => '/comments/:string/:int/:int/remove',
		'methods' => 'POST',
		'controller' => 'Comments',
		'action' => 'remove',
		'params' => ['mod' => 1, 'value' => 2, 'comment_id' => 3]
	],
	'comments_quote' => [
		'pattern' => '/comments/:string/:int/:int/quote',
		'methods' => 'POST',
		'controller' => 'Comments',
		'action' => 'quote',
		'params' => ['mod' => 1, 'value' => 2, 'comment_id' => 3]
	],
	'comments_edit' => [
		'pattern' => '/comments/:string/:int/:int/edit',
		'methods' => 'POST',
		'controller' => 'Comments',
		'action' => 'edit',
		'params' => ['mod' => 1, 'value' => 2, 'comment_id' => 3]
	],
	'comments_edit_save' => [
		'pattern' => '/comments/:string/:int/:int/save',
		'methods' => 'POST',
		'controller' => 'Comments',
		'action' => 'save',
		'params' => ['mod' => 1, 'value' => 2, 'comment_id' => 3]
	],

	'subscribes_update' => [
		'pattern' => '/subscribes/:string/:int',
		'methods' => 'POST',
		'controller' => 'Subscribes',
		'action' => 'update',
		'params' => ['mod' => 1, 'value' => 2]
	],

	'users_autocomplete' => [
		'pattern' => '/users/autocomplete/json',
		'methods' => 'POST',
		'controller' => 'Users',
		'action' => 'autocomplete',
	],

	'file_uploader' => [
		'pattern' => '/uploader',
		'methods' => 'POST',
		'controller' => 'Uploader',
	],

	/*'notfound' => [
		'pattern' => '/404',
		'view' => '404'
	],*/
];

?>