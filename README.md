MINISTERIO DE AMBIENTE Y DESARROLLO SOSTENIBLE
==============================================

Administración de Información Aplicación móvil Red Posconsumo
-------------------------------------------------------------

Como instalar este proyecto
---------------------------

  1. `git clone git@bitbucket.org:minambiente/mads_posconsumos.git`
  1. `cd mads_posconsumos`
  1. `composer install`
  1. Editar `mads_posconsumos/app/config/parameters.yml` y configurar
     las credenciales para el acceso a la base de datos.
  1. `php bin/console doctrine:schema:create`
  1. `php bin/console doctrine:fixtures:load --append`
  1. `php bin/console assets:install --symlink`

Para leer la información y registro de versiones [Click Aquí](./app/Resources/docs/CHANGELOG.md)

Diseño y desarrollo
David Alméciga [walmeciga@minambiente.gov.co](mailto:walmeciga@minambiente.gov.co)
