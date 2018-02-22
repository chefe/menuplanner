<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        small {
            font-size: 80%;
        }
        p {
            margin: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center {
            text-align: center;
        }
        .flex {
            display: flex;
        }
        .flex-wrap {
            flex-wrap: wrap;
        }
        .flex-1 {
            flex: 1;
        }
        .p-2 {
            padding: .5rem;
        }
        .py-2 {
            padding-top: .5rem;
            padding-bottom: .5rem;
        }
        .mt-3 {
            margin-top: .75rem;
        }
        .block {
            display: block;
        }
        .text-xl {
            font-size: 1.25rem;
        }
        .text-grey-darkest {
            color: #3d4852;
        }
        .border-b {
            border-bottom-width: 1px;
        }
        .text-grey {
            color: #b8c2cc;
        }
        .border-b {
            border-style: solid;
            border-color: #dae1e7;
            border-bottom-width: 1px;
            border-top-width: 0px;
            border-right-width: 0px;
            border-left-width: 0px;
        }
    </style>
</head>
<body>
    <h1 class="text-center">Menuplan "{{ $menuplan->title }}"</h1>
    <table style="width: 100%;">
        @foreach ($days->chunk(4) as $chunk)
            <tr>
                @foreach ($chunk as $date => $meals)
                    <td style="width: 25%;vertical-align: top">
                        <div class="p-2">
                            <p class="text-xl border-b text-grey-darkest">{{ $date }}</p>
                            @foreach ($meals as $meal)
                                <a class="block py-2 mt-3">
                                    <p class="text-grey-darker">{{ $meal->title }}</p>
                                    <small class="text-grey">{{ $durationFormatter($meal) }}</small>
                                </a>
                            @endforeach
                        </div>
                    </td>
                @endforeach

                @for($x = $chunk->count(); $x < 4; $x++)
                    <td style="width: 25%"></td>
                @endfor
            </tr>
        @endforeach
    </table>
</body>
</html