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
use Drupal\taxonomy\Entity\Term;

/**
 * Import taxonomies.
 */
function sb_core_deploy_108000(): void {
  /** @var \Drupal\Core\Extension\ModuleInstallerInterface $module_installer */
  $module_installer = \Drupal::service('module_installer');
  // Import new content.
  $module_installer->install(['sb_content']);
  $module_installer->uninstall(['sb_content']);
}

/**
 * Import archive taxonomies.
 */
function sb_core_deploy_108001(): void {
  $archives = [
    ["E-BUlh","Burgos","E-BUlh (Burgos) Monasterio de Las Huelgas"],
    ["E-SAu","Salamanca","E-SAu (Salamanca) Universidad, Archivo y Biblioteca"],
    ["E-Sco","Sevilla","E-Sco (Sevilla) Institución Colombina"],
    ["E-TUY","Tuy","E-TUY (Tuy) Archivo Capitular de la Catedral de Tuy"],
    ["E-V","Valladolid","E-V (Valladolid) Archivo Musical de la Catedral de Valladolid"],
    ["E-Vp","Valladolid","E-Vp (Valladolid) Parroquia de Santiago, Archivo"],
    ["E-ZAahp","Zamora","E-ZAahp Archivo Histórico Provincial de Zamora"],
    ["P-AN","Angra do Heroísmo","P-AN (Angra do Heroísmo) Biblioteca Pública e Arquivo Distrital"],
    ["P-AR","Arouca","P-AR (Arouca) Museu Regional de Arte Sacra do Mosteiro de Arouca"],
    ["P-ARRam","Arraiolos","P-ARRam (Arraiolos) Arquivo Histórico e Municipal"],
    ["P-ARRsc","Arraiolos","P-ARRsc (Arraiolos) Santa Casa da Misericórdia, Arquivo"],
    ["P-AV","Aveiro","P-AV (Aveiro) Museu de Aveiro, Mosteiro de Jesus"],
    ["P-AVad","Aveiro","P-AVad (Aveiro) Arquivo Distrital"],
    ["P-AVub","Aveiro","P-AVub (Aveiro) Serviços de Documentação, Biblioteca"],
    ["P-AZsc","Azurara","P-AZsc (Azurara) Santa Casa da Misericórdia, Arquivo"],
    ["P-BA","Barreiro","P-BA (Barreiro) Biblioteca Municipal"],
    ["P-BÇad","Bragança","P-BÇad (Bragança) Arquivo Distrital"],
    ["P-BJad","Beja","P-BJad (Beja) Arquivo Distrital"],
    ["P-BRad","Braga","P-BRad (Braga) Arquivo Distrital"],
    ["P-BRam","Braga","P-BRam (Braga) Arquivo Municipal"],
    ["P-BRic","Braga","P-BRic (Braga) Irmandade de Santa Cruz"],
    ["P-BRp","Braga","P-BRp (Braga) Biblioteca Pública"],
    ["P-BRs","Braga","P-BRs (Braga) Arquivo da Sé"],
    ["P-BRsc","Braga","P-BRsc (Braga) Seminário Conciliar"],
    ["P-BRu","Braga","P-BRu (Braga) Universidade do Minho, Biblioteca"],
    ["P-CA","Cascais","P-CA (Cascais) Museu-Biblioteca Condes de Castro Guimarães"],
    ["P-CAmm","Cascais","P-CAmm (Cascais) Museu da Música Regional Portuguesa"],
    ["P-CB","Castelo Branco","P-CB (Castelo Branco) Arquivo da Sé"],
    ["P-CBad","Castelo Branco","P-CBad (Castelo Branco) Arquivo Distrital"],
    ["P-Cm","Coimbra","P-Cm (Coimbra) Biblioteca Municipal"],
    ["P-Cmn","Coimbra","P-Cmn (Coimbra) Museu Nacional de Machado de Castro"],
    ["P-Cs","Coimbra","P-Cs (Coimbra) Arquivo da Sé Nova"],
    ["P-Csc","Coimbra","P-Csc (Coimbra) Santa Casa da Misericórdia, Arquivo"],
    ["P-Cua","Coimbra","P-Cua (Coimbra) Arquivo Distrital e da Universidade"],
    ["P-Cuc","Coimbra","P-Cuc (Coimbra) Universidade de Coimbra, Capela"],
    ["P-Cug","Coimbra","P-Cug (Coimbra) Biblioteca Geral da Universidade"],
    ["P-Cul","Coimbra","P-Cul (Coimbra) Faculdade de Letras da Universidade"],
    ["P-Em","Elvas","P-Em (Elvas) Biblioteca Municipal e Arquivo Municipal"],
    ["P-Es","Elvas","P-Es (Elvas) Arquivo da Sé"],
    ["P-ESam","Estremoz","P-ESam (Estremoz) Arquivo Municipal"],
    ["P-ESsc","Estremoz","P-ESsc (Estremoz) Santa Casa da Misericórdia, Arquivo"],
    ["P-EVad","Évora","P-EVad (Évora) Arquivo Distrital"],
    ["P-EVc","Évora","P-EVc (Évora) Arquivo da Sé"],
    ["P-EVm","Évora","P-EVm (Évora) Museu de Évora"],
    ["P-EVp","Évora","P-EVp (Évora) Biblioteca Pública"],
    ["P-EVpc","Évora","P-EVpc (Évora) Palácio Duques do Cadaval"],
    ["P-EVu","Évora","P-EVu (Évora) Universidade de Évora, Biblioteca Geral"],
    ["P-F","Figueira da Foz","P-F (Figueira da Foz) Biblioteca Pública Municipal Pedro Fernandes Tomas"],
    ["P-FAad","Faro","P-FAad (Faro) Arquivo Distrital"],
    ["P-FAs","Faro","P-FAs (Faro) Seminario Episcopal de S. José do Algarve"],
    ["P-G","Guimarães","P-G (Guimarães) Arquivo Municipal Alfredo Pimenta"],
    ["P-GDad","Guarda","P-GDad (Guarda) Arquivo Distrital"],
    ["P-Gm","Guimarães","P-Gm (Guimarães) Biblioteca Municipal Raul Brandão"],
    ["P-Gmas","Guimarães","P-Gmas (Guimarães) Museu de Alberto Sampaio"],
    ["P-Gms","Guimarães","P-Gms (Guimarães) Sociedade Martins Sarmento"],
    ["P-Gsc","Guimarães","P-Gsc (Guimarães) Santa Casa da Misericórdia, Arquivo"],
    ["P-LA","Lisboa","P-La (Lisboa) Biblioteca do Palacio Nacional da Ajuda"],
    ["P-LA","Lamego","P-LA (Lamego) Arquivo da Sé"],
    ["P-Laa","Lisboa","P-Laa (Lisboa) Academia dos Amadores de Música"],
    ["P-Lac","Lisboa","P-Lac (Lisboa) Academia das Ciências, Biblioteca"],
    ["P-Lahm","Lisboa","Arquivo Histórico Militar"],
    ["P-LAmad","Lamego","P-LAmad (Lamego) Museu e Arquivo Diocesanos"],
    ["P-LAml","Lamego","P-LAml (Lamego) Museu de Lamego "],
    ["P-Lant","Lisboa","P-Lant (Lisboa) Arquivo Nacional da Torre do Tombo"],
    ["P-LApe","Lamego","P-LApe (Lamego) Palácio Episcopal"],
    ["P-Lc","Lisboa","P-Lc (Lisboa) Escola de Música do Conservatório Nacional, Biblioteca"],
    ["P-Lcg","Lisboa","P-Lcg (Lisboa) Fundação Calouste Gulbenkian"],
    ["P-LE","Leiria","P-LE (Leiria) Arquivo Distrital"],
    ["P-LEm","Leiria","P-LEm (Leiria) Biblioteca Municipal Afonso Lopes Vieira"],
    ["P-Lf","Lisboa","P-Lf (Lisboa) Arquivo da Fabrica da Sé Patriarcal"],
    ["P-Lh","Lisboa","P-Lh (Lisboa) Hemeroteca"],
    ["P-Lif","Lisboa","P-Lif (Lisboa) Institut Franco-Portuguais"],
    ["P-Lim","Lisboa","P-Lim (Lisboa) Igreja das Mercês"],
    ["P-Lm","Lisboa","P-Lm (Lisboa) Biblioteca Municipal"],
    ["P-Lma","Lisboa","P-Lma (Lisboa) Museu de Arqueologia, Biblioteca"],
    ["P-Lmm","Lisboa","P-Lmm (Lisboa) Museu da Música"],
    ["P-Lmnaa","Lisboa","P-Lmnaa (Lisboa) Museu Nacional de Arte Antiga"],
    ["P-Ln","Lisboa","P-Ln (Lisboa) Biblioteca Nacional de Portugal"],
    ["P-Lo","Lisboa","P-Lo (Lisboa) Seminario dos Olivais, Biblioteca"],
    ["P-LOam","Loulé","Arquivo Municipal de Loulé"],
    ["P-Lr","Lisboa","P-Lr (Lisboa) Radiodifusao Portuguesa"],
    ["P-Ls","Lisboa","P-Ls (Lisboa) Sociedade Portuguesa de Autores"],
    ["P-Lscm","Lisboa","P-Lscm (Lisboa) Santa Casa da Misericórdia, Arquivo Histórico/Biblioteca"],
    ["P-Lt","Lisboa","P-Lt (Lisboa) Teatro Nacional de S. Carlos"],
    ["P-Lu","Lisboa","P-Lu (Lisboa) Universidade Nova, FCSH, Serviços de Informação e Documentação, Biblioteca Geral"],
    ["P-Luc","Lisboa","P-Luc (Lisboa) Universidade Nova, FCSH, Centro de Estudos de Sociologia e Estética Musical (CESEM)"],
    ["P-Lue","Lisboa","P-Lue (Lisboa) Universidade Nova, FCSH, Instituto de Etnomusicologia (INET), Biblioteca e Arquivo Sonoro"],
    ["P-Lum","Lisboa","P-Lum (Lisboa) Universidade Nova, FCSH, Biblioteca do Departamento de Ciências Musicais"],
    ["P-MNam","Montemor-o-Novo","P-MNam (Montemor-o-Novo) Arquivo Municipal"],
    ["P-MOam","Monção","P-MOam (Monção) Arquivo Municipal"],
    ["P-MONam","Torre de Moncorvo","P-MONam (Torre de Moncorvo) Arquivo Municipal"],
    ["P-Mp","Mafra","P-Mp (Mafra) Palacio Nacional de Mafra, Biblioteca"],
    ["P-MRahm","Moura","P-MRahm (Moura) Arquivo Histórico Municipal"],
    ["P-Op","Óbidos","P-Op (Óbidos) Igreja de S. Pedro"],
    ["P-Pa","Porto","P-Pa (Porto) Ateneu Comercial"],
    ["P-Pad","Porto","P-Pad (Porto) Arquivo Distrital"],
    ["P-Pc","Porto","P-Pc (Porto) Conservatório de Música"],
    ["P-PD","Ponta Delgada","P-PD (Ponta Delgada) Biblioteca Pública e Arquivo Distrital"],
    ["P-Peh","Porto","P-Peh (Porto) Museu de Etnografia e História"],
    ["P-Pf","Porto","P-Pf (Porto) Club dos Fenianos Portuenses"],
    ["P-PL","Ponte de Lima","P-PL (Ponte de Lima) Santa Casa da Misericórdia, Arquivo"],
    ["P-PLam","Ponte de Lima","P-PLam (Ponte de Lima) Arquivo Municipal"],
    ["P-PLmt","Ponte de Lima","P-PLmt (Ponte de Lima) Museu dos Terceiros"],
    ["P-Pm","Porto","P-Pm (Porto) Biblioteca Municipal"],
    ["P-PO","Portalegre","P-PO (Portalegre) Arquivo da Sé"],
    ["P-POad","Portalegre","P-POad (Portalegre) Arquivo Distrital"],
    ["P-POm","Portalegre","P-POm (Portalegre) Biblioteca Municipal"],
    ["P-Puc","Porto","P-Puc (Porto) Universidade Católica"],
    ["P-Pul","Porto","P-Pul (Porto) Faculdade de Letras"],
    ["P-PVam","Póvoa de Varzim","P-PVam (Póvoa de Varzim) Arquivo Municipal"],
    ["P-SAad","Santarém","P-SAad (Santarém) Arquivo Distrital"],
    ["P-SAscm","Santarém","P-SAscm (Santarém) Santa Casa da Misericórdia"],
    ["P-SEad","Setúbal","P-SEad (Setúbal) Arquivo Distrital"],
    ["P-Tcc","Tomar","P-Tcc (Tomar) Convento de Cristo"],
    ["P-Va","Viseu","P-Va (Viseu) Arquivo Distrital"],
    ["P-VAam","Valença","P-VAam (Valença) Arquivo Municipal"],
    ["P-Vbm","Viseu","P-Vbm (Viseu) Biblioteca Municipal"],
    ["P-VCad","Viana do Castelo","P-VCad (Viana do Castelo) Arquivo Distrital"],
    ["P-VCam","Viana do Castelo","P-VCam (Viana do Castelo) Arquivo Municipal"],
    ["P-VCDam","Vila do Conde","P-VCDam (Vila do Conde) Arquivo Municipal"],
    ["P-VCDsc","Vila do Conde","P-VCDsc (Vila do Conde) Santa Casa da Misericórdia, Arquivo"],
    ["P-VIsc","Vimieiro","P-VIsc (Vimieiro) Santa Casa da Misericórdia, Arquivo"],
    ["P-Vm","Viseu","P-Vm (Viseu) Museu Grão Vasco"],
    ["P-VNCam","Vila Nova de Cerveira","P-VNCam (Vila Nova de Cerveira) Arquivo Municipal"],
    ["P-VRad","Vila Real","P-VRad (Vila Real) Arquivo Distrital"],
    ["P-Vs","Viseu","P-Vs (Viseu) Arquivo da Sé"],
    ["P-VV","Vila Viçosa","P-VV (Vila Viçosa) Biblioteca do Palácio Real"],
    ["US-PHf","Philadelphia","US-PHf (Philadelphia) Free Library of Philadelphia, Music Department"]
  ];
  foreach ($archives as $archive) {
    $city = \Drupal::entityTypeManager()->getStorage("taxonomy_term")->loadByProperties(["name" => $archive[1], "vid" => 'city']);
    if (empty($city)) {
      $city = Term::create([
        'vid' => 'city',
        'name' => $archive[1]
        ]);
      $city->save();
    }
    else {
      $city_arr = array_values($city);
      $city = $city_arr[0];
    }

    $term = Term::create([
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
      "16023000", ""],
    [
      "Ad Aspersionem Aquae benedictae TP",
      "For the sprinkling of Holy Water in Eastertide",
      "16023080", ""],
    ["Ad lavandum altaria", "Chants for the washing of the altar", "16024000", ""],
    ["Ad Mandatum", "At the Mandatum (Foot-Washing)", "7065010", ""],
    ["Ad Processionem", "For Processions", "16009000", ""],
    ["Adalberti", "Adalbert of Prague, Bishop and Martyr", "14042310", "Apr.23"],
    ["Additamenta", "Added or Miscellaneous Items", "17001000", ""],
    ["Aegidii", "Aegidius (Giles), Abbot", "14090100", "Sep.1"],
    [
      "Aemigdii, Episc. et Martyr",
      "Emygdius (Emidius), Bishop Martyr",
      "14080530", "Aug.5"],
    ["Agapiti", "Agapitus, Martyr", "14081800", "Aug.18"],
    ["Agathae", "Agatha, Virgin Martyr", "14020500", "Feb.5"],
    ["Agnetis", "Agnes, Virgin Martyr", "14012100", "Jan.21"],
    ["Agnetis,8", "In week after Agnes", "14012108", ""],
    ["Alexandri et sociorum", "Alexander and Eventius, Martyrs", "14050310", "May.3"],
    ["Alexis", "Alexis, the Man of God", "14071700", "Jul.17"],
    [
      "Aloisii Gonzagae",
      "Aloysius (Luigi) Gonzaga, Confessor, Patron of youthful Catholic students.",
      "14062120", "Jun.21"],
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
      "14050510", "May.5"],
    [
      "Austremonii",
      "Austremonius (Stremoine), Bishop, Martyr, Apostle of Auvergne",
      "14110160", "Nov.1"],
    ["Barnabae", "Barnabas, Apostle", "14061100", "Jun.11"],
    ["Bartholomaei", "Bartholomew, Apostle", "14082400", "Aug.24"],
    [
      "Basilidis et sociorum",
      "Basilides and companions (Cyrinus, Nabor, and Nazarius), Martyrs",
      "14061200", "Jun.12"],
    ["Benedicti", "Benedict, Abbot", "14032100", "Mar.21"],
    ["Bernardi", "Bernard, Abbot and Doctor", "14082010", "Aug.20"],
    ["Bernardi,8", "In week after Bernard", "14082018", ""],
    [
      "Bernardini Senensis",
      "Bernardinus degl' Albizzeschi of Siena, Confessor",
      "14052010", "May.20"],
    ["Blasii", "Blaise, Bishop of Sebastea, Martyr", "14020300", "Feb.3"],
    ["BMV de Monte Carmelo", "Our Lady of Mount Carmel", "14071610", "Jul.16"],
    ["Briccii", "Brice, Bishop of Tours", "14111300", "Nov.13"],
    [
      "Brunonis Abbatis",
      "Bruno, Abbot, Founder of the Carthusian Order",
      "14100610", "Oct.6"],
    ["Caeciliae", "Cecilia (Cecily), Virgin Martyr", "14112200", "Nov.22"],
    ["Caesarii Arelatensis", "Caesarius, Archbishop of Arles", "14082700", "Aug.27"],
    ["Callisti", "Callistus (Calixtus) I, Pope", "14101400", "Oct.14"],
    [
      "Camilli de Lellis",
      "Camillus de Lellis, Confessor, Founder of the Canons Regular of a Good Death (Infirmis Ministrantium)",
      "14071820", "Jul.18"],
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
      "12019000", ""],
    [
      "Comm. Apostolorum sive Martyrum TP",
      "Common of Apostles or Martyrs, Eastertide",
      "12801100", ""],
    ["Comm. Apostolorum TP", "Common of Apostles, Eastertide", "12801000", ""],
    ["Comm. Apostolorum,8", "Common of Apostles, in week of", "12001008", ""],
    ["Comm. Conjungium", "Common of Holy Matrons", "12012000", ""],
    ["Comm. duorum Apostolorum", "Common of two Apostles", "12001200", ""],
    ["Comm. Evangelistarum", "Common of Evangelists", "12011000", ""],
    [
      "Comm. Evangelistarum TP",
      "Common of Evangelists, Eastertide",
      "12811000", ""],
    ["Comm. plurimorum Apostolorum in vigilia", "Eve of Apostles", "12001010", ""],
    [
      "Comm. plurimorum Confessorum",
      "Common of several Confessors",
      "12005000", ""],
    [
      "Comm. plurimorum Confessorum non Pontificum",
      "Common of several Confessors (not Popes)",
      "12005200", ""],
    [
      "Comm. plurimorum Confessorum Pontificum",
      "Common of several Confessors (Popes)",
      "12005100", ""],
    ["Comm. plurimorum Martyrum", "Common of several Martyrs", "12003000", ""],
    [
      "Comm. plurimorum Martyrum TP",
      "Common of several Martyrs, Eastertide",
      "12803000", ""],
    ["Comm. plurimorum Virginum", "Common of several Virgins", "12006000", ""],
    ["Comm. unius Abbatis", "Common of one Abbot", "12010000", ""],
    ["Comm. unius Apostoli", "Common of one Apostle", "12001100", ""],
    ["Comm. unius Apostoli in vigilia", "Eve of one Apostle", "12001110", ""],
    ["Comm. unius Confessoris", "Common of one Confessor", "12004000", ""],
    [
      "Comm. unius Confessoris Abbatis",
      "Common of one Confessor (Abbot)",
      "12004200", ""],
    [
      "Comm. unius Confessoris et Doctoris",
      "Common of one Confessor (Doctor)",
      "12004500", ""],
    [
      "Comm. unius Confessoris et Episcopi",
      "Common of one Confessor (Bishop)",
      "12004300", ""],
    [
      "Comm. unius Confessoris non Episcopus",
      "Common of one Confessor (not Bishop)",
      "12004400", ""],
    [
      "Comm. unius Confessoris non Pontificis",
      "Common of one Confessor (not Pope)",
      "12004700", ""],
    [
      "Comm. unius Confessoris non Pontificis TP",
      "Common of one Confessor (not Pope), Eastertide",
      "12804700", ""],
    [
      "Comm. unius Confessoris Pontificis",
      "Common of one Confessor (Pope)",
      "12004100", ""],
    [
      "Comm. unius Confessoris Pontificis TP",
      "Common of one Confessor (Pope), Eastertide",
      "12804100", ""],
    [
      "Comm. unius Confessoris TP",
      "Common of one Confessor, Eastertide",
      "12804000", ""],
    [
      "Comm. unius electae",
      "Common of those chosen (not Virgins, not Martyrs)",
      "12022000", ""],
    ["Comm. unius Martyris", "Common of one Martyr", "12002000", ""],
    [
      "Comm. unius Martyris non Pontificis",
      "Common of one Martyr (not Pope)",
      "12002200", ""],
    [
      "Comm. unius Martyris non Virginis",
      "Common of one Martyr (not Virgin)",
      "12002300", ""],
    [
      "Comm. unius Martyris Pontificis",
      "Common of one Martyr (Pope)",
      "12002100", ""],
    ["Comm. unius Martyris TP", "Common of one Martyr, Eastertide", "12802000", ""],
    ["Comm. unius Virginis", "Common of one Virgin", "12007100", ""],
    [
      "Comm. unius Virginis Martyris",
      "Common of one Virgin Martyr",
      "12007000", ""],
    [
      "Comm. unius Virginis non Martyris",
      "Common of one Virgin (not Martyr)",
      "12007200", ""],
    ["Comm. unius Virginis TP", "Common of one Virgin, Eastertide", "12807100", ""],
    ["Conceptio Mariae", "Immaculate Conception of Mary", "14120800", "Dec.8"],
    ["Conversio Pauli", "Conversion of Paul", "14012500", "Jan.25"],
    ["Cornelii, Cypriani", "Cornelius and Cyprian, Martyrs", "14091600", "Sep.16"],
    [
      "Corporis Christi",
      "Corpus Christi (also \"Blessed Sacrament\")",
      "9015000", ""],
    ["Corporis Christi,8", "In week after Corpus Christi", "9015008", ""],
    ["Cosmae, Damiani", "Cosmas and Damian, Martyrs", "14092700", "Sep.27"],
    ["Cyriaci et sociorum", "Cyriacus and companions, Martyrs", "14080800", "Aug.8"],
    ["Cyrici", "Cyricus and Julitta, Martyrs", "14061600", "Jun.16"],
    [
      "De Angelis",
      "Memorial chants for Angels, including e.g. Missa Votiva de Angelis. Feria III.",
      "12013000", ""],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV Adv.", "Votive Mass/Office for Mary, Advent", "15008010", ""],
    ["De BMV Nat.", "Votive Mass/Office for Mary, Christmas", "15008030", ""],
    [
      "De BMV post Epiph.",
      "Votive Mass/Office for Mary, after Epiphany",
      "15008050", ""],
    ["De BMV TP", "Votive Mass/Office for Mary, Eastertide", "15008080", ""],
    ["De caritate", "Chants for charity", "16025000", ""],
    ["De Corona Spinea", "Commemoration of the Crown of Thorns", "14081120", "Aug.11"],
    ["De festis duplicibus", "Chants for feasts of duplex rank", "16016000", ""],
    [
      "De festis duplicibus minoribus",
      "Chants for feasts of duplex minor rank",
      "16016001", ""],
    [
      "De festis semiduplicibus",
      "Chants for feasts of semiduplex rank",
      "16015000", ""],
    ["De festis simplicibus", "Chants for feasts of simple rank", "16039000", ""],
    ["De Job", "Summer Histories, from Job", "10300000", ""],
    ["De Machabaeis", "Summer Histories, from Maccabees", "10800000", ""],
    ["De Prophetis", "Summer Histories, from the Prophets", "10900000", ""],
    ["De Regum", "Summer Histories, from Kings", "10100000", ""],
    [
      "De Sancta Cruce",
      "Votive Mass/Office for the Holy Cross, including e.g. Missa Votiva de Sancta Cruce. Fer. VI.",
      "15011000", ""],
    ["De Sanctis TP", "Common of Saints, Eastertide", "12815000", ""],
    ["De Sapientia", "Summer Histories, from Wisdom", "10200000", ""],
    [
      "De Spiritu Sancto",
      "Votive Mass/Office for the Holy Spirit, including, e.g. Missa Votiva de Spiritu Sancto. Feria V.",
      "15002000", ""],
    ["De Tobia", "Summer Histories, from Tobias", "10400000", ""],
    ["De Trinitate", "Trinity Sunday", "9011000", ""],
    [
      "De victoriae christianorum apud Salado",
      "Commemoration of the victory of the Christians at the Battle of Rio Salado (also known as the Battle of Tarifa), 30 October 1340",
      "14103010", "Oct.30"],
    [
      "Decem Millium Martyrum",
      "Ten Thousand Martyrs (crucified on Mount Ararat)",
      "14062240", "Jun.22"],
    ["Decoll. Jo. Bapt.", "Beheading of John the Baptist", "14082900", "Aug.29"],
    [
      "Die 2 p. Epiphaniam",
      "1st day after Epiphany (2nd day \"of\" Epiphany)",
      "5010700", "Jan.7"],
    ["Die 5 a. Nat. Domini", "The fifth day before Christmas", "2122100", "Dec.21"],
    ["Dionysii", "Denis (Dionysius), Bishop of Paris", "14100900", "Oct.9"],
    ["Dom. 1 Adventus", "1st Sunday of Advent", "1011000", ""],
    [
      "Dom. 1 p. Epiphaniam",
      "1st Sunday after Epiphany (Sunday within the octave of Epiphany, 'Dom. Infra Oct. Epiph.')",
      "5011010", ""],
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
      "9021000", ""],
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
      "14062901", ""],
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
      "14080430", "Aug.4"],
    ["Donati", "Donatus, Bishop of Arezzo", "14080700", "Aug.7"],
    [
      "Elisabeth Reginae Portugalliae",
      "Elisabeth (Isabel), Widow, Queen of Portugal",
      "14070830", "Jul.4"],
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
      "14091400", "Sep.14"],
    [
      "Exspectationis BMV",
      "The Expectation of Mary (The Expectation of the Birth of Jesus)",
      "14121810", "Dec.18"],
    ["Fabiani, Sebastiani", "Pope Fabian and Sebastian, Martyrs", "14012000", "Jan.20"],
    [
      "Felicis",
      "Felix, Bishop and Martyr (falsely called Pope Felix II)",
      "14072900", "Jul.29"],
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
      "4000000", ""],
    ["Flori", "Florus, Bishop of Lodève", "14110400", "Nov.4"],
    ["Franchae", "Franca Visalta, Virgin and Abbess, at Piacenza", "14042700", "Apr.4"],
    ["Francisci", "Francis of Assisi", "14100400", "Oct.4"],
    [
      "Francisci Xaverii",
      "Francis Xavier, Confessor, Apostle of India and Japan (canonized in 1622)",
      "14120300", "Dec.3"],
    [
      "Fructuosi Archiepiscopi Bracharensis",
      "Fructuosus, Archbishop of Braga",
      "14041610", "Apr.16"],
    ["Gabrielis, Archang.", "Gabriel the Archangel", "14031800", "Mar.18"],
    ["Genesii", "Genesius, Martyr", "14082530", "Aug.25"],
    ["Georgii", "George, Martyr", "14042300", "Apr.23"],
    [
      "Geraldi Archiepiscopi Bracharensis",
      "Gerald (Girald), Archbishop of Braga",
      "14120510", "Dec.5"],
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
      "14021200", "Feb.12"],
    ["Ignatii", "Ignatius, Bishop of Antioch, Martyr", "14020110", "Feb.1"],
    ["Ildefonsi", "Ildephonsus, Archbishop of Toledo", "14012301", ""],
    [
      "In dedicatione Basilicae BMV de Martyribus Ulyssipponensis",
      "Dedication of the Basilica of Our Lady of the Martyrs, Lisbon",
      "14051320", "May.13"],
    ["In Dedicatione Ecclesiae", "Dedication of a Church", "12008000", ""],
    ["In festo sollemni", "", "xxx", ""],
    ["In Letaniis", "General, Rogation Days", "8068010", ""],
    ["In tempore Adventus", "General, in Advent", "1000000", ""],
    [
      "In tempore belli contra Sarracenos",
      "Chants in time of war against the Saracens",
      "16021000", ""],
    ["In tempore Epiphaniae", "General, after Epiphany", "5000000", ""],
    ["In tempore Nat.", "General, in Christmastide", "3000000", ""],
    [
      "In tempore oritur inter Christianos",
      "Chants in time of an uprising among Christians",
      "16022000", ""],
    ["In tempore Paschae", "General, Eastertide", "8000000", ""],
    ["In tempore pestilentiae", "Chants in time of the plague", "16018000", ""],
    ["In tempore Quad.", "General, in Lent", "7000000", ""],
    ["In Triduum", "General, during the Triduum", "7069000", ""],
    ["Inventio Crucis", "Finding of the Cross", "14050300", "May.3"],
    [
      "Inventio Stephani",
      "Finding of Stephen's relics (First Martyr)",
      "14080300", "Aug.3"],
    ["Invitatoria", "Invitatory antiphons or psalms", "16004000", ""],
    ["Irenes", "Irene of Santarém (Portugal)", "14102010", "Oct.20"],
    ["Isidori Episcopi Confessoris et Ecclesiae Doctoris", "", "", "Apr.4"],
    [
      "Ivonis de Kermartin",
      "Ivo of Kermartin (Yves Hélory, Yvo, Ives)",
      "14051930", "May.19"],
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
      "14080630", "Aug.6"],
    ["Laurentii", "Laurence, Martyr", "14081000", "Aug.10"],
    ["Laurentii,8", "In week after Laurence", "14081008", ""],
    [
      "Lauteni",
      "Lautein (Lothenus, Lautenus), Abbot, founder of Silèze and Maximiac abbeys in the Jura mountains",
      "14110200", "Nov.2"],
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
      "12802300", ""],
    ["Marthae", "Martha, Virgin", "14072930", "Jul.29"],
    ["Martini", "Martin, Bishop of Tours", "14111100", "Nov.11"],
    [
      "Martini Archiepiscopi Bracharensis",
      "Martin, Bishop of Braga",
      "14032020", "Mar. 20"],
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
      "14070600", "Jul.6"],
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
      "8021000", ""],
    ["Octava Paschae,8", "In 2nd week after Easter", "8021008", ""],
    ["Omnium Sanctorum", "All Saints' Day", "14110100", "Nov.1"],
    ["Omnium Sanctorum,8", "In week after All Saints' Day", "14110108", ""],
    [
      "Pancratii et sociorum",
      "Pancratius (Pancras) and companions, Martyrs",
      "14051210", "May.12"],
    ["Paulae", "Paula, Widow", "14012610", "Jan.26"],
    ["Pauli", "Paul, Apostle", "14063000", "Jun.30"],
    ["Pauli Heremitae", "Paul the Hermit", "14011010", "Jan.10"],
    ["Pauli,8", "In week after Paul (Apostle)", "14063008", ""],
    ["Petri", "Peter, Apostle", "14062910", "Jun.29"],
    ["Petri Alexandrini Ep. Mart.", "", "", "Nov.26"],
    [
      "Petri de Rates",
      "Peter de Rates, reputed first Bishop of Braga",
      "4042610", "Apr.26"],
    ["Petri Gundisalvi", "Peter González, Dominican friar", "14041410", "Apr.14"],
    ["Petri Regalati", "Peter Regalado, Franciscan friar", "14051330", "May.13"],
    [
      "Petri, Mart.",
      "Peter the Martyr, Dominican Friar and Priest",
      "14042900", "Apr.29"],
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
      "16019000", ""],
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
      "14051910", "May.19"],
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
      "14101930", "Oct.19"],
    [
      "Sacratissimi Cordis Jesu",
      "Most Sacred Heart of Jesus, on Friday after the second Sunday after Pentecost",
      "9026010", ""],
    ["Samsonis", "Samson, Bishop of Dol", "14072810", "Jul.28"],
    [
      "Sanctorum Quinque Martyrum Ordinis Minorum",
      "Five Martyrs of Morocco",
      "14011630", "Jan.16"],
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
      "14041710", "Apr.17"],
    ["Stephani, Pont.", "Stephen I, Pope", "14080200", "Aug.2"],
    ["Stephani,8", "In week after Stephen", "2122608", ""],
    ["Suff. Crucis", "Memorial chants for the Holy Cross", "15050300", ""],
    [
      "Suff. Crucis TP",
      "Memorial chants for the Holy Cross, Eastertide",
      "15050380", ""],
    ["Suff. Mariae TP", "Memorial chants for Mary, Eastertide", "15081580", ""],
    [
      "Suff. Om. Sanct. TP",
      "Memorial chants for All Saints, Eastertide",
      "15110180", ""],
    ["Suff. pro Pace", "Memorial chants for peace", "15001000", ""],
    ["Suff. pro Penitent.", "Memorial chants for penitence", "15003000", ""],
    ["Symphoriani", "Symphorian (and Timothy), Martyrs", "14082200", "Aug.22"],
    ["Syri", "Syrus", "14120900", "Dec.9"],
    ["Taurini", "Taurinus, Bishop of Evreux", "14081130", "Aug.11"],
    [
      "Teresiae Avilensis",
      "Teresa (Teresia), Virgin, reformer of the Carmelite Order and ascetical write (canonized in 1622)",
      "14101500", "Oct.15"],
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
      "14041400", "Apr.14"],
    ["Timothei, Apollinaris", "Timothy and Apollinaris, Martyrs", "14082300", "Aug.23"],
    ["Transfiguratio Dom.", "Transfiguration of Jesus", "14080600", "Aug.6"],
    ["Transl. Benedicti", "Moving of Benedict's relics", "14071100", "Jul.11"],
    ["Transl. Jacobi", "Translation of James the Greater", "14123020", "Dec.30"],
    ["Transl. Martini", "Moving of Martin's relics", "14070400", "Jul.4"],
    ["Tres Mariae", "Mary, Mary Cleophae, Mary Salome", "14052520", "May.25"],
    [
      "Triumphi Sanctae Crucis apud Navas Tolosae",
      "Triumph of the Holy Cross in Las Navas de Tolosa (Battle of Las Navas de Tolosa or Battle of Al-Uqab)",
      "14071730", "Jul.17"],
    ["Urbani", "Urban I, Pope and Martyr", "14052510", "May.25"],
    ["Valentini", "Valentine, Martyr", "14021400", "Feb.14"],
    [
      "Valentini, Hylarii",
      "Valentine (priest) and Hilary (deacon), at Viterbo",
      "14110360", "Nov.3"],
    ["Victoris Bracharensis", "Victor, Martyr at Braga", "14041210", "Apr.12"],
    [
      "Victoris et sociorum",
      "Victor of Marseilles and companions, Martyrs",
      "14072110", "Jul.21"],
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
      "14012230", "Jan.22"],
    [
      "Vincentii, transl.",
      "Moving of Vincent of Saragossa's relics to Lisbon",
      "14091560", "Sep.15"],
    [
      "Vincentii, transl. in Brachara",
      "Moving of Vincent of Saragossa's relics to Braga",
      "14050450", "May.4"],
    ["Vincula Petri", "Peter in Chains", "14080100", "Aug.1"],
    ["Visitatio Mariae", "Visitation of Mary", "14070200", "Jul.2"],
    ["Vitalis, Valeriae", "Vitalis and Valeria, Martyrs", "14042800", "Apr.28"],
    ["XI milium Virginum", "11,000 Virgin Martyrs of Cologne", "14102100", "Oct.21"]
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
 * Update book type taxonomies.
 */
