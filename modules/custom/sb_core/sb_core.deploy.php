<?php

/**
 * @file
 * Deploy functions for Squarebit.
 *
 * This should only contain update functions that rely on the Drupal API and
 * need to run _after_ the configuration is imported.
 *
 * This is applicable in most cases. However, in case the update code enables
 * some functionality that is required for configuration to be successfully
 * imported, it should instead be placed in sb_core.post_update.php.
 */

declare(strict_types = 1);

use Drupal\node\Entity\Node;

/**
 * Install the SB content module.
 */
function sb_core_deploy_108000(): void {
  /** @var \Drupal\Core\Extension\ModuleInstallerInterface $module_installer */
  $module_installer = \Drupal::service('module_installer');

  $module_installer->install(['sb_content']);
  $module_installer->uninstall(['sb_content']);
}

/**
 * Feed archive taxonomy.
 */
function sb_core_deploy_108001(): void {
  $archives = [
    ["E-BUlh", "Burgos", "E-BUlh (Burgos) Monasterio de Las Huelgas"],
    ["E-SAu", "Salamanca", "E-SAu (Salamanca) Universidad, Archivo y Biblioteca"],
    ["E-Sco", "Sevilla", "E-Sco (Sevilla) Institución Colombina"],
    ["E-TUY", "Tuy", "E-TUY (Tuy) Archivo Capitular de la Catedral de Tuy"],
    ["E-V", "Valladolid", "E-V (Valladolid) Archivo Musical de la Catedral de Valladolid"],
    ["E-Vp", "Valladolid", "E-Vp (Valladolid) Parroquia de Santiago, Archivo"],
    ["E-ZAahp", "Zamora", "E-ZAahp Archivo Histórico Provincial de Zamora"],
    ["P-AN", "Angra do Heroísmo", "P-AN (Angra do Heroísmo) Biblioteca Pública e Arquivo Distrital"],
    ["P-AR", "Arouca", "P-AR (Arouca) Museu Regional de Arte Sacra do Mosteiro de Arouca"],
    ["P-ARRam", "Arraiolos", "P-ARRam (Arraiolos) Arquivo Histórico e Municipal"],
    ["P-ARRsc", "Arraiolos", "P-ARRsc (Arraiolos) Santa Casa da Misericórdia, Arquivo"],
    ["P-AV", "Aveiro", "P-AV (Aveiro) Museu de Aveiro, Mosteiro de Jesus"],
    ["P-AVad", "Aveiro", "P-AVad (Aveiro) Arquivo Distrital"],
    ["P-AVub", "Aveiro", "P-AVub (Aveiro) Serviços de Documentação, Biblioteca"],
    ["P-AZsc", "Azurara", "P-AZsc (Azurara) Santa Casa da Misericórdia, Arquivo"],
    ["P-BA", "Barreiro", "P-BA (Barreiro) Biblioteca Municipal"],
    ["P-BÇad", "Bragança", "P-BÇad (Bragança) Arquivo Distrital"],
    ["P-BJad", "Beja", "P-BJad (Beja) Arquivo Distrital"],
    ["P-BRad", "Braga", "P-BRad (Braga) Arquivo Distrital"],
    ["P-BRam", "Braga", "P-BRam (Braga) Arquivo Municipal"],
    ["P-BRic", "Braga", "P-BRic (Braga) Irmandade de Santa Cruz"],
    ["P-BRp", "Braga", "P-BRp (Braga) Biblioteca Pública"],
    ["P-BRs", "Braga", "P-BRs (Braga) Arquivo da Sé"],
    ["P-BRsc", "Braga", "P-BRsc (Braga) Seminário Conciliar"],
    ["P-BRu", "Braga", "P-BRu (Braga) Universidade do Minho, Biblioteca"],
    ["P-CA", "Cascais", "P-CA (Cascais) Museu-Biblioteca Condes de Castro Guimarães"],
    ["P-CAmm", "Cascais", "P-CAmm (Cascais) Museu da Música Regional Portuguesa"],
    ["P-CB", "Castelo Branco", "P-CB (Castelo Branco) Arquivo da Sé"],
    ["P-CBad", "Castelo Branco", "P-CBad (Castelo Branco) Arquivo Distrital"],
    ["P-Cm", "Coimbra", "P-Cm (Coimbra) Biblioteca Municipal"],
    ["P-Cmn", "Coimbra", "P-Cmn (Coimbra) Museu Nacional de Machado de Castro"],
    ["P-Cs", "Coimbra", "P-Cs (Coimbra) Arquivo da Sé Nova"],
    ["P-Csc", "Coimbra", "P-Csc (Coimbra) Santa Casa da Misericórdia, Arquivo"],
    ["P-Cua", "Coimbra", "P-Cua (Coimbra) Arquivo Distrital e da Universidade"],
    ["P-Cuc", "Coimbra", "P-Cuc (Coimbra) Universidade de Coimbra, Capela"],
    ["P-Cug", "Coimbra", "P-Cug (Coimbra) Biblioteca Geral da Universidade"],
    ["P-Cul", "Coimbra", "P-Cul (Coimbra) Faculdade de Letras da Universidade"],
    ["P-Em", "Elvas", "P-Em (Elvas) Biblioteca Municipal e Arquivo Municipal"],
    ["P-Es", "Elvas", "P-Es (Elvas) Arquivo da Sé"],
    ["P-ESam", "Estremoz", "P-ESam (Estremoz) Arquivo Municipal"],
    ["P-ESsc", "Estremoz", "P-ESsc (Estremoz) Santa Casa da Misericórdia, Arquivo"],
    ["P-EVad", "Évora", "P-EVad (Évora) Arquivo Distrital"],
    ["P-EVc", "Évora", "P-EVc (Évora) Arquivo da Sé"],
    ["P-EVm", "Évora", "P-EVm (Évora) Museu de Évora"],
    ["P-EVp", "Évora", "P-EVp (Évora) Biblioteca Pública"],
    ["P-EVpc", "Évora", "P-EVpc (Évora) Palácio Duques do Cadaval"],
    ["P-EVu", "Évora", "P-EVu (Évora) Universidade de Évora, Biblioteca Geral"],
    ["P-F", "Figueira da Foz", "P-F (Figueira da Foz) Biblioteca Pública Municipal Pedro Fernandes Tomas"],
    ["P-FAad", "Faro", "P-FAad (Faro) Arquivo Distrital"],
    ["P-FAs", "Faro", "P-FAs (Faro) Seminario Episcopal de S. José do Algarve"],
    ["P-G", "Guimarães", "P-G (Guimarães) Arquivo Municipal Alfredo Pimenta"],
    ["P-GDad", "Guarda", "P-GDad (Guarda) Arquivo Distrital"],
    ["P-Gm", "Guimarães", "P-Gm (Guimarães) Biblioteca Municipal Raul Brandão"],
    ["P-Gmas", "Guimarães", "P-Gmas (Guimarães) Museu de Alberto Sampaio"],
    ["P-Gms", "Guimarães", "P-Gms (Guimarães) Sociedade Martins Sarmento"],
    ["P-Gsc", "Guimarães", "P-Gsc (Guimarães) Santa Casa da Misericórdia, Arquivo"],
    ["P-LA", "Lisboa", "P-La (Lisboa) Biblioteca do Palacio Nacional da Ajuda"],
    ["P-LA", "Lamego", "P-LA (Lamego) Arquivo da Sé"],
    ["P-Laa", "Lisboa", "P-Laa (Lisboa) Academia dos Amadores de Música"],
    ["P-Lac", "Lisboa", "P-Lac (Lisboa) Academia das Ciências, Biblioteca"],
    ["P-Lahm", "Lisboa", "Arquivo Histórico Militar"],
    ["P-LAmad", "Lamego", "P-LAmad (Lamego) Museu e Arquivo Diocesanos"],
    ["P-LAml", "Lamego", "P-LAml (Lamego) Museu de Lamego "],
    ["P-Lant", "Lisboa", "P-Lant (Lisboa) Arquivo Nacional da Torre do Tombo"],
    ["P-LApe", "Lamego", "P-LApe (Lamego) Palácio Episcopal"],
    ["P-Lc", "Lisboa", "P-Lc (Lisboa) Escola de Música do Conservatório Nacional, Biblioteca"],
    ["P-Lcg", "Lisboa", "P-Lcg (Lisboa) Fundação Calouste Gulbenkian"],
    ["P-LE", "Leiria", "P-LE (Leiria) Arquivo Distrital"],
    ["P-LEm", "Leiria", "P-LEm (Leiria) Biblioteca Municipal Afonso Lopes Vieira"],
    ["P-Lf", "Lisboa", "P-Lf (Lisboa) Arquivo da Fabrica da Sé Patriarcal"],
    ["P-Lh", "Lisboa", "P-Lh (Lisboa) Hemeroteca"],
    ["P-Lif", "Lisboa", "P-Lif (Lisboa) Institut Franco-Portuguais"],
    ["P-Lim", "Lisboa", "P-Lim (Lisboa) Igreja das Mercês"],
    ["P-Lm", "Lisboa", "P-Lm (Lisboa) Biblioteca Municipal"],
    ["P-Lma", "Lisboa", "P-Lma (Lisboa) Museu de Arqueologia, Biblioteca"],
    ["P-Lmm", "Lisboa", "P-Lmm (Lisboa) Museu da Música"],
    ["P-Lmnaa", "Lisboa", "P-Lmnaa (Lisboa) Museu Nacional de Arte Antiga"],
    ["P-Ln", "Lisboa", "P-Ln (Lisboa) Biblioteca Nacional de Portugal"],
    ["P-Lo", "Lisboa", "P-Lo (Lisboa) Seminario dos Olivais, Biblioteca"],
    ["P-LOam", "Loulé", "Arquivo Municipal de Loulé"],
    ["P-Lr", "Lisboa", "P-Lr (Lisboa) Radiodifusao Portuguesa"],
    ["P-Ls", "Lisboa", "P-Ls (Lisboa) Sociedade Portuguesa de Autores"],
    ["P-Lscm", "Lisboa", "P-Lscm (Lisboa) Santa Casa da Misericórdia, Arquivo Histórico/Biblioteca"],
    ["P-Lt", "Lisboa", "P-Lt (Lisboa) Teatro Nacional de S. Carlos"],
    ["P-Lu", "Lisboa", "P-Lu (Lisboa) Universidade Nova, FCSH, Serviços de Informação e Documentação, Biblioteca Geral"],
    ["P-Luc", "Lisboa", "P-Luc (Lisboa) Universidade Nova, FCSH, Centro de Estudos de Sociologia e Estética Musical (CESEM)"],
    ["P-Lue", "Lisboa", "P-Lue (Lisboa) Universidade Nova, FCSH, Instituto de Etnomusicologia (INET), Biblioteca e Arquivo Sonoro"],
    ["P-Lum", "Lisboa", "P-Lum (Lisboa) Universidade Nova, FCSH, Biblioteca do Departamento de Ciências Musicais"],
    ["P-MNam", "Montemor-o-Novo", "P-MNam (Montemor-o-Novo) Arquivo Municipal"],
    ["P-MOam", "Monção", "P-MOam (Monção) Arquivo Municipal"],
    ["P-MONam", "Torre de Moncorvo", "P-MONam (Torre de Moncorvo) Arquivo Municipal"],
    ["P-Mp", "Mafra", "P-Mp (Mafra) Palacio Nacional de Mafra, Biblioteca"],
    ["P-MRahm", "Moura", "P-MRahm (Moura) Arquivo Histórico Municipal"],
    ["P-Op", "Óbidos", "P-Op (Óbidos) Igreja de S. Pedro"],
    ["P-Pa", "Porto", "P-Pa (Porto) Ateneu Comercial"],
    ["P-Pad", "Porto", "P-Pad (Porto) Arquivo Distrital"],
    ["P-Pc", "Porto", "P-Pc (Porto) Conservatório de Música"],
    ["P-PD", "Ponta Delgada", "P-PD (Ponta Delgada) Biblioteca Pública e Arquivo Distrital"],
    ["P-Peh", "Porto", "P-Peh (Porto) Museu de Etnografia e História"],
    ["P-Pf", "Porto", "P-Pf (Porto) Club dos Fenianos Portuenses"],
    ["P-PL", "Ponte de Lima", "P-PL (Ponte de Lima) Santa Casa da Misericórdia, Arquivo"],
    ["P-PLam", "Ponte de Lima", "P-PLam (Ponte de Lima) Arquivo Municipal"],
    ["P-PLmt", "Ponte de Lima", "P-PLmt (Ponte de Lima) Museu dos Terceiros"],
    ["P-Pm", "Porto", "P-Pm (Porto) Biblioteca Municipal"],
    ["P-PO", "Portalegre", "P-PO (Portalegre) Arquivo da Sé"],
    ["P-POad", "Portalegre", "P-POad (Portalegre) Arquivo Distrital"],
    ["P-POm", "Portalegre", "P-POm (Portalegre) Biblioteca Municipal"],
    ["P-Puc", "Porto", "P-Puc (Porto) Universidade Católica"],
    ["P-Pul", "Porto", "P-Pul (Porto) Faculdade de Letras"],
    ["P-PVam", "Póvoa de Varzim", "P-PVam (Póvoa de Varzim) Arquivo Municipal"],
    ["P-SAad", "Santarém", "P-SAad (Santarém) Arquivo Distrital"],
    ["P-SAscm", "Santarém", "P-SAscm (Santarém) Santa Casa da Misericórdia"],
    ["P-SEad", "Setúbal", "P-SEad (Setúbal) Arquivo Distrital"],
    ["P-Tcc", "Tomar", "P-Tcc (Tomar) Convento de Cristo"],
    ["P-Va", "Viseu", "P-Va (Viseu) Arquivo Distrital"],
    ["P-VAam", "Valença", "P-VAam (Valença) Arquivo Municipal"],
    ["P-Vbm", "Viseu", "P-Vbm (Viseu) Biblioteca Municipal"],
    ["P-VCad", "Viana do Castelo", "P-VCad (Viana do Castelo) Arquivo Distrital"],
    ["P-VCam", "Viana do Castelo", "P-VCam (Viana do Castelo) Arquivo Municipal"],
    ["P-VCDam", "Vila do Conde", "P-VCDam (Vila do Conde) Arquivo Municipal"],
    ["P-VCDsc", "Vila do Conde", "P-VCDsc (Vila do Conde) Santa Casa da Misericórdia, Arquivo"],
    ["P-VIsc", "Vimieiro", "P-VIsc (Vimieiro) Santa Casa da Misericórdia, Arquivo"],
    ["P-Vm", "Viseu", "P-Vm (Viseu) Museu Grão Vasco"],
    ["P-VNCam", "Vila Nova de Cerveira", "P-VNCam (Vila Nova de Cerveira) Arquivo Municipal"],
    ["P-VRad", "Vila Real", "P-VRad (Vila Real) Arquivo Distrital"],
    ["P-Vs", "Viseu", "P-Vs (Viseu) Arquivo da Sé"],
    ["P-VV", "Vila Viçosa", "P-VV (Vila Viçosa) Biblioteca do Palácio Real"],
    ["US-PHf", "Philadelphia", "US-PHf (Philadelphia) Free Library of Philadelphia, Music Department"],
  ];

  foreach ($archives as $archive) {
    $city = \Drupal::entityTypeManager()->getStorage("taxonomy_term")->loadByProperties(["name" => $archive[1], "vid" => 'city']);

    if (empty($city)) {
      $city = \Drupal::entityTypeManager()->getStorage("taxonomy_term")->create([
        'vid' => 'city',
        'name' => $archive[1],
      ]);

      $city->save();
    }
    else {
      $city_arr = array_values($city);
      $city = $city_arr[0];
    }

    $term = \Drupal::entityTypeManager()->getStorage("taxonomy_term")->create([
      'vid' => 'archive',
      'name' => $archive[2],
      'field_city' => ['target_id' => $city->id()],
      'field_rism' => $archive[0],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
  }
}

/**
 * Import feasts nodes.
 */
function sb_core_deploy_108002(): void {
  $feasts = [
    ["- indeterminate -", "", "", ""],
    ["- none -", "", "", ""],
    ["Abdonis, Sennis", "Abdon and Sennen, Martyrs", "14073000", "Jul.30"],
    ["Ad Aquam Benedictio", "Chants for the blessing of water", "16010000", ""],
    [
      "Ad Aspersionem Aquae benedictae",
      "For the sprinkling of Holy Water",
      "16023000", "",
    ],
    [
      "Ad Aspersionem Aquae benedictae TP",
      "For the sprinkling of Holy Water in Eastertide",
      "16023080", "",
    ],
    ["Ad lavandum altaria", "Chants for the washing of the altar", "16024000", ""],
    ["Ad Mandatum", "At the Mandatum (Foot-Washing)", "7065010", ""],
    ["Ad Processionem", "For Processions", "16009000", ""],
    ["Adalberti", "Adalbert of Prague, Bishop and Martyr", "14042310", "Apr.23"],
    ["Additamenta", "Added or Miscellaneous Items", "17001000", ""],
    ["Aegidii", "Aegidius (Giles), Abbot", "14090100", "Sep.1"],
    [
      "Aemigdii, Episc. et Martyr",
      "Emygdius (Emidius), Bishop Martyr",
      "14080530", "Aug.5",
    ],
    ["Agapiti", "Agapitus, Martyr", "14081800", "Aug.18"],
    ["Agathae", "Agatha, Virgin Martyr", "14020500", "Feb.5"],
    ["Agnetis", "Agnes, Virgin Martyr", "14012100", "Jan.21"],
    ["Agnetis,8", "In week after Agnes", "14012108", ""],
    ["Alexandri et sociorum", "Alexander and Eventius, Martyrs", "14050310", "May.3"],
    ["Alexis", "Alexis, the Man of God", "14071700", "Jul.17"],
    [
      "Aloisii Gonzagae",
      "Aloysius (Luigi) Gonzaga, Confessor, Patron of youthful Catholic students.",
      "14062120", "Jun.21",
    ],
    ["Ambrosii", "Ambrose, Bishop of Milan, Doctor", "14040400", "Apr.4"],
    ["Ambrosii TP", "Ambrose, Eastertide", "14040480", "Apr. 4th ù"],
    ["Andreae", "Andrew, Apostle", "14113000", "Nov.30"],
    ["Angeli Custodis", "For Guardian Angels", "14100220", "Oct.2"],
    ["Annae", "Anne, Mother of Mary", "14072610", "Jul.26"],
    ["Annuntiatio Mariae", "Annunciation of Mary (Lady Day)", "14032500", "Mar.25"],
    ["Antiphonae Majores", "Great 'O' Antiphons", "1048010", ""],
    ["Antonii", "Antony, Abbot", "14011700", "Jan.17"],
    ["Antonii Patavini", "Anthony of Padua, Doctor", "14061300", "Jun.13"],
    ["Antonini", "Antoninus, Martyr", "14090200", "Sep.2"],
    ["Apollinaris", "Apollinaris, Bishop of Ravenna", "14072320", "Jul.23"],
    ["Apolloniae", "Apollonia, Virgin Martyr", "14020900", "Feb.9"],
    ["Appar. Michaelis", "Appearing of Michael the Archangel", "14050800", "May.8"],
    ["Ascensio Domini", "Ascension Thursday", "8065000", ""],
    ["Ascensio Domini,8", "In week after Ascension", "8065008", ""],
    ["Ascensionis Domini, in vigilia", "Eve of Ascension", "8064001", ""],
    ["Assumptio Mariae", "Assumption of Mary", "14081500", "Aug.15"],
    ["Assumptio Mariae,8", "In week after Assumption of Mary", "14081508", ""],
    ["Athanasii", "Athanasius, Archbishop of Alexandria", "14050200", "May.2"],
    ["Augustini", "Augustine, Bishop and Doctor", "14082800", "Aug.28"],
    [
      "Augustini, conv.",
      "Conversion of Augustine, Bishop and Doctor",
      "14050510", "May.5",
    ],
    [
      "Austremonii",
      "Austremonius (Stremoine), Bishop, Martyr, Apostle of Auvergne",
      "14110160", "Nov.1",
    ],
    ["Barnabae", "Barnabas, Apostle", "14061100", "Jun.11"],
    ["Bartholomaei", "Bartholomew, Apostle", "14082400", "Aug.24"],
    [
      "Basilidis et sociorum",
      "Basilides and companions (Cyrinus, Nabor, and Nazarius), Martyrs",
      "14061200", "Jun.12",
    ],
    ["Benedicti", "Benedict, Abbot", "14032100", "Mar.21"],
    ["Bernardi", "Bernard, Abbot and Doctor", "14082010", "Aug.20"],
    ["Bernardi,8", "In week after Bernard", "14082018", ""],
    [
      "Bernardini Senensis",
      "Bernardinus degl' Albizzeschi of Siena, Confessor",
      "14052010", "May.20",
    ],
    ["Blasii", "Blaise, Bishop of Sebastea, Martyr", "14020300", "Feb.3"],
    ["BMV de Monte Carmelo", "Our Lady of Mount Carmel", "14071610", "Jul.16"],
    ["Briccii", "Brice, Bishop of Tours", "14111300", "Nov.13"],
    [
      "Brunonis Abbatis",
      "Bruno, Abbot, Founder of the Carthusian Order",
      "14100610", "Oct.6",
    ],
    ["Caeciliae", "Cecilia (Cecily), Virgin Martyr", "14112200", "Nov.22"],
    ["Caesarii Arelatensis", "Caesarius, Archbishop of Arles", "14082700", "Aug.27"],
    ["Callisti", "Callistus (Calixtus) I, Pope", "14101400", "Oct.14"],
    [
      "Camilli de Lellis",
      "Camillus de Lellis, Confessor, Founder of the Canons Regular of a Good Death (Infirmis Ministrantium)",
      "14071820", "Jul.18",
    ],
    ["Cant. Canticorum Adv.", "", "xxx", ""],
    ["Cant. Canticorum p.Ep.", "", "xxx", ""],
    ["Cantica Canticorum", "", "xxx", ""],
    ["Caprasii, Abb.", "Caprasius, Abbot of Lerins", "14060110", "Jun.1"],
    ["Catharinae", "Catharine of Alexandria, Martyr", "14112500", "Nov.25"],
    ["Cathedra Petri", "Peter's Chair", "14022200", "Feb.22"],
    ["Christophori", "Christopher, Martyr", "14072510", "Jul.25"],
    ["Chrysanthi, Dariae", "Chrysanthus, Daria, Maurus, Martyrs", "14112910", "Nov.29"],
    ["Chrysogoni", "Chrysogonus, Martyr", "14112400", "Nov.24"],
    ["Circumcisio Domini", "The circumcision of Christ", "2010110", "Jan.1"],
    ["Clarae", "Clare of Assisi, Foundress of the Poor Clares", "14081200", "Aug.12"],
    ["Clementis", "Clement I, Pope and Martyr", "14112300", "Nov.23"],
    ["Columbae", "Columba of Sens, Virgin Martyr", "14123110", "Dec.31"],
    ["Comm. apostolibus et martiribus", "", "", ""],
    ["Comm. Apostolorum", "Common of Apostles", "12001000", ""],
    [
      "Comm. Apostolorum et Evangelistarum",
      "Common of Apostles and Evangelists",
      "12019000", "",
    ],
    [
      "Comm. Apostolorum sive Martyrum TP",
      "Common of Apostles or Martyrs, Eastertide",
      "12801100", "",
    ],
    ["Comm. Apostolorum TP", "Common of Apostles, Eastertide", "12801000", ""],
    ["Comm. Apostolorum,8", "Common of Apostles, in week of", "12001008", ""],
    ["Comm. Conjungium", "Common of Holy Matrons", "12012000", ""],
    ["Comm. duorum Apostolorum", "Common of two Apostles", "12001200", ""],
    ["Comm. Evangelistarum", "Common of Evangelists", "12011000", ""],
    [
      "Comm. Evangelistarum TP",
      "Common of Evangelists, Eastertide",
      "12811000", "",
    ],
    ["Comm. plurimorum Apostolorum in vigilia", "Eve of Apostles", "12001010", ""],
    [
      "Comm. plurimorum Confessorum",
      "Common of several Confessors",
      "12005000", "",
    ],
    [
      "Comm. plurimorum Confessorum non Pontificum",
      "Common of several Confessors (not Popes)",
      "12005200", "",
    ],
    [
      "Comm. plurimorum Confessorum Pontificum",
      "Common of several Confessors (Popes)",
      "12005100", "",
    ],
    ["Comm. plurimorum Martyrum", "Common of several Martyrs", "12003000", ""],
    [
      "Comm. plurimorum Martyrum TP",
      "Common of several Martyrs, Eastertide",
      "12803000", "",
    ],
    ["Comm. plurimorum Virginum", "Common of several Virgins", "12006000", ""],
    ["Comm. unius Abbatis", "Common of one Abbot", "12010000", ""],
    ["Comm. unius Apostoli", "Common of one Apostle", "12001100", ""],
    ["Comm. unius Apostoli in vigilia", "Eve of one Apostle", "12001110", ""],
    ["Comm. unius Confessoris", "Common of one Confessor", "12004000", ""],
    [
      "Comm. unius Confessoris Abbatis",
      "Common of one Confessor (Abbot)",
      "12004200", "",
    ],
    [
      "Comm. unius Confessoris et Doctoris",
      "Common of one Confessor (Doctor)",
      "12004500", "",
    ],
    [
      "Comm. unius Confessoris et Episcopi",
      "Common of one Confessor (Bishop)",
      "12004300", "",
    ],
    [
      "Comm. unius Confessoris non Episcopus",
      "Common of one Confessor (not Bishop)",
      "12004400", "",
    ],
    [
      "Comm. unius Confessoris non Pontificis",
      "Common of one Confessor (not Pope)",
      "12004700", "",
    ],
    [
      "Comm. unius Confessoris non Pontificis TP",
      "Common of one Confessor (not Pope), Eastertide",
      "12804700", "",
    ],
    [
      "Comm. unius Confessoris Pontificis",
      "Common of one Confessor (Pope)",
      "12004100", "",
    ],
    [
      "Comm. unius Confessoris Pontificis TP",
      "Common of one Confessor (Pope), Eastertide",
      "12804100", "",
    ],
    [
      "Comm. unius Confessoris TP",
      "Common of one Confessor, Eastertide",
      "12804000", "",
    ],
    [
      "Comm. unius electae",
      "Common of those chosen (not Virgins, not Martyrs)",
      "12022000", "",
    ],
    ["Comm. unius Martyris", "Common of one Martyr", "12002000", ""],
    [
      "Comm. unius Martyris non Pontificis",
      "Common of one Martyr (not Pope)",
      "12002200", "",
    ],
    [
      "Comm. unius Martyris non Virginis",
      "Common of one Martyr (not Virgin)",
      "12002300", "",
    ],
    [
      "Comm. unius Martyris Pontificis",
      "Common of one Martyr (Pope)",
      "12002100", "",
    ],
    ["Comm. unius Martyris TP", "Common of one Martyr, Eastertide", "12802000", ""],
    ["Comm. unius Virginis", "Common of one Virgin", "12007100", ""],
    [
      "Comm. unius Virginis Martyris",
      "Common of one Virgin Martyr",
      "12007000", "",
    ],
    [
      "Comm. unius Virginis non Martyris",
      "Common of one Virgin (not Martyr)",
      "12007200", "",
    ],
    ["Comm. unius Virginis TP", "Common of one Virgin, Eastertide", "12807100", ""],
    ["Conceptio Mariae", "Immaculate Conception of Mary", "14120800", "Dec.8"],
    ["Conversio Pauli", "Conversion of Paul", "14012500", "Jan.25"],
    ["Cornelii, Cypriani", "Cornelius and Cyprian, Martyrs", "14091600", "Sep.16"],
    [
      "Corporis Christi",
      "Corpus Christi (also \"Blessed Sacrament\")",
      "9015000", "",
    ],
    ["Corporis Christi,8", "In week after Corpus Christi", "9015008", ""],
    ["Cosmae, Damiani", "Cosmas and Damian, Martyrs", "14092700", "Sep.27"],
    ["Cyriaci et sociorum", "Cyriacus and companions, Martyrs", "14080800", "Aug.8"],
    ["Cyrici", "Cyricus and Julitta, Martyrs", "14061600", "Jun.16"],
    [
      "De Angelis",
      "Memorial chants for Angels, including e.g. Missa Votiva de Angelis. Feria III.",
      "12013000", "",
    ],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV Adv.", "Votive Mass/Office for Mary, Advent", "15008010", ""],
    ["De BMV Nat.", "Votive Mass/Office for Mary, Christmas", "15008030", ""],
    [
      "De BMV post Epiph.",
      "Votive Mass/Office for Mary, after Epiphany",
      "15008050", "",
    ],
    ["De BMV TP", "Votive Mass/Office for Mary, Eastertide", "15008080", ""],
    ["De caritate", "Chants for charity", "16025000", ""],
    ["De Corona Spinea", "Commemoration of the Crown of Thorns", "14081120", "Aug.11"],
    ["De festis duplicibus", "Chants for feasts of duplex rank", "16016000", ""],
    [
      "De festis duplicibus minoribus",
      "Chants for feasts of duplex minor rank",
      "16016001", "",
    ],
    [
      "De festis semiduplicibus",
      "Chants for feasts of semiduplex rank",
      "16015000", "",
    ],
    ["De festis simplicibus", "Chants for feasts of simple rank", "16039000", ""],
    ["De Job", "Summer Histories, from Job", "10300000", ""],
    ["De Machabaeis", "Summer Histories, from Maccabees", "10800000", ""],
    ["De Prophetis", "Summer Histories, from the Prophets", "10900000", ""],
    ["De Regum", "Summer Histories, from Kings", "10100000", ""],
    [
      "De Sancta Cruce",
      "Votive Mass/Office for the Holy Cross, including e.g. Missa Votiva de Sancta Cruce. Fer. VI.",
      "15011000", "",
    ],
    ["De Sanctis TP", "Common of Saints, Eastertide", "12815000", ""],
    ["De Sapientia", "Summer Histories, from Wisdom", "10200000", ""],
    [
      "De Spiritu Sancto",
      "Votive Mass/Office for the Holy Spirit, including, e.g. Missa Votiva de Spiritu Sancto. Feria V.",
      "15002000", "",
    ],
    ["De Tobia", "Summer Histories, from Tobias", "10400000", ""],
    ["De Trinitate", "Trinity Sunday", "9011000", ""],
    [
      "De victoriae christianorum apud Salado",
      "Commemoration of the victory of the Christians at the Battle of Rio Salado (also known as the Battle of Tarifa), 30 October 1340",
      "14103010", "Oct.30",
    ],
    [
      "Decem Millium Martyrum",
      "Ten Thousand Martyrs (crucified on Mount Ararat)",
      "14062240", "Jun.22",
    ],
    ["Decoll. Jo. Bapt.", "Beheading of John the Baptist", "14082900", "Aug.29"],
    [
      "Die 2 p. Epiphaniam",
      "1st day after Epiphany (2nd day \"of\" Epiphany)",
      "5010700", "Jan.7",
    ],
    ["Die 5 a. Nat. Domini", "The fifth day before Christmas", "2122100", "Dec.21"],
    ["Dionysii", "Denis (Dionysius), Bishop of Paris", "14100900", "Oct.9"],
    ["Dom. 1 Adventus", "1st Sunday of Advent", "1011000", ""],
    [
      "Dom. 1 p. Epiphaniam",
      "1st Sunday after Epiphany (Sunday within the octave of Epiphany, 'Dom. Infra Oct. Epiph.')",
      "5011010", "",
    ],
    ["Dom. 1 p. Pent.", "1st Sunday after Pentecost", "9011010", ""],
    ["Dom. 1 Quadragesimae", "1st Sunday of Lent", "7011000", ""],
    ["Dom. 10 p. Pent.", "10th Sunday after Pentecost", "9101000", ""],
    ["Dom. 11 p. Pent.", "11th Sunday after Pentecost", "9111000", ""],
    ["Dom. 12 p. Pent.", "12th Sunday after Pentecost", "9121000", ""],
    ["Dom. 13 p. Pent.", "13th Sunday after Pentecost", "9131000", ""],
    ["Dom. 14 p. Pent.", "14th Sunday after Pentecost", "9141000", ""],
    ["Dom. 15 p. Pent.", "15th Sunday after Pentecost", "9151000", ""],
    ["Dom. 16 p. Pent.", "16th Sunday after Pentecost", "9161000", ""],
    ["Dom. 17 p. Pent.", "17th Sunday after Pentecost", "9171000", ""],
    ["Dom. 18 p. Pent.", "18th Sunday after Pentecost", "9181000", ""],
    ["Dom. 19 p. Pent.", "19th Sunday after Pentecost", "9191000", ""],
    ["Dom. 2 Adventus", "2nd Sunday of Advent", "1021000", ""],
    ["Dom. 2 p. Epiph.", "2nd Sunday after Epiphany", "5021000", ""],
    ["Dom. 2 p. Pascha", "2nd Sunday after Easter", "8031000", ""],
    ["Dom. 2 p. Pascha,8", "In 3rd week after Easter", "8031008", ""],
    [
      "Dom. 2 p. Pent.",
      "2nd Sunday after Pentecost (also \"Dom. 1 p. Oct. Pent.\")",
      "9021000", "",
    ],
    ["Dom. 2 Quadragesimae", "2nd Sunday of Lent", "7021000", ""],
    ["Dom. 20 p. Pent.", "20th Sunday after Pentecost", "9201000", ""],
    ["Dom. 21 p. Pent.", "21st Sunday after Pentecost", "9211000", ""],
    ["Dom. 22 p. Pent.", "22nd Sunday after Pentecost", "9221000", ""],
    ["Dom. 23 p. Pent.", "23rd Sunday after Pentecost", "9231000", ""],
    ["Dom. 24 p. Pent.", "24th Sunday after Pentecost", "9241000", ""],
    ["Dom. 25 p. Pent.", "25th Sunday after Pentecost", "9251000", ""],
    ["Dom. 26 p. Pent.", "26th Sunday after Pentecost", "9261000", ""],
    ["Dom. 3 Adventus", "3rd Sunday of Advent", "1031000", ""],
    ["Dom. 3 p. Epiph.", "3rd Sunday after Epiphany", "5031000", ""],
    ["Dom. 3 p. Pascha", "3rd Sunday after Easter", "8041000", ""],
    ["Dom. 3 p. Pascha,8", "In 4th week after Easter", "8041008", ""],
    ["Dom. 3 p. Pent.", "3rd Sunday after Pentecost", "9031000", ""],
    ["Dom. 3 Quadragesimae", "3rd Sunday of Lent", "7031000", ""],
    ["Dom. 4 Adventus", "4th Sunday of Advent", "1041000", ""],
    ["Dom. 4 p. Epiph.", "4th Sunday after Epiphany", "5041000", ""],
    ["Dom. 4 p. Pascha", "4th Sunday after Easter", "8051000", ""],
    ["Dom. 4 p. Pascha,8", "In 5th week after Easter", "8051008", ""],
    ["Dom. 4 p. Pent.", "4th Sunday after Pentecost", "9041000", ""],
    ["Dom. 4 Quadragesimae", "4th Sunday of Lent", "7041000", ""],
    ["Dom. 5 p. Epiph.", "5th Sunday after Epiphany", "5051000", ""],
    ["Dom. 5 p. Pascha", "5th Sunday after Easter", "8061000", ""],
    ["Dom. 5 p. Pent.", "5th Sunday after Pentecost", "9051000", ""],
    ["Dom. 6 p. Epiph.", "6th Sunday after Epiphany", "5061000", ""],
    ["Dom. 6 p. Pent.", "6th Sunday after Pentecost", "9061000", ""],
    ["Dom. 7 p. Pent.", "7th Sunday after Pentecost", "9071000", ""],
    ["Dom. 8 p. Pent.", "8th Sunday after Pentecost", "9081000", ""],
    ["Dom. 9 p. Pent.", "9th Sunday after Pentecost", "9091000", ""],
    ["Dom. Adventus", "Sundays in Advent", "1001000", ""],
    ["Dom. de Passione", "5th Sunday of Lent (Passion Sunday)", "7051000", ""],
    ["Dom. in Palmis", "Palm Sunday", "7061000", ""],
    [
      "Dom. infra Oct. Apostolorum Petri et Pauli",
      "Sunday after Peter and Paul",
      "14062901", "",
    ],
    ["Dom. mensis Augusti", "Sundays in August", "11080100", ""],
    ["Dom. mensis Nov.", "Sundays in November", "11110100", ""],
    ["Dom. mensis Octobris", "Sundays in October", "11100100", ""],
    ["Dom. mensis Sept.", "Sundays in September", "11090100", ""],
    ["Dom. p. Assumptionem", "Sunday after Assumption of Mary", "14081501", ""],
    ["Dom. p. Cor. Christi", "Sunday after Corpus Christi", "9021010", ""],
    ["Dom. p. Epiphaniam", "Sundays after Epiphany", "5001000", ""],
    ["Dom. p. Martini", "Sunday after Martin", "14111101", ""],
    ["Dom. p. Nat. Dom.", "Sunday after Christmas", "3021000", ""],
    ["Dom. p. Nat. Mariae", "Sunday after Birthday of Mary", "14090801", ""],
    ["Dom. Pentecostes", "Pentecost Sunday (also \"Whitsunday\")", "8081000", ""],
    ["Dom. per annum", "Sundays, Ferial Office", "4001000", ""],
    ["Dom. post Ascensionem", "Sunday after Ascension", "8071000", ""],
    ["Dom. Quadragesimae", "Sundays in Lent", "7001000", ""],
    ["Dom. Quinquagesimae", "Quinquagesima Sunday", "6031000", ""],
    ["Dom. Resurrect.,8", "In week after Easter Sunday", "8011008", ""],
    ["Dom. Resurrectionis", "Easter Sunday", "8011000", ""],
    ["Dom. Septuagesimae", "Septuagesima Sunday", "6011000", ""],
    ["Dom. Sexagesimae", "Sexagesima Sunday", "6021000", ""],
    ["Dom. ultima ante Adventum", "The last Sunday before Advent", "9991010", ""],
    ["Dominica de BMV", "", "xxx", ""],
    ["Dominica in estate", "Sundays in summer", "10001000", ""],
    [
      "Dominici",
      "Dominic, founder of the Order of Friars Preachers",
      "14080430", "Aug.4",
    ],
    ["Donati", "Donatus, Bishop of Arezzo", "14080700", "Aug.7"],
    [
      "Elisabeth Reginae Portugalliae",
      "Elisabeth (Isabel), Widow, Queen of Portugal",
      "14070830", "Jul.4",
    ],
    ["Epiphania", "Epiphany", "5010600", "Jan.6"],
    ["Epiphania,8", "In week after Epiphany", "5010608", ""],
    ["Eulaliae Emeritensis", "Eulalia of Merida, Virgin Martyr", "", "Dec. 10"],
    ["Euphemiae", "Euphemia, Virgin and Martyr", "14091610", "Sep.16"],
    ["Eusebii Romanae", "Eusebius of Rome", "14081400", "Aug.14"],
    ["Eustachii", "Eustachius (Eustasius, Eustace), Martyr", "14092010", "Sep.20"],
    ["Evurtii", "Evurtius (Euvert), Bishop", "14090700", "Sep.7"],
    [
      "Exaltatio Crucis",
      "Holy Cross Day (Exaltation of the Cross)",
      "14091400", "Sep.14",
    ],
    [
      "Exspectationis BMV",
      "The Expectation of Mary (The Expectation of the Birth of Jesus)",
      "14121810", "Dec.18",
    ],
    ["Fabiani, Sebastiani", "Pope Fabian and Sebastian, Martyrs", "14012000", "Jan.20"],
    [
      "Felicis",
      "Felix, Bishop and Martyr (falsely called Pope Felix II)",
      "14072900", "Jul.29",
    ],
    ["Felicis Nolani", "Felix of Nola, Confessor", "14011410", "Jan.14"],
    ["Felicis, Adaucti", "Felix and Adauctus, Martyrs", "14083000", "Aug.30"],
    ["Felicitatis", "Felicitatis, Matron and Martyr", "14112310", "Nov.23"],
    ["Fer. 2 Cor. Christi", "Monday after Corpus Christi", "9022000", ""],
    ["Fer. 2 de BMV", "", "xxx", ""],
    ["Fer. 2 de Passione", "Monday, 5th week, Lent", "7052000", ""],
    ["Fer. 2 Hebd. 1 Adv.", "Monday, 1st week, Advent", "1012000", ""],
    ["Fer. 2 Hebd. 1 Quad.", "Monday, 1st week, Lent", "7012000", ""],
    ["Fer. 2 Hebd. 2 Adv.", "Monday, 2nd week, Advent", "1022000", ""],
    ["Fer. 2 Hebd. 2 p.Ep.", "Monday, 2nd week after Epiphany", "5022000", ""],
    ["Fer. 2 Hebd. 2 Quad.", "Monday, 2nd week, Lent", "7022000", ""],
    ["Fer. 2 Hebd. 3 Adv.", "Monday, 3rd week, Advent", "1032000", ""],
    ["Fer. 2 Hebd. 3 Quad.", "Monday, 3rd week, Lent", "7032000", ""],
    ["Fer. 2 Hebd. 4 Adv.", "Monday, 4th week, Advent", "1042000", ""],
    ["Fer. 2 Hebd. 4 Pasc.", "Monday, 4th week after Easte", "8042000", ""],
    ["Fer. 2 Hebd. 4 Quad.", "Monday, 4th week, Lent", "7042000", ""],
    ["Fer. 2 in estate", "Mondays in summer", "10002000", ""],
    ["Fer. 2 in Letaniis", "Rogation Monday", "8062000", ""],
    ["Fer. 2 Maj. Hebd.", "Monday, Holy Week", "7062000", ""],
    ["Fer. 2 p. Epiphaniam", "Monday, 1st week after Epiphany", "5012000", ""],
    ["Fer. 2 p. Oct.Pasch.", "Monday, 2nd week after Easter", "8022000", ""],
    ["Fer. 2 p. Pascha", "Easter Monday", "8012000", ""],
    ["Fer. 2 Pent.", "Pentecost Monday", "8082000", ""],
    ["Fer. 2 per annum", "Mondays, Ferial Office", "4002000", ""],
    ["Fer. 2 post Ascensionem", "Monday, week after Ascension", "8072000", ""],
    ["Fer. 2 Quadragesimae", "Mondays in Lent", "7002000", ""],
    ["Fer. 2 Quinquages.", "", "", ""],
    ["Fer. 2 Trinitate", "Trinity Monday", "9012000", ""],
    ["Fer. 3 Cor. Christi", "Tuesday after Corpus Christi", "9023000", ""],
    ["Fer. 3 de BMV", "", "xxx", ""],
    ["Fer. 3 de Passione", "Tuesday, 5th week, Lent", "7053000", ""],
    ["Fer. 3 et 6 de BMV", "", "xxx", ""],
    ["Fer. 3 et 6 de BMV Adv.", "", "xxx", ""],
    ["Fer. 3 Hebd. 1 Adv.", "Tuesday, 1st week, Advent", "1013000", ""],
    ["Fer. 3 Hebd. 1 Quad.", "Tuesday, 1st week, Lent", "7013000", ""],
    ["Fer. 3 Hebd. 2 Adv.", "Tuesday, 2nd week, Advent", "1023000", ""],
    ["Fer. 3 Hebd. 2 p.Ep.", "Tuesday, 2nd week after Epiphany", "5023000", ""],
    ["Fer. 3 Hebd. 2 Quad.", "Tuesday, 2nd week, Lent", "7023000", ""],
    ["Fer. 3 Hebd. 3 Adv.", "Tuesday, 3rd week, Advent", "1033000", ""],
    ["Fer. 3 Hebd. 3 Quad.", "Tuesday, 3rd week, Lent", "7033000", ""],
    ["Fer. 3 Hebd. 4 Adv.", "Tuesday, 4th week, Advent", "1043000", ""],
    ["Fer. 3 Hebd. 4 Pasc.", "Tuesday, 4th week after Easter", "8043000", ""],
    ["Fer. 3 Hebd. 4 Quad.", "Tuesday, 4th week, Lent", "7043000", ""],
    ["Fer. 3 in estate", "Tuesdays in summer", "10003000", ""],
    ["Fer. 3 in Letaniis", "Rogation Tuesday", "8063000", ""],
    ["Fer. 3 Maj. Hebd.", "Tuesday, Holy Week", "7063000", ""],
    ["Fer. 3 p. Epiphaniam", "Tuesday, 1st week after Epiphany", "5013000", ""],
    ["Fer. 3 p. Oct.Pasch.", "Tuesday, 2nd week after Easter", "8023000", ""],
    ["Fer. 3 p. Pascha", "Easter Tuesday", "8013000", ""],
    ["Fer. 3 Pent.", "Pentecost Tuesday", "8083000", ""],
    ["Fer. 3 per annum", "Tuesdays, Ferial Office", "4003000", ""],
    ["Fer. 3 post Ascensionem", "Tuesday, week after Ascension", "8073000", ""],
    ["Fer. 3 Quadragesimae", "Tuesdays in Lent", "7003000", ""],
    ["Fer. 3 Quinquagesimae", "Quinquagesima Tuesday", "6033000", ""],
    ["Fer. 3 Trinitate", "Trinity Tuesday", "9013000", ""],
    ["Fer. 4 Cinerum", "Ash Wednesday", "6034000", ""],
    ["Fer. 4 Cor. Christi", "Wednesday after Corpus Christi", "9024000", ""],
    ["Fer. 4 de BMV", "", "xxx", ""],
    ["Fer. 4 de Passione", "Wednesday, 5th week, Lent", "7054000", ""],
    ["Fer. 4 et Sabb. de BMV", "", "xxx", ""],
    ["Fer. 4 et Sabb. de BMV Adv.", "", "xxx", ""],
    ["Fer. 4 Hebd. 1 Adv.", "Wednesday, 1st week, Advent", "1014000", ""],
    ["Fer. 4 Hebd. 1 Quad.", "Wednesday, 1st week, Lent", "7014000", ""],
    ["Fer. 4 Hebd. 2 Adv.", "Wednesday, 2nd week, Advent", "1024000", ""],
    ["Fer. 4 Hebd. 2 p.Ep.", "Wednesday, 2nd week after Epiphany", "5024000", ""],
    ["Fer. 4 Hebd. 2 Quad.", "Wednesday, 2nd week, Lent", "7024000", ""],
    ["Fer. 4 Hebd. 3 Adv.", "Wednesday, 3rd week, Advent", "1034000", ""],
    ["Fer. 4 Hebd. 3 Quad.", "Wednesday, 3rd week, Lent", "7034000", ""],
    ["Fer. 4 Hebd. 4 Adv.", "Wednesday, 4th week, Advent", "1044000", ""],
    ["Fer. 4 Hebd. 4 Pasc", "Wednesday, 4th week after Easter", "8044000", ""],
    ["Fer. 4 Hebd. 4 Quad.", "Wednesday, 4th week, Lent", "7044000", ""],
    ["Fer. 4 in estate", "Wednesdays in summer", "10004000", ""],
    ["Fer. 4 in Letaniis", "Rogation Wednesday", "8064000", ""],
    ["Fer. 4 Maj. Hebd.", "Wednesday, Holy Week", "7064000", ""],
    ["Fer. 4 p. Epiphaniam", "Wednesday, 1st week after Epiphany", "5014000", ""],
    ["Fer. 4 p. Oct.Pasch.", "Wednesday, 2nd week after Easter", "8024000", ""],
    ["Fer. 4 p. Pascha", "Easter Wednesday", "8014000", ""],
    ["Fer. 4 Pent.", "Pentecost Wednesday", "8084000", ""],
    ["Fer. 4 per annum", "Wednesdays, Ferial Office", "4004000", ""],
    ["Fer. 4 post Ascensionem", "Wednesday, week after Ascension", "8074000", ""],
    ["Fer. 4 Q.T. Adventus", "Ember Day, Advent (Wednesday)", "1034009", ""],
    ["Fer. 4 Q.T. Pent.", "Ember Day, Pentecost (Wednesday)", "8084009", ""],
    ["Fer. 4 Q.T. Sept.", "Ember Day, September (Wednesday)", "11090409", ""],
    ["Fer. 4 Quadragesimae", "Wednesdays in Lent", "7004000", ""],
    ["Fer. 4 Trinitate", "Trinity Wednesday", "9014000", ""],
    ["Fer. 5 de BMV", "", "xxx", ""],
    ["Fer. 5 de Passione", "Thursday, 5th week, Lent", "7055000", ""],
    ["Fer. 5 Hebd. 1 Adv.", "Thursday, 1st week, Advent", "1015000", ""],
    ["Fer. 5 Hebd. 1 Quad.", "Thursday, 1st week, Lent", "7015000", ""],
    ["Fer. 5 Hebd. 2 Adv.", "Thursday, 2nd week, Advent", "1025000", ""],
    ["Fer. 5 Hebd. 2 p.Ep.", "Thursday, 2nd week after Epiphany", "5025000", ""],
    ["Fer. 5 Hebd. 2 Quad.", "Thursday, 2nd week, Lent", "7025000", ""],
    ["Fer. 5 Hebd. 3 Adv.", "Thursday, 3rd week, Advent", "1035000", ""],
    ["Fer. 5 Hebd. 3 Quad.", "Thursday, 3rd week, Lent", "7035000", ""],
    ["Fer. 5 Hebd. 4 Adv.", "Thursday, 4th week, Advent", "1045000", ""],
    ["Fer. 5 Hebd. 4 Pasc.", "Thursday, 4th week after Easter", "8045000", ""],
    ["Fer. 5 Hebd. 4 Quad.", "Thursday, 4th week, Lent", "7045000", ""],
    ["Fer. 5 in Cena Dom.", "Holy Thursday (Maundy Thursday)", "7065000", ""],
    ["Fer. 5 in estate", "Thursdays in summer", "10005000", ""],
    ["Fer. 5 p. Epiphaniam", "Thursday, 1st week after Epiphany", "5015000", ""],
    ["Fer. 5 p. Oct.Pasch.", "Thursday, 2nd week after Easter", "8025000", ""],
    ["Fer. 5 p. Pascha", "Easter Thursday", "8015000", ""],
    ["Fer. 5 Pent.", "Pentecost Thursday", "8085000", ""],
    ["Fer. 5 per annum", "Thursdays, Ferial Office", "4005000", ""],
    ["Fer. 5 post Cineres", "Thursday after Ash Wednesday", "6035000", ""],
    ["Fer. 5 Quadragesimae", "Thursdays in Lent", "7005000", ""],
    ["Fer. 6 Cor. Christi", "Friday after Corpus Christi", "9016000", ""],
    ["Fer. 6 de BMV", "", "xxx", ""],
    ["Fer. 6 de Passione", "Friday, 5th week, Lent", "7056000", ""],
    ["Fer. 6 Hebd. 1 Adv.", "Friday, 1st week, Advent", "1016000", ""],
    ["Fer. 6 Hebd. 1 Quad.", "Friday, 1st week, Lent", "7016000", ""],
    ["Fer. 6 Hebd. 2 Adv.", "Friday, 2nd week, Advent", "1026000", ""],
    ["Fer. 6 Hebd. 2 p.Ep.", "Friday, 2nd week after Epiphany", "5026000", ""],
    ["Fer. 6 Hebd. 2 Quad.", "Friday, 2nd week, Lent", "7026000", ""],
    ["Fer. 6 Hebd. 3 Adv.", "Friday, 3rd week, Advent", "1036000", ""],
    ["Fer. 6 Hebd. 3 Quad.", "Friday, 3rd week, Lent", "7036000", ""],
    ["Fer. 6 Hebd. 4 Adv.", "Friday, 4th week, Advent", "1046000", ""],
    ["Fer. 6 Hebd. 4 Pasc.", "Friday, 4th week after Easter", "8046000", ""],
    ["Fer. 6 Hebd. 4 Quad.", "Friday, 4th week, Lent", "7046000", ""],
    ["Fer. 6 in estate", "Fridays in summer", "10006000", ""],
    ["Fer. 6 in Parasceve", "Good Friday", "7066000", ""],
    ["Fer. 6 p. Epiphaniam", "Friday, 1st week after Epiphany", "5016000", ""],
    ["Fer. 6 p. Oct. Asc.", "Friday, after the Octave of Ascension", "8076000", ""],
    ["Fer. 6 p. Oct.Pasch.", "Friday, 2nd week after Easter", "8026000", ""],
    ["Fer. 6 p. Pascha", "Easter Friday", "8016000", ""],
    ["Fer. 6 Pent.", "Pentecost Friday", "8086000", ""],
    ["Fer. 6 per annum", "Fridays, Ferial Office", "4006000", ""],
    ["Fer. 6 post Ascensionem", "Friday after Ascension", "8066000", ""],
    ["Fer. 6 post Cineres", "Friday after Ash Wednesday", "6036000", ""],
    ["Fer. 6 Q.T. Adventus", "Ember Day, Advent (Friday)", "1036009", ""],
    ["Fer. 6 Q.T. Pent.", "Ember Day, Pentecost (Friday)", "8086009", ""],
    ["Fer. 6 Q.T. Sept.", "Ember Day, September (Friday)", "11090609", ""],
    ["Fer. 6 Quadragesimae", "Fridays in Lent", "7006000", ""],
    [
      "Fest. per annum",
      "For unspecified or miscellaneous ferial days throughout the year",
      "4000000", "",
    ],
    ["Flori", "Florus, Bishop of Lodève", "14110400", "Nov.4"],
    ["Franchae", "Franca Visalta, Virgin and Abbess, at Piacenza", "14042700", "Apr.4"],
    ["Francisci", "Francis of Assisi", "14100400", "Oct.4"],
    [
      "Francisci Xaverii",
      "Francis Xavier, Confessor, Apostle of India and Japan (canonized in 1622)",
      "14120300", "Dec.3",
    ],
    [
      "Fructuosi Archiepiscopi Bracharensis",
      "Fructuosus, Archbishop of Braga",
      "14041610", "Apr.16",
    ],
    ["Gabrielis, Archang.", "Gabriel the Archangel", "14031800", "Mar.18"],
    ["Genesii", "Genesius, Martyr", "14082530", "Aug.25"],
    ["Georgii", "George, Martyr", "14042300", "Apr.23"],
    [
      "Geraldi Archiepiscopi Bracharensis",
      "Gerald (Girald), Archbishop of Braga",
      "14120510", "Dec.5",
    ],
    ["Geraldi Aureliaci", "Gerald of Aurillac", "14101300", "Oct.13"],
    ["Geraldi Aureliaci", "Gerald of Aurillac", "14101300", "Oct.13"],
    ["Germani", "Germanus, Bishop of Auxerre", "14073100", "Jul.31"],
    ["Gervasii, Protasii", "Gervase and Protase, Martyrs", "14061900", "Jun.19"],
    ["Gordiani, Epimachi", "Gordian and Epimachus, Martyrs", "14051000", "May.10"],
    ["Gorgonii", "Gorgonius, Martyr", "14090900", "Sep.9"],
    ["Gregorii", "Gregory the Great, Pope and Doctor", "14031200", "Mar.12"],
    ["Hebd. 1 Adventus", "1st week of Advent", "1018000", ""],
    ["Hebd. 1 Quad.", "1st week of Lent", "7018000", ""],
    ["Hebd. 2 Adventus", "2nd week of Advent", "1028000", ""],
    ["Hebd. 2 p. Pascha", "2nd week after Easter", "8028000", ""],
    ["Hebd. 2 Quad.", "2nd week of Lent", "7028000", ""],
    ["Hebd. 3 p. Pascha", "3rd week after Easter", "8038000", ""],
    ["Hebd. 4 Adventus", "4th week of Advent", "1048000", ""],
    ["Hebd. de Passione", "5th week of Lent", "7058000", ""],
    ["Hebd. p. Pent.", "Weekdays after Pentecost", "9008000", ""],
    ["Hebd. per annum", "Weekdays, Ferial Office", "4008000", ""],
    ["Hebd. Quinquagesimae", "Week after Quinquagesima", "6038000", ""],
    ["Hebd. Septuagesimae", "Week after Septuagesima", "6018000", ""],
    ["Hebd. Sexagesimae", "Week after Sexagesima", "6028000", ""],
    ["Hebd. TP", "Weekdays, Eastertide", "8008000", ""],
    ["Hermetis", "Hermes, Martyr", "14082830", "Aug.28"],
    ["Hieronimi", "Jerome, Doctor", "14093000", "Sep.30"],
    ["Hilarii", "Hilary, Bishop of Poitiers, Doctor", "14011400", "Jan.14"],
    ["Hippolyti", "Hippolytus, Martyr", "14081300", "Aug.13"],
    ["Hugonis Episc. Lincolniensis", "Hugh, Bishop of Lincoln", "14111740", "Nov.17"],
    [
      "Humbelinae",
      "Humbelina (Humbleline), matron, sister of Bernard of Clairvaux",
      "14021200", "Feb.12",
    ],
    ["Ignatii", "Ignatius, Bishop of Antioch, Martyr", "14020110", "Feb.1"],
    ["Ildefonsi", "Ildephonsus, Archbishop of Toledo", "14012301", ""],
    [
      "In dedicatione Basilicae BMV de Martyribus Ulyssipponensis",
      "Dedication of the Basilica of Our Lady of the Martyrs, Lisbon",
      "14051320", "May.13",
    ],
    ["In Dedicatione Ecclesiae", "Dedication of a Church", "12008000", ""],
    ["In festo sollemni", "", "xxx", ""],
    ["In Letaniis", "General, Rogation Days", "8068010", ""],
    ["In tempore Adventus", "General, in Advent", "1000000", ""],
    [
      "In tempore belli contra Sarracenos",
      "Chants in time of war against the Saracens",
      "16021000", "",
    ],
    ["In tempore Epiphaniae", "General, after Epiphany", "5000000", ""],
    ["In tempore Nat.", "General, in Christmastide", "3000000", ""],
    [
      "In tempore oritur inter Christianos",
      "Chants in time of an uprising among Christians",
      "16022000", "",
    ],
    ["In tempore Paschae", "General, Eastertide", "8000000", ""],
    ["In tempore pestilentiae", "Chants in time of the plague", "16018000", ""],
    ["In tempore Quad.", "General, in Lent", "7000000", ""],
    ["In Triduum", "General, during the Triduum", "7069000", ""],
    ["Inventio Crucis", "Finding of the Cross", "14050300", "May.3"],
    [
      "Inventio Stephani",
      "Finding of Stephen's relics (First Martyr)",
      "14080300", "Aug.3",
    ],
    ["Invitatoria", "Invitatory antiphons or psalms", "16004000", ""],
    ["Irenes", "Irene of Santarém (Portugal)", "14102010", "Oct.20"],
    ["Isidori Episcopi Confessoris et Ecclesiae Doctoris", "", "", "Apr.4"],
    [
      "Ivonis de Kermartin",
      "Ivo of Kermartin (Yves Hélory, Yvo, Ives)",
      "14051930", "May.19",
    ],
    ["Jacobi", "James the Greater, Apostle", "14072500", "Jul.25"],
    ["Joachimi", "Joachim, the father of Mary", "14032010", "Mar.20"],
    ["Joannis Abbatis Reomensis", "John of Réome, abbot", "14012830", "Jan.28"],
    ["Joannis Baptistae", "John the Baptist", "14062400", "Jun.24"],
    ["Joannis Baptistae,8", "In week after John the Baptist", "14062408", ""],
    ["Joannis Chrysostomi", "John Chrysostom, Doctor", "14012700", "Jan.27"],
    ["Joannis Evang.", "John the Evangelist", "2122700", "Dec.27"],
    ["Joannis Evang.,8", "In week after John the Evangelist", "2122708", ""],
    ["Joannis Port. Lat.", "John before the Latin Gate", "14050600", "May.6"],
    ["Joannis, Pauli", "John and Paul, Martyrs", "14062600", "Jun.26"],
    ["Josephi", "Joseph, spouse of Mary", "14031900", "Mar.19"],
    ["Juliani, Epi.", "Julian, Bishop of Le Mans", "14012710", "Jan.27"],
    ["Juliani, Hermetis", "Julian of Brioude and Hermes, Martyrs", "14082860", "Aug.28"],
    ["Justae et Rufinae martyrum", "Jul-17", "xxx", ""],
    ["Justi", "Justus of Beauvais, Martyr", "14101810", "Oct.18"],
    [
      "Justi, Pastoris",
      "Justus and Pastor, Martyrs at Alcalá de Henares (Complutum)",
      "14080630", "Aug.6",
    ],
    ["Laurentii", "Laurence, Martyr", "14081000", "Aug.10"],
    ["Laurentii,8", "In week after Laurence", "14081008", ""],
    [
      "Lauteni",
      "Lautein (Lothenus, Lautenus), Abbot, founder of Silèze and Maximiac abbeys in the Jura mountains",
      "14110200", "Nov.2",
    ],
    ["Leocadiae", "Leocadia of Toledo, Virgin Martyr", "14120920", "Dec.9"],
    ["Leodegarii", "Leodegarius (Leger), Bishop Martyr", "14100200", "Oct.2"],
    ["Leonardi", "Leonard, Hermit", "14110610", "Nov.6"],
    ["Lucae", "Luke, Evangelist", "14101800", "Oct.18"],
    ["Luciae", "Lucy (Lucia), Virgin Martyr", "14121300", "Dec.13"],
    ["Ludovici", "Louis IX, King of France", "14082500", "Aug.25"],
    ["Ludovici Toul.", "Louis, Bishop of Toulouse", "14081900", "Aug.19"],
    ["Lupi", "Lupus (Leu), Bishop of Sens", "14090110", "Sep.1"],
    ["Lutgardae", "Lutgard, Virgin, Mystic, Cistercian Order", "14061620", "Jun.16"],
    ["Mafaldae Portugalensis", "", "xxx", ""],
    ["Malachiae", "Malachy, Bishop at Armagh, Ireland", "14110220", "Nov.2"],
    ["Marcelli", "Marcellus, Martyr", "14090420", "Sep.4"],
    ["Marcellini, Petri", "Marcellinus and Peter, Martyrs", "14060200", "Jun.2"],
    ["Marci", "Mark, Evangelist", "14042500", "Apr.25"],
    ["Marci, Marcellini", "Mark and Marcellian, Martyrs", "14061800", "Jun.18"],
    ["Marci, Pont.", "Mark, Pope", "14100700", "Oct.7"],
    ["Margaritae", "Margaret (Marina), Virgin Martyr", "14072010", "Jul.20"],
    ["Mariae ad Nives", "Mary of the Snows", "14080520", "Aug.5"],
    ["Mariae Magdalenae", "Mary Magdalene", "14072200", "Jul.22"],
    ["Mariae Salome", "Mary Salome", "14102210", "Oc.22"],
    ["Marii, Marthae", "Marius, Martha, et al., Martyrs", "14011900", "Jan.19"],
    ["Marinae", "Marina, Virgin", "14071720", "Jul.17"],
    [
      "Mart. sive Conf. TP",
      "Common of Martyrs or Confessors, Eastertide",
      "12802300", "",
    ],
    ["Marthae", "Martha, Virgin", "14072930", "Jul.29"],
    ["Martini", "Martin, Bishop of Tours", "14111100", "Nov.11"],
    [
      "Martini Archiepiscopi Bracharensis",
      "Martin, Bishop of Braga",
      "14032020", "Mar. 20",
    ],
    ["Martini,8", "In week after Martin", "14111108", ""],
    ["Matthaei", "Matthew, Apostle and Evangelist", "14092100", "Sep.21"],
    ["Matthiae", "Matthias, Apostle", "14022400", "Feb.24"],
    ["Mauri", "Maurus, Abbot", "14011510", "Jan.15"],
    ["Mauritii et sociorum", "Maurice and companions, Martyrs", "14092200", "Sep.22"],
    ["Mennae", "Mennas, Martyr", "14111110", "Nov.11"],
    ["Michaelis", "Michael the Archangel (Michaelmas)", "14092900", "Sep.29"],
    ["Monicae", "Monica, Mother of Augustine", "14050440", "May.4"],
    ["Nat. Innocentium", "Holy Innocents", "2122800", "Dec.28"],
    ["Nat. Innocentium,8", "In week after Holy Innocents", "2122808", ""],
    ["Nativitas Domini", "Christmas Day", "2122500", "Dec.25"],
    ["Nativitas Domini,8", "In week after Christmas", "2122508", ""],
    ["Nativitas Mariae", "Birthday of Mary", "14090800", "Sep.8"],
    ["Nativitas Mariae,8", "In week after Birthday of Mary", "14090808", ""],
    ["Nazarii, Celsi", "Nazarius and Celsus, Martyrs", "14072800", "Jul.28"],
    ["Nicolai", "Nicholas of Bari, Bishop of Myra", "14120600", "Dec.6"],
    ["Nicomedis", "Nicomedes, Martyr", "14091530", "Sep.15"],
    ["Nicomedis, Valeriani", "Nicomedes and Valerianus, Martyrs", "14091540", "Sep.15"],
    ["Nominis Jesu", "The Holy Name of Jesus", "14011430", "Jan.14"],
    ["Oct. Ascens. Domini", "Octave of Ascension", "8075000", ""],
    ["Oct. Corporis Christi", "Octave of Corpus Christi", "9025000", ""],
    ["Oct. Nat. Innocent.", "Octave of Holy Innocents", "2010400", "Jan.4"],
    ["Octava Agnetis", "Octave of Agnes", "14012800", "Jan.28"],
    ["Octava Andreae", "Octave of Andrew", "14120710", "Dec.7"],
    [
      "Octava Apostolorum Petri et Pauli",
      "Octave of Peter and Paul",
      "14070600", "Jul.6",
    ],
    ["Octava Assumptionis", "Octave of Assumption of Mary", "14082210", "Aug.22"],
    ["Octava Epiphaniae", "Octave of Epiphany", "5011300", "Jan.13"],
    ["Octava Joannis Bapt.", "Octave of John the Baptist", "14070100", "Jul.1"],
    ["Octava Laurentii", "Octave of Laurence", "14081700", "Aug.17"],
    ["Octava Martini", "Octave of Martin", "14111800", "Nov.18"],
    ["Octava Nat. Domini", "Octave of Christmas", "2010100", "Jan.1"],
    ["Octava Nat. Domini,8", "In week after Octave of Christmas", "2010108", ""],
    [
      "Octava Paschae",
      "Octave of Easter (also \"Dominica in Albis\")",
      "8021000", "",
    ],
    ["Octava Paschae,8", "In 2nd week after Easter", "8021008", ""],
    ["Omnium Sanctorum", "All Saints' Day", "14110100", "Nov.1"],
    ["Omnium Sanctorum,8", "In week after All Saints' Day", "14110108", ""],
    [
      "Pancratii et sociorum",
      "Pancratius (Pancras) and companions, Martyrs",
      "14051210", "May.12",
    ],
    ["Paulae", "Paula, Widow", "14012610", "Jan.26"],
    ["Pauli", "Paul, Apostle", "14063000", "Jun.30"],
    ["Pauli Heremitae", "Paul the Hermit", "14011010", "Jan.10"],
    ["Pauli,8", "In week after Paul (Apostle)", "14063008", ""],
    ["Petri", "Peter, Apostle", "14062910", "Jun.29"],
    ["Petri Alexandrini Ep. Mart.", "", "", "Nov.26"],
    [
      "Petri de Rates",
      "Peter de Rates, reputed first Bishop of Braga",
      "4042610", "Apr.26",
    ],
    ["Petri Gundisalvi", "Peter González, Dominican friar", "14041410", "Apr.14"],
    ["Petri Regalati", "Peter Regalado, Franciscan friar", "14051330", "May.13"],
    [
      "Petri, Mart.",
      "Peter the Martyr, Dominican Friar and Priest",
      "14042900", "Apr.29",
    ],
    ["Petri, Pauli", "Peter and Paul, Apostles", "14062900", "Jun.29"],
    ["Petri, Pauli,8", "In week after Peter and Paul", "14062908", ""],
    ["Philippi, Jacobi", "Philip and James the Lesser, Apostles", "14050100", "May.1"],
    ["Polycarpi", "Polycarp, Bishop of Smyrna, Martyr", "14012600", "Jan.26"],
    ["Praesentatio Mariae", "Presentation of Mary", "14112100", "Nov.21"],
    ["Praxedis", "Praxedes, Virgin", "14072100", "Jul.21"],
    ["Primi, Feliciani", "Primus and Felician, Martyrs", "14060900", "Jun.9"],
    ["Priscae", "Prisca, Virgin Martyr", "14011800", "Jan.18"],
    ["Pro amico", "Chants for the friend", "16029000", ""],
    ["Pro Defunctis", "For the dead", "13001000", ""],
    ["Pro familiaribus", "Chants for family members", "16044000", ""],
    ["Pro familiaribus", "Chants for family members", "16044000", ""],
    ["Pro febribus", "Chants for fever", "16031000", ""],
    ["Pro infirmis", "Chants for many who are sick", "16013030", ""],
    ["Pro iter agentibus", "Chants for travellers, pilgrims", "16030000", ""],
    ["Pro pace", "Chants for peace", "16019010", ""],
    [
      "Pro pace regni",
      "Chants for the peace of the realm and the church",
      "16019000", "",
    ],
    ["Pro pluvia", "Chants for rain", "16020000", ""],
    ["Pro quacumque necessitate", "For all needs", "16050000", ""],
    ["Pro remissione peccatorum", "Chants for forgiveness", "16045000", ""],
    ["Pro sacerdote", "Chants for the Priest", "16028000", ""],
    ["Pro salute vivorum", "Chants for the health of the living", "16027000", ""],
    ["Pro serenitate", "Chants for serenity", "16042000", ""],
    ["Pro sponso et sponsa", "Chants for the bride and groom", "16044000", ""],
    ["Pro tribulatione", "Chants for distress", "16026000", ""],
    ["Processi, Martiniani", "Processus and Martinian, Martyrs", "14070210", "Jul.2"],
    ["Proti, Hiacinthi", "Protus and Hyacinth, Martyrs", "14091100", "Sep.11"],
    [
      "Pudentianae, Pudentis",
      "Pudentiana and Pudens, Martyrs; Pudentiana (Potentiana), a Roman Virgin, and Pudens (Quintus Cornelius Pudens), a Roman senator and said to be the father of Praxedis, Pudentiana, Novatus, and Timotheus, husband of [],Priscilla",
      "14051910", "May.19",
    ],
    ["Purificatio Mariae", "Purification of Mary (Candlemas)", "14020200", "Feb.2"],
    ["Q.T. Quadragesimae", "Ember Days, Lent", "7010009", ""],
    ["Quadrag. Martyrorum", "Forty Martyrs of Sebaste", "14030900", "Mar.9"],
    ["Quattuor Coronatorum", "The Four Crowned Martyrs", "14110800", "Nov.8"],
    ["Quattuor Doctoribus Ecclesiae", "", "xxx", ""],
    ["Raphaelis, Archang.", "Raphael the Archangel", "14102410", "Oct.24"],
    ["Reliquiarum", "Feast of Relics", "14091500", "Sep.15"],
    ["Remigii", "Remigius (Remi), Bishop of Reims", "14100100", "Oct.1"],
    ["Rufi", "Rufus, Bishop", "14082820", "Aug.28"],
    ["Sabb. Adventus", "Saturdays in Advent", "1007000", ""],
    ["Sabb. Cor. Christi", "Saturday after Corpus Christi", "9017000", ""],
    ["Sabb. de Passione", "Saturday, 5th week, Lent", "7057000", ""],
    ["Sabb. Hebd. 1 Quad.", "Saturday, 1st week, Lent", "7017000", ""],
    ["Sabb. Hebd. 2 p. Ep.", "Saturday, 2nd week after Epiphany", "5027000", ""],
    ["Sabb. Hebd. 2 Quad.", "Saturday, 2nd week, Lent", "7027000", ""],
    ["Sabb. Hebd. 3 Quad.", "Saturday, 3rd week, Lent", "7037000", ""],
    ["Sabb. Hebd. 4 Quad.", "Saturday, 4th week, Lent", "7047000", ""],
    ["Sabb. p. Epiphaniam", "Saturday, 1st week after Epiphany", "5017000", ""],
    ["Sabb. p. Oct. Pasch.", "Saturday, 2nd week after Easter", "8027000", ""],
    ["Sabb. post Ascensionem", "Saturday after Ascension", "8067000", ""],
    ["Sabb. Quadragesimae", "Saturdays in Lent", "7007000", ""],
    ["Sabb. Sexagesimae", "Sexagesima Saturday", "6027000", ""],
    ["Sabbato 3 p. Pascha", "Saturday, 3rd week after Easter", "8037000", ""],
    ["Sabbato 4 p. Pascha", "Saturday, 4th week after Easter", "8047000", ""],
    ["Sabbato de BMV", "", "xxx", ""],
    ["Sabbato Hebd. 1 Adv.", "Saturday, 1st week, Advent", "1017000", ""],
    ["Sabbato Hebd. 2 Adv.", "Saturday, 2nd week, Advent", "1027000", ""],
    ["Sabbato Hebd. 3 Adv.", "Saturday, 3rd week, Advent", "1037000", ""],
    ["Sabbato Hebd. 4 Adv.", "Saturday, 4th week, Advent", "1047000", ""],
    ["Sabbato in Albis", "Easter Saturday (Saturday after Easter)", "8017000", ""],
    ["Sabbato in estate", "Saturdays in summer", "10007000", ""],
    ["Sabbato Pent.", "Pentecost Saturday", "8087000", ""],
    ["Sabbato per annum", "Saturdays, Ferial Office", "4007000", ""],
    ["Sabbato post Cineres", "Saturday after Ash Wednesday", "6037000", ""],
    ["Sabbato Q.T. Adventus", "Ember Day, Advent (Saturday)", "1037009", ""],
    ["Sabbato Q.T. Pent.", "Ember Day, Pentecost (Saturday)", "8087009", ""],
    ["Sabbato Q.T. Sept.", "Ember Day, September (Saturday)", "11090709", ""],
    ["Sabbato Sancto", "Holy Saturday", "7067000", ""],
    ["Sabinae", "Sabina, Martyr", "14082910", "Aug.29"],
    [
      "Sabiniani, Potentiani",
      "Sabinianus (Savinien) and Potentianus (Potentien), Bishops of Sens",
      "14101930", "Oct.19",
    ],
    [
      "Sacratissimi Cordis Jesu",
      "Most Sacred Heart of Jesus, on Friday after the second Sunday after Pentecost",
      "9026010", "",
    ],
    ["Samsonis", "Samson, Bishop of Dol", "14072810", "Jul.28"],
    [
      "Sanctorum Quinque Martyrum Ordinis Minorum",
      "Five Martyrs of Morocco",
      "14011630", "Jan.16",
    ],
    ["Saturnini", "Saturninus, Martyr Bishop of Toulouse", "14112900", "Nov.29"],
    ["Savinianus, Potentianus", "", "xxx", ""],
    ["Scholasticae", "Scholastica, Virgin", "14021000", "Feb.10"],
    ["Septem Dolorum BMV", "The Seven Sorrows of Mary", "14091550", "Sep.15"],
    ["Septem Fratrum", "Seven Brothers, Martyrs", "14071000", "Jul.10"],
    ["Sergii, Bacchi", "Sergius and Bacchus, Martyrs", "14100820", "Oct.8"],
    ["Silvestri", "Silvester I, Pope", "14123100", "Dec.31"],
    ["Simonis, Judae", "Simon, Jude (Thaddeus), Apostles", "14102800", "Oct.28"],
    ["Simplicii et sociorum", "Simplicius and companions, Martyrs", "14072920", "Jul.29"],
    ["Sixti et sociorum", "Sixtus and companions, Martyrs", "14080610", "Aug.6"],
    ["Speusippi", "Speusippus and Meleusippus, Martyrs", "14011720", "Jan.17"],
    ["Stephani", "Stephen the First Martyr", "2122600", "Dec.26"],
    [
      "Stephani Abbatis",
      "Stephen Harding, third Abbot of Citeaux, founder of the Cistercian Order",
      "14041710", "Apr.17",
    ],
    ["Stephani, Pont.", "Stephen I, Pope", "14080200", "Aug.2"],
    ["Stephani,8", "In week after Stephen", "2122608", ""],
    ["Suff. Crucis", "Memorial chants for the Holy Cross", "15050300", ""],
    [
      "Suff. Crucis TP",
      "Memorial chants for the Holy Cross, Eastertide",
      "15050380", "",
    ],
    ["Suff. Mariae TP", "Memorial chants for Mary, Eastertide", "15081580", ""],
    [
      "Suff. Om. Sanct. TP",
      "Memorial chants for All Saints, Eastertide",
      "15110180", "",
    ],
    ["Suff. pro Pace", "Memorial chants for peace", "15001000", ""],
    ["Suff. pro Penitent.", "Memorial chants for penitence", "15003000", ""],
    ["Symphoriani", "Symphorian (and Timothy), Martyrs", "14082200", "Aug.22"],
    ["Syri", "Syrus", "14120900", "Dec.9"],
    ["Taurini", "Taurinus, Bishop of Evreux", "14081130", "Aug.11"],
    [
      "Teresiae Avilensis",
      "Teresa (Teresia), Virgin, reformer of the Carmelite Order and ascetical write (canonized in 1622)",
      "14101500", "Oct.15",
    ],
    ["Theodori Tiro", "Theodore Tiro, Martyr", "14110910", "Nov.9"],
    ["Theotonii", "Theotonius, priest and prior (Santa Cruz, Coimbra)", "", "Feb.18"],
    ["Thomae Apost.", "Thomas, Apostle", "14122100", "Dec.21"],
    ["Thomae Cant.", "Thomas Becket, Bishop of Canterbury", "14122900", "Dec.29"],
    ["Thomae de Aquino", "Thomas of Aquino, Doctor of the Church", "14030700", ""],
    ["Thyrsus & Saturninus, Victor mm. Alexand.", "", "xxx", ""],
    ["Tiburtii, Susannae", "Tiburtius and Susanna, Martyrs", "14081100", "Aug.11"],
    [
      "Tiburtii, Valeriani",
      "Tiburtius, Valerian and Maximus, Martyrs",
      "14041400", "Apr.14",
    ],
    ["Timothei, Apollinaris", "Timothy and Apollinaris, Martyrs", "14082300", "Aug.23"],
    ["Transfiguratio Dom.", "Transfiguration of Jesus", "14080600", "Aug.6"],
    ["Transl. Benedicti", "Moving of Benedict's relics", "14071100", "Jul.11"],
    ["Transl. Jacobi", "Translation of James the Greater", "14123020", "Dec.30"],
    ["Transl. Martini", "Moving of Martin's relics", "14070400", "Jul.4"],
    ["Tres Mariae", "Mary, Mary Cleophae, Mary Salome", "14052520", "May.25"],
    [
      "Triumphi Sanctae Crucis apud Navas Tolosae",
      "Triumph of the Holy Cross in Las Navas de Tolosa (Battle of Las Navas de Tolosa or Battle of Al-Uqab)",
      "14071730", "Jul.17",
    ],
    ["Urbani", "Urban I, Pope and Martyr", "14052510", "May.25"],
    ["Valentini", "Valentine, Martyr", "14021400", "Feb.14"],
    [
      "Valentini, Hylarii",
      "Valentine (priest) and Hilary (deacon), at Viterbo",
      "14110360", "Nov.3",
    ],
    ["Victoris Bracharensis", "Victor, Martyr at Braga", "14041210", "Apr.12"],
    [
      "Victoris et sociorum",
      "Victor of Marseilles and companions, Martyrs",
      "14072110", "Jul.21",
    ],
    ["Vig. Assump. Mariae", "Eve of Assumption of Mary", "14081410", "Aug.14"],
    ["Vig. Joannis Bapt.", "Eve of John the Baptist", "14062300", "Jun.23"],
    ["Vig. Om. Sanctorum", "Eve of All Saints' Day", "14103120", "Oct.31"],
    ["Vigilia Andreae", "Eve of Andrew", "14112920", "Nov.29"],
    ["Vigilia Epiphaniae", "Eve of Epiphany", "2010500", "Jan.5"],
    ["Vigilia Laurentii", "Eve of Laurence", "14080910", "Aug.9"],
    ["Vigilia Martini", "Eve of Martin, Bishop of Tours", "14111030", "Nov.10"],
    ["Vigilia Matthaei", "Eve of Matthew, Apostle and Evangelist", "14092020", "Sep.20"],
    ["Vigilia Nat. Domini", "Christmas Eve", "2122400", "Dec.24"],
    ["Vigilia Pentecostes", "Eve of Pentecost", "8077000", ""],
    ["Vigilia Petri, Pauli", "Eve of Peter and Paul", "14062810", "Jun.28"],
    ["Vigilia Simonis, Judae", "Eve of Simon and Jude", "14102700", "Oct.27"],
    ["Vincentii", "Vincent of Saragossa, Martyr", "14012200", "Jan.22"],
    [
      "Vincentii, Orontii, Victoris",
      "Vincentius, Orontius (brothers), and Victor, Martyrs, killed near Gerona",
      "14012230", "Jan.22",
    ],
    [
      "Vincentii, transl.",
      "Moving of Vincent of Saragossa's relics to Lisbon",
      "14091560", "Sep.15",
    ],
    [
      "Vincentii, transl. in Brachara",
      "Moving of Vincent of Saragossa's relics to Braga",
      "14050450", "May.4",
    ],
    ["Vincula Petri", "Peter in Chains", "14080100", "Aug.1"],
    ["Visitatio Mariae", "Visitation of Mary", "14070200", "Jul.2"],
    ["Vitalis, Valeriae", "Vitalis and Valeria, Martyrs", "14042800", "Apr.28"],
    ["XI milium Virginum", "11,000 Virgin Martyrs of Cologne", "14102100", "Oct.21"],
  ];

  foreach ($feasts as $feast) {
    $node = Node::create([
      'type' => 'parties',
      'title' => $feast[0],
    ]);

    $node->body->value = $feast[1];
    $node->field_feast_code->value = $feast[2];
    $node->save();
  }
}

/**
 * Feed book_type taxonomy.
 */
function sb_core_deploy_108003(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'book_type']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['-indeterminado-', '-undetermined-'],
    ['-n/a-', '-n/a-'],
    ['Antifonário', 'Antiphoner'],
    ['Bíblia', 'Bible'],
    ['Breviário', 'Breviary'],
    ['Capituleiro', 'Capitulary'],
    ['Cerimonial', 'Cerimonial'],
    ['Colectário', 'Collectar'],
    ['Compósito', 'Composite'],
    ['Costumeiro', 'Customary'],
    ['Devocionário', 'Devocionary'],
    ['Diurnal', 'Diurnal'],
    ['Evangeliário', 'Evangeliary'],
    ['Gradual', 'Gradual'],
    ['Hinário', 'Hymnal'],
    ['Kirial', 'Kyrial'],
    ['Leccionário', 'Lectionary'],
    ['Livro de coro polifónico', 'Polyphonic book'],
    ['Livro de horas', 'Book of hours'],
    ['Livro didáctico', 'Didactic book'],
    ['Manual', 'Manual'],
    ['Martirológio', 'Martyrology'],
    ['Matutinário', 'Matutinal'],
    ['Miscelânea', 'Miscellany'],
    ['Missal', 'Missal'],
    ['Necrológio/Obituário', 'Necrology/Obituary'],
    ['Passionário', 'Passionary'],
    ['Pontifical', 'Pontifical'],
    ['Processionário', 'Processional'],
    ['Prosário', 'Proser/Sequentiary'],
    ['Obra de teologia e de espiritualidade', 'Books on theology and spirituality'],
    ['Responsorial', 'Responsorial'],
    ['Ritual', 'Ritual'],
    ['Sacramentário', 'Sacramentary'],
    ['Saltério', 'Psalter'],
    ['Tonário', 'Tonary'],
    ['Tropário', 'Troper'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'book_type',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed book_subcategory taxonomy.
 */
function sb_core_deploy_108004(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'book_subcategory']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Antifonário de Verão', 'Summer antiphoner'],
    ['Antifonário santoral', 'Sanctoral antiphoner'],
    ['Antifonário temporal', 'Temporal antiphoner'],
    ['Breviário de Inverno', 'Winter breviary'],
    ['Breviário de Verão', 'Summer breviary'],
    ['Breviário notado', 'Noted breviary'],
    ['Gradual santoral', 'Sanctoral gradual'],
    ['Gradual temporal', 'Temporal gradual'],
    ['Livro de coro', 'Choirbook'],
    ['Livro de partes', 'Partbook'],
    ['Missal de Verão', 'Summer missal'],
    ['Missal plenário', 'Plenary missal'],
    ['Saltério ferial', 'Ferial psalter'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'book_subcategory',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed music taxonomy.
 */
function sb_core_deploy_108005(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'music']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['-n/a', '-n/a'],
    ['Monodia', 'Monophony'],
    ['Polifonia', 'Polyphony'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'music',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed completeness taxonomy.
 */
function sb_core_deploy_108006(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'completeness']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Completo ou quase completo', 'Complete or nearly complete'],
    ['Fragmento', 'Fragment'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'completeness',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed cursus taxonomy.
 */
function sb_core_deploy_108007(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'cursus']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Monástico', 'Monastic'],
    ['Secular', 'Secular'],
    ['desconhecido', 'unknown'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'cursus',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed locations taxonomy.
 */
function sb_core_deploy_108008(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'locations']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Alcobaça, Mosteiro de Santa Maria?', 'Alcobaça, Mosteiro de Santa Maria?'],
    ['Arouca, Mosteiro de Santa Maria', 'Arouca, Mosteiro de Santa Maria'],
    ['Braga, Catedral', 'Braga, Catedral'],
    ['Braga?', 'Braga?'],
    ['Coimbra', 'Coimbra'],
    ['Coimbra, Catedral', 'Coimbra, Catedral'],
    ['Coimbra, Mosteiro de Santa Cruz', 'Coimbra, Mosteiro de Santa Cruz'],
    ['Coimbra, Mosteiro de São Marcos', 'Coimbra, Mosteiro de São Marcos'],
    ['Coimbra, Mosteiro de São Marcos?', 'Coimbra, Mosteiro de São Marcos?'],
    ['Coimbra?', 'Coimbra?'],
    ['Desconhecida (Ordem de Cristo)', 'Desconhecida, (Ordem de Cristo)'],
    ['Évora', 'Évora'],
    ['Évora, Catedral', 'Évora, Catedral'],
    ['Guimarães, Colegiada de Nossa Senhora da Oliveira', 'Guimarães, Colegiada de Nossa Senhora da Oliveira'],
    ['Guimarães, Mosteiro de Santa Marinha da Costa', 'Guimarães, Mosteiro de Santa Marinha da Costa'],
    ['Lamego?', 'Lamego?'],
    ['Lisboa, Colecção Manuel Ivo Cruz', 'Lisboa, Colecção Manuel Ivo Cruz'],
    ['Lorvão, Mosteiro de Santa Maria', 'Lorvão, Mosteiro de Santa Maria'],
    ['Paris (F)', 'Paris (F)'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'locations',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed tradition taxonomy.
 */
function sb_core_deploy_108009(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'tradition']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Aquitana / Hispânica?', 'Aquitaine / Hispanic?'],
    ['Aquitana (tradição)', 'Aquitanian (tradition)'],
    ['Aquitana? (tradição)', 'Aquitanian? (tradition)'],
    ['Braga (uso)', 'Braga (use)'],
    ['Braga / Toledo (tradição)', 'Braga / Toledo (tradition)'],
    ['Braga / Toledo? (tradição)', 'Braga / Toledo? (tradition)'],
    ['Braga? (uso)', 'Braga? (use)'],
    ['Carmelita (tradição)', 'Carmelite (tradition)'],
    ['Carmelita? (tradição)', 'Carmelite? (tradition)'],
    ['Cartuxa (tradição)', 'Carthusian (tradition)'],
    ['Cartuxa? (tradição)', 'Carthusian? (tradition)'],
    ['Cisterciense (tradição)', 'Cistercian (tradition)'],
    ['Cisterciense? (tradição)', 'Cistercian? (tradition)'],
    ['Cluniacense (tradição)', 'Cluny (tradition)'],
    ['Cluniacense? (tradição)', 'Cluny? (tradition)'],
    ['Coimbra (uso)', 'Coimbra (use)'],
    ['Coimbra? (uso)', 'Coimbra? (use)'],
    ['Dominicana (tradição)', 'Dominican (tradition)'],
    ['Dominicana? (tradição)', 'Dominican? (tradition)'],
    ['Évora (uso)', 'Évora (use)'],
    ['Évora? (uso)', 'Évora? (use)'],
    ['Franciscana (tradição)', 'Franciscan (tradition)'],
    ['Franciscana? (tradição)', 'Franciscan? (tradition)'],
    ['Jerónima (tradição)', 'Hieronymite (tradition)'],
    ['Jerónima? (tradição)', 'Hieronymite? (tradition)'],
    ['Lisboa (uso)', 'Lisboa (use)'],
    ['Lisboa? (uso)', 'Lisboa? (use)'],
    ['Hispânica antiga (tradição)', 'Old Hispanic (tradition)'],
    ['Hispânica antiga? (tradição)', 'Old Hispanic? (tradition)'],
    ['Romana (tradição)', 'Roman (tradition)'],
    ['Romana? (tradição)', 'Roman? (tradition)'],
    ['Sens (uso)', 'Sens (use)'],
    ['Sens? (uso)', 'Sens? (use)'],
    ['Sevilhana (uso)', 'Sevillan (use)'],
    ['Sevilhana? (uso)', 'Sevillan? (use)'],
    ['Sevilhana / Romana (pós-Tridentina) (uso)', 'Sevillian / Roman (post-Tridentine) (use)'],
    ['Silves (uso)', 'Silves (use)'],
    ['Silves? (uso)', 'Silves? (use)'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'tradition',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed writing_type taxonomy.
 */
function sb_core_deploy_108010(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'writing_type']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Carolina com características visigóticas', 'Transitional (Caroline with Visigothic features)'],
    ['Gótica inicial', 'Early gothic'],
    ['Gótica', 'Gothic'],
    ['Gótica tardia', 'Late Gothic'],
    ['Humanística', 'Humanistic'],
    ['Moderna', 'Modern'],
    ['outra', 'other'],
    ['Romana redonda', 'Roman round'],
    ['Semi-romana', 'Semi-Roman'],
    ['Visigótica', 'Visigothic'],
    ['Visigótica tardia', 'Transitional (Late Visigothic)'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'writing_type',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed font_type taxonomy.
 */
function sb_core_deploy_108011(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'font_type']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Manuscrito', 'Manuscript'],
    ['Impresso', 'Print'],
    ['Manuscrito estampilhado', 'Stencilled manuscript'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'font_type',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed music_notation taxonomy.
 */
function sb_core_deploy_108012(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'music_notation']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Aquitana', 'Aquitanian'],
    ['Aquitana (variedade portuguesa)', 'Aquitanian (Portuguese variety)'],
    ['Hufnagelschrift', 'Hufnagelschrift'],
    ['Mensural (negra)', 'Mensural (black)'],
    ['Mensural (branca)', 'Mensural (white)'],
    ['Moderna', 'Modern'],
    ['Notação de cantochão moderna branca', 'Modern chant notation (white)'],
    ['Notação de cantochão moderna negra', 'Modern chant notation (black)'],
    ['Hispânica antiga', 'Old Hispanic'],
    ['Semimensural', 'Semi-mensural'],
    ['Quadrada', 'Square'],
    ['Tablatura', 'Tablature'],
    ['Outra', 'Other'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'music_notation',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed text_language taxonomy.
 */
function sb_core_deploy_108013(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'text_language']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Alemão', 'German'],
    ['Catalão', 'Catalan'],
    ['Castelhano', 'Castilian'],
    ['Francês', 'French'],
    ['Grego', 'Greek'],
    ['Hebraico', 'Hebrew'],
    ['Neerlandês', 'Dutch'],
    ['Húngaro', 'Hungarian'],
    ['Inglês', 'English'],
    ['Italiano', 'Italian'],
    ['Latim', 'Latin'],
    ['Português', 'Portuguese'],
    ['outra', 'other'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'text_language',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed document_validation taxonomy.
 */
function sb_core_deploy_108014(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'document_validation']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Assinaturas', 'Signatures'],
    ['Cartas partidas por abc', 'Chirograph'],
    ['Selo de chapa', 'Dry seal'],
    ['Selo pendente em cera', 'Wax pendant seal'],
    ['Selo pendente em chumbo', 'Lead pendant seal'],
    ['Sinal de tabelião', 'Notary\'s mark'],
    ['Sinal rodado', 'Sinal rodado'],
    ['Outras', 'Others'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'document_validation',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed document_type taxonomy.
 */
function sb_core_deploy_108015(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'document_type']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Carta régia', 'Royal charter'],
    ['Doação', 'Donation'],
    ['Testamento', 'Will'],
    ['Carta de partilhas', 'Partition deed'],
    ['Tomada de posse/entrega', 'Possession letter'],
    ['Compra e venda', 'Sale deed'],
    ['Contrato enfitêutico', 'Lease deed'],
    ['Préstamo ', 'Préstamo '],
    ['Escambo', 'Exchange deed'],
    ['Composição amigável/avença', 'Composition deed'],
    ['Sentença', 'Judgement'],
    ['Procuração', 'Power of attorney'],
    ['Apresentação de um clérigo', 'Clerk appointment'],
    ['Confirmação da apresentação de um clérigo', 'Clerk appointment confirmation'],
    ['Pacto', 'Agreement deed'],
    ['Carta de quitação', 'Quittance deed'],
    ['Atestação notarial/traslado em pública forma', 'Exemplification'],
    ['Traditio', 'Traditio'],
    ['Carta de fundação', 'Foundation deed'],
    ['Carta de couto', 'Carta de couto'],
    ['Carta de arras/dote', 'Dowry deed'],
    ['Carta de alforria', 'Manumission letter'],
    ['Carta de agnição', 'Carta de agnição'],
    ['Bula', 'Bull'],
    ['Visitação', 'Visitation'],
    ['Inquirição', 'Inquisitio'],
    ['Libelo', 'Libel '],
    ['Renúncia', 'Renunciation need'],
    ['Tombo', 'Tombo'],
    ['Outra', 'Other'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'document_type',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Import feasts nodes.
 */
function sb_core_deploy_108016(): void {
  $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
  $parties = $storage_handler->loadByProperties(["type" => "parties"]);
  $storage_handler->delete($parties);

  $feasts = [
    ["- indeterminate -", "", "", ""],
    ["- none -", "", "", ""],
    ["Abdonis, Sennis", "Abdon and Sennen, Martyrs", "14073000", "Jul.30"],
    ["Ad Aquam Benedictio", "Chants for the blessing of water", "16010000", ""],
    [
      "Ad Aspersionem Aquae benedictae",
      "For the sprinkling of Holy Water",
      "16023000", "",
    ],
    [
      "Ad Aspersionem Aquae benedictae TP",
      "For the sprinkling of Holy Water in Eastertide",
      "16023080", "",
    ],
    ["Ad lavandum altaria", "Chants for the washing of the altar", "16024000", ""],
    ["Ad Mandatum", "At the Mandatum (Foot-Washing)", "7065010", ""],
    ["Ad Processionem", "For Processions", "16009000", ""],
    ["Adalberti", "Adalbert of Prague, Bishop and Martyr", "14042310", "Apr.23"],
    ["Additamenta", "Added or Miscellaneous Items", "17001000", ""],
    ["Aegidii", "Aegidius (Giles), Abbot", "14090100", "Sep.1"],
    [
      "Aemigdii, Episc. et Martyr",
      "Emygdius (Emidius), Bishop Martyr",
      "14080530", "Aug.5",
    ],
    ["Agapiti", "Agapitus, Martyr", "14081800", "Aug.18"],
    ["Agathae", "Agatha, Virgin Martyr", "14020500", "Feb.5"],
    ["Agnetis", "Agnes, Virgin Martyr", "14012100", "Jan.21"],
    ["Agnetis,8", "In week after Agnes", "14012108", ""],
    ["Alexandri et sociorum", "Alexander and Eventius, Martyrs", "14050310", "May.3"],
    ["Alexis", "Alexis, the Man of God", "14071700", "Jul.17"],
    [
      "Aloisii Gonzagae",
      "Aloysius (Luigi) Gonzaga, Confessor, Patron of youthful Catholic students.",
      "14062120", "Jun.21",
    ],
    ["Ambrosii", "Ambrose, Bishop of Milan, Doctor", "14040400", "Apr.4"],
    ["Ambrosii TP", "Ambrose, Eastertide", "14040480", "Apr. 4th ù"],
    ["Andreae", "Andrew, Apostle", "14113000", "Nov.30"],
    ["Angeli Custodis", "For Guardian Angels", "14100220", "Oct.2"],
    ["Annae", "Anne, Mother of Mary", "14072610", "Jul.26"],
    ["Annuntiatio Mariae", "Annunciation of Mary (Lady Day)", "14032500", "Mar.25"],
    ["Antiphonae Majores", "Great 'O' Antiphons", "1048010", ""],
    ["Antonii", "Antony, Abbot", "14011700", "Jan.17"],
    ["Antonii Patavini", "Anthony of Padua, Doctor", "14061300", "Jun.13"],
    ["Antonini", "Antoninus, Martyr", "14090200", "Sep.2"],
    ["Apollinaris", "Apollinaris, Bishop of Ravenna", "14072320", "Jul.23"],
    ["Apolloniae", "Apollonia, Virgin Martyr", "14020900", "Feb.9"],
    ["Appar. Michaelis", "Appearing of Michael the Archangel", "14050800", "May.8"],
    ["Ascensio Domini", "Ascension Thursday", "8065000", ""],
    ["Ascensio Domini,8", "In week after Ascension", "8065008", ""],
    ["Ascensionis Domini, in vigilia", "Eve of Ascension", "8064001", ""],
    ["Assumptio Mariae", "Assumption of Mary", "14081500", "Aug.15"],
    ["Assumptio Mariae,8", "In week after Assumption of Mary", "14081508", ""],
    ["Athanasii", "Athanasius, Archbishop of Alexandria", "14050200", "May.2"],
    ["Augustini", "Augustine, Bishop and Doctor", "14082800", "Aug.28"],
    [
      "Augustini, conv.",
      "Conversion of Augustine, Bishop and Doctor",
      "14050510", "May.5",
    ],
    [
      "Austremonii",
      "Austremonius (Stremoine), Bishop, Martyr, Apostle of Auvergne",
      "14110160", "Nov.1",
    ],
    ["Barnabae", "Barnabas, Apostle", "14061100", "Jun.11"],
    ["Bartholomaei", "Bartholomew, Apostle", "14082400", "Aug.24"],
    [
      "Basilidis et sociorum",
      "Basilides and companions (Cyrinus, Nabor, and Nazarius), Martyrs",
      "14061200", "Jun.12",
    ],
    ["Benedicti", "Benedict, Abbot", "14032100", "Mar.21"],
    ["Bernardi", "Bernard, Abbot and Doctor", "14082010", "Aug.20"],
    ["Bernardi,8", "In week after Bernard", "14082018", ""],
    [
      "Bernardini Senensis",
      "Bernardinus degl' Albizzeschi of Siena, Confessor",
      "14052010", "May.20",
    ],
    ["Blasii", "Blaise, Bishop of Sebastea, Martyr", "14020300", "Feb.3"],
    ["BMV de Monte Carmelo", "Our Lady of Mount Carmel", "14071610", "Jul.16"],
    ["Briccii", "Brice, Bishop of Tours", "14111300", "Nov.13"],
    [
      "Brunonis Abbatis",
      "Bruno, Abbot, Founder of the Carthusian Order",
      "14100610", "Oct.6",
    ],
    ["Caeciliae", "Cecilia (Cecily), Virgin Martyr", "14112200", "Nov.22"],
    ["Caesarii Arelatensis", "Caesarius, Archbishop of Arles", "14082700", "Aug.27"],
    ["Callisti", "Callistus (Calixtus) I, Pope", "14101400", "Oct.14"],
    [
      "Camilli de Lellis",
      "Camillus de Lellis, Confessor, Founder of the Canons Regular of a Good Death (Infirmis Ministrantium)",
      "14071820", "Jul.18",
    ],
    ["Cant. Canticorum Adv.", "", "xxx", ""],
    ["Cant. Canticorum p.Ep.", "", "xxx", ""],
    ["Cantica Canticorum", "", "xxx", ""],
    ["Caprasii, Abb.", "Caprasius, Abbot of Lerins", "14060110", "Jun.1"],
    ["Catharinae", "Catharine of Alexandria, Martyr", "14112500", "Nov.25"],
    ["Cathedra Petri", "Peter's Chair", "14022200", "Feb.22"],
    ["Christophori", "Christopher, Martyr", "14072510", "Jul.25"],
    ["Chrysanthi, Dariae", "Chrysanthus, Daria, Maurus, Martyrs", "14112910", "Nov.29"],
    ["Chrysogoni", "Chrysogonus, Martyr", "14112400", "Nov.24"],
    ["Circumcisio Domini", "The circumcision of Christ", "2010110", "Jan.1"],
    ["Clarae", "Clare of Assisi, Foundress of the Poor Clares", "14081200", "Aug.12"],
    ["Clementis", "Clement I, Pope and Martyr", "14112300", "Nov.23"],
    ["Columbae", "Columba of Sens, Virgin Martyr", "14123110", "Dec.31"],
    ["Comm. apostolibus et martiribus", "", "", ""],
    ["Comm. Apostolorum", "Common of Apostles", "12001000", ""],
    [
      "Comm. Apostolorum et Evangelistarum",
      "Common of Apostles and Evangelists",
      "12019000", "",
    ],
    [
      "Comm. Apostolorum sive Martyrum TP",
      "Common of Apostles or Martyrs, Eastertide",
      "12801100", "",
    ],
    ["Comm. Apostolorum TP", "Common of Apostles, Eastertide", "12801000", ""],
    ["Comm. Apostolorum,8", "Common of Apostles, in week of", "12001008", ""],
    ["Comm. Conjungium", "Common of Holy Matrons", "12012000", ""],
    ["Comm. duorum Apostolorum", "Common of two Apostles", "12001200", ""],
    ["Comm. Evangelistarum", "Common of Evangelists", "12011000", ""],
    [
      "Comm. Evangelistarum TP",
      "Common of Evangelists, Eastertide",
      "12811000", "",
    ],
    ["Comm. plurimorum Apostolorum in vigilia", "Eve of Apostles", "12001010", ""],
    [
      "Comm. plurimorum Confessorum",
      "Common of several Confessors",
      "12005000", "",
    ],
    [
      "Comm. plurimorum Confessorum non Pontificum",
      "Common of several Confessors (not Popes)",
      "12005200", "",
    ],
    [
      "Comm. plurimorum Confessorum Pontificum",
      "Common of several Confessors (Popes)",
      "12005100", "",
    ],
    ["Comm. plurimorum Martyrum", "Common of several Martyrs", "12003000", ""],
    [
      "Comm. plurimorum Martyrum TP",
      "Common of several Martyrs, Eastertide",
      "12803000", "",
    ],
    ["Comm. plurimorum Virginum", "Common of several Virgins", "12006000", ""],
    ["Comm. unius Abbatis", "Common of one Abbot", "12010000", ""],
    ["Comm. unius Apostoli", "Common of one Apostle", "12001100", ""],
    ["Comm. unius Apostoli in vigilia", "Eve of one Apostle", "12001110", ""],
    ["Comm. unius Confessoris", "Common of one Confessor", "12004000", ""],
    [
      "Comm. unius Confessoris Abbatis",
      "Common of one Confessor (Abbot)",
      "12004200", "",
    ],
    [
      "Comm. unius Confessoris et Doctoris",
      "Common of one Confessor (Doctor)",
      "12004500", "",
    ],
    [
      "Comm. unius Confessoris et Episcopi",
      "Common of one Confessor (Bishop)",
      "12004300", "",
    ],
    [
      "Comm. unius Confessoris non Episcopus",
      "Common of one Confessor (not Bishop)",
      "12004400", "",
    ],
    [
      "Comm. unius Confessoris non Pontificis",
      "Common of one Confessor (not Pope)",
      "12004700", "",
    ],
    [
      "Comm. unius Confessoris non Pontificis TP",
      "Common of one Confessor (not Pope), Eastertide",
      "12804700", "",
    ],
    [
      "Comm. unius Confessoris Pontificis",
      "Common of one Confessor (Pope)",
      "12004100", "",
    ],
    [
      "Comm. unius Confessoris Pontificis TP",
      "Common of one Confessor (Pope), Eastertide",
      "12804100", "",
    ],
    [
      "Comm. unius Confessoris TP",
      "Common of one Confessor, Eastertide",
      "12804000", "",
    ],
    [
      "Comm. unius electae",
      "Common of those chosen (not Virgins, not Martyrs)",
      "12022000", "",
    ],
    ["Comm. unius Martyris", "Common of one Martyr", "12002000", ""],
    [
      "Comm. unius Martyris non Pontificis",
      "Common of one Martyr (not Pope)",
      "12002200", "",
    ],
    [
      "Comm. unius Martyris non Virginis",
      "Common of one Martyr (not Virgin)",
      "12002300", "",
    ],
    [
      "Comm. unius Martyris Pontificis",
      "Common of one Martyr (Pope)",
      "12002100", "",
    ],
    ["Comm. unius Martyris TP", "Common of one Martyr, Eastertide", "12802000", ""],
    ["Comm. unius Virginis", "Common of one Virgin", "12007100", ""],
    [
      "Comm. unius Virginis Martyris",
      "Common of one Virgin Martyr",
      "12007000", "",
    ],
    [
      "Comm. unius Virginis non Martyris",
      "Common of one Virgin (not Martyr)",
      "12007200", "",
    ],
    ["Comm. unius Virginis TP", "Common of one Virgin, Eastertide", "12807100", ""],
    ["Conceptio Mariae", "Immaculate Conception of Mary", "14120800", "Dec.8"],
    ["Conversio Pauli", "Conversion of Paul", "14012500", "Jan.25"],
    ["Cornelii, Cypriani", "Cornelius and Cyprian, Martyrs", "14091600", "Sep.16"],
    [
      "Corporis Christi",
      "Corpus Christi (also \"Blessed Sacrament\")",
      "9015000", "",
    ],
    ["Corporis Christi,8", "In week after Corpus Christi", "9015008", ""],
    ["Cosmae, Damiani", "Cosmas and Damian, Martyrs", "14092700", "Sep.27"],
    ["Cyriaci et sociorum", "Cyriacus and companions, Martyrs", "14080800", "Aug.8"],
    ["Cyrici", "Cyricus and Julitta, Martyrs", "14061600", "Jun.16"],
    [
      "De Angelis",
      "Memorial chants for Angels, including e.g. Missa Votiva de Angelis. Feria III.",
      "12013000", "",
    ],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV Adv.", "Votive Mass/Office for Mary, Advent", "15008010", ""],
    ["De BMV Nat.", "Votive Mass/Office for Mary, Christmas", "15008030", ""],
    [
      "De BMV post Epiph.",
      "Votive Mass/Office for Mary, after Epiphany",
      "15008050", "",
    ],
    ["De BMV TP", "Votive Mass/Office for Mary, Eastertide", "15008080", ""],
    ["De caritate", "Chants for charity", "16025000", ""],
    ["De Corona Spinea", "Commemoration of the Crown of Thorns", "14081120", "Aug.11"],
    ["De festis duplicibus", "Chants for feasts of duplex rank", "16016000", ""],
    [
      "De festis duplicibus minoribus",
      "Chants for feasts of duplex minor rank",
      "16016001", "",
    ],
    [
      "De festis semiduplicibus",
      "Chants for feasts of semiduplex rank",
      "16015000", "",
    ],
    ["De festis simplicibus", "Chants for feasts of simple rank", "16039000", ""],
    ["De Job", "Summer Histories, from Job", "10300000", ""],
    ["De Machabaeis", "Summer Histories, from Maccabees", "10800000", ""],
    ["De Prophetis", "Summer Histories, from the Prophets", "10900000", ""],
    ["De Regum", "Summer Histories, from Kings", "10100000", ""],
    [
      "De Sancta Cruce",
      "Votive Mass/Office for the Holy Cross, including e.g. Missa Votiva de Sancta Cruce. Fer. VI.",
      "15011000", "",
    ],
    ["De Sanctis TP", "Common of Saints, Eastertide", "12815000", ""],
    ["De Sapientia", "Summer Histories, from Wisdom", "10200000", ""],
    [
      "De Spiritu Sancto",
      "Votive Mass/Office for the Holy Spirit, including, e.g. Missa Votiva de Spiritu Sancto. Feria V.",
      "15002000", "",
    ],
    ["De Tobia", "Summer Histories, from Tobias", "10400000", ""],
    ["De Trinitate", "Trinity Sunday", "9011000", ""],
    [
      "De victoriae christianorum apud Salado",
      "Commemoration of the victory of the Christians at the Battle of Rio Salado (also known as the Battle of Tarifa), 30 October 1340",
      "14103010", "Oct.30",
    ],
    [
      "Decem Millium Martyrum",
      "Ten Thousand Martyrs (crucified on Mount Ararat)",
      "14062240", "Jun.22",
    ],
    ["Decoll. Jo. Bapt.", "Beheading of John the Baptist", "14082900", "Aug.29"],
    [
      "Die 2 p. Epiphaniam",
      "1st day after Epiphany (2nd day \"of\" Epiphany)",
      "5010700", "Jan.7",
    ],
    ["Die 5 a. Nat. Domini", "The fifth day before Christmas", "2122100", "Dec.21"],
    ["Dionysii", "Denis (Dionysius), Bishop of Paris", "14100900", "Oct.9"],
    ["Dom. 1 Adventus", "1st Sunday of Advent", "1011000", ""],
    [
      "Dom. 1 p. Epiphaniam",
      "1st Sunday after Epiphany (Sunday within the octave of Epiphany, 'Dom. Infra Oct. Epiph.')",
      "5011010", "",
    ],
    ["Dom. 1 p. Pent.", "1st Sunday after Pentecost", "9011010", ""],
    ["Dom. 1 Quadragesimae", "1st Sunday of Lent", "7011000", ""],
    ["Dom. 10 p. Pent.", "10th Sunday after Pentecost", "9101000", ""],
    ["Dom. 11 p. Pent.", "11th Sunday after Pentecost", "9111000", ""],
    ["Dom. 12 p. Pent.", "12th Sunday after Pentecost", "9121000", ""],
    ["Dom. 13 p. Pent.", "13th Sunday after Pentecost", "9131000", ""],
    ["Dom. 14 p. Pent.", "14th Sunday after Pentecost", "9141000", ""],
    ["Dom. 15 p. Pent.", "15th Sunday after Pentecost", "9151000", ""],
    ["Dom. 16 p. Pent.", "16th Sunday after Pentecost", "9161000", ""],
    ["Dom. 17 p. Pent.", "17th Sunday after Pentecost", "9171000", ""],
    ["Dom. 18 p. Pent.", "18th Sunday after Pentecost", "9181000", ""],
    ["Dom. 19 p. Pent.", "19th Sunday after Pentecost", "9191000", ""],
    ["Dom. 2 Adventus", "2nd Sunday of Advent", "1021000", ""],
    ["Dom. 2 p. Epiph.", "2nd Sunday after Epiphany", "5021000", ""],
    ["Dom. 2 p. Pascha", "2nd Sunday after Easter", "8031000", ""],
    ["Dom. 2 p. Pascha,8", "In 3rd week after Easter", "8031008", ""],
    [
      "Dom. 2 p. Pent.",
      "2nd Sunday after Pentecost (also \"Dom. 1 p. Oct. Pent.\")",
      "9021000", "",
    ],
    ["Dom. 2 Quadragesimae", "2nd Sunday of Lent", "7021000", ""],
    ["Dom. 20 p. Pent.", "20th Sunday after Pentecost", "9201000", ""],
    ["Dom. 21 p. Pent.", "21st Sunday after Pentecost", "9211000", ""],
    ["Dom. 22 p. Pent.", "22nd Sunday after Pentecost", "9221000", ""],
    ["Dom. 23 p. Pent.", "23rd Sunday after Pentecost", "9231000", ""],
    ["Dom. 24 p. Pent.", "24th Sunday after Pentecost", "9241000", ""],
    ["Dom. 25 p. Pent.", "25th Sunday after Pentecost", "9251000", ""],
    ["Dom. 26 p. Pent.", "26th Sunday after Pentecost", "9261000", ""],
    ["Dom. 3 Adventus", "3rd Sunday of Advent", "1031000", ""],
    ["Dom. 3 p. Epiph.", "3rd Sunday after Epiphany", "5031000", ""],
    ["Dom. 3 p. Pascha", "3rd Sunday after Easter", "8041000", ""],
    ["Dom. 3 p. Pascha,8", "In 4th week after Easter", "8041008", ""],
    ["Dom. 3 p. Pent.", "3rd Sunday after Pentecost", "9031000", ""],
    ["Dom. 3 Quadragesimae", "3rd Sunday of Lent", "7031000", ""],
    ["Dom. 4 Adventus", "4th Sunday of Advent", "1041000", ""],
    ["Dom. 4 p. Epiph.", "4th Sunday after Epiphany", "5041000", ""],
    ["Dom. 4 p. Pascha", "4th Sunday after Easter", "8051000", ""],
    ["Dom. 4 p. Pascha,8", "In 5th week after Easter", "8051008", ""],
    ["Dom. 4 p. Pent.", "4th Sunday after Pentecost", "9041000", ""],
    ["Dom. 4 Quadragesimae", "4th Sunday of Lent", "7041000", ""],
    ["Dom. 5 p. Epiph.", "5th Sunday after Epiphany", "5051000", ""],
    ["Dom. 5 p. Pascha", "5th Sunday after Easter", "8061000", ""],
    ["Dom. 5 p. Pent.", "5th Sunday after Pentecost", "9051000", ""],
    ["Dom. 6 p. Epiph.", "6th Sunday after Epiphany", "5061000", ""],
    ["Dom. 6 p. Pent.", "6th Sunday after Pentecost", "9061000", ""],
    ["Dom. 7 p. Pent.", "7th Sunday after Pentecost", "9071000", ""],
    ["Dom. 8 p. Pent.", "8th Sunday after Pentecost", "9081000", ""],
    ["Dom. 9 p. Pent.", "9th Sunday after Pentecost", "9091000", ""],
    ["Dom. Adventus", "Sundays in Advent", "1001000", ""],
    ["Dom. de Passione", "5th Sunday of Lent (Passion Sunday)", "7051000", ""],
    ["Dom. in Palmis", "Palm Sunday", "7061000", ""],
    [
      "Dom. infra Oct. Apostolorum Petri et Pauli",
      "Sunday after Peter and Paul",
      "14062901", "",
    ],
    ["Dom. mensis Augusti", "Sundays in August", "11080100", ""],
    ["Dom. mensis Nov.", "Sundays in November", "11110100", ""],
    ["Dom. mensis Octobris", "Sundays in October", "11100100", ""],
    ["Dom. mensis Sept.", "Sundays in September", "11090100", ""],
    ["Dom. p. Assumptionem", "Sunday after Assumption of Mary", "14081501", ""],
    ["Dom. p. Cor. Christi", "Sunday after Corpus Christi", "9021010", ""],
    ["Dom. p. Epiphaniam", "Sundays after Epiphany", "5001000", ""],
    ["Dom. p. Martini", "Sunday after Martin", "14111101", ""],
    ["Dom. p. Nat. Dom.", "Sunday after Christmas", "3021000", ""],
    ["Dom. p. Nat. Mariae", "Sunday after Birthday of Mary", "14090801", ""],
    ["Dom. Pentecostes", "Pentecost Sunday (also \"Whitsunday\")", "8081000", ""],
    ["Dom. per annum", "Sundays, Ferial Office", "4001000", ""],
    ["Dom. post Ascensionem", "Sunday after Ascension", "8071000", ""],
    ["Dom. Quadragesimae", "Sundays in Lent", "7001000", ""],
    ["Dom. Quinquagesimae", "Quinquagesima Sunday", "6031000", ""],
    ["Dom. Resurrect.,8", "In week after Easter Sunday", "8011008", ""],
    ["Dom. Resurrectionis", "Easter Sunday", "8011000", ""],
    ["Dom. Septuagesimae", "Septuagesima Sunday", "6011000", ""],
    ["Dom. Sexagesimae", "Sexagesima Sunday", "6021000", ""],
    ["Dom. ultima ante Adventum", "The last Sunday before Advent", "9991010", ""],
    ["Dominica de BMV", "", "xxx", ""],
    ["Dominica in estate", "Sundays in summer", "10001000", ""],
    [
      "Dominici",
      "Dominic, founder of the Order of Friars Preachers",
      "14080430", "Aug.4",
    ],
    ["Donati", "Donatus, Bishop of Arezzo", "14080700", "Aug.7"],
    [
      "Elisabeth Reginae Portugalliae",
      "Elisabeth (Isabel), Widow, Queen of Portugal",
      "14070830", "Jul.4",
    ],
    ["Epiphania", "Epiphany", "5010600", "Jan.6"],
    ["Epiphania,8", "In week after Epiphany", "5010608", ""],
    ["Eulaliae Emeritensis", "Eulalia of Merida, Virgin Martyr", "", "Dec. 10"],
    ["Euphemiae", "Euphemia, Virgin and Martyr", "14091610", "Sep.16"],
    ["Eusebii Romanae", "Eusebius of Rome", "14081400", "Aug.14"],
    ["Eustachii", "Eustachius (Eustasius, Eustace), Martyr", "14092010", "Sep.20"],
    ["Evurtii", "Evurtius (Euvert), Bishop", "14090700", "Sep.7"],
    [
      "Exaltatio Crucis",
      "Holy Cross Day (Exaltation of the Cross)",
      "14091400", "Sep.14",
    ],
    [
      "Exspectationis BMV",
      "The Expectation of Mary (The Expectation of the Birth of Jesus)",
      "14121810", "Dec.18",
    ],
    ["Fabiani, Sebastiani", "Pope Fabian and Sebastian, Martyrs", "14012000", "Jan.20"],
    [
      "Felicis",
      "Felix, Bishop and Martyr (falsely called Pope Felix II)",
      "14072900", "Jul.29",
    ],
    ["Felicis Nolani", "Felix of Nola, Confessor", "14011410", "Jan.14"],
    ["Felicis, Adaucti", "Felix and Adauctus, Martyrs", "14083000", "Aug.30"],
    ["Felicitatis", "Felicitatis, Matron and Martyr", "14112310", "Nov.23"],
    ["Fer. 2 Cor. Christi", "Monday after Corpus Christi", "9022000", ""],
    ["Fer. 2 de BMV", "", "xxx", ""],
    ["Fer. 2 de Passione", "Monday, 5th week, Lent", "7052000", ""],
    ["Fer. 2 Hebd. 1 Adv.", "Monday, 1st week, Advent", "1012000", ""],
    ["Fer. 2 Hebd. 1 Quad.", "Monday, 1st week, Lent", "7012000", ""],
    ["Fer. 2 Hebd. 2 Adv.", "Monday, 2nd week, Advent", "1022000", ""],
    ["Fer. 2 Hebd. 2 p.Ep.", "Monday, 2nd week after Epiphany", "5022000", ""],
    ["Fer. 2 Hebd. 2 Quad.", "Monday, 2nd week, Lent", "7022000", ""],
    ["Fer. 2 Hebd. 3 Adv.", "Monday, 3rd week, Advent", "1032000", ""],
    ["Fer. 2 Hebd. 3 Quad.", "Monday, 3rd week, Lent", "7032000", ""],
    ["Fer. 2 Hebd. 4 Adv.", "Monday, 4th week, Advent", "1042000", ""],
    ["Fer. 2 Hebd. 4 Pasc.", "Monday, 4th week after Easte", "8042000", ""],
    ["Fer. 2 Hebd. 4 Quad.", "Monday, 4th week, Lent", "7042000", ""],
    ["Fer. 2 in estate", "Mondays in summer", "10002000", ""],
    ["Fer. 2 in Letaniis", "Rogation Monday", "8062000", ""],
    ["Fer. 2 Maj. Hebd.", "Monday, Holy Week", "7062000", ""],
    ["Fer. 2 p. Epiphaniam", "Monday, 1st week after Epiphany", "5012000", ""],
    ["Fer. 2 p. Oct.Pasch.", "Monday, 2nd week after Easter", "8022000", ""],
    ["Fer. 2 p. Pascha", "Easter Monday", "8012000", ""],
    ["Fer. 2 Pent.", "Pentecost Monday", "8082000", ""],
    ["Fer. 2 per annum", "Mondays, Ferial Office", "4002000", ""],
    ["Fer. 2 post Ascensionem", "Monday, week after Ascension", "8072000", ""],
    ["Fer. 2 Quadragesimae", "Mondays in Lent", "7002000", ""],
    ["Fer. 2 Quinquages.", "", "", ""],
    ["Fer. 2 Trinitate", "Trinity Monday", "9012000", ""],
    ["Fer. 3 Cor. Christi", "Tuesday after Corpus Christi", "9023000", ""],
    ["Fer. 3 de BMV", "", "xxx", ""],
    ["Fer. 3 de Passione", "Tuesday, 5th week, Lent", "7053000", ""],
    ["Fer. 3 et 6 de BMV", "", "xxx", ""],
    ["Fer. 3 et 6 de BMV Adv.", "", "xxx", ""],
    ["Fer. 3 Hebd. 1 Adv.", "Tuesday, 1st week, Advent", "1013000", ""],
    ["Fer. 3 Hebd. 1 Quad.", "Tuesday, 1st week, Lent", "7013000", ""],
    ["Fer. 3 Hebd. 2 Adv.", "Tuesday, 2nd week, Advent", "1023000", ""],
    ["Fer. 3 Hebd. 2 p.Ep.", "Tuesday, 2nd week after Epiphany", "5023000", ""],
    ["Fer. 3 Hebd. 2 Quad.", "Tuesday, 2nd week, Lent", "7023000", ""],
    ["Fer. 3 Hebd. 3 Adv.", "Tuesday, 3rd week, Advent", "1033000", ""],
    ["Fer. 3 Hebd. 3 Quad.", "Tuesday, 3rd week, Lent", "7033000", ""],
    ["Fer. 3 Hebd. 4 Adv.", "Tuesday, 4th week, Advent", "1043000", ""],
    ["Fer. 3 Hebd. 4 Pasc.", "Tuesday, 4th week after Easter", "8043000", ""],
    ["Fer. 3 Hebd. 4 Quad.", "Tuesday, 4th week, Lent", "7043000", ""],
    ["Fer. 3 in estate", "Tuesdays in summer", "10003000", ""],
    ["Fer. 3 in Letaniis", "Rogation Tuesday", "8063000", ""],
    ["Fer. 3 Maj. Hebd.", "Tuesday, Holy Week", "7063000", ""],
    ["Fer. 3 p. Epiphaniam", "Tuesday, 1st week after Epiphany", "5013000", ""],
    ["Fer. 3 p. Oct.Pasch.", "Tuesday, 2nd week after Easter", "8023000", ""],
    ["Fer. 3 p. Pascha", "Easter Tuesday", "8013000", ""],
    ["Fer. 3 Pent.", "Pentecost Tuesday", "8083000", ""],
    ["Fer. 3 per annum", "Tuesdays, Ferial Office", "4003000", ""],
    ["Fer. 3 post Ascensionem", "Tuesday, week after Ascension", "8073000", ""],
    ["Fer. 3 Quadragesimae", "Tuesdays in Lent", "7003000", ""],
    ["Fer. 3 Quinquagesimae", "Quinquagesima Tuesday", "6033000", ""],
    ["Fer. 3 Trinitate", "Trinity Tuesday", "9013000", ""],
    ["Fer. 4 Cinerum", "Ash Wednesday", "6034000", ""],
    ["Fer. 4 Cor. Christi", "Wednesday after Corpus Christi", "9024000", ""],
    ["Fer. 4 de BMV", "", "xxx", ""],
    ["Fer. 4 de Passione", "Wednesday, 5th week, Lent", "7054000", ""],
    ["Fer. 4 et Sabb. de BMV", "", "xxx", ""],
    ["Fer. 4 et Sabb. de BMV Adv.", "", "xxx", ""],
    ["Fer. 4 Hebd. 1 Adv.", "Wednesday, 1st week, Advent", "1014000", ""],
    ["Fer. 4 Hebd. 1 Quad.", "Wednesday, 1st week, Lent", "7014000", ""],
    ["Fer. 4 Hebd. 2 Adv.", "Wednesday, 2nd week, Advent", "1024000", ""],
    ["Fer. 4 Hebd. 2 p.Ep.", "Wednesday, 2nd week after Epiphany", "5024000", ""],
    ["Fer. 4 Hebd. 2 Quad.", "Wednesday, 2nd week, Lent", "7024000", ""],
    ["Fer. 4 Hebd. 3 Adv.", "Wednesday, 3rd week, Advent", "1034000", ""],
    ["Fer. 4 Hebd. 3 Quad.", "Wednesday, 3rd week, Lent", "7034000", ""],
    ["Fer. 4 Hebd. 4 Adv.", "Wednesday, 4th week, Advent", "1044000", ""],
    ["Fer. 4 Hebd. 4 Pasc", "Wednesday, 4th week after Easter", "8044000", ""],
    ["Fer. 4 Hebd. 4 Quad.", "Wednesday, 4th week, Lent", "7044000", ""],
    ["Fer. 4 in estate", "Wednesdays in summer", "10004000", ""],
    ["Fer. 4 in Letaniis", "Rogation Wednesday", "8064000", ""],
    ["Fer. 4 Maj. Hebd.", "Wednesday, Holy Week", "7064000", ""],
    ["Fer. 4 p. Epiphaniam", "Wednesday, 1st week after Epiphany", "5014000", ""],
    ["Fer. 4 p. Oct.Pasch.", "Wednesday, 2nd week after Easter", "8024000", ""],
    ["Fer. 4 p. Pascha", "Easter Wednesday", "8014000", ""],
    ["Fer. 4 Pent.", "Pentecost Wednesday", "8084000", ""],
    ["Fer. 4 per annum", "Wednesdays, Ferial Office", "4004000", ""],
    ["Fer. 4 post Ascensionem", "Wednesday, week after Ascension", "8074000", ""],
    ["Fer. 4 Q.T. Adventus", "Ember Day, Advent (Wednesday)", "1034009", ""],
    ["Fer. 4 Q.T. Pent.", "Ember Day, Pentecost (Wednesday)", "8084009", ""],
    ["Fer. 4 Q.T. Sept.", "Ember Day, September (Wednesday)", "11090409", ""],
    ["Fer. 4 Quadragesimae", "Wednesdays in Lent", "7004000", ""],
    ["Fer. 4 Trinitate", "Trinity Wednesday", "9014000", ""],
    ["Fer. 5 de BMV", "", "xxx", ""],
    ["Fer. 5 de Passione", "Thursday, 5th week, Lent", "7055000", ""],
    ["Fer. 5 Hebd. 1 Adv.", "Thursday, 1st week, Advent", "1015000", ""],
    ["Fer. 5 Hebd. 1 Quad.", "Thursday, 1st week, Lent", "7015000", ""],
    ["Fer. 5 Hebd. 2 Adv.", "Thursday, 2nd week, Advent", "1025000", ""],
    ["Fer. 5 Hebd. 2 p.Ep.", "Thursday, 2nd week after Epiphany", "5025000", ""],
    ["Fer. 5 Hebd. 2 Quad.", "Thursday, 2nd week, Lent", "7025000", ""],
    ["Fer. 5 Hebd. 3 Adv.", "Thursday, 3rd week, Advent", "1035000", ""],
    ["Fer. 5 Hebd. 3 Quad.", "Thursday, 3rd week, Lent", "7035000", ""],
    ["Fer. 5 Hebd. 4 Adv.", "Thursday, 4th week, Advent", "1045000", ""],
    ["Fer. 5 Hebd. 4 Pasc.", "Thursday, 4th week after Easter", "8045000", ""],
    ["Fer. 5 Hebd. 4 Quad.", "Thursday, 4th week, Lent", "7045000", ""],
    ["Fer. 5 in Cena Dom.", "Holy Thursday (Maundy Thursday)", "7065000", ""],
    ["Fer. 5 in estate", "Thursdays in summer", "10005000", ""],
    ["Fer. 5 p. Epiphaniam", "Thursday, 1st week after Epiphany", "5015000", ""],
    ["Fer. 5 p. Oct.Pasch.", "Thursday, 2nd week after Easter", "8025000", ""],
    ["Fer. 5 p. Pascha", "Easter Thursday", "8015000", ""],
    ["Fer. 5 Pent.", "Pentecost Thursday", "8085000", ""],
    ["Fer. 5 per annum", "Thursdays, Ferial Office", "4005000", ""],
    ["Fer. 5 post Cineres", "Thursday after Ash Wednesday", "6035000", ""],
    ["Fer. 5 Quadragesimae", "Thursdays in Lent", "7005000", ""],
    ["Fer. 6 Cor. Christi", "Friday after Corpus Christi", "9016000", ""],
    ["Fer. 6 de BMV", "", "xxx", ""],
    ["Fer. 6 de Passione", "Friday, 5th week, Lent", "7056000", ""],
    ["Fer. 6 Hebd. 1 Adv.", "Friday, 1st week, Advent", "1016000", ""],
    ["Fer. 6 Hebd. 1 Quad.", "Friday, 1st week, Lent", "7016000", ""],
    ["Fer. 6 Hebd. 2 Adv.", "Friday, 2nd week, Advent", "1026000", ""],
    ["Fer. 6 Hebd. 2 p.Ep.", "Friday, 2nd week after Epiphany", "5026000", ""],
    ["Fer. 6 Hebd. 2 Quad.", "Friday, 2nd week, Lent", "7026000", ""],
    ["Fer. 6 Hebd. 3 Adv.", "Friday, 3rd week, Advent", "1036000", ""],
    ["Fer. 6 Hebd. 3 Quad.", "Friday, 3rd week, Lent", "7036000", ""],
    ["Fer. 6 Hebd. 4 Adv.", "Friday, 4th week, Advent", "1046000", ""],
    ["Fer. 6 Hebd. 4 Pasc.", "Friday, 4th week after Easter", "8046000", ""],
    ["Fer. 6 Hebd. 4 Quad.", "Friday, 4th week, Lent", "7046000", ""],
    ["Fer. 6 in estate", "Fridays in summer", "10006000", ""],
    ["Fer. 6 in Parasceve", "Good Friday", "7066000", ""],
    ["Fer. 6 p. Epiphaniam", "Friday, 1st week after Epiphany", "5016000", ""],
    ["Fer. 6 p. Oct. Asc.", "Friday, after the Octave of Ascension", "8076000", ""],
    ["Fer. 6 p. Oct.Pasch.", "Friday, 2nd week after Easter", "8026000", ""],
    ["Fer. 6 p. Pascha", "Easter Friday", "8016000", ""],
    ["Fer. 6 Pent.", "Pentecost Friday", "8086000", ""],
    ["Fer. 6 per annum", "Fridays, Ferial Office", "4006000", ""],
    ["Fer. 6 post Ascensionem", "Friday after Ascension", "8066000", ""],
    ["Fer. 6 post Cineres", "Friday after Ash Wednesday", "6036000", ""],
    ["Fer. 6 Q.T. Adventus", "Ember Day, Advent (Friday)", "1036009", ""],
    ["Fer. 6 Q.T. Pent.", "Ember Day, Pentecost (Friday)", "8086009", ""],
    ["Fer. 6 Q.T. Sept.", "Ember Day, September (Friday)", "11090609", ""],
    ["Fer. 6 Quadragesimae", "Fridays in Lent", "7006000", ""],
    [
      "Fest. per annum",
      "For unspecified or miscellaneous ferial days throughout the year",
      "4000000", "",
    ],
    ["Flori", "Florus, Bishop of Lodève", "14110400", "Nov.4"],
    ["Franchae", "Franca Visalta, Virgin and Abbess, at Piacenza", "14042700", "Apr.4"],
    ["Francisci", "Francis of Assisi", "14100400", "Oct.4"],
    [
      "Francisci Xaverii",
      "Francis Xavier, Confessor, Apostle of India and Japan (canonized in 1622)",
      "14120300", "Dec.3",
    ],
    [
      "Fructuosi Archiepiscopi Bracharensis",
      "Fructuosus, Archbishop of Braga",
      "14041610", "Apr.16",
    ],
    ["Gabrielis, Archang.", "Gabriel the Archangel", "14031800", "Mar.18"],
    ["Genesii", "Genesius, Martyr", "14082530", "Aug.25"],
    ["Georgii", "George, Martyr", "14042300", "Apr.23"],
    [
      "Geraldi Archiepiscopi Bracharensis",
      "Gerald (Girald), Archbishop of Braga",
      "14120510", "Dec.5",
    ],
    ["Geraldi Aureliaci", "Gerald of Aurillac", "14101300", "Oct.13"],
    ["Geraldi Aureliaci", "Gerald of Aurillac", "14101300", "Oct.13"],
    ["Germani", "Germanus, Bishop of Auxerre", "14073100", "Jul.31"],
    ["Gervasii, Protasii", "Gervase and Protase, Martyrs", "14061900", "Jun.19"],
    ["Gordiani, Epimachi", "Gordian and Epimachus, Martyrs", "14051000", "May.10"],
    ["Gorgonii", "Gorgonius, Martyr", "14090900", "Sep.9"],
    ["Gregorii", "Gregory the Great, Pope and Doctor", "14031200", "Mar.12"],
    ["Hebd. 1 Adventus", "1st week of Advent", "1018000", ""],
    ["Hebd. 1 Quad.", "1st week of Lent", "7018000", ""],
    ["Hebd. 2 Adventus", "2nd week of Advent", "1028000", ""],
    ["Hebd. 2 p. Pascha", "2nd week after Easter", "8028000", ""],
    ["Hebd. 2 Quad.", "2nd week of Lent", "7028000", ""],
    ["Hebd. 3 p. Pascha", "3rd week after Easter", "8038000", ""],
    ["Hebd. 4 Adventus", "4th week of Advent", "1048000", ""],
    ["Hebd. de Passione", "5th week of Lent", "7058000", ""],
    ["Hebd. p. Pent.", "Weekdays after Pentecost", "9008000", ""],
    ["Hebd. per annum", "Weekdays, Ferial Office", "4008000", ""],
    ["Hebd. Quinquagesimae", "Week after Quinquagesima", "6038000", ""],
    ["Hebd. Septuagesimae", "Week after Septuagesima", "6018000", ""],
    ["Hebd. Sexagesimae", "Week after Sexagesima", "6028000", ""],
    ["Hebd. TP", "Weekdays, Eastertide", "8008000", ""],
    ["Hermetis", "Hermes, Martyr", "14082830", "Aug.28"],
    ["Hieronimi", "Jerome, Doctor", "14093000", "Sep.30"],
    ["Hilarii", "Hilary, Bishop of Poitiers, Doctor", "14011400", "Jan.14"],
    ["Hippolyti", "Hippolytus, Martyr", "14081300", "Aug.13"],
    ["Hugonis Episc. Lincolniensis", "Hugh, Bishop of Lincoln", "14111740", "Nov.17"],
    [
      "Humbelinae",
      "Humbelina (Humbleline), matron, sister of Bernard of Clairvaux",
      "14021200", "Feb.12",
    ],
    ["Ignatii", "Ignatius, Bishop of Antioch, Martyr", "14020110", "Feb.1"],
    ["Ildefonsi", "Ildephonsus, Archbishop of Toledo", "14012301", ""],
    [
      "In dedicatione Basilicae BMV de Martyribus Ulyssipponensis",
      "Dedication of the Basilica of Our Lady of the Martyrs, Lisbon",
      "14051320", "May.13",
    ],
    ["In Dedicatione Ecclesiae", "Dedication of a Church", "12008000", ""],
    ["In festo sollemni", "", "xxx", ""],
    ["In Letaniis", "General, Rogation Days", "8068010", ""],
    ["In tempore Adventus", "General, in Advent", "1000000", ""],
    [
      "In tempore belli contra Sarracenos",
      "Chants in time of war against the Saracens",
      "16021000", "",
    ],
    ["In tempore Epiphaniae", "General, after Epiphany", "5000000", ""],
    ["In tempore Nat.", "General, in Christmastide", "3000000", ""],
    [
      "In tempore oritur inter Christianos",
      "Chants in time of an uprising among Christians",
      "16022000", "",
    ],
    ["In tempore Paschae", "General, Eastertide", "8000000", ""],
    ["In tempore pestilentiae", "Chants in time of the plague", "16018000", ""],
    ["In tempore Quad.", "General, in Lent", "7000000", ""],
    ["In Triduum", "General, during the Triduum", "7069000", ""],
    ["Inventio Crucis", "Finding of the Cross", "14050300", "May.3"],
    [
      "Inventio Stephani",
      "Finding of Stephen's relics (First Martyr)",
      "14080300", "Aug.3",
    ],
    ["Invitatoria", "Invitatory antiphons or psalms", "16004000", ""],
    ["Irenes", "Irene of Santarém (Portugal)", "14102010", "Oct.20"],
    ["Isidori Episcopi Confessoris et Ecclesiae Doctoris", "", "", "Apr.4"],
    [
      "Ivonis de Kermartin",
      "Ivo of Kermartin (Yves Hélory, Yvo, Ives)",
      "14051930", "May.19",
    ],
    ["Jacobi", "James the Greater, Apostle", "14072500", "Jul.25"],
    ["Joachimi", "Joachim, the father of Mary", "14032010", "Mar.20"],
    ["Joannis Abbatis Reomensis", "John of Réome, abbot", "14012830", "Jan.28"],
    ["Joannis Baptistae", "John the Baptist", "14062400", "Jun.24"],
    ["Joannis Baptistae,8", "In week after John the Baptist", "14062408", ""],
    ["Joannis Chrysostomi", "John Chrysostom, Doctor", "14012700", "Jan.27"],
    ["Joannis Evang.", "John the Evangelist", "2122700", "Dec.27"],
    ["Joannis Evang.,8", "In week after John the Evangelist", "2122708", ""],
    ["Joannis Port. Lat.", "John before the Latin Gate", "14050600", "May.6"],
    ["Joannis, Pauli", "John and Paul, Martyrs", "14062600", "Jun.26"],
    ["Josephi", "Joseph, spouse of Mary", "14031900", "Mar.19"],
    ["Juliani, Epi.", "Julian, Bishop of Le Mans", "14012710", "Jan.27"],
    ["Juliani, Hermetis", "Julian of Brioude and Hermes, Martyrs", "14082860", "Aug.28"],
    ["Justae et Rufinae martyrum", "Jul-17", "xxx", ""],
    ["Justi", "Justus of Beauvais, Martyr", "14101810", "Oct.18"],
    [
      "Justi, Pastoris",
      "Justus and Pastor, Martyrs at Alcalá de Henares (Complutum)",
      "14080630", "Aug.6",
    ],
    ["Laurentii", "Laurence, Martyr", "14081000", "Aug.10"],
    ["Laurentii,8", "In week after Laurence", "14081008", ""],
    [
      "Lauteni",
      "Lautein (Lothenus, Lautenus), Abbot, founder of Silèze and Maximiac abbeys in the Jura mountains",
      "14110200", "Nov.2",
    ],
    ["Leocadiae", "Leocadia of Toledo, Virgin Martyr", "14120920", "Dec.9"],
    ["Leodegarii", "Leodegarius (Leger), Bishop Martyr", "14100200", "Oct.2"],
    ["Leonardi", "Leonard, Hermit", "14110610", "Nov.6"],
    ["Lucae", "Luke, Evangelist", "14101800", "Oct.18"],
    ["Luciae", "Lucy (Lucia), Virgin Martyr", "14121300", "Dec.13"],
    ["Ludovici", "Louis IX, King of France", "14082500", "Aug.25"],
    ["Ludovici Toul.", "Louis, Bishop of Toulouse", "14081900", "Aug.19"],
    ["Lupi", "Lupus (Leu), Bishop of Sens", "14090110", "Sep.1"],
    ["Lutgardae", "Lutgard, Virgin, Mystic, Cistercian Order", "14061620", "Jun.16"],
    ["Mafaldae Portugalensis", "", "xxx", ""],
    ["Malachiae", "Malachy, Bishop at Armagh, Ireland", "14110220", "Nov.2"],
    ["Marcelli", "Marcellus, Martyr", "14090420", "Sep.4"],
    ["Marcellini, Petri", "Marcellinus and Peter, Martyrs", "14060200", "Jun.2"],
    ["Marci", "Mark, Evangelist", "14042500", "Apr.25"],
    ["Marci, Marcellini", "Mark and Marcellian, Martyrs", "14061800", "Jun.18"],
    ["Marci, Pont.", "Mark, Pope", "14100700", "Oct.7"],
    ["Margaritae", "Margaret (Marina), Virgin Martyr", "14072010", "Jul.20"],
    ["Mariae ad Nives", "Mary of the Snows", "14080520", "Aug.5"],
    ["Mariae Magdalenae", "Mary Magdalene", "14072200", "Jul.22"],
    ["Mariae Salome", "Mary Salome", "14102210", "Oc.22"],
    ["Marii, Marthae", "Marius, Martha, et al., Martyrs", "14011900", "Jan.19"],
    ["Marinae", "Marina, Virgin", "14071720", "Jul.17"],
    [
      "Mart. sive Conf. TP",
      "Common of Martyrs or Confessors, Eastertide",
      "12802300", "",
    ],
    ["Marthae", "Martha, Virgin", "14072930", "Jul.29"],
    ["Martini", "Martin, Bishop of Tours", "14111100", "Nov.11"],
    [
      "Martini Archiepiscopi Bracharensis",
      "Martin, Bishop of Braga",
      "14032020", "Mar. 20",
    ],
    ["Martini,8", "In week after Martin", "14111108", ""],
    ["Matthaei", "Matthew, Apostle and Evangelist", "14092100", "Sep.21"],
    ["Matthiae", "Matthias, Apostle", "14022400", "Feb.24"],
    ["Mauri", "Maurus, Abbot", "14011510", "Jan.15"],
    ["Mauritii et sociorum", "Maurice and companions, Martyrs", "14092200", "Sep.22"],
    ["Mennae", "Mennas, Martyr", "14111110", "Nov.11"],
    ["Michaelis", "Michael the Archangel (Michaelmas)", "14092900", "Sep.29"],
    ["Monicae", "Monica, Mother of Augustine", "14050440", "May.4"],
    ["Nat. Innocentium", "Holy Innocents", "2122800", "Dec.28"],
    ["Nat. Innocentium,8", "In week after Holy Innocents", "2122808", ""],
    ["Nativitas Domini", "Christmas Day", "2122500", "Dec.25"],
    ["Nativitas Domini,8", "In week after Christmas", "2122508", ""],
    ["Nativitas Mariae", "Birthday of Mary", "14090800", "Sep.8"],
    ["Nativitas Mariae,8", "In week after Birthday of Mary", "14090808", ""],
    ["Nazarii, Celsi", "Nazarius and Celsus, Martyrs", "14072800", "Jul.28"],
    ["Nicolai", "Nicholas of Bari, Bishop of Myra", "14120600", "Dec.6"],
    ["Nicomedis", "Nicomedes, Martyr", "14091530", "Sep.15"],
    ["Nicomedis, Valeriani", "Nicomedes and Valerianus, Martyrs", "14091540", "Sep.15"],
    ["Nominis Jesu", "The Holy Name of Jesus", "14011430", "Jan.14"],
    ["Oct. Ascens. Domini", "Octave of Ascension", "8075000", ""],
    ["Oct. Corporis Christi", "Octave of Corpus Christi", "9025000", ""],
    ["Oct. Nat. Innocent.", "Octave of Holy Innocents", "2010400", "Jan.4"],
    ["Octava Agnetis", "Octave of Agnes", "14012800", "Jan.28"],
    ["Octava Andreae", "Octave of Andrew", "14120710", "Dec.7"],
    [
      "Octava Apostolorum Petri et Pauli",
      "Octave of Peter and Paul",
      "14070600", "Jul.6",
    ],
    ["Octava Assumptionis", "Octave of Assumption of Mary", "14082210", "Aug.22"],
    ["Octava Epiphaniae", "Octave of Epiphany", "5011300", "Jan.13"],
    ["Octava Joannis Bapt.", "Octave of John the Baptist", "14070100", "Jul.1"],
    ["Octava Laurentii", "Octave of Laurence", "14081700", "Aug.17"],
    ["Octava Martini", "Octave of Martin", "14111800", "Nov.18"],
    ["Octava Nat. Domini", "Octave of Christmas", "2010100", "Jan.1"],
    ["Octava Nat. Domini,8", "In week after Octave of Christmas", "2010108", ""],
    [
      "Octava Paschae",
      "Octave of Easter (also \"Dominica in Albis\")",
      "8021000", "",
    ],
    ["Octava Paschae,8", "In 2nd week after Easter", "8021008", ""],
    ["Omnium Sanctorum", "All Saints' Day", "14110100", "Nov.1"],
    ["Omnium Sanctorum,8", "In week after All Saints' Day", "14110108", ""],
    [
      "Pancratii et sociorum",
      "Pancratius (Pancras) and companions, Martyrs",
      "14051210", "May.12",
    ],
    ["Paulae", "Paula, Widow", "14012610", "Jan.26"],
    ["Pauli", "Paul, Apostle", "14063000", "Jun.30"],
    ["Pauli Heremitae", "Paul the Hermit", "14011010", "Jan.10"],
    ["Pauli,8", "In week after Paul (Apostle)", "14063008", ""],
    ["Petri", "Peter, Apostle", "14062910", "Jun.29"],
    ["Petri Alexandrini Ep. Mart.", "", "", "Nov.26"],
    [
      "Petri de Rates",
      "Peter de Rates, reputed first Bishop of Braga",
      "4042610", "Apr.26",
    ],
    ["Petri Gundisalvi", "Peter González, Dominican friar", "14041410", "Apr.14"],
    ["Petri Regalati", "Peter Regalado, Franciscan friar", "14051330", "May.13"],
    [
      "Petri, Mart.",
      "Peter the Martyr, Dominican Friar and Priest",
      "14042900", "Apr.29",
    ],
    ["Petri, Pauli", "Peter and Paul, Apostles", "14062900", "Jun.29"],
    ["Petri, Pauli,8", "In week after Peter and Paul", "14062908", ""],
    ["Philippi, Jacobi", "Philip and James the Lesser, Apostles", "14050100", "May.1"],
    ["Polycarpi", "Polycarp, Bishop of Smyrna, Martyr", "14012600", "Jan.26"],
    ["Praesentatio Mariae", "Presentation of Mary", "14112100", "Nov.21"],
    ["Praxedis", "Praxedes, Virgin", "14072100", "Jul.21"],
    ["Primi, Feliciani", "Primus and Felician, Martyrs", "14060900", "Jun.9"],
    ["Priscae", "Prisca, Virgin Martyr", "14011800", "Jan.18"],
    ["Pro amico", "Chants for the friend", "16029000", ""],
    ["Pro Defunctis", "For the dead", "13001000", ""],
    ["Pro familiaribus", "Chants for family members", "16044000", ""],
    ["Pro familiaribus", "Chants for family members", "16044000", ""],
    ["Pro febribus", "Chants for fever", "16031000", ""],
    ["Pro infirmis", "Chants for many who are sick", "16013030", ""],
    ["Pro iter agentibus", "Chants for travellers, pilgrims", "16030000", ""],
    ["Pro pace", "Chants for peace", "16019010", ""],
    [
      "Pro pace regni",
      "Chants for the peace of the realm and the church",
      "16019000", "",
    ],
    ["Pro pluvia", "Chants for rain", "16020000", ""],
    ["Pro quacumque necessitate", "For all needs", "16050000", ""],
    ["Pro remissione peccatorum", "Chants for forgiveness", "16045000", ""],
    ["Pro sacerdote", "Chants for the Priest", "16028000", ""],
    ["Pro salute vivorum", "Chants for the health of the living", "16027000", ""],
    ["Pro serenitate", "Chants for serenity", "16042000", ""],
    ["Pro sponso et sponsa", "Chants for the bride and groom", "16044000", ""],
    ["Pro tribulatione", "Chants for distress", "16026000", ""],
    ["Processi, Martiniani", "Processus and Martinian, Martyrs", "14070210", "Jul.2"],
    ["Proti, Hiacinthi", "Protus and Hyacinth, Martyrs", "14091100", "Sep.11"],
    [
      "Pudentianae, Pudentis",
      "Pudentiana and Pudens, Martyrs; Pudentiana (Potentiana), a Roman Virgin, and Pudens (Quintus Cornelius Pudens), a Roman senator and said to be the father of Praxedis, Pudentiana, Novatus, and Timotheus, husband of [],Priscilla",
      "14051910", "May.19",
    ],
    ["Purificatio Mariae", "Purification of Mary (Candlemas)", "14020200", "Feb.2"],
    ["Q.T. Quadragesimae", "Ember Days, Lent", "7010009", ""],
    ["Quadrag. Martyrorum", "Forty Martyrs of Sebaste", "14030900", "Mar.9"],
    ["Quattuor Coronatorum", "The Four Crowned Martyrs", "14110800", "Nov.8"],
    ["Quattuor Doctoribus Ecclesiae", "", "xxx", ""],
    ["Raphaelis, Archang.", "Raphael the Archangel", "14102410", "Oct.24"],
    ["Reliquiarum", "Feast of Relics", "14091500", "Sep.15"],
    ["Remigii", "Remigius (Remi), Bishop of Reims", "14100100", "Oct.1"],
    ["Rufi", "Rufus, Bishop", "14082820", "Aug.28"],
    ["Sabb. Adventus", "Saturdays in Advent", "1007000", ""],
    ["Sabb. Cor. Christi", "Saturday after Corpus Christi", "9017000", ""],
    ["Sabb. de Passione", "Saturday, 5th week, Lent", "7057000", ""],
    ["Sabb. Hebd. 1 Quad.", "Saturday, 1st week, Lent", "7017000", ""],
    ["Sabb. Hebd. 2 p. Ep.", "Saturday, 2nd week after Epiphany", "5027000", ""],
    ["Sabb. Hebd. 2 Quad.", "Saturday, 2nd week, Lent", "7027000", ""],
    ["Sabb. Hebd. 3 Quad.", "Saturday, 3rd week, Lent", "7037000", ""],
    ["Sabb. Hebd. 4 Quad.", "Saturday, 4th week, Lent", "7047000", ""],
    ["Sabb. p. Epiphaniam", "Saturday, 1st week after Epiphany", "5017000", ""],
    ["Sabb. p. Oct. Pasch.", "Saturday, 2nd week after Easter", "8027000", ""],
    ["Sabb. post Ascensionem", "Saturday after Ascension", "8067000", ""],
    ["Sabb. Quadragesimae", "Saturdays in Lent", "7007000", ""],
    ["Sabb. Sexagesimae", "Sexagesima Saturday", "6027000", ""],
    ["Sabbato 3 p. Pascha", "Saturday, 3rd week after Easter", "8037000", ""],
    ["Sabbato 4 p. Pascha", "Saturday, 4th week after Easter", "8047000", ""],
    ["Sabbato de BMV", "", "xxx", ""],
    ["Sabbato Hebd. 1 Adv.", "Saturday, 1st week, Advent", "1017000", ""],
    ["Sabbato Hebd. 2 Adv.", "Saturday, 2nd week, Advent", "1027000", ""],
    ["Sabbato Hebd. 3 Adv.", "Saturday, 3rd week, Advent", "1037000", ""],
    ["Sabbato Hebd. 4 Adv.", "Saturday, 4th week, Advent", "1047000", ""],
    ["Sabbato in Albis", "Easter Saturday (Saturday after Easter)", "8017000", ""],
    ["Sabbato in estate", "Saturdays in summer", "10007000", ""],
    ["Sabbato Pent.", "Pentecost Saturday", "8087000", ""],
    ["Sabbato per annum", "Saturdays, Ferial Office", "4007000", ""],
    ["Sabbato post Cineres", "Saturday after Ash Wednesday", "6037000", ""],
    ["Sabbato Q.T. Adventus", "Ember Day, Advent (Saturday)", "1037009", ""],
    ["Sabbato Q.T. Pent.", "Ember Day, Pentecost (Saturday)", "8087009", ""],
    ["Sabbato Q.T. Sept.", "Ember Day, September (Saturday)", "11090709", ""],
    ["Sabbato Sancto", "Holy Saturday", "7067000", ""],
    ["Sabinae", "Sabina, Martyr", "14082910", "Aug.29"],
    [
      "Sabiniani, Potentiani",
      "Sabinianus (Savinien) and Potentianus (Potentien), Bishops of Sens",
      "14101930", "Oct.19",
    ],
    [
      "Sacratissimi Cordis Jesu",
      "Most Sacred Heart of Jesus, on Friday after the second Sunday after Pentecost",
      "9026010", "",
    ],
    ["Samsonis", "Samson, Bishop of Dol", "14072810", "Jul.28"],
    [
      "Sanctorum Quinque Martyrum Ordinis Minorum",
      "Five Martyrs of Morocco",
      "14011630", "Jan.16",
    ],
    ["Saturnini", "Saturninus, Martyr Bishop of Toulouse", "14112900", "Nov.29"],
    ["Savinianus, Potentianus", "", "xxx", ""],
    ["Scholasticae", "Scholastica, Virgin", "14021000", "Feb.10"],
    ["Septem Dolorum BMV", "The Seven Sorrows of Mary", "14091550", "Sep.15"],
    ["Septem Fratrum", "Seven Brothers, Martyrs", "14071000", "Jul.10"],
    ["Sergii, Bacchi", "Sergius and Bacchus, Martyrs", "14100820", "Oct.8"],
    ["Silvestri", "Silvester I, Pope", "14123100", "Dec.31"],
    ["Simonis, Judae", "Simon, Jude (Thaddeus), Apostles", "14102800", "Oct.28"],
    ["Simplicii et sociorum", "Simplicius and companions, Martyrs", "14072920", "Jul.29"],
    ["Sixti et sociorum", "Sixtus and companions, Martyrs", "14080610", "Aug.6"],
    ["Speusippi", "Speusippus and Meleusippus, Martyrs", "14011720", "Jan.17"],
    ["Stephani", "Stephen the First Martyr", "2122600", "Dec.26"],
    [
      "Stephani Abbatis",
      "Stephen Harding, third Abbot of Citeaux, founder of the Cistercian Order",
      "14041710", "Apr.17",
    ],
    ["Stephani, Pont.", "Stephen I, Pope", "14080200", "Aug.2"],
    ["Stephani,8", "In week after Stephen", "2122608", ""],
    ["Suff. Crucis", "Memorial chants for the Holy Cross", "15050300", ""],
    [
      "Suff. Crucis TP",
      "Memorial chants for the Holy Cross, Eastertide",
      "15050380", "",
    ],
    ["Suff. Mariae TP", "Memorial chants for Mary, Eastertide", "15081580", ""],
    [
      "Suff. Om. Sanct. TP",
      "Memorial chants for All Saints, Eastertide",
      "15110180", "",
    ],
    ["Suff. pro Pace", "Memorial chants for peace", "15001000", ""],
    ["Suff. pro Penitent.", "Memorial chants for penitence", "15003000", ""],
    ["Symphoriani", "Symphorian (and Timothy), Martyrs", "14082200", "Aug.22"],
    ["Syri", "Syrus", "14120900", "Dec.9"],
    ["Taurini", "Taurinus, Bishop of Evreux", "14081130", "Aug.11"],
    [
      "Teresiae Avilensis",
      "Teresa (Teresia), Virgin, reformer of the Carmelite Order and ascetical write (canonized in 1622)",
      "14101500", "Oct.15",
    ],
    ["Theodori Tiro", "Theodore Tiro, Martyr", "14110910", "Nov.9"],
    ["Theotonii", "Theotonius, priest and prior (Santa Cruz, Coimbra)", "", "Feb.18"],
    ["Thomae Apost.", "Thomas, Apostle", "14122100", "Dec.21"],
    ["Thomae Cant.", "Thomas Becket, Bishop of Canterbury", "14122900", "Dec.29"],
    ["Thomae de Aquino", "Thomas of Aquino, Doctor of the Church", "14030700", ""],
    ["Thyrsus & Saturninus, Victor mm. Alexand.", "", "xxx", ""],
    ["Tiburtii, Susannae", "Tiburtius and Susanna, Martyrs", "14081100", "Aug.11"],
    [
      "Tiburtii, Valeriani",
      "Tiburtius, Valerian and Maximus, Martyrs",
      "14041400", "Apr.14",
    ],
    ["Timothei, Apollinaris", "Timothy and Apollinaris, Martyrs", "14082300", "Aug.23"],
    ["Transfiguratio Dom.", "Transfiguration of Jesus", "14080600", "Aug.6"],
    ["Transl. Benedicti", "Moving of Benedict's relics", "14071100", "Jul.11"],
    ["Transl. Jacobi", "Translation of James the Greater", "14123020", "Dec.30"],
    ["Transl. Martini", "Moving of Martin's relics", "14070400", "Jul.4"],
    ["Tres Mariae", "Mary, Mary Cleophae, Mary Salome", "14052520", "May.25"],
    [
      "Triumphi Sanctae Crucis apud Navas Tolosae",
      "Triumph of the Holy Cross in Las Navas de Tolosa (Battle of Las Navas de Tolosa or Battle of Al-Uqab)",
      "14071730", "Jul.17",
    ],
    ["Urbani", "Urban I, Pope and Martyr", "14052510", "May.25"],
    ["Valentini", "Valentine, Martyr", "14021400", "Feb.14"],
    [
      "Valentini, Hylarii",
      "Valentine (priest) and Hilary (deacon), at Viterbo",
      "14110360", "Nov.3",
    ],
    ["Victoris Bracharensis", "Victor, Martyr at Braga", "14041210", "Apr.12"],
    [
      "Victoris et sociorum",
      "Victor of Marseilles and companions, Martyrs",
      "14072110", "Jul.21",
    ],
    ["Vig. Assump. Mariae", "Eve of Assumption of Mary", "14081410", "Aug.14"],
    ["Vig. Joannis Bapt.", "Eve of John the Baptist", "14062300", "Jun.23"],
    ["Vig. Om. Sanctorum", "Eve of All Saints' Day", "14103120", "Oct.31"],
    ["Vigilia Andreae", "Eve of Andrew", "14112920", "Nov.29"],
    ["Vigilia Epiphaniae", "Eve of Epiphany", "2010500", "Jan.5"],
    ["Vigilia Laurentii", "Eve of Laurence", "14080910", "Aug.9"],
    ["Vigilia Martini", "Eve of Martin, Bishop of Tours", "14111030", "Nov.10"],
    ["Vigilia Matthaei", "Eve of Matthew, Apostle and Evangelist", "14092020", "Sep.20"],
    ["Vigilia Nat. Domini", "Christmas Eve", "2122400", "Dec.24"],
    ["Vigilia Pentecostes", "Eve of Pentecost", "8077000", ""],
    ["Vigilia Petri, Pauli", "Eve of Peter and Paul", "14062810", "Jun.28"],
    ["Vigilia Simonis, Judae", "Eve of Simon and Jude", "14102700", "Oct.27"],
    ["Vincentii", "Vincent of Saragossa, Martyr", "14012200", "Jan.22"],
    [
      "Vincentii, Orontii, Victoris",
      "Vincentius, Orontius (brothers), and Victor, Martyrs, killed near Gerona",
      "14012230", "Jan.22",
    ],
    [
      "Vincentii, transl.",
      "Moving of Vincent of Saragossa's relics to Lisbon",
      "14091560", "Sep.15",
    ],
    [
      "Vincentii, transl. in Brachara",
      "Moving of Vincent of Saragossa's relics to Braga",
      "14050450", "May.4",
    ],
    ["Vincula Petri", "Peter in Chains", "14080100", "Aug.1"],
    ["Visitatio Mariae", "Visitation of Mary", "14070200", "Jul.2"],
    ["Vitalis, Valeriae", "Vitalis and Valeria, Martyrs", "14042800", "Apr.28"],
    ["XI milium Virginum", "11,000 Virgin Martyrs of Cologne", "14102100", "Oct.21"],
  ];

  foreach ($feasts as $feast) {
    $node = Node::create([
      'type' => 'parties',
      'title' => $feast[0],
    ]);
    $node->body->value = $feast[1];
    $node->field_feast_code->value = $feast[2];
    $node->field_feast_date->value = $feast[3];
    $node->save();
  }
}

/**
 * Feed document_type taxonomy.
 */
function sb_core_deploy_108017(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'document_type']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Carta régia', 'Royal charter'],
    ['Doação', 'Gift'],
    ['Testamento', 'Testament'],
    ['Carta de partilhas', 'Partition deed'],
    ['Tomada de posse/entrega', 'Possession letter'],
    ['Compra e venda', 'Sale'],
    ['Contrato enfitêutico', 'Emphiteusis'],
    ['Préstamo ', 'Grant'],
    ['Escambo', 'Exchange'],
    ['Composição amigável/avença', 'Composition'],
    ['Sentença', 'Judgement'],
    ['Procuração', 'Power of attorney'],
    ['Apresentação de um clérigo', 'Clerk appointment'],
    ['Confirmação da apresentação de um clérigo', 'Clerk appointment confirmation'],
    ['Pacto', 'Agreement'],
    ['Carta de quitação', 'Quittance'],
    ['Atestação notarial/traslado em pública forma', 'Exemplification'],
    ['Traditio', 'Traditio'],
    ['Carta de foro', 'Donatio ad populandum'],
    ['Reconhecimento', 'Recognition'],
    ['Carta de arras/dote', 'Dowry'],
    ['Carta de alforria', 'Manumission letter'],
    ['Mutuum, commodatum', 'Loan'],
    ['Bula', 'Bull'],
    ['Visitação', 'Visitation'],
    ['Inquirição', 'Inquisitio'],
    ['Libelo', 'Libel '],
    ['Renúncia', 'Renunciation'],
    ['Tombo', 'Tombo'],
    ['Codicilo', 'Codicil'],
    ['Carta de fundação ou dotação', 'Endourment'],
    ['Penhora, hipoteca', 'Gage'],
    ['Outra', 'Other'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'document_type',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed document_validation taxonomy.
 */
function sb_core_deploy_108018(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'document_validation']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $types = [
    ['Assinaturas', 'Signatures'],
    ['Cartas partidas por abc', 'Chirograph'],
    ['Selo de chapa', 'Papered seal'],
    ['Selo pendente em cera', 'Wax pendent seal'],
    ['Selo pendente em chumbo', 'Lead pendent seal'],
    ['Sinal de tabelião', 'Notarial mark (or sign)'],
    ['Sinal rodado', 'Rota'],
    ['Outras', 'Others'],
  ];

  foreach ($types as $type) {
    $term = $taxonomy_storage->create([
      'vid' => 'document_validation',
      'name' => $type[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $type[0],
    ])->save();
  }
}

/**
 * Feed typology_according_number_books taxonomy.
 */
function sb_core_deploy_108019(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'typology_according_number_books']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Bínio', 'Binion'],
    ['Trínio', 'Trinion'],
    ['Quaterno', 'Quaternion'],
    ['Quínio', 'Quinion'],
    ['Sénio', 'Senion'],
    ['Sete ou mais', 'Seven or more'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'typology_according_number_books',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed gregory_rules taxonomy.
 */
function sb_core_deploy_108020(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'gregory_rules']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Seguida de forma regular', 'Regularly followed'],
    ['Seguida de forma irregular', 'Irregularly followed'],
    ['Inicia com pêlo', 'Starts with hair side'],
    ['Inicia com carne', 'Starts with flesh side'],
    ['Não seguida', 'Not followed'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'gregory_rules',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed paper_marks taxonomy.
 */
function sb_core_deploy_108021(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'paper_marks']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Pontusais em todos os fólios', 'Chain lines in every folia'],
    ['Vergaturas em todos os fólios', 'Wire lines in every folia'],
    ['Marcas de água em todos os fólios', 'Watermarks in every folia'],
    ['Pontusais nalguns fólios', 'Chain lines in some folia'],
    ['Vergaturas nalguns fólios', 'Wire lines in some folia'],
    ['Marcas de água nalguns fólios', 'Watermarks in some folia'],
    ['Outra', 'Other'],
    ['Sem marcas', 'No marks'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'paper_marks',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed grain_direction taxonomy.
 */
function sb_core_deploy_108022(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'grain_direction']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Vertical', 'Vertical'],
    ['Horizontal', 'Horizontal'],
    ['Sem grão', 'No grain'],
    ['Não aplicável', 'Not applicable'],
    ['Vertical', 'Vertical'],
    ['Horizontal', 'Horizontal'],
    ['No grain', 'No grain'],
    ['Not applicable', 'Not applicable'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'grain_direction',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed edge_treatments taxonomy.
 */
function sb_core_deploy_108023(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'edge_treatments']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Corte regular na cabeça', 'Regular cut (head)'],
    ['Corte irregular na cabeça', 'Irregular cut (head)'],
    ['Corte derivado da produção na cabeça', 'Deckle edge (head)'],
    ['Corte regular no pé', 'Regular cut (tail)'],
    ['Corte irregular no pé', 'Irregular cut (tail)'],
    ['Corte derivado da produção no pé', 'Deckle edge (tail)'],
    ['Corte regular na goteira', 'Regular cut (fore-edge)'],
    ['Corte irregular na goteira', 'Irregular cut (fore-edge)'],
    ['Corte derivado da produção na goteira', 'Deckle edge (fore-edge)'],
    ['Decoração na cabeça', 'Head decoration'],
    ['Decoração no pé', 'Tail decoration'],
    ['Decoração na goteira', 'Fore-edge decoration'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'edge_treatments',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed color taxonomy.
 */
function sb_core_deploy_108024(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'color']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Intrínseca ao material', 'Intrinsic to the material'],
    ['Azul', 'Blue'],
    ['Roxa', 'Purple'],
    ['Outras', 'Other'],
    ['Amarelo', 'Yellow'],
    ['Branco', 'White'],
    ['Castanho', 'Brown'],
    ['Cinzento', 'Grey'],
    ['Dourado', 'Gold'],
    ['Preto', 'Black'],
    ['Rosa', 'Pink'],
    ['Verde', 'Green'],
    ['Vermelho', 'Red'],
    ['Sem cobertura', 'No cover'],
    ['Sem guardas', 'No endleaves'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'color',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed standard_size_folia taxonomy.
 */
function sb_core_deploy_108025(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'standard_size_folia']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Fólio', 'Folio'],
    ['Quarto', 'Quarto'],
    ['Octavo', 'Octavo'],
    ['Duodécimo', 'Duodecimo'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'standard_size_folia',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed support_id taxonomy.
 */
function sb_core_deploy_108026(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'support_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Papel manual', 'Handmade paper'],
    ['Papel mecânico', 'Machine-made paper'],
    ['Pergaminho', 'Parchment'],
    ['Polpa de trapo', 'Rag pulp'],
    ['Polpa madeira coníferas', 'Softwood pulp'],
    ['Polpa madeira folhosas', 'Hardwood pulp'],
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Vaca/Bezerro', 'Cow/calf'],
    ['Cabra', 'Goat'],
    ['Rena/Veado', 'Reindeer/deer'],
    ['Ovelha', 'Sheep'],
    ['Porco', 'Pig'],
    ['Cavalo', 'Horse'],
    ['Foca', 'Seal'],
    ['Vegetal', 'Vegetal'],
    ['Animal', 'Animal'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'support_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed proportion taxonomy.
 */
function sb_core_deploy_108027(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'proportion']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Quadrado (1,211-1,260)', 'Square (1,211-1,260)'],
    ['Rectângulo de Pitágoras (1,307-1,359)', 'Rectangle of Pythagoras (1,307-1,359)'],
    ['Rectângulo de Pitágoras justaposto pelo lado maior a um rectângulo do número de ouro (1,341-1,395)', 'Rectangle of Pythagoras coinciding by the larger side with the golden ration rectangle (1,341-1,395)'],
    ['Rectângulo da fórmula a x a √2 (1,386-1,442)', 'Rectangle formula a x a √2 (1,386-1,442)'],
    ['Duplo rectângulo de Pitágoras (1,470-1,530)', 'Double rectangle of Pythagoras (1,470-1,530)'],
    ['Rectângulo do número de ouro (1,586-1,650)', 'Golden ration rectangle (1,586-1,650)'],
    ['Rectângulo da fórmula a x a √3 (1,698-1,766)', 'Rectangle formula a x a √3 (1,698-1,766)'],
    ['Duplo quadrado (1,960-2,040)', 'Double square (1,960-2,040)'],
    ['Rectângulo da fórmula a x a √5 (2,192-2,280)', 'Rectangle formula a x a √5 (2,192-2,280)'],
    ['Rectângulo de Pitágoras justaposto pelo lado menor a um rectângulo do número de ouro (2,893-3,010)', 'Rectangle of Pythagoras coinciding by the smaller side with the golden ration rectangle (2,893-3,010)'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'proportion',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed pricking_location taxonomy.
 */
function sb_core_deploy_108028(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'pricking_location']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Cabeça junto à espinha', 'Head near spine'],
    ['Cabeça centro', 'Head centre'],
    ['Cabeça junto à goteira', 'Head near fore-edge'],
    ['Goteira junto à cabeça', 'Fore-edge near head'],
    ['Goteira centro', 'Fore-edge centre'],
    ['Goteira junto ao pé', 'Fore-edge near tail'],
    ['Goteira (UR)', 'Fore-edge (RU)'],
    ['Espinha junto à cabeça', 'Spine near head'],
    ['Espinha centro', 'Spine centre'],
    ['Espinha junto ao pé', 'Spine tail'],
    ['Espinha (UR)', 'Spine (RU)'],
    ['Pé junto à espinha', 'Tail near spine'],
    ['Pé centro', 'Tail centre'],
    ['Pé junto à goteira', 'Tail near fore-edge'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'pricking_location',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed pricking_system taxonomy.
 */
function sb_core_deploy_108029(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'pricking_system']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['>>|>>', '>>|>>'],
    ['<<|<<', '<<|<<'],
    ['<>|<>', '<>|<>'],
    ['><|><', '><|><'],
    ['Outro', 'Other'],
    ['>>|>>', '>>|>>'],
    ['<<|<<', '<<|<<'],
    ['<>|<>', '<>|<>'],
    ['><|><', '><|><'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'pricking_system',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed pricking_process taxonomy.
 */
function sb_core_deploy_108030(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'pricking_process']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Compasso', 'Compass'],
    ['Sovela', 'Awl'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'pricking_process',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed ruling_process taxonomy.
 */
function sb_core_deploy_108031(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'ruling_process']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Ponta seca', 'Dry point'],
    ['Plumbagina', 'Lead'],
    ['Tinta', 'Ink'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'ruling_process',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed ruling_system taxonomy.
 */
function sb_core_deploy_108032(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'ruling_system']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Verso para recto', 'Verso to recto'],
    ['Recto para verso', 'Recto to verso'],
    ['Carne para pêlo', 'Hairside to fleshside'],
    ['Pêlo para carne', 'Fleshside to hairside'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'ruling_system',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed writing_ink_id taxonomy.
 */
function sb_core_deploy_108033(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'writing_ink_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Carbono', 'Carbon'],
    ['Ferrogálica', 'Iron gall'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'writing_ink_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed decoration_type taxonomy.
 */
function sb_core_deploy_108034(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'decoration_type']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Iluminura de página inteira', 'Fully painted page'],
    ['Cercaduras ou tarjas', 'Borders'],
    ['Iniciais historiadas (antropomórficas)', 'Historiated initial (anthropomorfic)'],
    ['Iniciais historiadas (zoomórficas)', 'Historiated initial (zoomorfic)'],
    ['Iniciais historiadas (híbridas)', 'Historiated initial (hybrid)'],
    ['Iniciais historiadas (mistas)', 'Historiated initial (miscellanious)'],
    ['Iniciais historiadas (outra)', 'Historiated initial (other)'],
    ['Iniciais figuradas ou figurativas (antropomórficas)', 'Figurative initial (anthropomorfic)'],
    ['Iniciais figuradas ou figurativas (zoomórficas)', 'Figurative initial (zoomorfic)'],
    ['Iniciais figuradas ou figurativas (híbridas)', 'Figurative initial (hybrid)'],
    ['Iniciais figuradas ou figurativas (mistas)', 'Figurative initial (miscellanious)'],
    ['Iniciais figuradas ou figurativas (outra)', 'Figurative initial (other)'],
    ['Iniciais fitomórficas', 'Phytomorphic initial'],
    ['Iniciais geométricas', 'Geometric initial'],
    ['Iniciais somente a cores', 'Coloured only initial'],
    ['Iniciais filigranadas', 'Pen-flourished initial'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'decoration_type',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed major_decoration_id taxonomy.
 */
function sb_core_deploy_108035(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'major_decoration_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Azurite [2CuCO₃ · Cu(OH)₂]', 'Azurite [2CuCO₃ · Cu(OH)₂]'],
    ['Índigo', 'Indigo'],
    ['Ultramarino natural [Na₆₋₁₀Al₆Si₆O₂₄S₂₋₄]', 'Natural ultramarine [Na₆₋₁₀Al₆Si₆O₂₄S₂₋₄]'],
    ['Malaquite [CuCO₃ · Cu(OH)₂]', 'Malachite [CuCO₃ · Cu(OH)₂]'],
    ['Proteinato de cobre', 'Copper proteinate'],
    ['Auripigmento [As₂S₃]', 'Orpiment [As₂S₃]'],
    ['Curcuma', 'Turmeric'],
    ['Mínio [Pb₃O₄]', 'Minium [Pb₃O₄]'],
    ['Massicote [β-PbO]', 'Massicot [β-PbO]'],
    ['Ocre amarelo', 'Yellow ochre'],
    ['Laca', 'Lac dye'],
    ['Ocre vermelho', 'Red ochre'],
    ['Vermelhão / cinábrio [α-HgS]', 'Vermilion / cinnabar [α-HgS]'],
    ['Branco de chumbo [2PbCO₃ · Pb(OH)₂]', 'Lead white [2PbCO₃ · Pb(OH)₂]'],
    ['Litargírio [α-PbO]', 'Litharge [α-PbO]'],
    ['Negro de carvão (negro marfim, negro de osso; negro de vime; negro de fuligem)', 'Carbon black (ivory black, bone black; vine black; lampblack)'],
    ['Polissacarídeo (ligante)', 'Polysaccharidic binder'],
    ['Proteína (ligante)', 'Protein binder'],
    ['Carga de Branco de chumbo [2PbCO₃ · Pb(OH)₂]', 'Lead white - extender [2PbCO₃ · Pb(OH)₂]'],
    ['Carga de Calcite [CaCO₃]', 'Calcite - extender [CaCO₃]'],
    ['Carga de Gesso [CaSO₄ · (H₂O)₀₋₂]', 'Gypsum - extender [CaSO₄ · (H₂O)₀₋₂]'],
    ['Mordente mineral (alúmen)', 'Mordant (mineral - alum)'],
    ['Mordente vegetal (taninos)', 'Mordant (tannins)'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'major_decoration_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_minor_decoration taxonomy.
 */
function sb_core_deploy_108036(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_minor_decoration']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Títulos dos capítulos', 'Chapter title'],
    ['Remates dos títulos dos capítulos', 'Chapter title decoration'],
    ['Caldeirões', 'Para-sign'],
    ['Letras caligrafadas e hastes decoradas', 'Cadels and decorated stems (ascender or descender)'],
    ['Letrinas', 'Lettrine'],
    ['Reclamos', 'Catchword'],
    ['Assinaturas', 'Signatures'],
    ['Letras de espera', 'Guide letter'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_minor_decoration',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed minor_decoration_id taxonomy.
 */
function sb_core_deploy_108037(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'minor_decoration_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Azurite [2CuCO₃ · Cu(OH)₂]', 'Azurite [2CuCO₃ · Cu(OH)₂]'],
    ['Índigo', 'Indigo'],
    ['Ultramarino natural [Na₆₋₁₀Al₆Si₆O₂₄S₂₋₄]', 'Natural ultramarine [Na₆₋₁₀Al₆Si₆O₂₄S₂₋₄]'],
    ['Malaquite [CuCO₃ · Cu(OH)₂]', 'Malachite [CuCO₃ · Cu(OH)₂]'],
    ['Proteinato de cobre', 'Copper proteinate'],
    ['Auripigmento [As₂S₃]', 'Orpiment [As₂S₃]'],
    ['Curcuma', 'Turmeric'],
    ['Mínio [Pb₃O₄]', 'Minium [Pb₃O₄]'],
    ['Massicote [β-PbO]', 'Massicot [β-PbO]'],
    ['Ocre amarelo', 'Yellow ochre'],
    ['Laca', 'Lac dye'],
    ['Ocre vermelho', 'Red ochre'],
    ['Vermelhão / cinábrio [α-HgS]', 'Vermilion / cinnabar [α-HgS]'],
    ['Branco de chumbo [2PbCO₃ · Pb(OH)₂]', 'Lead white [2PbCO₃ · Pb(OH)₂]'],
    ['Litargírio [α-PbO]', 'Litharge [α-PbO]'],
    ['Negro de carvão (negro marfim, negro de osso; negro de vime; negro de fuligem)', 'Carbon black (ivory black, bone black; vine black; lampblack)'],
    ['Polissacarídeo (ligante)', 'Polysaccharidic binder'],
    ['Proteína (ligante)', 'Protein binder'],
    ['Carga de Branco de chumbo [2PbCO₃ · Pb(OH)₂]', 'Lead white - extender [2PbCO₃ · Pb(OH)₂]'],
    ['Carga de Calcite [CaCO₃]', 'Calcite - extender [CaCO₃]'],
    ['Carga de Gesso [CaSO₄ · (H₂O)₀₋₂]', 'Gypsum - extender [CaSO₄ · (H₂O)₀₋₂]'],
    ['Mordente mineral (alúmen)', 'Mordant (mineral - alum)'],
    ['Mordente vegetal (taninos)', 'Mordant (tannins)'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'minor_decoration_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_sequence_marks taxonomy.
 */
function sb_core_deploy_108038(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_sequence_marks']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Paginação', 'Pagination'],
    ['Foliotação', 'Foliation'],
    ['Assinaturas', 'Signatures'],
    ['Reclamos', 'Catchword'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_sequence_marks',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_wear_marks taxonomy.
 */
function sb_core_deploy_108039(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_wear_marks']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Emendas', 'Correction'],
    ['Notas', 'Glosses'],
    ['Sinalefas', 'Side-note (hand and others)'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_wear_marks',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed empty_stations taxonomy.
 */
function sb_core_deploy_108040(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'empty_stations']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Uma estação vazia', 'One empty station'],
    ['Mais do que uma estação vazia', 'More than one empty station'],
    ['Todas as estações vazias observadas têm correspondência com as estações atuais', 'All observed empty stations match current stations'],
    ['Sem estações vazias', 'No empty stations'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'empty_stations',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed sewing_description taxonomy.
 */
function sb_core_deploy_108041(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'sewing_description']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Integral com tranchefila', 'All-along with endbands'],
    ['Integral sem tranchefila', 'All-along without endbands'],
    ['Alternada (cadernos um a um)', 'Skip-station'],
    ['Individual', 'Single station'],
    ['Direita simples', 'Straight simple'],
    ['Direita compacta', 'Straight pack'],
    ['Em espinha de peixe simples', 'Herringbone/Linked simple'],
    ['Em espinha de peixe compacta', 'Herringbone/Linked pack'],
    ['Sobre suportes simples', 'With simple sewing supports'],
    ['Sobre suportes simples enrolados', 'With rolled simple sewing supports'],
    ['Sobre suportes simples torcidos', 'With twisted simple sewing supports'],
    ['Sobre suportes fendidos simples', 'With simple split sewing supports'],
    ['Sobre suportes fendidos torcidos', 'With intertwisted split sewing supports'],
    ['Sobre suportes duplos simples', 'With simple double sewing supports'],
    ['Sobre suportes duplos torcidos', 'With twisted double sewing supports'],
    ['Sobre suportes duplos enrolados', 'With rolled double sewing supports'],
    ['Sobre suportes salientes', 'With raised sewing supports'],
    ['Sobre suportes embutidos', 'With recessed sewing supports'],
    ['Sobre suportes externos', 'With external sewing supports'],
    ['Não suportada', 'Unsupported'],
    ['Ponto de cadeia', 'Chain stitch'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'sewing_description',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed sewing_sections taxonomy.
 */
function sb_core_deploy_108042(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'sewing_sections']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Um a um', 'One-on'],
    ['Dois a dois', 'Two-on'],
    ['Três a três', 'Three-on'],
    ['Quatro a quatro', 'Four-on'],
    ['Cinco a cinco', 'Five-on'],
    ['Mais do que 5', 'More than 5'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'sewing_sections',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed sewing_holes taxonomy.
 */
function sb_core_deploy_108043(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'sewing_holes']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Circular', 'Round'],
    ['Fendas verticais', 'Vertical slits'],
    ['Fendas horizontais', 'Horizontal slits'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'sewing_holes',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed main_twist_sewing_thread taxonomy.
 */
function sb_core_deploy_108044(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'main_twist_sewing_thread']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['S', 'S'],
    ['Sem torção', 'No twist'],
    ['Z', 'Z'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'main_twist_sewing_thread',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed sewing_thread_id taxonomy.
 */
function sb_core_deploy_108045(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'sewing_thread_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Juta', 'Jute'],
    ['Seda', 'Silk'],
    ['Lã', 'Wool'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'sewing_thread_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed sewing_supports_id taxonomy.
 */
function sb_core_deploy_108046(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'sewing_supports_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Corda', 'Cord'],
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Juta', 'Jute'],
    ['Pele curtida', 'Tanned leather'],
    ['Pele semi-curtida', 'Tawed leather'],
    ['Vaca/Bezerro', 'Cow/calf'],
    ['Cabra', 'Goat'],
    ['Rena/Veado', 'Reindeer/deer'],
    ['Ovelha', 'Sheep'],
    ['Porco', 'Pig'],
    ['Cavalo', 'Horse'],
    ['Foca', 'Seal'],
    ['Vegetal', 'Vegetal'],
    ['Animal', 'Animal'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'sewing_supports_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed board_attachment taxonomy.
 */
function sb_core_deploy_108047(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'board_attachment']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Empaste (laço de volta inteira)', 'Lacing (round)'],
    ['Empaste (laço com nó)', 'Lacing (knot)'],
    ['Empaste (sigmático)', 'Lacing (long)'],
    ['Empaste (semi-sigmático)', 'Lacing (short)'],
    ['Empaste (variante de semi-sigmático)', 'Lacing (out-entering short lacing)'],
    ['Empaste (outro)', 'Lacing (other)'],
    ['Colagem dos suportes de costura', 'Surface adhering (slips)'],
    ['Cosido', 'Sewn'],
    ['Costura das folhas de guarda', 'Sewn-endleaves'],
    ['Colagem das folhas de guarda', 'Adhering endleaves'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'board_attachment',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed board_attachment_securing taxonomy.
 */
function sb_core_deploy_108048(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'board_attachment_securing']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Autofixação', 'Self-securing'],
    ['Cunha', 'Wedge'],
    ['Cavilha', 'Wooden peg'],
    ['Prego', 'Nail'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'board_attachment_securing',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_of_endbands taxonomy.
 */
function sb_core_deploy_108049(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_of_endbands']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Cosida', 'Sewn'],
    ['Colada', 'Stuck-on'],
    ['Com alma simples (tira)', 'With simple cores (strap)'],
    ['Com alma simples (tira com lado saliente ao centro)', 'With simple cores (strap with extended central side)'],
    ['Com alma simples enrolada', 'With simple rolled cores'],
    ['Com alma simples torcida', 'With simple twisted cores'],
    ['Com alma dupla', 'With double simple cores'],
    ['Com alma dupla enrolado', 'With double rolled cores'],
    ['Com alma dupla torcido', 'With double twisted cores'],
    ['Com reforço (em meia lua)', 'With round tab'],
    ['Com reforço (retangular)', 'With square tab'],
    ['Com reforço (outro)', 'With tab (other)'],
    ['Com requife corrido simples', 'With simple wound tiedown'],
    ['Com requife corrido com laçada', 'With bead tiedown'],
    ['Com requife em trança', 'With braided tiedown'],
    ['Com requife colorido (1 cor)', 'One colour tiedown'],
    ['Com requife colorido (2 cores)', 'Two colours tiedown'],
    ['Com requife colorido (mais que 2 cores)', 'More than two colours tiedown'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_of_endbands',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed squares taxonomy.
 */
function sb_core_deploy_108050(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'squares']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem seixas', 'No squares'],
    ['Inferiores a 1 cm', 'Less than 10 mm'],
    ['Superiores a 1 cm', 'Equal or above 10 mm'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'squares',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed board_edges_outer_side taxonomy.
 */
function sb_core_deploy_108051(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'board_edges_outer_side']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Esquadria', 'Square'],
    ['Boleado', 'Cushion'],
    ['Biselado', 'Bevel'],
    ['Esquadria com remoção de aresta', 'Square with edge removal'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'board_edges_outer_side',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed board_edges_inner_side taxonomy.
 */
function sb_core_deploy_108052(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'board_edges_inner_side']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Esquadria', 'Square'],
    ['Boleado', 'Cushion'],
    ['Biselado', 'Bevel'],
    ['Esquadria com remoção de aresta', 'Square with edge removal'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'board_edges_inner_side',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed entry_carvings_sewing_support taxonomy.
 */
function sb_core_deploy_108053(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'entry_carvings_sewing_support']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Circulares', 'Circular'],
    ['Rectangulares', 'Rectangle'],
    ['Na espessura da pasta', 'At the board thickness'],
    ['Na capa', 'At the outer cover'],
    ['Na contracapa', 'At the inner cover'],
    ['Outros', 'Other'],
    ['Não aplicável', 'Not applicable'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'entry_carvings_sewing_support',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed entry_carvings_endband_cores taxonomy.
 */
function sb_core_deploy_108054(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'entry_carvings_endband_cores']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Circulares', 'Circular'],
    ['Rectangulares', 'Rectangle'],
    ['Na espessura da pasta', 'At the board thickness'],
    ['Na capa', 'At the outer cover'],
    ['Na contracapa', 'At the inner cover'],
    ['Outros', 'Other'],
    ['Não aplicável', 'Not applicable'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'entry_carvings_endband_cores',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed direction_channel_sewing_support taxonomy.
 */
function sb_core_deploy_108055(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'direction_channel_sewing_support']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Horizontal', 'Horizontal'],
    ['Em ângulo', 'Angled'],
    ['Outro', 'Other'],
    ['Sem calha', 'No channels'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'direction_channel_sewing_support',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed direction_channels_endband_cores taxonomy.
 */
function sb_core_deploy_108056(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'direction_channels_endband_cores']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Horizontal', 'Horizontal'],
    ['Em ângulo', 'Angled'],
    ['Outro', 'Other'],
    ['Sem calhas', 'No channels'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'direction_channels_endband_cores',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed pos_channels_sewing_supports taxonomy.
 */
function sb_core_deploy_108057(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'pos_channels_sewing_supports']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Alinhadas', 'Aligned'],
    ['Desalinhadas por menos de 1 cm', 'Unaligned by less than 10 mm'],
    ['Desalinhadas por mais de 1 cm', 'Unaligned by more than 10 mm'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'pos_channels_sewing_supports',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed shape_holes taxonomy.
 */
function sb_core_deploy_108058(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'shape_holes']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Circulares', 'Circular'],
    ['Rectangulares', 'Rectangular'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'shape_holes',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed boards_id taxonomy.
 */
function sb_core_deploy_108059(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'boards_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Madeira', 'Wood'],
    ['Carvalho', 'Oak'],
    ['Choupo', 'Poplar'],
    ['Faia', 'Beech'],
    ['Abeto', 'Spruce'],
    ['Sobreiro', 'Cork Oak'],
    ['Pinheiro', 'Pine'],
    ['Cedro', 'Cedar'],
    ['Cartão', 'Cardboard'],
    ['Papelão', 'Laminated paper'],
    ['Cartolina', 'Cartonnage'],
    ['Pergaminho laminado', 'Laminated Parchment'],
    ['Polpa de trapo', 'Rag pulp'],
    ['Polpa de madeira de coníferas', 'Softwood pulp'],
    ['Polpa de madeira de folhosas', 'Hardwood pulp'],
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Vaca/Bezerro', 'Cow/calf'],
    ['Cabra', 'Goat'],
    ['Rena/Veado', 'Reindeer/deer'],
    ['Ovelha', 'Sheep'],
    ['Porco', 'Pig'],
    ['Cavalo', 'Horse'],
    ['Foca', 'Seal'],
    ['Vegetal', 'Vegetal'],
    ['Animal', 'Animal'],
    ['Outro', 'Other'],
    ['Sem pastas', 'No boards'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'boards_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_cover taxonomy.
 */
function sb_core_deploy_108060(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_cover']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Inteira', 'Full'],
    ['Meia', 'Half'],
    ['A três quartos', 'Three-quarters'],
    ['Rente', 'Cut flush'],
    ['Com virados', 'Turned-in'],
    ['Com extensões', 'With extensions'],
    ['Camisa', 'Chemise'],
    ['Outra', 'Other'],
    ['Sem cobertura', 'No cover'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_cover',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed cover_mitres taxonomy.
 */
function sb_core_deploy_108061(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'cover_mitres']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Cosidos', 'Sewn'],
    ['Abertos', 'Open'],
    ['Tangentes', 'Butt'],
    ['Sobrepostos (goteira por cima)', 'Lapped (fore-edge on top)'],
    ['Sobrepostos (cabeça/pé por cima)', 'Lapped (head/tail on top)'],
    ['Com língua', 'Tongued'],
    ['Dobrados', 'Folded corners'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'cover_mitres',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed attachment_cover_boards taxonomy.
 */
function sb_core_deploy_108062(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'attachment_cover_boards']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Por encaixe', 'Fitting'],
    ['Colada', 'Adhesion'],
    ['Pregada', 'Nailed'],
    ['Pregada (com cavilha)', 'Attached (with wooden pegs)'],
    ['Pregada (com elementos de guarnição)', 'Attached (with furniture elements)'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'attachment_cover_boards',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed decoration_technique_cover taxonomy.
 */
function sb_core_deploy_108063(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'decoration_technique_cover']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem decoração', 'Without decoration'],
    ['Colorida', 'Brush-coloured'],
    ['Tingida', 'Dyed'],
    ['Pintada com pincel', 'Brush-painted'],
    ['Pintada com esponja/boneca', 'Dabbing/sponge-painted'],
    ['Pintada com rede e escova', 'Bar and brush painted'],
    ['Filigranada', 'Filigree'],
    ['Impressa', 'Printed'],
    ['Estampilhada', 'Stencil'],
    ['Bordada', 'Embroidered'],
    ['Embutido (ao mesmo nível)', 'Inlay'],
    ['Embutido (sobre a base)', 'Onlay'],
    ['Com relevo', 'Relief'],
    ['Moldada', 'Moulded'],
    ['Cavada', 'Intaglio'],
    ['Gravação a seco', 'Dry blind-tooling'],
    ['Gofragem', 'Damp blind-tooling'],
    ['Gravação a ouro', 'Gold-tooling'],
    ['Gravação a prata', 'Silver tooling'],
    ['Gravação com outra folha metálica', 'Metal-leaf tooling (other)'],
    ['Gravação com roda ou tarja', 'Tooling with rolls or fillets'],
    ['Gravação com placa', 'Plaque tooling'],
    ['Gravação com ferros individuais', 'Individual hand tooling'],
    ['Gravação com viradores', 'Spine-panel tooling'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'decoration_technique_cover',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed decoration_pattern_cover taxonomy.
 */
function sb_core_deploy_108064(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'decoration_pattern_cover']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem decoração', 'No decoration'],
    ['Marmoreado', 'Marbled'],
    ['Manchado', 'Stained'],
    ['Salpicado', 'Spattered'],
    ['Pontilhado', 'Sprinkled'],
    ['Axadrezado', 'Plaid'],
    ['Granulado', 'Granular'],
    ['Outro padrão', 'Other pattern'],
    ['Abstractos', 'Abstract motifs'],
    ['Filigranados', 'Filigree motifs'],
    ['Geométricos', 'Geometric'],
    ['Moldura', 'Frame'],
    ['Vegetalistas', 'Vegetal'],
    ['Zoomórficos', 'Zoomorphic'],
    ['Antropomórficos', 'Anthropomorphic'],
    ['Figurativos', 'Figurative'],
    ['Narrativos', 'Narrative'],
    ['Inscrições', 'Inscriptions'],
    ['Outro motivo', 'Other motive'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'decoration_pattern_cover',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed wear_marks_cover taxonomy.
 */
function sb_core_deploy_108065(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'wear_marks_cover']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem marcas de uso da cobertura', 'No cover wear marks'],
    ['Inscrições', 'Inscriptions'],
    ['Carimbos', 'Stamps'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'wear_marks_cover',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed cover_id taxonomy.
 */
function sb_core_deploy_108066(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'cover_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Tecido/Tela', 'Fabric'],
    ['Seda', 'Silk'],
    ['Papel manual', 'Handmade paper'],
    ['Papel mecânico', 'Machine-made paper'],
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Polpa de trapo', 'Rag pulp'],
    ['Polpa de madeira de coníferas', 'Softwood pulp'],
    ['Polpa de madeira de folhosas', 'Hardwood pulp'],
    ['Marfim', 'Ivory'],
    ['Pergaminho', 'Parchment'],
    ['Pele curtida', 'Tanned leather'],
    ['Pele semi-curtida com alúmen', 'Alum tawed leather'],
    ['Vaca/Bezerro', 'Cow/calf'],
    ['Cabra', 'Goat'],
    ['Rena/Veado', 'Reindeer/deer'],
    ['Ovelha', 'Sheep'],
    ['Porco', 'Pig'],
    ['Cavalo', 'Horse'],
    ['Foca', 'Seal'],
    ['Vegetal', 'Vegetal'],
    ['Animal', 'Animal'],
    ['Metal', 'Metal'],
    ['Cobre (Cu)', 'Copper (Cu)'],
    ['Ferro (Fe)', 'Iron (Fe)'],
    ['Alumínio (Al)', 'Aluminium (Al)'],
    ['Zinco (Zn)', 'Zinc (Zn)'],
    ['Crómio (Cr)', 'Chromium (Cr)'],
    ['Chumbo (Pb)', 'Lead (Pb)'],
    ['Magnésio (Mg)', 'Magnesium (Mg)'],
    ['Cobalto (Co)', 'Cobalt (Co)'],
    ['Prata (Ag)', 'Silver (Ag)'],
    ['Outro', 'Other'],
    ['Sem cobertura', 'No cover'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'cover_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed cover_decoration_id taxonomy.
 */
function sb_core_deploy_108067(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'cover_decoration_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Corantes', 'Dyes'],
    ['Pigmentos', 'Pigments'],
    ['Ligantes', 'Binders'],
    ['Cargas', 'Extenders'],
    ['Adesivos', 'Adhesives'],
    ['Óleos', 'Oils'],
    ['Agar-agar', 'Seaweeds'],
    ['Ácidos', 'Acids (etching)'],
    ['Folhas metálicas', 'Metal foils'],
    ['Outro', 'Others'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'cover_decoration_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed primary_cover taxonomy.
 */
function sb_core_deploy_108068(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'primary_cover']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Inteira', 'Full'],
    ['Meia', 'Half'],
    ['A três quartos', 'Three-quarter'],
    ['Rente', 'Cut flush'],
    ['Com virados', 'With turn-ins'],
    ['Com extensões', 'With extensions'],
    ['Camisa', 'Slipcase'],
    ['Outra', 'Other'],
    ['Sem cobertura interna', 'No primary cover'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'primary_cover',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed linings taxonomy.
 */
function sb_core_deploy_108069(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'linings']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Inteiro na lombada (sem recortes)', 'Full-height (continuous)'],
    ['Inteiro na lombada (com recortes para suportes)', 'One-piece slotted spine linings'],
    ['Individuais (entre todos os suportes)', 'Panel spine linings along the entire spine'],
    ['Individuais (entre alguns suportes)', 'Panel spine linings along parts of the spine'],
    ['Individuais (na cabeça)', 'Panel spine linings only on the head'],
    ['Individuais (no pé)', 'Panel spine linings only on the tail'],
    ['Ao longo de toda a cabeça', 'Horizontal linings across the entire head'],
    ['Ao longo de todo o pé', 'Horizontal linings across the entire tail'],
    ['Outro', 'Others'],
    ['Sem reforços', 'No linings'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'linings',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_endleaves taxonomy.
 */
function sb_core_deploy_108070(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_endleaves']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Guardas simples', 'Fold endleaves'],
    ['Guardas duplas', 'Two unit endleaves'],
    ['Guardas cosidas', 'Sewn endleaves'],
    ['Guardas coladas', 'Tipped endleaves'],
    ['Guardas toda à volta do corpo do livro', 'Wrapper-type endleaves'],
    ['Guardas rígidas', 'Stiff endpapers'],
    ['Só guarda(s) volante(s)', 'Only flyleaf'],
    ['Só contraguarda(s)', 'Only pastedown'],
    ['Outro', 'Other'],
    ['Sem guardas', 'No endleaves'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_endleaves',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed watermarks_endleaves taxonomy.
 */
function sb_core_deploy_108071(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'watermarks_endleaves']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Pontusais em todos os fólios', 'Chain lines in every folio'],
    ['Vergaturas em todos os fólios', 'Wire lines in every folio'],
    ['Marcas figurativas em todos os fólios', 'Other marks in every folio'],
    ['Pontusais nalguns fólios', 'Chain lines in some folio'],
    ['Vergaturas nalguns fólios', 'Wire lines in some folio'],
    ['Marcas figurativas nalguns fólios', 'Other marks in some folio'],
    ['Sem marcas de água', 'No watermarks'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'watermarks_endleaves',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed decoration_technique_end taxonomy.
 */
function sb_core_deploy_108072(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'decoration_technique_end']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem decoração', 'Without decoration'],
    ['Colorida', 'Brush-coloured'],
    ['Tingida', 'Dyed'],
    ['Pintada com pincel', 'Brush-painted'],
    ['Pintada com esponja/boneca', 'Dabbing/sponge-painted'],
    ['Pintada com rede e escova', 'Bar and brush painted'],
    ['Filigranada', 'Filigree'],
    ['Impressa', 'Printed'],
    ['Estampilhada', 'Stencil'],
    ['Revestida a seda', 'Silk coated'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'decoration_technique_end',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed wear_marks_endleaves taxonomy.
 */
function sb_core_deploy_108073(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'wear_marks_endleaves']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem marcas de uso nas guardas', 'No wear marks on the endleaves'],
    ['Inscrições', 'Inscriptions'],
    ['Carimbos', 'Stamps'],
    ['Outra', 'Others'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'wear_marks_endleaves',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed dimension_endleaves taxonomy.
 */
function sb_core_deploy_108074(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'dimension_endleaves']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Igual ao corpo do livro', 'Same as the bookblock'],
    ['Maiores que o corpo do livro', 'Bigger than the bookblock'],
    ['Menores que o corpo do livro', 'Smaller than the bookblock'],
    ['Sem guardas', 'No endleaves'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'dimension_endleaves',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed endleaves_id taxonomy.
 */
function sb_core_deploy_108075(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'endleaves_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Papel manual', 'Handmade paper'],
    ['Papel mecânico', 'Machine-made paper'],
    ['Polpa de trapo', 'Rag pulp'],
    ['Polpa de madeira de coníferas', 'Softwood pulp'],
    ['Polpa de madeira de folhosas', 'Hardwood pulp'],
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Pergaminho', 'Parchment'],
    ['Vaca/Bezerro', 'Cow/Calf'],
    ['Cabra', 'Goat'],
    ['Rena/Veado', 'Reindeer/Deer'],
    ['Ovelha', 'Sheep'],
    ['Porco', 'Pig'],
    ['Cavalo', 'Horse'],
    ['Foca', 'Seal'],
    ['Vegetal', 'Vegetal'],
    ['Animal', 'Animal'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'endleaves_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed cover_decoration_id taxonomy.
 */
function sb_core_deploy_108076(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'cover_decoration_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Corantes', 'Dyes'],
    ['Pigmentos', 'Pigments'],
    ['Ligantes', 'Binders'],
    ['Cargas', 'Extenders'],
    ['Ácidos', 'Acids (etching)'],
    ['Folhas metálicas', 'Metal foils'],
    ['Outro', 'Others'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'cover_decoration_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_fastening taxonomy.
 */
function sb_core_deploy_108077(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_fastening']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['De colchete (placa de orifício e espigão)', 'With clasp (open pin clasp)'],
    ['De colchete (outros)', 'With clasp (other)'],
    ['Com ataca(s)', 'With strap(s)'],
    ['Corda e botão', 'Cord and button'],
    ['Fitas', 'Ties'],
    ['De argola', 'Loop'],
    ['Outro', 'Others'],
    ['Sem fecho(s)', 'No fastening(s)'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_fastening',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed location_fastening taxonomy.
 */
function sb_core_deploy_108078(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'location_fastening']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Cabeça junto à espinha', 'Head near spine'],
    ['Cabeça ao centro', 'Head centre'],
    ['Cabeça junto à goteira', 'Head near fore-edge'],
    ['Pé junto à espinha', 'Tail near spine'],
    ['Pé ao centro', 'Tail centre'],
    ['Pé junto à goteira', 'Tail near fore-edge'],
    ['Goteira junto à cabeça', 'Fore-edge near head'],
    ['Goteira ao centro', 'Fore-edge centre'],
    ['Goteira junto ao pé', 'Fore-edge near tail'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'location_fastening',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed shape_fastenings taxonomy.
 */
function sb_core_deploy_108079(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'shape_fastenings']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Quadrangular', 'Quadrangular'],
    ['Rectangular', 'Rectangular'],
    ['Circular', 'Circular'],
    ['Triangular', 'Triangular'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'shape_fastenings',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed decoration_fastening taxonomy.
 */
function sb_core_deploy_108080(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'decoration_fastening']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem decoração', 'Without decoration'],
    ['Elaborada', 'Elaborate'],
    ['Simples', 'Simple'],
    ['Em relevo', 'Embossed'],
    ['Pintada', 'Painted'],
    ['Por coloração', 'By colouring'],
    ['Motivos vegetalistas', 'Vegetal motifs'],
    ['Motivos abstractos', 'Abstract motifs'],
    ['Motivos geométricos', 'Geometric motifs'],
    ['Motivos zoomórficos', 'Zoomorphic motifs'],
    ['Motivos figurativos', 'Figurative motifs'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'decoration_fastening',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed fastening_id taxonomy.
 */
function sb_core_deploy_108081(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'fastening_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Metal', 'Metal'],
    ['Cobre (Cu)', 'Copper (Cu)'],
    ['Ferro (Fe)', 'Iron (Fe)'],
    ['Alumínio (Al)', 'Aluminium (Al)'],
    ['Zinco (Zn)', 'Zinc (Zn)'],
    ['Crómio (Cr)', 'Chromium (Cr)'],
    ['Chumbo (Pb)', 'Lead (Pb)'],
    ['Magnésio (Mg)', 'Magnesium (Mg)'],
    ['Cobalto (Co)', 'Cobalt (Co)'],
    ['Tecido', 'Fabric'],
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Pele curtida', 'Tanned leather'],
    ['Pele semi-curtida', 'Alum tawed leather'],
    ['Pergaminho', 'Parchment'],
    ['Vaca/Bezerro', 'Cow/calf'],
    ['Cabra', 'Goat'],
    ['Rena/Veado', 'Reindeer/deer'],
    ['Ovelha', 'Sheep'],
    ['Porco', 'Pig'],
    ['Cavalo', 'Horse'],
    ['Foca', 'Seal'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'fastening_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_bosses taxonomy.
 */
function sb_core_deploy_108082(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_bosses']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Individuais', 'Separate'],
    ['Integrados', 'Integral'],
    ['Tacha(s)', 'Boss(es)'],
    ['Outro', 'Others'],
    ['Sem brochos', 'No bosses'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_bosses',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed location_bosses taxonomy.
 */
function sb_core_deploy_108083(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'location_bosses']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Cantos plano anterior', 'Corners right side'],
    ['Cantos plano posterior', 'Corners left side'],
    ['Centro plano anterior', 'Centre right side'],
    ['Centro plano posterior', 'Centre left side'],
    ['Outra', 'Others'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'location_bosses',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed decoration_bosses taxonomy.
 */
function sb_core_deploy_108084(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'decoration_bosses']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem decoração', 'Without decoration'],
    ['Elaborada', 'Elaborate'],
    ['Simples', 'Simple'],
    ['Em relevo', 'In relief'],
    ['Motivos vegetalistas', 'Vegetal motifs'],
    ['Motivos abstractos', 'Abstract motifs'],
    ['Motivos geométricos', 'Geometric motifs'],
    ['Motivos zoomórficos', 'Zoomorphic motifs'],
    ['Motivos figurativos', 'Figurative motifs'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'decoration_bosses',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed bosses_id taxonomy.
 */
function sb_core_deploy_108085(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'bosses_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Metal', 'Metal'],
    ['Cobre (Cu)', 'Copper (Cu)'],
    ['Ferro (Fe)', 'Iron (Fe)'],
    ['Alumínio (Al)', 'Aluminium (Al)'],
    ['Zinco (Zn)', 'Zinc (Zn)'],
    ['Crómio (Cr)', 'Chromium (Cr)'],
    ['Chumbo (Pb)', 'Lead (Pb)'],
    ['Magnésio (Mg)', 'Magnesium (Mg)'],
    ['Cobalto (Co)', 'Cobalt (Co)'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'bosses_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_corner taxonomy.
 */
function sb_core_deploy_108086(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_corner']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Individuais', 'Individual'],
    ['Integrados', 'Integral'],
    ['Combinados', 'Combined'],
    ['Outro', 'Other'],
    ['Sem cantos', 'No corners'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_corner',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed location_corner taxonomy.
 */
function sb_core_deploy_108087(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'location_corner']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Canto cabeça/goteira (plano anterior)', 'Corner head/fore-edge (right side)'],
    ['Canto cabeça/espinha (plano anterior)', 'Corner head/spine (right side)'],
    ['Canto pé/goteira (plano anterior)', 'Corner tail/fore-edge (right side)'],
    ['Canto pé/espinha (plano anterior)', 'Corner tail/spine (right side)'],
    ['Canto cabeça/goteira (plano posterior)', 'Corner head/fore-edge (left side)'],
    ['Canto cabeça/espinha (plano posterior)', 'Corner head/spine (left side)'],
    ['Canto pé/goteira (plano posterior)', 'Corner tail/fore-edge (left side)'],
    ['Canto pé/espinha (plano posterior)', 'Corner tail/spine (left side)'],
    ['Outra', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'location_corner',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed decoration_corner taxonomy.
 */
function sb_core_deploy_108088(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'decoration_corner']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sem decoração', 'Without decoration'],
    ['Elaborada', 'Elaborate'],
    ['Simples', 'Simple'],
    ['Em relevo', 'In relief'],
    ['Motivos vegetalistas', 'Vegetal motifs'],
    ['Motivos abstractos', 'Abstract motifs'],
    ['Motivos geométricos', 'Geometric motifs'],
    ['Motivos zoomórficos', 'Zoomorphic motifs'],
    ['Motivos figurativos', 'Figurative motifs'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'decoration_corner',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed corner_id taxonomy.
 */
function sb_core_deploy_108089(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'corner_id']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Metal', 'Metal'],
    ['Cobre (Cu)', 'Copper (Cu)'],
    ['Ferro (Fe)', 'Iron (Fe)'],
    ['Alumínio (Al)', 'Aluminium (Al)'],
    ['Zinco (Zn)', 'Zinc (Zn)'],
    ['Crómio (Cr)', 'Chromium (Cr)'],
    ['Chumbo (Pb)', 'Lead (Pb)'],
    ['Magnésio (Mg)', 'Magnesium (Mg)'],
    ['Cobalto (Co)', 'Cobalt (Co)'],
    ['Papel manual', 'Handmade paper'],
    ['Papel mecânico', 'Machine-made paper'],
    ['Polpa de trapo', 'Rag pulp'],
    ['Polpa de madeira de coníferas', 'Softwood pulp'],
    ['Polpa de madeira de folhosas', 'Hardwood pulp'],
    ['Algodão', 'Cotton'],
    ['Linho', 'Flax'],
    ['Cânhamo', 'Hemp'],
    ['Pergaminho', 'Parchment'],
    ['Vaca/bezerro', 'Cow/calf'],
    ['Cabra', 'Goat'],
    ['Rena/veado', 'Reindeer/deer'],
    ['Ovelha', 'Sheep'],
    ['Porco', 'Pig'],
    ['Cavalo', 'Horse'],
    ['Foca', 'Seal'],
    ['Vegetal', 'Vegetal'],
    ['Animal', 'Animal'],
    ['Outro', 'Other'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'corner_id',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed features_furniture taxonomy.
 */
function sb_core_deploy_108090(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'features_furniture']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Correntes', 'Chains'],
    ['Moldura de título', 'Title frames'],
    ['Etiquetas', 'Tags'],
    ['Placas decorativas', 'Plates'],
    ['Outro', 'Others'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'features_furniture',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed conservation_condition taxonomy.
 */
function sb_core_deploy_108091(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'conservation_condition']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Bom', 'Good'],
    ['Razoável', 'Reasonable'],
    ['Mau', 'Bad'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'conservation_condition',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed past_interventions taxonomy.
 */
function sb_core_deploy_108092(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'past_interventions']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Costura/bordado nos danos', 'Sewing/embroidery over damage'],
    ['Remendos c/ costura', 'Stitched patches'],
    ['Remendos colados', 'Glued patches'],
    ['Remendos em pergaminho colados', 'Glued parchment patches'],
    ['Remendos em papel colados', 'Glued paper patches'],
    ['Reforços/laminações de fólios', 'Folio reinforcements/laminations'],
    ['Reconstituição da escrita/imagem', 'Writing/image reconstitution'],
    ['Adições', 'Additions'],
    ['Consolidação de tintas/pigmentos', 'Ink/pigment consolidation'],
    ['Branqueamentos', 'Bleaching'],
    ['Outra', 'Other'],
    ['Sem intervenções', 'No interventions'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'past_interventions',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed recent_interventions taxonomy.
 */
function sb_core_deploy_108093(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'recent_interventions']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Higienização', 'Cleaning'],
    ['Limpeza a seco', 'Dry cleaning'],
    ['Limpeza aquosa', 'Aqueous cleaning'],
    ['Estabilização química por desacidificação', 'Chemical stabilization by deacidification'],
    ['Estabilização química por alcalinização', 'Chemical stabilization by alkalinization'],
    ['Estabilização química por branqueamento', 'Chemical stabilization by bleaching'],
    ['Estabilização química por desinfecção', 'Chemical stabilization by disinfection'],
    ['Estabilização física por consolidação de tintas/pigmentos', 'Physical stabilization by consolidation of paints/pigments'],
    ['Estabilização física por consolidação de suporte', 'Physical stabilization by consolidation of support'],
    ['Estabilização física por consolidação de rasgos', 'Physical stabilization by consolidation of tears'],
    ['Reintegração de lacunas', 'Gap reintegration'],
    ['Planificação', 'Flattening'],
    ['Outra', 'Other'],
    ['Sem intervenções', 'No interventions'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'recent_interventions',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed current_assessment taxonomy.
 */
function sb_core_deploy_108094(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'current_assessment']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sujidade', 'Dirt'],
    ['Manchas variadas', 'Miscellaneous Stains'],
    ['Foxing', 'Foxing'],
    ['Linhas de maré', 'Tide lines'],
    ['Oxidação (amarelecimento)', 'Oxidation (yellowing)'],
    ['Ondulação/encarquilhamento', 'Rippling/creasing'],
    ['Vincos', 'Creases'],
    ['Dobras inadequadas', 'Improper folding'],
    ['Papel quebradiço', 'Brittle paper'],
    ['Rasgos', 'Tears'],
    ['Lacunas', 'Gaps'],
    ['Danos por roedores/insectos', 'Rodent/insect damage'],
    ['Danos por microorganismos', 'Damage from microorganisms'],
    ['Desvanecimento de escrita/imagem', 'Fading of writing/image'],
    ['Destacamento de tintas/pigmentos', 'Fading inks/pigments'],
    ['Oxidação de tintas/pigmentos', 'Oxidation of inks/pigments'],
    ['Trespasse de tintas/pigmentos', 'Paint/pigment transfer'],
    ['Migração de tintas/pigmentos', 'Migration paint/pigment'],
    ['Fissuração de tintas/pigmentos', 'Cracking paint/pigment'],
    ['Desgaste de tintas/pigmentos', 'Wear and tear paints/pigments'],
    ['Danos por adesivos originais', 'Damage from original adhesives'],
    ['Danos por intervenções anteriores', 'Damage from previous interventions'],
    ['Outro', 'Other'],
    ['Sem danos', 'No damage'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'current_assessment',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed past_interventions_bb taxonomy.
 */
function sb_core_deploy_108095(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'past_interventions_bb']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Encadernação posterior (até ao séc. XIII)', 'Until 13th century rebinding'],
    ['Encadernação posterior (sécs. XIV-XVI)', '14th-16th century rebindings'],
    ['Encadernação posterior (sécs. XVII-XVIII)', '17th-18th Centuries rebinding'],
    ['Encadernação posterior (séc. XIX)', '19th Century rebinding'],
    ['Encadernação posterior (séc. XX)', '20th century rebinding'],
    ['Substituição de elementos da costura', 'Replacement of stitching elements'],
    ['Substituição de elementos da tranchefila', 'Replacement of parts of the endbands'],
    ['Substituição de elementos das guardas', 'Replacement of elements of the endleaves'],
    ['Substituição de elementos das pastas', 'Replacement of elements of the boards'],
    ['Substituição de elementos da cobertura', 'Replacement of cover elements'],
    ['Substituição de elementos da lombada', 'Replacement of spine elements'],
    ['Substituição de elementos metálicos', 'Replacement of metallic elements'],
    ['Restauro pontual da lombada', 'Temporary restoration of the spine'],
    ['Restauro pontual de cantos', 'Individual restoration of corners'],
    ['Restauro pontual de lacunas', 'Localized restoration of gaps'],
    ['Consolidação da cobertura', 'Consolidation of the cover'],
    ['Outra', 'Other'],
    ['Sem intervenções', 'No interventions'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'past_interventions_bb',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed recent_interventions_bb taxonomy.
 */
function sb_core_deploy_108096(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'recent_interventions_bb']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Encadernação nova de preservação', 'New Preservation Binding'],
    ['Encadernação nova fac-símile', 'New facsimile bookbinding'],
    ['Substituição de elementos da costura', 'Replacement of stitching elements'],
    ['Substituição de elementos da tranchefila', 'Replacement of parts of the endbands'],
    ['Substituição de elementos das guardas', 'Replacement of elements of the endleaves'],
    ['Substituição de elementos das pastas', 'Replacement of elements of the boards'],
    ['Substituição de elementos da cobertura', 'Replacement of cover elements'],
    ['Substituição de elementos da lombada', 'Replacement of spine elements'],
    ['Substituição de elementos metálicos', 'Replacement of metallic elements'],
    ['Restauro pontual da lombada', 'Temporary restoration of the spine'],
    ['Restauro pontual de cantos', 'Individual restoration of corners'],
    ['Restauro pontual de lacunas', 'Localized restoration of gaps'],
    ['Consolidação da cobertura', 'Consolidation of the cover'],
    ['Outra', 'Other'],
    ['Sem intervenções', 'No interventions'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'recent_interventions_bb',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed current_assessment_bb taxonomy.
 */
function sb_core_deploy_108097(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'current_assessment_bb']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Sujidade', 'Dirt'],
    ['Manchas várias', 'Various stains'],
    ['Danos por microorganismos', 'Damage by microorganisms'],
    ['Cobertura quebradiça', 'Broken cover'],
    ['Oxidação', 'Oxidation'],
    ['Red rot', 'Red rot'],
    ['Vincos/dobras', 'Creases/Folds'],
    ['Desgaste', 'Wear'],
    ['Lacunas', 'Gaps'],
    ['Lacunas na lombada', 'Gaps in spine'],
    ['Ausência de lombada', 'Absence of spine'],
    ['Deformação das pastas', 'Deformation of the boards'],
    ['Danos por roedores/insetos', 'Rodent/insect damage'],
    ['Oxidação de pastas', 'Pulp oxidation'],
    ['Ausência total de pastas', 'Full absence of boards'],
    ['Ausência parcial de pastas', 'Partial lack of pulp'],
    ['Sistema de empaste partido', 'Broken board attachment'],
    ['Costura quebrada', 'Broken seams'],
    ['Fólios/cadernos soltos', 'Loose pages/books'],
    ['Tranchefila quebrada', 'Broken endband'],
    ['Ausência total de tranchefila', 'Total lack of cover'],
    ['Ausência parcial de tranchefila', 'Partial lack of cover'],
    ['Oxidação dos elementos metálicos', 'Oxidation of metallic elements'],
    ['Adesivos originais oxidados', 'Original stickers oxidized'],
    ['Danos por intervenções anteriores', 'Damage from previous interventions'],
    ['Outra', 'Other'],
    ['Sem danos', 'No damage'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'current_assessment_bb',
      'name' => $terms_arr[1],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[0],
    ])->save();
  }
}

/**
 * Feed type_of_bookbinding taxonomy.
 */
function sb_core_deploy_108098(): void {
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'type_of_bookbinding']);

  foreach ($terms as $term) {
    $term->delete();
  }

  $list_terms = [
    ['Inboard', 'Rígida'],
    ['Limp', 'Flexível'],
    ['Medieval', 'Medieval'],
    ['Romanesque', 'Românica'],
    ['Gothic', 'Gótica'],
    ['Manueline', 'Manuelina'],
    ['Renaissance', 'Renascentista'],
    ['Contemporary', 'Contemporânea'],
    ['Monastic', 'Monástica'],
    ['Cistercian', 'Cisterciense'],
    ['Alcobaça white', 'Alcobacense branca'],
    ['Alcobaça brown', 'Alcobacense castanha'],
    ['Mozarab', 'Moçárabe'],
    ['Mudejar', 'Mudéjar'],
    ['Byzantine', 'Bizantina'],
    ['Islamic', 'Islâmica'],
    ['With extensions', 'Com abas'],
    ['Envelope', 'De envelope/solapa'],
    ['With ties', 'À ataca'],
    ['Full', 'Inteira'],
    ['Quarter', 'Meia'],
    ['Half', 'A três quartos'],
    ['Treasure', 'De luxo e/ou ourivesaria'],
    ['Heraldic', 'Heráldica'],
    ['Album', 'Álbum'],
    ['Industrial', 'Industrial'],
    ['Library/preservation', 'De biblioteca/preservação'],
    ['Archival', 'De arquivo'],
    ['Medieval facsimile', 'Fac-símile medieval'],
    ['Original dated', 'Original datada'],
    ['Original undated', 'Original não datada'],
    ['Unoriginal dated', 'Não original não datada'],
    ['Unoriginal undated', 'Não original datada'],
    ['Other', 'Outra'],
  ];

  foreach ($list_terms as $terms_arr) {
    $term = $taxonomy_storage->create([
      'vid' => 'type_of_bookbinding',
      'name' => $terms_arr[0],
      'langcode' => 'en',
    ]);

    $term->enforceIsNew();
    $term->save();
    $term->addTranslation('pt-pt', [
      'name' => $terms_arr[1],
    ])->save();
  }
}
