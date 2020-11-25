<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        table, th, td{
            border:1px solid black;
        }
        table{
            border-collapse:collapse;
        }
    </style>
</head>
<body>
    <div bgcolor="#70bbd9" style="padding: 40px 0 30px 0;">
        <img src="{{ $message->embed(public_path() . '/LOGO.png') }}" alt="logo" width="300" height="230" style="display: block; background: black" />
    </div>
    <h4>Dear Admin,<h4>

    <p>There are {{ $countUser }} new users for this month, here is the list:</p>

    <table>
    @foreach($users as $index => $user)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user['name'] }}</td>
        </tr>
    @endforeach
    </table>

    <p>There are <b>{{ $countMovie }}</b> new movies for this month, here is the list:</p>

    <table>
    @foreach($movies as $index => $movie)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $movie['name'] }}</td>
        </tr>
    @endforeach
    </table>
</body>
</html>
