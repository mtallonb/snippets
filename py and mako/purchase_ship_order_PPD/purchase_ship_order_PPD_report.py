# -*- encoding: utf-8 -*-
##############################################################################
#
#    OpenERP, Open Source Management Solution
#    Copyright (C) 2004-2009 Tiny SPRL (<http://tiny.be>). All Rights Reserved
#    $Id$
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
##############################################################################

from report import report_sxw
from report_webkit import report_sxw_ext

import time
from datetime import datetime
from tools import DEFAULT_SERVER_DATETIME_FORMAT
import pytz

# Sorts, groups and prints lists
import operator
import itertools

from bt_helper.tools import bt_dates
from bt_helper.tools import bt_reports


class purchase_ship_order_PPD_report(report_sxw.rml_parse):
    TIMEZONE = 'Europe/Zurich'
    _LINES_PER_PAGE = 30
    _LINES_FIRST_PAGE = 15

    def __init__(self, cr, uid, name, context):
        super(purchase_ship_order_PPD_report, self).__init__(cr, uid, name, context=context)

        self.localcontext.update({
            'time': time,
            'ctx': context,
            'get_line_groups': self.get_line_groups,
            'get_current_date': self.get_current_date,
            'get_selection_value': self.get_selection_value,
            'get_date_with_format': self.get_date_with_format,
            'get_footer': self.get_footer,
            'get_num_days_PPD': self.get_num_days_PPD,
            'compute_total_lines': self.compute_total_lines,
            'get_customer_number': self.get_customer_number,
        })

    def get_selection_value(self, field_name, field_val):
        result = ""
        if field_name and field_val:
            result = dict(self.pool.get('stock.inventory').fields_get(self.cr, self.uid)[field_name]['selection'])[field_val]
        return result

    def get_date_with_format(self, str_date_time, format='%d.%m.%Y', language=False):
        return bt_dates.strftime_str(str_date_time, format, language, self.TIMEZONE)

    def compute_total_lines(self, purchase_order):
        """
        Return a list of lines with the following information:
        token = { 'cc_name': __,
                  'sum_cc': __,
            }
        where:
             cc_name: is the cost center name.
             sum_cc : The sum of all product of this cost center.
        restrictions:
            cc_name must appear one time in the list.
        """
        order = []
        partial_result = {}
        total_cost = 0
        lines_group = self.get_line_groups(purchase_order)
        # In lines_group we obtain the
        for group_line in lines_group:
            for item in group_line:
                # Cost center name
                cc_name = item.get('group_name')
                if cc_name not in order:
                    order.append(cc_name)
                if cc_name not in partial_result:
                    partial_result[cc_name] = {'cc_name': cc_name,
                                               'sum_cc': 0}
                total_price = item.get('total_price', 0)
                partial_result[cc_name]['sum_cc'] += total_price
                total_cost += total_price
        result = []
        for cost_center in order:
            token = partial_result[cost_center]
            if token.get('sum_cc') > 0:
                result.append(token)
        return result, total_cost

    def get_line_groups(self, purchase_order):
        lines = []

        for order_line in purchase_order.order_line:
            delivery_uom = ''
            product_code = ''
            for supplier_info in order_line.product_id.seller_ids:
                if supplier_info.name.id == purchase_order.partner_id.id:
                    delivery_uom = supplier_info.delivery_uom_id.name
                    product_code = supplier_info.product_code
                    break

            line_data = {'product_name': order_line.product_id and order_line.product_id.name or 'No name',
                         'product_identifier': order_line.product_id and order_line.product_id.identifier or '',
                         'product_code': product_code,
                         'product_serien': order_line.product_id and order_line.product_id.serie_id and order_line.product_id.serie_id.name or '',
                         'group_id': order_line.product_id and order_line.product_id.categ_id and order_line.product_id.categ_id.cost_center_id.id or 0,
                         'group_name': order_line.product_id and order_line.product_id.categ_id and order_line.product_id.categ_id.cost_center_id.name or 'No group',
                         'category_id': order_line.product_id and order_line.product_id.categ_id.id or 0,
                         'category_name': order_line.product_id and order_line.product_id.categ_id.name or 'No group',
                         'group_sequence': order_line.product_id and order_line.product_id.categ_id and order_line.product_id.categ_id.sequence or False,
                         'quantity': order_line.product_qty,
                         'product_uom': order_line.product_uom and order_line.product_uom.name and order_line.product_uom.name.encode('utf-8') or "None ",
                         'delivery_uom': delivery_uom,
                         'cost_price': order_line.product_id and order_line.product_id.product_tmpl_id and order_line.product_id.product_tmpl_id.standard_price or 0,
                         'price_unit': order_line.price_unit,
                         'specification': order_line.product_id and order_line.product_id.product_tmpl_id and order_line.product_id.product_tmpl_id.specification or '',
                         'further_info': order_line.more_info or '',
                         'total_price': (order_line.price_unit * order_line.product_qty
                                         if order_line.price_unit and order_line.product_qty else 0)}
            lines.append(line_data)

        # Sorts and groups lines by group_id and group_name
        lines.sort(key=operator.itemgetter('group_sequence'))

        list_grouped = []
        for key, items in itertools.groupby(lines, operator.itemgetter('group_id', 'category_id')):
            list_grouped.append(list(items))

        return bt_reports.separate_groups(self._LINES_FIRST_PAGE, self._LINES_PER_PAGE, list_grouped, 1, 1)

    def get_current_date(self):
        return datetime.now(pytz.timezone(self.TIMEZONE)).strftime(DEFAULT_SERVER_DATETIME_FORMAT)

    def get_num_days_PPD(self, purchase_order):
        if purchase_order.multi_purchase_order_id:
            return bt_dates.get_difference_in_days(purchase_order.multi_purchase_order_id.ppd_from_date, purchase_order.multi_purchase_order_id.ppd_to_date)
        return 1

    def get_customer_number(self, purchase_order):

        if purchase_order.location_id:
            for customer in purchase_order.location_id.customer_number_ids:
                if customer.supplier_id == purchase_order.partner_id:
                    return customer.name
        return False

    def get_footer(self):

        return """<tr >

                <td style="vertical-align: top; text-align: left; font-size: 8; font-family: Verdana; color:#2a58a6;" width="4%"><span class="page"></span>/<span class="topage"></span></td>
                <td style="vertical-align: top; text-align: right; font-size: 8; font-family: Verdana; color:#2a58a6;" width="4%">Gedruckt am: """ + self.get_current_date() + """ </td>

            </tr>
            """

report_sxw_ext.report_sxw_ext('report.purchase_ship_order_PPD_report',
                              'purchase.order',
                              'gp_stockpurchase/report/purchase_ship_order_PPD_report.mako',
                              parser=purchase_ship_order_PPD_report)
