#!/bin/sh -f

PLACE=".1.3.6.1.4.1.47554"
REQ="$2"

# Do nothing with SET requests
if [ "$1" = "-s" ]; then
  exit 0
fi

#  GETNEXT requests - determine next valid instance
if [ "$1" = "-n" ]; then
  case "$REQ" in
    # Walk Everything .0
    $PLACE)           RET=$PLACE.0 ;;

    $PLACE.1.19)      RET=$PLACE.1.19 ;;

    $PLACE.19)        RET=$PLACE.19.0 ;;

    #Temperature .1
    $PLACE.19.1|      \
    $PLACE.19.1.0.*|  \
    $PLACE.19.1.1|    \
    $PLACE.19.1.1.*)    RET=$PLACE.19.1.0 ;;     # piHatTemperature

    # Humidity .2
    $PLACE.19.2|      \
    $PLACE.19.2.0.*|  \
    $PLACE.19.2.1|    \
    $PLACE.19.2.1.*)    RET=$PLACE.19.2.0 ;;     # piHatHumidity

    # Contacts .3
    $PLACE.19.3|      \
    $PLACE.19.3.0|    \
    $PLACE.19.3.0.*|  \
    $PLACE.19.3.1)    RET=$PLACE.19.3.1.0 ;;     # String description

    $PLACE.19.3.1.*|  \
    $PLACE.19.3.2)    RET=$PLACE.19.3.2.0 ;;     # piHatContacts.1

    $PLACE.19.3.2.*|  \
    $PLACE.19.3.3)    RET=$PLACE.19.3.3.0 ;;     # piHatContacts.3

    $PLACE.19.3.3.*|  \
    $PLACE.19.3.4)    RET=$PLACE.19.3.4.0 ;;     # piHatContacts.4

    $PLACE.19.3.4.*|  \
    $PLACE.19.3.5)    RET=$PLACE.19.3.5.0 ;;     # piHatContacts.5

    $PLACE.19.3.5.*|  \
    $PLACE.19.3.6)    RET=$PLACE.19.3.6.0 ;;     # piHatContacts.6

    # Relays .4
    $PLACE.19.4|      \
    $PLACE.19.4.0|    \
    $PLACE.19.4.0.*|  \
    $PLACE.19.4.1)    RET=$PLACE.19.4.1.0 ;;     # piHatRelays.1

    $PLACE.19.4.1.*|  \
    $PLACE.19.4.2)    RET=$PLACE.19.4.2.0 ;;     # piHatRelays.2

    *) exit 0 ;;
  esac
else
  case "$REQ" in
    # Status Group
    $PLACE.0|         \
    $PLACE)           RET=$PLACE.0 ;;

    $PLACE.1.19)      RET=$PLACE.1.19 ;;

    $PLACE.19.0|      \
    $PLACE.19)        RET=$PLACE.19.0 ;;

    # Temperature
    $PLACE.19.1|      \
    $PLACE.19.1.0|    \
    $PLACE.19.1.0.*)  RET=$PLACE.19.1.0 ;;     # piHatTemperature

    # Humidity
    $PLACE.19.2|      \
    $PLACE.19.2.0|    \
    $PLACE.19.2.0.*)  RET=$PLACE.19.2.0 ;;     # piHatHumidity
    # Contacts
    $PLACE.19.3|      \
    $PLACE.19.3.0|    \
    $PLACE.19.3.1)    RET=$PLACE.19.3.1.0 ;;

    $PLACE.19.3.2)    RET=$PLACE.19.3.2.0 ;;

    $PLACE.19.3.3)    RET=$PLACE.19.3.3.0 ;;

    $PLACE.19.3.4)    RET=$PLACE.19.3.4.0 ;;

    $PLACE.19.3.5)    RET=$PLACE.19.3.5.0 ;;

    $PLACE.19.3.6)    RET=$PLACE.19.3.6.0 ;;

    $PLACE.19.3.1.0|  \
    $PLACE.19.3.2.0|  \
    $PLACE.19.3.3.0|  \
    $PLACE.19.3.4.0|  \
    $PLACE.19.3.5.0|  \
    $PLACE.19.3.6.0)	 RET=$REQ ;;

    # Relays .4
    $PLACE.19.4|      \
    $PLACE.19.4.0|    \
    $PLACE.19.4.1)    RET=$PLACE.19.4.1.0 ;;

    $PLACE.19.4.2)   RET=$PLACE.19.4.2.0 ;;

    $PLACE.19.4.1.0|  \
    $PLACE.19.4.2.0)  RET=$REQ ;;
    *) exit 0;
  esac
fi
echo "$RET"
case "$RET" in
  # Everything
  $PLACE.0)    echo "string"; echo "Total Highspeed AlarmPiHat"; exit 0 ;;
  $PLACE.1.19) echo "string";  echo "Should be a group thing"; exit 0;;
  $PLACE.19.0) echo "string"; echo "AlarmPiHat Status Group"; exit 0 ;;
  # Temperature .1
  $PLACE.19.1.0)   echo "string";    temp=$(/usr/bin/python3 /home/pi/AlarmPiHat/getTemp.py);   echo $temp;    exit 0 ;;
  # Humitidity .2
  $PLACE.19.2.0)   echo "string";    humid=$(/usr/bin/python3 /home/pi/AlarmPiHat/getHumid.py); echo $humid;   exit 0 ;;
  # Contacts .3
  $PLACE.19.3.1.0)   echo "integer";   contact1=$(gpio read 25);   echo $contact1;   exit 0 ;;
  $PLACE.19.3.2.0)   echo "integer";   contact2=$(gpio read 27);   echo $contact2;   exit 0 ;;
  $PLACE.19.3.3.0)   echo "integer";   contact3=$(gpio read 24);   echo $contact3;   exit 0 ;;
  $PLACE.19.3.4.0)   echo "integer";   contact4=$(gpio read 23);   echo $contact4;   exit 0 ;;
  $PLACE.19.3.5.0)   echo "integer";   contact5=$(gpio read 26);   echo $contact5;   exit 0 ;;
  $PLACE.19.3.6.0)   echo "integer";   contact6=$(gpio read 22);   echo $contact6;   exit 0 ;;
  # Relays .4
  $PLACE.19.4.1.0)   echo "integer";   contact1=$(gpio read 0);    echo $contact1;   exit 0 ;;
  $PLACE.19.4.2.0)   echo "integer";   contact2=$(gpio read 7);    echo $contact2;   exit 0 ;;
  *)          echo "string";    echo "ack... $RET $REQ";                      exit 0;;  # Should not happen
esac
