<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * {
            font-family: sans-serif;
        }
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
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
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
        .mr-3 {
            margin-right: .75rem;
        }
        .mb-1 {
            margin-bottom: .25rem;
        }
        .block {
            display: block;
        }
        .text-xl {
            font-size: 1.25rem;
        }
        .text-3xl {
            font-size: 1.875rem;
        }
        .text-grey-darkest {
            color: #3d4852;
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
        .w-64 {
            width: 16rem;
        }
        .w-full {
            width: 100%;
        }
        .italic {
            font-style: italic;
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
    <div class="footer text-grey">
        @lang('app.page') <span class="pagenum"></span>
    </div>

    <h1 class="text-center">@lang('app.menuplan') "{{ $menuplan->title }}"</h1>
    <table class="w-full">
        @foreach ($days->chunk(4) as $chunk)
            <tr>
                @foreach ($chunk as $date => $datemeals)
                    <td style="width: 25%;vertical-align: top">
                        <div class="p-2">
                            <p class="text-xl border-b">
                                {{ Carbon\Carbon::parse($date)->formatLocalized('%a, %e %b') }}
                            </p>
                            @foreach ($datemeals as $meal)
                                <a class="block py-2 mt-3">
                                    <p>{{ $meal->title }}</p>
                                    <small>{{ $meal->duration }}</small>
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

    @foreach ($meals as $meal)
        <div class="p-2 mt-3" style="page-break-inside: avoid">
            <p class="text-3xl border-b">{{ $meal->title }}</p>

            <p class="mt-3 italic">
                {{ $meal->date->formatLocalized('%A, %e %b') }}
                &mdash; {{ $meal->duration }}
                &mdash; {{ $meal->absolut_people }} @lang('app.people')
            </p>

            <div class="mt-3">{!! $meal->description !!}</div>

            <table class="mt-3 w-full">
                @foreach ($meal->ingredients->chunk(3) as $chunk)
                    <tr>
                        @foreach ($chunk as $ingredient)
                            <td>{{ $ingredient->quantity_for_shopping_list . $ingredient->item->unit . ' ' . $ingredient->item->title }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach
</body>
</html