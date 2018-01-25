Ce plugin permet de faire un ping ou un wake-on-lan sur un équipement
réseau.

== Plugin configuration

Après téléchargement du plugin, il vous suffit juste d’activer celui-ci,
il n’y a aucune configuration à ce niveau.

![networks](../images/networks.PNG)

Configuration des équipements 
=============================

La configuration des équipements networks est accessible à partir du
menu plugin :

![networks2](../images/networks2.PNG)

Voilà à quoi ressemble la page du plugin Networks (ici avec déjà 1
équipement) :

![networks3](../images/networks3.PNG)

> **Tip**
>
> Comme à beaucoup d’endroits sur Jeedom, placer la souris tout à gauche
> permet de faire apparaître un menu d’accès rapide (vous pouvez, à
> partir de votre profil, le laisser toujours visible).

Une fois que vous cliquez sur l’un d’eux, vous obtenez :

![networks4](../images/networks4.PNG)

You can find here the full configuration of your device :

-   **Nom de l’équipement Networks** : nom de votre équipement Networks,

-   **Parent Object** : means the parent object the equipment depend
    equipment,

-   **Catégorie** : les catégories de l’équipement (il peut appartenir à
    plusieurs catégories),

-   ** Enable **: to make your equipment active,

-   ** Visible **: makes your equipment visible on the dashboard,

-   **Adresse IP** : adresse IP sur laquelle faire le ping,

-   **Adresse MAC (wol)** : adresse MAC pour le wake-on-lan,

-   **Broadcast IP (wol)** : adresse IP de broadcast du réseau pour
    envoyer le wake-on-lan,

-   **Auto-actualisation (cron)** : cron définissant la fréquence
    du ping.

En-dessous vous retrouvez la liste des commandes :

-   **Nom** : le nom affiché sur le dashboard,

-   **Afficher** : permet d’afficher la donnée sur le dashboard,

-   **Tester** : permet de tester la commande.

> **Note**
>
> Jeedom va vérifier toutes les minutes (par defaut) le ping sur l’IP.

> **Important**
>
> Si vous ne renseignez pas l’adresse MAC et broadcast alors vous
> n’aurez pas de commande wake-on-lan.

> **Note**
>
> L’adresse MAC doit être de la forme : 5E:FF:56:A2:AF:15.

Widgets 
=======

Voilà un exemple de widget (sans le wake-on-lan) en desktop :

![networks5](../images/networks5.PNG)

Et en mobile :

![networks6](../images/networks6.PNG)

Changelog détaillé :
<https://github.com/jeedom/plugin-networks/commits/stable>
