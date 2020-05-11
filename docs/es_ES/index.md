Este complemento le permite hacer ping o reactivar el equipo
Red.

Configuración del plugin 
=======================

Después de descargar el complemento, solo necesita activarlo,
no hay configuración a este nivel.

![networks](../images/networks.PNG)

Configuración del equipo 
=============================

Se puede acceder a la configuración del equipo de redes desde
menú de complementos :

![networks2](../images/networks2.PNG)

Así es como se ve la página del complemento Redes (aquí con 1
equipos) :

![networks3](../images/networks3.PNG)

> **Punta**
>
> Como en muchos lugares de Jeedom, coloca el mouse en el extremo izquierdo
> muestra un menú de acceso rápido (puede, en
> desde tu perfil, siempre déjalo visible).

Una vez que haces clic en uno de ellos, obtienes :

![networks4](../images/networks4.PNG)

Aquí encontrarás toda la configuración de tu equipo :

-   **Nombre de l'équipement Networks** : nombre de su equipo de redes,

-   **Objeto padre** : indica el objeto padre al que pertenece el equipo,

-   **Categoría** : categorías de equipos (puede pertenecer a varias categorías),

-   **Activar** : activa su equipo,

-   **Visible** : hace que su equipo sea visible en el tablero,

-   **Dirección IP** : Dirección IP para hacer ping,

-   **Dirección MAC (wol)** : Dirección MAC para wake-on-lan,

-   **Broadcast IP (wol)** : Dirección IP de transmisión de red para enviar wake-on-lan,

-   **Método de ping** : Elección del método de ping : IP (normal), ARP (preferido para teléfonos o periféricos que se quedan dormidos), PORT (para probar si un puerto está abierto)
    
-   **TTL** : Tiempo de vida, los valores pueden ser : 
    - 0 : mismo anfitrión
    - 1 : mismas subredes
    - 32 : mismo sitio
    - 64 : misma región
    - 128 : mismo continente
    - 256 : sin limite
Si tiene el error 'Tiempo de vida excedido', aumente este valor. Si está vacío, entonces el parámetro es 255. Tenga en cuenta que en algunas configuraciones (Docker, por ejemplo) el 255 no está autorizado, por lo que es necesario disminuir este valor.

-   **Puerto** : Puerto para hacer ping si está en modo ping en un puerto (ejemplo : 8080 para 192.168.0.12:8080),

-   **Actualización automática (cron)** : cron que define la frecuencia de ping,

A continuación encontrará la lista de pedidos :

-   **Nombre** : el nombre que se muestra en el tablero,

-   **Mostrar** : permite mostrar los datos en el tablero,

-   **Probar** : Se usa para probar el comando.

> **Nota**
>
> Jeedom verificará el ping IP cada minuto (predeterminado).

> **Importante**
>
> Si no ingresa el MAC y la dirección de transmisión, entonces
> no tendrá un comando wake-on-lan.

> **Nota**
>
> La dirección MAC debe ser de la forma : 5E:FF:56:A2:AF:15

Reproductores 
=======

Ejemplo de widget (sin wake-on-lan) en la vista de escritorio :

![networks5](../images/networks5.PNG)

Y en vista móvil :

![networks6](../images/networks6.PNG)

Registro de cambios detallado :
<https://jeedom.github.io/plugin-networks/es_ES/changelog>
