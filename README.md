Simettric Simple Forum Bundle
=============================

El objetivo de este bundle es el de proveer la funcionalidad básica para crear un sistema de foros para una aplicación escrita en Symfony.

Lejos de pretender abarcar la máxima funcionalidad posible, la filosofía de este proyecto es la de mantener siempre la máxima simpleza y flexibilidad para que otros desarrolladores puedan trabajar sobre el mismo sin una gran curva de aprendizaje.

Por defecto, el bundle funciona con unos templates que requieren disponer de un layout en app/Resources/views/base.html.twig implementando bootstrap3 junto a los bloques "body" y "javascripts" en el.
Esto es completamente opcional, ya que estos templates se pueden sobreescribir a nivel de app.


#### Este bundle depende de 

* PHP5.6 o superior
* KnpPaginatorBundle
* Twig_Extensions_Extension_Text (para los templates base)
* JQuery y Bootstrap (para los templates base)


#### Instalación

TODO, actualmente el bundle se encuentra en desarrollo.

La fecha prevista para disponer de una primera versión en packagist es el 26/05/2016 

#### Configuración

En el archivo app/config/config.yml

    # Doctrine Configuration
    doctrine:
        orm:
            resolve_target_entities:
                Simettric\SimpleForumBundle\Interfaces\UserInterface: %your_user_entity_class%
                

Añadir la extensión de texto de twig (Solo si usas los templates base y si no se ha añadido anteriormente) en el archivo app/config/services.yml

    services:
        twig.extension.text:
             class: Twig_Extensions_Extension_Text
             tags:
                 - { name: twig.extension }

Configurar template de knp_paginator (Solo si usas los templates base y si no se ha añadido anteriormente)

    knp_paginator:
        template:
            pagination: KnpPaginatorBundle:Pagination:twitter_bootstrap_v3_pagination.html.twig



