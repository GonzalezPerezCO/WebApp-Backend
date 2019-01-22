# WebApp-Backend 

Esta aplicación funciona sobre un entorno LAMP, por lo que se asume que todos los componentes necesarios
(Apache, MariaDB, PHP) ya se encuentran instalados.

## Funcionamiento en entorno local
---------------------------------

Para correr el proyecto primero es necesario tener instalado Composer (el gestor de dependencias de PHP),
en caso de no tenerlo instalado en el entorno, puede seguir las instrucciones [en el sitio oficial](https://getcomposer.org/download/). 

Una vez Composer esté instalado, puede clonar el repositorio al entorno local 
```
git clone https://github.com/GonzalezPerezCo/WebApp-Backend.git 
```

Entre a la carpeta del proyecto 
```
cd WebApp-Backend
```

Ahora instale las dependencias del proyecto (para Windows)
```
composer install 
```
o también puede usar el comando (para macOS y Linux)
```
php composer.phar install
```

Una vez haya terminado el proceso de instalación puede correr el proyecto 
```
php composer.phar start 
```

Ahora abra un navegador y vaya a la dirección http://localhost:8080
Si ve un mensaje de bienvenida significa que el API ya está preparado para recibir y atender peticiones.

Usando el servidor web de Apache 
----------------------------------------
De la forma anterior el proyecto queda corriendo con el servidor web por defecto de php, alternativamente 
también se puede alojar en un servidor web como Apache con el cual puede mantenerse activo indefinidamente.

Para lograr esto puede renombrar la carpeta del proyecto para que sea más fácil de acceder y también ubicarla
junto con los demás archivos del servidor web para que pueda ser accedido correctamente.

Mueva la carpeta que contiene el proyecto al directorio raiz de la instalación de Apache (algo como /var/www/html)
después puede acceder a través de un navegador visitando la dirección http://localhost/WebApp-Backend/public 
y ahí podrá ver la página por defecto que también muestra el mensaje de bienvenida.

Si lo anterior no funcionó, es necesario hacer unas configuraciones en el servidor de Apache para que pueda manejar
la reescritura de direcciones.

Habilite el módulo de Apache llamado *mod_rewrite* y asegúrese de que esté habilitada la opción *AllowOverride* en 
la configuración del servidor local. 

Por último, cree un archivo *.htaccess* el cual se debe añadir en el mismo directorio donde está el archivo *index.php*.
Abra este archivo *.htaccess* con un editor de texto y añada las siguientes líneas:

```
RewriteEngine On 
RewriteCond %{REQUEST_FILENAME} ! -f
RewriteCond %{REQUEST_FILENAME} ! -d
RewriteRule ^ index.php [QSA,L]
```

Ahora con estos cambios hechos reinicie el servidor web, y vuelva a probar visitando la dirección anteriormente mencionada.