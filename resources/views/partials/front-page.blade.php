@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}

<div class="health-reporting __content">
    <div class="container">
        <h1>Health Reporting</h1>
        <form action="" method="post" id="health-reporting-form">
            <div class="form-body">
                <div class="number-of-students">
                    <h4>Number of Students</h4>
                    <input type="number" placeholder="Number of Students" step="1" name="number_of_students" min="1" max="10" value="1" class="number-of-students__button"/>
                </div>
                @php $count=1; @endphp
                @for($count; $count <= 10; $count++)
                <div class="student" id="student-{{$count}}">
                    <div class="student__container">
                        <h2>Student {{$count}}</h2>
                        <h4>Symptoms</h4>
                        <div class="information">
                            <input type="text" placeholder="Child's First Name" name="student-{{$count}}-first_name" class="child-name"/>
                            <input type="text" placeholder="Child's Last Name" name="student-{{$count}}-last_name" class="child-name"/>
                            <input type="text" placeholder="Child's RENWEB ID" name="student-{{$count}}-id" class="child-id">
                            <input type="number" placeholder="Temperature (F)" step="0.1" name="student-{{$count}}-temperature" min="0" class="child-temperature"/>
                        </div>
                        <div class="symptoms">
                            @while(have_rows('symptoms', 'option'))
                                @php the_row() @endphp
                                @while(have_rows('symptoms'))
                                    @php the_row() @endphp
                                    <input type="checkbox" id="student-{{$count}}-symptom-{{get_row_index()}}" name="student-{{$count}}-symptom-{{get_row_index()}}"/>
                                @endwhile
                                @while(have_rows('symptoms'))
                                    @php the_row() @endphp
                                    <label for="student-{{$count}}-symptom-{{get_row_index()}}">{{the_sub_field('symptom')}}</label>
                                @endwhile
                            @endwhile
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <input type="submit" name="health_reporting" class="submit-button"/>
        </form>
    </div>
</div>