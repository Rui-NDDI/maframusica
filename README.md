# mosteiros
Mosteiro Lorvão, Alcobaça e Arouca (Perfil Histórico)

## Instructions

### First config import is recommended to be done via drush by running:
```
drush site:install monasteries --existing-config
```

### Quick tip: generating a hash salt for Drupal
```
drush php-eval 'echo \Drupal\Component\Utility\Crypt::hashBase64(55) . "\n";'
```
