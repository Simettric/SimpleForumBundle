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

Implementa la interfaz SimpleForumBundle\Interfaces\UserInterface en tu entidad como puedes ver en el siguiente ejemplo

    <?php
    
    namespace AppBundle\Entity;
    
    use Simettric\SimpleForumBundle\Interfaces\UserInterface as ForumUserInterface;
    use Doctrine\ORM\Mapping as ORM;
    
    /**
     * @ORM\Entity
     * @ORM\Table(name="user")
     */
    class User implements ForumUserInterface {

Y en el archivo app/config/config.yml (cambiando la clase AppBundle\Entity\User por la tuya, en el caso de no encontrarse en AppBundle)

    # Doctrine Configuration
    doctrine:
        orm:
            resolve_target_entities:
                Simettric\SimpleForumBundle\Interfaces\UserInterface: AppBundle\Entity\User
                
                

Añadir rutas en app/config/routing.yml

    simettric_simple_forum:
        resource: "@SimettricSimpleForumBundle/Controller/"
        type:     annotation
        prefix:   /forums


En el archive app/config/security.yml, gestionar la herencia de roles para ROLE_FORUM_ADMIN y su acceso a las operaciones de administración

    security:
        role_hierarchy:
            ROLE_FORUM_ADMIN: [ROLE_USER]
            ROLE_ADMIN:       [ROLE_USER, ROLE_FORUM_ADMIN]
            
        access_control:
            - { path: ^/admin-forum, role: ROLE_FORUM_ADMIN }


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


#### Extender y personalizar

##### Entidad usuario

Este bundle utiliza la entidad de usuario que definas en tu aplicación. Simplemente debe implementar la interfaz SimpleForumBundle\Interfaces\UserInterface como puedes ver en el siguiente ejemplo

    <?php
    
    namespace AppBundle\Entity;
    
    use Simettric\SimpleForumBundle\Interfaces\UserInterface as ForumUserInterface;
    use Doctrine\ORM\Mapping as ORM;
    
    /**
     * @ORM\Entity
     * @ORM\Table(name="user")
     */
    class User implements ForumUserInterface

##### Templates

Los templates se pueden sobreescribir totalmente a nivel de aplicación.
Puedes consultar cómo se puede hacer esto [en este apartado de la documentación de Symfony](http://symfony.com/doc/current/book/templating.html#overriding-bundle-templates)

##### Eventos

Cada vez que se crea o actualiza un Foro, Post o Respuesta, se emite un evento. 
Puedes suscribirte a estos eventos personalizando y extendiendo la funcionalidad de tu sistema de foros sin modificar el código fuente del bundle.

Puedes ver los eventos disponibles en el directorio /Event