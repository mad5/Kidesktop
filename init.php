<?php

if(!file_exists("/etc/resolv.conf.orig")) {
	copy("/etc/resolv.conf", "/etc/resolv.conf.orig");
}
#copy("/etc/resolv.conf.orig", "/etc/resolv.conf");

chdir(dirname(__FILE__));
if(!file_exists("config.json")) die("no config.js found.\n");
$config = json_decode(file_get_contents("config.json"), true);

$c = "# Generated by Kidesktop\n";
$c .= "127.0.0.1	localhost\n";
$c .= "::1		localhost ip6-localhost ip6-loopback\n";
$c .= "ff02::1		ip6-allnodes\n";
$c .= "ff02::2		ip6-allrouters\n";
$c .= "\n";
$c .= "127.0.1.1	raspberrypi\n";
$hosts = $config["hosts"];
foreach($hosts as $host) {
	$ip = getHostByName($host);
	#echo $ip."\n";
	$c .= $ip."\t".$host."\n";
	$ip = getHostByName("www.".$host);
	if($ip!="") $c .= $ip."\twww.".$host."\n";
	#echo $ip."\n";
}

$hosts = json_decode(file_get_contents($config["kideskadmsrv"]."?action=gethosts&mainkey=".$config["mainkey"]."&key=".$config["key"]),true);
foreach($hosts as $host) {
	$ip = getHostByName($host);
	if($ip!=$host) {
	    $c .= $ip."\t".$host."\n";
	}
	$ip = getHostByName("www.".$host);
	if($ip!=$host) {
	    if($ip!="") $c .= $ip."\twww.".$host."\n";
	}
}

#file_put_contents("/etc/hosts", $c);

#unlink("/etc/resolv.conf");
#sleep(5);
#unlink("/etc/resolv.conf");


?>