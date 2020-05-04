Este plugin permite que você faça ping ou ative a LAN em equipamentos
Rede.

Configuração do plugin 
=======================

Depois de baixar o plugin, você só precisa ativá-lo,
não há configuração neste nível.

![networks](../images/networks.PNG)

Configuração do equipamento 
=============================

A configuração do equipamento de rede é acessível a partir do
menu de plugins :

![networks2](../images/networks2.PNG)

É assim que a página do plug-in Networks se parece (aqui com 1
equipamento) :

![networks3](../images/networks3.PNG)

> **Tip**
>
> Como em muitos lugares em Jeedom, coloque o mouse na extremidade esquerda
> abre um menu de acesso rápido (você pode, em
> do seu perfil, deixe-o sempre visível).

Depois de clicar em um deles, você obtém :

![networks4](../images/networks4.PNG)

Aqui você encontra toda a configuração do seu equipamento :

-   **Nome do equipamento de redes** : nome do seu equipamento de rede,

-   **Objeto pai** : indica o objeto pai ao qual o equipamento pertence,

-   **Categoria** : categorias de equipamentos (pode pertencer a várias categorias),

-   **Activer** : torna seu equipamento ativo,

-   **Visible** : torna seu equipamento visível no painel,

-   **Endereço IP** : Endereço IP para executar ping,

-   **Endereço MAC (wol)** : Endereço MAC para wake-on-lan,

-   **IP de transmissão (wol)** : endereço IP de broadcast da rede para enviar wake-on-lan,

-   **Método Ping** : Escolha do método ping : IP (normal), ARP (preferível para telefones ou periféricos que adormecem), PORT (para testar se uma porta está aberta)
    
-   **TTL** : Tempo de vida útil, os valores podem ser : 
    - 0 : mesmo host
    - 1 : mesmas sub-redes
    - 32 : mesmo site
    - 64 : mesma região
    - 128 : mesmo continente
    - 256 : sem limite
Se você tiver um erro "Tempo de vida excedido", aumente esse valor. Se vazio, o parâmetro é 255. Observe que em algumas configurações (Docker, por exemplo), o 255 não está autorizado, portanto, é necessário diminuir esse valor.

-   **Port** : Porta para executar ping se você estiver no modo ping em uma porta (exemplo : 8080 para 192.168.0.12:8080),

-   **Atualização automática (cron)** : cron definindo a frequência do ping,

Abaixo você encontra a lista de pedidos :

-   **Nom** : o nome exibido no painel,

-   **Afficher** : permite exibir os dados no painel,

-   **Tester** : permite testar o comando.

> **Note**
>
> O Jeedom irá verificar o ping do IP a cada minuto (padrão).

> **Important**
>
> Se você não digitar o endereço MAC e de transmissão, então você
> não terá um comando wake-on-lan.

> **Note**
>
> O endereço MAC deve estar no formato : 5E:FF:56:A2:AF:15

Widgets 
=======

Exemplo de widget (sem ativação na lan) na visualização da área de trabalho :

![networks5](../images/networks5.PNG)

E na visualização móvel :

![networks6](../images/networks6.PNG)

Registro de alterações detalhado :
<https://jeedom.github.io/plugin-networks/fr_FR/changelog>
