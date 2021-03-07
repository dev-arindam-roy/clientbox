<!DOCTYPE html>
<html>
<head>
    <title>Client Box</title>
</head>
<body>
    <div class="container">
        <h1>My Clients</h1>
        <hr/>
        <table class="pdf-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile No</th>
                    <th>Country</th>
                    <th>City</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($clients) && count($clients))
                @php $sl = 1; @endphp
                @foreach($clients as $v)
                    <tr>
                        <td>{{ $sl }}</td>
                        <td>{{ $v->first_name . ' ' . $v->last_name }}</td>
                        <td>{{ $v->email_id }}</td>
                        <td>{{ $v->phno }}</td>
                        <td>
                            @if(isset($v->countryInfo) && !empty($v->countryInfo))
                                {{ $v->countryInfo->name }}
                            @endif
                        </td>
                        <td>{{ $v->city }}</td>
                    </tr>
                @php $sl++; @endphp
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</body>
</html>