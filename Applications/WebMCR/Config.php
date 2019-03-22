<?php // WebMCR 3 Main Config | Updated: 19.03.2019 16:59:18

return array (
  'install' => 0,
  'version' => '1.0.0',
  'userLogic' => 'App\\WebMCR\\Models\\User\\Logic\\DefaultLogic',
  'csrfString' => 'йгтщТhя1хZъОfy0БщD$YА`ЮТFюзэпЖВИ',
  'database' => 
  array (
    'driver' => 'mysqli',
    'host' => 'localhost',
    'port' => '3306',
    'charset' => 'utf8mb4',
    'timeout' => 3,
    'database' => 'webmcr',
    'user' => 'root',
    'password' => '',
    'economy' => 
    array (
      'enable' => true,
      'table' => 'user_balance',
      'login_column' => 'login',
    ),
  ),
  'meta' => 
  array (
    'sitename' => 'WebMCR',
    'sitedesc' => 'WebMCR 3 - Powerful multifunction CMS',
    'sitekeys' => 'webmcr, qexy, cms',
    'theme' => 'Default',
    'theme_url' => '/Themes/Default/',
    'site_url' => '/',
    'full_site_url' => 'http://mysite.com',
    'cache_version_css' => '1',
    'cache_version_js' => '1',
  ),
  'changegroup' => 
  array (
    'ban' => 3,
    'back' => 1,
    'remove' => 1,
    'pageipban' => '/403',
  ),
  'register' => 
  array (
    'enable' => true,
    'captcha' => true,
    'type' => 'email',
  ),
  'captcha' => 
  array (
    'recaptcha' => 
    array (
      'public' => '6Leu1X4UAAAAAKRHsQf2Q8vgf9cxQaLV_ATa0MXt',
      'private' => '6Leu1X4UAAAAAI4DIcQqjZMk4ZV5lrurZFUnDKO8',
    ),
  ),
  'money' => 
  array (
    'gamemoney' => 
    array (
      'enable' => true,
      'name' => 'Игровая валюта',
      'column' => 'money',
      'cur' => 'М.',
    ),
    'realmoney' => 
    array (
      'enable' => true,
      'name' => 'Рубли',
      'column' => 'realmoney',
      'cur' => 'Р.',
    ),
  ),
  'password' => 
  array (
    'min' => 6,
    'max' => 0,
    'algo' => 'md5',
    'prefix' => false,
  ),
  'restore_expire' => 86400,
  'mail' => 
  array (
    'smtp' => false,
    'host' => 'smtp.myserver.com',
    'port' => 465,
    'secure' => 'ssl',
    'username' => 'admin@mysite.com',
    'password' => 'SUPER_SECRET_PASSWORD',
    'from' => 'admin@mysite.com',
    'from_name' => 'WebMCR',
    'lng' => 'ru',
    'blacklist' => 
    array (
    ),
    'whitelist' => 
    array (
    ),
  ),
  'pagination' => 
  array (
    'news' => 
    array (
      'list' => 10,
      'comments' => 10,
    ),
    'profile' => 
    array (
      'comments' => 10,
      'messages' => 10,
      'reply' => 10,
      'activity' => 20,
    ),
    'users' => 
    array (
      'list' => 10,
      'comments' => 10,
    ),
    'admin' => 
    array (
      'statics' => 15,
      'logs' => 20,
      'news' => 12,
      'news_tags' => 15,
      'users' => 15,
      'user_groups' => 15,
      'permissions' => 20,
    ),
  ),
  'comment_mods' => 
  array (
    'users' => 
    array (
      'type' => 'users',
      'amount' => 10,
      'prefix' => 'users',
      'order_by' => 'id',
      'order' => 'DESC',
      'comment_id_tpl' => 'Resources/Comments/tpl/comment-id.tpl',
      'table' => 'users',
      'stats' => true,
      'values' => true,
    ),
    'news' => 
    array (
      'type' => 'news',
      'amount' => 10,
      'prefix' => 'news',
      'order_by' => 'id',
      'order' => 'DESC',
      'comment_id_tpl' => 'Resources/Comments/tpl/comment-id.tpl',
      'table' => 'news',
      'stats' => true,
      'values' => true,
    ),
  ),
  'subscribe_mods' => 
  array (
    'users' => 
    array (
      'type' => 'users',
      'prefix' => 'users',
      'table' => 'users',
      'values' => true,
    ),
  ),
  'logger' => 
  array (
    'enable' => true,
    'items' => 
    array (
      'admin_tags_add' => 
      array (
        'title' => '[ПУ] Добавление тега новости',
        'class' => 'fa fa-tag',
      ),
      'admin_tags_edit' => 
      array (
        'title' => '[ПУ] Изменение тега новости',
        'class' => 'fa fa-pencil',
      ),
      'admin_tags_remove' => 
      array (
        'title' => '[ПУ] Удаление тега новости',
        'class' => 'fa fa-trash',
      ),
      'admin_news_remove' => 
      array (
        'title' => '[ПУ] Удаление новости',
        'class' => 'fa fa-trash',
      ),
      'admin_news_add' => 
      array (
        'title' => '[ПУ] Добавление новости',
        'class' => 'fa fa-newspaper-o',
      ),
      'admin_news_edit' => 
      array (
        'title' => '[ПУ] Изменение новости',
        'class' => 'fa fa-pencil',
      ),
      'admin_news_publish' => 
      array (
        'title' => '[ПУ] Публикация новости',
        'class' => 'fa fa-eye',
      ),
      'admin_news_unpublish' => 
      array (
        'title' => '[ПУ] Скрытие новости',
        'class' => 'fa fa-eye-slash',
      ),
      'admin_users_banip' => 
      array (
        'title' => '[ПУ] Бан IP адреса',
        'class' => 'fa fa-ban',
      ),
      'admin_users_remove' => 
      array (
        'title' => '[ПУ] Удаление пользователя',
        'class' => 'fa fa-trash',
      ),
      'admin_users_edit' => 
      array (
        'title' => '[ПУ] Изменение пользователя',
        'class' => 'fa fa-pencil',
      ),
      'admin_users_ban' => 
      array (
        'title' => '[ПУ] Бан пользователя',
        'class' => 'fa fa-ban',
      ),
      'admin_permissions_add' => 
      array (
        'title' => '[ПУ] Добавление привилегии',
        'class' => 'fa fa-unlock-alt',
      ),
      'admin_permissions_edit' => 
      array (
        'title' => '[ПУ] Изменение привилегии',
        'class' => 'fa fa-pencil',
      ),
      'admin_permissions_remove' => 
      array (
        'title' => '[ПУ] Удаление привилегии',
        'class' => 'fa fa-trash',
      ),
      'admin_groups_add' => 
      array (
        'title' => '[ПУ] Добавление группы пользователей',
        'class' => 'fa fa-users',
      ),
      'admin_groups_edit' => 
      array (
        'title' => '[ПУ] Изменение группы пользователей',
        'class' => 'fa fa-pencil',
      ),
      'admin_groups_remove' => 
      array (
        'title' => '[ПУ] Удаление группы пользователей',
        'class' => 'fa fa-trash',
      ),
      'admin_logs_remove' => 
      array (
        'title' => '[ПУ] Удаление лога действия',
        'class' => 'fa fa-trash',
      ),
      'admin_settings' => 
      array (
        'title' => '[ПУ] Изменение настроек',
        'class' => 'fa fa-cogs',
      ),
      'admin_statics_edit' => 
      array (
        'title' => '[ПУ] Изменение статической страницы',
        'class' => 'fa fa-pencil',
      ),
      'admin_statics_add' => 
      array (
        'title' => '[ПУ] Добавление статической страницы',
        'class' => 'fa fa-file',
      ),
      'admin_statics_unpublish' => 
      array (
        'title' => '[ПУ] Скрытие статической страницы',
        'class' => 'fa fa-eye-slash',
      ),
      'admin_statics_publish' => 
      array (
        'title' => '[ПУ] Публикация статической страницы',
        'class' => 'fa fa-eye',
      ),
      'admin_statics_remove' => 
      array (
        'title' => '[ПУ] Удаление статической страницы',
        'class' => 'fa fa-trash',
      ),
      'unsubscribe' => 
      array (
        'title' => 'Отмена подписки',
        'class' => 'fa fa-bullhorn',
      ),
      'subscribe' => 
      array (
        'title' => 'Оформление подписки',
        'class' => 'fa fa-bullhorn',
      ),
      'news_unlike' => 
      array (
        'title' => 'Разонравилась новость',
        'class' => 'fa fa-heart-o',
      ),
      'news_like' => 
      array (
        'title' => 'Понравилась новость',
        'class' => 'fa fa-heart',
      ),
      'comments_edit' => 
      array (
        'title' => 'Изменение комментария',
        'class' => 'fa fa-pencil',
      ),
      'comments_remove' => 
      array (
        'title' => 'Удаление комментария',
        'class' => 'fa fa-trash',
      ),
      'comments_add' => 
      array (
        'title' => 'Добавление комментария',
        'class' => 'fa fa-comment',
      ),
      'profile_message_reply_edit' => 
      array (
        'title' => 'Изменение ответа в беседе',
        'class' => 'fa fa-pencil',
      ),
      'profile_message_reply_remove' => 
      array (
        'title' => 'Удаление ответа в беседе',
        'class' => 'fa fa-trash',
      ),
      'profile_message_reply_add' => 
      array (
        'title' => 'Отправка ответа в беседе',
        'class' => 'fa fa-reply',
      ),
      'profile_message_remove' => 
      array (
        'title' => 'Удаление беседы',
        'class' => 'fa fa-trash',
      ),
      'profile_message_unlock' => 
      array (
        'title' => 'Открытие беседы',
        'class' => 'fa fa-unlock',
      ),
      'profile_message_lock' => 
      array (
        'title' => 'Закрытие беседы',
        'class' => 'fa fa-lock',
      ),
      'profile_message_create' => 
      array (
        'title' => 'Создание беседы',
        'class' => 'fa fa-envelope',
      ),
      'profile_settings_change' => 
      array (
        'title' => 'Изменение настроек',
        'class' => 'fa fa-cog',
      ),
      'profile_change_avatar' => 
      array (
        'title' => 'Изменение аватара',
        'class' => 'fa fa-picture-o',
      ),
      'register' => 
      array (
        'title' => 'Регистрация',
        'class' => 'fa fa-user',
      ),
      'register_try' => 
      array (
        'title' => 'Попытка регистрации',
        'class' => 'fa fa-user-plus',
      ),
      'auth' => 
      array (
        'title' => 'Вход',
        'class' => 'fa fa-sign-out',
      ),
      'auth_try' => 
      array (
        'title' => 'Попытка входа',
        'class' => 'fa fa-key',
      ),
      'logout' => 
      array (
        'title' => 'Выход',
        'class' => 'fa fa-sign-out',
      ),
      'restore' => 
      array (
        'title' => 'Сброс пароля',
        'class' => 'fa fa-key',
      ),
      'restore_try' => 
      array (
        'title' => 'Попытка сброса пароля',
        'class' => 'fa fa-key',
      ),
    ),
    'store' => 
    array (
      0 => 'admin_tags_add',
      1 => 'admin_tags_edit',
      2 => 'admin_tags_remove',
      3 => 'admin_news_remove',
      4 => 'admin_news_add',
      5 => 'admin_news_edit',
      6 => 'admin_news_publish',
      7 => 'admin_news_unpublish',
      8 => 'admin_users_banip',
      9 => 'admin_users_remove',
      10 => 'admin_users_edit',
      11 => 'admin_users_ban',
      12 => 'admin_permissions_add',
      13 => 'admin_permissions_edit',
      14 => 'admin_permissions_remove',
      15 => 'admin_groups_add',
      16 => 'admin_groups_edit',
      17 => 'admin_groups_remove',
      18 => 'admin_logs_remove',
      19 => 'admin_settings',
      20 => 'admin_statics_edit',
      21 => 'admin_statics_add',
      22 => 'admin_statics_unpublish',
      23 => 'admin_statics_publish',
      24 => 'admin_statics_remove',
      25 => 'unsubscribe',
      26 => 'subscribe',
      27 => 'news_unlike',
      28 => 'news_like',
      29 => 'comments_edit',
      30 => 'comments_remove',
      31 => 'comments_add',
      32 => 'profile_message_reply_edit',
      33 => 'profile_message_reply_remove',
      34 => 'profile_message_reply_add',
      35 => 'profile_message_remove',
      36 => 'profile_message_unlock',
      37 => 'profile_message_lock',
      38 => 'profile_message_create',
      39 => 'profile_settings_change',
      40 => 'profile_change_avatar',
      41 => 'register',
      42 => 'register_try',
      43 => 'auth',
      44 => 'auth_try',
      45 => 'logout',
      46 => 'restore',
      47 => 'restore_try',
    ),
    'activity' => 
    array (
      0 => 'unsubscribe',
      1 => 'subscribe',
      2 => 'news_unlike',
      3 => 'news_like',
      4 => 'comments_edit',
      5 => 'comments_remove',
      6 => 'comments_add',
      7 => 'profile_message_reply_edit',
      8 => 'profile_message_reply_remove',
      9 => 'profile_message_reply_add',
      10 => 'profile_message_remove',
      11 => 'profile_message_unlock',
      12 => 'profile_message_lock',
      13 => 'profile_message_create',
      14 => 'profile_settings_change',
      15 => 'profile_change_avatar',
      16 => 'register',
      17 => 'auth',
      18 => 'auth_try',
      19 => 'logout',
      20 => 'restore',
      21 => 'restore_try',
    ),
  ),
);

?>