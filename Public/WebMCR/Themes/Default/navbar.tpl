<div class="navbar">
    <div class="container">
        <div class="navbar-wrapper">
            <div class="block-left">
                <ul>
                    <li><a class="mobile" href="#"><i class="fa fa-bars"></i></a></li>
                    <li><a class="brand" href="{{__META__.site_url}}">{{__META__.sitename}}</a></li>
                </ul>
            </div>

            <div class="block-right">
                <ul>
                    <li class="{% if navbar_menu_active=='main' %}active{% endif %}"><a href="{{__META__.site_url}}">Главная</a></li>

                    {% if __PERMISSION__.news_list %}
                        <li class="{% if navbar_menu_active=='news' %}active{% endif %}"><a href="{{__META__.site_url}}news">Новости</a></li>
                    {% endif %}

                    {% if __USERCORE__.isAuth() %}
                        <li class="{% if navbar_menu_active=='profile' %}active{% endif %}"><a href="{{__META__.site_url}}profile/">Личный кабинет</a></li>
                    {% endif %}

                    {% if __PERMISSION__.admin %}
                        <li class="{% if navbar_menu_active=='admin' %}active{% endif %}"><a href="{{__META__.site_url}}admin/">ПУ</a></li>
                    {% endif %}
                </ul>
            </div>
        </div>
    </div>
</div>