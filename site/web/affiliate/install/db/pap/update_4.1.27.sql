UPDATE qu_g_mail_templates SET classname = 'Pap_Mail_AffiliateOnNewSale' WHERE classname = 'Pap_Mail_OnSaleApproved';
DELETE FROM qu_g_mail_templates WHERE classname = 'Pap_Mail_OnSaleBeforeApproval';