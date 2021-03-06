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
        .text-gray-500 {
            color: #b8c2cc;
        }
        .text-4xl {
            font-size: 2.25rem;
        }
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
            bottom: 0px;
        }
        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="footer text-gray-500">
        @lang('app.page') <span class="pagenum"></span>
    </div>

    <h1 class="text-4xl center">@lang('app.shoppinglist') "{{ $title }}"</h1>
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
                                return sprintf('%s, %s [%s%s]',
                                    $meal['title'],
                                    $meal['formatedDate'],
                                    $meal['quantity'],
                                    $item['unit']
                                );
                            })->implode(' · ')
                        }}
                    </small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html
