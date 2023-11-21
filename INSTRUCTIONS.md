# fontes-históricas
Perfil Histórico

### Instructions
###
## DDEV
Teste
Perfil Histórico can be installed locally using [DDEV](https://ddev.com/).
* Download and install [DDEV](https://github.com/drud/ddev)
* Start ddev:
```
ddev start
```
* Run composer install:
```
ddev composer install
```
* You may be prompted to add your GitHub token. Follow the on-screen instructions for public repositories.
* Launch your new ddev project:
```
ddev launch
```
* Create .env file with:
```
DRUPAL_BASE_URL=http://project-name.ddev.site
DRUSH_VERBOSE=no
DRUSH_OPTIONS_URI=http://project-name.ddev.site
```
* First config import is recommended to be done via drush by running:
```
ddev exec drush site:install monasteries --existing-config
```
* Quick tip: generating a hash salt for Drupal
```
ddev exec drush php-eval 'echo \Drupal\Component\Utility\Crypt::hashBase64(55) . "\n";'
```
* Export updated configurations:
```
ddev exec drush cex
```
* Import configurations:
```
ddev exec drush cim
```
