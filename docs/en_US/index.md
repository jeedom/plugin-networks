This plugin allows you to ping or wake-on-lan on equipment
Network.

Plugin configuration 
=======================

After downloading the plugin, you just need to activate it,
there is no configuration at this level.

![networks](../images/networks.PNG)

Equipment configuration 
=============================

The configuration of the Networks equipment is accessible from the
plugin menu :

![networks2](../images/networks2.PNG)

This is what the Networks plugin page looks like (here with already 1
equipment) :

![networks3](../images/networks3.PNG)

> **Tip**
>
> As in many places on Jeedom, place the mouse on the far left
> brings up a quick access menu (you can, at
> from your profile, always leave it visible).

Once you click on one of them, you get :

![networks4](../images/networks4.PNG)

Here you find all the configuration of your equipment :

-   **Name de l'équipement Networks** : name of your Networks equipment,

-   **Parent object** : indicates the parent object to which the equipment belongs,

-   **Category** : equipment categories (it can belong to several categories),

-   **Activate** : makes your equipment active,

-   **Visible** : makes your equipment visible on the dashboard,

-   **IP adress** : IP address to ping,

-   **MAC address (wol)** : MAC address for wake-on-lan,

-   **Broadcast IP (wol)** : network broadcast IP address to send wake-on-lan,

-   **Ping method** : Choice of ping method : IP (normal), ARP (preferred for phones or peripherals that fall asleep), PORT (to test if a port is open)
    
-   **TTL** : Time-to-live, values can be : 
    - 0 : same host
    - 1 : same subnets
    - 32 : same site
    - 64 : same region
    - 128 : same continent
    - 256 : no limit
If you have a 'Time to live exceeded' error, increase this value. If empty, then the parameter is 255. Note that on some configuration (Docker for example) the 255 is not authorized so it is necessary to decrease this value.

-   **Port** : Port to ping if you are in ping mode on a port (example : 8080 for 192.168.0.12:8080),

-   **Self-updating (cron)** : cron defining the ping frequency,

Below you find the list of orders :

-   **Name** : the name displayed on the dashboard,

-   **Pin up** : allows to display the data on the dashboard,

-   **Test** : Used to test the command.

> **NOTE**
>
> Jeedom will check the IP ping every minute (default).

> **Important**
>
> If you do not enter the MAC and broadcast address then you
> will not have a wake-on-lan command.

> **NOTE**
>
> MAC address must be of the form : 5E:FF:56:A2:AF:15

Widget 
=======

Example of widget (without wake-on-lan) in desktop view :

![networks5](../images/networks5.PNG)

And in mobile view :

![networks6](../images/networks6.PNG)

Changelog detailed :
<https://jeedom.github.io/plugin-networks/en_US/changelog>
