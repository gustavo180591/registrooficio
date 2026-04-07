# Sistema de Registro de Oficios

Aplicación web desarrollada en Symfony 7.1 para la gestión completa de oficios administrativos.

## 🚀 Características Principales

### Gestión de Oficios
- **CRUD completo** para oficios con seguimiento
- **Subida de documentos** con VichUploaderBundle
- **Búsqueda avanzada** y filtrado
- **Asignación** de responsables y destinatarios

### Sistema de Usuarios y Seguridad
- **Autenticación** de usuarios
- **Roles y permisos** configurables
- **Sesiones seguras** con Symfony Security

### Gestión de Registros
- **Registro detallado** de comunicaciones
- **Comentarios** y recomendaciones
- **Seguimiento** de estados
- **Delegaciones** organizativas

### Funcionalidades Adicionales
- **Notificaciones por email** integradas
- **Interfaz responsive** con Twig y Bootstrap
- **Exportación** de datos
- **Sistema de comentarios** colaborativo

## 🛠️ Stack Tecnológico

- **Backend**: Symfony 7.1
- **Base de datos**: MySQL 8.0 con Doctrine ORM
- **Frontend**: Twig, Bootstrap, Stimulus, Turbo
- **Subida de archivos**: VichUploaderBundle
- **Tests**: PHPUnit
- **Contenerización**: Docker

## 📋 Requisitos

- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Docker (opcional)

## 🚀 Instalación

### 1. Clonar el repositorio
```bash
git clone <repository-url>
cd registrooficio
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar base de datos
```bash
# Copiar archivo de entorno
cp .env .env.local

# Configurar credenciales en .env.local
DATABASE_URL="mysql://usuario:password@127.0.0.1:3306/oficio?serverVersion=8.0"
```

### 4. Crear base de datos y ejecutar migraciones
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Iniciar servidor de desarrollo
```bash
php bin/console server:start
```

## 🐳 Docker

### Desarrollo
```bash
docker-compose up -d
```

### Producción
```bash
docker-compose -f compose.override.yaml up -d
```

## 📁 Estructura del Proyecto

```
src/
├── Controller/          # Controladores CRUD
│   ├── OficioController.php
│   ├── RegistroController.php
│   ├── SecurityController.php
│   └── ...
├── Entity/             # Entidades Doctrine
│   ├── Oficio.php
│   ├── Registro.php
│   ├── User.php
│   └── ...
├── Form/               # Formularios Symfony
└── Repository/         # Repositorios personalizados
```

## 🔧 Comandos Útiles

### Gestión de base de datos
```bash
# Crear nueva migración
php bin/console make:migration

# Ejecutar migraciones
php bin/console doctrine:migrations:migrate

# Validar esquema
php bin/console doctrine:schema:validate
```

### Desarrollo
```bash
# Limpiar caché
php bin/console cache:clear

# Instalar assets
php bin/console assets:install

# Ejecutar tests
php bin/phpunit
```

## 📊 Entidades Principales

- **Oficio**: Oficios principales con seguimiento
- **Registro**: Registros detallados de comunicaciones  
- **User**: Usuarios del sistema
- **Delegacion**: Unidades organizativas
- **Comment**: Comentarios colaborativos
- **Recomendacion**: Recomendaciones de mejora

## 🔐 Configuración de Seguridad

El sistema incluye:
- Autenticación basada en formularios
- Encriptación de contraseñas
- Protección CSRF
- Validación de entradas

## 📧 Configuración de Email

Configurar en `.env.local`:
```bash
MAILER_DSN="smtp://usuario:password@servidor:puerto"
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
php bin/phpunit

# Ejecutar tests con cobertura
php bin/phpunit --coverage-html coverage
```

## 📝 Licencia

Proprietary - Todos los derechos reservados

## 🤝 Contribución

Para contribuir al proyecto:
1. Crear una rama feature
2. Realizar los cambios
3. Ejecutar tests
4. Enviar Pull Request

---

**Desarrollado con Symfony 7.1** ❤️