function sb_core_deploy_108003(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'book_type']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['-indeterminado-', '-indeterminado-'],
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
    ['Livro de Horas', 'Book of Hours'],
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
    ['Obras de teologia e/ou espiritualidade', 'Books on theology and Christian spirituality'],
    ['Responsorial', 'Responsorial'],
    ['Ritual', 'Ritual'],
    ['Sacramentário', 'Sacramentary'],
    ['Saltério', 'Psalter'],
    ['Tonário', 'Tonary'],
    ['Tropário', 'Troper']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update subcategory book taxonomies.
 */
function sb_core_deploy_108004(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'book_subcategory']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
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
    ['Saltério ferial', 'Ferial psalter']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update music taxonomies.
 */
function sb_core_deploy_108005(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'music']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['-n/a', '-n/a'],
    ['Monodia', 'Monophony'],
    ['Polifonia', 'Polyphony']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update completeness taxonomies.
 */
function sb_core_deploy_108006(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'completeness']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['Completo ou quase completo', 'Complete or nearly complete'],
    ['Fragmento', 'Fragment']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update cursus taxonomies.
 */
function sb_core_deploy_108007(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'cursus']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['Monástico', 'Monastic'],
    ['Secular', 'Secular'],
    ['desconhecido', 'unknown']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update locations taxonomies.
 */
