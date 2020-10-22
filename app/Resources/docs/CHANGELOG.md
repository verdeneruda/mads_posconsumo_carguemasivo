## 1.3 (2016-12-7)

**Funcionalidades:**

  - Se genera nuevo módulo de reportes que permite:
  - Crear, Modificar y Eliminar Reportes personalizados
  - Seleccionar tipo reporte entre Campañas, Puntos de recolección, Residuos y Programas
  - Seleccionar las columnas para la salida del reporte
  - Agregar, Quitar filtros a la entidad
  - Exportar información propia para cada programa sengún el rol del usuario
  - Exportar toda la información por el Administrador
  - Generar la salida en archivo Microsoft Excel

## 1.2.1 (2016-09-21)

**Funcionalidades:**

  - Se configura el API RestFull basado en Standard HYDRA.
  - Se configura la documentación del API en http://redposconsumo.minambiente.gov.co/api/doc
  - Se incorpora la entidad AppActivity para registrar toda la actividad de los usuarios desde la App.
  - Se agregan en API filtros para entidades `Campañas`, `Puntos de Recolección`, `Tipos Residuos`

## 1.2.0 (2016-09-18)

**Funcionalidades:**

  - Se mejoran vistas en formularios organizados por grupos para mejor entendimiento.
  - Se agrega notificación en la creación de una campaña a contactos asociados a la nueva campaña.
  - Se mejoran mensajes de ayuda para mejor entendimiento.
  - Se mejoran validaciones en todos los formularios para evitar el ingreso de información incorrecta.
  - Se modifican términos de entidades en el desarrollo, según lo acordado. Organizaciones por `Programas`, Categorías por `Tipos Residuos`, Usuarios por `Contactos`, Tipo Residuo por `Residuo`.

**Correciones:**

  - Para la creación de un punto de recolección se agrega tipo de punto de recolección y se valida según el tipo de punto de recolección `Punto de Recolección, son puntos fijos con un horario de disponibilidad` y `Punto de ruta, son puntos en la que se programa la recolección en una fecha y hora`.
  - Se corrige el bug de la edición de puntos de recolección y programas que no actualizaba la georeferenciación.
  - Se corrigen usuarios y contactos asociados por punto de recolección, que pertenezcan al mismo programa y contactos asociados a una campaña que pueden ser de otros programas.
  - Se corrigen errores en la modificación de un Residuo.

## 1.1.0 (2016-08-26)

**Funcionalidades:**
  
  - El Rol Usuario y Administrador, pueden crear campañas de tipos `Único punto de recolección`, `Mútiples puntos de recolección` y `Ruta`.
  - El Rol Usuario y Administrador, pueden asociar varios Usuarios o Contactos de Programas Posconsumo a una Campaña.
  - El Rol Usuario y Administrador, pueden cargar una imagen JPEG, horizontal (landscape) con relación 4:3, ancho mínimo: 640px y alto mínimo: 480px para la creación de un nuevo Residuo.
  - El Rol Administrador, al crear un nuevo contacto, envía automáticamente un email de bienvenida con su respectiva asignación de usuario y contraseña en el sistema.
  - El Rol Administrador, puede personalizar los correos de bienvenida y notificación al usuario del programa asociado a una nueva campaña. En `Plantillas Email`.
  - El Rol Usuario puede editar su perfil y modificar su contraseña.
  - Página de error de permisos.
  - Permisos por ACL para evitar editar, eliminar información entre los distintos programas.

**Para hacer (TO DO):**
  
  - Para puntos de recolección en la Campaña, se debe validar según el tipo de puntos de recoleccón seleccionado.
  - **Importante!** En la modificación de un punto de recolección o un programa, no actualiza el punto de georeferenciación. Se inhabilita la modificación temporalmente.

---

## 1.0.0 (2016-07-08)

**Funcionalidades:**

  - Existen 2 ROLES Principales en el Backend y son el Rol Administrador y el Usuario o responsable de un programa posconsumo.
  - El Rol Administrador puede ver, listar, crear, modificar y/o eliminar los tipos de residuos.
  - El Rol Administrador puede ver, listar, crear, modificar y/o eliminar los Programas o Organizaciones pertenecientes a la Red Posconsumo.
  - El Rol Administrador puede ver, listar, crear, modificar y/o eliminar los Usuarios o Contactos responsables de un programa posconsumo.
  - El Rol Administrador puede ver, listar, crear, modificar y/o eliminar los Canales a quienes se enfocan los puntos de recolección.
  - El Rol Usuario y Administrador pueden ver, listar, crear, modificar y/o eliminar los residuos pertenecientes a los programas posconsumo.
  - El Rol Usuario y Administrador pueden ver, listar, crear, modificar y/o eliminar los puntos de recolección debidamente georeferenciados.
  - El Rol Usuario y Administrador pueden ver, listar, crear, modificar y/o eliminar las campañas debidamente programadas.