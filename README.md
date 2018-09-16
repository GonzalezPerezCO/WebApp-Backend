# WebApp-Backend

Esta aplicación funciona sobre un entorno LAMP, por lo que se asume que todos sus componentes (Apache, MariaDB, PHP) ya se encuentran instalados. 

## Instalación de Slim (Framework de PHP)

La forma más recomendada de instalar Slim es a través del administrador de dependencias de PHP *Composer*. 

En caso de no tener Composer en el entorno de desarrollo, 
este se puede descargar usando los siguientes comandos en la terminal del servidor: 

```php
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
Estos 4 comandos lo que hacen es descargar el instalador en el directorio actual, luego verifican el instalador SHA-384, lo ejecutan en el directorio y por último lo remueven.

Para instalar Slim se navega al directorio base del proyecto y se ejecuta este comando:

```php
   composer require slim/slim "^3.0"
```

Después basta con incluir el archivo autoload como requerimiento en el script de PHP de la siguiente forma: 

```php
    <?php
    require 'vendor/autoload.php';
```

Para hacer el despliegue de la aplicación en el servidor web, son necesarios 2 cambios en la configuración de Apache.  
Asumiendo que no se tiene acceso a los archivos de configuración principal del servidor, se crea un archivo *.htaccess* el cual se añade en el mismo directorio del archivo *index.php*. En el archivo *.htaccess* se escriben las siguientes líneas:

``` 
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
```
Ahora para que estas reglas funcionen, hay que asegurarse de habilitar el módulo de Apache llamado *mod_rewrite* y que esté habilitada la opción *AllowOverride* en la configuración del servidor para que las directivas que fueron escritas en el archivo *.htaccess* puedan ser usadas. 

```
 AllowOverride All
 ```
 
Con estas configuraciones hechas, ya se puede hacer el despliegue de la aplicación al servidor web donde puede recibir y manejar peticiones HTTP hechas a los servicios.
