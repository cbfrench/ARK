@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
@php $successful_url = submitted_url(); @endphp
<div class="submitted __content">
    <div class="container">
        @for($i = 1; $i <= (int)$_SESSION['number_of_students']; $i++)
            @php $child = $_SESSION['child-' . $i] @endphp
            <h1>{{$child['fname']}} {{$child['lname']}}</h1>
            <h2>{{$child['temperature']}} ° F</h2>
            @php update_student($child) @endphp
        @endfor
        <div class="links">
            {!!do_shortcode('[kaya_qrcode content="' . $successful_url . '"]')!!}
            <p>or</p>
            <div class="success-button">
                <a href="{{$successful_url}}">Click Here</a>
            </div>
        </div>
    </div>
</div>