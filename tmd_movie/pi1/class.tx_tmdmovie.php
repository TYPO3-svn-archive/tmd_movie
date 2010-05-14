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

class tx_tmdmovie extends tslib_pibase {

	var $rating;
	var $genre;
	var $web;
	var $youtube;
	var $screenformat;
	var $length;
	var $distibutor;
	var $sound;
	var $poster;
	var $media;
	var $fbw;
	var $titel;
	var $short;
	var $originaltitle;
	var $releasedate;
	var $summary;
	var $ratingTooltip;
	var $director;
	var $producer;
	var $actor;
	var $version3D;

/*
	function getMovieList($where, $group='', $sort='')
		{
		$res = $this->pi_exec_query(, 0, $where,$mm_cat='',$group, $sort);
		$res = $GLOBALS['TSFE']->exec_SELECTquery ('*', $from_table, $where_clause, $groupBy='', $orderBy='', $limit='')
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
			{
			$this->movieList[] = $row;
			};
		}
*/

	function getMovieById($id)
		{
		$this->movie = $this->pi_getRecord("tx_tmdmovie_movie", $id);

		// ID
		$this->id = $this->movie['uid'];
		
		// case "rating":
		$this->rating = $this->pi_getRecord("tx_tmdmovie_rating", $this->movie["rating"]);
		$this->ratingTooltip = $this->rating['tooltip'];
		$this->rating = $this->rating['rating'];

		// case "genre":
		$genre = explode(",", $this->movie["genre"]);
		foreach($genre as $id)
			{
			$temp = $this->pi_getRecord("tx_tmdmovie_genre", $id);
			$out[] = $temp['genre'];
			}
		$this->genre =	implode(", ", $out);


		
		//		case "web":
		if($this->movie["web"]) {
			$this->web = $this->movie['web'];
			}


		//		case "youtube":
		if($this->movie["youtube"]) {
			$this->youtube = $this->movie['youtube'];
			}
			
			
		// case "screenformat" :
			# 0 = BW 1= CS 2=N
		$this->screenformat = $this->movie["screenformat"];

		

		// case "length":
	 	$this->length = $this->movie["runningtime"];



		//case "distributor":
		$temp = $this->pi_getRecord("tt_address", $this->movie['distributor']);
		$this->distibutor = $temp['company'];

		// case "sound":
		$sound = array("DolbySR","DolbyDIGITAL","DTS","SDDS","Mono","Stereo","DolbyDIGITAL-EX","DTS-EX");
		$field = $this->movie["sound"];
		for($i = 0; $i < 8; $i++)
			if ($field & pow(2,$i))
				$res .= $sound[$i]." ";
		$this->sound = trim($res);



		//	case "poster":
		$this->poster = $this->movie['poster'];


		
		// case "mediafile":
		$this->media = $this->movie["mediafile"];


		//case "fbw":
		$res = $this->movie["fbw"];
		/*
		for($i = 0; $i < 2; $i++)
			if ($field & pow(2,$i))
				$res .= $this->conf['fbw.'][$i]." ";
		*/
		$this->fbw = trim($res);
		


		//case "title":
		$artikel = array('', 'Der', 'Die', 'Das', 'The', 'Lé');
		$this->titel = trim($artikel[$this->movie["artikel"]].' '.$this->movie["title"]);



		// case "releasedate":
		if($this->movie["releasedate"])
			$this->releasedate = strftime("%d.%m.%Y", $this->movie["releasedate"]);



		// case "summary":
		$this->summary = $this->movie["summary"];		



		// Director
		$this->director  = $this->movie["director"];


		
		// Producer
		$this->producer = $this->movie["producer"];


		
		// Cast
		$this->actor = $this->movie["actor"];



		// Original Titel
		$this->originaltitle = $this->movie["originaltitle"];



		// Original Titel
		$this->short = $this->movie["short"];

		// 3d Verion 
		$this->version3D = $this->movie["3d"];
		
				
		// Safe cleanup
		unset($this->movie);
		}


		function debug()
			{
			debug(
				array(
					"id" => $this->id,
					"titel" => $this->titel,
					"originaltitle" => $this->originaltitle,
					"shorttitle" => $this->shorttitle,
					"rating" => $this->rating,
					"ratingTooltip" => $this->ratingTooltip,
					"genre" => $this->genre,
					"web" => $this->web,
					"youtube" => $this->youtube,
					"screenformat" => $this->screenformat,
				 	"dauer" => $this->length,
					"distibutor" => $this->distibutor,
					"sound" => $this->sound,
					"poster" => $this->poster,
					"media" => $this->media,
					"fbw" => $this->fbw,
					"releasedate" => $this->releasedate,
					"inhalt" => $this->summary,
					"director" => $this->director,
					"producer" => $this->producer,
					"actor" => $this->actor,
					"3d" => $this->version3D,
					),
				"Film"
				);
			}	






	} # END of class



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/tmd_movie/pi1/class.tx_tmdmovie.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/tmd_movie/pi1/class.tx_tmdmovie.php"]);
}
?>