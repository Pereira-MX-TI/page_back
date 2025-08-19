### Sí se agrega una nueva variable de entorno realizar lo siguiente:
1. Agregar la variable de entorno en el archivo .env.example
2. Agregar la variable de entorno en el archivo .env
3. Agregar la variable de entorno en el archivo config/env.check.php (solo el nombre de la variable)

### Validar que todas las variables de entorno estén definidas

```
compose run check-env
```
---


## Generate documentations private and public

```
compose run scribe
```
