<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

$TCA["tx_tmdmovie_movie"] = Array (
	"ctrl" => $TCA["tx_tmdmovie_movie"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,starttime,endtime,fe_group,artikel,title,short,screenformat,runningtime,rating,distributor,releasedate,web,sound,summary,poster,mediafile,fbw,genre,director,producer,actor,originaltitle,productionyear,country"
	),
	"feInterface" => $TCA["tx_tmdmovie_movie"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "1"
			)
		),
		"starttime" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.starttime",
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"default" => "0",
				"checkbox" => "0"
			)
		),
		"endtime" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.endtime",
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"checkbox" => "0",
				"default" => "0",
				"range" => Array (
					"upper" => mktime(0,0,0,12,31,2020),
					"lower" => mktime(0,0,0,date("m")-1,date("d"),date("Y"))
				)
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"artikel" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.artikel",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.artikel.I.0", "0"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.artikel.I.1", "1"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.artikel.I.2", "2"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.artikel.I.3", "3"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.artikel.I.4", "4"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.artikel.I.5", "5"),
				),
				"size" => 1,	
				"maxitems" => 1,
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"short" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.short",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"screenformat" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.screenformat",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.screenformat.I.0", "0", t3lib_extMgm::extRelPath("tmd_movie")."selicon_tx_tmdmovie_movie_screenformat_0.gif"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.screenformat.I.1", "1", t3lib_extMgm::extRelPath("tmd_movie")."selicon_tx_tmdmovie_movie_screenformat_1.gif"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.screenformat.I.2", "2", t3lib_extMgm::extRelPath("tmd_movie")."selicon_tx_tmdmovie_movie_screenformat_2.gif"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.screenformat.I.3", "3", t3lib_extMgm::extRelPath("tmd_movie")."selicon_tx_tmdmovie_movie_screenformat_3.gif"),
				),
				"size" => 1,	
				"maxitems" => 1,
			)
		),
		"runningtime" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.runningtime",		
			"config" => Array (
				"type" => "input",	
				"size" => "4",
				"max" => "4",
				"eval" => "int",
				"checkbox" => "0",
				"range" => Array (
					"upper" => "1000",
					"lower" => "1"
				),
				"default" => 0
			)
		),
		"rating" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.rating",		
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("",0),
				),
				"foreign_table" => "tx_tmdmovie_rating",	
				"foreign_table_where" => "ORDER BY tx_tmdmovie_rating.sorting",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"distributor" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie.distributor",        
            "config" => Array (
                "type" => "select",    
                "items" => Array (
                    Array("",0),
                ),
                "foreign_table" => "tt_address",    
                "foreign_table_where" => "  AND tt_address.pid=###PAGE_TSCONFIG_ID### ORDER BY tt_address.last_name",	
                "size" => 1,    
                "minitems" => 0,
                "maxitems" => 1,
            )
        ),
		"releasedate" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.releasedate",		
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"checkbox" => "0",
				"default" => "0"
			)
		),
		"web" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.web",		
			"config" => Array (
				"type" => "input",		
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"sound" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound",		
			"config" => Array (
				"type" => "check",
				"cols" => 4,
				"default" => 3,
				"items" => Array (
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.0", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.1", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.2", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.3", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.4", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.5", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.6", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.7", ""),
/*					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.8", ""),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.sound.I.9", ""),
*/				),
			)
		),
		"summary" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.summary",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"poster" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.poster",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_tmdmovie",
				"show_thumbs" => 1,	
				"size" => 2,	
				"minitems" => 0,
				"maxitems" => 5,
			)
		),
		"mediafile" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.mediafile",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_tmdmovie",
				"show_thumbs" => 1,	
				"size" => 3,	
				"minitems" => 0,
				"maxitems" => 5,
			)
		),
		"fbw" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.fbw",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.fbw.I.0", "0"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.fbw.I.1", "1"),
					Array("LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.fbw.I.2", "2"),
				),
				"size" => 1,	
				"maxitems" => 1,
			),
		),


		"genre" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_movie.genre",		
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					),
				"foreign_table" => "tx_tmdmovie_genre",	
				"foreign_table_where" => " AND tx_tmdmovie_genre.pid=###PAGE_TSCONFIG_ID### ORDER BY tx_tmdmovie_genre.genre",	
				"size" => 10,	
				"minitems" => 0,
				"maxitems" => 15,	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_tmdmovie_genre",
							"pid" => "###PAGE_TSCONFIG_ID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
					"list" => Array(
						"type" => "script",
						"title" => "List",
						"icon" => "list.gif",
						"params" => Array(
							"table"=>"tx_tmdmovie_genre",
							"pid" => "###PAGE_TSCONFIG_ID###",
						),
						"script" => "wizard_list.php",
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
					),
				),
			),
		),
		"director" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie.director",        
            "config" => Array (
                "type" => "text",
                "cols" => "30",    
                "rows" => "2",
            )
        ),
        "producer" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie.producer",        
            "config" => Array (
                "type" => "text",
                "cols" => "30",    
                "rows" => "3",
            )
        ),
        "actor" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie.actor",        
            "config" => Array (
                "type" => "text",
                "cols" => "30",    
                "rows" => "5",
            )
        ),
    	"originaltitle" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie.originaltitle",        
            "config" => Array (
                "type" => "input",    
                "size" => "30",
            )
        ),
        "productionyear" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie.productionyear",        
            "config" => Array (
                "type"     => "input",
                "size"     => "4",
                "max"      => "4",
                "eval"     => "int",
                "checkbox" => "0",
                "range"    => Array (
/*                    "upper" => "2100", */
                    "lower" => "1885"
                ),
                "default" => strftime("%Y", time()-3*30*24*60*60)
            )
        ),
        "country" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_movie.country",        
            "config" => Array (
                "type" => "input",    
                "size" => "30",
                "default" => "USA",
            )
        ),

        
    ),
