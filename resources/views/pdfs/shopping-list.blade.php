<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .page-break {
            page-break-after: always;
        }
        .left {
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .center {
            text-align: center;
        }
        td, th {
            padding: 0.25em;
        }
    </style>
</head>
<body>
    <h1 class="center">Shopping List "{{ $menuplan->title }}"</h1>
    <table>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td class="right">{{ $item['quantity'] }}</td>
                <td class="left">{{ $item['unit'] }}</td>
                <td class="left">{{ $item['title'] }}</td>
                <td class="left">
                    <small>
                        {{
                            collect($item['meals'])->map(function ($meal) use ($item) {
                                return sprintf('%s %s %s', 
                                    $meal['title'],
                                    $meal['quantity'],
                                    $item['unit']
                                );
                            })->implode(', ')
                        }}
                    </small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html