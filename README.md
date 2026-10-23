# API de gestión de reservas de alojamientos y planes asociados

Se ha contruido una API RESTful con **PHP puro** cuyo objetivo es la reserva de alijamientos. Para la visualización de los datos se ha contruido un frontend básico que utiliza **Twig** para el renderizado y **JavaScript vanilla** para la inteactividad. Mientras que para gestionar la base de datos se ha usado **MySQL**.

Con este proyecto lo que se busca es completar la prueba técnica reqeurida por MisterPlan SL, demostrando el entendimiento de las buenas prácticas en la arquitectura backend. Es decir, la separación de capas, inyección de independencias, seguridad y manejo centralizado de errores, todo ello sin depender de un framework como Laravel o Symfony. 

## Stack tecnológico del proyecto.
**Lenguaje backend:** PHP 8.2 con tipado estricto, POO y PDO.
**Gestor de bases de datos:** MySQL.
**Frontend:** Twig 3 para el motor de plantillas + JavaScript vanilla para las partes interactivas.
**Testing:** PHPUnit 10.
**Gestor de dependencias:**se ha utilizado Composer, pero exclusivamente para Twig y el autoload PSR-4.
**Servidor local** XAMPP (Apache + MySQL)

## Instalación en local y jecución en local

### 1. Requisitos previos
Instalar **XAMP** para poder acceder a Apache y a MySQL.
Tener instalado **Composer** de manera global en el dispositivo.
Contar con **Git** instalado

### 2. Clonar el repositorio de Git
Abrir terminal y clonar el proyecto dentro del directorio 'htdocs' de XAMPP

## 3. Gestion de la base de datos.
Para poder tener la base de datos creadas en PHPmyAdmin es necesario importar desde las opciones que da el archivo que se encuentra en el proyecto llamado schema.sql.
Una vez importado, la BD se crea y se añaden algunso datos.
**Importante* Arrancar en el panel de XAMPP el servicio de MySQL para poder llevar a cabo esta tarea.

## 4. Acceder a Apache.
Una vez importada la BD es necesario en el panel de XAMPP arrancar el servicio de Apche e introducir la siguiente URL http://localhost/reservas_api/public/reservas. 
De esta manera es posible acceder a la tabla de muestra que carga los datos de la BD. Desde aquí ya es posible añadir una nueva reserva, o cambiar el estado de las propias reservas. Además, para comprobar los detalles de la reserva si se presiona ver se accederá a una tabla que detalla esta información. 

## Decisiones de arquitectura.
El código se ha organizado siguiendo el principio de responsabilidad única, creando capas estrictamente separadas y fáciles de testear. El proyecto cuenta con:

**Controladores:** estos se encuentran en src/Controllers y separados en /Api (JSON) y en Web/ donde se encuentran los archivois Twig.
**Services:** Contiene la lógica de negocio, por ejemplo, validación de que una reserva no se pueda confirmar si ha sido cancelada. Además, corrdinan los validadores y los repositorios.
**Repositorioes:** esta capa se encarga de comunicarse con la base de datos. De esta manera si cambiasemos el gestor de la misma solo habría que tocar esta parte del proyecto. 
**Modelos:** aquí hemos creado las entidades inmutables que evitan mutaciones accidentales.
**Core:** aquí se han colocado archivos fundamentales para el enroutado de las url o el contror de los errores que puedan existir con las peticiones HTTP. 

## Cambios a futuro:
Desde luego que pasaría todo el proyecto a un framework como Laravel, más fácil de manejar en algunas cosas como el enroutado, la seguridad, etc. 
Por otro lado, cambiaría todo el JS por React y desde luego que tocaría la parte visual de la app.
Por último, quedaría desplegar en un buen servicio de hosting o de manera personal. 

## Cambios de escalabilidad.
Añadiría un caché con Redis, añadiría paginación obligatoria e implementaría índices compuestos.