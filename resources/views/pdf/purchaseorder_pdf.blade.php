<html>

<head>
    <title>Purchase Order</title>

    <style>
        body {
            width: 100%;
        }

        .center-info {
            /*border: 5px solid;*/
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 10px;

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
                        <br />
                        <h1>
                            <center><u>Purchase Order</u></center>
                        </h1>
                    </td>
                </tr>
            </table>
            <div class="">
                <table width=100%>
                    <tr>
                        <td width=85% style="padding: 10px;">
                            <table style="font-size: x-small;">
                                <tr>
                                    <td style="padding-top: 10px;padding-left: 10px;"><b>TO:</b></td>
                                    <td style="text-align: center;text-transform: uppercase;">
                                        {{$content -> supplier}}
                                        <hr style="width: 578px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td><b></b></td>
                                    <td style="text-align: center">
                                        <div style="text-align: center;  display:inline-block;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; ">{{ $content->englishaddress }}</div>
                                        <hr style="width: 578px;">
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
                <table width=100%>

                    <tr>
                        <td width=40% style="padding: 10px;">
                            <table style="font-size: x-small;">
                                <tr>
                                    <td>Attn:</td>
                                    <td> 
									    {{$content -> contact}}
                                        <hr style="width: 200px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tel:</td>
                                    <td>
                                        {{$content -> telephone1}}
                                        <hr style="width: 200px">
                                    </td>
                                </tr>
								
                                <tr>
                                    <td>PO NO:</td>
                                    <td>
                                        {{ $content -> po_no }}
                                        <hr style="width: 200px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Payment Term:</td>
                                    <td>
                                        <hr style="width: 200px">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width=5%>
                        </td>
                        <td width=40% style="padding: 10px;">
                            <table style="font-size: x-small;">
                                <tr>
                                    <td>Email:</td>
                                    <td>
                                        {{$content -> email}}
                                        <hr style="width: 175px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fax:</td>
                                    <td>
                                        {{$content -> fax}}
                                        <hr style="width: 175px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date:</td>
                                    <td>
                                        @if($content -> created_at > 0)
                                            {{date('d-M-Y', strtotime($content -> created_at)) }}
                                        @endif
                                        <hr style="width: 175px">
                                    </td>
                                </tr>
                                <tr>

                                    <td>Delivery Date:</td>
                                    <td>
                                        
                                        <hr style="width: 175px">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table width=100%>
                    <tr>
                        <td width=85% style="padding: 10px;">
                            <table style="font-size: x-small;">
                                <tr>
                                    <td><b>Delivery To:</b></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        
                                        <hr style="width: 545px;">
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>


            </div>


            <div id="items" style="margin-top: 5px;">
                <table border="0" width=100%>
                    <thead>
                    <tr>
                        <th style="width:15%; height: 30px; background-color: #000; color: #EEE;">Item ID</th>
                        <th style="width:20%; height: 30px; background-color: #000; color: #EEE;">Name</th>
                        <th style="width:15%; height: 30px; background-color: #000; color: #EEE;">Description</th>
                        <th style="width:5%; height: 30px; background-color: #000; color: #EEE;">Pack</th>
 					    <th style="width:5%; height: 30px; background-color: #000; color: #EEE;">Qty</th>
                        <th style="width:10%; height: 30px; background-color: #000; color: #EEE;">Price</th>
                        <th style="width:10%; height: 30px; background-color: #000; color: #EEE;">Total</th>
                    </tr>
                    </thead>
                    <tbody class="useritem_tbody">
                    {{ $totalPrice = 0 }}
                    @foreach ($items as $item)
                        {{ $totalPrice  +=  $item -> qty * $item -> price}}
                        <tr>
                            <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> code }}</td>
                            <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> name }}</td>
                            <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> specification }}</td>
                            <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> pack }}</td>
							<td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> qty }}</td>
                            <td style="height: 20px; background-color: #ddd;text-align:center;">$ {{ $item -> price }}</td>
                            <td style="height: 20px; background-color: #ddd;text-align:center;">$ {{ number_format((float)($item -> qty * $item -> price), 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" style="width:10%; height: 10px;">Remarks {{ $item -> remarks }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td colspan="2" style="width:10%; height: 10px; background-color: #ddd; text-align:center;">Total Price <span style="color: red;"> HKD</span></td>
                        <td style="width:10%; height: 10px; background-color: #ddd; text-align:center;">$ {{$totalPrice }}</td>
                    </tr>
                    </tbody>
                </table>

                <table width=100%>
                    <tr>
                        <td width=40% style="vertical-align: top;">
                            <br /><p>Remarks: {{ $content->remarks }}</p>
                            <br />
                        </td>
                        <td width=20%>
                        </td>
                        <td width=40% style="vertical-align: top;">
                            <img src="{{asset('/public/images/logo-chop.jpg' )}}" style="width:100px;">
                        </td>
                    </tr>
                </table>
            <!--    <tr>
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
            </table> -->

            </div>
        </td>
    </tr>
</table>
</body>

</html>