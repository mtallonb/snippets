<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<head>
		<style type="text/css">
		@font-face {
  font-family: "Arial";
  advancedAntiAliasing: true;
  unicode-range: U+0021-U+007B;
  }
  .bold{
  font-family:"Arial";
  }

  @font-face {
  font-family: "Arial";
  advancedAntiAliasing: true;
  unicode-range: U+0021-U+007B;
  }
  .normal{
  font-family:"Arial";
  }
  
			${css}
		</style>
	</head>

<body style="border:0; margin: 0;">
	% for general_report in objects:
			<% 
			duration_period = get_duration(general_report.get_init_time(), general_report.get_end_time()) 
			pax_period = general_report.get_total_passengers_by_period() or 0
			pax_season = general_report.get_total_passengers_by_season() or 0	
			avg_pax_info = general_report.get_pax_info()	
			%>
			<div id="container" style="width:28cm;">
			&nbsp;
			<h1>${_('Management Report')} - ${general_report.ship_id.name or ""}</h1>				
					
			<div style="width:14cm;float:left;"> 		
				<table width="95%">
					<tr>
						<td width="40%"><b>${_('Report Number:')}</b> ${general_report.ship_id.short_name or ""}_${general_report.id}</td>
						<td width="20%"><b>${_('Period From:')}</b> </td>
						<td width="20%" class="left"> ${general_report.get_init_time() or ""} </td>
						<td width="20%"><b>${_('Duration:')}</b> </td>
						<td width="10%" class="left"> ${duration_period} ${_('Days')} </td>
					</tr>
					<tr>
						<td width="40%"><b>${_('Period / Month:')}</b> ${get_month(general_report.month)}</td>
						<td width="20%"><b>${_('Period Until:')}</b> </td>
						<td width="20%" class="left"> ${general_report.get_end_time() or ""} </td>
						<td width="20%"><b>${_('Pax Period:')}</b></td>
						<td width="10%" class="left"> ${general_report.write_coma(pax_period)} </td>
					</tr>
				</table>
				
				<br>

				<h2>${_('Included Cruises')}</h2> <hr/>	
				<table style="width:95%; margin-bottom: 1cm" >
					<tr>
						<td width="30%" />
						<td width="25%"><b>${_('From:')}</b> </td>
						<td width="25%"><b>${_('To:')}</b> </td>
						<td width="20%"><b>${_('Pax:')}</b> </td>
					</tr>
					% for cruise in general_report.cruise_ids:
					<tr>
						<td>${cruise.name or ""}</td>
						<td>${cruise.start_date or ""}</td>
						<td>${cruise.end_date or ""}</td>
						<td>${'{0:.0f}'.format(cruise.avg_pax) or "0.00"}</td>
					</tr>
					%endfor
				</table>
				
				<h2>${_('Food Cost')}</h2> <hr/>	
				<table style="width:95%; margin-bottom: 1cm" >
					<tr>
						<td width="20%"/>
						<td width="15%"/> 
						<td width="20%"/>
						<td width="15%"/>
						<td width="15%"/>
						<td width="15%"/> 
					</tr>
					<% 
					CC = [4250] 
					initial = general_report.get_cost_inventory(CC, 'period','initial')
					cost_period = general_report.get_cost(CC, 'period')
					budget_period = general_report.get_budget('10', 'period')
					end = general_report.get_cost_inventory(CC, 'period','end')
					%>
					<tr>
						<td colspan="4"><b>${_('Food Cost')}</b></td>
						<td class="right"><b>${_('Budget:')}</b>  </td>
						<td class="right"><b>${_('Difference:')}</b></td>
					</tr>
					<tr>
						<td>Opening ${_('Inventory:')}</td>
						<td class="gray right">€ ${format_number(initial)} </td>
						<td><b>&nbsp;&nbsp;${_('Food Cost:')}</b></td>
						<td class="gray right">€ ${format_number(cost_period)} </td>
						<td class="gray right">€ ${format_number(budget_period)}</td>
						<td class="gray right">
							<% fc_budg = cost_period - budget_period %>
							%if fc_budg > 0:
							<font color="red">€ ${format_number(fc_budg)}</font>
							%else:
							<font color="green">€ ${format_number(fc_budg)}</font>
							%endif
						</td>
					</tr>
					<tr>	
						<td>${_('Closing Inventory:')}</td>
						<td class="gray right">€ ${format_number(end)} </td>
						
						
						<td><b>&nbsp;&nbsp;${_('Food Cost Season:')}</b></td>
						<td class="gray right">€ ${format_number(general_report.get_cost(CC, 'season'))}</td>
						<td class="gray right">€ ${format_number(general_report.get_budget('10', 'season'))}</td>
						<td class="gray right">
						<% fc_budg_sais = general_report.get_cost(CC, 'season') - general_report.get_budget('10', 'season')%>
							%if fc_budg_sais > 0:
							<font color="red">€ ${format_number(fc_budg_sais)}</font>
							%else:
							<font color="green">€ ${format_number(fc_budg_sais)}</font>
							%endif
						</td>
					</tr>
					<tr>
						<td>${_('Purchase:')}</td>
						<td class="gray right">€ ${format_number(cost_period * pax_period + end - initial)} </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>						
						<td>${_('Consumption:')}</td>
						<td class="gray right">€ ${format_number(cost_period * pax_period)} </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					
					<% 
					CC = [4255] 
					initial = general_report.get_cost_inventory(CC, 'period','initial')
					end = general_report.get_cost_inventory(CC, 'period','end')
					%>
					<tr class="spaceTop">
						<td colspan="6"><b>${_('Expenses Vegetable Boxes')}</b></td>
					</tr>
					<tr>
						<td>${_('Opening Inventory:')}</td>
						<td class="right">€ ${format_number(initial)} &nbsp; &nbsp;</td>
						<td colspan="2"></td>
						<td class="gray" colspan="2">${_('The total amount of kitchen')}</td>
					</tr>
					<tr>
						<td>${_('Closing Inventory:')}</td>
						<td class="right">€ ${format_number(end)} &nbsp; &nbsp;</td>
						<td class="right"><b>${_('Stock On Hand:')}</></td>
						<td class="left">&nbsp; &nbsp; € ${format_number(initial + (general_report.get_ek_wt(CC, 'period')) - (end))} </td>
						<td class="gray" colspan="2">${_('boxes may not be higher than 400€.')}</td>
					</tr>
				</table>
				
				<h2>${_('On Board Revenue')}</h2> <hr/>	
				
				<table style="width:95%; margin-bottom: 1cm" >
					<tr>
						<td width="25%"/>
						<td width="15%"/>
						<td width="15%"/>
						<td width="15%"/>
						<td width="15%"/>
						<td width="15%"/> 
					</tr>
					<tr class="dashed">
						<td/>
						<td class="right"><b>${_('OBR')}</b> </td>
						<td class="right"><b>${_('Budget')}</b></td>
						<td class="right"><b>${_('Diff. B.')}</b></td>
						<td class="right"><b>${_('Season')}</b></td>
						<td class="right"><b>${_('Diff. B. S.')}</b></td>
					</tr>
					
					<% 
						total_groups =[0, 0, 0, 0, 0]
						values = {}
					 %>
					% for cruise_revenue_group in general_report.partial_cost_calculation_cr_period_ids:
						<%
						total_group =[0, 0, 0]
						%>
						% for (obr_period, budget_period, obr_season) in general_report.get_cruise_venues(cruise_revenue_group.gp_cruise_revenue_item_id.id) :
							<%
							total_group[0] += obr_period
							total_group[1] = budget_period
							total_group[2] += obr_season
							%>								
							
						%endfor
						
					
					
						<%
						name = cruise_revenue_group.gp_cruise_revenue_item_id.name.split(' ')[0]
						total_group[0] = total_group[0] / pax_period or 0
						total_group[2] = total_group[2] / pax_season or 0
						partial_value01 = total_group[0] - total_group[1]
						partial_value02 = total_group[2] - total_group[1]
						values[name] = [total_group[0], total_group[1], total_group[2], partial_value01, partial_value02]
						%>
					%endfor
					% for name in ['Bar', 'Restaurant', 'Minibar']:
						%if name not in values: 
							<%
								total_group = [0, 0, 0]
								partial_value01 = 0
								partial_value02 = 0
							%>
						
						% else:
							<%
								total_group = [values[name][0], values[name][1], values[name][2]]
								partial_value01 = values[name][3]
								partial_value02 = values[name][4]
							%>
						%endif
						<tr class="dashed">
							<td >
								<b>${_(name)}</b>
							</td>
							<td class="right">
								€ ${format_number(total_group[0])}
							</td>
							<td class="right">
								€ ${format_number(total_group[1])}
							</td>
							<td class="right">
								%if (partial_value01) > 0:
									<font color="green">€ ${format_number(partial_value01)}</font>
								%endif
								%if (partial_value01) == 0:
									€ ${format_number(partial_value01)}
								%endif
								%if (partial_value01) < 0:
									<font color="red">€ ${format_number(partial_value01)}</font>
								%endif
							</td>
							<td class="right">
								€ ${format_number(total_group[2])}
							</td>
							<td class="right">
								%if (partial_value02) > 0:
									<font color="green">€ ${format_number(partial_value02)}</font>
								%endif
								%if (partial_value02) == 0:
									€ ${format_number(partial_value02)}
								%endif
								%if (partial_value02) < 0:
									<font color="red">€ ${format_number(partial_value02)}</font>
								%endif
							</td>
						</tr>										
						<% 
						total_groups [0] += total_group[0]
						total_groups [1] += total_group[1]
						total_groups [2] += partial_value01
						total_groups [3] += total_group[2]
						total_groups [4] += partial_value02
						%>
					%endfor
						<tr class="total gray">
							<td class="left">
								<b>${_('Total F&B:')}</b>
							</td>
							<td class="right">
									€ ${format_number(total_groups[0])}
							</td>
							<td class="right">
									€ ${format_number(total_groups[1])}
							</td>
							<td class="green right">
									%if total_groups[2] > 0:
										<font color="green">€ ${format_number(total_groups[2])}</font>
									%endif
									%if total_groups[2] == 0:
										€ ${format_number(total_groups[2])}
									%endif
									%if total_groups[2] < 0:
										<font color="red">€ ${format_number(total_groups[2])}</font>
									%endif
							</td>
							<td class="right">
									€ ${format_number(total_groups[3])}
							</td>
							<td class="green right">
									%if total_groups[4] > 0:
										<font color="green">€ ${format_number(total_groups[4])}</font>
									%endif
									%if total_groups[4] == 0:
										€ ${format_number(total_groups[4])}
									%endif
									%if total_groups[4] < 0:
										<font color="red">€ ${format_number(total_groups[4])}</font>
									%endif
							</td>
						</tr>	
					% for name in ['Shop', 'Laundry', 'Telefon', 'Beauty']:
						%if name not in values:
								<% 
								continue
								%>
						%endif
						<%
							
							total_group = [values[name][0], values[name][1], values[name][2]]
							partial_value01 = values[name][3]
							partial_value02 = values[name][4]
						%>
						<tr class="dashed">
							<td >
								<b>${_(name)}</b>
							</td>
							<td class="right">
								€ ${format_number(total_group[0])}
							</td>
							<td class="right">
								€ ${format_number(total_group[1])}
							</td>
							<td class="right">
								%if (partial_value01) > 0:
									<font color="green">€ ${format_number(partial_value01)}</font>
								%endif
								%if (partial_value01) == 0:
									€ ${format_number(partial_value01)}
								%endif
								%if (partial_value01) < 0:
									<font color="red">€ ${format_number(partial_value01)}</font>
								%endif
							</td>
							<td class="right">
								€ ${format_number(total_group[2])}
							</td>
							<td class="right">
								%if (partial_value02) > 0:
									<font color="green">€ ${format_number(partial_value02)}</font>
								%endif
								%if (partial_value02) == 0:
									€ ${format_number(partial_value02)}
								%endif
								%if (partial_value02) < 0:
									<font color="red">€ ${format_number(partial_value02)}</font>
								%endif
							</td>
						</tr>										
						<% 
						total_groups [0] += total_group[0]
						total_groups [1] += total_group[1]
						total_groups [2] += partial_value01
						total_groups [3] += total_group[2]
						total_groups [4] += partial_value02
						%>
					%endfor
						<tr class="total bold">
							<td>
								<b>${_('Total')}</b>
							</td>
							<td class="right">
									€ ${format_number(total_groups[0])}
							</td>
							<td class="right">
									€ ${format_number(total_groups[1])}
							</td>
							<td class="green right">
									%if total_groups[2] > 0:
										<font color="green">€ ${format_number(total_groups[2])}</font>
									%endif
									%if total_groups[2] == 0:
										€ ${format_number(total_groups[2])}
									%endif
									%if total_groups[2] < 0:
										<font color="red">€ ${format_number(total_groups[2])}</font>
									%endif
							</td>
							<td class="right">
									€ ${format_number(total_groups[3])}
							</td>
							<td class="green right">
									%if total_groups[4] > 0:
										<font color="green">€ ${format_number(total_groups[4])}</font>
									%endif
									%if total_groups[4] == 0:
										€ ${format_number(total_groups[4])}
									%endif
									%if total_groups[4] < 0:
										<font color="red">€ ${format_number(total_groups[4])}</font>
									%endif
							</td>
						</tr>
												
				</table>
										
			</div>
			
			<!-- BEGIN PAGE ON THE RIGHT				 -->
			<div style="width:14cm;float:left;">		

				<table  width="95%">
					<tr>
						<td width="30%"/>
						<td width="20%"><b>${_('Occupancy Pax:')}</b> </td>
						<td width="10%" class="left"> ${format_number(avg_pax_info['avg_pax_period'])} </td>
						<td width="25%"><b>&nbsp; ${_('Occupancy Season:')}</b>  </td>
						<td width="10%" class="left"> ${format_number(avg_pax_info['avg_pax_season'])} </td>
					</tr>
					<tr>
						<td width="30%"><b>${_('Pax Season:')}</b> ${general_report.write_coma(pax_season)}  </td>
						<td width="20%"><b>${_('Occupancy %')}: </b></td>
						<td width="10%" class="left">${format_number(avg_pax_info['percentage_period'])} %</td>
						<td width="25%"><b>&nbsp; ${_('Occupancy %')}: </b> </td>
						<td width="10%" class="left"> ${format_number(avg_pax_info['percentage_season'])} %</td>
					</tr>
				</table>
				
				%if general_report.beverage_cost == 'rate_of_return':
				<h2>${_('Yield')}</h2> <hr/>
				%endif
				
				%if general_report.beverage_cost == 'both':
				<h2>${_('Yield / Beverage Cost')}</h2> <hr/>
				%endif
				
				%if general_report.beverage_cost == 'cost':
				<h2>${_('Beverage Cost')}</h2> <hr/>																							
				%endif
				
				
				<table style="width:95%; margin-bottom: 1cm" >
					<tr>
						<td width="25%"/>
						<td width="12.5%"/>
						<td width="12.5%"/>
						<td width="12.5%"/>
						<td width="12.5%"/>
						<td width="12.5%"/> 
					</tr>
					<tr>
						<td/>
						%if general_report.beverage_cost in ['both']:
							<td class="right"><b>${_('Yield')}/${_('Cost PPD')}</b> </td>
						%endif
						%if general_report.beverage_cost in ['rate_of_return']:
							<td class="right"><b>${_('Yield')}</b> </td>
						%endif
						%if general_report.beverage_cost in ['cost']:
							<td class="right"><b>${_('Cost PPD')}</b> </td>	
						%endif					
						<td class="right"><b>${_('Budget')}</b></td>
						<td class="right"><b>${_('Diff. B.')}</b></td>
						<td class="right"> <b>${_('Cost S. PPD')}</b></td>
						<td class="right"><b>${_('Diff. S.')}</b></td>
					</tr>
					
					<%
					  (yield_values, total_values) = general_report.yield_total_value() 
					%>
					<%
					  	GROUP = ['Mineral', 'Beer', 'Wine', 'Spirit', 'Minibar'] 
					%>	
						
					%if general_report.beverage_cost in ['rate_of_return']:
						<%
					  	GROUP = ['Mineral', 'Beer', 'Wine', 'Spirit']
						%>	
					%endif
					
					% for key in GROUP:
						<tr class="dashed">
							<td >
								${_(key)}
							</td>
						% if general_report.beverage_cost == 'rate_of_return':
							<% 
							   aux_yield_values = yield_values[key][0]
							   total_groups =[0, 0, 0, 0, 0, 0]
							%>
						% endif
						% if general_report.beverage_cost == 'cost':
							<% 
							   aux_yield_values = yield_values[key][1]
							%>
						% endif
						% if general_report.beverage_cost == 'both':
							<% 
							   aux_yield_values = yield_values[key][2]
							%>
						% endif
						 
						% for elem in aux_yield_values:
							<td class="right">
								${elem.decode('utf-8')}

							</td>
						% endfor
						</tr>

					% endfor
					% if general_report.beverage_cost != 'rate_of_return':
						<tr class="total">

						<td >
							<b>Total</b>
                        </td>
                        % for value in total_values:
                        	<td  class="right">
								
								<b>${value.decode('utf-8')}
                        % endfor
                        </tr>
					%endif                        

					
				</table>
				
				<h2>${_('Consumption PPD Nonfood Material etc.')}</h2> <hr/>	
					
				<table style="width:95%; margin-bottom: 1cm" >
					<tr>
						<td width="37.5%"/>
						<td width="12.5%"/> 
						<td width="12.5%"/>
						<td width="12.5%"/>
						<td width="12.5%"/>
						<td width="12.5%"/>
					</tr>
					<tr>
						<td></td>
						<td class="right"><b>Cost PPD</b> </td>
						<td class="right"><b>Budget</b></td>
						<td class="right"><b>Diff. B.</b></td>
						<td class="right"><b>Cost S. PPD</b></td>
						<td class="right"><b>Diff. S.</b></td>
					</tr>
					
					<% 
					total_groups =[0, 0, 0, 0, 0] 
					total_group =[0, 0, 0, 0, 0]			
					%>											
					
					% for (Title, CC, budget_code) in [ ('Cleaning Goods',[6450],'510'),('Cleaning Material', [6430], '520'),]:
							<%
							cost_period = general_report.get_cost(CC, 'period')
							cost_season = general_report.get_cost(CC, 'season')
							budget_period = general_report.get_budget(budget_code, 'period') 
							budget_season = general_report.get_budget(budget_code, 'season')
							total_group [0] += cost_period
							total_group [1] += budget_period
							total_group [2] += cost_period-budget_period
							total_group [3] += cost_season
							total_group [4] += cost_season-budget_period
							%>
						<tr class="dashed">
							
							<td>
								 ${_(Title)}
							</td>
							<td class="right">
								€ ${format_number(cost_period)}
							</td>
							<td class="right">
								€ ${format_number(budget_period)}
							</td>
							<td class="right">
								%if (cost_period - budget_period) > 0:
									<font color="red">€ ${format_number(cost_period - budget_period)}</font>
								%endif
								%if (cost_period - budget_period) == 0:
									€ ${format_number(cost_period - budget_period)}
								%endif
								
								%if (cost_period - budget_period) < 0:
									<font color="green">€ ${format_number(cost_period - budget_period)}</font>
								%endif
							</td>
							<td class="right">
								€ ${format_number(cost_season)}
							</td>
							<td class="right">
								%if (cost_season - budget_season) > 0:
									<font color="red">€ ${format_number(cost_season - budget_season)}</font>
								%endif
								%if (cost_season - budget_season) == 0:
									€ ${format_number(cost_season - budget_season)}
								%endif
								
								%if (cost_season - budget_season) < 0:
									<font color="green">€ ${format_number(cost_season - budget_season)}</font>
								%endif
							</td>
						</tr>
						
					%endfor
										
							<tr class="total gray">
								<td class="left">
									<b>Total &nbsp;</b>
								</td>
								<td class="right">
									€ ${format_number(total_group[0])}
								</td>
								<td class="right">
									€ ${format_number(total_group[1])}
								</td>
								<td class="right">
									€ ${format_number(total_group[2])}
								</td>
								<td class="right">
									€ ${format_number(total_group[3])}
								</td>
								<td class="right">
									€ ${format_number(total_group[4])}
								</td>
							</tr>	
							<tr>
								<td colspan=6>&nbsp;</td>
							</tr>
					<% 
					total_groups [0] += total_group[0]
					total_groups [1] += total_group[1]
					total_groups [2] += total_group[2]
					total_groups [3] += total_group[3]
					total_groups [4] += total_group[4]
					
					total_group =[0, 0, 0, 0, 0]
					%>
					
					<tr>
						<td colspan="6"> </td>
					</tr>
					
					% for (Title, CC, budget_code) in [ ('Nonfood Material Housekeeping',[5420],'530'),('Nonfood Material Generally', [6400],'540'),('Nonfood Material Restaurant', [6410],'550'),('Office Material', [6500],'560')]:
							<%
							cost_period = general_report.get_cost(CC, 'period')
							cost_season = general_report.get_cost(CC, 'season')
							budget_period = general_report.get_budget(budget_code, 'period') 
							budget_season = general_report.get_budget(budget_code, 'season')
							total_group [0] += cost_period
							total_group [1] += budget_period
							total_group [2] += cost_period-budget_period
							total_group [3] += cost_season
							total_group [4] += cost_season-budget_period
							%>
						<tr class="dashed">
							
							<td >
								 ${_(Title)}
							</td>
							<td class="right">
								€ ${format_number(cost_period)}
							</td>
							<td class="right">
								€ ${format_number(budget_period)}
							</td>
							<td class="right"> 
								%if (cost_period - budget_period) > 0:
									<font color="red">€ ${format_number(cost_period - budget_period)}</font>
								%endif
								%if (cost_period - budget_period) == 0:
									€ ${format_number(cost_period - budget_period)}
								%endif
								
								%if (cost_period - budget_period) < 0:
									<font color="green">€ ${format_number(cost_period - budget_period)}</font>
								%endif
							</td>
							<td class="right">
								€ ${format_number(cost_season)}
							</td>
							<td class="right">
								%if (cost_season - budget_season) > 0:
									<font color="red">€ ${format_number(cost_season - budget_season)}</font>
								%endif
								%if (cost_season - budget_season) == 0:
									€ ${format_number(cost_season - budget_season)}
								%endif
								
								%if (cost_season - budget_season) < 0:
									<font color="green">€ ${format_number(cost_season - budget_season)}</font>
								%endif
							</td>
						</tr>
																
					%endfor
										
						<tr class="total gray">
							<td class="left">
								<b>Total &nbsp;</b>
							</td>
							<td class="right">
								€ ${format_number(total_group[0])}
							</td>
							<td class="right">
								€ ${format_number(total_group[1])}
							</td>
							<td class="right">
								€ ${format_number(total_group[2])}
							</td>
							<td class="right">
								€ ${format_number(total_group[3])}
							</td>
							<td class="right">
								€ ${format_number(total_group[4])}
							</td>
						</tr>	
					<% 
					total_groups [0] += total_group[0]
					total_groups [1] += total_group[1]
					total_groups [2] += total_group[2]
					total_groups [3] += total_group[3]
					total_groups [4] += total_group[4]
					
					total_group =[0, 0, 0, 0, 0]
					%>
						<tr class="total black">
							<td class="left">
								<b>Total &nbsp;</b>
							</td>
							<td class="right">
								€ ${format_number(total_groups[0])}
							</td>
							<td class="right">
								€ ${format_number(total_groups[1])}
							</td>
							<td class="right">
								€ ${format_number(total_groups[2])}
							</td>
							<td class="right">
								€ ${format_number(total_groups[3])}
							</td>
							<td class="right">
								€ ${format_number(total_groups[4])}
							</td>
						</tr>
										

				</table>
				
				<h2>${_('Other Expenses')}</h2> <hr/>	
					
				<table style="width:95%; margin-bottom: 1cm" >
					<tr>
						<td width="30%"/>
						<td width="15%"/> 
						<td width="20%"/>
						<td width="20%"/>
						<td width="15%"/>											
					</tr>
					<tr>
						<td></td>
						<td  class="right"><b>${_('Purchase')}</b> </td>
						<td  class="right"><b>${_('P. Season')}</b></td>
						<td  class="right"><b>${_('Budget Season')}</b></td>
						<td  class="right"><b>${_('Diff. Real')}</b></td>					
					</tr>
					<% 
					aux_total = [0, 0, 0, 0]
					values = general_report.get_transport_information()
					%>
					% for name in values :
						<%
						ek_period = values[name][0]
						ek_season = values[name][1]
						budget = values[name][2]
						%>
						<tr class="dashed">
							<td>${name}</td>
							<td  class="right">€ ${format_number(ek_period)}</td>
							<td  class="right">€ ${format_number(ek_season)}</td>
							<td  class="right">€ ${format_number(budget)}</td>
							<td  class="right">€ ${format_number(budget - ek_season )}</td>					
						</tr>
						<% 
						aux_total[0] += ek_period		
						aux_total[1] += ek_season		
						aux_total[2] += budget		
						aux_total[3] += (-ek_season + budget)
						%>
					% endfor					
					<tr class="total bold">
						<td>
							<b>Total</b>
						</td>
						<td class="right">
							€ ${format_number(aux_total[0])}
						</td>
						<td class="right">
							€ ${format_number(aux_total[1])}
						</td>
						<td class="right">
							€ ${format_number(aux_total[2])}
						</td>
						<td class="right">
							€ ${format_number(aux_total[3])}
						</td>
					</tr>							
						
				</table>				
				<p> ${_('PPD = per Pax & Day')} </p>
			</div>
			
			<div style="clear:both"/>
										
			</div> <!-- From container -->
		
		
	%endfor
</body>
</html>
