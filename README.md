# Sistema de Reservas Cowork

## Descripción

Sistema de gestión de reservas para espacios de coworking desarrollado con Laravel. La aplicación permite a los clientes registrarse, iniciar sesión y realizar reservas de salas de coworking, mientras que los administradores pueden gestionar las salas y supervisar las reservaciones.

## Características

### Roles de Usuario

- **Administrador**: Puede gestionar salas, cambiar el estado de las reservaciones, ver las reservaciones por sala y exportar datos a CSV.
- **Cliente**: Puede registrarse, iniciar sesión, hacer reservas en las salas disponibles y listar sus propias reservaciones.

### Funcionalidad de Cliente

- Registro e inicio de sesión.
- Reservaciones de salas:
  - Selección de sala de coworking.
  - Selección de fecha y hora de la reserva.
  - Las reservas son siempre de una hora de duración.
  - Verificación automática de disponibilidad.
  - Estado inicial de la reserva: "Pendiente".

### Funcionalidad de Administrador

- Gestión de salas:
  - Crear, editar y eliminar salas de coworking.
  - Cada sala tiene un nombre y una descripción opcional.
- Gestión de reservas:
  - Cambiar el estado de una reserva de "Pendiente" a "Aceptada" o "Rechazada".
  - Listar todas las reservas y filtrarlas por sala de coworking.
- Exportar a CSV:
  - Exportar archivos CSV con todas las reservas generadas, incluyendo Cliente, Sala, Hora de reserva.
  - Resumen del total de tiempo de reserva por Sala según días.

## Requisitos Técnicos

- PHP 8.1 o superior
- Laravel 9.x
- MySQL 8.1
- Composer
- Node.js y NPM

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/Labarta/coworking-system.git
cd coworking-system
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Configurar el entorno

Copiar el archivo de ejemplo de entorno y generar la clave de la aplicación:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos

Editar el archivo `.env` con los datos de conexión a tu base de datos MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cowork_reservation
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Crear la base de datos

Crea una base de datos MySQL con el nombre especificado en la configuración:

```sql
CREATE DATABASE cowork_reservation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Ejecutar las migraciones y seeders

```bash
php artisan migrate --seed
```

### 7. Compilar los assets

```bash
npm install
npm run dev
```

O para producción:

```bash
npm run build
```

### 8. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: http://127.0.0.1:8000

## Configuración en un nuevo equipo

Para configurar el proyecto en un nuevo equipo, sigue estos pasos detallados:

### Requisitos previos

1. **PHP 8.1 o superior**
   - Asegúrate de tener las siguientes extensiones habilitadas en php.ini:
     - pdo_mysql
     - mbstring
     - xml
     - curl
     - zip
     - fileinfo
     - gd

2. **Composer**
   - Instala la última versión desde [getcomposer.org](https://getcomposer.org/download/)

3. **MySQL 8.1**
   - Instala MySQL Server y asegúrate de que esté en ejecución

4. **Node.js y NPM**
   - Instala Node.js (versión 14 o superior) desde [nodejs.org](https://nodejs.org/)

### Pasos de configuración

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/Labarta/coworking-system.git
   cd coworking-system
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Configurar el entorno**
   ```bash
   cp .env.example .env
   ```
   
   Edita el archivo `.env` con tu configuración local, especialmente los datos de conexión a la base de datos.

4. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

5. **Crear la base de datos**
   - Crea una base de datos MySQL con el nombre que configuraste en el archivo `.env`
   - Asegúrate de usar la codificación UTF-8: `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci`

6. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Compilar assets**
   ```bash
   npm install
   npm run dev
   ```

8. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

9. **Acceder a la aplicación**
   - Abre tu navegador y visita: http://127.0.0.1:8000

## Solución de problemas comunes

1. **Error de conexión a la base de datos**
   - Verifica que el servidor MySQL esté en ejecución
   - Comprueba las credenciales en el archivo `.env`
   - Asegúrate de que la base de datos exista

2. **Error al compilar assets**
   - Intenta eliminar la carpeta `node_modules` y volver a ejecutar `npm install`
   - Asegúrate de tener una versión compatible de Node.js

3. **Error de permisos**
   - Asegúrate de que las carpetas `storage` y `bootstrap/cache` tengan permisos de escritura

## Usuarios por Defecto

La aplicación viene con los siguientes usuarios predefinidos:

- **Administrador**:
  - Email: admin@cowork.com
  - Contraseña: password

- **Cliente**:
  - Email: cliente@cowork.com
  - Contraseña: password

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo LICENSE para más detalles.

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo LICENSE para más detalles.
