# Low

Estructura directorios

1 - configuracion

2 - controllers

3 - cron

4 - libs

5 - modelos

6 - modules

7 - nucleo

8 - public

9 - tmp

10 - views

11 - widgets


1 - Están todos los archivos de configuración del sitio, como son el acceso a la base de datos, la url del sitio, claves de APIs como son docusign, stripe o smtp

2 - Están todos los controladores del front que son los que contienen todos los métodos que responden a los eventos de los usuarios y realizan las peticiones a los modelos.

3 - Están los archivos que se van a ejecutar como tarea programada por el servidor.

4 - Están los archivos de las librerías (ej: phpmailer, stripe, phpexcel) y los modelos del MVC, que hacen las peticiones a la base de datos tanto del frontend como backend

5 - Son archivos que corresponden al uso del activerecord que es un ORM que utiliza el sitio para algunas de las peticiones a la base de datos

6 - Están los archivos de los modelos, controladores y vistas del backend

7 - Son todos archivos propios del funcionamiento del framework. Lo ideal es no tocarlos.

8 - Están los archivos públicos que se generan en el sitio (los docs para docusing o los recortes de imágenes) y también archivos js o css o imágenes que utiliza el sitio

9 - Están los archivos temporales que genera el framework. No se deben tocar.

10 - Los templates de las vistas del frontend.

11 - Son archivos que se utilizan para gestionar los menú dinámicos del sitio. En este caso no se utilizan.
