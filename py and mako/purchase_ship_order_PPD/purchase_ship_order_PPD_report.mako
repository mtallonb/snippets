<html>
<head>
    <style type="text/css">
        ${css} 
myh2{
	font-size:16px;
	font-weight:bold;
	text-align:left;
	color:#2a58a6;
}   

td{
	vertical-align: top;

}     
    </style>     
</head>

<body>

         
<% setLang(ctx['lang']) %>

	<!-- <div width="100%" style="text-align:right;">
		${helper.embed_logo_by_name('gsell_default_header_image', width=80)|n}
	</div> -->
	
%for n,object in enumerate(objects):
	%if n != 0:
		 <p style="page-break-after:always; clear:both"></p>
 		 <font size="5">&nbsp;</font> <!-- We need to a break line to solve problems with margin top in all pages except the first one -->
	%endif
	
<!-- 	<div style="page-break-inside:avoid;"> -->
	<h1>Bestellung ${object.partner_id.name or ''}</h1>
	</br>
	<!--<h2>Bestellung: ${ _(get_date_with_format(object.delivery_date, '%d.%m.%Y', ctx['lang'])) or ''}</h2>-->
	<h2>${object.name or ''}</h2>
	</br>
	<div class="ship_title">
		${object.multi_purchase_order_id and object.multi_purchase_order_id.cruise_id and object.multi_purchase_order_id.cruise_id.ship_version.ship_id and object.multi_purchase_order_id.cruise_id.ship_version.ship_id.name or _("No Ship")}
	</div>
	</br>
	
	<table width="100%" frame="box" style="border-color:red; cellpadding: 10; vertical-align:text-top;">
				<tr>
					<td colspan="3"><myh2>${_('Lieferort:') }</myh2></td>			
				</tr>
		
				<tr>
					<td ><myh2 style="color:black;">
					${object.delivery_place or ''}
					</myh2>
					</td>
			
					<td />
					<td />
			
				</tr>	
		
				<tr>
					<td colspan="3"><myh2>${_('Lieferdatum:') }</myh2> </td>
																
					
				</tr>
				<tr>
					<td ><myh2 style="color:black;">
					${_(get_date_with_format(object.delivery_date, '%d.%m.%Y %H:%M', ctx['lang']))}
					</myh2>
					</td>
					
					<td>
					
					</td>
					
					<td >
					${}
					</td>
																		
				</tr>
				<tr>
					<td colspan="3">&nbsp; </td>																				
				</tr>
				<tr>
					<td>
					<b>Berechnung von: </b> <br />
					${object.multi_purchase_order_id and object.multi_purchase_order_id.ppd_from_date or ''}
					</td>
					
					<td>
					<b>Berechnung bis:</b> <br />
					${object.multi_purchase_order_id and object.multi_purchase_order_id.ppd_to_date or ''}
					</td>
					
					<td >
					<b>Anzahl Pax:</b> <br />
					${object.multi_purchase_order_id and object.multi_purchase_order_id.passengers or ''}
					</td>
								
				</tr>
				<tr>
					<td colspan="3">&nbsp; </td>																					
				</tr>		
				
				<tr>
					<td class="header" colspan="3"><b>${_('Bermerkungen:') }</b></td>
					
				</tr>		
				<tr>
					<td colspan="3" style="border: 1pt solid #F3F7F7;"> 
					${object.notes or ''}
					</td>															
				</tr>	
		
			</table>

			<table width="100%" frame="box" style="border-color:red; cellpadding: 10; vertical-align:text-top;">
				<tr>					
					<td colspan="3"> <b>Fahrstrecke: </b> <br /> ${object.delivery_place or ''} </td>
				</tr>				
				<tr>
					<td  width="20%" style="background-color:#F3F7F7"><b>Department</b></td>
					<td  width="10%" style="background-color:#F3F7F7"><b>Order PPD</b></td>
					<td  width="10%" style="background-color:#F3F7F7"><b>Summe Order</b></td>		
				</tr>			
				
<% 
total_lines,total_cost = compute_total_lines(object)
%>

	%for total_line in total_lines:
			
				<tr>						
					<td width="20%" style="background-color:#F3F7F7">						
						${total_line.get('cc_name','No name')}						
					</td>
					
					<td width="10%" style="background-color:#F3F7F7">
					%if object.multi_purchase_order_id:
						€ ${'{0:.2f}'.format(total_line.get('sum_cc',0)/(object.multi_purchase_order_id.passengers*get_num_days_PPD(object)))}
					%endif					
					</td>
					<td width="10%" style="background-color:#F3F7F7">
						€ ${'{0:.2f}'.format(total_line.get('sum_cc',0))}
					</td>
				</tr>
	%endfor	#total_line in total_lines:
																																	
				<tr>
					<td colspan="2" style="background-color:#F3F7F7"> <b>Total Order</b></td>						
					<td style="background-color:#F3F7F7"> € ${'{0:.2f}'.format(total_cost)} </td>
				</tr>
			
			
												
			</table>		
			
