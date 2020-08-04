@php
  $site_link = esc_url(home_url('/'));
  $site_title = esc_attr(get_bloginfo('name', 'display'));
  $site_logo = get_theme_mod('m1_logo');
  $site_name = get_bloginfo('name');
  $site_description = get_bloginfo('description');
@endphp
<header class="banner">
  <div class="container">
  @if(get_theme_mod('m1_logo'))
    <a href="{{$site_link}}" id="site-logo" title="{{$site_title}}" rel="home" class="site-logo">
        <img src="{{$site_logo}}" alt="{{$site_title}}">
    </a>
  @else          
    <hgroup>
        <h1 class="site-title"><a href="{{$site_link}}" title="{{$site_title}}" rel="home">{{$site_name}}</a></h1>
        <p class="site-description">{{$site_description}}</p>
    </hgroup>              
  @endif
    <nav class="nav-primary">
      @if(has_nav_menu('primary_navigation'))
        {!!wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav'])!!}
      @endif
    </nav>
  </div>
</header>
