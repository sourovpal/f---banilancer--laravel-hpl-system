<html>

<head>
    <title>{{ (isset($invoice_print) && $invoice_print) ? 'Invoice' : 'Quotation' }}</title>

    <style>
        body {
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
                            <br />
                            <h1>
                                <center><u>{{ (isset($invoice_print) && $invoice_print) ? 'Invoice' : 'Quotation' }}</u></center>
                            </h1>
                        </td>
                    </tr>
                </table>
            
			<div class="">
               
                <table width=100%>
					<tr>
                        <td width=40% style="padding: 10px;">
                            <table style="font-size: x-small;">
                                <tr>
                                    <td> 
									    {{$externalcompany -> name}}
                                        <hr style="width: 578px;">
                                    </td>
                                </tr>
								<tr>
                                    <td>Delivery To:</td>
                                    <td>
                                       {{ $externalcompany -> add1 }}
									   {{ $externalcompany -> add2 }}
									   {{ $externalcompany -> add3 }}
                                    </td>
                                </tr>i          

								<tr>
                                    <td> 
									    Attn : 
                                    </td>
									<td> 
									    {{$content -> user}}
                                        <hr style="width: 578px;">
                                    </td>
                                </tr>
								<tr>
                                    <td> 
									    Tel : 
                                    </td>
									<td> 
									    {{$content -> tel}}
                                        <hr style="width: 578px;">
                                    </td>
                                </tr>
							 </table>
                        </td>
                        <td width=5%>
                        </td>
                        <td width=40% style="padding: 10px;">
                            <table style="font-size: x-small;">
                                <tr>
                                    <td>Quo No:</td>
                                    <td>
                                        {{$content -> code}}
                                        <hr style="width: 175px">
                                    </td>                                                           
                                </tr>
								
                                <tr>
                                    <td>Date:</td>
                                    <td>
                                        {{$content -> created_at}}
                                        <hr style="width: 175px">
                                    </td>
                                </tr>
                               
                            </table>
                        </td>
                    </tr>
                </table>
              

            </div>
			
                <!-- <table width=100%>
                    <tr>
                        <td width=40%>
                            <br />Order No.: {{ $content -> code }}
                            <br />Order Date: {{ $content -> created_at }}
                        </td>
                        <td width=20%>
                        </td>
                        <td width=40%>
                        </td>
                    </tr>
                </table> -->
                <table width=100%>
                    @if((isset($invoice_print) && $invoice_print))
                    <tr>
                        <td width=40% style="padding: 10px;">
                            <b>Invoice No</b>
                            <br> <b>Your P.O. No</b>
                        </td>
                        <td width=20%>
                        </td>
                        <td width=40% style="">
                            <b>Date:</b>
                            <br> <b>Out Ref.No</b>

                        </td>
                    </tr>
                    @endif
                </table>
                <table width=100%>
                    @if((isset($invoice_print) && $invoice_print))
                    <tr>
                        <td width=40% style="background-color: #EEE; padding: 10px; border-style: solid; border-color: #AAA">
                            <b><u>Bill to</b></u>
                            <br />{{ $externalcompany -> name }}
                            <br />{{ $externalcompany -> add1 }}
                            <br />{{ $externalcompany -> add2 }}
                            <br />{{ $externalcompany -> add3 }}
                            <br />{{ $content -> department }}
                            <br /> <b>Attn:</b><u>{{ $content -> name }}</u>
                            <br /> <b>Tel:</b>{{ $content -> tel }}
                        </td>
                        <td width=20%>
                        </td>
                        <td width=40% style="background-color: #EEE; padding: 10px; border-style: solid; border-color: #AAA">
                            <b><u>Delivery to</b></u>
                            <br />{{ $externalcompany -> name }}
                            <br />{{ $externalcompany -> add1 }}
                            <br />{{ $externalcompany -> add2 }}
                            <br />{{ $externalcompany -> add3 }}
                            <br />{{ $content -> department }}
                            <br />Attn.: {{ $content -> name }}
                            <br />Tel: {{ $content -> tel }}
                        </td>
                    </tr>
                    
                    <tr>
                        <td width=40% style="padding: 10px;">
                            <b><u>Bill to</b></u>
                            <br />{{ $externalcompany -> name }}
                            <br />{{ $externalcompany -> add1 }}
                            <br />{{ $externalcompany -> add2 }}
                            <br />{{ $externalcompany -> add3 }}
                            <br />{{ $content -> department }}
                            <br /> <b>Attn:</b><u>{{ $content -> name }}</u>
                            <br /> <b>Tel:</b>{{ $content -> tel }}
                        </td>
                        <td width=20%>
                        </td>
                        <td width=40% style="">
                            <!-- <b><u>Delivery to</b></u>
                                <br />{{ $externalcompany -> name }}
                                <br />{{ $externalcompany -> add1 }}
                                <br />{{ $externalcompany -> add2 }}
                                <br />{{ $externalcompany -> add3 }}
                                <br />{{ $content -> department }}
                                <br />Attn.: {{ $content -> user }}
                                <br />Tel: {{ $content -> tel }} -->
                            <b>Quo No.: {{ $content -> code }}</b>
                            <br /> <b>Date: {{ $content -> created_at }}</b>
                        </td>
                    </tr>
                    @endif
                </table>

                <div id="items">
                    <table border="0" width=100%>
                        <thead>
                            <tr>
                                <th style="width:30%; height: 30px; background-color: #000; color: #EEE;"> <u>Name</u></th>
                                <th style="width:5%; height: 30px; background-color: #000; color: #EEE;"> <u>Qty</u></th>
                                <th style="width:5%; height: 30px; background-color: #000; color: #EEE;"> <u>Unit</u></th>
                                <th style="width:10%; height: 30px; background-color: #000; color: #EEE;"> <u>Description</u></th>
                                <th style="width:10%; height: 30px; background-color: #000; color: #EEE;"> <u>Remarks</u></th>
                                <th style="width:10%; height: 30px; background-color: #000; color: #EEE;"> <u>Price HK$</u></th>
                            </tr>
                        </thead>
                        <tbody class="useritem_tbody">
                            @php
                            $totalPrice = 0;
                            @endphp
                            @foreach ($items as $item)
                            @php
                            $totalPrice = $totalPrice + $item -> price;
                            @endphp
                            <tr>
                                <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> name }}</td>
                                <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> qty }}</td>
                                <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> unit }}</td>
                                <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> specification }}</td>
                                <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> remarks }}</td>
                                <td style="height: 20px; background-color: #ddd;text-align:center;">{{ $item -> price }}</td>
                            </tr>
                            @endforeach
                            @if((isset($invoice_print) && $invoice_print))
                            <tr>
                                @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                @endphp
                                <td colspan="6" style="height: 20px; background-color: #ddd;"> Say Total H.K Dollars <b>HK$ {{ $f->format($totalPrice) }}</b> Dollars and No Cents Only</td>
                            </tr>
                            @endif

                            @if((isset($invoice_print) && $invoice_print))
                            <tr>
                                <td colspan="2" style="height: 20px; background-color: #ddd;">Prepared By: {{ $content -> user }} </td>
                                <td colspan="2" style="height: 20px; background-color: #ddd;">Total Price</td>
                                <td colspan="2" style="height: 20px; background-color: #ddd;text-align:center;"> <b>HK$ {{ $totalPrice }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="height: 20px; background-color: #ddd;">Payment Terms</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="2" style="height: 20px; background-color: #ddd;text-align:center;">Prepared By: {{ $content -> user }} </td>
                                <td colspan="2" style="height: 20px; background-color: #ddd;text-align:center;">Total Price</td>
                                <td colspan="2" style="height: 20px; background-color: #ddd;text-align:center;"> <b>HK$ {{ $totalPrice }}</b></td>
                            </tr>
                            @endif
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
                    @if((isset($invoice_print) && $invoice_print))

                    <table width=100%>
                        <tr>
                            <td width=40% style="vertical-align: top;">
                                <br />
                                <p style="text-align: justify;">All cheques must be crossed and marked ‘A/C Payee’ and made payable to {{ $internalcompany -> name }}. Please quote our invoice number when making payment. Goods sold and delivered are not subject to return</p>
                                <br />
                            </td>
                            <td width=20%>
                            </td>
                            <td width=40% style="vertical-align: top;">
                                For and on behalf of
                                <br />{{ $internalcompany -> name }}
                                <br> <img src="{{asset('/public/images/logo-chop.jpg' )}}" style="width:100px;">

                            </td>
                        </tr>
                        <tr>
                            <td width=40% style="">
                                <!-- <br />Received & sign by -->
                                <!-- <br />Staff ID &#58; {{ $internalcompany -> intuser }} -->
                            </td>
                            <td width=20%>
                            </td>
                            <td width=40% style="vertical-align: top; border-style: hidden; border-top: solid #000;">
                                <br /> <i>Authorize Signature(s)</i>
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </td>
        </tr>
    </table>
</body>

</html>