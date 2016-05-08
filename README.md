Simettric Simple Forum Bundle
=============================

El objetivo de este bundle es el de proveer la funcionalidad básica para crear un sistema de foros para una aplicación escrita en Symfony.

Lejos de pretender abarcar la máxima funcionalidad posible, la filosofía de este proyecto es la de mantener siempre la máxima simpleza y flexibilidad para que otros desarrolladores puedan trabajar sobre el mismo sin una gran curva de aprendizaje.

Por defecto, el bundle funciona con unos templates que requieren disponer de un layout en app/Resources/views/base.html.twig implementando bootstrap3 junto a los bloques "body" y "javascripts" en él.
Esto es completamente opcional, ya que estos templates se pueden sobreescribir a nivel de app.


#### Instalación

TODO, actualmente el bundle se encuentra en desarrollo.

La fecha prevista para disponer de una primera versión en packagist es el 26/05/2016 

#### Funcionalidad disponible

* Crear, Editar y Eliminar foros por usuarios con rol de ROLE_FORUM_ADMIN

* Crear posts en foros

* Enviar respuestas al post

* Enviar respuestas a otra respuesta (limitado a dos niveles) **pendiente de refactorizar**

* Detalle de respuesta, mostrando las respuestas a la misma

* Se registra la IP de los usuarios tanto en Post como en Replies


##### TODO (En desarrollo)

* Posibilidad de editar posts por parte de los usuarios.

* Citar respuesta.

* Filtros de Twig para auto enlazar links, a otros usuarios y otras respuestas.

* Editor WYSIWYG en templates base

* Suscribirse\Cancelar suscripción a un post para recibir notificaciones por correo cuando se publique una respuesta

* EventSubscriber para envío de emails con sus correspondientes templates en twig

* Sistema básico de moderación de foros mediante estados de post: activo, baneado, eliminado

* ROLE_FORUM_MODERATOR y asignar administradores para moderar un foro

* El usuario ROLE_FORUM_ADMIN, ROLE_SUPER_ADMIN y ROLE_ADMIN pueden eliminar definitivamente posts y sus replies asociadas

* Posibilidad de crear foros privados

* Mejorar el permalink de respuesta, llevando al usuario a la página concreta donde se encuentra con un anchor 



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



##### Templates

Los templates se pueden sobreescribir totalmente a nivel de aplicación.
Puedes consultar cómo se puede hacer esto [en este apartado de la documentación de Symfony](http://symfony.com/doc/current/book/templating.html#overriding-bundle-templates)

##### Eventos

Cada vez que se crea o actualiza un Foro, Post o Respuesta, se emite un evento. 
Puedes suscribirte a estos eventos personalizando y extendiendo la funcionalidad de tu sistema de foros sin modificar el código fuente del bundle.

Puedes ver los eventos disponibles en el directorio /Event

##### Búsqueda

Por defecto, este bundle viene con un repositorio de búsqueda de posts basado en Doctrine. 

Para crear un repositorio propio el primer paso sería implementar la interfaz SearchRepositoryInterface como se muestra en el siguiente ejemplo


    
    class ElasticSearchRepository 
          implements \Simettric\SimpleForumBundle\Repository\SearchRepositoryInterface{
    
        private $em;
        
        private $searchService;
        
        
        function __construct(EntityManager $em, ElasticSearchService $searchService){
        
            $this->em = $em;
            
            $this->searchService = $searchService;
        }
        
        ...
        
        function search($search_pattern, $page=1, $limit=10){
        
            $ids = $this->searchService->search($search_pattern, $page, $limit);
            
            $this->results = $this->em->createQueryBuilder()
                                  ->select("p")
                                  ->form("SimettricSimpleForumBundle:Post", "p")
                                  ->where("p.id IN :ids")
                                  ->setParameter("ids", $ids)
                                  ->getQuery()
                                  ->getResult();
                                  
            return $this;
            
        }
       
    }
    
Por último, se sobreescribiría el servicio @sim_forum.search_repository en el archivo app/config/services.yml

    sim_forum.search_repository:
        class: AppBundle\SimpleForumBundle\Repository\ElasticSearchRepository
        arguments: ["@doctrine.orm.entity_manager", "@your_elastic_search_service"]
        
        
