@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
@php
    global $wpdb;
    $today = (new DateTime('now', new DateTimeZone('America/Los_Angeles')))->format('Y-m-d');
    $max_temp = get_field('temperature', 'option');
    $max_symp = get_field('symptoms', 'option')['max_symptoms'];
    $sick = (int)($wpdb->get_results('SELECT count(*) total FROM students where (temperature >= ' . $max_temp . ' or symptom_count >= ' . $max_symp . ') and checked = "' . $today . '"'))[0]->total;
    $healthy = (int)($wpdb->get_results('SELECT count(*) total FROM students where (temperature < ' . $max_temp . ' and symptom_count < ' . $max_symp . ') and checked = "' . $today . '"'))[0]->total;
    $total = (int)($wpdb->get_results('SELECT count(*) total FROM students'))[0]->total;
    $animal = verify_animal();
@endphp
@if(!post_password_required())
    <div class="status-information __content">
        <div class="container">
            <div class="stats-section">
                <h3>Stats</h3>
                <table class="general-stats">
                    <tr>
                        <th class="healthy__heading">Healthy</th>
                        <th class="sick__heading">Sick</th>
                        <th class="missing__heading">Not Checked In</th>
                        <th class="students__heading">Total</th>
                    </tr>
                    <tr>
                        <td class="healthy__total">{{$healthy}}</td>
                        <td class="sick__total">{{$sick}}</td>
                        <td class="missing__total">{{$total - $healthy - $sick}}</td>
                        <td class="students__total">{{$total}}</td>
                </table>
            </div>
            <div class="verification">
                <h3>Today's Image</h3>
                <img src="{{$animal}}" />
            </div>
            <div class="information">
                <select class="information__select">
                    <option selected value=0>All</option>
                    <option value=1>Healthy</option>
                    <option value=2>Sick</option>
                    <option value=3>Not Checked In</option>
                </select>
                <table class="student-info">
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Time</th>
                        <th>Temperature</th>
                        <th>Symptoms</th>
                    </tr>
                    @php $results = $wpdb->get_results('SELECT * FROM students ORDER BY last_name, first_name;'); @endphp
                    @foreach($results as $row)
                        @php $temp_taken = ''; @endphp
                        @if($row->temperature_taken)
                            @php $temp_taken = DateTime::createFromFormat('Y-m-d H:i:s', $row->temperature_taken);
                            $temp_taken = $temp_taken->format('h:i A'); @endphp
                        @endif
                        <tr
                                @if($today == $row->checked)
                                    @if($row->temperature < $max_temp && $row->symptom_count < $max_symp)
                                        class="good-to-go">
                                    @else
                                        class="stay-home">
                                    @endif
                                    <td>{{$row->last_name}}</td>
                                    <td>{{$row->first_name}}</td>
                                    <td>{{$temp_taken}}</td>
                                    <td>{{$row->temperature}}</td>
                                    <td>{{$row->symptom_count}}</td>
                                @else
                                    class="not-checked-in">
                                    <td>{{$row->last_name}}</td>
                                    <td>{{$row->first_name}}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endif