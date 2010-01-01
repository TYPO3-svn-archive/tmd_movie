#
# Table structure for table 'tx_tmdmovie_movie'
#
CREATE TABLE tx_tmdmovie_movie (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    starttime int(11) DEFAULT '0' NOT NULL,
    endtime int(11) DEFAULT '0' NOT NULL,
    fe_group int(11) DEFAULT '0' NOT NULL,
    artikel int(11) DEFAULT '0' NOT NULL,
    title tinytext NOT NULL,
    short tinytext NOT NULL,
    screenformat int(11) DEFAULT '0' NOT NULL,
    runningtime int(11) DEFAULT '0' NOT NULL,
    rating int(11) DEFAULT '0' NOT NULL,
    rating_nf tinyint(3) DEFAULT '0' NOT NULL,
    distributor int(11) DEFAULT '0' NOT NULL,
    releasedate int(11) DEFAULT '0' NOT NULL,
    web tinytext NOT NULL,
    sound int(11) DEFAULT '0' NOT NULL,
    summary text NOT NULL,
    poster blob NOT NULL,
    mediafile blob NOT NULL,
    fbw int(11) DEFAULT '0' NOT NULL,
    genre blob NOT NULL,
	3d tinyint(3) DEFAULT '0' NOT NULL,
    director text NOT NULL,
    producer text NOT NULL,
    actor text NOT NULL,
    originaltitle tinytext NOT NULL,
    productionyear int(11) DEFAULT '0' NOT NULL,
    country tinytext NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);



#
# Table structure for table 'tx_tmdmovie_rating'
#
CREATE TABLE tx_tmdmovie_rating (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    rating tinytext NOT NULL,
    tooltip tinytext NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);



#
# Table structure for table 'tx_tmdmovie_genre'
#
CREATE TABLE tx_tmdmovie_genre (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    genre tinytext NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);
