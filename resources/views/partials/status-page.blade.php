@php the_content() @endphp
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
@php $status = admin_status(); @endphp
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
                        <td class="healthy__total">{{$status['healthy']}}</td>
                        <td class="sick__total">{{$status['sick']}}</td>
                        <td class="missing__total">{{$status['total'] - $status['healthy'] - $status['sick']}}</td>
                        <td class="students__total">{{$status['total']}}</td>
                </table>
            </div>
            <div class="verification">
                <h3>Today's Image</h3>
                <img src="{{$status['animal']}}" />
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
                    @php display_students($status); @endphp
                </table>
            </div>
        </div>
    </div>
@endif