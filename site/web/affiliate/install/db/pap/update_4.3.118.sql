INSERT INTO qu_pap_clicks (userid,campaignid,bannerid,parentbannerid,countrycode,cdata1,cdata2,channel,dateinserted,raw,uniq,declined) SELECT userid, campaignid, bannerid, parentbannerid, countrycode, cdata1, cdata2, channel, DATE_FORMAT(month, '%Y-%m-30 12:00:00') as dateinserted, raw_30 as raw, unique_30 as uniq, declined_30 as `declined` FROM qu_pap_monthlyclicks WHERE DAY(LAST_DAY(month)) >= 30 AND (raw_30 > 0 OR unique_30 > 0 OR declined_30 > 0);