function sb_core_deploy_108008(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'locations']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
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
    ['Paris (F)', 'Paris (F)']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update tradition taxonomies.
 */
function sb_core_deploy_108009(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'tradition']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
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
    ['Silves? (uso)', 'Silves? (use)']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update writing_type taxonomies.
 */
function sb_core_deploy_108010(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'writing_type']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
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
    ['Visigótica tardia', 'Transitional (Late Visigothic)']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update font_type taxonomies.
 */
function sb_core_deploy_108011(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'font_type']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['Manuscrito', 'Manuscript'],
    ['Impresso', 'Print'],
    ['Manuscrito estampilhado', 'Stencilled manuscript']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update music_notation taxonomies.
 */
function sb_core_deploy_108012(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'music_notation']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['Aquitana','Aquitanian'],
    ['Aquitana (variedade portuguesa)','Aquitanian (Portuguese variety)'],
    ['Hufnagelschrift','Hufnagelschrift'],
    ['Mensural (negra)','Mensural (black)'],
    ['Mensural (branca)','Mensural (white)'],
    ['Moderna','Modern'],
    ['Notação de cantochão moderna branca','Modern chant notation (white)'],
    ['Notação de cantochão moderna negra','Modern chant notation (black)'],
    ['Hispânica antiga','Old Hispanic'],
    ['outra','other'],
    ['Semimensural','Semi-mensural'],
    ['Quadrada','Square'],
    ['Tablatura','Tablature']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update text_language taxonomies.
 */
