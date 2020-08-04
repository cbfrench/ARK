@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}

<div class="health-reporting __content">
    <div class="container">
        <h1>Health Reporting</h1>
        <form action="" method="post" id="health-reporting-form">
            <div class="information">
                <input type="text" placeholder="Child's First Name" name="first_name" class="child-name"/>
                <input type="text" placeholder="Child's Last Name" name="last_name" class="child-name"/>
                <input type="text" placeholder="Child's RENWEB ID" name="id" class="child-id">
                <input type="number" placeholder="Temperature (F)" step="0.1" name="temperature" min="0" class="child-temperature"/>
            </div>
            <h4>Symptoms</h4>
            <div class="symptoms">
                @while(have_rows('symptoms', 'option'))
                    @php the_row() @endphp
                    @while(have_rows('symptoms'))
                        @php the_row() @endphp
                        <input type="checkbox" id="symptom-{{get_row_index()}}" name="symptom-{{get_row_index()}}"/>
                    @endwhile
                    @while(have_rows('symptoms'))
                        @php the_row() @endphp
                        <label for="symptom-{{get_row_index()}}">{{the_sub_field('symptom')}}</label>
                    @endwhile
                @endwhile
            </div>
            <input type="submit" name="health_reporting" class="submit-button"/>
        </form>
    </div>
</div>