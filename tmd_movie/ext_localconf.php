<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_tmdmovie_movie=1
');
/*
t3lib_extMgm::addPageTSConfig('

	# ***************************************************************************************
	# CONFIGURATION of RTE in table "tx_tmdmovie_movie", field "summary"
	# ***************************************************************************************
RTE.config.tx_tmdmovie_movie.summary {
  hidePStyleItems = H1, H4, H5, H6
  proc.exitHTMLparser_db=1
  proc.exitHTMLparser_db {
    keepNonMatchedTags=1
    tags.font.allowedAttribs= color
    tags.font.rmTagIfNoAttrib = 1
    tags.font.nesting = global
  }
}
');
*/

t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_tmdmovie_rating=1
');

t3lib_extMgm::addUserTSConfig('
    options.saveDocNew.tx_tmdmovie_genre=1
');

  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,"editorcfg","
	tt_content.CSS_editor.ch.tx_tmdmovie_pi1 = < plugin.tx_tmdmovie_pi1.CSS_editor
",43);


t3lib_extMgm::addPItoST43($_EXTKEY,"pi1/class.tx_tmdmovie_pi1.php","_pi1","list_type",1);


t3lib_extMgm::addTypoScript($_EXTKEY,"setup","
	tt_content.shortcut.20.0.conf.tx_tmdmovie_movie = < plugin.".t3lib_extMgm::getCN($_EXTKEY)."_pi1
	tt_content.shortcut.20.0.conf.tx_tmdmovie_movie.CMD = singleView
",43);
?>