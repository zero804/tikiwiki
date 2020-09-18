CREATE TABLE IF NOT EXISTS `tiki_machine_learning_models` (
  `mlmId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` text NULL,
  `sourceTrackerId` int(11) NOT NULL,
  `trackerFields` text NULL,
  `payload` text NULL,
  PRIMARY KEY  (`mlmId`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
