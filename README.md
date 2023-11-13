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

### Export Content

##### Taxonomies
```
drush dcer taxonomy_term --bundles=archive,articulation_cover,articulation_system,attach_sewing_support_folder,bifolios_notebook,binders,book_body_composition,book_subcategory,book_type,brooch_typology,closure_typology,color,completeness,cover_decoration,cursus,cut,decoration_type,document_type,document_validation,dyestuff,edge_treatments,finial,folder_articulation,font_type,format,guards_registration,guards_typology,identification_method,label_brand_typology,leading_orientation,liturgical_occasion,loads,location,locations,location_seam_attach_gutter,material,materiality_book_body,materiality_shape,materiality_support,music,music_notation,nature,parties,pebbles,pigment,regularity,tanning,text_language,thread_season,tool_brands,tradition,tranchephil_typology,typology_according_loop,typology_according_number_books,typology_according_route,typology_sewing_supports,writing_type --folder=modules/custom/sb_content/content/
```

##### Nodes
```
drush dcer node --folder=modules/custom/sb_content/content/
```
