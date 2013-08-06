INSERT INTO qu_pap_clicks (userid,campaignid,bannerid,parentbannerid,countrycode,cdata1,cdata2,channel,dateinserted,raw,uniq,declined) SELECT userid, campaignid, bannerid, parentbannerid, countrycode, cdata1, cdata2, channel, CONCAT(DATE(day), ' 5:00:00') as dateinserted, raw_5 as raw, unique_5 as uniq, declined_5 as `declined` FROM qu_pap_dailyclicks WHERE raw_5 > 0 OR unique_5 > 0 OR declined_5 > 0 ;