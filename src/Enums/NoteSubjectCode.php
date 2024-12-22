<?php

namespace MahdiAbderraouf\FacturX\Enums;

enum NoteSubjectCode: string
{
    case GENERAL_INFORMATION = 'AAI';
    case SUPPLIER_NOTES = 'SUR';
    case REGULATORY_INFORMATION = 'REG';
    case LEGAL_INFORMATION = 'ABL';
    case TAX_INFORMATION = 'TXD';
    case CUSTOMS_INFORMATION = 'CUS';
    case PAYMENT_DETAIL = 'PMD';
    case PAYMENT_TERM = 'AAB';
    case GOODS_ITEM_DESCRIPTION = 'AAA';
    case DANGEROUS_GOODS_ADDITIONAL_INFORMATION = 'AAC';
    case DANGEROUS_GOODS_TECHNICAL_NAME = 'AAD';
    case ACKNOWLEDGEMENT_DESCRIPTION = 'AAE';
    case RATE_ADDITIONAL_INFORMATION = 'AAF';
    case PARTY_INSTRUCTIONS = 'AAG';
    case ADDITIONAL_CONDITIONS_OF_SALE_PURCHASE = 'AAJ';
    case PRICE_CONDITIONS = 'AAK';
    case GOODS_DIMENSIONS_IN_CHARACTERS = 'AAL';
    case EQUIPMENT_RE_USAGE_RESTRICTIONS = 'AAM';
    case HANDLING_RESTRICTION = 'AAN';
    case ERROR_DESCRIPTION = 'AAO';
    case RESPONSE = 'AAP';
    case PACKAGE_CONTENTS_DESCRIPTION = 'AAQ';
    case TERMS_OF_DELIVERY = 'AAR';
    case BILL_OF_LADING_REMARKS = 'AAS';
    case MODE_OF_SETTLEMENT_INFORMATION = 'AAT';
    case CONSIGNMENT_INVOICE_INFORMATION = 'AAU';
    case CLEARANCE_INVOICE_INFORMATION = 'AAV';
    case LETTER_OF_CREDIT_INFORMATION = 'AAW';
    case LICENSE_INFORMATION = 'AAX';
    case CERTIFICATION_STATEMENTS = 'AAY';
    case ADDITIONAL_EXPORT_INFORMATION = 'AAZ';
    case TARIFF_STATEMENTS = 'ABA';
    case MEDICAL_HISTORY = 'ABB';
    case CONDITIONS_OF_SALE_OR_PURCHASE = 'ABC';
    case CONTRACT_DOCUMENT_TYPE = 'ABD';
    case ADDITIONAL_TERMS_CONDITIONS_DOCUMENTARY_CREDIT = 'ABE';
    case INSTRUCTIONS_INFORMATION_ABOUT_STANDBY_DOCUMENTARY = 'ABF';
    case INSTRUCTIONS_INFORMATION_ABOUT_PARTIAL_SHIPMENTS = 'ABG';
    case INSTRUCTIONS_INFORMATION_ABOUT_TRANSHIPMENTS = 'ABH';
    case ADDITIONAL_HANDLING_INSTRUCTIONS_DOCUMENTARY_CREDIT = 'ABI';
    case DOMESTIC_ROUTING_INFORMATION = 'ABJ';
    case CHARGEABLE_CATEGORY_OF_EQUIPMENT = 'ABK';
    case ONWARD_ROUTING_INFORMATION = 'ABM';
    case ACCOUNTING_INFORMATION = 'ABN';
    case DISCREPANCY_INFORMATION = 'ABO';
    case CONFIRMATION_INSTRUCTIONS = 'ABP';
    case METHOD_OF_ISSUANCE = 'ABQ';
    case DOCUMENTS_DELIVERY_INSTRUCTIONS = 'ABR';
    case ADDITIONAL_CONDITIONS = 'ABS';
    case INFORMATION_INSTRUCTIONS_ABOUT_ADDITIONAL_AMOUNTS_COVERED = 'ABT';
    case DEFERRED_PAYMENT_TERMS_ADDITIONAL = 'ABU';
    case ACCEPTANCE_TERMS_ADDITIONAL = 'ABV';
    case NEGOTIATION_TERMS_ADDITIONAL = 'ABW';
    case DOCUMENT_NAME_AND_DOCUMENTARY_REQUIREMENTS = 'ABX';
    case INSTRUCTIONS_INFORMATION_ABOUT_REVOLVING_DOCUMENTARY_CREDIT = 'ABZ';
    case DOCUMENTARY_REQUIREMENTS = 'ACA';
    case ADDITIONAL_INFORMATION = 'ACB';
    case FACTOR_ASSIGNMENT_CLAUSE = 'ACC';
    case REASON = 'ACD';
    case DISPUTE = 'ACE';
    case ADDITIONAL_ATTRIBUTE_INFORMATION = 'ACF';
    case ABSENCE_DECLARATION = 'ACG';
    case AGGREGATION_STATEMENT = 'ACH';
    case COMPILATION_STATEMENT = 'ACI';
    case DEFINITIONS_EXCEPTION = 'ACJ';
    case PRIVACY_STATEMENT = 'ACK';
    case QUALITY_STATEMENT = 'ACL';
    case STATISTICAL_DESCRIPTION = 'ACM';
    case STATISTICAL_DEFINITION = 'ACN';
    case STATISTICAL_NAME = 'ACO';
    case STATISTICAL_TITLE = 'ACP';
    case OFF_DIMENSION_INFORMATION = 'ACQ';
    case UNEXPECTED_STOPS_INFORMATION = 'ACR';
    case PRINCIPLES = 'ACS';
    case TERMS_AND_DEFINITION = 'ACT';
    case SEGMENT_NAME = 'ACU';
    case SIMPLE_DATA_ELEMENT_NAME = 'ACV';
    case SCOPE = 'ACW';
    case MESSAGE_TYPE_NAME = 'ACX';
    case INTRODUCTION = 'ACY';
    case GLOSSARY = 'ACZ';
    case FUNCTIONAL_DEFINITION = 'ADA';
    case EXAMPLES = 'ADB';
    case COVER_PAGE = 'ADC';
    case DEPENDENCY_NOTES = 'ADD';
    case CODE_VALUE_NAME = 'ADE';
    case CODE_LIST_NAME = 'ADF';
    case CLARIFICATION_OF_USAGE = 'ADG';
    case COMPOSITE_DATA_ELEMENT_NAME = 'ADH';
    case FIELD_OF_APPLICATION = 'ADI';
    case TYPE_OF_ASSETS_AND_LIABILITIES = 'ADJ';
    case PROMOTION_INFORMATION = 'ADK';
    case METER_CONDITION = 'ADL';
    case METER_READING_INFORMATION = 'ADM';
    case TYPE_OF_TRANSACTION_REASON = 'ADN';
    case TYPE_OF_SURVEY_QUESTION = 'ADO';
    case CARRIER_AGENT_COUNTER_INFORMATION = 'ADP';
    case WORK_ITEM_DESCRIPTION_ON_EQUIPMENT = 'ADQ';
    case MESSAGE_DEFINITION = 'ADR';
    case BOOKED_ITEM_INFORMATION = 'ADS';
    case SOURCE_OF_DOCUMENT = 'ADT';
    case NOTE = 'ADU';
    case FIXED_PART_OF_SEGMENT_CLARIFICATION_TEXT = 'ADV';
    case CHARACTERISTICS_OF_GOODS = 'ADW';
    case ADDITIONAL_DISCHARGE_INSTRUCTIONS = 'ADX';
    case CONTAINER_STRIPPING_INSTRUCTIONS = 'ADY';
    case CSC_PLATE_INFORMATION = 'ADZ';
    case CARGO_REMARKS = 'AEA';
    case TEMPERATURE_CONTROL_INSTRUCTIONS = 'AEB';
    case TEXT_REFERS_TO_EXPECTED_DATA = 'AEC';
    case TEXT_REFERS_TO_RECEIVED_DATA = 'AED';
    case SECTION_CLARIFICATION_TEXT = 'AEE';
    case INFORMATION_TO_THE_BENEFICIARY = 'AEF';
    case INFORMATION_TO_THE_APPLICANT = 'AEG';
    case INSTRUCTIONS_TO_THE_BENEFICIARY = 'AEH';
    case INSTRUCTIONS_TO_THE_APPLICANT = 'AEI';
    case CONTROLLED_ATMOSPHERE = 'AEJ';
    case TAKE_OFF_ANNOTATION = 'AEK';
    case PRICE_VARIATION_NARRATIVE = 'AEL';
    case DOCUMENTARY_CREDIT_AMENDMENT_INSTRUCTIONS = 'AEM';
    case STANDARD_METHOD_NARRATIVE = 'AEN';
    case PROJECT_NARRATIVE = 'AEO';
    case RADIOACTIVE_GOODS_ADDITIONAL_INFORMATION = 'AEP';
    case BANK_TO_BANK_INFORMATION = 'AEQ';
    case REIMBURSEMENT_INSTRUCTIONS = 'AER';
    case REASON_FOR_AMENDING_A_MESSAGE = 'AES';
    case INSTRUCTIONS_TO_THE_PAYING_AND_OR_ACCEPTING_AND_OR = 'AET';
    case INTEREST_INSTRUCTIONS = 'AEU';
    case AGENT_COMMISSION = 'AEV';
    case REMITTING_BANK_INSTRUCTIONS = 'AEW';
    case INSTRUCTIONS_TO_THE_COLLECTING_BANK = 'AEX';
    case COLLECTION_AMOUNT_INSTRUCTIONS = 'AEY';
    case INTERNAL_AUDITING_INFORMATION = 'AEZ';
    case CONSTRAINT = 'AFA';
    case COMMENT = 'AFB';
    case SEMANTIC_NOTE = 'AFC';
    case HELP_TEXT = 'AFD';
    case LEGEND = 'AFE';
    case BATCH_CODE_STRUCTURE = 'AFF';
    case PRODUCT_APPLICATION = 'AFG';
    case CUSTOMER_COMPLAINT = 'AFH';
    case PROBABLE_CAUSE_OF_FAULT = 'AFI';
    case DEFECT_DESCRIPTION = 'AFJ';
    case REPAIR_DESCRIPTION = 'AFK';
    case REVIEW_COMMENTS = 'AFL';
    case TITLE = 'AFM';
    case DESCRIPTION_OF_AMOUNT = 'AFN';
    case RESPONSIBILITIES = 'AFO';
    case SUPPLIER = 'AFP';
    case PURCHASE_REGION = 'AFQ';
    case AFFILIATION = 'AFR';
    case BORROWER = 'AFS';
    case LINE_OF_BUSINESS = 'AFT';
    case FINANCIAL_INSTITUTION = 'AFU';
    case BUSINESS_FOUNDER = 'AFV';
    case BUSINESS_HISTORY = 'AFW';
    case BANKING_ARRANGEMENTS = 'AFX';
    case BUSINESS_ORIGIN = 'AFY';
    case BRAND_NAMES_DESCRIPTION = 'AFZ';
    case BUSINESS_FINANCING_DETAILS = 'AGA';
    case COMPETITION = 'AGB';
    case CONSTRUCTION_PROCESS_DETAILS = 'AGC';
    case CONSTRUCTION_SPECIALTY = 'AGD';
    case CONTRACT_INFORMATION = 'AGE';
    case CORPORATE_FILING = 'AGF';
    case CUSTOMER_INFORMATION = 'AGG';
    case COPYRIGHT_NOTICE = 'AGH';
    case CONTINGENT_DEBT = 'AGI';
    case CONVICTION_DETAILS = 'AGJ';
    case EQUIPMENT = 'AGK';
    case WORKFORCE_DESCRIPTION = 'AGL';
    case EXEMPTION = 'AGM';
    case FUTURE_PLANS = 'AGN';
    case INTERVIEWEE_CONVERSATION_INFORMATION = 'AGO';
    case INTANGIBLE_ASSET = 'AGP';
    case INVENTORY = 'AGQ';
    case INVESTMENT = 'AGR';
    case INTERCOMPANY_RELATIONS_INFORMATION = 'AGS';
    case JOINT_VENTURE = 'AGT';
    case LOAN = 'AGU';
    case LONG_TERM_DEBT = 'AGV';
    case LOCATION = 'AGW';
    case CURRENT_LEGAL_STRUCTURE = 'AGX';
    case MARITAL_CONTRACT = 'AGY';
    case MARKETING_ACTIVITIES = 'AGZ';
    case MERGER = 'AHA';
    case MARKETABLE_SECURITIES = 'AHB';
    case BUSINESS_DEBT = 'AHC';
    case ORIGINAL_LEGAL_STRUCTURE = 'AHD';
    case EMPLOYEE_SHARING_ARRANGEMENTS = 'AHE';
    case ORGANIZATION_DETAILS = 'AHF';
    case PUBLIC_RECORD_DETAILS = 'AHG';
    case PRICE_RANGE = 'AHH';
    case QUALIFICATIONS = 'AHI';
    case REGISTERED_ACTIVITY = 'AHJ';
    case CRIMINAL_SENTENCE = 'AHK';
    case SALES_METHOD = 'AHL';
    case EDUCATIONAL_INSTITUTION_INFORMATION = 'AHM';
    case STATUS_DETAILS = 'AHN';
    case SALES = 'AHO';
    case SPOUSE_INFORMATION = 'AHP';
    case EDUCATIONAL_DEGREE_INFORMATION = 'AHQ';
    case SHAREHOLDING_INFORMATION = 'AHR';
    case SALES_TERRITORY = 'AHS';
    case ACCOUNTANTS_COMMENTS = 'AHT';
    case EXEMPTION_LAW_LOCATION = 'AHU';
    case SHARE_CLASSIFICATIONS = 'AHV';
    case FORECAST = 'AHW';
    case EVENT_LOCATION = 'AHX';
    case FACILITY_OCCUPANCY = 'AHY';
    case IMPORT_AND_EXPORT_DETAILS = 'AHZ';
    case ADDITIONAL_FACILITY_INFORMATION = 'AIA';
    case INVENTORY_VALUE = 'AIB';
    case EDUCATION = 'AIC';
    case EVENT = 'AID';
    case AGENT = 'AIE';
    case DOMESTICALLY_AGREED_FINANCIAL_STATEMENT_DETAILS = 'AIF';
    case OTHER_CURRENT_ASSET_DESCRIPTION = 'AIG';
    case OTHER_CURRENT_LIABILITY_DESCRIPTION = 'AIH';
    case FORMER_BUSINESS_ACTIVITY = 'AII';
    case TRADE_NAME_USE = 'AIJ';
    case SIGNING_AUTHORITY = 'AIK';
    case GUARANTEE = 'AIL';
    case HOLDING_COMPANY_OPERATION = 'AIM';
    case CONSIGNMENT_ROUTING = 'AIN';
    case LETTER_OF_PROTEST = 'AIO';
    case QUESTION = 'AIP';
    case PARTY_INFORMATION = 'AIQ';
    case AREA_BOUNDARIES_DESCRIPTION = 'AIR';
    case ADVERTISEMENT_INFORMATION = 'AIS';
    case FINANCIAL_STATEMENT_DETAILS = 'AIT';
    case ACCESS_INSTRUCTIONS = 'AIU';
    case LIQUIDITY = 'AIV';
    case CREDIT_LINE = 'AIW';
    case WARRANTY_TERMS = 'AIX';
    case DIVISION_DESCRIPTION = 'AIY';
    case REPORTING_INSTRUCTION = 'AIZ';
    case EXAMINATION_RESULT = 'AJA';
    case LABORATORY_RESULT = 'AJB';
    case ALLOWANCE_CHARGE_INFORMATION = 'ALC';
    case X_RAY_RESULT = 'ALD';
    case PATHOLOGY_RESULT = 'ALE';
    case INTERVENTION_DESCRIPTION = 'ALF';
    case SUMMARY_OF_ADMITTANCE = 'ALG';
    case MEDICAL_TREATMENT_COURSE_DETAIL = 'ALH';
    case PROGNOSIS = 'ALI';
    case INSTRUCTION_TO_PATIENT = 'ALJ';
    case INSTRUCTION_TO_PHYSICIAN = 'ALK';
    case ALL_DOCUMENTS = 'ALL';
    case MEDICINE_TREATMENT = 'ALM';
    case MEDICINE_DOSAGE_AND_ADMINISTRATION = 'ALN';
    case AVAILABILITY_OF_PATIENT = 'ALO';
    case REASON_FOR_SERVICE_REQUEST = 'ALP';
    case PURPOSE_OF_SERVICE = 'ALQ';
    case ARRIVAL_CONDITIONS = 'ARR';
    case SERVICE_REQUESTERS_COMMENT = 'ARS';
    case AUTHENTICATION = 'AUT';
    case REQUESTED_LOCATION_DESCRIPTION = 'AUU';
    case MEDICINE_ADMINISTRATION_CONDITION = 'AUV';
    case PATIENT_INFORMATION = 'AUW';
    case PRECAUTIONARY_MEASURE = 'AUX';
    case SERVICE_CHARACTERISTIC = 'AUY';
    case PLANNED_EVENT_COMMENT = 'AUZ';
    case EXPECTED_DELAY_COMMENT = 'AVA';
    case TRANSPORT_REQUIREMENTS_COMMENT = 'AVB';
    case TEMPORARY_APPROVAL_CONDITION = 'AVC';
    case CUSTOMS_VALUATION_INFORMATION = 'AVD';
    case VALUE_ADDED_TAX_MARGIN_SCHEME = 'AVE';
    case MARITIME_DECLARATION_OF_HEALTH = 'AVF';
    case PASSENGER_BAGGAGE_INFORMATION = 'BAG';
    case ADDITIONAL_PRODUCT_INFORMATION_ADDRESS = 'BAI';
    case INFORMATION_TO_BE_PRINTED_ON_DESPATCH_ADVICE = 'BAJ';
    case MISSING_GOODS_REMARKS = 'BAK';
    case NON_ACCEPTANCE_INFORMATION = 'BAL';
    case RETURNS_INFORMATION = 'BAM';
    case SUB_LINE_ITEM_INFORMATION = 'BAN';
    case TEST_INFORMATION = 'BAO';
    case EXTERNAL_LINK = 'BAP';
    case VAT_EXEMPTION_REASON = 'BAQ';
    case PROCESSING_INSTRUCTIONS = 'BAR';
    case RELAY_INSTRUCTIONS = 'BAS';
    case SIMA_APPLICABLE = 'BAT';
    case APPEALS_PROGRAM_CODE = 'BAU';
    case SIMA_SUBJECT = 'BAV';
    case SURTAX_APPLICABLE = 'BAW';
    case SIMA_SECURITY_BOND = 'BAX';
    case SURTAX_SUBJECT = 'BAY';
    case SAFEGUARD_APPLICABLE = 'BAZ';
    case SAFEGUARD_SUBJECT = 'BBB';
    case TRANSPORT_CONTRACT_DOCUMENT_CLAUSE = 'BLC';
    case INSTRUCTION_TO_PREPARE_THE_PATIENT = 'BLD';
    case MEDICINE_TREATMENT_COMMENT = 'BLE';
    case EXAMINATION_RESULT_COMMENT = 'BLF';
    case SERVICE_REQUEST_COMMENT = 'BLG';
    case PRESCRIPTION_REASON = 'BLH';
    case PRESCRIPTION_COMMENT = 'BLI';
    case CLINICAL_INVESTIGATION_COMMENT = 'BLJ';
    case MEDICINAL_SPECIFICATION_COMMENT = 'BLK';
    case ECONOMIC_CONTRIBUTION_COMMENT = 'BLL';
    case STATUS_OF_A_PLAN = 'BLM';
    case RANDOM_SAMPLE_TEST_INFORMATION = 'BLN';
    case PERIOD_OF_TIME = 'BLO';
    case LEGISLATION = 'BLP';
    case SECURITY_MEASURES_REQUESTED = 'BLQ';
    case TRANSPORT_CONTRACT_DOCUMENT_REMARK = 'BLR';
    case PREVIOUS_PORT_OF_CALL_SECURITY_INFORMATION = 'BLS';
    case SECURITY_INFORMATION = 'BLT';
    case WASTE_INFORMATION = 'BLU';
    case B2C_MARKETING_INFORMATION_SHORT_DESCRIPTION = 'BLV';
    case B2B_MARKETING_INFORMATION_LONG_DESCRIPTION = 'BLW';
    case B2C_MARKETING_INFORMATION_LONG_DESCRIPTION = 'BLX';
    case PRODUCT_INGREDIENTS = 'BLY';
    case LOCATION_SHORT_NAME = 'BLZ';
    case PACKAGING_MATERIAL_INFORMATION = 'BMA';
    case FILLER_MATERIAL_INFORMATION = 'BMB';
    case SHIP_TO_SHIP_ACTIVITY_INFORMATION = 'BMC';
    case PACKAGE_MATERIAL_DESCRIPTION = 'BMD';
    case CONSUMER_LEVEL_PACKAGE_MARKING = 'BME';
    case SIMA_MEASURE_IN_FORCE = 'BMF';
    case PRE_CARM = 'BMG';
    case SIMA_MEASURE_TYPE = 'BMH';
    case CUSTOMS_CLEARANCE_INSTRUCTIONS = 'CCI';
    case SUB_TYPE_CODE = 'CCJ';
    case SIMA_INFORMATION = 'CCK';
    case TIME_LIMIT_END = 'CCL';
    case TIME_LIMIT_START = 'CCM';
    case WAREHOUSE_TIME_LIMIT = 'CCN';
    case VALUE_FOR_DUTY_INFORMATION = 'CCO';
    case CUSTOMS_CLEARANCE_INSTRUCTIONS_EXPORT = 'CEX';
    case CHANGE_INFORMATION = 'CHG';
    case CUSTOMS_CLEARANCE_INSTRUCTION_IMPORT = 'CIP';
    case CLEARANCE_PLACE_REQUESTED = 'CLP';
    case LOADING_REMARKS = 'CLR';
    case ORDER_INFORMATION = 'COI';
    case CUSTOMER_REMARKS = 'CUR';
    case DAMAGE_REMARKS = 'DAR';
    case DOCUMENT_ISSUER_DECLARATION = 'DCL';
    case DELIVERY_INFORMATION = 'DEL';
    case DELIVERY_INSTRUCTIONS = 'DIN';
    case DOCUMENTATION_INSTRUCTIONS = 'DOC';
    case DUTY_DECLARATION = 'DUT';
    case EFFECTIVE_USED_ROUTING = 'EUR';
    case FIRST_BLOCK_TO_BE_PRINTED_ON_THE_TRANSPORT_CONTRACT = 'FBC';
    case GOVERNMENT_BILL_OF_LADING_INFORMATION = 'GBL';
    case ENTIRE_TRANSACTION_SET = 'GEN';
    case FURTHER_INFORMATION_CONCERNING_GGVS_PAR_7 = 'GS7';
    case CONSIGNMENT_HANDLING_INSTRUCTION = 'HAN';
    case HAZARD_INFORMATION = 'HAZ';
    case CONSIGNMENT_INFORMATION_FOR_CONSIGNEE = 'ICN';
    case INSURANCE_INSTRUCTIONS = 'IIN';
    case INVOICE_MAILING_INSTRUCTIONS = 'IMI';
    case COMMERCIAL_INVOICE_ITEM_DESCRIPTION = 'IND';
    case INSURANCE_INFORMATION = 'INS';
    case INVOICE_INSTRUCTION = 'INV';
    case INFORMATION_FOR_RAILWAY_PURPOSE = 'IRP';
    case INLAND_TRANSPORT_DETAILS = 'ITR';
    case TESTING_INSTRUCTIONS = 'ITS';
    case LOCATION_ALIAS = 'LAN';
    case LINE_ITEM = 'LIN';
    case LOADING_INSTRUCTION = 'LOI';
    case MISCELLANEOUS_CHARGE_ORDER = 'MCO';
    case ADDITIONAL_MARKS_NUMBERS_INFORMATION = 'MKS';
    case ORDER_INSTRUCTION = 'ORI';
    case OTHER_SERVICE_INFORMATION = 'OSI';
    case PACKING_MARKING_INFORMATION = 'PAC';
    case PAYMENT_INSTRUCTIONS_INFORMATION = 'PAI';
    case PAYABLES_INFORMATION = 'PAY';
    case PACKAGING_INFORMATION = 'PKG';
    case PACKAGING_TERMS_INFORMATION = 'PKT';
    case PAYMENT_INFORMATION = 'PMT';
    case PRODUCT_INFORMATION = 'PRD';
    case PRICE_CALCULATION_FORMULA = 'PRF';
    case PRIORITY_INFORMATION = 'PRI';
    case PURCHASING_INFORMATION = 'PUR';
    case QUARANTINE_INSTRUCTIONS = 'QIN';
    case QUALITY_DEMANDS_REQUIREMENTS = 'QQD';
    case QUOTATION_INSTRUCTION_INFORMATION = 'QUT';
    case RISK_AND_HANDLING_INFORMATION = 'RAH';
    case RETURN_TO_ORIGIN_INFORMATION = 'RET';
    case RECEIVABLES = 'REV';
    case CONSIGNMENT_ROUTE = 'RQR';
    case SAFETY_INFORMATION = 'SAF';
    case CONSIGNMENT_DOCUMENTARY_INSTRUCTION = 'SIC';
    case SPECIAL_INSTRUCTIONS = 'SIN';
    case SHIP_LINE_REQUESTED = 'SLR';
    case SPECIAL_PERMISSION_FOR_TRANSPORT = 'SPA';
    case SPECIAL_PERMISSION_CONCERNING_GOODS_TO_BE_TRANSPORTED = 'SPG';
    case SPECIAL_HANDLING = 'SPH';
    case SPECIAL_PERMISSION_CONCERNING_PACKAGE = 'SPP';
    case SPECIAL_PERMISSION_CONCERNING_TRANSPORT_MEANS = 'SPT';
    case SUBSIDIARY_RISK_NUMBER_IATA_DGR = 'SRN';
    case SPECIAL_SERVICE_REQUEST = 'SSR';
    case CONSIGNMENT_TARIFF = 'TCA';
    case CONSIGNMENT_TRANSPORT = 'TDT';
    case TRANSPORTATION_INFORMATION = 'TRA';
    case REQUESTED_TARIFF = 'TRR';
    case WAREHOUSE_INSTRUCTION_INFORMATION = 'WHI';
    case MUTUALLY_DEFINED = 'ZZZ';
}
