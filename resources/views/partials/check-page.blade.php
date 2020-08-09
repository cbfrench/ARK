@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
@php $animal = check_validity(); @endphp
<div class="student-image __content">
    <div class="container">
        @for($i = 1; $i <= (int)$_GET['ns']; $i++)
            @php 
                $name = $_GET['fname-' . $i] . ' ' . $_GET['lname-' . $i];
                $temperature = $_GET['temp-' . $i] . '° F';
                $date = $_GET['date'];
            @endphp
            <div class="check-image">
                <h1 class="check-image__name">{{$name}}</h1>
                <h1 class="check-image__temperature">{{$temperature}}</h1>
                <h1 class="check-image__date">{{$date}}</h1>
                <div class="check-image__animal" style="background-image: url('{{$animal}}');"></div>
            </div>
        @endfor
    </div>
</div>
