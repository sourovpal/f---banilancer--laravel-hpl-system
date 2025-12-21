<html>

<head>
    <title>Delivery note</title>

    <style>
        body {
            width: 100%;
        }
    </style>
</head>

<body>
    @for ($i = 0; count($data) > $i; $i++)
        <table cellpadding="10" width='100%'>
            <tr>
                <td>
                    <table width=100%>
                        <tr>
                            <td>
                                <img src="{{ asset('/public/images/logo/Internal_Company_Logo.' . $data[$i]['internalcompany']['logo']) }}"
                                    style="width:100px;">
                                <br />{{ $data[$i]['internalcompany']['add1'] }}&nbsp;&nbsp;{{ $data[$i]['internalcompany']['add2'] }}&nbsp;&nbsp;{{ $data[$i]['internalcompany']['add3'] }}
                                <br />Tel: {{ $data[$i]['content'] ? $data[$i]['content']->tel : '' }} &nbsp;&nbsp;&nbsp; Fax:
                                {{ $data[$i]['internalcompany']['fax'] }}
                                <br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <br />
                                <h1>
                                    <center><u>Delivery Note</u></center>
                                </h1>
                            </td>
                        </tr>
                    </table>

                    <table width=100%>
                        <tr>
                            <td width=40%>
                                @if ($data[$i]['content'] ? $data[$i]['content']->so_no : '')
                                    <br />Order No.: {{ $data[$i]['content'] ? $data[$i]['content']->so_no : '' }}
                                @elseif ($data[$i]['content'] ? $data[$i]['content']->qn_no : '')
                                    <br />Order No.: {{ $data[$i]['content'] ? $data[$i]['content']->qn_no : '' }}
                                @endif
                                <br />Order Date: {{ $data[$i]['content'] ? $data[$i]['content']->order_date : '' }}
                            </td>
                            <td width=20%>
                            </td>
                            <td width=40%>
                                <br />Delivery No.: {{ $data[$i]['content'] ? $data[$i]['content']->note_no : '' }}
                                <br />Delivery Date: {{ $data[$i]['content'] ? $data[$i]['content']->dn_date : '' }}
                            </td>
                        </tr>
                    </table>

                    <table width=100%>
                        <tr>
                            <td width=40%
                                style="background-color: #EEE; padding: 10px; border-style: solid; border-color: #AAA">
                                <b><u>Bill to</b></u>
                                <br />{{ $data[$i]['externalcompany']['name'] }}
                                <br />{{ $data[$i]['externalcompany']['add1'] }}
                                <br />{{ $data[$i]['externalcompany']['add2'] }}
                                <br />{{ $data[$i]['externalcompany']['add3'] }}
                                <br />{{ $data[$i]['content'] ? $data[$i]['content']->department : '' }}
                                <br />Attn.: {{ $data[$i]['content'] ? $data[$i]['content']->user : '' }}
                                <br />Tel: {{ $data[$i]['content'] ? $data[$i]['content']->tel : '' }}
                            </td>
                            <td width=20%>
                            </td>
                            <td width=40%
                                style="background-color: #EEE;padding: 10px; border-style: solid; border-color: #AAA">
                                <b><u>Delivery to</b></u>
                                <br />{{ $data[$i]['externalcompany']['name'] }}
                                <br />{{ $data[$i]['externalcompany']['add1'] }}
                                <br />{{ $data[$i]['externalcompany']['add2'] }}
                                <br />{{ $data[$i]['externalcompany']['add3'] }}
                                <br />{{ $data[$i]['content'] ? $data[$i]['content']->department : '' }}
                                <br />Attn.: {{ $data[$i]['content'] ? $data[$i]['content']->user : '' }}
                                <br />Tel: {{ $data[$i]['content'] ? $data[$i]['content']->tel : '' }}
                            </td>
                        </tr>
                    </table>

                    <div id="items">
                        <table border="0" width="100%" style="table-layout:fixed;">
                            <thead>
                                <tr>
                                    <th
                                        style="width:20%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">
                                        Item ID</th>
                                    <th
                                        style="width:30%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">
                                        Name</th>
                                    <th
                                        style="width:20%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">
                                        Description</th>
                                    <th
                                        style="width:5%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">
                                        Qty</th>
                                    <th
                                        style="width:5%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">
                                        Unit</th>
                                    <th
                                        style="width:10%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">
                                        Packs</th>
                                    <th
                                        style="width:10%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">
                                        Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="useritem_tbody">
                                @foreach ($data[$i]['items'] as $item)
                                    <tr>
                                        <td
                                            style="width:20%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">
                                            {{ $item->code ? $item->code : $item->id }}</td>
                                        <td
                                            style="width:30%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">
                                            {{ $item->name }}</td>
                                        <td
                                            style="width:20%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">
                                            {{ $item->specification }}</td>
                                        <td
                                            style="width:5%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">
                                            {{ $item->qty }}</td>
                                        <td
                                            style="width:5%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">
                                            {{ $item->unit }}</td>
                                        <td
                                            style="width:10%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">
                                            {{ $item->pack }}</td>
                                        <td
                                            style="width:10%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">
                                            {{ $item->specification }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </tbody>
                        </table>
                        <br>
                        <br>
                        <table width=100%>
                            <tr>
                                <td width=40% style="vertical-align: top;">
                                    <br />Received the above goods in good condition
                                    <br />
                                </td>
                                <td width=20%>
                                </td>
                                <td width=40% style="vertical-align: top;">
                                    <br />{{ $data[$i]['internalcompany']['name'] }}
                                    {{-- <br /><img src="../blanksign.png"> --}}
                                    <br />
                                    {{-- <img src="{{ asset('/public/images/logo/Internal_Company_Logo.' . $data[$i]['internalcompany']['logo'] ) }}" style="width:100px;"> --}}
                                </td>
                            </tr>
                        </table>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <table width=100%>
                            <tr style="margine-top:50%;">
                                <td width=40%
                                    style="vertical-align: top; border-style: hidden; border-top: solid #000;">
                                    <br />Received & sign by
                                    <br />Staff ID &#58; {{ $data[$i]['internalcompany']['intuser'] }}
                                </td>
                                <td width=20%>
                                </td>
                                <td width=40%
                                    style="vertical-align: top; border-style: hidden; border-top: solid #000;">
                                    <br />{{ $data[$i]['internalcompany']['name'] }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    @endfor

</body>

</html>
