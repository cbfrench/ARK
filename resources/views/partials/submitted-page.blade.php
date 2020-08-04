@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
@php
    $successful_url = submitted_url();
@endphp
<div class="submitted __content">
    <div class="container">
        <h1>{{$_SESSION['child']['fname']}} {{$_SESSION['child']['lname']}}</h1>
        <h2>{{$_SESSION['child']['temperature']}} ° F</h2>
        <div class="links">
            {!!do_shortcode('[kaya_qrcode content="' . $successful_url . '"]')!!}
            <p>or</p>
            <div class="success-button">
                <a href="{{$successful_url}}">Click Here</a>
            </div>
            @php update_student($_SESSION['child']) @endphp
        </div>
    </div>
</div>