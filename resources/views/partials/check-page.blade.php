@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
@php
    if(!isset($_GET['date']) || !isset($_GET['day'])){
        header("Location: /");
        return;
    }
    $animal = verify_animal();
@endphp
<div class="student-image __content">
    <div class="container">
        <div class="check-image">
            <h1 class="check-image__name">{{$_GET['fname']}} {{$_GET['lname']}}</h1>
            <h1 class="check-image__temperature">{{$_GET['temp']}}° F</h1>
            <h1 class="check-image__date">{{$_GET['date']}}</h1>
            <div class="check-image__animal" style="background-image: url('{{$animal}}');"></div>
        </div>
    </div>
</div>
