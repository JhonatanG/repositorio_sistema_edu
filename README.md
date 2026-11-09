# repositorio_sistema_edu

Sistema de gestión escolar en PHP (MVC) con AdminLTE: matrículas, distributivo docente, horarios, asistencia, calificaciones parametrizables (quimestral/trimestral), historial de matrículas, reportes y perfil de usuario.

## Ejecutar con Docker

```bash
docker compose up -d --build
```

- Sistema: http://localhost:8080
- phpMyAdmin: http://localhost:8081

La base `education8.sql` se importa automáticamente la primera vez que se crea el contenedor de la base de datos (estructura + usuario administrador).

## Roles

Administrador, Secretaría, Docente, Representante y Estudiante. Los usuarios de docentes, representantes y estudiantes son la letra D, R o E seguida de la cédula (por ejemplo `D1712345014`); al primer ingreso el sistema pide cambiar la contraseña.
