<?php

return [
	'admin_main' => [
		'pattern' => '/admin',
		'controller' => 'Admin\Main',
	],

	'admin_menu_stats' => [
		'pattern' => '/admin/stats/menu/json',
		'parent' => 'admin_main',
		'methods' => 'POST',
		'action' => 'menuStats'
	],

	'admin_statics' => [
		'pattern' => '/admin/statics',
		'controller' => 'Admin\Statics',
		'aliases' => ['/admin/statics/page-:int' => ['page_id' => 1]]
	],
	'admin_statics_edit' => [
		'pattern' => '/admin/statics/edit/:int',
		'controller' => 'Admin\Statics',
		'action' => 'editItem',
		'params' => ['id' => 1],
	],
	'admin_statics_edit_submit' => [
		'pattern' => '/admin/statics/edit/:int/submit',
		'controller' => 'Admin\Statics',
		'methods' => 'POST',
		'action' => 'editItemSubmit',
		'params' => ['id' => 1],
	],
	'admin_statics_add' => [
		'pattern' => '/admin/statics/add',
		'controller' => 'Admin\Statics',
		'action' => 'addItem',
	],
	'admin_statics_add_submit' => [
		'pattern' => '/admin/statics/add/submit',
		'controller' => 'Admin\Statics',
		'methods' => 'POST',
		'action' => 'addItemSubmit',
	],
	'admin_statics_remove' => [
		'pattern' => '/admin/statics/remove/:int',
		'controller' => 'Admin\Statics',
		'methods' => 'POST',
		'action' => 'removeItem',
		'params' => ['id' => 1],
	],
	'admin_statics_public' => [
		'pattern' => '/admin/statics/public/:int/:int',
		'controller' => 'Admin\Statics',
		'methods' => 'POST',
		'action' => 'publicItem',
		'params' => ['id' => 1, 'value' => 2],
	],

	'admin_news' => [
		'pattern' => '/admin/news',
		'controller' => 'Admin\News\News',
		'aliases' => ['/admin/news/page-:int' => ['page_id' => 1]]
	],
	'admin_news_edit' => [
		'pattern' => '/admin/news/edit/:int',
		'controller' => 'Admin\News\News',
		'action' => 'editItem',
		'params' => ['id' => 1],
	],
	'admin_news_edit_submit' => [
		'pattern' => '/admin/news/edit/:int/submit',
		'controller' => 'Admin\News\News',
		'methods' => 'POST',
		'action' => 'editItemSubmit',
		'params' => ['id' => 1],
	],
	'admin_news_add' => [
		'pattern' => '/admin/news/add',
		'controller' => 'Admin\News\News',
		'action' => 'addItem',
	],
	'admin_news_add_submit' => [
		'pattern' => '/admin/news/add/submit',
		'controller' => 'Admin\News\News',
		'methods' => 'POST',
		'action' => 'addItemSubmit',
	],
	'admin_news_remove' => [
		'pattern' => '/admin/news/remove/:int',
		'controller' => 'Admin\News\News',
		'methods' => 'POST',
		'action' => 'removeItem',
		'params' => ['id' => 1],
	],
	'admin_news_public' => [
		'pattern' => '/admin/news/public/:int/:int',
		'controller' => 'Admin\News\News',
		'methods' => 'POST',
		'action' => 'publicItem',
		'params' => ['id' => 1, 'value' => 2],
	],

	'admin_news_tags' => [
		'pattern' => '/admin/news/tags',
		'controller' => 'Admin\News\Tags',
		'aliases' => ['/admin/news/tags/page-:int' => ['page_id' => 1]]
	],
	'admin_news_tags_edit' => [
		'pattern' => '/admin/news/tags/edit/:int',
		'controller' => 'Admin\News\Tags',
		'action' => 'editItem',
		'params' => ['id' => 1],
	],
	'admin_news_tags_edit_submit' => [
		'pattern' => '/admin/news/tags/edit/:int/submit',
		'controller' => 'Admin\News\Tags',
		'methods' => 'POST',
		'action' => 'editItemSubmit',
		'params' => ['id' => 1],
	],
	'admin_news_tags_add' => [
		'pattern' => '/admin/news/tags/add',
		'controller' => 'Admin\News\Tags',
		'action' => 'addItem',
	],
	'admin_news_tags_add_submit' => [
		'pattern' => '/admin/news/tags/add/submit',
		'controller' => 'Admin\News\Tags',
		'methods' => 'POST',
		'action' => 'addItemSubmit',
	],
	'admin_news_tags_remove' => [
		'pattern' => '/admin/news/tags/remove/:int',
		'controller' => 'Admin\News\Tags',
		'methods' => 'POST',
		'action' => 'removeItem',
		'params' => ['id' => 1],
	],

	'admin_permissions' => [
		'pattern' => '/admin/permissions',
		'controller' => 'Admin\Users\Permissions',
		'aliases' => ['/admin/permissions/page-:int' => ['page_id' => 1]]
	],
	'admin_permissions_edit' => [
		'pattern' => '/admin/permissions/edit/:int',
		'controller' => 'Admin\Users\Permissions',
		'action' => 'editItem',
		'params' => ['id' => 1],
	],
	'admin_permissions_edit_submit' => [
		'pattern' => '/admin/permissions/edit/:int/submit',
		'controller' => 'Admin\Users\Permissions',
		'methods' => 'POST',
		'action' => 'editItemSubmit',
		'params' => ['id' => 1],
	],
	'admin_permissions_add' => [
		'pattern' => '/admin/permissions/add',
		'controller' => 'Admin\Users\Permissions',
		'action' => 'addItem',
	],
	'admin_permissions_add_submit' => [
		'pattern' => '/admin/permissions/add/submit',
		'controller' => 'Admin\Users\Permissions',
		'methods' => 'POST',
		'action' => 'addItemSubmit',
	],
	'admin_permissions_remove' => [
		'pattern' => '/admin/permissions/remove/:int',
		'controller' => 'Admin\Users\Permissions',
		'methods' => 'POST',
		'action' => 'removeItem',
		'params' => ['id' => 1],
	],

	'admin_groups' => [
		'pattern' => '/admin/groups',
		'controller' => 'Admin\Users\Groups',
		'aliases' => ['/admin/groups/page-:int' => ['page_id' => 1]]
	],
	'admin_groups_edit' => [
		'pattern' => '/admin/groups/edit/:int',
		'controller' => 'Admin\Users\Groups',
		'action' => 'editItem',
		'params' => ['id' => 1],
	],
	'admin_groups_edit_submit' => [
		'pattern' => '/admin/groups/edit/:int/submit',
		'controller' => 'Admin\Users\Groups',
		'methods' => 'POST',
		'action' => 'editItemSubmit',
		'params' => ['id' => 1],
	],
	'admin_groups_add' => [
		'pattern' => '/admin/groups/add',
		'controller' => 'Admin\Users\Groups',
		'action' => 'addItem',
	],
	'admin_groups_add_submit' => [
		'pattern' => '/admin/groups/add/submit',
		'controller' => 'Admin\Users\Groups',
		'methods' => 'POST',
		'action' => 'addItemSubmit',
	],
	'admin_groups_remove' => [
		'pattern' => '/admin/groups/remove/:int',
		'controller' => 'Admin\Users\Groups',
		'methods' => 'POST',
		'action' => 'removeItem',
		'params' => ['id' => 1],
	],

	'admin_settings' => [
		'pattern' => '/admin/settings',
		'controller' => 'Admin\Settings'
	],
	'admin_settings_save' => [
		'pattern' => '/admin/settings/save',
		'controller' => 'Admin\Settings',
		'methods' => 'POST',
		'action' => 'save',
	],

	'admin_users' => [
		'pattern' => '/admin/users',
		'controller' => 'Admin\Users\Users',
		'aliases' => ['/admin/users/page-:int' => ['page_id' => 1]]
	],
	'admin_users_edit' => [
		'pattern' => '/admin/users/edit/:int',
		'controller' => 'Admin\Users\Users',
		'action' => 'editItem',
		'params' => ['id' => 1],
	],
	'admin_users_edit_submit' => [
		'pattern' => '/admin/users/edit/:int/submit',
		'controller' => 'Admin\Users\Users',
		'methods' => 'POST',
		'action' => 'editItemSubmit',
		'params' => ['id' => 1],
	],
	'admin_users_upload_avatar' => [
		'pattern' => '/admin/users/avatar',
		'controller' => 'Admin\Users\Users',
		'methods' => 'POST',
		'action' => 'uploadAvatar',
	],
	'admin_users_remove' => [
		'pattern' => '/admin/users/remove/:int',
		'controller' => 'Admin\Users\Users',
		'methods' => 'POST',
		'action' => 'removeItem',
		'params' => ['id' => 1],
	],
	'admin_users_ban' => [
		'pattern' => '/admin/users/ban/:int/:int',
		'controller' => 'Admin\Users\Users',
		'methods' => 'POST',
		'action' => 'banItem',
		'params' => ['id' => 1, 'value' => 2],
	],
	'admin_users_banip' => [
		'pattern' => '/admin/users/banip/',
		'controller' => 'Admin\Users\Users',
		'methods' => 'POST',
		'action' => 'banipItem',
	],

	'admin_logs' => [
		'pattern' => '/admin/logs',
		'controller' => 'Admin\Logs',
		'aliases' => ['/admin/logs/page-:int' => ['page_id' => 1]]
	],
	'admin_logs_remove' => [
		'pattern' => '/admin/logs/remove/:int',
		'controller' => 'Admin\Logs',
		'methods' => 'POST',
		'action' => 'removeItem',
		'params' => ['id' => 1],
	],
	'admin_logs_public' => [
		'pattern' => '/admin/statics/public/:int/:int',
		'controller' => 'Admin\Logs',
		'methods' => 'POST',
		'action' => 'publicItem',
		'params' => ['id' => 1, 'value' => 2],
	],
	/*'admin_users_clear' => [
		'pattern' => '/admin/users/clear/:int',
		'controller' => 'Admin\Users\Users',
		'methods' => 'POST',
		'action' => 'clearItem',
		'params' => ['id' => 1],
	],*/
];

?>