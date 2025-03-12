<?php 
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->loadHtmlFile('https://www.zabbix.com');
$metaChildren = $dom->getElementsByTagName('meta');
for ($i = 0; $i < $metaChildren->length; $i++) {
  $el = $metaChildren->item($i);
  print $el->getAttribute('name') . '=' . $el->getAttribute('content') . "<br>";
}


/*

<meta property="og:title" content="Zabbix :: The Enterprise-Class Open Source Network Monitoring Solution" />
<meta property="og:description" content="Zabbix is a mature and effortless enterprise-class open source monitoring solution for network monitoring and application monitoring of millions of metrics." />

<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.zabbix.com/" />
<meta property="og:image" content="https://assets.zabbix.com/img/banners/zabbix_5_2_1200x628.png" />
<meta property="og:image:secure_url" content="https://assets.zabbix.com/img/banners/zabbix_5_2_1200x628.png" />
<meta property="og:image:type" content="image/png" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="628" />

<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="theme-color" content="#23262D">


result:

=
description=Zabbix is a mature and effortless enterprise-class open source monitoring solution for network monitoring and application monitoring of millions of metrics.

=Zabbix :: The Enterprise-Class Open Source Network Monitoring Solution
=Zabbix is a mature and effortless enterprise-class open source monitoring solution for network monitoring and application monitoring of millions of metrics.
=website
=https://www.zabbix.com/
=https://assets.zabbix.com/img/banners/zabbix_5_2_1200x628.png
=https://assets.zabbix.com/img/banners/zabbix_5_2_1200x628.png
=image/png
=1200
=628
format-detection=telephone=no
viewport=width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no
theme-color=#23262D
*/