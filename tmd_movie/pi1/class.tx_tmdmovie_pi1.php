<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Christian Tauscher (cms@media-distillery.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'Cinema Movie' for the 'tmd_movie' extension.
 *
 * @author	Christian Tauscher <cms@media-distillery.de>
 */


require_once(PATH_tslib."class.tslib_pibase.php");

class tx_tmdmovie_pi1 extends tslib_pibase {
	var $prefixId = "tx_tmdmovie_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_tmdmovie_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "tmd_movie";	// The extension key.
	var $uploadPath = "uploads/tx_tmdmovie/";
	var $pi_checkCHash = true;

	/**
	 * [Put your description here]
	 */
	function main($content,$conf)
		{
			/* initialisierungen */
		$this->pidList = $this->pi_getPidList($this->conf["pidList"],$this->conf["recursive"]);
		$this->initFF();

		switch((string)$conf["CMD"])
			{
			case "singleView":
				list($t) = explode(":",$this->cObj->currentRecord);
				$this->internal["currentTable"]=$t;
				$this->internal["currentRow"]=$this->cObj->data;

				return $this->pi_wrapInBaseClass($this->singleView($content,$conf));
			break;
			default:
				if (strstr($this->cObj->currentRecord,"tt_content")) {
					$conf["pidList"] = $this->cObj->data["pages"];
					$conf["recursive"] = $this->cObj->data["recursive"];
					}
				return $this->pi_wrapInBaseClass($this->listView($content,$conf));
			break;
			}
		}



	/**
	 * [Put your description here]
	 */
	function listView($content,$conf)
		{
		$this->conf=$conf;		// Setting the TypoScript passed to this function in $this->conf
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();		// Loading the LOCAL_LANG values

		$lConf = $this->conf["listView."];	// Local settings for the listView function

		if ($this->piVars["showUid"]) {	// If a single element should be displayed:
			$this->internal["currentTable"] = "tx_tmdmovie_movie";
			$this->internal["currentRow"] = $this->pi_getRecord("tx_tmdmovie_movie",$this->piVars["showUid"]);

			$content = $this->singleView($content,$conf);
			return $content;
		} else {
			$items= $this->makeABC();

			if (!isset($this->piVars["pointer"]))	$this->piVars["pointer"]=0;
			if (!isset($this->piVars["sort"]))		$this->piVars["sort"] = "title:1";
			if (!isset($this->piVars["abc"]))		$this->piVars["abc"] = "latest";

			$this->internal['searchFieldList']='title,originaltitle,short,summary,director,producer,actor';
			list($this->internal["orderBy"], $this->internal["descFlag"]) = explode(":",$this->piVars["sort"]);
			
				# page Browser			
			$this->internal["results_at_a_time"]=t3lib_div::intInRange($lConf["results_at_a_time"],0,1000,3);		// Number of results to show in a listing.
			$this->internal["maxPages"]=t3lib_div::intInRange($lConf["maxPages"],0,1000,5);;		// The maximum number of "pages" in the browse-box: "Page 1", "Page 2", etc.
			$this->internal['showFirstLast'] = FALSE;
			$this->internal['showRange'] = $this->conf['listView.']['showRange'];
			$this->internal['dontLinkActivePage'] = $this->conf['listView.']['dontLinkActivePage'];
				// 	pagefloat set as integer. 0 = left, value >= $this->internal['maxPages'] = right
			$this->internal['pagefloat'] = $this->conf['listView.']['pagefloat'];
			
			
			switch($this->piVars["abc"]) {
				case 'latest':
					$this->internal["orderBy"] = "crdate";
					$orderBy = $this->internal["orderBy"]." DESC";
				break;
				case 'all':
					$where = "";
					$orderBy = $this->internal["orderBy"]." ".($this->internal["descFlag"] ? "ASC" : "DESC");
				break;
				default:
					$where  = " AND title LIKE '".$this->piVars["abc"]."%'";
					$orderBy = $this->internal["orderBy"]." ".($this->internal["descFlag"] ? "ASC" : "DESC");
			}

			
			
#debug($this->piVars);

				// Get number of records:
			$res = $this->pi_exec_query("tx_tmdmovie_movie", 1, $where,$mm_cat='',$groupBy='',$orderBy,$query=''); # 1 = count
			list($this->internal["res_count"]) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				// Make listing query, pass query to SQL database:
			$res = $this->pi_exec_query("tx_tmdmovie_movie", 0, $where,$mm_cat='',$groupBy='',$orderBy,$query='');
			$this->internal["currentTable"] = "tx_tmdmovie_movie";

			if($this->ff['def']['mode'] == 'amazon')
				{
				$fullTable = $this->amazonAdwords($res);
				}
			else
				{
					// Put the whole list together:
				$fullTable="";	// Clear var;

					// Adds the mode selector.
				$fullTable.=$this->list_abcSelector($items);

					// Adds the whole list table
				$fullTable.=$this->pi_list_makelist($res);
#debug($this->piVars);
#debug($this->internal, "latest");
#debug($where, "where");

					// Adds the search box:
				$fullTable.=$this->pi_list_searchBox();

					// Adds the result browser:
				$fullTable.=$this->pi_list_browseresults();
				}

				// Returns the content from the plugin.
			return $fullTable;
		}
	}




	/**
	 * [Put your description here]
	 */
	function singleView($content,$conf)
		{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		if($this->ff['def']['mode'] == 'amazon') {
			return $this->amazonAdwords();
		}

		return $this->substituteMarkers("###SINGLE_VIEW###");
		}




	/**
	 * [Put your description here]
	 */
	function pi_list_row($c) {
		if ($editPanel)	$editPanel="<td>".$editPanel."</td>";

		return $this->substituteMarkers("###LIST_VIEW_ROW###");
	}




		/**
		 * Alle marker die im Template benutzt werden können
		 *
		 * @param string Template string
		 * @return string Template mit überschriebenen Markern
		 */
	function substituteMarkers($templ) {
		$out = $this->cObj->fileResource($this->conf['template']);

		$out = $GLOBALS['TSFE']->cObj->getSubpart($out, $templ);

		$out = $this->cObj->substituteMarker($out, '###TITLE###', 		$this->getFieldContent('title'));
		$out = $this->cObj->substituteMarker($out, '###ORIGINALTITLE###',$this->getFieldContent('originaltitle'));
		$out = $this->cObj->substituteMarker($out, '###SHORTTITEL###', 	$this->getFieldContent('short'));
		$out = $this->cObj->substituteMarker($out, '###SCREENFORMAT###',$this->getFieldContent('screenformat'));
		$out = $this->cObj->substituteMarker($out, '###RUNNINGTIME###', $this->getFieldContent('runningtime'));
		$out = $this->cObj->substituteMarker($out, '###RATING###', 		$this->getFieldContent('rating'));
		$out = $this->cObj->substituteMarker($out, '###DISTRIBUTOR###', $this->getFieldContent('distributor'));
		$out = $this->cObj->substituteMarker($out, '###RELEASEDATE###', $this->getFieldContent('releasedate'));
		$out = $this->cObj->substituteMarker($out, '###WEB###', 		$this->getFieldContent('web'));
		$out = $this->cObj->substituteMarker($out, '###SOUND###', 		$this->getFieldContent('sound'));
		$out = $this->cObj->substituteMarker($out, '###POSTER###',		$this->getFieldContent('poster'));
		$out = $this->cObj->substituteMarker($out, '###GENRE###',		$this->getFieldContent('genre'));
		$out = $this->cObj->substituteMarker($out, '###SUMMARY###', 	$this->getFieldContent('summary'));
		$out = $this->cObj->substituteMarker($out, '###SUMMARY_SHORT###',$this->getFieldContent('summary_short'));
		$out = $this->cObj->substituteMarker($out, '###FBW###', 		$this->getFieldContent('fbw'));
		$out = $this->cObj->substituteMarker($out, '###DIRECTOR###', 	$this->getFieldContent('director'));
		$out = $this->cObj->substituteMarker($out, '###PRODUCER###', 	$this->getFieldContent('producer'));
		$out = $this->cObj->substituteMarker($out, '###ACTOR###', 		$this->getFieldContent('actor'));
		$out = $this->cObj->substituteMarker($out, '###VERSION3D###', 	$this->getFieldContent('version3D'));
		$out = $this->cObj->substituteMarker($out, '###MEDIA_1###',		$this->getFieldContent('movie_media-1'));
		$out = $this->cObj->substituteMarker($out, '###MEDIA_2###',		$this->getFieldContent('movie_media-2'));
		$out = $this->cObj->substituteMarker($out, '###MEDIA_3###',		$this->getFieldContent('movie_media-3'));
		$out = $this->cObj->substituteMarker($out, '###MEDIA_4###',		$this->getFieldContent('movie_media-4'));
		$out = $this->cObj->substituteMarker($out, '###MEDIA_5###',		$this->getFieldContent('movie_media-5'));
				
		$out = $this->cObj->substituteMarker($out, '###SORT_TITLE###', 			$this->getFieldHeader_sortLink('title'));
		$out = $this->cObj->substituteMarker($out, '###SORT_ORIGINALTITLE###', 	$this->getFieldHeader_sortLink('originaltitle'));
		$out = $this->cObj->substituteMarker($out, '###SORT_SCREENFORMAT###', 	$this->getFieldHeader_sortLink('screenformat'));
		$out = $this->cObj->substituteMarker($out, '###SORT_RUNNINGTIME###', 	$this->getFieldHeader_sortLink('runningtime'));
		$out = $this->cObj->substituteMarker($out, '###SORT_RATING###', 		$this->getFieldHeader_sortLink('rating'));
		$out = $this->cObj->substituteMarker($out, '###SORT_DISTRIBUTOR###', 	$this->getFieldHeader_sortLink('distributor'));
		$out = $this->cObj->substituteMarker($out, '###SORT_RELEASEDATE###',	$this->getFieldHeader_sortLink('releasedate'));

										# noch verlinken wenn es gewünscht ist
		$link = $this->pi_list_linkSingle('|', $this->getFieldContent('uid'),$cache=FALSE,$mergeArr=array(),$urlOnly=FALSE,$altPageId=0);
		$wrappedSubpartArray['###LINKITEM###'] = explode('|', $link);
		$out = $this->cObj->substituteMarkerArrayCached($out, $markerArray, $subpartArray, $wrappedSubpartArray);

			
		if ($this->conf['CMD'] != "singleView")
			$out = $this->cObj->substituteMarker($out, '###BACK###',	$this->pi_list_linkSingle($this->pi_getLL("back","Back"),0));
		else
			$out = $this->cObj->substituteMarker($out, '###BACK###',	""); # marker �berschreiben

		return $out;
	}




	/**
	 * [Put your description here]
	 */
	function pi_list_header() {
		# sorting via TS
		return $this->substituteMarkers("###LIST_VIEW_HEADER###");
	}



	/**
	 * Inhalte aus der DB holen und neu Formatieren
	 */
	function getFieldContent($fN) { 
		$type = 'listView.';
		if($this->piVars['showUid'])
			{
			$type = 'singleView.';
			}
		
		if(!$this->ratingCache)
			{
			$select = "uid,rating";
			$table = "tx_tmdmovie_rating";
			$where = "1";
			$group = "";
			$order = "";
			$limit = "";
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $group, $order, $limit);
			while($out = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
				$this->ratingCache[$out['uid']] = $out['rating'];

			#debug($this->ratingCache);
			}

		if(!$this->genreCache)
			{
			$select = "uid,genre";
			$table = "tx_tmdmovie_genre";
			$where = "1";
			$group = "";
			$order = "";
			$limit = "";
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $group, $order, $limit);
			while($out = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
				$this->genreCache[$out['uid']] = $out['genre'];

			#debug($this->ratingCache);
			}


		switch($fN)
			{
			case "web":
				# tslib_cObj.typoLink()
				if($this->internal["currentRow"]["web"])
					{
					$out = $this->cObj->getTypoLink('Offizielle Website',
													$this->internal["currentRow"]["web"],
												 	'',
													"_blank");

					if($out)
						{
						$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['WEB']);
						return $out;
						}
					}
				return $out;
			break;
			case "screenformat" :
				$out = $this->internal["currentRow"]["screenformat"];
				# 0 = BW 1= CS 2=N
				switch($out)
					{
					case 1: $out = "Breitwand"; break;
					case 2: $out = "CinemaScope"; break;
					case 3: $out = "Normal"; break;
					default: $out = "";
					}

				if($out)
						{
						$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['SCREENFORMAT']);
						return $out;
						}
			break;
			case "genre":
				$genre = explode(",", $this->internal["currentRow"]["genre"]);

				foreach($genre as $id)
					$out[] = $this->genreCache[$id];

				$out = implode(", ", $out);
				if($out)
						{
						$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['GENRE']);
						return $out;
						}
			break;
			case "runningtime":
				$out = $this->internal["currentRow"]["runningtime"];
				if($out)
						{
						$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['RUNNINGTIME']);
						return $out;
						}
			break;
			case "version3D":
				$out = $this->internal["currentRow"]["3d"];
				if($out) {
					$out = $this->cObj->wrap('', $this->conf['wrap.'][$type]['VERSION3D']);
					return $out;
				}
			break;
			case "distributor":
				$field = $this->internal["currentRow"]["distributor"];

				if(!$this->distribCache[$field] && $field != 0)
					{
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery("name","tt_address","uid=".$field);
					$erg = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					$this->distribCache[$field] = $erg[name];
					}

				$out = $this->cObj->wrap($out, $this->distribCache[$field]);
				if($out)
					{
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['DISTRIBUTOR']);
					return $out;
					}
			break;
			case "rating":
				$out = $this->ratingCache[$this->internal["currentRow"]["rating"]];
				if($out)
					{
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['RATING']);
					return $out;
					}

			break;
			case "sound":
				$sound = array("DolbySR","DolbyDIGITAL","DTS","SDDS","Mono","Stereo","DolbyDIGITAL-EX","DTS-EX");

				$field = $this->internal["currentRow"]["sound"];
				for($i = 0; $i < 8; $i++)
					if ($field & pow(2,$i))
						$out .= $sound[$i]." ";

				$out = trim($out);
				if($out)
						{
						$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['SOUND']);
						return $out;
						}

			break;
			case 'poster':
				if($this->piVars['showUid'] || $this->conf['CMD'] == "singleView") {
					$this->conf['image.']['file.']['width'] = $this->conf['imageW.']['single'];
				} else {
					$this->conf['image.']['file.']['width'] = $this->conf['imageW.']['list'];
				}

				if($this->internal["currentRow"]['poster']) {
					$temp = explode(',', $this->internal["currentRow"]['poster']); # mehrere Poster?
					$this->conf['image.']['file'] = $this->uploadPath.$temp[rand(0,count($temp)-1)];
				} else {
					$this->conf['image.']['file'] = $this->uploadPath.$this->getFieldContent('movie_media-random');
				}

				if($this->conf['image.']['file'] == $this->uploadPath) {
					$this->conf['image.']['file'] = $this->conf['dummyPoster']; # dummy
				}

				$this->conf['image.']['altText'] = $this->internal["currentRow"]['title'];
				$out = $this->cObj->IMAGE($this->conf['image.']);

				if($out)
						{
						$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['POSTER']);
						return $out;
						}

				return $out;
			break;

			case 'movie_media-1':
			case 'movie_media-2':
			case 'movie_media-3':
			case 'movie_media-4':
			case 'movie_media-5':
			case 'movie_media-random':
				list(,$nr) = explode("-", $fN);
				$pic = explode(",", $this->internal['currentRow']['mediafile']);
								
				if($nr == 'random')
					{
					foreach($pic as $key => $val)
						{
						if($val == '')
							unset($pic[$key]);
						}
					
					$c = count($pic);
					if($c == 0)
						break;
					
					return $pic[rand(0,$c-1)];			
					}
				else 
					{
					$nr--; // bei 0 anfangen zu zählen
					$pic = $pic[$nr];
					}

				$this->conf['media.']['file'] = 'uploads/tx_tmdmovie/'.$pic;
				$out = $this->cObj->IMAGE($this->conf['media.']);

				return $out;
			break;








			case "fbw":
				$field = $this->internal["currentRow"]["fbw"];
				for($i = 0; $i < 2; $i++)
					if ($field & pow(2,$i))
						$res .= $this->conf['fbw.'][$i]." ";
				$out = trim($res);
				if($out)
					{
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['FBW']);
					return $out;
					}

			break;
			case "title":
				$out = $this->internal["currentRow"]["title"];
				$out = $this->conf['artikel.'][$this->internal["currentRow"]["artikel"]].' '.$out;
				$out = trim($out);

				if($out)
					{
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['TITLE']);
					return $out;
					}
			break;
			case 'short':
				$out = trim($this->internal["currentRow"]["short"]);

				if($out) {
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['SHORT']);
					return $out;
				}
			break;
			
			case "originaltitle":
				$out = $this->internal["currentRow"]["originaltitle"];
				if($out)
					{
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['ORIGINALTITLE']);
					return $out;
					}
			break;
			case "releasedate":
					// For a numbers-only date, use something like: %d-%m-%y
				if($this->internal["currentRow"]["releasedate"] != 0)
					$out = strftime($this->conf['strftime'], $this->internal["currentRow"]["releasedate"]);

				if($out)
					{
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['RELEASEDATE']);
					return $out;
					}
			break;
			case "summary":
				return $this->pi_RTEcssText($this->internal["currentRow"]["summary"]);
			break;
			case 'summary_short':
				$out = $this->internal["currentRow"]["summary"];
				if($out)
					{
					$out = $this->cObj->stdWrap($out, $this->conf['wrap.'][$type]['SUMMARY_SHORT.']);
					$out = $this->cObj->wrap($out, $this->conf['wrap.'][$type]['SUMMARY_SHORT']);
				
					return $out;
					}
			break;
			case 'actor':
				$out = $this->internal["currentRow"]["actor"];
				$out = strip_tags($out);
				
				$fullName = explode(",", $out);
				
				foreach($fullName as $val){
					$parts= array();
					$parts = explode(" ", $val);
					foreach($parts as $namePart) {
						$correctedName[] = ucfirst(strtolower($namePart));	
					}
					$names[] = implode(" ", $correctedName);
					$correctedName = '';
				}
				$out = implode(", ", $names);
				
				if($out) {
					return $this->cObj->wrap($out, $this->conf['wrap.'][$type]['ACTOR']);;
				}
			break;
			case 'uid':
				return $this->internal["currentRow"]["uid"];
			break;
			default:
				return "neues Feld?->".$fN;
			break;
		}
	}


	/**
	 * [Put your description here]
	 */
	function getFieldHeader($fN)
		{
		switch($fN)
			{
			case "title":
				return $this->pi_getLL("listFieldHeader_title","<em>title</em>");
			break;
			default:
				return $this->pi_getLL("listFieldHeader_".$fN,"[".$fN."]");
			break;
			}
		}



	/**
	 * [Put your description here]
	 */
	function getFieldHeader_sortLink($fN)
		{
		return $this->pi_linkTP_keepPIvars($this->getFieldHeader($fN),array("sort"=>$fN.":".($this->internal["descFlag"]?0:1)));
		}



	function makeABC()
		{
		$out[latest] = $this->pi_getLL("abc_latest", "_abc_latest_");

		for ($i = 65; $i < 91; $i++)
			{
			$out[chr($i)] = chr($i);
			}

		$out[all] = $this->pi_getLL("abc_All","_abc_All_");

		return($out);
		}


	/**
	 * Returns a ABC selector; a little menu in a table normally put in the top of the page/list.
	 *
	 * @param	array		Key/Value pairs for the menu; keys are the piVars[mode] values and the "values" are the labels for them.
	 * @param	string		Attributes for the table tag which is wrapped around the table cells containing the menu
	 * @return	string		Output HTML, wrapped in <div>-tags with a class attribute
	 */
	function list_abcSelector($items=array(),$tableParams='')	
		{
		$cells=array();
		reset($items);
		while(list($k,$v)=each($items))	{
			$cells[]='
					<td'.($this->piVars['abc']==$k?$this->pi_classParam('abcSelector-SCell'):'').'><p>'.
				$this->pi_linkTP_keepPIvars(htmlspecialchars($v),array('abc'=>$k, 'sword'=>'', 'pointer'=>0),$this->pi_isOnlyFields($this->pi_isOnlyFields)).
				'</p></td>';
		}

		$sTables = '

		<!--
			ABC selector (menu for list):
		-->
		<div'.$this->pi_classParam('abcSelector').'>
			<'.trim('table '.$tableParams).'>
				<tr>
					'.implode('',$cells).'
				</tr>
			</table>
		</div>';

		return $sTables;
	}

		/**
		 * Returns Amazon Adwords
		 * List / Singlevie Separate
		 */
	function amazonAdwords($res = 0)
		{

			# Alle Titel zusammenstellen
		if($this->piVars['showUid'])
			{
			$title = rawurlencode($this->getFieldContent('title'));
			}
		else
			{
			while($erg = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
				{
				$titles[] = urlencode(trim($erg['title']));
				}
			}

		/* Selbst optomierend ... Naja.
		$titles = implode(";", (array)$titles);
		debug($titles);

		$out = '
		<script type="text/javascript">
		<!--
		  amazon_ad_tag = "kinisn-21";  amazon_ad_width = "160";  amazon_ad_height = "600";  amazon_ad_link_target = "new";  amazon_ad_include = "'.$titles.' ";  amazon_ad_categories = "c";//--></script>
		<script type="text/javascript" src="http://www.assoc-amazon.de/s/ads.js"></script>
				';
		*/

		# Einen Film aus der Liste ausw�hlen
		srand ((double)microtime()*1000000);
		$titles = $titles[rand(0,count($titles)-1)];

		$out = '<iframe ' .
			'src="http://rcm-de.amazon.de/e/cm?t=kinisn-21&o=3&p=11&l=st1&mode=dvd-de&search='.$titles.'&fc1=&lt1=&lc1=&bg1=&f=ifr"' .
			' marginwidth="0" marginheight="0" width="120" height="600" border="0" frameborder="0" style="border:none;" scrolling="no"></iframe>';

		return $out;
		}

			/**
		 * Flex Form Parameter werden ausgelesen und initialisiert
		 *
		 * @todo TYPOscript parameter ber�cksichtigen und eventuell �berschreiben
		 */
	function initFF()
		{
			# FF Parsen
		$this->pi_initPIflexForm();

			# Werte auslesen
		/*
		<s_DEF>
			<mode>
					<numIndex index="1">preLongView</numIndex>
					<numIndex index="1">prgShortView</numIndex>
					<numIndex index="1">prgLongView</numIndex>
					<numIndex index="1">singeView</numIndex>

		*/
		$this->ff['def']['mode']  = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'mode', 			  's_DEF');


		/*
		<s_amazon>
			<width>
			<rows>
			<colums>
			<clickEnlarge>
		*/
		$this->ff['amazon']['was']		= $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'colums', 		's_amazon');
		$this->ff['amazon']['immer'] 	= $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'clickEnlarge', 's_amazon');

		#debug($this->ff);
		}


	} # END of class



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/tmd_movie/pi1/class.tx_tmdmovie_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/tmd_movie/pi1/class.tx_tmdmovie_pi1.php"]);
}

?>