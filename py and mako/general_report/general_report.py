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
from tools import DEFAULT_SERVER_DATETIME_FORMAT
from tools import ustr
# Sorts, groups and prints lists


from bt_helper.tools import bt_dates
from bt_helper.tools import bt_misc
from bt_helper.tools import bt_format
import gp_customer.gp_tools.general_tools as gp_tools
from bt_helper.log_rotate import get_log
logger = get_log('NOTSET')


class general_report(report_sxw.rml_parse):
    TIMEZONE = 'Europe/Zurich'
    DATE_FORMAT = '%d.%m.%Y'

    def __init__(self, cr, uid, name, context):
        super(general_report, self).__init__(cr, uid, name, context=context)

        self.localcontext.update({
            'time': time,
            'ctx': context,
            'get_current_date_time': self.get_current_date_time,
            'get_duration': self.get_duration,
            'format_number': self.format_number,
            'get_month': self.get_month,
        })
        self.context = context

    def get_current_date_time(self):
        date = time.strftime(DEFAULT_SERVER_DATETIME_FORMAT)
        return date

    def get_duration(self, init_date, end_date):
        return gp_tools.get_difference_in_days(init_date, end_date) - 1

    def format_date(self, date, ff=DATE_FORMAT, language=False):
        return gp_tools.strftime_str(date, ff, language) if date else ''

    def format_number(self, number, decimal_digits=2):
        decimal_point = '.'
        thousand_separator = '\''
        return bt_format.format_number(number, decimal_point, thousand_separator, decimal_digits)

    def get_month(self, number):
        if self.context['lang'] == 'de_DE':
            selected_month = ustr(bt_dates.get_german_month_string(number))
        else:
            selected_month = bt_dates.get_month_string(number)
        return selected_month



report_sxw_ext.report_sxw_ext('report.general_report',
                              'gp.wizard_general_report',
                              'gp_general_report/report/general_report.mako',
                              parser=general_report)

report_sxw_ext.report_sxw_ext('report.general_report_manual',
                              'gp.wizard_general_report_manual',
                              'gp_general_report/report/general_report.mako',
                              parser=general_report)