function sb_core_deploy_108013(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'text_language']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['Alemão','German'],
    ['Catalão','Catalan'],
    ['Castelhano','Castilian'],
    ['Francês','French'],
    ['Grego','Greek'],
    ['Hebraico','Hebrew'],
    ['Neerlandês','Dutch'],
    ['Húngaro','Hungarian'],
    ['Inglês','English'],
    ['Italiano','Italian'],
    ['Latim','Latin'],
    ['Português','Portuguese'],
    ['outra','other']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update document_validation taxonomies.
 */
function sb_core_deploy_108014(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'document_validation']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['Assinaturas','Signatures'],
    ['Cartas partidas por abc','Chirograph'],
    ['Selo de chapa','Dry seal'],
    ['Selo pendente em cera','Wax pendant seal'],
    ['Selo pendente em chumbo','Lead pendant seal'],
    ['Sinal de tabelião','Notary\'s mark'],
    ['Sinal rodado','Sinal rodado'],
    ['Outras','Others']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
 * Update document_type taxonomies.
 */
function sb_core_deploy_108015(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'document_type']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
  $types = [
    ['Carta régia','Royal charter'],
    ['Doação','Donation'],
    ['Testamento','Will'],
    ['Carta de partilhas','Partition deed'],
    ['Tomada de posse/entrega','Possession letter'],
    ['Compra e venda','Sale deed'],
    ['Contrato enfitêutico','Lease deed'],
    ['Préstamo ','Préstamo '],
    ['Escambo','Exchange deed'],
    ['Composição amigável/avença','Composition deed'],
    ['Sentença','Judgement'],
    ['Procuração','Power of attorney'],
    ['Apresentação de um clérigo','Clerk appointment'],
    ['Confirmação da apresentação de um clérigo','Clerk appointment confirmation'],
    ['Pacto','Agreement deed'],
    ['Carta de quitação','Quittance deed'],
    ['Atestação notarial/traslado em pública forma','Exemplification'],
    ['Traditio','Traditio'],
    ['Carta de fundação','Foundation deed'],
    ['Carta de couto','Carta de couto'],
    ['Carta de arras/dote','Dowry deed'],
    ['Carta de alforria','Manumission letter'],
    ['Carta de agnição','Carta de agnição'],
    ['Bula','Bull'],
    ['Visitação','Visitation'],
    ['Inquirição','Inquisitio'],
    ['Libelo','Libel '],
    ['Renúncia','Renunciation need'],
    ['Tombo','Tombo'],
    ['Outra','Other']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
      "16023000", ""],
    [
      "Ad Aspersionem Aquae benedictae TP",
      "For the sprinkling of Holy Water in Eastertide",
      "16023080", ""],
    ["Ad lavandum altaria", "Chants for the washing of the altar", "16024000", ""],
    ["Ad Mandatum", "At the Mandatum (Foot-Washing)", "7065010", ""],
    ["Ad Processionem", "For Processions", "16009000", ""],
    ["Adalberti", "Adalbert of Prague, Bishop and Martyr", "14042310", "Apr.23"],
    ["Additamenta", "Added or Miscellaneous Items", "17001000", ""],
    ["Aegidii", "Aegidius (Giles), Abbot", "14090100", "Sep.1"],
    [
      "Aemigdii, Episc. et Martyr",
      "Emygdius (Emidius), Bishop Martyr",
      "14080530", "Aug.5"],
    ["Agapiti", "Agapitus, Martyr", "14081800", "Aug.18"],
    ["Agathae", "Agatha, Virgin Martyr", "14020500", "Feb.5"],
    ["Agnetis", "Agnes, Virgin Martyr", "14012100", "Jan.21"],
    ["Agnetis,8", "In week after Agnes", "14012108", ""],
    ["Alexandri et sociorum", "Alexander and Eventius, Martyrs", "14050310", "May.3"],
    ["Alexis", "Alexis, the Man of God", "14071700", "Jul.17"],
    [
      "Aloisii Gonzagae",
      "Aloysius (Luigi) Gonzaga, Confessor, Patron of youthful Catholic students.",
      "14062120", "Jun.21"],
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
      "14050510", "May.5"],
    [
      "Austremonii",
      "Austremonius (Stremoine), Bishop, Martyr, Apostle of Auvergne",
      "14110160", "Nov.1"],
    ["Barnabae", "Barnabas, Apostle", "14061100", "Jun.11"],
    ["Bartholomaei", "Bartholomew, Apostle", "14082400", "Aug.24"],
    [
      "Basilidis et sociorum",
      "Basilides and companions (Cyrinus, Nabor, and Nazarius), Martyrs",
      "14061200", "Jun.12"],
    ["Benedicti", "Benedict, Abbot", "14032100", "Mar.21"],
    ["Bernardi", "Bernard, Abbot and Doctor", "14082010", "Aug.20"],
    ["Bernardi,8", "In week after Bernard", "14082018", ""],
    [
      "Bernardini Senensis",
      "Bernardinus degl' Albizzeschi of Siena, Confessor",
      "14052010", "May.20"],
    ["Blasii", "Blaise, Bishop of Sebastea, Martyr", "14020300", "Feb.3"],
    ["BMV de Monte Carmelo", "Our Lady of Mount Carmel", "14071610", "Jul.16"],
    ["Briccii", "Brice, Bishop of Tours", "14111300", "Nov.13"],
    [
      "Brunonis Abbatis",
      "Bruno, Abbot, Founder of the Carthusian Order",
      "14100610", "Oct.6"],
    ["Caeciliae", "Cecilia (Cecily), Virgin Martyr", "14112200", "Nov.22"],
    ["Caesarii Arelatensis", "Caesarius, Archbishop of Arles", "14082700", "Aug.27"],
    ["Callisti", "Callistus (Calixtus) I, Pope", "14101400", "Oct.14"],
    [
      "Camilli de Lellis",
      "Camillus de Lellis, Confessor, Founder of the Canons Regular of a Good Death (Infirmis Ministrantium)",
      "14071820", "Jul.18"],
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
      "12019000", ""],
    [
      "Comm. Apostolorum sive Martyrum TP",
      "Common of Apostles or Martyrs, Eastertide",
      "12801100", ""],
    ["Comm. Apostolorum TP", "Common of Apostles, Eastertide", "12801000", ""],
    ["Comm. Apostolorum,8", "Common of Apostles, in week of", "12001008", ""],
    ["Comm. Conjungium", "Common of Holy Matrons", "12012000", ""],
    ["Comm. duorum Apostolorum", "Common of two Apostles", "12001200", ""],
    ["Comm. Evangelistarum", "Common of Evangelists", "12011000", ""],
    [
      "Comm. Evangelistarum TP",
      "Common of Evangelists, Eastertide",
      "12811000", ""],
    ["Comm. plurimorum Apostolorum in vigilia", "Eve of Apostles", "12001010", ""],
    [
      "Comm. plurimorum Confessorum",
      "Common of several Confessors",
      "12005000", ""],
    [
      "Comm. plurimorum Confessorum non Pontificum",
      "Common of several Confessors (not Popes)",
      "12005200", ""],
    [
      "Comm. plurimorum Confessorum Pontificum",
      "Common of several Confessors (Popes)",
      "12005100", ""],
    ["Comm. plurimorum Martyrum", "Common of several Martyrs", "12003000", ""],
    [
      "Comm. plurimorum Martyrum TP",
      "Common of several Martyrs, Eastertide",
      "12803000", ""],
    ["Comm. plurimorum Virginum", "Common of several Virgins", "12006000", ""],
    ["Comm. unius Abbatis", "Common of one Abbot", "12010000", ""],
    ["Comm. unius Apostoli", "Common of one Apostle", "12001100", ""],
    ["Comm. unius Apostoli in vigilia", "Eve of one Apostle", "12001110", ""],
    ["Comm. unius Confessoris", "Common of one Confessor", "12004000", ""],
    [
      "Comm. unius Confessoris Abbatis",
      "Common of one Confessor (Abbot)",
      "12004200", ""],
    [
      "Comm. unius Confessoris et Doctoris",
      "Common of one Confessor (Doctor)",
      "12004500", ""],
    [
      "Comm. unius Confessoris et Episcopi",
      "Common of one Confessor (Bishop)",
      "12004300", ""],
    [
      "Comm. unius Confessoris non Episcopus",
      "Common of one Confessor (not Bishop)",
      "12004400", ""],
    [
      "Comm. unius Confessoris non Pontificis",
      "Common of one Confessor (not Pope)",
      "12004700", ""],
    [
      "Comm. unius Confessoris non Pontificis TP",
      "Common of one Confessor (not Pope), Eastertide",
      "12804700", ""],
    [
      "Comm. unius Confessoris Pontificis",
      "Common of one Confessor (Pope)",
      "12004100", ""],
    [
      "Comm. unius Confessoris Pontificis TP",
      "Common of one Confessor (Pope), Eastertide",
      "12804100", ""],
    [
      "Comm. unius Confessoris TP",
      "Common of one Confessor, Eastertide",
      "12804000", ""],
    [
      "Comm. unius electae",
      "Common of those chosen (not Virgins, not Martyrs)",
      "12022000", ""],
    ["Comm. unius Martyris", "Common of one Martyr", "12002000", ""],
    [
      "Comm. unius Martyris non Pontificis",
      "Common of one Martyr (not Pope)",
      "12002200", ""],
    [
      "Comm. unius Martyris non Virginis",
      "Common of one Martyr (not Virgin)",
      "12002300", ""],
    [
      "Comm. unius Martyris Pontificis",
      "Common of one Martyr (Pope)",
      "12002100", ""],
    ["Comm. unius Martyris TP", "Common of one Martyr, Eastertide", "12802000", ""],
    ["Comm. unius Virginis", "Common of one Virgin", "12007100", ""],
    [
      "Comm. unius Virginis Martyris",
      "Common of one Virgin Martyr",
      "12007000", ""],
    [
      "Comm. unius Virginis non Martyris",
      "Common of one Virgin (not Martyr)",
      "12007200", ""],
    ["Comm. unius Virginis TP", "Common of one Virgin, Eastertide", "12807100", ""],
    ["Conceptio Mariae", "Immaculate Conception of Mary", "14120800", "Dec.8"],
    ["Conversio Pauli", "Conversion of Paul", "14012500", "Jan.25"],
    ["Cornelii, Cypriani", "Cornelius and Cyprian, Martyrs", "14091600", "Sep.16"],
    [
      "Corporis Christi",
      "Corpus Christi (also \"Blessed Sacrament\")",
      "9015000", ""],
    ["Corporis Christi,8", "In week after Corpus Christi", "9015008", ""],
    ["Cosmae, Damiani", "Cosmas and Damian, Martyrs", "14092700", "Sep.27"],
    ["Cyriaci et sociorum", "Cyriacus and companions, Martyrs", "14080800", "Aug.8"],
    ["Cyrici", "Cyricus and Julitta, Martyrs", "14061600", "Jun.16"],
    [
      "De Angelis",
      "Memorial chants for Angels, including e.g. Missa Votiva de Angelis. Feria III.",
      "12013000", ""],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV", "Votive Mass/Office for Mary", "15008000", ""],
    ["De BMV Adv.", "Votive Mass/Office for Mary, Advent", "15008010", ""],
    ["De BMV Nat.", "Votive Mass/Office for Mary, Christmas", "15008030", ""],
    [
      "De BMV post Epiph.",
      "Votive Mass/Office for Mary, after Epiphany",
      "15008050", ""],
    ["De BMV TP", "Votive Mass/Office for Mary, Eastertide", "15008080", ""],
    ["De caritate", "Chants for charity", "16025000", ""],
    ["De Corona Spinea", "Commemoration of the Crown of Thorns", "14081120", "Aug.11"],
    ["De festis duplicibus", "Chants for feasts of duplex rank", "16016000", ""],
    [
      "De festis duplicibus minoribus",
      "Chants for feasts of duplex minor rank",
      "16016001", ""],
    [
      "De festis semiduplicibus",
      "Chants for feasts of semiduplex rank",
      "16015000", ""],
    ["De festis simplicibus", "Chants for feasts of simple rank", "16039000", ""],
    ["De Job", "Summer Histories, from Job", "10300000", ""],
    ["De Machabaeis", "Summer Histories, from Maccabees", "10800000", ""],
    ["De Prophetis", "Summer Histories, from the Prophets", "10900000", ""],
    ["De Regum", "Summer Histories, from Kings", "10100000", ""],
    [
      "De Sancta Cruce",
      "Votive Mass/Office for the Holy Cross, including e.g. Missa Votiva de Sancta Cruce. Fer. VI.",
      "15011000", ""],
    ["De Sanctis TP", "Common of Saints, Eastertide", "12815000", ""],
    ["De Sapientia", "Summer Histories, from Wisdom", "10200000", ""],
    [
      "De Spiritu Sancto",
      "Votive Mass/Office for the Holy Spirit, including, e.g. Missa Votiva de Spiritu Sancto. Feria V.",
      "15002000", ""],
    ["De Tobia", "Summer Histories, from Tobias", "10400000", ""],
    ["De Trinitate", "Trinity Sunday", "9011000", ""],
    [
      "De victoriae christianorum apud Salado",
      "Commemoration of the victory of the Christians at the Battle of Rio Salado (also known as the Battle of Tarifa), 30 October 1340",
      "14103010", "Oct.30"],
    [
      "Decem Millium Martyrum",
      "Ten Thousand Martyrs (crucified on Mount Ararat)",
      "14062240", "Jun.22"],
    ["Decoll. Jo. Bapt.", "Beheading of John the Baptist", "14082900", "Aug.29"],
    [
      "Die 2 p. Epiphaniam",
      "1st day after Epiphany (2nd day \"of\" Epiphany)",
      "5010700", "Jan.7"],
    ["Die 5 a. Nat. Domini", "The fifth day before Christmas", "2122100", "Dec.21"],
    ["Dionysii", "Denis (Dionysius), Bishop of Paris", "14100900", "Oct.9"],
    ["Dom. 1 Adventus", "1st Sunday of Advent", "1011000", ""],
    [
      "Dom. 1 p. Epiphaniam",
      "1st Sunday after Epiphany (Sunday within the octave of Epiphany, 'Dom. Infra Oct. Epiph.')",
      "5011010", ""],
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
      "9021000", ""],
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
      "14062901", ""],
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
      "14080430", "Aug.4"],
    ["Donati", "Donatus, Bishop of Arezzo", "14080700", "Aug.7"],
    [
      "Elisabeth Reginae Portugalliae",
      "Elisabeth (Isabel), Widow, Queen of Portugal",
      "14070830", "Jul.4"],
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
      "14091400", "Sep.14"],
    [
      "Exspectationis BMV",
      "The Expectation of Mary (The Expectation of the Birth of Jesus)",
      "14121810", "Dec.18"],
    ["Fabiani, Sebastiani", "Pope Fabian and Sebastian, Martyrs", "14012000", "Jan.20"],
    [
      "Felicis",
      "Felix, Bishop and Martyr (falsely called Pope Felix II)",
      "14072900", "Jul.29"],
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
      "4000000", ""],
    ["Flori", "Florus, Bishop of Lodève", "14110400", "Nov.4"],
    ["Franchae", "Franca Visalta, Virgin and Abbess, at Piacenza", "14042700", "Apr.4"],
    ["Francisci", "Francis of Assisi", "14100400", "Oct.4"],
    [
      "Francisci Xaverii",
      "Francis Xavier, Confessor, Apostle of India and Japan (canonized in 1622)",
      "14120300", "Dec.3"],
    [
      "Fructuosi Archiepiscopi Bracharensis",
      "Fructuosus, Archbishop of Braga",
      "14041610", "Apr.16"],
    ["Gabrielis, Archang.", "Gabriel the Archangel", "14031800", "Mar.18"],
    ["Genesii", "Genesius, Martyr", "14082530", "Aug.25"],
    ["Georgii", "George, Martyr", "14042300", "Apr.23"],
    [
      "Geraldi Archiepiscopi Bracharensis",
      "Gerald (Girald), Archbishop of Braga",
      "14120510", "Dec.5"],
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
      "14021200", "Feb.12"],
    ["Ignatii", "Ignatius, Bishop of Antioch, Martyr", "14020110", "Feb.1"],
    ["Ildefonsi", "Ildephonsus, Archbishop of Toledo", "14012301", ""],
    [
      "In dedicatione Basilicae BMV de Martyribus Ulyssipponensis",
      "Dedication of the Basilica of Our Lady of the Martyrs, Lisbon",
      "14051320", "May.13"],
    ["In Dedicatione Ecclesiae", "Dedication of a Church", "12008000", ""],
    ["In festo sollemni", "", "xxx", ""],
    ["In Letaniis", "General, Rogation Days", "8068010", ""],
    ["In tempore Adventus", "General, in Advent", "1000000", ""],
    [
      "In tempore belli contra Sarracenos",
      "Chants in time of war against the Saracens",
      "16021000", ""],
    ["In tempore Epiphaniae", "General, after Epiphany", "5000000", ""],
    ["In tempore Nat.", "General, in Christmastide", "3000000", ""],
    [
      "In tempore oritur inter Christianos",
      "Chants in time of an uprising among Christians",
      "16022000", ""],
    ["In tempore Paschae", "General, Eastertide", "8000000", ""],
    ["In tempore pestilentiae", "Chants in time of the plague", "16018000", ""],
    ["In tempore Quad.", "General, in Lent", "7000000", ""],
    ["In Triduum", "General, during the Triduum", "7069000", ""],
    ["Inventio Crucis", "Finding of the Cross", "14050300", "May.3"],
    [
      "Inventio Stephani",
      "Finding of Stephen's relics (First Martyr)",
      "14080300", "Aug.3"],
    ["Invitatoria", "Invitatory antiphons or psalms", "16004000", ""],
    ["Irenes", "Irene of Santarém (Portugal)", "14102010", "Oct.20"],
    ["Isidori Episcopi Confessoris et Ecclesiae Doctoris", "", "", "Apr.4"],
    [
      "Ivonis de Kermartin",
      "Ivo of Kermartin (Yves Hélory, Yvo, Ives)",
      "14051930", "May.19"],
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
      "14080630", "Aug.6"],
    ["Laurentii", "Laurence, Martyr", "14081000", "Aug.10"],
    ["Laurentii,8", "In week after Laurence", "14081008", ""],
    [
      "Lauteni",
      "Lautein (Lothenus, Lautenus), Abbot, founder of Silèze and Maximiac abbeys in the Jura mountains",
      "14110200", "Nov.2"],
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
      "12802300", ""],
    ["Marthae", "Martha, Virgin", "14072930", "Jul.29"],
    ["Martini", "Martin, Bishop of Tours", "14111100", "Nov.11"],
    [
      "Martini Archiepiscopi Bracharensis",
      "Martin, Bishop of Braga",
      "14032020", "Mar. 20"],
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
      "14070600", "Jul.6"],
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
      "8021000", ""],
    ["Octava Paschae,8", "In 2nd week after Easter", "8021008", ""],
    ["Omnium Sanctorum", "All Saints' Day", "14110100", "Nov.1"],
    ["Omnium Sanctorum,8", "In week after All Saints' Day", "14110108", ""],
    [
      "Pancratii et sociorum",
      "Pancratius (Pancras) and companions, Martyrs",
      "14051210", "May.12"],
    ["Paulae", "Paula, Widow", "14012610", "Jan.26"],
    ["Pauli", "Paul, Apostle", "14063000", "Jun.30"],
    ["Pauli Heremitae", "Paul the Hermit", "14011010", "Jan.10"],
    ["Pauli,8", "In week after Paul (Apostle)", "14063008", ""],
    ["Petri", "Peter, Apostle", "14062910", "Jun.29"],
    ["Petri Alexandrini Ep. Mart.", "", "", "Nov.26"],
    [
      "Petri de Rates",
      "Peter de Rates, reputed first Bishop of Braga",
      "4042610", "Apr.26"],
    ["Petri Gundisalvi", "Peter González, Dominican friar", "14041410", "Apr.14"],
    ["Petri Regalati", "Peter Regalado, Franciscan friar", "14051330", "May.13"],
    [
      "Petri, Mart.",
      "Peter the Martyr, Dominican Friar and Priest",
      "14042900", "Apr.29"],
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
      "16019000", ""],
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
      "14051910", "May.19"],
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
      "14101930", "Oct.19"],
    [
      "Sacratissimi Cordis Jesu",
      "Most Sacred Heart of Jesus, on Friday after the second Sunday after Pentecost",
      "9026010", ""],
    ["Samsonis", "Samson, Bishop of Dol", "14072810", "Jul.28"],
    [
      "Sanctorum Quinque Martyrum Ordinis Minorum",
      "Five Martyrs of Morocco",
      "14011630", "Jan.16"],
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
      "14041710", "Apr.17"],
    ["Stephani, Pont.", "Stephen I, Pope", "14080200", "Aug.2"],
    ["Stephani,8", "In week after Stephen", "2122608", ""],
    ["Suff. Crucis", "Memorial chants for the Holy Cross", "15050300", ""],
    [
      "Suff. Crucis TP",
      "Memorial chants for the Holy Cross, Eastertide",
      "15050380", ""],
    ["Suff. Mariae TP", "Memorial chants for Mary, Eastertide", "15081580", ""],
    [
      "Suff. Om. Sanct. TP",
      "Memorial chants for All Saints, Eastertide",
      "15110180", ""],
    ["Suff. pro Pace", "Memorial chants for peace", "15001000", ""],
    ["Suff. pro Penitent.", "Memorial chants for penitence", "15003000", ""],
    ["Symphoriani", "Symphorian (and Timothy), Martyrs", "14082200", "Aug.22"],
    ["Syri", "Syrus", "14120900", "Dec.9"],
    ["Taurini", "Taurinus, Bishop of Evreux", "14081130", "Aug.11"],
    [
      "Teresiae Avilensis",
      "Teresa (Teresia), Virgin, reformer of the Carmelite Order and ascetical write (canonized in 1622)",
      "14101500", "Oct.15"],
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
      "14041400", "Apr.14"],
    ["Timothei, Apollinaris", "Timothy and Apollinaris, Martyrs", "14082300", "Aug.23"],
    ["Transfiguratio Dom.", "Transfiguration of Jesus", "14080600", "Aug.6"],
    ["Transl. Benedicti", "Moving of Benedict's relics", "14071100", "Jul.11"],
    ["Transl. Jacobi", "Translation of James the Greater", "14123020", "Dec.30"],
    ["Transl. Martini", "Moving of Martin's relics", "14070400", "Jul.4"],
    ["Tres Mariae", "Mary, Mary Cleophae, Mary Salome", "14052520", "May.25"],
    [
      "Triumphi Sanctae Crucis apud Navas Tolosae",
      "Triumph of the Holy Cross in Las Navas de Tolosa (Battle of Las Navas de Tolosa or Battle of Al-Uqab)",
      "14071730", "Jul.17"],
    ["Urbani", "Urban I, Pope and Martyr", "14052510", "May.25"],
    ["Valentini", "Valentine, Martyr", "14021400", "Feb.14"],
    [
      "Valentini, Hylarii",
      "Valentine (priest) and Hilary (deacon), at Viterbo",
      "14110360", "Nov.3"],
    ["Victoris Bracharensis", "Victor, Martyr at Braga", "14041210", "Apr.12"],
    [
      "Victoris et sociorum",
      "Victor of Marseilles and companions, Martyrs",
      "14072110", "Jul.21"],
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
      "14012230", "Jan.22"],
    [
      "Vincentii, transl.",
      "Moving of Vincent of Saragossa's relics to Lisbon",
      "14091560", "Sep.15"],
    [
      "Vincentii, transl. in Brachara",
      "Moving of Vincent of Saragossa's relics to Braga",
      "14050450", "May.4"],
    ["Vincula Petri", "Peter in Chains", "14080100", "Aug.1"],
    ["Visitatio Mariae", "Visitation of Mary", "14070200", "Jul.2"],
    ["Vitalis, Valeriae", "Vitalis and Valeria, Martyrs", "14042800", "Apr.28"],
    ["XI milium Virginum", "11,000 Virgin Martyrs of Cologne", "14102100", "Oct.21"]
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
 * Update document_type taxonomies.
 */
function sb_core_deploy_108017(): void {
  // Delete existing types.
  $taxonomy_storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  $terms = $taxonomy_storage->loadByProperties(['vid' => 'document_type']);
  foreach ($terms as $term) {
    $term->delete();
  }

  // Create new ones.
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
    ['Outra', 'Other']
  ];

  foreach ($types as $type) {
    $term = Term::create([
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
