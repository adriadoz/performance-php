
# Práctica Final MPWAR

El proyecto consta en la creaión de una aplicación para el tratamiento de imágenes.
Ha sido realizado en el contexto del Máster en Programación Web de Alto Rendimiento, por Adrià Velardos Palomar
 

## Instalación de la máquina virtual
Una vez descargado el repositorio debemos asegurar que tenemos instalado VirtualBox, Vagrant y Ansible.
Mediante la consola nos situaremos en la carpeta del proyecto que contiene el archivo `VagratnFile`, tal que así:
        
        cd mpwarAnsible/vagrant
        
Una vez en la carpeta ejecutaremos el siguient comando con la finalidad de levantar la máquina:
 
        vagrant up 

## La Máquina Virtual

La máquina que tendremos instalada tendrá instalado en su interior los siguientes servicios, y sus respectivos puertos:

- **Apache**, port: 80
- **Mysql**, port: 3306
- **Blackfire**
- **RabbitMQ**, port: 5672
- **RabbitMQ Dashboard**, port: 15672, user: admin, password: admin
- **Redis**, port: 6379
- **Elasticsearch**, port: 9200
- **Kibana**, port: 5601

## Base de Datos

Antes de usar la aplicación deberemos setear nuestra base de datos ejecutando el script siguiente:

        ../html/db/imagesDB.sql
        
Este creará nuestra base de datos y seteará las tablas necesarias.

## Uso de la Aplicación

### 1. Subida de imágenes

Mediante el endpoint `http://192.168.33.50/index.php` podremos acceder a la subida de imágenes concurrente mediante DropzoneJs.
Cada vez que se suba una imagen a nuestra apliación, la información de esta será persistida en nuestre base de datos Mysql. 
Una vez subida será escalada a distintos tamaños `XL, L, M, S, XS` y se le aplicarán dos filtros extras `Blur y Escala de grises`.
Estos procesos de transformación se realizan a cabo por consumidores de las colas que persistirán también en base de datos las nuevas imágenes resultantes.

Se decidió usar una cola de RabbitMQ para cada transformación porque algunos procesos como aplicar el fitlro en escala de grises o el blur ralentizaban el resto de transformaciónes.
De esta manera cada cola y consumer va a su ritmo sin entorpecer al resto.

### 2. Buscador de imágenes

Una vez subidas nuestras imágenes podemos acceder al endpoint `http://192.168.33.50/search.php`. Se listarán todas las imágenes hasta el momento,
la primera vez las irá a buscar a la base de datos MySQL, pero en las siguientes usará los datos de Redis.
En el buscador podemos filtrar según los tags de nuestras imágenes. Por ejemplo si introducimos `L` y hacemos click en el botton `Search`.
Se mostrarán únicamente las imágenes con ese tag.

### 3. Editor de imágenes

Haciendo click en cualquier imagen de las mostradas en el Buscador de imágenes accederemos a un endpoint tipo:
`http://192.168.33.50/edit.php?id=5b0d9c4ba86c41.94768330` donde `5b0d9c4ba86c41.94768330` es el identificador de la imagen a editar.
Podremos modificar la descripción o los tags de la imágen en cuestión.


## Blackfire
Para realizar las pruebas de profiler, primero hay que configurar el cliente y el servidor de blackfire con las credenciales de nuestra cuenta en `blackfire.io`
Para ello usamos los siguientes comandos:

        sudo blackfire-agent -register
        sudo /etc/init.d/blackfire-agent restart
        blackfire config
        
Para ver los resultados directamente en el explorador Chrome deberemos instalar la extension pertinente.

### Capturas de pantalla de los resultados
Los archivos originales se pueden encontrar en `../html/blackfire` dentro del repositorio 

#### 1. Subida de imágenes
![alt text](https://bitbucket.org/adoz/performance-adriavelardos/src/master/share/www/html/blackfire/search_graph.png)

#### 2. Buscador de imágenes
![alt text](https://bitbucket.org/adoz/performance-adriavelardos/src/master/share/www/html/blackfire/search_timeline.png)
![alt text](https://bitbucket.org/adoz/performance-adriavelardos/src/master/share/www/html/blackfire/index_graph.png)

#### 3. Edición de imágenes
![alt text](https://bitbucket.org/adoz/performance-adriavelardos/src/master/share/www/html/blackfire/edit_graph.png)
![alt text](https://bitbucket.org/adoz/performance-adriavelardos/src/master/share/www/html/blackfire/edit_timeline.png)

### Modificaciones a partir de los resultados
