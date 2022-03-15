#!/bin/bash

. $(dirname $0)/config.inc

headerfile=/tmp/$$.headers
outputfile=/tmp/$$.output
curl -s -D $headerfile "http://localhost:8080/test?auto="$service_name > $outputfile ;
loginurl=$(cat $headerfile | grep "Location:" | sed 's/Location: //' | strings) ;
curl -s -D $headerfile $loginurl > $outputfile ;
casurl=$(cat $headerfile | grep "Location:" | sed 's/Location: //' | strings) ;
curl -s -D $headerfile "$casurl" > $outputfile ;
cookie=$(cat $headerfile | grep "Set-Cookie:" | sed 's/;.*//' | sed 's/.* //' | strings)
lt=$(cat $outputfile | grep 'name="lt" value=' | sed 's/.*name="lt" value="//' | sed 's/".*//' | strings)
curl -s -D $headerfile $casurl -H 'Referer: '$casurl -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' -H 'Accept-Language: fr,en;q=0.7,en-US;q=0.3' -H 'DNT: 1' -H 'Content-Type: application/x-www-form-urlencoded' -H "Cookie: $cookie" --data-raw 'username='$service_login'&password='$service_pass'&x=102&y=21&lt='$lt'&_eventId=submit'
curl -s -D $headerfile $(cat $headerfile | grep "Location:" | sed 's/Location: //' | strings) > $outputfile 
ticket=$(cat $headerfile | grep "Location:" | sed 's/Location: //' | strings | sed 's/.*ticket=//')
curl -s -D $headerfile "http://localhost:8080/test?ticket="$ticket
echo
rm $headerfile $outputfile