/*
    "types" => array (
        "0" => array("showitem" => "hidden;;1;;1-1-1,
        							artikel, title, short, originaltitle;;;;2-2-2,
        							screenformat, runningtime, rating, distributor, releasedate, web, sound;;;;3-3-3,
        							summary;;;richtext[cut|copy|paste|formatblock|textcolor|bold|italic|underline|left|center|right|orderedlist|unorderedlist|outdent|indent|link|table|image|line|chMode]:rte_transform[mode=ts_css|imgpath=uploads/tx_tmdmovie/rte/];;;;4-4-4,
        							poster, mediafile;;;;5-5-5,
        							fbw, genre, director, producer, actor,productionyear, country;;;;6-6-6")
    ),
    ;;;;3-3-3
*/
    "types" => array (
        "0" => array("showitem" => "
        							--div--;Titel,hidden;;1;;1-1-1, artikel;;;;2-2-2, title, short, originaltitle,
        							--div--;Technik, runningtime, rating, fbw, releasedate, screenformat, distributor, web, sound,
        							--div--;Inhalt,summary;;;richtext[cut|copy|paste|formatblock|textcolor|bold|italic|underline|left|center|right|orderedlist|unorderedlist|outdent|indent|link|table|image|line|chMode]:rte_transform[mode=ts_css|imgpath=uploads/tx_tmdmovie/rte/],
        							--div--;Medien,poster, mediafile,
        							--div--;Pruduktion,genre, director, producer, actor,productionyear, country,
									")
    ),

    "palettes" => array (
        "1" => array("showitem" => "starttime, endtime, fe_group")
    )
);

# "types" => array (
# "0" => array("showitem" => "--div--;Produkt,sys_language_uid;;;;1-1-1, l18n_parent, l18n_diffsource, hidden;;1, name, text;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], icon, mood, related, detail, multicolorimage,--div--;ProduktFinder,artikelnr, temphigh, templow, prodcategory, sports, sex,sortgroup,sizes,sizecorrect,lengthcorrect"),
# ),

$TCA["tx_tmdmovie_rating"] = Array (
	"ctrl" => $TCA["tx_tmdmovie_rating"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,rating,tooltip"
	),
	"feInterface" => $TCA["tx_tmdmovie_rating"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"rating" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_rating.rating",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"tooltip" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:tmd_movie/locallang_db.php:tx_tmdmovie_rating.tooltip",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, rating, tooltip")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);


$TCA["tx_tmdmovie_genre"] = array (
    "ctrl" => $TCA["tx_tmdmovie_genre"]["ctrl"],
    "interface" => array (
        "showRecordFieldList" => "hidden,genre"
    ),
    "feInterface" => $TCA["tx_tmdmovie_genre"]["feInterface"],
    "columns" => array (
        'hidden' => array (        
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array (
                'type' => 'check',
                'default' => '0'
            )
        ),
        "genre" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:tmd_movie/locallang_db.xml:tx_tmdmovie_genre.genre",        
            "config" => Array (
                "type" => "input",    
                "size" => "30",    
                "eval" => "required",
            )
        ),
    ),
    "types" => array (
        "0" => array("showitem" => "hidden;;1;;1-1-1, genre")
    ),
    "palettes" => array (
        "1" => array("showitem" => "")
    )
);
?>