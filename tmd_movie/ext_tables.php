<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

t3lib_extMgm::allowTableOnStandardPages("tx_tmdmovie_movie");


t3lib_extMgm::addToInsertRecords("tx_tmdmovie_movie");

$TCA["tx_tmdmovie_movie"] = array (
    "ctrl" => array (
        'title'     => 'LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie',        
        'label'     => 'title',    
        'tstamp'    => 'tstamp',
        'crdate'    => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => "ORDER BY title",    
        'delete' => 'deleted',    
        'thumbnail' => "poster",
		'dividers2tabs' => TRUE,
        'enablecolumns' => array (        
            'disabled' => 'hidden',    
            'starttime' => 'starttime',    
            'endtime' => 'endtime',    
            'fe_group' => 'fe_group',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_tmdmovie_movie.png',
    ),
    "feInterface" => array (
		"fe_admin_fieldList" => "hidden, starttime, endtime, fe_group, artikel, title, short, screenformat, runningtime, rating, rating_nf, distributor, releasedate, web, sound, summary, poster, mediafile, fbw, genre, director, producer, actor, originaltitle, productionyear, country",
    )
);

	// initalize "context sensitive help" (csh)
t3lib_extMgm::addLLrefForTCAdescr('tx_tmdmovie_movie','EXT:tmd_movie/locallang_csh_txtmdmoviemovie.xml');

$TCA["tx_tmdmovie_rating"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_rating",
		"label" => "rating",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
        'sortby' => 'sorting',
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_tmdmovie_rating.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, rating, tooltip",
	)
);

$TCA["tx_tmdmovie_genre"] = array (
    "ctrl" => array (
        'title' => 'LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_genre',
        'label' => 'genre',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => "ORDER BY genre",
        'delete' => 'deleted',
        'enablecolumns' => array (
            'disabled' => 'hidden',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_tmdmovie_genre.gif',
    ),
    "feInterface" => array (
        "fe_admin_fieldList" => "hidden, genre",
    )
);


t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi1"]="layout,select_key";

# Wir blenden die Standard Felder layout,select_key,pages,recursive  von Plugins aus
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';
# ,pages,recursive

# Daf�r blenden wir das tt_content Feld pi_flexform ein
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';

# Wir definieren die Datei, die unser Flexform Schema enthält
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds.xml');

t3lib_extMgm::addPlugin(Array("LLL:EXT:tmd_movie/locallang_db.php:tt_content.list_type_pi1", $_EXTKEY."_pi1"),"list_type");
t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","Cinema Movie");


?>