<!-- 	</div> -->
	</br>

	
<% 
line_groups = get_line_groups(object)
total_cost = 0
cc_name = 'init'
next_cc_name = 'init'
total_cost_cc = 0
%>

% for n,group in enumerate(line_groups):
	<% total_cost_category = 0 %>
	<% total_quantity_group = 0 %>
	<% next_cc_name = line_groups[n+1][0].get('group_name','No name') if n + 1 < len(line_groups) else 'last_name' %>
	<% next_cc_name = line_groups[n+2][0].get('group_name','No name') if n + 2 < len(line_groups) and next_cc_name == 'No name' else next_cc_name %>
	
	%if group and group[0].get('break-page', False):
		<p style="page-break-after:always; clear:both"></p>
		<br>
		<%continue%>
	%endif
	
	%if group and group[0].get('category_name',False):
		%if group[0].get('group_name','No name') != cc_name:
			
			<h2>Faktura: ${_(group[0].get('group_name',''))}</h2>
			<b>Konto Lieferant: ${get_customer_number(object)}</b>
			<% total_cost_cc= 0%>
			<% cc_name = group[0].get('group_name','none')%>
		%endif
		<h3>${_(group[0].get('category_name',''))}</h3>
		<% total_cost_category = 0 %>
	%endif
	
		
	<table style="width:100%; height:1cm;">	
		<tr style="border-bottom:1pt solid #F3F7F7;">
			<td class="header" style="text-align: left;  width:12%">${_('Art. ID')}</td>
			<td class="header" style="text-align: left;  width:30%">${_('Artikel Name')}</td>
			<td class="header" style="text-align: left;  width:15%">${_('Specification')}</td>
			<td class="header" style="text-align: left;  width:15%">${_('Further Info')}</td>
			<td class="header" style="text-align: center;  width:10%">${_('Liefer Verpackung')}</td>
			<td class="header" style="text-align: center;  width:10%">${_('Bestell- Menge')}</td>
			<td class="header" style="text-align: center;  width:10%">${_('EKP/ EH')}</td>
			<td class="header" style="text-align: right;  width:10%;">${_('Wert je EH')}</td>
		</tr>
	%for i,product in enumerate(group):
		<tr>
			<td >
			${product.get('product_identifier', '')}
			</td>			
			<td >
			${product.get('product_name', '')}
			</td>
			<td >
			${product.get('specification', '')}
			</td>
			<td >
			${product.get('further_info', '')}
			</td>
			<td style="text-align: center;">
			${product.get('delivery_uom', '')}
			</td>
			<td style="text-align: center;">
			${'{0:.0f}'.format(product.get('quantity', 0))} ${str(product.get('delivery_uom', 'None ')).split()[0] if str(product.get('delivery_uom', 'None ')).find(' ') > 0 else product.get('delivery_uom', 'None ')}
			</td>
			<td style="text-align: center;">
<!-- 			product_uom -->
			€ ${'{0:.2f}'.format(product.get('price_unit', 0))} ${str(product.get('delivery_uom', 'None ')).split()[0] if str(product.get('delivery_uom', 'None ')).find(' ') > 0 else product.get('delivery_uom', 'None ')}
			</td>
			<td style="text-align: right;">
			€ ${'{0:.2f}'.format(product.get('total_price',0))} ${str(product.get('delivery_uom', 'None ')).split()[0] if str(product.get('delivery_uom', 'None ')).find(' ') > 0 else product.get('delivery_uom', 'None ')}
			</td>
		</tr>
		
		<% total_quantity_group += product.get('quantity', 0) %>		
		<% total_cost_category += product.get('total_price',0) %>
		<% total_cost_cc += product.get('total_price',0) %>
		<% total_cost += product.get('total_price',0) %>
		
	%endfor #i,product in enumerate(group):
	<tr style="border-top:1pt solid #F3F7F7;">
		<td />
		<td />
		<td />	
		<td />		
		
		<td colspan="3" style="text-align: left">
			 ${'Total '+ group[0].get('category_name','')}
			
		</td>	
		<td style="text-align: right">
			
			 € ${'{0:.2f}'.format(total_cost_category)} 
		</td>				
	</tr>
	</table>
	
	% if cc_name != next_cc_name:
		<h2 style="text-align:right">${cc_name}: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; € ${'{0:.2f}'.format(total_cost_cc)} </h2>
	%endif
%endfor #group in line_groups:	
				
	<div style="text-align:right;margin-top:1cm">
		<b>Gesammtwert Bestellung: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; € ${total_cost}</b>
	</div>

%endfor #n,object in enumerate(objects):

<br />

<table style="width:100%; border-color:red;" frame="box">
	<tr>
				
		<td style="text-align:center; font-size:12pt"> <b>Bitte beachten Sie, dass alle Bestellfristen !</b></td>
			
	</tr>	
</table>


</body>
