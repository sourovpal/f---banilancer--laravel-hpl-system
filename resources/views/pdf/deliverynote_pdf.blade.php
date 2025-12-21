<html>
    <head>
        <title>Delivery note</title>

        <style>
            body{
                width: 100%;
            }
        </style>
    </head>
    <body>
        <table cellpadding="10" width='100%'>
            <tr>
                <td>
                    <table width=100%>
                        <tr>
                            <td>
                                
                                <img src="{{ asset('/public/images/logo/Internal_Company_Logo.' . $internalcompany->logo ) }}" style="width:400px;">
                                <!-- <br />{{ $content -> user }} -->
                                <b>
                                <br />{{ $internalcompany->add1 }}&nbsp;&nbsp;{{$internalcompany->add2}}&nbsp;&nbsp;
                                <br/> {{$internalcompany->add3}}
                                <br />Tel: {{ $internalcompany->tel }} &nbsp;&nbsp;&nbsp; Fax: {{$internalcompany->fax}}
                                <br />
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <br /><h1><center><u>Delivery Note</u></center></h1>
                            </td>
                        </tr>
                    </table>
                    @php
                    function shortenDescription($description, $maxLength = 10) {
                      // Check if the description length is already within the limit
                      if (strlen($description) <= $maxLength) {
                          return $description;
                      } else {
                          // Shorten the description and add ellipsis
                          return substr($description, 0, $maxLength - 3) . "...";
                      }
                    }
                    @endphp
                    <table width=100%>
                        <tr>
                            <td width=40%>
                                @if ($content -> so_no)
                                <br />Order No.: {{ $content -> so_no }}
                                @elseif ($content -> qn_no)
                                    <br />Order No.: {{ $content -> qn_no }}
                                @endif
                                <br />Order Date: {{ $content -> order_date }}
                            </td>
                            <td width=20%>
                            </td>
                            <td width=40%>
                                <br />Delivery No.: {{ $content -> note_no }}
                                <br />Delivery Date: {{ $content -> dn_date }}
                                

                            </td>
                        </tr>
                    </table>
                    
                    <table width=100%>
                        <tr>
                            <td width=40% style="background-color: #EEE; padding: 10px; border-style: solid; border-color: #AAA">
                                <b><u>Bill to</b></u>
                                <br />{{ $externalcompany -> name }}
                                <br />{{ $externalcompany -> add1 }}
                                <br />{{ $externalcompany -> add2 }}
                                <br />{{ $externalcompany -> add3 }}
                                <br />Account Department
                                {{-- <br />Attn.: {{ $content -> user }} --}}
                                <br />Tel: {{ $content -> tel }}
                            </td>
                            <td width=20%>
                            </td>------------------------------------------------------0oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooillllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll
                            <td width=40% style="background-color: #EEE;padding: 10px; border-style: solid; border-color: #AAA">
                                <b><u>Delivery to</b></u>
                                <br />{{ $externalcompany -> name }}
                                <br />{{ $externalcompany -> add1 }}
                                <br />{{ $externalcompany -> add2 }}
                                <br />{{ $externalcompany -> add3 }}
								 <br />{{ $content -> department }} - {{ $content->costcenter_code }}
                                <br />Attn.: {{ $content -> user }}
                                <br />Tel: {{ $content -> tel }}
                            </td>
                        </tr>
                    </table>

                    <div id="items">
                        <table border="0" width="100%" style="table-layout:fixed;">
                            <thead>
                                <tr>
                                    <th style="width:20%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">Item ID</th>
                                    <th style="width:30%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">Name</th>
                                    <th style="width:20%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">Description</th>
                                    <th style="width:5%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">Qty</th>
                                    <th style="width:5%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">Unit</th>
                                    <th style="width:10%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">Packs</th>
                                    <th style="width:10%; height: 30px; background-color: #000; color: #EEE; word-break: break-word;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="useritem_tbody">
                                @foreach ($items as $item)
                                <tr>
                                    <td style="width:20%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">{{ ($item -> code ? $item -> code : $item -> id) }}</td>
                                    <td style="width:30%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">{{ $item -> name }}</td>
                                    <td style="width:20%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">{{ $item -> specification }}</td>
                                    <td style="width:5%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">{{ $item -> qty }}</td>
                                    <td style="width:5%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">{{ $item -> unit }}</td>
                                    <td style="width:10%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">{{ $item -> pack }}</td>
                                    <td style="width:10%; height: 20px; background-color: #ddd;text-align:center; word-break: break-word;">{{ $item -> specification }}</td>
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
                                    <br />{{ $internalcompany -> name }}
                                    {{-- <br /><img src="../blanksign.png"> --}}
                                    <br />
                                    {{-- <img src="{{ asset('/public/images/logo/Internal_Company_Logo.' . $internalcompany->logo ) }}" style="width:100px;"> --}}
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
                                <td width=40% style="vertical-align: top; border-style: hidden; border-top: solid #000;">
                                    <br />Received & sign by
                                    <br />Staff ID &#58; {{ $internalcompany -> intuser }}
                                </td>
                                <td width=20%>
                                </td>
                                <td width=40% style="vertical-align: top; border-style: hidden; border-top: solid #000;">
                                    <br />{{ $internalcompany -> name }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>