<?php

define("USER_PROFILE_PATH", "public/uploads/user_profile");
define("USER_COVER_PATH", "public/uploads/user_cover");
define("DEBUG_ERROR_MAIL_ID", 'khatriafaz@gmail.com');
define("ADMIN_PANEL_URL_PREFIX", 'admin');
define("API_URL_PREFIX", 'api');
define("CUSTOMERPORTAL_PANEL_URL_PREFIX", 'customerportal');
define("DYNAMIC_PANEL_URL_PREFIX", '');
define('DIRECTLY_INSERTED_STATUS', 'directly_inserted');
define('CURRENCY_SELECT_STRING_WITH_CURRENCY_EXCHANGE_RATE', 'CONCAT(currency.i_s_o, \' - \', COALESCE(currency.name, \'\'), \' :: \', COALESCE(currency.current_exchange_rate, \'\'))');
define('CUSTOMER_SELECT_STRING_WITH_CUSTOMER_TYPE_COLOR', "CONCAT('<span class=\'badge bold\' style=\'background-color:', COALESCE(customer_type_added.title_color, ''),';color: ', COALESCE(customer_type_added.title_font_color, ''),'\'>', customer.name ,'</span>')");