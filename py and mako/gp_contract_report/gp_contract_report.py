# -*- coding: utf-8 -*-
##############################################################################
#
#    OpenERP, Open Source Management Solution
#    Copyright (C) 2004-2010 Tiny SPRL (<http://tiny.be>).
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as
#    published by the Free Software Foundation, either version 3 of the
#    License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program. If not, see <http://www.gnu.org/licenses/>.
#
##############################################################################

from osv import osv, fields
from tools.translate import _

import gp_customer.gp_tools.general_tools as gp_tools

DATE_FORMAT = '%d.%m.%Y'


class gp_contract_report(osv.osv):
    _name = 'gp.contract_report'
    _description = 'Contract types reporting'
    _rec_name = 'report_type'  # TODO: Add a function to include language too
    _order = 'creation_date DESC'

    def _sel_report_codes(self, cr, uid, context):
        # By default, all reports are available
        report_codes = [('CA_', _('Contract Amendment')),
                        ('COE', _('Certificate of Employment - Current Ship')),
                        ('COEM', _('Certificate of Employment - Main Season')),
                        ('CES', _('Certificate of Employment (with Salary)')),
                        ('CEC', _('Certificate of Employment (Current)')),
                        ('CTR', _('Certificate of Tax Regulation - Current Ship')),
                        ('CTRM', _('Certificate of Tax Regulation - Main Season')),
                        ('LCH', _('Letter CH')),
                        ('LZY', _('Letter ZY')),
                        ('IC_', _('Interview Confirmation')),
                        ('LSE', _('Letter SECO')),
                        ('COR', _('Confirmation of Resignation')),
                        ('ERL', _('Employment Reference Letter')),
                        ('COC', _('Cancellation of Contract')),
                        ('IGL', _('Guarantee Letter / Homeward journey')),
                        ('CBD', _('Contract - Binding Addendum')),
                        ('KFR', _('Kündigung French')),
                        ('PDU1', _('PD U1 Form')),]

        # Removes the reports that are not available in the given context
        if context.get('gp.employee_id') or context.get('gp.applicant_id'):
            report_codes.remove(('CA_', _('Contract Amendment')))
            report_codes.remove(('COE', _('Certificate of Employment - Current Ship')))
            report_codes.remove(('COEM', _('Certificate of Employment - Main Season')))
            report_codes.remove(('CES', _('Certificate of Employment (with Salary)')))
            report_codes.remove(('CEC', _('Certificate of Employment (Current)')))
            report_codes.remove(('CTR', _('Certificate of Tax Regulation - Current Ship')))
            report_codes.remove(('CTRM', _('Certificate of Tax Regulation - Main Season')))

            report_codes.remove(('COR', _('Confirmation of Resignation')))
            report_codes.remove(('ERL', _('Employment Reference Letter')))
            report_codes.remove(('COC', _('Cancellation of Contract')))
            report_codes.remove(('IGL', _('Guarantee Letter / Homeward journey')))
            report_codes.remove(('CBD', _('Contract - Binding Addendum')))
            report_codes.remove(('KFR', _('Kündigung French')))

        if context.get('gp.employee_id') or context.get('gp.contract_id'):
            report_codes.remove(('IC_', _('Interview Confirmation')))

        if context.get('gp.applicant_id'):
            report_codes.remove(('LSE', _('Letter SECO')))

        return report_codes

    _columns = {
        'employee_id': fields.many2one('hr.employee', 'Employee', required=False),
        'applicant_id': fields.many2one('hr.applicant', 'Applicant', required=False),
        'contract_id': fields.many2one('hr.contract', 'Contract', required=False),
        
        'is_default_rep': fields.boolean('Is default', required=True),
        'report_type': fields.selection(_sel_report_codes, _('Report Type'), required=True),
        'language_rep': fields.selection([('en_US', 'English'), ('de_DE', 'Deutsch')],
                                         _('Report Language'), required=True),
        'place_rep': fields.char(_('Place'), size=64),
        'date_c_start': fields.date(_('Contract start')),
        'date_c_end': fields.date(_('Contract end')),
        
        'basic_sal': fields.float(_('Basic Salary'), digits=(16, 2)),
        'wage': fields.float(_('Net Salary'), digits=(16, 2)),
        'position': fields.char(_('Position'), size=128),        
        
        'date_rep': fields.date(_('Report Date')),
        'title_rep': fields.char(_('Title'), size=128),
        'greeting_rep': fields.char(_('Greeting'), size=128),
        'text1_rep': fields.text(_('Text1')),
        'text2_rep': fields.text(_('Text2')),
        'text3_rep': fields.text(_('Text3')),
        'farewell_rep': fields.char(_('Farewell'), size=128),
        'signature_rep': fields.char(_('Signature'), size=128),
        'dig_signature_rep': fields.boolean(_('Digital Signature')),
        'creation_date': fields.date('Creation date', readonly=True),
        'user_id': fields.many2one('res.users', 'Creation user', readonly=True),
    }

    def default_get(self, cr, uid, fields, context={}):
        defaults = {}

        if 'language_rep' in fields:
            defaults['language_rep'] = 'de_DE'

        if 'employee_id' in fields:
            defaults['employee_id'] = context.get('gp.employee_id', False)

        if 'applicant_id' in fields:
            defaults['applicant_id'] = context.get('gp.applicant_id', False)

        if 'contract_id' in fields:
            defaults['contract_id'] = context.get('gp.contract_id', False)

        defaults['user_id'] = uid
        defaults['creation_date'] = gp_tools.today()

        return defaults

    def replace_special_field(self, cr, uid, report_type, language, field, employee, contract, date_start,
                              date_end, date_rep, user, context={}):
        if user:
            field = field.replace("?USER", user)

        if date_start:
            field = field.replace("?DATE_START", gp_tools.strftime_str(date_start, DATE_FORMAT))

        if date_end:
            field = field.replace("?DATE_END", gp_tools.strftime_str(date_end, DATE_FORMAT))

        if date_rep:
            field = field.replace("?DATE_REP", gp_tools.strftime_str(date_rep, DATE_FORMAT))

        if contract:
            contract_data = gp_tools.get_salary_calculation_data(contract)

            field = field.replace("?BASIC_SALARY", '{0:.2f}'.format(contract_data['base_salary']))
            field = field.replace("?RED_BASIC_SALARY", '{0:.2f}'.format(contract_data['reduced_base_salary']))
            field = field.replace("?NET_SALARY", '{0:.2f}'.format(contract.wage))
            field = field.replace("?RED_NET_SALARY", '{0:.2f}'.format(contract.reduced_wage))
            
            gross_sal_obj = self.pool.get('gp.gross_salary')
            field = field.replace("?SW_GROSS_SAL", '{0:.2f}'.format(contract.current_gross_salary))
            current_gross_sal_ids = gross_sal_obj.search(cr, uid, [('contract_id', '=', contract.id),
                                                                   ('current', '=', True),
                                                                   ])
            if current_gross_sal_ids:
                current_gross_sal = gross_sal_obj.browse(cr, uid, current_gross_sal_ids[0]) 
                field = field.replace("?SW_RED_GROSS_SAL", '{0:.2f}'.format(current_gross_sal.reduced_salary))
                field = field.replace("?SW_PK_AMOUNT", '{0:.2f}'.format(current_gross_sal.pk_amount))
                field = field.replace("?SW_NET", '{0:.2f}'.format(current_gross_sal.salary_2))
                default_holidays = 25.0
                if contract.contract_id and contract.contract_id.type in ('long_term', 'corporate', 'relief', 'head_office'):
                    default_holidays = contract.number_of_paid_holidays_2
                field = field.replace("?HOLIDAYS", '{0:.2f}'.format(default_holidays))       

        if employee:
            field = field.replace("?NAME", employee.name + " " + employee.last_name)
            field = field.replace("?LASTNAME", employee.last_name)
            field = field.replace("?PRENAME", employee.last_name + " " + employee.name)

            if employee.birthday:
                field = field.replace("?BIRTH", gp_tools.strftime_str(employee.birthday, DATE_FORMAT))

            if employee.gender == 'male':
                if language == 'de_DE':
                    field = field.replace("?GENDER", 'Herr')
                else:
                    field = field.replace("?GENDER", 'Mr.')
            else:
                if language == 'de_DE':
                    field = field.replace("?GENDER", 'Frau')
                else:
                    field = field.replace("?GENDER", 'Ms.')

            try:
                if contract:                                                                            
                    
                    if report_type in ('COEM', 'CTRM'):
                        field = field.replace("?SHIP", (contract.main_version_id and
                                                        contract.main_version_id.ship_id.print_name))
                    else:
                        field = field.replace("?SHIP", contract.version_id.ship_id.print_name)

                    if employee.gender == 'male':
                        field = field.replace("?POSITION", contract.job_id.print_name)
                    else:
                        field = field.replace("?POSITION", contract.job_id.print_name_fmale)
            except:
                # Avoids exception if field is not defined
                pass

        return field

    def onchange_report_type(self, cr, uid, ids, report_type, language, applicant_id, employee_id,
                             contract_id, date_c_start, date_c_end, context={}):
        if not (report_type and language and (employee_id or applicant_id or contract_id)):
            return {}

        ctx = context.copy()
        ctx['lang'] = language

        result = {'value': {}}
        date_end = date_c_end
        date_start = date_c_start
        contract = False

        if applicant_id:
            employee = self.pool.get('hr.applicant').browse(cr, uid, applicant_id, ctx)

        if employee_id:
            employee = self.pool.get('hr.employee').browse(cr, uid, employee_id, ctx)
            contract = employee.contract_id

        if contract_id:
            contract = self.pool.get('hr.contract').browse(cr, uid, contract_id, ctx)
            employee = contract.employee_id or contract.applicant_id

            if not date_end:
                date_end = contract.date_end

            if not date_start:
                date_start = contract.date_start

        result['value']['date_c_start'] = date_start
        result['value']['date_c_end'] = date_end
        
        if report_type in ('CTR', 'CTRM', 'IGL', 'KFR') and contract:
            result['value']['basic_sal'] = gp_tools.get_salary_calculation_data(contract)['base_salary']
            result['value']['wage'] = contract.wage
            result['value']['position'] = contract.job_id.print_name or '' if employee.gender == "male" else contract.job_id.print_name_fmale or ''
       

        if report_type in ('COE', 'COEM', 'CES', 'CTR', 'CTRM', 'ERL') and date_end:
            result['value']['date_rep'] = date_end
        else:
            result['value']['date_rep'] = gp_tools.today()

        user = self.pool.get('res.users').browse(cr, uid, uid)
        user = user.name

        contract_report_obj = self.pool.get('gp.contract_report')
        contract_report_ids = contract_report_obj.search(cr, uid,
                                                         [('report_type', '=', report_type),
                                                          ('language_rep', '=', language),
                                                          ('is_default_rep', '=', True)])

        if contract_report_ids:
            contract_default = contract_report_obj.browse(cr, uid, contract_report_ids[0], ctx)

            result['value']['place_rep'] = contract_default.place_rep
            result['value']['title_rep'] = contract_default.title_rep
            result['value']['greeting_rep'] = contract_default.greeting_rep
            result['value']['text1_rep'] = contract_default.text1_rep
            result['value']['text2_rep'] = contract_default.text2_rep
            result['value']['text3_rep'] = contract_default.text3_rep
            result['value']['farewell_rep'] = contract_default.farewell_rep
            result['value']['signature_rep'] = contract_default.signature_rep
            result['value']['dig_signature_rep'] = contract_default.dig_signature_rep

            # Replaces the tokens
            if result['value']['title_rep']:
                result['value']['title_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['title_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

            if result['value']['greeting_rep']:
                result['value']['greeting_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['greeting_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

            if result['value']['place_rep']:
                result['value']['place_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['place_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

            if result['value']['text1_rep']:
                result['value']['text1_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['text1_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

            if result['value']['text2_rep']:
                result['value']['text2_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['text2_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

            if result['value']['text3_rep']:
                result['value']['text3_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['text3_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

            if result['value']['farewell_rep']:
                result['value']['farewell_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['farewell_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

            if result['value']['signature_rep']:
                result['value']['signature_rep'] = self.replace_special_field(cr, uid, report_type, language, result['value']['signature_rep'], employee, contract, date_start, date_end, result['value']['date_rep'], user, context)

        return result

    def do_print_contract(self, cr, uid, ids, context={}):
        # return report with the corresponding data
        datas = {'ids': ids}

        res = self.read(cr, uid, ids, ['language_rep'])
        context['lang'] = res[0]['language_rep']

        # In order to link the attachment correctly
        if  context.get('gp.applicant_id', False):
            context['bt.res_model'] = 'hr.applicant'
            context['bt.res_id'] = context.get('gp.applicant_id')

        if context.get('gp.employee_id', False):
            context['bt.res_model'] = 'hr.employee'
            context['bt.res_id'] = context.get('gp.employee_id')

        if context.get('gp.contract_id', False):
            context['bt.res_model'] = 'hr.contract'
            context['bt.res_id'] = context.get('gp.contract_id')

        return {
            'type': 'ir.actions.report.xml',
            'report_name': 'gp_report_contract.report',
            'datas': datas,
            'context': context,
        }

    # TODO: Add constraint
