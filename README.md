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
