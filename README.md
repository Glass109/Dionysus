# Sistema de Eventos de Norma

Aquí te dejo las instrucciones por si estás bien pendejo y no sabes como correrlo

## Pre-requisitos 

- PHP 8.1 o más nuevo (Si te da hueva instalarlo, instala Xampp y usa ese alaverga)
- Composer (Es como NPM, pero de PHP)
- Node.js y NPM (Este de ahuevo lo tienes)

## Instrucciones

### 1. Clona el repo

```
Ya sabes como hacer esto, duh
```

### 2. Instala las dependencias

```bash
composer install
npm install
```

### 3. Configura tu .env

Primero, copia el archivo de ejemplo (No es de ahuevo usar la terminal, solo hazle copypaste y le cambias el nombre a .env) 
```bash
cp .env.example .env
```

Luego, genera tu llave de aplicación, es para encriptar todo alaverga
```bash
php artisan key:generate
```

Puro SQLite pa, no tienes que configurar permisos de la DB

### 4. Crea las tablas y metele datos falsos alaverga (Seeding)

```bash
php artisan migrate --seed
```

### 5. Configura el storage para guardar imágenes

Este comando crea un enlace simbólico entre la carpeta `storage/app/public` y `public/storage`. Esto es necesario para que las imágenes subidas por los usuarios (como las fotos de los eventos) sean accesibles públicamente a través de la web. Sin este enlace, las imágenes subidas no serían visibles en el sitio.


### 6. ¡A correr el proyecto! 🚀

Abre dos terminales diferentes y ejecuta:

En la primera:
```bash
php artisan serve
```


En la segunda:
```bash
npm run dev
```
### 7. ¡IMPORTANTE! ⚠️

No cierres ninguna de las dos terminales mientras estés trabajando en el proyecto. Si cierras la terminal de `php artisan serve`, el servidor local se detendrá y no podrás acceder a la aplicación. Si cierres la terminal de `npm run dev`, los cambios que hagas en el frontend no se compilarán automáticamente.

Mantén ambas terminales abiertas y funcionando mientras desarrollas.


¡Y listo! Ya puedes entrar a `http://localhost:8000` y ver tu proyecto funcionando de poca.

## "No tengas miedo en preguntarme, cobro barato" - Dondé, diario