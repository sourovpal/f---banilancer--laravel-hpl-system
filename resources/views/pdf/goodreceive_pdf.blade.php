<html>
    <head>
        <title>Good Receive</title>

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
                                <br /><h1><center><u>Good Receive</u></center></h1>
                            </td>
                        </tr>
                    </table>
                    
                    <table width=100%>
                        <tr>
                            <td width=40%>
                                <br />Order No.: {{ $content -> gr_no }}
                                <br />Order Date: {{ $content -> created_at }}
                            </td>
                            <td width=20%>
                            </td>
                            <td width=40%>
                            </td>
                        </tr>
                    </table>
                    
                    <table width=100%>
                        <tr>
                            <td width=40% style="background-color: #EEE; padding: 10px; border-style: solid; border-color: #AAA">
                                <b><u>Bill to</b></u>
                                <br />{{ $internalcompany -> name }}
                                <br />{{ $internalcompany -> add1 }}
                                <br />{{ $internalcompany -> add2 }}
                                <br />{{ $internalcompany -> add3 }}
                                <br />{{ $content -> supplier }}
                                <br />Attn.: {{ $content -> user }}
                           </td>
                            <td width=20%>
                            </td>
                            <td width=40% style="background-color: #EEE; padding: 10px; border-style: solid; border-color: #AAA">
                                <b><u>Delivery to</b></u>
                                <br />{{ $externalcompany -> name }}
                                <br />{{ $externalcompany -> add1 }}
                                <br />{{ $externalcompany -> add2 }}
                                <br />{{ $externalcompany -> add3 }}
                                <br />{{ $content -> supplier }}
                                <br />Attn.: {{ $content -> user }}
                           </td>
                        </tr>
                    </table>

                    <div id="items">
                        <table border="0" width=100%>
                            <thead>
                                <tr>
                                    <th style="width:20%; height: 30px; background-color: #000; color: #EEE;">Item ID</th>
                                    <th style="width:30%; height: 30px; background-color: #000; color: #EEE;">Name</th>
                                    <th style="width:20%; height: 30px; background-color: #000; color: #EEE;">Description</th>
                                    <th style="width:5%; height: 30px; background-color: #000; color: #EEE;">Qty</th>
                                    <th style="width:5%; height: 30px; background-color: #000; color: #EEE;">Unit</th>
                                    <th style="width:10%; height: 30px; background-color: #000; color: #EEE;">Packs</th>
                                    <th style="width:10%; height: 30px; background-color: #000; color: #EEE;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="useritem_tbody">
                                @foreach ($items as $item)
                                <tr>
                                    <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> code }}</td>
                                    <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> name }}</td>
                                    <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> specification }}</td>
                                    <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> qty }}</td>
                                    <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> unit }}</td>
                                    <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> pack }}</td>
                                    <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> specification }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
						<br /><br />
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