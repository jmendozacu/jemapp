INSERT INTO qu_pap_clicks (userid,campaignid,bannerid,parentbannerid,countrycode,cdata1,cdata2,channel,dateinserted,raw,uniq,declined) SELECT userid, campaignid, bannerid, parentbannerid, countrycode, cdata1, cdata2, channel, DATE_FORMAT(month, '%Y-%m-18 12:00:00') as dateinserted, raw_18 as raw, unique_18 as uniq, declined_18 as `declined` FROM qu_pap_monthlyclicks WHERE DAY(LAST_DAY(month)) >= 18 AND (raw_18 > 0 OR unique_18 > 0 OR declined_18 > 0);