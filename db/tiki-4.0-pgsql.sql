-- --------------------------------------------------------
-- Database : tikiwiki
-- --------------------------------------------------------

DROP TABLE IF EXISTS "galaxia_activities";

CREATE TABLE "galaxia_activities" (
  "activityId" bigserial,
  "name" varchar(80) default NULL,
  "normalized_name" varchar(80) default NULL,
  "pId" bigint NOT NULL default '0',
  "type" enum('start','end','split','switch','join','activity','standalone') default NULL,
  "isAutoRouted" char(1) default NULL,
  "flowNum" bigint default NULL,
  "isInteractive" char(1) default NULL,
  "lastModif" bigint default NULL,
  "description" text,
  "expirationTime" integer NOT NULL default '0',
  PRIMARY KEY ("activityId")
) ;


DROP TABLE IF EXISTS "galaxia_activity_roles";

CREATE TABLE "galaxia_activity_roles" (
  "activityId" bigint NOT NULL default '0',
  "roleId" bigint NOT NULL default '0',
  PRIMARY KEY ("activityId","roleId")
);


DROP TABLE IF EXISTS "galaxia_instance_activities";

CREATE TABLE "galaxia_instance_activities" (
  "instanceId" bigint NOT NULL default '0',
  "activityId" bigint NOT NULL default '0',
  "started" bigint NOT NULL default '0',
  "ended" bigint NOT NULL default '0',
  "user" varchar(200) default '',
  "status" enum('running','completed') default NULL,
  PRIMARY KEY ("instanceId","activityId")
);


DROP TABLE IF EXISTS "galaxia_instance_comments";

CREATE TABLE "galaxia_instance_comments" (
  "cId" bigserial,
  "instanceId" bigint NOT NULL default '0',
  "user" varchar(200) default '',
  "activityId" bigint default NULL,
  "hash" varchar(34) default NULL,
  "title" varchar(250) default NULL,
  "comment" text,
  "activity" varchar(80) default NULL,
  "timestamp" bigint default NULL,
  PRIMARY KEY ("cId")
) ;


DROP TABLE IF EXISTS "galaxia_instances";

CREATE TABLE "galaxia_instances" (
  "instanceId" bigserial,
  "pId" bigint NOT NULL default '0',
  "started" bigint default NULL,
  "name" varchar(200) NOT NULL default 'No Name',
  "owner" varchar(200) default NULL,
  "nextActivity" bigint default NULL,
  "nextUser" varchar(200) default NULL,
  "ended" bigint default NULL,
  "status" enum('active','exception','aborted','completed') default NULL,
  "properties" bytea,
  PRIMARY KEY ("instanceId")
) ;


DROP TABLE IF EXISTS "galaxia_processes";

CREATE TABLE "galaxia_processes" (
  "pId" bigserial,
  "name" varchar(80) default NULL,
  "isValid" char(1) default NULL,
  "isActive" char(1) default NULL,
  "version" varchar(12) default NULL,
  "description" text,
  "lastModif" bigint default NULL,
  "normalized_name" varchar(80) default NULL,
  PRIMARY KEY ("pId")
) ;


DROP TABLE IF EXISTS "galaxia_roles";

CREATE TABLE "galaxia_roles" (
  "roleId" bigserial,
  "pId" bigint NOT NULL default '0',
  "lastModif" bigint default NULL,
  "name" varchar(80) default NULL,
  "description" text,
  PRIMARY KEY ("roleId")
) ;


DROP TABLE IF EXISTS "galaxia_transitions";

CREATE TABLE "galaxia_transitions" (
  "pId" bigint NOT NULL default '0',
  "actFromId" bigint NOT NULL default '0',
  "actToId" bigint NOT NULL default '0',
  PRIMARY KEY ("actFromId","actToId")
);


DROP TABLE IF EXISTS "galaxia_user_roles";

CREATE TABLE "galaxia_user_roles" (
  "pId" bigint NOT NULL default '0',
  "roleId" bigserial,
  "user" varchar(200) NOT NULL default '',
  PRIMARY KEY ("roleId", "user")
) ;


DROP TABLE IF EXISTS "galaxia_workitems";

CREATE TABLE "galaxia_workitems" (
  "itemId" bigserial,
  "instanceId" bigint NOT NULL default '0',
  "orderId" bigint NOT NULL default '0',
  "activityId" bigint NOT NULL default '0',
  "properties" bytea,
  "started" bigint default NULL,
  "ended" bigint default NULL,
  "user" varchar(200) default '',
  PRIMARY KEY ("itemId")
) ;


DROP TABLE IF EXISTS "messu_messages";

CREATE TABLE "messu_messages" (
  "msgId" bigserial,
  "user" varchar(200) NOT NULL default '',
  "user_from" varchar(200) NOT NULL default '',
  "user_to" text,
  "user_cc" text,
  "user_bcc" text,
  "subject" varchar(255) default NULL,
  "body" text,
  "hash" varchar(32) default NULL,
  "replyto_hash" varchar(32) default NULL,
  "date" bigint default NULL,
  "isRead" char(1) default NULL,
  "isReplied" char(1) default NULL,
  "isFlagged" char(1) default NULL,
  "priority" smallint default NULL,
  PRIMARY KEY ("msgId")
) ;

CREATE INDEX "_userIsRead" ON ""("user","isRead");

DROP TABLE IF EXISTS "messu_archive";

CREATE TABLE "messu_archive" (
  "msgId" bigserial,
  "user" varchar(40) NOT NULL default '',
  "user_from" varchar(40) NOT NULL default '',
  "user_to" text,
  "user_cc" text,
  "user_bcc" text,
  "subject" varchar(255) default NULL,
  "body" text,
  "hash" varchar(32) default NULL,
  "replyto_hash" varchar(32) default NULL,
  "date" bigint default NULL,
  "isRead" char(1) default NULL,
  "isReplied" char(1) default NULL,
  "isFlagged" char(1) default NULL,
  "priority" smallint default NULL,
  PRIMARY KEY ("msgId")
) ;


DROP TABLE IF EXISTS "messu_sent";

CREATE TABLE "messu_sent" (
  "msgId" bigserial,
  "user" varchar(40) NOT NULL default '',
  "user_from" varchar(40) NOT NULL default '',
  "user_to" text,
  "user_cc" text,
  "user_bcc" text,
  "subject" varchar(255) default NULL,
  "body" text,
  "hash" varchar(32) default NULL,
  "replyto_hash" varchar(32) default NULL,
  "date" bigint default NULL,
  "isRead" char(1) default NULL,
  "isReplied" char(1) default NULL,
  "isFlagged" char(1) default NULL,
  "priority" smallint default NULL,
  PRIMARY KEY ("msgId")
) ;


DROP TABLE IF EXISTS "sessions";

CREATE TABLE sessions(
  "sesskey" char(32) NOT NULL,
  "expiry" bigint NOT NULL,
  "expireref" varchar(64),
  "data" text NOT NULL,
  PRIMARY KEY ("sesskey")
);

CREATE INDEX "_expiry" ON ""(\","expiry"\");

DROP TABLE IF EXISTS "tiki_actionlog";

CREATE TABLE "tiki_actionlog" (
  "actionId" serial,
  "action" varchar(255) NOT NULL default '',
  "lastModif" bigint default NULL,
  "object" varchar(255) default NULL,
  "objectType" varchar(32) NOT NULL default '',
  "user" varchar(200) default '',
  "ip" varchar(15) default NULL,
  "comment" varchar(200) default NULL,
  "categId" bigint NOT NULL default '0',
  PRIMARY KEY ("actionId")
);

CREATE INDEX "_lastModif" ON ""(\","lastModif"\");
CREATE INDEX "_object" ON ""(\","object"\substr(", 0, 100)\","objectType"\"\","action"\substr(", 0, 100));

DROP TABLE IF EXISTS "tiki_actionlog_params";

CREATE TABLE "tiki_actionlog_params" (
  "actionId" integer NOT NULL,
  "name" varchar(40) NOT NULL,
  "value" text
);

CREATE INDEX "_actionIDIndex" ON ""(\","actionId"\");
CREATE INDEX "_nameValue" ON ""(\","name"\"\","value"\substr(", 0, 200));

DROP TABLE IF EXISTS "tiki_articles";

CREATE TABLE "tiki_articles" (
  "articleId" serial,
  "topline" varchar(255) default NULL,
  "title" varchar(255) default NULL,
  "subtitle" varchar(255) default NULL,
  "linkto" varchar(255) default NULL,
  "lang" varchar(16) default NULL,
  "state" char(1) default 's',
  "authorName" varchar(60) default NULL,
  "topicId" bigint default NULL,
  "topicName" varchar(40) default NULL,
  "size" bigint default NULL,
  "useImage" char(1) default NULL,
  "image_name" varchar(80) default NULL,
  "image_caption" text default NULL,
  "image_type" varchar(80) default NULL,
  "image_size" bigint default NULL,
  "image_x" smallint default NULL,
  "image_y" smallint default NULL,
  "image_data" bytea,
  "publishDate" bigint default NULL,
  "expireDate" bigint default NULL,
  "created" bigint default NULL,
  "heading" text,
  "body" text,
  "hash" varchar(32) default NULL,
  "author" varchar(200) default NULL,
  "nbreads" bigint default NULL,
  "votes" integer default NULL,
  "points" bigint default NULL,
  "type" varchar(50) default NULL,
  "rating" decimal(3,2) default NULL,
  "isfloat" char(1) default NULL,
  PRIMARY KEY ("articleId")
) ;

CREATE INDEX "_title" ON ""(\","title"\");
CREATE INDEX "_heading" ON ""(\","heading"\substr(", 0, 255));
CREATE INDEX "_body" ON ""(\","body"\substr(", 0, 255));
CREATE INDEX "_nbreads" ON ""(\","nbreads"\");
CREATE INDEX "_author" ON ""(\","author"\substr(", 0, 32));
CREATE INDEX "_topicId" ON ""(\","topicId"\");
CREATE INDEX "_publishDate" ON ""(\","publishDate"\");
CREATE INDEX "_expireDate" ON ""(\","expireDate"\");
CREATE INDEX "_type" ON ""(\","type"\");

DROP TABLE IF EXISTS "tiki_article_types";

CREATE TABLE "tiki_article_types" (
  "type" varchar(50) NOT NULL,
  "use_ratings" varchar(1) default NULL,
  "show_pre_publ" varchar(1) default NULL,
  "show_post_expire" varchar(1) default 'y',
  "heading_only" varchar(1) default NULL,
  "allow_comments" varchar(1) default 'y',
  "show_image" varchar(1) default 'y',
  "show_avatar" varchar(1) default NULL,
  "show_author" varchar(1) default 'y',
  "show_pubdate" varchar(1) default 'y',
  "show_expdate" varchar(1) default NULL,
  "show_reads" varchar(1) default 'y',
  "show_size" varchar(1) default 'n',
  "show_topline" varchar(1) default 'n',
  "show_subtitle" varchar(1) default 'n',
  "show_linkto" varchar(1) default 'n',
  "show_image_caption" varchar(1) default 'n',
  "show_lang" varchar(1) default 'n',
  "creator_edit" varchar(1) default NULL,
  "comment_can_rate_article" char(1) default NULL,
  PRIMARY KEY ("type")
) ;

CREATE INDEX "_show_pre_publ" ON ""(\","show_pre_publ"\");
CREATE INDEX "_show_post_expire" ON ""(\","show_post_expire"\");

INSERT INTO "tiki_article_types" ("type") VALUES ('Article');

INSERT INTO "tiki_article_types" ("type","use_ratings") VALUES ('Review','y');

INSERT INTO "tiki_article_types" ("type","show_post_expire") VALUES ('Event','n');

INSERT INTO "tiki_article_types" ("type","show_post_expire","heading_only","allow_comments") VALUES ('Classified','n','y','n');


DROP TABLE IF EXISTS "tiki_banners";

CREATE TABLE "tiki_banners" (
  "bannerId" bigserial,
  "client" varchar(200) NOT NULL default '',
  "url" varchar(255) default NULL,
  "title" varchar(255) default NULL,
  "alt" varchar(250) default NULL,
  "which" varchar(50) default NULL,
  "imageData" bytea,
  "imageType" varchar(200) default NULL,
  "imageName" varchar(100) default NULL,
  "HTMLData" text,
  "fixedURLData" varchar(255) default NULL,
  "textData" text,
  "fromDate" bigint default NULL,
  "toDate" bigint default NULL,
  "useDates" char(1) default NULL,
  "mon" char(1) default NULL,
  "tue" char(1) default NULL,
  "wed" char(1) default NULL,
  "thu" char(1) default NULL,
  "fri" char(1) default NULL,
  "sat" char(1) default NULL,
  "sun" char(1) default NULL,
  "hourFrom" varchar(4) default NULL,
  "hourTo" varchar(4) default NULL,
  "created" bigint default NULL,
  "maxImpressions" integer default NULL,
  "impressions" integer default NULL,
  "maxUserImpressions" integer default -1,
  "maxClicks" integer default NULL,
  "clicks" integer default NULL,
  "zone" varchar(40) default NULL,
  PRIMARY KEY ("bannerId"),
  INDEX ban1(zone,useDates,impressions,maxImpressions,hourFrom,hourTo,fromDate,toDate,mon,tue,wed,thu,fri,sat,sun)
) ;


DROP TABLE IF EXISTS "tiki_banning";

CREATE TABLE "tiki_banning" (
  "banId" bigserial,
  "mode" enum('user','ip') default NULL,
  "title" varchar(200) default NULL,
  "ip1" char(3) default NULL,
  "ip2" char(3) default NULL,
  "ip3" char(3) default NULL,
  "ip4" char(3) default NULL,
  "user" varchar(200) default '',
  "date_from" timestamp(3) NOT NULL,
  "date_to" timestamp(3) NOT NULL,
  "use_dates" char(1) default NULL,
  "created" bigint default NULL,
  "message" text,
  PRIMARY KEY ("banId")
) ;


DROP TABLE IF EXISTS "tiki_banning_sections";

CREATE TABLE "tiki_banning_sections" (
  "banId" bigint NOT NULL default '0',
  "section" varchar(100) NOT NULL default '',
  PRIMARY KEY ("banId","section")
);


DROP TABLE IF EXISTS "tiki_blog_activity";

CREATE TABLE "tiki_blog_activity" (
  "blogId" integer NOT NULL default '0',
  "day" bigint NOT NULL default '0',
  "posts" integer default NULL,
  PRIMARY KEY ("blogId","day")
);


DROP TABLE IF EXISTS "tiki_blog_posts";

CREATE TABLE "tiki_blog_posts" (
  "postId" serial,
  "blogId" integer NOT NULL default '0',
  "data" text,
  "data_size" bigint NOT NULL default '0',
  "created" bigint default NULL,
  "user" varchar(200) default '',
  "trackbacks_to" text,
  "trackbacks_from" text,
  "title" varchar(255) default NULL,
  "priv" varchar(1) default NULL,
  PRIMARY KEY ("postId")
) ;

CREATE INDEX "_data" ON ""(\","data"\substr(", 0, 255));
CREATE INDEX "_blogId" ON ""(\","blogId"\");
CREATE INDEX "_created" ON ""(\","created"\");

DROP TABLE IF EXISTS "tiki_blog_posts_images";

CREATE TABLE "tiki_blog_posts_images" (
  "imgId" bigserial,
  "postId" bigint NOT NULL default '0',
  "filename" varchar(80) default NULL,
  "filetype" varchar(80) default NULL,
  "filesize" bigint default NULL,
  "data" bytea,
  PRIMARY KEY ("imgId")
) ;


DROP TABLE IF EXISTS "tiki_blogs";

CREATE TABLE "tiki_blogs" (
  "blogId" serial,
  "created" bigint default NULL,
  "lastModif" bigint default NULL,
  "title" varchar(200) default NULL,
  "description" text,
  "user" varchar(200) default '',
  "public" char(1) default NULL,
  "posts" integer default NULL,
  "maxPosts" integer default NULL,
  "hits" integer default NULL,
  "activity" decimal(4,2) default NULL,
  "heading" text,
  "use_find" char(1) default NULL,
  "use_title" char(1) default NULL,
  "add_date" char(1) default NULL,
  "add_poster" char(1) default NULL,
  "allow_comments" char(1) default NULL,
  "show_avatar" char(1) default NULL,
  PRIMARY KEY ("blogId")
) ;

CREATE INDEX "_title" ON ""(\","title"\");
CREATE INDEX "_description" ON ""(\","description"\substr(", 0, 255));
CREATE INDEX "_hits" ON ""(\","hits"\");

DROP TABLE IF EXISTS "tiki_calendar_categories";

CREATE TABLE "tiki_calendar_categories" (
  "calcatId" bigserial,
  "calendarId" bigint NOT NULL default '0',
  "name" varchar(255) NOT NULL default '',
  PRIMARY KEY ("calcatId"),
  UNIQUE ("calendarId", "name")
) ;


DROP TABLE IF EXISTS "tiki_calendar_recurrence";

CREATE TABLE "tiki_calendar_recurrence" (
  "recurrenceId" bigserial,
  "calendarId" bigint NOT NULL default '0',
  "start" smallint NOT NULL default '0',
  "end" smallint NOT NULL default '2359',
  "allday" smallint NOT NULL default '0',
  "locationId" bigint default NULL,
  "categoryId" bigint default NULL,
  "nlId" bigint NOT NULL default '0',
  "priority" enum('1','2','3','4','5','6','7','8','9') NOT NULL default '1',
  "status" enum('0','1','2') NOT NULL default '0',
  "url" varchar(255) default NULL,
  "lang" char(16) NOT NULL default 'en',
  "name" varchar(255) NOT NULL default '',
  "description" bytea,
  "weekly" smallint default '0',
  "weekday" smallint,
  "monthly" smallint default '0',
  "dayOfMonth" smallint,
  "yearly" smallint default '0',
  "dateOfYear" smallint,
  "nbRecurrences" integer,
  "startPeriod" bigint,
  "endPeriod" bigint,
  "user" varchar(200) default '',
  "created" bigint NOT NULL default '0',
  "lastmodif" bigint NOT NULL default '0',
  PRIMARY KEY ("recurrenceId")
) ;

CREATE INDEX "_calendarId" ON ""(\","calendarId"\");

DROP TABLE IF EXISTS "tiki_calendar_items";

CREATE TABLE "tiki_calendar_items" (
  "calitemId" bigserial,
  "calendarId" bigint NOT NULL default '0',
  "start" bigint NOT NULL default '0',
  "end" bigint NOT NULL default '0',
  "locationId" bigint default NULL,
  "categoryId" bigint default NULL,
  "nlId" bigint NOT NULL default '0',
  "priority" enum('0', '1','2','3','4','5','6','7','8','9') default '0',
  "status" enum('0','1','2') NOT NULL default '0',
  "url" varchar(255) default NULL,
  "lang" char(16) NOT NULL default 'en',
  "name" varchar(255) NOT NULL default '',
  "description" text,
  "recurrenceId" bigint,
  "changed" smallint DEFAULT '0',
  "user" varchar(200) default '',
  "created" bigint NOT NULL default '0',
  "lastmodif" bigint NOT NULL default '0',
  "allday" smallint NOT NULL default '0',
  PRIMARY KEY ("calitemId"),
  CONSTRAINT "fk_calitems_recurrence"
	FOREIGN KEY ("recurrenceId") REFERENCES "tiki_calendar_recurrence"("recurrenceId")
	ON UPDATE CASCADE ON DELETE SET NULL
) ;

CREATE INDEX "_calendarId" ON ""(\","calendarId"\");

DROP TABLE IF EXISTS "tiki_calendar_locations";

CREATE TABLE "tiki_calendar_locations" (
  "callocId" bigserial,
  "calendarId" bigint NOT NULL default '0',
  "name" varchar(255) NOT NULL default '',
  "description" bytea,
  PRIMARY KEY ("callocId"),
  UNIQUE ("calendarId", "name")
) ;


DROP TABLE IF EXISTS "tiki_calendar_roles";

CREATE TABLE "tiki_calendar_roles" (
  "calitemId" bigint NOT NULL default '0',
  "username" varchar(200) NOT NULL default '',
  "role" enum('0','1','2','3','6') NOT NULL default '0',
  PRIMARY KEY ("calitemId","username","role")
);


DROP TABLE IF EXISTS "tiki_calendars";

CREATE TABLE "tiki_calendars" (
  "calendarId" bigserial,
  "name" varchar(80) NOT NULL default '',
  "description" varchar(255) default NULL,
  "user" varchar(200) NOT NULL default '',
  "customlocations" enum('n','y') NOT NULL default 'n',
  "customcategories" enum('n','y') NOT NULL default 'n',
  "customlanguages" enum('n','y') NOT NULL default 'n',
  "custompriorities" enum('n','y') NOT NULL default 'n',
  "customparticipants" enum('n','y') NOT NULL default 'n',
  "customsubscription" enum('n','y') NOT NULL default 'n',
  "customstatus" enum('n','y') NOT NULL default 'y',
  "created" bigint NOT NULL default '0',
  "lastmodif" bigint NOT NULL default '0',
  "personal" enum ('n', 'y') NOT NULL default 'n',
  PRIMARY KEY ("calendarId")
) ;


DROP TABLE IF EXISTS "tiki_calendar_options";

CREATE TABLE "tiki_calendar_options" (
	calendarId bigint NOT NULL default 0,
	optionName varchar(120) NOT NULL default '',
	value varchar(255),
	PRIMARY KEY ("calendarId","optionName")
) ;


DROP TABLE IF EXISTS "tiki_categories";

CREATE TABLE "tiki_categories" (
  "categId" bigserial,
  "name" varchar(100) default NULL,
  "description" varchar(250) default NULL,
  "parentId" bigint default NULL,
  "hits" integer default NULL,
  PRIMARY KEY ("categId")
) ;


DROP TABLE IF EXISTS "tiki_objects";

CREATE TABLE "tiki_objects" (
  "objectId" bigserial,
  "type" varchar(50) default NULL,
  "itemId" varchar(255) default NULL,
  "description" text,
  "created" bigint default NULL,
  "name" varchar(200) default NULL,
  "href" varchar(200) default NULL,
  "hits" integer default NULL,
  "comments_locked" char(1) NOT NULL default 'n',
  PRIMARY KEY ("objectId")
) ;

CREATE INDEX "_" ON ""(\","type"\"\","objectId"\");
CREATE INDEX "_" ON ""(\","itemId"\"\","type"\");

DROP TABLE IF EXISTS "tiki_categorized_objects";

CREATE TABLE "tiki_categorized_objects" (
  "catObjectId" bigint NOT NULL default '0',
  PRIMARY KEY ("catObjectId")
) ;


DROP TABLE IF EXISTS "tiki_category_objects";

CREATE TABLE "tiki_category_objects" (
  "catObjectId" bigint NOT NULL default '0',
  "categId" bigint NOT NULL default '0',
  PRIMARY KEY ("catObjectId","categId")
);


DROP TABLE IF EXISTS "tiki_object_ratings";

CREATE TABLE "tiki_object_ratings" (
  "catObjectId" bigint NOT NULL default '0',
  "pollId" bigint NOT NULL default '0',
  PRIMARY KEY ("catObjectId","pollId")
);


DROP TABLE IF EXISTS "tiki_category_sites";

CREATE TABLE "tiki_category_sites" (
  "categId" bigint NOT NULL default '0',
  "siteId" bigint NOT NULL default '0',
  PRIMARY KEY ("categId","siteId")
);


DROP TABLE IF EXISTS "tiki_chat_channels";

CREATE TABLE "tiki_chat_channels" (
  "channelId" serial,
  "name" varchar(30) default NULL,
  "description" varchar(250) default NULL,
  "max_users" integer default NULL,
  "mode" char(1) default NULL,
  "moderator" varchar(200) default NULL,
  "active" char(1) default NULL,
  "refresh" integer default NULL,
  PRIMARY KEY ("channelId")
) ;


DROP TABLE IF EXISTS "tiki_chat_messages";

CREATE TABLE "tiki_chat_messages" (
  "messageId" serial,
  "channelId" integer NOT NULL default '0',
  "data" varchar(255) default NULL,
  "poster" varchar(200) NOT NULL default 'anonymous',
  "timestamp" bigint default NULL,
  PRIMARY KEY ("messageId")
) ;


DROP TABLE IF EXISTS "tiki_chat_users";

CREATE TABLE "tiki_chat_users" (
  "nickname" varchar(200) NOT NULL default '',
  "channelId" integer NOT NULL default '0',
  "timestamp" bigint default NULL,
  PRIMARY KEY ("nickname","channelId")
);


DROP TABLE IF EXISTS "tiki_comments";

CREATE TABLE "tiki_comments" (
  "threadId" bigserial,
  "object" varchar(255) NOT NULL default '',
  "objectType" varchar(32) NOT NULL default '',
  "parentId" bigint default NULL,
  "userName" varchar(200) default '',
  "commentDate" bigint default NULL,
  "hits" integer default NULL,
  "type" char(1) default NULL,
  "points" decimal(8,2) default NULL,
  "votes" integer default NULL,
  "average" decimal(8,4) default NULL,
  "title" varchar(255) default NULL,
  "data" text,
  "hash" varchar(32) default NULL,
  "user_ip" varchar(15) default NULL,
  "summary" varchar(240) default NULL,
  "smiley" varchar(80) default NULL,
  "message_id" varchar(128) default NULL,
  "in_reply_to" varchar(128) default NULL,
  "comment_rating" smallint default NULL,
  "archived" char(1) default NULL,
  "approved" char(1) NOT NULL default 'y',
  "locked" char(1) NOT NULL default 'n',
  PRIMARY KEY ("threadId"),
  UNIQUE (parentId, userName, title, commentDate, message_id, in_reply_to)
) ;

CREATE INDEX "_title" ON ""("title");
CREATE INDEX "_data" ON ""(substr("data", 0, 255));
CREATE INDEX "_hits" ON ""("hits");
CREATE INDEX "_tc_pi" ON ""("parentId");
CREATE INDEX "_objectType" ON ""("object","objectType");
CREATE INDEX "_commentDate" ON ""("commentDate");
CREATE INDEX "_threaded" ON ""("message_id","in_reply_to","parentId");

DROP TABLE IF EXISTS "tiki_content";

CREATE TABLE "tiki_content" (
  "contentId" serial,
  "description" text,
  "contentLabel" varchar(255) NOT NULL default '',
  PRIMARY KEY ("contentId")
) ;


DROP TABLE IF EXISTS "tiki_content_templates";

CREATE TABLE "tiki_content_templates" (
  "templateId" bigserial,
  "content" bytea,
  "name" varchar(200) default NULL,
  "created" bigint default NULL,
  PRIMARY KEY ("templateId")
) ;


DROP TABLE IF EXISTS "tiki_content_templates_sections";

CREATE TABLE "tiki_content_templates_sections" (
  "templateId" bigint NOT NULL default '0',
  "section" varchar(250) NOT NULL default '',
  PRIMARY KEY ("templateId","section")
);


DROP TABLE IF EXISTS "tiki_cookies";

CREATE TABLE "tiki_cookies" (
  "cookieId" bigserial,
  "cookie" text,
  PRIMARY KEY ("cookieId")
) ;


DROP TABLE IF EXISTS "tiki_copyrights";

CREATE TABLE "tiki_copyrights" (
  "copyrightId" bigserial,
  "page" varchar(200) default NULL,
  "title" varchar(200) default NULL,
  "year" bigint default NULL,
  "authors" varchar(200) default NULL,
  "copyright_order" bigint default NULL,
  "userName" varchar(200) default '',
  PRIMARY KEY ("copyrightId")
) ;


DROP TABLE IF EXISTS "tiki_directory_categories";

CREATE TABLE "tiki_directory_categories" (
  "categId" bigserial,
  "parent" bigint default NULL,
  "name" varchar(240) default NULL,
  "description" text,
  "childrenType" char(1) default NULL,
  "sites" bigint default NULL,
  "viewableChildren" smallint default NULL,
  "allowSites" char(1) default NULL,
  "showCount" char(1) default NULL,
  "editorGroup" varchar(200) default NULL,
  "hits" bigint default NULL,
  PRIMARY KEY ("categId")
) ;


DROP TABLE IF EXISTS "tiki_directory_search";

CREATE TABLE "tiki_directory_search" (
  "term" varchar(250) NOT NULL default '',
  "hits" bigint default NULL,
  PRIMARY KEY ("term")
);


DROP TABLE IF EXISTS "tiki_directory_sites";

CREATE TABLE "tiki_directory_sites" (
  "siteId" bigserial,
  "name" varchar(240) default NULL,
  "description" text,
  "url" varchar(255) default NULL,
  "country" varchar(255) default NULL,
  "hits" bigint default NULL,
  "isValid" char(1) default NULL,
  "created" bigint default NULL,
  "lastModif" bigint default NULL,
  "cache" bytea,
  "cache_timestamp" bigint default NULL,
  PRIMARY KEY ("siteId")
) ;

CREATE INDEX "_" ON ""("isValid");
CREATE INDEX "_" ON ""("url");

DROP TABLE IF EXISTS "tiki_dsn";

CREATE TABLE "tiki_dsn" (
  "dsnId" bigserial,
  "name" varchar(200) NOT NULL default '',
  "dsn" varchar(255) default NULL,
  PRIMARY KEY ("dsnId")
) ;


DROP TABLE IF EXISTS "tiki_dynamic_variables";

CREATE TABLE "tiki_dynamic_variables" (
  "name" varchar(40) NOT NULL,
  "data" text,
  PRIMARY KEY ("name")
);


DROP TABLE IF EXISTS "tiki_extwiki";

CREATE TABLE "tiki_extwiki" (
  "extwikiId" bigserial,
  "name" varchar(200) NOT NULL default '',
  "extwiki" varchar(255) default NULL,
  PRIMARY KEY ("extwikiId")
) ;


DROP TABLE IF EXISTS "tiki_faq_questions";

CREATE TABLE "tiki_faq_questions" (
  "questionId" bigserial,
  "faqId" bigint default NULL,
  "position" smallint default NULL,
  "question" text,
  "answer" text,
  PRIMARY KEY ("questionId")
) ;

CREATE INDEX "_faqId" ON ""("faqId");
CREATE INDEX "_question" ON ""(substr("question", 0, 255));
CREATE INDEX "_answer" ON ""(substr("answer", 0, 255));

DROP TABLE IF EXISTS "tiki_faqs";

CREATE TABLE "tiki_faqs" (
  "faqId" bigserial,
  "title" varchar(200) default NULL,
  "description" text,
  "created" bigint default NULL,
  "questions" integer default NULL,
  "hits" integer default NULL,
  "canSuggest" char(1) default NULL,
  PRIMARY KEY ("faqId")
) ;

CREATE INDEX "_title" ON ""("title");
CREATE INDEX "_description" ON ""(substr("description", 0, 255));
CREATE INDEX "_hits" ON ""("hits");

DROP TABLE IF EXISTS "tiki_featured_links";

CREATE TABLE "tiki_featured_links" (
  "url" varchar(200) NOT NULL default '',
  "title" varchar(200) default NULL,
  "description" text,
  "hits" integer default NULL,
  "position" integer default NULL,
  "type" char(1) default NULL,
  PRIMARY KEY ("url")
);


DROP TABLE IF EXISTS "tiki_file_galleries";

CREATE TABLE "tiki_file_galleries" (
  "galleryId" bigserial,
  "name" varchar(80) NOT NULL default '',
  "type" varchar(20) NOT NULL default 'default',
  "description" text,
  "created" bigint default NULL,
  "visible" char(1) default NULL,
  "lastModif" bigint default NULL,
  "user" varchar(200) default '',
  "hits" bigint default NULL,
  "votes" integer default NULL,
  "points" decimal(8,2) default NULL,
  "maxRows" bigint default NULL,
  "public" char(1) default NULL,
  "show_id" char(1) default NULL,
  "show_icon" char(1) default NULL,
  "show_name" char(1) default NULL,
  "show_size" char(1) default NULL,
  "show_description" char(1) default NULL,
  "max_desc" integer default NULL,
  "show_created" char(1) default NULL,
  "show_hits" char(1) default NULL,
  "parentId" bigint NOT NULL default -1,
  "lockable" char(1) default 'n',
  "show_lockedby" char(1) default NULL,
  "archives" smallint default -1,
  "sort_mode" char(20) default NULL,
  "show_modified" char(1) default NULL,
  "show_author" char(1) default NULL,
  "show_creator" char(1) default NULL,
  "subgal_conf" varchar(200) default NULL,
  "show_last_user" char(1) default NULL,
  "show_comment" char(1) default NULL,
  "show_files" char(1) default NULL,
  "show_explorer" char(1) default NULL,
  "show_path" char(1) default NULL,
  "show_slideshow" char(1) default NULL,
  "default_view" varchar(20) default NULL,
  PRIMARY KEY ("galleryId")
) ;


DROP TABLE IF EXISTS "tiki_files";

CREATE TABLE "tiki_files" (
  "fileId" bigserial,
  "galleryId" bigint NOT NULL default '0',
  "name" varchar(200) NOT NULL default '',
  "description" text,
  "created" bigint default NULL,
  "filename" varchar(80) default NULL,
  "filesize" bigint default NULL,
  "filetype" varchar(250) default NULL,
  "data" bytea,
  "user" varchar(200) default '',
  "author" varchar(40) default NULL,
  "hits" bigint default NULL,
  "votes" integer default NULL,
  "points" decimal(8,2) default NULL,
  "path" varchar(255) default NULL,
  "reference_url" varchar(250) default NULL,
  "is_reference" char(1) default NULL,
  "hash" varchar(32) default NULL,
  "search_data" text,
  "lastModif" bigint DEFAULT NULL,
  "lastModifUser" varchar(200) DEFAULT NULL,
  "lockedby" varchar(200) default '',
  "comment" varchar(200) default NULL,
  "archiveId" bigint default 0,
  PRIMARY KEY ("fileId")
) ;

CREATE INDEX "_name" ON ""("name");
CREATE INDEX "_description" ON ""(substr("description", 0, 255));
CREATE INDEX "_created" ON ""("created");
CREATE INDEX "_archiveId" ON ""("archiveId");
CREATE INDEX "_galleryId" ON ""("galleryId");
CREATE INDEX "_hits" ON ""("hits");

DROP TABLE IF EXISTS "tiki_forum_attachments";

CREATE TABLE "tiki_forum_attachments" (
  "attId" bigserial,
  "threadId" bigint NOT NULL default '0',
  "qId" bigint NOT NULL default '0',
  "forumId" bigint default NULL,
  "filename" varchar(250) default NULL,
  "filetype" varchar(250) default NULL,
  "filesize" bigint default NULL,
  "data" bytea,
  "dir" varchar(200) default NULL,
  "created" bigint default NULL,
  "path" varchar(250) default NULL,
  PRIMARY KEY ("attId")
) ;

CREATE INDEX "_threadId" ON ""("threadId");

DROP TABLE IF EXISTS "tiki_forum_reads";

CREATE TABLE "tiki_forum_reads" (
  "user" varchar(200) NOT NULL default '',
  "threadId" bigint NOT NULL default '0',
  "forumId" bigint default NULL,
  "timestamp" bigint default NULL,
  PRIMARY KEY ("user","threadId")
);


DROP TABLE IF EXISTS "tiki_forums";

CREATE TABLE "tiki_forums" (
  "forumId" serial,
  "name" varchar(255) default NULL,
  "description" text,
  "created" bigint default NULL,
  "lastPost" bigint default NULL,
  "threads" integer default NULL,
  "comments" integer default NULL,
  "controlFlood" char(1) default NULL,
  "floodInterval" integer default NULL,
  "moderator" varchar(200) default NULL,
  "hits" integer default NULL,
  "mail" varchar(200) default NULL,
  "useMail" char(1) default NULL,
  "section" varchar(200) default NULL,
  "usePruneUnreplied" char(1) default NULL,
  "pruneUnrepliedAge" integer default NULL,
  "usePruneOld" char(1) default NULL,
  "pruneMaxAge" integer default NULL,
  "topicsPerPage" integer default NULL,
  "topicOrdering" varchar(100) default NULL,
  "threadOrdering" varchar(100) default NULL,
  "att" varchar(80) default NULL,
  "att_store" varchar(4) default NULL,
  "att_store_dir" varchar(250) default NULL,
  "att_max_size" bigint default NULL,
  "ui_level" char(1) default NULL,
  "forum_password" varchar(32) default NULL,
  "forum_use_password" char(1) default NULL,
  "moderator_group" varchar(200) default NULL,
  "approval_type" varchar(20) default NULL,
  "outbound_address" varchar(250) default NULL,
  "outbound_mails_for_inbound_mails" char(1) default NULL,
  "outbound_mails_reply_link" char(1) default NULL,
  "outbound_from" varchar(250) default NULL,
  "inbound_pop_server" varchar(250) default NULL,
  "inbound_pop_port" smallint default NULL,
  "inbound_pop_user" varchar(200) default NULL,
  "inbound_pop_password" varchar(80) default NULL,
  "topic_smileys" char(1) default NULL,
  "ui_avatar" char(1) default NULL,
  "ui_flag" char(1) default NULL,
  "ui_posts" char(1) default NULL,
  "ui_email" char(1) default NULL,
  "ui_online" char(1) default NULL,
  "topic_summary" char(1) default NULL,
  "show_description" char(1) default NULL,
  "topics_list_replies" char(1) default NULL,
  "topics_list_reads" char(1) default NULL,
  "topics_list_pts" char(1) default NULL,
  "topics_list_lastpost" char(1) default NULL,
  "topics_list_author" char(1) default NULL,
  "vote_threads" char(1) default NULL,
  "forum_last_n" smallint default 0,
  "threadStyle" varchar(100) default NULL,
  "commentsPerPage" varchar(100) default NULL,
  "is_flat" char(1) default NULL,
  "mandatory_contribution" char(1) default NULL,
  PRIMARY KEY ("forumId")
) ;


DROP TABLE IF EXISTS "tiki_forums_queue";

CREATE TABLE "tiki_forums_queue" (
  "qId" bigserial,
  "object" varchar(32) default NULL,
  "parentId" bigint default NULL,
  "forumId" bigint default NULL,
  "timestamp" bigint default NULL,
  "user" varchar(200) default '',
  "title" varchar(240) default NULL,
  "data" text,
  "type" varchar(60) default NULL,
  "hash" varchar(32) default NULL,
  "topic_smiley" varchar(80) default NULL,
  "topic_title" varchar(240) default NULL,
  "summary" varchar(240) default NULL,
  "in_reply_to" varchar(128) default NULL,
  "tags" varchar(255) default NULL,
  "email" varchar(255) default NULL,
  PRIMARY KEY ("qId")
) ;


DROP TABLE IF EXISTS "tiki_forums_reported";

CREATE TABLE "tiki_forums_reported" (
  "threadId" bigint NOT NULL default '0',
  "forumId" bigint NOT NULL default '0',
  "parentId" bigint NOT NULL default '0',
  "user" varchar(200) default '',
  "timestamp" bigint default NULL,
  "reason" varchar(250) default NULL,
  PRIMARY KEY ("threadId")
);


DROP TABLE IF EXISTS "tiki_galleries";

CREATE TABLE "tiki_galleries" (
  "galleryId" bigserial,
  "name" varchar(80) NOT NULL default '',
  "description" text,
  "created" bigint default NULL,
  "lastModif" bigint default NULL,
  "visible" char(1) default NULL,
  "geographic" char(1) default NULL,
  "theme" varchar(60) default NULL,
  "user" varchar(200) default '',
  "hits" bigint default NULL,
  "maxRows" bigint default NULL,
  "rowImages" bigint default NULL,
  "thumbSizeX" bigint default NULL,
  "thumbSizeY" bigint default NULL,
  "public" char(1) default NULL,
  "sortorder" varchar(20) NOT NULL default 'created',
  "sortdirection" varchar(4) NOT NULL default 'desc',
  "galleryimage" varchar(20) NOT NULL default 'first',
  "parentgallery" bigint NOT NULL default -1,
  "showname" char(1) NOT NULL default 'y',
  "showimageid" char(1) NOT NULL default 'n',
  "showdescription" char(1) NOT NULL default 'n',
  "showcreated" char(1) NOT NULL default 'n',
  "showuser" char(1) NOT NULL default 'n',
  "showhits" char(1) NOT NULL default 'y',
  "showxysize" char(1) NOT NULL default 'y',
  "showfilesize" char(1) NOT NULL default 'n',
  "showfilename" char(1) NOT NULL default 'n',
  "defaultscale" varchar(10) NOT NULL DEFAULT 'o',
  "showcategories" char(1) NOT NULL default 'n', 
  PRIMARY KEY ("galleryId")
) ;

CREATE INDEX "_name" ON ""("name");
CREATE INDEX "_description" ON ""(substr("description", 0, 255));
CREATE INDEX "_hits" ON ""("hits");
CREATE INDEX "_parentgallery" ON ""("parentgallery");
CREATE INDEX "_visibleUser" ON ""("visible","user");

DROP TABLE IF EXISTS "tiki_galleries_scales";

CREATE TABLE "tiki_galleries_scales" (
  "galleryId" bigint NOT NULL default '0',
  "scale" bigint NOT NULL default '0',
  PRIMARY KEY ("galleryId","scale")
);


DROP TABLE IF EXISTS "tiki_group_inclusion";

CREATE TABLE "tiki_group_inclusion" (
  "groupName" varchar(255) NOT NULL default '',
  "includeGroup" varchar(255) NOT NULL default '',
  PRIMARY KEY ("groupName","includeGroup")
);


DROP TABLE IF EXISTS "tiki_group_watches";

CREATE TABLE "tiki_group_watches" (
  "watchId" bigserial,
  "group" varchar(200) NOT NULL default '',
  "event" varchar(40) NOT NULL default '',
  "object" varchar(200) NOT NULL default '',
  "title" varchar(250) default NULL,
  "type" varchar(200) default NULL,
  "url" varchar(250) default NULL,
  PRIMARY KEY ("group",event,object)
);

CREATE INDEX "_watchId" ON ""("watchId");

DROP TABLE IF EXISTS "tiki_history";

CREATE TABLE "tiki_history" (
  "historyId" bigserial,
  "pageName" varchar(160) NOT NULL default '',
  "version" integer NOT NULL default '0',
  "version_minor" integer NOT NULL default '0',
  "lastModif" bigint default NULL,
  "description" varchar(200) default NULL,
  "user" varchar(200) not null default '',
  "ip" varchar(15) default NULL,
  "comment" varchar(200) default NULL,
  "data" bytea,
  "type" varchar(50) default NULL,
  "is_html" TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY ("pageName","version"),
  KEY(historyId)
);

CREATE INDEX "_user" ON ""(\","user"\");

DROP TABLE IF EXISTS "tiki_hotwords";

CREATE TABLE "tiki_hotwords" (
  "word" varchar(40) NOT NULL default '',
  "url" varchar(255) NOT NULL default '',
  PRIMARY KEY ("word")
);


DROP TABLE IF EXISTS "tiki_html_pages";

CREATE TABLE "tiki_html_pages" (
  "pageName" varchar(200) NOT NULL default '',
  "content" bytea,
  "refresh" bigint default NULL,
  "type" char(1) default NULL,
  "created" bigint default NULL,
  PRIMARY KEY ("pageName")
);


DROP TABLE IF EXISTS "tiki_html_pages_dynamic_zones";

CREATE TABLE "tiki_html_pages_dynamic_zones" (
  "pageName" varchar(40) NOT NULL default '',
  "zone" varchar(80) NOT NULL default '',
  "type" char(2) default NULL,
  "content" text,
  PRIMARY KEY ("pageName","zone")
);


DROP TABLE IF EXISTS "tiki_images";

CREATE TABLE "tiki_images" (
  "imageId" bigserial,
  "galleryId" bigint NOT NULL default '0',
  "name" varchar(200) NOT NULL default '',
  "description" text,
  "lon" float default NULL,
  "lat" float default NULL,
  "created" bigint default NULL,
  "user" varchar(200) default '',
  "hits" bigint default NULL,
  "path" varchar(255) default NULL,
  PRIMARY KEY ("imageId")
) ;

CREATE INDEX "_name" ON ""("name");
CREATE INDEX "_description" ON ""(substr("description", 0, 255));
CREATE INDEX "_hits" ON ""("hits");
CREATE INDEX "_ti_gId" ON ""("galleryId");
CREATE INDEX "_ti_cr" ON ""("created");
CREATE INDEX "_ti_us" ON ""("user");

DROP TABLE IF EXISTS "tiki_images_data";

CREATE TABLE "tiki_images_data" (
  "imageId" bigint NOT NULL default '0',
  "xsize" integer NOT NULL default '0',
  "ysize" integer NOT NULL default '0',
  "type" char(1) NOT NULL default '',
  "filesize" bigint default NULL,
  "filetype" varchar(80) default NULL,
  "filename" varchar(80) default NULL,
  "data" bytea,
  "etag" varchar(32) default NULL,
  PRIMARY KEY ("imageId","xsize","ysize","type")
);

CREATE INDEX "_t_i_d_it" ON ""("imageId","type");

DROP TABLE IF EXISTS "tiki_language";

CREATE TABLE "tiki_language" (
  "source" bytea NOT NULL,
  "lang" char(16) NOT NULL default '',
  "tran" bytea,
  PRIMARY KEY ("source","lang")
);


DROP TABLE IF EXISTS "tiki_languages";

CREATE TABLE "tiki_languages" (
  "lang" char(16) NOT NULL default '',
  "language" varchar(255) default NULL,
  PRIMARY KEY ("lang")
);


INSERT INTO "tiki_languages" ("lang","language") VALUES ('en','English');


DROP TABLE IF EXISTS "tiki_link_cache";

CREATE TABLE "tiki_link_cache" (
  "cacheId" bigserial,
  "url" varchar(250) default NULL,
  "data" bytea,
  "refresh" bigint default NULL,
  PRIMARY KEY ("cacheId")
) ;

CREATE INDEX "_url" ON ""("url");
CREATE INDEX "urlindex" ON "tiki_link_cache" (substr("url", 0, 250));
;


DROP TABLE IF EXISTS "tiki_links";

CREATE TABLE "tiki_links" (
  "fromPage" varchar(160) NOT NULL default '',
  "toPage" varchar(160) NOT NULL default '',
  "reltype" varchar(50),
  PRIMARY KEY ("fromPage","toPage")
);

CREATE INDEX "_toPage" ON ""("toPage");

DROP TABLE IF EXISTS "tiki_live_support_events";

CREATE TABLE "tiki_live_support_events" (
  "eventId" bigserial,
  "reqId" varchar(32) NOT NULL default '',
  "type" varchar(40) default NULL,
  "seqId" bigint default NULL,
  "senderId" varchar(32) default NULL,
  "data" text,
  "timestamp" bigint default NULL,
  PRIMARY KEY ("eventId")
) ;


DROP TABLE IF EXISTS "tiki_live_support_message_comments";

CREATE TABLE "tiki_live_support_message_comments" (
  "cId" bigserial,
  "msgId" bigint default NULL,
  "data" text,
  "timestamp" bigint default NULL,
  PRIMARY KEY ("cId")
) ;


DROP TABLE IF EXISTS "tiki_live_support_messages";

CREATE TABLE "tiki_live_support_messages" (
  "msgId" bigserial,
  "data" text,
  "timestamp" bigint default NULL,
  "user" varchar(200) not null default '',
  "username" varchar(200) default NULL,
  "priority" smallint default NULL,
  "status" char(1) default NULL,
  "assigned_to" varchar(200) default NULL,
  "resolution" varchar(100) default NULL,
  "title" varchar(200) default NULL,
  "module" smallint default NULL,
  "email" varchar(250) default NULL,
  PRIMARY KEY ("msgId")
) ;


DROP TABLE IF EXISTS "tiki_live_support_modules";

CREATE TABLE "tiki_live_support_modules" (
  "modId" serial,
  "name" varchar(90) default NULL,
  PRIMARY KEY ("modId")
) ;


INSERT INTO "tiki_live_support_modules" ("name") VALUES ('wiki');

INSERT INTO "tiki_live_support_modules" ("name") VALUES ('forums');

INSERT INTO "tiki_live_support_modules" ("name") VALUES ('image galleries');

INSERT INTO "tiki_live_support_modules" ("name") VALUES ('file galleries');

INSERT INTO "tiki_live_support_modules" ("name") VALUES ('directory');

INSERT INTO "tiki_live_support_modules" ("name") VALUES ('workflow');


DROP TABLE IF EXISTS "tiki_live_support_operators";

CREATE TABLE "tiki_live_support_operators" (
  "user" varchar(200) NOT NULL default '',
  "accepted_requests" bigint default NULL,
  "status" varchar(20) default NULL,
  "longest_chat" bigint default NULL,
  "shortest_chat" bigint default NULL,
  "average_chat" bigint default NULL,
  "last_chat" bigint default NULL,
  "time_online" bigint default NULL,
  "votes" bigint default NULL,
  "points" bigint default NULL,
  "status_since" bigint default NULL,
  PRIMARY KEY ("user")
);


DROP TABLE IF EXISTS "tiki_live_support_requests";

CREATE TABLE "tiki_live_support_requests" (
  "reqId" varchar(32) NOT NULL default '',
  "user" varchar(200) NOT NULL default '',
  "tiki_user" varchar(200) default NULL,
  "email" varchar(200) default NULL,
  "operator" varchar(200) default NULL,
  "operator_id" varchar(32) default NULL,
  "user_id" varchar(32) default NULL,
  "reason" text,
  "req_timestamp" bigint default NULL,
  "timestamp" bigint default NULL,
  "status" varchar(40) default NULL,
  "resolution" varchar(40) default NULL,
  "chat_started" bigint default NULL,
  "chat_ended" bigint default NULL,
  PRIMARY KEY ("reqId")
);


DROP TABLE IF EXISTS "tiki_logs";

CREATE TABLE "tiki_logs" (
  "logId" serial,
  "logtype" varchar(20) NOT NULL,
  "logmessage" text NOT NULL,
  "loguser" varchar(40) NOT NULL,
  "logip" varchar(200),
  "logclient" text NOT NULL,
  "logtime" bigint NOT NULL,
  PRIMARY KEY ("logId")
);

CREATE INDEX "_logtype" ON ""("logtype");

DROP TABLE IF EXISTS "tiki_mail_events";

CREATE TABLE "tiki_mail_events" (
  "event" varchar(200) default NULL,
  "object" varchar(200) default NULL,
  "email" varchar(200) default NULL
);


DROP TABLE IF EXISTS "tiki_mailin_accounts";

CREATE TABLE "tiki_mailin_accounts" (
  "accountId" bigserial,
  "user" varchar(200) NOT NULL default '',
  "account" varchar(50) NOT NULL default '',
  "pop" varchar(255) default NULL,
  "port" smallint default NULL,
  "username" varchar(100) default NULL,
  "pass" varchar(100) default NULL,
  "active" char(1) default NULL,
  "type" varchar(40) default NULL,
  "smtp" varchar(255) default NULL,
  "useAuth" char(1) default NULL,
  "smtpPort" smallint default NULL,
  "anonymous" char(1) NOT NULL default 'y',
  "attachments" char(1) NOT NULL default 'n',
  "article_topicId" smallint default NULL,
  "article_type" varchar(50) default NULL,
  "discard_after" varchar(255) default NULL,
  PRIMARY KEY ("accountId")
) ;


DROP TABLE IF EXISTS "tiki_menu_languages";

CREATE TABLE "tiki_menu_languages" (
  "menuId" serial,
  "language" char(16) NOT NULL default '',
  PRIMARY KEY ("menuId","language")
) ;


DROP TABLE IF EXISTS "tiki_menu_options";

CREATE TABLE "tiki_menu_options" (
  "optionId" serial,
  "menuId" integer default NULL,
  "type" char(1) default NULL,
  "name" varchar(200) default NULL,
  "url" varchar(255) default NULL,
  "position" smallint default NULL,
  "section" text default NULL,
  "perm" text default NULL,
  "groupname" text default NULL,
  "userlevel" smallint default 0,
  "icon" varchar(200),
  PRIMARY KEY ("optionId"),
  UNIQUE (menuId,name,url,position,section,perm,groupname)
) ;


-- when adding new inserts, order commands by position
INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Home','./',10,'','','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Search','tiki-searchresults.php',13,'feature_search_fulltext','tiki_p_search','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Search','tiki-searchindex.php',13,'feature_search','tiki_p_search','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Contact Us','tiki-contact.php',20,'feature_contact,feature_messages','','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Stats','tiki-stats.php',23,'feature_stats','tiki_p_view_stats','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Categories','tiki-browse_categories.php',25,'feature_categories','tiki_p_view_categories','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Freetags','tiki-browse_freetags.php',27,'feature_freetags','tiki_p_view_freetags','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Calendar','tiki-calendar.php',35,'feature_calendar','tiki_p_view_calendar','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Users Map','tiki-gmap_usermap.php',36,'feature_gmap','','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Tiki Calendar','tiki-action_calendar.php',37,'feature_action_calendar','tiki_p_view_tiki_calendar','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Mobile','tiki-mobile.php',37,'feature_mobile','','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','(debug)','javascript:toggle(\'debugconsole\')',40,'feature_debug_console','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','MyTiki','tiki-my_tiki.php',50,'feature_mytiki','','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','MyTiki Home','tiki-my_tiki.php',51,'feature_mytiki','','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Preferences','tiki-user_preferences.php',55,'feature_mytiki,feature_userPreferences','','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Messages','messu-mailbox.php',60,'feature_mytiki,feature_messages','tiki_p_messages','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Tasks','tiki-user_tasks.php',65,'feature_mytiki,feature_tasks','tiki_p_tasks','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Bookmarks','tiki-user_bookmarks.php',70,'feature_mytiki,feature_user_bookmarks','tiki_p_create_bookmarks','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Modules','tiki-user_assigned_modules.php',75,'feature_mytiki,user_assigned_modules','tiki_p_configure_modules','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Webmail','tiki-webmail.php',85,'feature_mytiki,feature_webmail','tiki_p_use_webmail','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Contacts','tiki-contacts.php',87,'feature_mytiki,feature_contacts','','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Notepad','tiki-notepad_list.php',90,'feature_mytiki,feature_notepad','tiki_p_notepad','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','My Files','tiki-userfiles.php',95,'feature_mytiki,feature_userfiles','tiki_p_userfiles','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','User Menu','tiki-usermenu.php',100,'feature_mytiki,feature_usermenu','tiki_p_usermenu','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Mini Calendar','tiki-minical.php',105,'feature_mytiki,feature_minical','tiki_p_minical','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','My Watches','tiki-user_watches.php',110,'feature_mytiki,feature_user_watches','','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Workflow','tiki-g-user_processes.php',150,'feature_workflow','tiki_p_use_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Processes','tiki-g-admin_processes.php',155,'feature_workflow','tiki_p_admin_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Monitor Processes','tiki-g-monitor_processes.php',160,'feature_workflow','tiki_p_admin_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Monitor Activities','tiki-g-monitor_activities.php',165,'feature_workflow','tiki_p_admin_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Monitor Instances','tiki-g-monitor_instances.php',170,'feature_workflow','tiki_p_admin_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','User Processes','tiki-g-user_processes.php',175,'feature_workflow','tiki_p_use_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','User activities','tiki-g-user_activities.php',180,'feature_workflow','tiki_p_use_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','User instances','tiki-g-user_instances.php',185,'feature_workflow','tiki_p_use_workflow','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Community','tiki-list_users.php',187,'feature_friends','tiki_p_list_users','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','User List','tiki-list_users.php',188,'feature_friends','tiki_p_list_users','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Friendship Network','tiki-friends.php',189,'feature_friends','','Registered',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Wiki','tiki-index.php',200,'feature_wiki','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Wiki Home','tiki-index.php',202,'feature_wiki','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Last Changes','tiki-lastchanges.php',205,'feature_wiki,feature_lastChanges','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Dump','dump/new.tar',210,'feature_wiki,feature_dump','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Rankings','tiki-wiki_rankings.php',215,'feature_wiki,feature_wiki_rankings','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Pages','tiki-listpages.php',220,'feature_wiki,feature_listPages','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Orphan Pages','tiki-orphan_pages.php',225,'feature_wiki,feature_listorphanPages','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Sandbox','tiki-editpage.php?page=sandbox',230,'feature_wiki,feature_sandbox','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Multiple Print','tiki-print_pages.php',235,'feature_wiki,feature_wiki_multiprint','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Send Pages','tiki-send_objects.php',240,'feature_wiki,feature_comm','tiki_p_view,tiki_p_send_pages','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Received Pages','tiki-received_pages.php',245,'feature_wiki,feature_comm','tiki_p_view,tiki_p_admin_received_pages','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Structures','tiki-admin_structures.php',250,'feature_wiki,feature_wiki_structure','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Mind Map','tiki-mindmap.php',255,'feature_wiki_mindmap','tiki_p_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Image Galleries','tiki-galleries.php',300,'feature_galleries','tiki_p_view_image_gallery','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Galleries','tiki-galleries.php',305,'feature_galleries','tiki_p_list_image_galleries','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Rankings','tiki-galleries_rankings.php',310,'feature_galleries,feature_gal_rankings','tiki_p_list_image_galleries','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Upload Image','tiki-upload_image.php',315,'feature_galleries','tiki_p_upload_images','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Directory Batch','tiki-batch_upload.php',318,'feature_galleries,feature_gal_batch','tiki_p_batch_upload','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','System Gallery','tiki-list_gallery.php?galleryId=0',320,'feature_galleries','tiki_p_admin_galleries','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Articles','tiki-view_articles.php',350,'feature_articles','tiki_p_read_article','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Articles','tiki-view_articles.php',350,'feature_articles','tiki_p_articles_read_heading','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Articles Home','tiki-view_articles.php',355,'feature_articles','tiki_p_read_article','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Articles Home','tiki-view_articles.php',355,'feature_articles','tiki_p_articles_read_heading','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Articles','tiki-list_articles.php',360,'feature_articles','tiki_p_read_article','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Articles','tiki-list_articles.php',360,'feature_articles','tiki_p_articles_read_heading','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Rankings','tiki-cms_rankings.php',365,'feature_articles,feature_cms_rankings','tiki_p_read_article','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Submit Article','tiki-edit_submission.php',370,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_submit_article','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','View submissions','tiki-list_submissions.php',375,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_submit_article','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','View submissions','tiki-list_submissions.php',375,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_approve_submission','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','View Submissions','tiki-list_submissions.php',375,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_remove_submission','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','New Article','tiki-edit_article.php',380,'feature_articles','tiki_p_read_article,tiki_p_edit_article','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Send Articles','tiki-send_objects.php',385,'feature_articles,feature_comm','tiki_p_read_article,tiki_p_send_articles','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Received Articles','tiki-received_articles.php',385,'feature_articles,feature_comm','tiki_p_read_article,tiki_p_admin_received_articles','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Types','tiki-article_types.php',395,'feature_articles','tiki_p_articles_admin_types','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Topics','tiki-admin_topics.php',390,'feature_articles','tiki_p_articles_admin_topics','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Blogs','tiki-list_blogs.php',450,'feature_blogs','tiki_p_read_blog','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Blogs','tiki-list_blogs.php',455,'feature_blogs','tiki_p_read_blog','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Rankings','tiki-blog_rankings.php',460,'feature_blogs,feature_blog_rankings','tiki_p_read_blog','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Create/Edit Blog','tiki-edit_blog.php',465,'feature_blogs','tiki_p_read_blog,tiki_p_create_blogs','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Post','tiki-blog_post.php',470,'feature_blogs','tiki_p_read_blog,tiki_p_blog_post','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Posts','tiki-list_posts.php',475,'feature_blogs','tiki_p_read_blog,tiki_p_blog_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Forums','tiki-forums.php',500,'feature_forums','tiki_p_forum_read','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Forums','tiki-forums.php',505,'feature_forums','tiki_p_forum_read','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Rankings','tiki-forum_rankings.php',510,'feature_forums,feature_forum_rankings','tiki_p_forum_read','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Forums','tiki-admin_forums.php',515,'feature_forums','tiki_p_forum_read,tiki_p_admin_forum','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Directory','tiki-directory_browse.php',550,'feature_directory','tiki_p_view_directory','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Submit a new link','tiki-directory_add_site.php',555,'feature_directory','tiki_p_submit_link','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Browse Directory','tiki-directory_browse.php',560,'feature_directory','tiki_p_view_directory','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Directory','tiki-directory_admin.php',565,'feature_directory','tiki_p_view_directory,tiki_p_admin_directory_cats','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Directory','tiki-directory_admin.php',565,'feature_directory','tiki_p_view_directory,tiki_p_admin_directory_sites','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Directory','tiki-directory_admin.php',565,'feature_directory','tiki_p_view_directory,tiki_p_validate_links','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','File Galleries','tiki-list_file_gallery.php',600,'feature_file_galleries','tiki-list_file_gallery.php|tiki_p_view_file_gallery|tiki_p_upload_files','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Galleries','tiki-list_file_gallery.php',605,'feature_file_galleries','tiki_p_list_file_galleries','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Rankings','tiki-file_galleries_rankings.php',610,'feature_file_galleries,feature_file_galleries_rankings','tiki_p_list_file_galleries','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Upload File','tiki-upload_file.php',615,'feature_file_galleries','tiki_p_upload_files','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Directory batch','tiki-batch_upload_files.php',617,'feature_file_galleries_batch','tiki_p_batch_upload_file_dir','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','FAQs','tiki-list_faqs.php',650,'feature_faqs','tiki_p_view_faqs','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List FAQs','tiki-list_faqs.php',665,'feature_faqs','tiki_p_view_faqs','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin FAQs','tiki-list_faqs.php',660,'feature_faqs','tiki_p_admin_faqs','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Maps','tiki-map.php',700,'feature_maps','tiki_p_map_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Mapfiles','tiki-map_edit.php',705,'feature_maps','tiki_p_map_view','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Layer Management','tiki-map_upload.php',710,'feature_maps','tiki_p_map_edit','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Quizzes','tiki-list_quizzes.php',750,'feature_quizzes','tiki_p_take_quiz','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Quizzes','tiki-list_quizzes.php',755,'feature_quizzes','tiki_p_take_quiz','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Quiz Stats','tiki-quiz_stats.php',760,'feature_quizzes','tiki_p_view_quiz_stats','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Quizzes','tiki-edit_quiz.php',765,'feature_quizzes','tiki_p_admin_quizzes','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','TikiSheet','tiki-sheets.php',780,'feature_sheet','tiki_p_view_sheet','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List TikiSheets','tiki-sheets.php',782,'feature_sheet','tiki_p_view_sheet','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Trackers','tiki-list_trackers.php',800,'feature_trackers','tiki_p_view_trackers','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Trackers','tiki-list_trackers.php',805,'feature_trackers','tiki_p_view_trackers','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Trackers','tiki-admin_trackers.php',810,'feature_trackers','tiki_p_admin_trackers','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Surveys','tiki-list_surveys.php',850,'feature_surveys','tiki_p_take_survey','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','List Surveys','tiki-list_surveys.php',855,'feature_surveys','tiki_p_take_survey','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Stats','tiki-survey_stats.php',860,'feature_surveys','tiki_p_view_survey_stats','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Surveys','tiki-admin_surveys.php',865,'feature_surveys','tiki_p_admin_surveys','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Newsletters','tiki-newsletters.php',900,'feature_newsletters','tiki_p_subscribe_newsletters','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Newsletters','tiki-newsletters.php',900,'feature_newsletters','tiki_p_send_newsletters','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Newsletters','tiki-newsletters.php',900,'feature_newsletters','tiki_p_admin_newsletters','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'s','Newsletters','tiki-newsletters.php',900,'feature_newsletters','tiki_p_list_newsletters','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Send Newsletters','tiki-send_newsletters.php',905,'feature_newsletters','tiki_p_send_newsletters','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Newsletters','tiki-admin_newsletters.php',910,'feature_newsletters','tiki_p_admin_newsletters','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_categories','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_banners','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_templates','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_cookies','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_dynamic','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_mailin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_content_templates','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_html_pages','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_view_referer_stats','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_shoutbox','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_live_support_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','user_is_operator','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'feature_integrator','tiki_p_admin_integrator','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'feature_edit_templates','tiki_p_edit_templates','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'feature_view_tpl','tiki_p_edit_templates','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'feature_editcss','tiki_p_create_css','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_contribution','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_users','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_quicktags','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_menu','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'r','Admin','tiki-admin.php',1050,'','tiki_p_clean_cache','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Admin Home','tiki-admin.php',1051,'','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Live Support','tiki-live_support_admin.php',1055,'feature_live_support','tiki_p_live_support_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Live Support','tiki-live_support_admin.php',1055,'feature_live_support','user_is_operator','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Banning','tiki-admin_banning.php',1060,'feature_banning','tiki_p_admin_banning','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Calendar','tiki-admin_calendars.php',1065,'feature_calendar','tiki_p_admin_calendar','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Users','tiki-adminusers.php',1070,'','tiki_p_admin_users','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Groups','tiki-admingroups.php',1075,'','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Wiki Cache','tiki-list_cache.php',1080,'cachepages','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Modules','tiki-admin_modules.php',1085,'','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Hotwords','tiki-admin_hotwords.php',1095,'feature_hotwords','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','RSS Modules','tiki-admin_rssmodules.php',1100,'','tiki_p_admin_rssmodules','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Menus','tiki-admin_menus.php',1105,'','tiki_p_edit_menu','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Polls','tiki-admin_polls.php',1110,'feature_polls','tiki_p_admin_polls','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Mail Notifications','tiki-admin_notifications.php',1120,'','tiki_p_admin_notifications','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Search Stats','tiki-search_stats.php',1125,'feature_search_stats','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Theme Control','tiki-theme_control.php',1130,'feature_theme_control','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','QuickTags','tiki-admin_quicktags.php',1135,'','tiki_p_admin_quicktags','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Categories','tiki-admin_categories.php',1145,'feature_categories','tiki_p_admin_categories','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Banners','tiki-list_banners.php',1150,'feature_banners','tiki_p_admin_banners','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Edit Templates','tiki-edit_templates.php',1155,'feature_edit_templates','tiki_p_edit_templates','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','View Templates','tiki-edit_templates.php',1155,'feature_view_tpl','tiki_p_edit_templates','',2);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Edit CSS','tiki-edit_css.php',1158,'feature_editcss','tiki_p_create_css','',2);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Dynamic content','tiki-list_contents.php',1165,'feature_dynamic_content','tiki_p_admin_dynamic','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Mail-in','tiki-admin_mailin.php',1175,'feature_mailin','tiki_p_admin_mailin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','HTML Pages','tiki-admin_html_pages.php',1185,'feature_html_pages','tiki_p_edit_html_pages','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Shoutbox','tiki-shoutbox.php',1190,'feature_shoutbox','tiki_p_admin_shoutbox','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Shoutbox Words','tiki-admin_shoutbox_words.php',1191,'feature_shoutbox','tiki_p_admin_shoutbox','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Referer Stats','tiki-referer_stats.php',1195,'feature_referer_stats','tiki_p_view_referer_stats','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Integrator','tiki-admin_integrator.php',1205,'feature_integrator','tiki_p_admin_integrator','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','phpinfo','tiki-phpinfo.php',1215,'','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Tiki Cache/Sys Admin','tiki-admin_system.php',1230,'','tiki_p_clean_cache','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Tiki Importer','tiki-importer.php',1240,'','tiki_p_admin_importer','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Tiki Logs','tiki-syslog.php',1245,'','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Security Admin','tiki-admin_security.php',1250,'','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Action Log','tiki-admin_actionlog.php',1255,'feature_actionlog','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Action Log','tiki-admin_actionlog.php',1255,'feature_actionlog','tiki_p_view_actionlog','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Action Log','tiki-admin_actionlog.php',1255,'feature_actionlog','tiki_p_view_actionlog_owngroups','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Content Templates','tiki-admin_content_templates.php',1256,'','tiki_p_edit_content_templates','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Comments','tiki-list_comments.php',1260,'feature_wiki_comments','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Comments','tiki-list_comments.php',1260,'feature_article_comments','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Comments','tiki-list_comments.php',1260,'feature_blog_comments','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Comments','tiki-list_comments.php',1260,'feature_file_galleries_comments','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Comments','tiki-list_comments.php',1260,'feature_image_galleries_comments','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Comments','tiki-list_comments.php',1260,'feature_poll_comments','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Comments','tiki-list_comments.php',1260,'feature_faq_comments','tiki_p_admin','',0);

INSERT INTO "," ("\"menuId\",""\"type\",""\"name\",""\"url\",""\"position\",""\"section\",""\"perm\",""\"groupname\",""\"userlevel\",") VALUES (42,'o','Contribution','tiki-admin_contribution.php',1265,'feature_contribution','tiki_p_admin_contribution','',0);




DROP TABLE IF EXISTS "tiki_menus";

CREATE TABLE "tiki_menus" (
  "menuId" serial,
  "name" varchar(200) NOT NULL default '',
  "description" text,
  "type" char(1) default NULL,
  "icon" varchar(200) default NULL,
  "use_items_icons" char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY ("menuId")
) ;


INSERT INTO "tiki_menus" ("menuId","name","description","type") VALUES ('42','Application menu','Main extensive navigation menu','d');


DROP TABLE IF EXISTS "tiki_minical_events";

CREATE TABLE "tiki_minical_events" (
  "user" varchar(200) default '',
  "eventId" bigserial,
  "title" varchar(250) default NULL,
  "description" text,
  "start" bigint default NULL,
  "end" bigint default NULL,
  "security" char(1) default NULL,
  "duration" smallint default NULL,
  "topicId" bigint default NULL,
  "reminded" char(1) default NULL,
  PRIMARY KEY ("eventId")
) ;


DROP TABLE IF EXISTS "tiki_minical_topics";

CREATE TABLE "tiki_minical_topics" (
  "user" varchar(200) default '',
  "topicId" bigserial,
  "name" varchar(250) default NULL,
  "filename" varchar(200) default NULL,
  "filetype" varchar(200) default NULL,
  "filesize" varchar(200) default NULL,
  "data" bytea,
  "path" varchar(250) default NULL,
  "isIcon" char(1) default NULL,
  PRIMARY KEY ("topicId")
) ;


DROP TABLE IF EXISTS "tiki_modules";

CREATE TABLE "tiki_modules" (
  "moduleId" serial,
  "name" varchar(200) NOT NULL default '',
  "position" char(1) default NULL,
  "ord" smallint default NULL,
  "type" char(1) default NULL,
  "title" varchar(255) default NULL,
  "cache_time" bigint default NULL,
  "rows" smallint default NULL,
  "params" varchar(255) default NULL,
  "groups" text,
  PRIMARY KEY ("name", "position", "ord", "params")
);

CREATE INDEX "_positionType" ON ""("position","type");
CREATE INDEX "_moduleId" ON ""("moduleId");

INSERT INTO "tiki_modules" ("name","position","ord","cache_time","params","groups") VALUES ('mnu_application_menu','l',30,0,'flip=y','a:1:{i:0;s:10:"Registered";}');


DROP TABLE IF EXISTS "tiki_newsletter_subscriptions";

CREATE TABLE "tiki_newsletter_subscriptions" (
  "nlId" bigint NOT NULL default '0',
  "email" varchar(255) NOT NULL default '',
  "code" varchar(32) default NULL,
  "valid" char(1) default NULL,
  "subscribed" bigint default NULL,
  "isUser" char(1) NOT NULL default 'n',
  "included" char(1) NOT NULL default 'n',
  PRIMARY KEY ("nlId","email","isUser")
);


DROP TABLE IF EXISTS "tiki_newsletter_groups";

CREATE TABLE "tiki_newsletter_groups" (
  "nlId" bigint NOT NULL default '0',
  "groupName" varchar(255) NOT NULL default '',
  "code" varchar(32) default NULL,
  PRIMARY KEY ("nlId","groupName")
);


DROP TABLE IF EXISTS "tiki_newsletter_included";

CREATE TABLE "tiki_newsletter_included" (
  "nlId" bigint NOT NULL default '0',
  "includedId" bigint NOT NULL default '0',
  PRIMARY KEY ("nlId","includedId")
);


DROP TABLE IF EXISTS "tiki_newsletters";

CREATE TABLE "tiki_newsletters" (
  "nlId" bigserial,
  "name" varchar(200) default NULL,
  "description" text,
  "created" bigint default NULL,
  "lastSent" bigint default NULL,
  "editions" bigint default NULL,
  "users" bigint default NULL,
  "allowUserSub" char(1) default 'y',
  "allowAnySub" char(1) default NULL,
  "unsubMsg" char(1) default 'y',
  "validateAddr" char(1) default 'y',
  "frequency" bigint default NULL,
  "allowTxt" char(1) default 'y',
  "author" varchar(200) default NULL,
  PRIMARY KEY ("nlId")
) ;


DROP TABLE IF EXISTS "tiki_page_footnotes";

CREATE TABLE "tiki_page_footnotes" (
  "user" varchar(200) NOT NULL default '',
  "pageName" varchar(250) NOT NULL default '',
  "data" text,
  PRIMARY KEY ("user",pageName)
);


DROP TABLE IF EXISTS "tiki_pages";

CREATE TABLE "tiki_pages" (
  "page_id" bigserial,
  "pageName" varchar(160) NOT NULL default '',
  "hits" integer default NULL,
  "data" mediumtext,
  "description" varchar(200) default NULL,
  "lastModif" bigint default NULL,
  "comment" varchar(200) default NULL,
  "version" integer NOT NULL default '0',
  "user" varchar(200) default '',
  "ip" varchar(15) default NULL,
  "flag" char(1) default NULL,
  "points" integer default NULL,
  "votes" integer default NULL,
  "cache" text,
  "wiki_cache" bigint default NULL,
  "cache_timestamp" bigint default NULL,
  "pageRank" decimal(4,3) default NULL,
  "creator" varchar(200) default NULL,
  "page_size" bigint default '0',
  "lang" varchar(16) default NULL,
  "lockedby" varchar(200) default NULL,
  "is_html" smallint default 0,
  "created" bigint,
  "wysiwyg" char(1) default NULL,
  "wiki_authors_style" varchar(20) default '',
  PRIMARY KEY ("page_id"),
  UNIQUE (pageName)
);

CREATE INDEX "_data" ON ""(substr("data", 0, 255));
CREATE INDEX "_pageRank" ON ""("pageRank");
CREATE INDEX "_lastModif" ON ""("lastModif");

DROP TABLE IF EXISTS "tiki_page_drafts";

CREATE TABLE "tiki_page_drafts" (
  "user" varchar(200) default '',
  "pageName" varchar(255) NOT NULL,
  "data" mediumtext,
  "description" varchar(200) default NULL,
  "comment" varchar(200) default NULL,
  "lastModif" bigint default NULL,
  PRIMARY KEY ("pageName", "user")
);


DROP TABLE IF EXISTS "tiki_pageviews";

CREATE TABLE "tiki_pageviews" (
  "day" bigint NOT NULL default '0',
  "pageviews" bigint default NULL,
  PRIMARY KEY ("day")
);


DROP TABLE IF EXISTS "tiki_poll_objects";

CREATE TABLE "tiki_poll_objects" (
  "catObjectId" bigint NOT NULL default '0',
  "pollId" bigint NOT NULL default '0',
  "title" varchar(255) default NULL,
  PRIMARY KEY ("catObjectId","pollId")
);


DROP TABLE IF EXISTS "tiki_poll_options";

CREATE TABLE "tiki_poll_options" (
  "pollId" integer NOT NULL default '0',
  "optionId" serial,
  "title" varchar(200) default NULL,
  "position" smallint NOT NULL default '0',
  "votes" integer default NULL,
  PRIMARY KEY ("optionId")
) ;


DROP TABLE IF EXISTS "tiki_polls";

CREATE TABLE "tiki_polls" (
  "pollId" serial,
  "title" varchar(200) default NULL,
  "votes" integer default NULL,
  "active" char(1) default NULL,
  "publishDate" bigint default NULL,
  "voteConsiderationSpan" smallint default 0,
  PRIMARY KEY ("pollId")
) ;


DROP TABLE IF EXISTS "tiki_preferences";

CREATE TABLE "tiki_preferences" (
  "name" varchar(40) NOT NULL default '',
  "value" text,
  PRIMARY KEY ("name")
);


DROP TABLE IF EXISTS "tiki_private_messages";

CREATE TABLE "tiki_private_messages" (
  "messageId" serial,
  "toNickname" varchar(200) NOT NULL default '',
  "poster" varchar(200) NOT NULL default 'anonymous',
  "timestamp" bigint default NULL,
  "received" smallint not null default 0,
  "message" varchar(255) default NULL,
  PRIMARY KEY ("messageId")
) ;

CREATE INDEX "_" ON ""(\","received"\");
CREATE INDEX "_" ON ""(\","timestamp"\");

DROP TABLE IF EXISTS "tiki_programmed_content";

CREATE TABLE "tiki_programmed_content" (
  "pId" serial,
  "contentId" integer NOT NULL default '0',
  "publishDate" bigint NOT NULL default '0',
  "data" text,
  PRIMARY KEY ("pId")
) ;


DROP TABLE IF EXISTS "tiki_quiz_question_options";

CREATE TABLE "tiki_quiz_question_options" (
  "optionId" bigserial,
  "questionId" bigint default NULL,
  "optionText" text,
  "points" smallint default NULL,
  PRIMARY KEY ("optionId")
) ;


DROP TABLE IF EXISTS "tiki_quiz_questions";

CREATE TABLE "tiki_quiz_questions" (
  "questionId" bigserial,
  "quizId" bigint default NULL,
  "question" text,
  "position" smallint default NULL,
  "type" char(1) default NULL,
  "maxPoints" smallint default NULL,
  PRIMARY KEY ("questionId")
) ;


DROP TABLE IF EXISTS "tiki_quiz_results";

CREATE TABLE "tiki_quiz_results" (
  "resultId" bigserial,
  "quizId" bigint default NULL,
  "fromPoints" smallint default NULL,
  "toPoints" smallint default NULL,
  "answer" text,
  PRIMARY KEY ("resultId")
) ;


DROP TABLE IF EXISTS "tiki_quiz_stats";

CREATE TABLE "tiki_quiz_stats" (
  "quizId" bigint NOT NULL default '0',
  "questionId" bigint NOT NULL default '0',
  "optionId" bigint NOT NULL default '0',
  "votes" bigint default NULL,
  PRIMARY KEY ("quizId","questionId","optionId")
);


DROP TABLE IF EXISTS "tiki_quiz_stats_sum";

CREATE TABLE "tiki_quiz_stats_sum" (
  "quizId" bigint NOT NULL default '0',
  "quizName" varchar(255) default NULL,
  "timesTaken" bigint default NULL,
  "avgpoints" decimal(5,2) default NULL,
  "avgavg" decimal(5,2) default NULL,
  "avgtime" decimal(5,2) default NULL,
  PRIMARY KEY ("quizId")
);


DROP TABLE IF EXISTS "tiki_quizzes";

CREATE TABLE "tiki_quizzes" (
  "quizId" bigserial,
  "name" varchar(255) default NULL,
  "description" text,
  "canRepeat" char(1) default NULL,
  "storeResults" char(1) default NULL,
  "questionsPerPage" smallint default NULL,
  "timeLimited" char(1) default NULL,
  "timeLimit" bigint default NULL,
  "created" bigint default NULL,
  "taken" bigint default NULL,
  "immediateFeedback" char(1) default NULL,
  "showAnswers" char(1) default NULL,
  "shuffleQuestions" char(1) default NULL,
  "shuffleAnswers" char(1) default NULL,
  "publishDate" bigint default NULL,
  "expireDate" bigint default NULL,
  "bDeleted" char(1) default NULL,
  "nVersion" smallint NOT NULL,
  "nAuthor" smallint default NULL,
  "bOnline" char(1) default NULL,
  "bRandomQuestions" char(1) default NULL,
  "nRandomQuestions" smallint default NULL,
  "bLimitQuestionsPerPage" char(1) default NULL,
  "nLimitQuestionsPerPage" smallint default NULL,
  "bMultiSession" char(1) default NULL,
  "nCanRepeat" smallint default NULL,
  "sGradingMethod" varchar(80) default NULL,
  "sShowScore" varchar(80) default NULL,
  "sShowCorrectAnswers" varchar(80) default NULL,
  "sPublishStats" varchar(80) default NULL,
  "bAdditionalQuestions" char(1) default NULL,
  "bForum" char(1) default NULL,
  "sForum" varchar(80) default NULL,
  "sPrologue" text,
  "sData" text,
  "sEpilogue" text,
  "passingperct" smallint default 0,
  PRIMARY KEY ("quizId", "nVersion")
) ;


DROP TABLE IF EXISTS "tiki_received_articles";

CREATE TABLE "tiki_received_articles" (
  "receivedArticleId" bigserial,
  "receivedFromSite" varchar(200) default NULL,
  "receivedFromUser" varchar(200) default NULL,
  "receivedDate" bigint default NULL,
  "title" varchar(80) default NULL,
  "authorName" varchar(60) default NULL,
  "size" bigint default NULL,
  "useImage" char(1) default NULL,
  "image_name" varchar(80) default NULL,
  "image_type" varchar(80) default NULL,
  "image_size" bigint default NULL,
  "image_x" smallint default NULL,
  "image_y" smallint default NULL,
  "image_data" bytea,
  "publishDate" bigint default NULL,
  "expireDate" bigint default NULL,
  "created" bigint default NULL,
  "heading" text,
  "body" bytea,
  "hash" varchar(32) default NULL,
  "author" varchar(200) default NULL,
  "type" varchar(50) default NULL,
  "rating" decimal(3,2) default NULL,
  PRIMARY KEY ("receivedArticleId")
) ;


DROP TABLE IF EXISTS "tiki_received_pages";

CREATE TABLE "tiki_received_pages" (
  "receivedPageId" bigserial,
  "pageName" varchar(160) NOT NULL default '',
  "data" bytea,
  "description" varchar(200) default NULL,
  "comment" varchar(200) default NULL,
  "receivedFromSite" varchar(200) default NULL,
  "receivedFromUser" varchar(200) default NULL,
  "receivedDate" bigint default NULL,
  "parent" varchar(255) default NULL,
  "position" smallint unsigned default NULL,
  "alias" varchar(255) default NULL,
  "structureName" varchar(250) default NULL,
  "parentName" varchar(250) default NULL,
  "page_alias" varchar(250) default '',
  "pos" smallint default NULL,
  PRIMARY KEY ("receivedPageId")
) ;

CREATE INDEX "_structureName" ON ""("structureName");

DROP TABLE IF EXISTS "tiki_referer_stats";

CREATE TABLE "tiki_referer_stats" (
  "referer" varchar(255) NOT NULL default '',
  "hits" bigint default NULL,
  "last" bigint default NULL,
  PRIMARY KEY ("referer")
);


DROP TABLE IF EXISTS "tiki_related_categories";

CREATE TABLE "tiki_related_categories" (
  "categId" bigint NOT NULL default '0',
  "relatedTo" bigint NOT NULL default '0',
  PRIMARY KEY ("categId","relatedTo")
);


DROP TABLE IF EXISTS "tiki_rss_modules";

CREATE TABLE "tiki_rss_modules" (
  "rssId" serial,
  "name" varchar(30) NOT NULL default '',
  "description" text,
  "url" varchar(255) NOT NULL default '',
  "refresh" integer default NULL,
  "lastUpdated" bigint default NULL,
  "showTitle" char(1) default 'n',
  "showPubDate" char(1) default 'n',
  "content" bytea,
  PRIMARY KEY ("rssId")
) ;

CREATE INDEX "_name" ON ""("name");

DROP TABLE IF EXISTS "tiki_rss_feeds";

CREATE TABLE "tiki_rss_feeds" (
  "name" varchar(30) NOT NULL default '',
  "rssVer" char(1) NOT NULL default '1',
  "refresh" integer default '300',
  "lastUpdated" bigint default NULL,
  "cache" bytea,
  PRIMARY KEY ("name","rssVer")
);


DROP TABLE IF EXISTS "tiki_searchindex";

CREATE TABLE tiki_searchindex(
  "searchword" varchar(80) NOT NULL default '',
  "location" varchar(80) NOT NULL default '',
  "page" varchar(255) NOT NULL default '',
  "count" bigint NOT NULL default '1',
  "last_update" bigint NOT NULL default '0',
  PRIMARY KEY ("searchword","location","page")
);

CREATE INDEX "_last_update" ON ""("last_update");
CREATE INDEX "_location" ON ""(substr("location", 0, 50)substr("page", 0, 200));

-- LRU (last recently used) list for searching parts of words
DROP TABLE IF EXISTS "tiki_searchsyllable";

CREATE TABLE tiki_searchsyllable(
  "syllable" varchar(80) NOT NULL default '',
  "lastUsed" bigint NOT NULL default '0',
  "lastUpdated" bigint NOT NULL default '0',
  PRIMARY KEY ("syllable")
);

CREATE INDEX "_lastUsed" ON ""("lastUsed");

-- searchword caching table for search syllables
DROP TABLE IF EXISTS "tiki_searchwords";

CREATE TABLE tiki_searchwords(
  "syllable" varchar(80) NOT NULL default '',
  "searchword" varchar(80) NOT NULL default '',
  PRIMARY KEY ("syllable","searchword")
);


DROP TABLE IF EXISTS "tiki_search_stats";

CREATE TABLE "tiki_search_stats" (
  "term" varchar(50) NOT NULL default '',
  "hits" bigint default NULL,
  PRIMARY KEY ("term")
);


DROP TABLE IF EXISTS "tiki_secdb";

CREATE TABLE tiki_secdb(
  "md5_value" varchar(32) NOT NULL,
  "filename" varchar(250) NOT NULL,
  "tiki_version" varchar(60) NOT NULL,
  "severity" smallint NOT NULL default '0',
  PRIMARY KEY ("md5_value","filename","tiki_version")
);

CREATE INDEX "_sdb_fn" ON ""("filename");

DROP TABLE IF EXISTS "tiki_semaphores";

CREATE TABLE "tiki_semaphores" (
  "semName" varchar(250) NOT NULL default '',
  "objectType" varchar(20) default 'wiki page',
  "user" varchar(200) NOT NULL default '',
  "timestamp" bigint default NULL,
  PRIMARY KEY ("semName")
);


DROP TABLE IF EXISTS "tiki_sent_newsletters";

CREATE TABLE "tiki_sent_newsletters" (
  "editionId" bigserial,
  "nlId" bigint NOT NULL default '0',
  "users" bigint default NULL,
  "sent" bigint default NULL,
  "subject" varchar(200) default NULL,
  "data" bytea,
  "datatxt" bytea,
  PRIMARY KEY ("editionId")
) ;


DROP TABLE IF EXISTS "tiki_sent_newsletters_errors";

CREATE TABLE "tiki_sent_newsletters_errors" (
  "editionId" bigint,
  "email" varchar(255),
  "login" varchar(40) default '',
  "error" char(1) default ''
) ;

CREATE INDEX "_" ON ""("editionId");

DROP TABLE IF EXISTS "tiki_sessions";

CREATE TABLE "tiki_sessions" (
  "sessionId" varchar(32) NOT NULL default '',
  "user" varchar(200) default '',
  "timestamp" bigint default NULL,
  "tikihost" varchar(200) default NULL,
  PRIMARY KEY ("sessionId")
);

CREATE INDEX "_user" ON ""("user");
CREATE INDEX "_timestamp" ON ""(\","timestamp"\");

DROP TABLE IF EXISTS "tiki_sheet_layout";

CREATE TABLE "tiki_sheet_layout" (
  "sheetId" integer NOT NULL default '0',
  "begin" bigint NOT NULL default '0',
  "end" bigint default NULL,
  "headerRow" smallint NOT NULL default '0',
  "footerRow" smallint NOT NULL default '0',
  "className" varchar(64) default NULL,
  UNIQUE ("sheetId", "begin")
);


DROP TABLE IF EXISTS "tiki_sheet_values";

CREATE TABLE "tiki_sheet_values" (
  "sheetId" integer NOT NULL default '0',
  "begin" bigint NOT NULL default '0',
  "end" bigint default NULL,
  "rowIndex" smallint NOT NULL default '0',
  "columnIndex" smallint NOT NULL default '0',
  "value" varchar(255) default NULL,
  "calculation" varchar(255) default NULL,
  "width" smallint NOT NULL default '1',
  "height" smallint NOT NULL default '1',
  "format" varchar(255) default NULL,
  "user" varchar(200) default '',
  UNIQUE (sheetId,begin,rowIndex,columnIndex)
);

CREATE INDEX "_sheetId_2" ON ""("sheetId","rowIndex","columnIndex");

DROP TABLE IF EXISTS "tiki_sheets";

CREATE TABLE "tiki_sheets" (
  "sheetId" serial,
  "title" varchar(200) NOT NULL default '',
  "description" text,
  "author" varchar(200) NOT NULL default '',
  PRIMARY KEY ("sheetId")
);


DROP TABLE IF EXISTS "tiki_shoutbox";

CREATE TABLE "tiki_shoutbox" (
  "msgId" bigserial,
  "message" varchar(255) default NULL,
  "timestamp" bigint default NULL,
  "user" varchar(200) NULL default '',
  "hash" varchar(32) default NULL,
  PRIMARY KEY ("msgId")
) ;


DROP TABLE IF EXISTS "tiki_shoutbox_words";

CREATE TABLE "tiki_shoutbox_words" (
  "word" VARCHAR( 40 ) NOT NULL ,
  "qty" INT DEFAULT '0' NOT NULL ,
  PRIMARY KEY ("word")
);


DROP TABLE IF EXISTS "tiki_structure_versions";

CREATE TABLE "tiki_structure_versions" (
  "structure_id" bigserial,
  "version" bigint default NULL,
  PRIMARY KEY ("structure_id")
) ;


DROP TABLE IF EXISTS "tiki_structures";

CREATE TABLE "tiki_structures" (
  "page_ref_id" bigserial,
  "structure_id" bigint NOT NULL,
  "parent_id" bigint default NULL,
  "page_id" bigint NOT NULL,
  "page_version" integer default NULL,
  "page_alias" varchar(240) NOT NULL default '',
  "pos" smallint default NULL,
  PRIMARY KEY ("page_ref_id")
) ;

CREATE INDEX "_pidpaid" ON ""("page_id","parent_id");
CREATE INDEX "_page_id" ON ""("page_id");

DROP TABLE IF EXISTS "tiki_submissions";

CREATE TABLE "tiki_submissions" (
  "subId" serial,
  "topline" varchar(255) default NULL,
  "title" varchar(255) default NULL,
  "subtitle" varchar(255) default NULL,
  "linkto" varchar(255) default NULL,
  "lang" varchar(16) default NULL,
  "authorName" varchar(60) default NULL,
  "topicId" bigint default NULL,
  "topicName" varchar(40) default NULL,
  "size" bigint default NULL,
  "useImage" char(1) default NULL,
  "image_name" varchar(80) default NULL,
  "image_caption" text default NULL,
  "image_type" varchar(80) default NULL,
  "image_size" bigint default NULL,
  "image_x" smallint default NULL,
  "image_y" smallint default NULL,
  "image_data" bytea,
  "publishDate" bigint default NULL,
  "expireDate" bigint default NULL,
  "created" bigint default NULL,
  "bibliographical_references" text,
  "resume" text,
  "heading" text,
  "body" text,
  "hash" varchar(32) default NULL,
  "author" varchar(200) NOT NULL default '',
  "nbreads" bigint default NULL,
  "votes" integer default NULL,
  "points" bigint default NULL,
  "type" varchar(50) default NULL,
  "rating" decimal(3,2) default NULL,
  "isfloat" char(1) default NULL,
  PRIMARY KEY ("subId")
) ;


DROP TABLE IF EXISTS "tiki_suggested_faq_questions";

CREATE TABLE "tiki_suggested_faq_questions" (
  "sfqId" bigserial,
  "faqId" bigint NOT NULL default '0',
  "question" text,
  "answer" text,
  "created" bigint default NULL,
  "user" varchar(200) NOT NULL default '',
  PRIMARY KEY ("sfqId")
) ;


DROP TABLE IF EXISTS "tiki_survey_question_options";

CREATE TABLE "tiki_survey_question_options" (
  "optionId" bigserial,
  "questionId" bigint NOT NULL default '0',
  "qoption" text,
  "votes" bigint default NULL,
  PRIMARY KEY ("optionId")
) ;


DROP TABLE IF EXISTS "tiki_survey_questions";

CREATE TABLE "tiki_survey_questions" (
  "questionId" bigserial,
  "surveyId" bigint NOT NULL default '0',
  "question" text,
  "options" text,
  "type" char(1) default NULL,
  "position" integer default NULL,
  "votes" bigint default NULL,
  "value" bigint default NULL,
  "average" decimal(4,2) default NULL,
  "mandatory" char(1) NOT NULL default 'n',
  "max_answers" integer NOT NULL default 0,
  "min_answers" integer NOT NULL default 0,
  PRIMARY KEY ("questionId")
) ;


DROP TABLE IF EXISTS "tiki_surveys";

CREATE TABLE "tiki_surveys" (
  "surveyId" bigserial,
  "name" varchar(200) default NULL,
  "description" text,
  "taken" bigint default NULL,
  "lastTaken" bigint default NULL,
  "created" bigint default NULL,
  "status" char(1) default NULL,
  PRIMARY KEY ("surveyId")
) ;


DROP TABLE IF EXISTS "tiki_tags";

CREATE TABLE "tiki_tags" (
  "tagName" varchar(80) NOT NULL default '',
  "pageName" varchar(160) NOT NULL default '',
  "hits" integer default NULL,
  "description" varchar(200) default NULL,
  "data" bytea,
  "lastModif" bigint default NULL,
  "comment" varchar(200) default NULL,
  "version" integer NOT NULL default '0',
  "user" varchar(200) NOT NULL default '',
  "ip" varchar(15) default NULL,
  "flag" char(1) default NULL,
  PRIMARY KEY ("tagName","pageName")
);


DROP TABLE IF EXISTS "tiki_theme_control_categs";

CREATE TABLE "tiki_theme_control_categs" (
  "categId" bigint NOT NULL default '0',
  "theme" varchar(250) NOT NULL default '',
  PRIMARY KEY ("categId")
);


DROP TABLE IF EXISTS "tiki_theme_control_objects";

CREATE TABLE "tiki_theme_control_objects" (
  "objId" varchar(250) NOT NULL default '',
  "type" varchar(250) NOT NULL default '',
  "name" varchar(250) NOT NULL default '',
  "theme" varchar(250) NOT NULL default '',
  PRIMARY KEY ("objId", "type")
);


DROP TABLE IF EXISTS "tiki_theme_control_sections";

CREATE TABLE "tiki_theme_control_sections" (
  "section" varchar(250) NOT NULL default '',
  "theme" varchar(250) NOT NULL default '',
  PRIMARY KEY ("section")
);


DROP TABLE IF EXISTS "tiki_topics";

CREATE TABLE "tiki_topics" (
  "topicId" bigserial,
  "name" varchar(40) default NULL,
  "image_name" varchar(80) default NULL,
  "image_type" varchar(80) default NULL,
  "image_size" bigint default NULL,
  "image_data" bytea,
  "active" char(1) default NULL,
  "created" bigint default NULL,
  PRIMARY KEY ("topicId")
) ;


DROP TABLE IF EXISTS "tiki_tracker_fields";

CREATE TABLE "tiki_tracker_fields" (
  "fieldId" bigserial,
  "trackerId" bigint NOT NULL default '0',
  "name" varchar(255) default NULL,
  "options" text,
  "type" varchar(15) default NULL,
  "isMain" char(1) default NULL,
  "isTblVisible" char(1) default NULL,
  "position" smallint default NULL,
  "isSearchable" char(1) NOT NULL default 'y',
  "isPublic" char(1) NOT NULL default 'n',
  "isHidden" char(1) NOT NULL default 'n',
  "isMandatory" char(1) NOT NULL default 'n',
  "description" text,
  "isMultilingual" char(1) default 'n',
  "itemChoices" text,
  "errorMsg" text,
  "visibleBy" text,
  "editableBy" text,
  "descriptionIsParsed" char(1) default 'n',
  PRIMARY KEY ("fieldId"),
  INDEX trackerId (trackerId)
) ;


DROP TABLE IF EXISTS "tiki_tracker_item_attachments";

CREATE TABLE "tiki_tracker_item_attachments" (
  "attId" bigserial,
  "itemId" bigint NOT NULL default 0,
  "filename" varchar(80) default NULL,
  "filetype" varchar(80) default NULL,
  "filesize" bigint default NULL,
  "user" varchar(200) default NULL,
  "data" bytea,
  "path" varchar(255) default NULL,
  "hits" bigint default NULL,
  "created" bigint default NULL,
  "comment" varchar(250) default NULL,
  "longdesc" bytea,
  "version" varchar(40) default NULL,
  PRIMARY KEY ("attId"),
  INDEX itemId (itemId)
) ;


DROP TABLE IF EXISTS "tiki_tracker_item_comments";

CREATE TABLE "tiki_tracker_item_comments" (
  "commentId" bigserial,
  "itemId" bigint NOT NULL default '0',
  "user" varchar(200) default NULL,
  "data" text,
  "title" varchar(200) default NULL,
  "posted" bigint default NULL,
  PRIMARY KEY ("commentId")
) ;


DROP TABLE IF EXISTS "tiki_tracker_item_fields";

CREATE TABLE "tiki_tracker_item_fields" (
  "itemId" bigint NOT NULL default '0',
  "fieldId" bigint NOT NULL default '0',
  "value" text,
  "lang" char(16) default NULL,
  PRIMARY KEY ("itemId","fieldId","lang"),
  INDEX fieldId (fieldId),
  INDEX value (value(250)),
  INDEX lang (lang)
);


DROP TABLE IF EXISTS "tiki_tracker_items";

CREATE TABLE "tiki_tracker_items" (
  "itemId" bigserial,
  "trackerId" bigint NOT NULL default '0',
  "created" bigint default NULL,
  "status" char(1) default NULL,
  "lastModif" bigint default NULL,
  PRIMARY KEY ("itemId"),
  INDEX trackerId (trackerId)
) ;


DROP TABLE IF EXISTS "tiki_tracker_options";

CREATE TABLE "tiki_tracker_options" (
  "trackerId" bigint NOT NULL default '0',
  "name" varchar(80) NOT NULL default '',
  "value" text default NULL,
  PRIMARY KEY ("trackerId","name")
) ;


DROP TABLE IF EXISTS "tiki_trackers";

CREATE TABLE "tiki_trackers" (
  "trackerId" bigserial,
  "name" varchar(255) default NULL,
  "description" text,
  "descriptionIsParsed" varchar(1) NULL default '0',
  "created" bigint default NULL,
  "lastModif" bigint default NULL,
  "showCreated" char(1) default NULL,
  "showStatus" char(1) default NULL,
  "showLastModif" char(1) default NULL,
  "useComments" char(1) default NULL,
  "useAttachments" char(1) default NULL,
  "showAttachments" char(1) default NULL,
  "items" bigint default NULL,
  "showComments" char(1) default NULL,
  "orderAttachments" varchar(255) NOT NULL default 'filename,created,filesize,hits,desc',
  PRIMARY KEY ("trackerId")
) ;


DROP TABLE IF EXISTS "tiki_untranslated";

CREATE TABLE "tiki_untranslated" (
  "id" bigserial,
  "source" bytea NOT NULL,
  "lang" char(16) NOT NULL default '',
  PRIMARY KEY ("source","lang"),
  UNIQUE (id)
) ;

CREATE INDEX "_id_2" ON ""("id");

DROP TABLE IF EXISTS "tiki_user_answers";

CREATE TABLE "tiki_user_answers" (
  "userResultId" bigint NOT NULL default '0',
  "quizId" bigint NOT NULL default '0',
  "questionId" bigint NOT NULL default '0',
  "optionId" bigint NOT NULL default '0',
  PRIMARY KEY ("userResultId","quizId","questionId","optionId")
);


DROP TABLE IF EXISTS "tiki_user_answers_uploads";

CREATE TABLE "tiki_user_answers_uploads" (
  "answerUploadId" serial,
  "userResultId" bigint NOT NULL default '0',
  "questionId" bigint NOT NULL default '0',
  "filename" varchar(255) NOT NULL default '',
  "filetype" varchar(64) NOT NULL default '',
  "filesize" varchar(255) NOT NULL default '',
  "filecontent" bytea NOT NULL,
  PRIMARY KEY ("answerUploadId")
);


DROP TABLE IF EXISTS "tiki_user_assigned_modules";

CREATE TABLE "tiki_user_assigned_modules" (
  "moduleId" integer NOT NULL,
  "name" varchar(200) NOT NULL default '',
  "position" char(1) default NULL,
  "ord" smallint default NULL,
  "type" char(1) default NULL,
  "user" varchar(200) NOT NULL default '',
  PRIMARY KEY ("name","user","position", "ord")
);


DROP TABLE IF EXISTS "tiki_user_bookmarks_folders";

CREATE TABLE "tiki_user_bookmarks_folders" (
  "folderId" bigserial,
  "parentId" bigint default NULL,
  "user" varchar(200) NOT NULL default '',
  "name" varchar(30) default NULL,
  PRIMARY KEY ("user","folderId")
) ;


DROP TABLE IF EXISTS "tiki_user_bookmarks_urls";

CREATE TABLE "tiki_user_bookmarks_urls" (
  "urlId" bigserial,
  "name" varchar(30) default NULL,
  "url" varchar(250) default NULL,
  "data" bytea,
  "lastUpdated" bigint default NULL,
  "folderId" bigint NOT NULL default '0',
  "user" varchar(200) NOT NULL default '',
  PRIMARY KEY ("urlId")
) ;


DROP TABLE IF EXISTS "tiki_user_mail_accounts";

CREATE TABLE "tiki_user_mail_accounts" (
  "accountId" bigserial,
  "user" varchar(200) NOT NULL default '',
  "account" varchar(50) NOT NULL default '',
  "pop" varchar(255) default NULL,
  "current" char(1) default NULL,
  "port" smallint default NULL,
  "username" varchar(100) default NULL,
  "pass" varchar(100) default NULL,
  "msgs" smallint default NULL,
  "smtp" varchar(255) default NULL,
  "useAuth" char(1) default NULL,
  "smtpPort" smallint default NULL,
  "flagsPublic" char(1) default 'n',				-- COMMENT 'MatWho - Shared Group Mail box if y',
  "autoRefresh" smallint NOT NULL default 0,		-- COMMENT 'seconds for mail list to refresh, 0 = none',
  "imap" varchar( 255 ) default NULL,
  "mbox" varchar( 255 ) default NULL,
  "maildir" varchar( 255 ) default NULL,
  "useSSL" char( 1 ) NOT NULL default 'n',
  PRIMARY KEY ("accountId")
) ;


DROP TABLE IF EXISTS "tiki_user_menus";

CREATE TABLE "tiki_user_menus" (
  "user" varchar(200) NOT NULL default '',
  "menuId" bigserial,
  "url" varchar(250) default NULL,
  "name" varchar(40) default NULL,
  "position" smallint default NULL,
  "mode" char(1) default NULL,
  PRIMARY KEY ("menuId")
) ;


DROP TABLE IF EXISTS "tiki_user_modules";

CREATE TABLE "tiki_user_modules" (
  "name" varchar(200) NOT NULL default '',
  "title" varchar(40) default NULL,
  "data" bytea,
  "parse" char(1) default NULL,
  PRIMARY KEY ("name")
);


INSERT INTO "tiki_user_modules" ("name","title","data","parse") VALUES ('mnu_application_menu', 'Menu', '{menu id=42}', 'n');


DROP TABLE IF EXISTS "tiki_user_notes";

CREATE TABLE "tiki_user_notes" (
  "user" varchar(200) NOT NULL default '',
  "noteId" bigserial,
  "created" bigint default NULL,
  "name" varchar(255) default NULL,
  "lastModif" bigint default NULL,
  "data" text,
  "size" bigint default NULL,
  "parse_mode" varchar(20) default NULL,
  PRIMARY KEY ("noteId")
) ;


DROP TABLE IF EXISTS "tiki_user_postings";

CREATE TABLE "tiki_user_postings" (
  "user" varchar(200) NOT NULL default '',
  "posts" bigint default NULL,
  "last" bigint default NULL,
  "first" bigint default NULL,
  "level" integer default NULL,
  PRIMARY KEY ("user")
);


DROP TABLE IF EXISTS "tiki_user_preferences";

CREATE TABLE "tiki_user_preferences" (
  "user" varchar(200) NOT NULL default '',
  "prefName" varchar(40) NOT NULL default '',
  "value" varchar(250) default NULL,
  PRIMARY KEY ("user","prefName")
);


DROP TABLE IF EXISTS "tiki_user_quizzes";

CREATE TABLE "tiki_user_quizzes" (
  "user" varchar(200) default '',
  "quizId" bigint default NULL,
  "timestamp" bigint default NULL,
  "timeTaken" bigint default NULL,
  "points" bigint default NULL,
  "maxPoints" bigint default NULL,
  "resultId" bigint default NULL,
  "userResultId" bigserial,
  PRIMARY KEY ("userResultId")
) ;


DROP TABLE IF EXISTS "tiki_user_taken_quizzes";

CREATE TABLE "tiki_user_taken_quizzes" (
  "user" varchar(200) NOT NULL default '',
  "quizId" varchar(255) NOT NULL default '',
  PRIMARY KEY ("user","quizId")
);


DROP TABLE IF EXISTS "tiki_user_tasks_history";

CREATE TABLE "tiki_user_tasks_history" (
  "belongs_to" bigint NOT NULL,                   -- the first task in a history it has the same id as the task id
  "task_version" smallint NOT NULL DEFAULT 0,        -- version number for the history it starts with 0
  "title" varchar(250) NOT NULL,                       -- title
  "description" text DEFAULT NULL,                     -- description
  "start" bigint DEFAULT NULL,                    -- date of the starting, if it is not set than there is no starting date
  "end" bigint DEFAULT NULL,                      -- date of the end, if it is not set than there is not dealine
  "lasteditor" varchar(200) NOT NULL,                  -- lasteditor: username of last editior
  "lastchanges" bigint NOT NULL,                  -- date of last changes
  "priority" smallint NOT NULL DEFAULT 3,                     -- priority
  "completed" bigint DEFAULT NULL,                -- date of the completation if it is null it is not yet completed
  "deleted" bigint DEFAULT NULL,                  -- date of the deleteation it it is null it is not deleted
  "status" char(1) DEFAULT NULL,                       -- null := waiting, o := open / in progress, c := completed -> (percentage = 100)
  "percentage" smallint DEFAULT NULL,
  "accepted_creator" char(1) DEFAULT NULL,             -- y - yes, n - no, null - waiting
  "accepted_user" char(1) DEFAULT NULL,                -- y - yes, n - no, null - waiting
  PRIMARY KEY ("belongs_to", "task_version")
) ;


DROP TABLE IF EXISTS "tiki_user_tasks";

CREATE TABLE "tiki_user_tasks" (
  "taskId" bigserial,        -- task id
  "last_version" smallint NOT NULL DEFAULT 0,        -- last version of the task starting with 0
  "user" varchar(200) NOT NULL DEFAULT '',              -- task user
  "creator" varchar(200) NOT NULL,                     -- username of creator
  "public_for_group" varchar(30) DEFAULT NULL,         -- this group can also view the task, if it is null it is not public
  "rights_by_creator" char(1) DEFAULT NULL,            -- null the user can delete the task,
  "created" bigint NOT NULL,                      -- date of the creation
  "status" char(1) default NULL,
  "priority" smallint default NULL,
  "completed" bigint default NULL,
  "percentage" smallint default NULL,
  PRIMARY KEY ("taskId"),
  UNIQUE(creator, created)
);


DROP TABLE IF EXISTS "tiki_user_votings";

CREATE TABLE "tiki_user_votings" (
  "user" varchar(200) default '',
  "ip" varchar(15) default NULL,
  "id" varchar(255) NOT NULL default '',
  "optionId" bigint NOT NULL default 0,
  "time" bigint NOT NULL default 0
);

CREATE INDEX "_" ON ""(\","user"\","id");
CREATE INDEX "_ip" ON ""(\","ip"\");
CREATE INDEX "_id" ON ""(\","id"\");

DROP TABLE IF EXISTS "tiki_user_watches";

CREATE TABLE "tiki_user_watches" (
  "watchId" bigserial,
  "user" varchar(200) NOT NULL default '',
  "event" varchar(40) NOT NULL default '',
  "object" varchar(200) NOT NULL default '',
  "title" varchar(250) default NULL,
  "type" varchar(200) default NULL,
  "url" varchar(250) default NULL,
  "email" varchar(200) default NULL,
  PRIMARY KEY ("user",event,object,email)
);

CREATE INDEX "_watchId" ON ""("watchId");

DROP TABLE IF EXISTS "tiki_userfiles";

CREATE TABLE "tiki_userfiles" (
  "user" varchar(200) NOT NULL default '',
  "fileId" bigserial,
  "name" varchar(200) default NULL,
  "filename" varchar(200) default NULL,
  "filetype" varchar(200) default NULL,
  "filesize" varchar(200) default NULL,
  "data" bytea,
  "hits" integer default NULL,
  "isFile" char(1) default NULL,
  "path" varchar(255) default NULL,
  "created" bigint default NULL,
  PRIMARY KEY ("fileId")
) ;


DROP TABLE IF EXISTS "tiki_userpoints";

CREATE TABLE "tiki_userpoints" (
  "user" varchar(200) NOT NULL default '',
  "points" decimal(8,2) default NULL,
  "voted" integer default NULL
);


DROP TABLE IF EXISTS "tiki_users";

CREATE TABLE "tiki_users" (
  "user" varchar(200) NOT NULL default '',
  "password" varchar(40) default NULL,
  "email" varchar(200) default NULL,
  "lastLogin" bigint default NULL,
  PRIMARY KEY ("user")
);


DROP TABLE IF EXISTS "tiki_webmail_contacts";

CREATE TABLE "tiki_webmail_contacts" (
  "contactId" bigserial,
  "firstName" varchar(80) default NULL,
  "lastName" varchar(80) default NULL,
  "email" varchar(250) default NULL,
  "nickname" varchar(200) default NULL,
  "user" varchar(200) NOT NULL default '',
  PRIMARY KEY ("contactId")
) ;


DROP TABLE IF EXISTS "tiki_webmail_contacts_groups";

CREATE TABLE "tiki_webmail_contacts_groups" (
  "contactId" bigint NOT NULL,
  "groupName" varchar(255) NOT NULL,
  PRIMARY KEY ("contactId","groupName")
) ;


DROP TABLE IF EXISTS "tiki_webmail_messages";

CREATE TABLE "tiki_webmail_messages" (
  "accountId" bigint NOT NULL default '0',
  "mailId" varchar(255) NOT NULL default '',
  "user" varchar(200) NOT NULL default '',
  "isRead" char(1) default NULL,
  "isReplied" char(1) default NULL,
  "isFlagged" char(1) default NULL,
  "flaggedMsg" varchar(50) default '',
  PRIMARY KEY ("accountId","mailId")
);


DROP TABLE IF EXISTS "tiki_wiki_attachments";

CREATE TABLE "tiki_wiki_attachments" (
  "attId" bigserial,
  "page" varchar(200) NOT NULL default '',
  "filename" varchar(80) default NULL,
  "filetype" varchar(80) default NULL,
  "filesize" bigint default NULL,
  "user" varchar(200) NOT NULL default '',
  "data" bytea,
  "path" varchar(255) default NULL,
  "hits" bigint default NULL,
  "created" bigint default NULL,
  "comment" varchar(250) default NULL,
  PRIMARY KEY ("attId")
) ;

CREATE INDEX "_page" ON ""("page");

DROP TABLE IF EXISTS "tiki_zones";

CREATE TABLE "tiki_zones" (
  "zone" varchar(40) NOT NULL default '',
  PRIMARY KEY ("zone")
);


DROP TABLE IF EXISTS "tiki_download";

CREATE TABLE "tiki_download" (
  "id" bigserial,
  "object" varchar(255) NOT NULL default '',
  "userId" integer NOT NULL default '0',
  "type" varchar(20) NOT NULL default '',
  "date" bigint NOT NULL default '0',
  "IP" varchar(50) NOT NULL default '',
  PRIMARY KEY ("id")
);

CREATE INDEX "_object" ON ""("object","userId","type");
CREATE INDEX "_userId" ON ""("userId");
CREATE INDEX "_type" ON ""("type");
CREATE INDEX "_date" ON ""("date");

DROP TABLE IF EXISTS "users_grouppermissions";

CREATE TABLE "users_grouppermissions" (
  "groupName" varchar(255) NOT NULL default '',
  "permName" varchar(40) NOT NULL default '',
  "value" char(1) default '',
  PRIMARY KEY ("groupName","permName")
);



INSERT INTO "users_grouppermissions" ("groupName","permName") VALUES ('Anonymous','tiki_p_view');


DROP TABLE IF EXISTS "users_groups";

CREATE TABLE "users_groups" (
  "id" bigserial,
  "groupName" varchar(255) NOT NULL default '',
  "groupDesc" varchar(255) default NULL,
  "groupHome" varchar(255),
  "usersTrackerId" bigint,
  "groupTrackerId" bigint,
  "usersFieldId" bigint,
  "groupFieldId" bigint,
  "registrationChoice" char(1) default NULL,
  "registrationUsersFieldIds" text,
  "userChoice" char(1) default NULL,
  "groupDefCat" bigint default 0,
  "groupTheme" varchar(255) default '',
  "isExternal" char(1) default 'n',
  PRIMARY KEY ("id"),
  UNIQUE (groupName)
);


DROP TABLE IF EXISTS "users_objectpermissions";

CREATE TABLE "users_objectpermissions" (
  "groupName" varchar(255) NOT NULL default '',
  "permName" varchar(40) NOT NULL default '',
  "objectType" varchar(20) NOT NULL default '',
  "objectId" varchar(32) NOT NULL default '',
  PRIMARY KEY ("objectId", "objectType", "groupName","permName")
);


DROP TABLE IF EXISTS "users_permissions";

CREATE TABLE "users_permissions" (
  "permName" varchar(40) NOT NULL default '',
  "permDesc" varchar(250) default NULL,
  "level" varchar(80) default NULL,
  "type" varchar(20) default NULL,
  "admin" varchar(1) default NULL,
  "feature_check" VARCHAR(50) NULL,
  PRIMARY KEY ("permName")
);

CREATE INDEX "_type" ON ""("type");

INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_calendar', 'Can create/admin calendars', 'admin', 'calendar', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_add_events', 'Can add events in the calendar', 'registered', 'calendar');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_change_events', 'Can change events in the calendar', 'registered', 'calendar');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_calendar', 'Can browse the calendar', 'basic', 'calendar');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_events', 'Can view events details', 'registered', 'calendar');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_tiki_calendar', 'Can view Tikiwiki tools calendar', 'basic', 'calendar');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_chat', 'Administrator, can create channels remove channels etc', 'editors', 'chat', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_chat', 'Can use the chat system', 'registered', 'chat');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_cms', 'Can admin the cms', 'editors', 'cms', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_approve_submission', 'Can approve submissions', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_articles_admin_topics', 'Can admin article topics', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_articles_admin_types', 'Can admin article types', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_articles_read_heading', 'Can read article headings', 'basic', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_autoapprove_submission', 'Submited articles automatically approved', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_article', 'Can edit articles', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_submission', 'Can edit submissions', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_read_article', 'Can read articles', 'basic', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_remove_article', 'Can remove articles', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_remove_submission', 'Can remove submissions', 'editors', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_submit_article', 'Can submit articles', 'basic', 'cms');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_topic_read', 'Can read a topic (Applies only to individual topic perms)', 'basic', 'cms');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_contribution', 'Can admin contributions', 'admin', 'contribution', 'y');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_directory', 'Can admin the directory', 'editors', 'directory', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_directory_cats', 'Can admin directory categories', 'editors', 'directory');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_directory_sites', 'Can admin directory sites', 'editors', 'directory');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_autosubmit_link', 'Submited links are valid', 'editors', 'directory');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_submit_link', 'Can submit sites to the directory', 'basic', 'directory');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_validate_links', 'Can validate submited links', 'editors', 'directory');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_directory', 'Can use the directory', 'basic', 'directory');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_faqs', 'Can admin faqs', 'editors', 'faqs', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_suggest_faq', 'Can suggest faq questions', 'basic', 'faqs');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_faqs', 'Can view faqs', 'basic', 'faqs');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin', 'Administrator, can manage users groups and permissions, Hotwords and all the weblog features', 'admin', 'tiki', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_users', 'Can admin users', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_access_closed_site', 'Can access site when closed', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_banners', 'Administrator, can admin banners', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_banning', 'Can ban users or ips', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_dynamic', 'Can admin the dynamic content system', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_integrator', 'Can admin integrator repositories and rules', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_mailin', 'Can admin mail-in accounts', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_objects','Can edit object permissions', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_rssmodules','Can admin rss modules', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_clean_cache', 'Can clean cache', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_create_css', 'Can create new css suffixed with -user', 'registered', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_detach_translation', 'Can remove association between two pages in a translation set', 'registered', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_cookies', 'Can admin cookies', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_languages', 'Can edit translations and create new languages', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_menu', 'Can edit menu', 'admin', 'menus');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_menu_option', 'Can edit menu option', 'admin', 'menus');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_templates', 'Can edit site templates', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_search', 'Can search', 'basic', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_site_report', 'Can report a link to the webmaster', 'basic', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_subscribe_groups', 'Can subscribe to groups', 'registered', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tell_a_friend', 'Can send a link to a friend', 'Basic', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_use_HTML', 'Can use HTML in pages', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_actionlog', 'Can view action log', 'registered', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_actionlog_owngroups', 'Can view action log for users of his own groups', 'registered', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_integrator', 'Can view integrated repositories', 'basic', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_referer_stats', 'Can view referer stats', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_stats', 'Can view site stats', 'basic', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_templates', 'Can view site templates', 'admin', 'tiki');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_blog_admin', 'Can admin blogs', 'editors', 'blogs', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_assign_perm_blog', 'Can assign perms to blog', 'admin', 'blogs');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_blog_post', 'Can post to a blog', 'registered', 'blogs');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_create_blogs', 'Can create a blog', 'editors', 'blogs');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_read_blog', 'Can read blogs', 'basic', 'blogs');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_file_galleries', 'Can admin file galleries', 'editors', 'file galleries', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_assign_perm_file_gallery', 'Can assign perms to file gallery', 'admin', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_batch_upload_file_dir', 'Can use Directory Batch Load', 'editors', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_batch_upload_files', 'Can upload zip files with files', 'editors', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_create_file_galleries', 'Can create file galleries', 'editors', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_download_files', 'Can download files', 'basic', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_gallery_file', 'Can edit a gallery file', 'editors', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_list_file_galleries', 'Can list file galleries', 'basic', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_upload_files', 'Can upload files', 'registered', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_fgal_explorer', 'Can view file galleries explorer', 'basic', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_fgal_path', 'Can view file galleries path', 'basic', 'file galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_file_gallery', 'Can view file galleries', 'basic', 'file galleries');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_forum', 'Can admin forums', 'editors', 'forums', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forum_attach', 'Can attach to forum posts', 'registered', 'forums');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forum_autoapp', 'Auto approve forum posts', 'editors', 'forums');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forum_edit_own_posts', 'Can edit own forum posts', 'registered', 'forums');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forum_post', 'Can post in forums', 'registered', 'forums');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forum_post_topic', 'Can start threads in forums', 'registered', 'forums');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forum_read', 'Can read forums', 'basic', 'forums');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forums_report', 'Can report msgs to moderator', 'registered', 'forums');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_forum_vote', 'Can vote comments in forums', 'registered', 'forums');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_freetags', 'Can admin freetags', 'admin', 'freetags', 'y');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_galleries', 'Can admin Image Galleries', 'editors', 'image galleries', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_assign_perm_image_gallery', 'Can assign perms to image gallery', 'admin', 'image galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_batch_upload_image_dir', 'Can use Directory Batch Load', 'editors', 'image galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_batch_upload_images', 'Can upload zip files with images', 'editors', 'image galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_create_galleries', 'Can create image galleries', 'editors', 'image galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_freetags_tag', 'Can tag objects', 'registered', 'freetags');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_list_image_galleries', 'Can list image galleries', 'basic', 'image galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_unassign_freetags', 'Can unassign tags from an object', 'basic', 'freetags');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_upload_images', 'Can upload images', 'registered', 'image galleries');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_freetags', 'Can browse freetags', 'basic', 'freetags');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_image_gallery', 'Can view image galleries', 'basic', 'image galleries');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_newsletters', 'Can admin newsletters', 'admin', 'newsletters', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_batch_subscribe_email', 'Can subscribe many e-mails at once (requires tiki_p_subscribe email)', 'editors', 'newsletters');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_send_newsletters', 'Can send newsletters', 'editors', 'newsletters');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_subscribe_email', 'Can subscribe any email to newsletters', 'editors', 'newsletters');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_subscribe_newsletters', 'Can subscribe to newsletters', 'basic', 'newsletters');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_list_newsletters', 'Can list newsletters', 'basic', 'newsletters');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_polls','Can admin polls', 'admin', 'polls', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_poll_results', 'Can view poll results', 'basic', 'polls');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_vote_poll', 'Can vote polls', 'basic', 'polls');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_poll_voters', 'Can view poll voters', 'basic', 'polls');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_quicktags', 'Can admin quicktags', 'admin', 'quicktags', 'y');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_quizzes', 'Can admin quizzes', 'editors', 'quizzes', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_take_quiz', 'Can take quizzes', 'basic', 'quizzes');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_quiz_stats', 'Can view quiz stats', 'basic', 'quizzes');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_user_results', 'Can view user quiz results', 'editors', 'quizzes');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_sheet', 'Can admin sheet', 'admin', 'sheet', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_sheet', 'Can create and edit sheets', 'editors', 'sheet');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_sheet', 'Can view sheet', 'basic', 'sheet');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_sheet_history', 'Can view sheet history', 'admin', 'sheet');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_shoutbox', 'Can admin shoutbox (Edit/remove msgs)', 'editors', 'shoutbox', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_post_shoutbox', 'Can post messages in shoutbox', 'basic', 'shoutbox');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_shoutbox', 'Can view shoutbox', 'basic', 'shoutbox');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_surveys', 'Can admin surveys', 'editors', 'surveys', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_take_survey', 'Can take surveys', 'basic', 'surveys');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_survey_stats', 'Can view survey stats', 'basic', 'surveys');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_trackers', 'Can admin trackers', 'editors', 'trackers', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_attach_trackers', 'Can attach files to tracker items', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_comment_tracker_items', 'Can insert comments for tracker items', 'basic', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tracker_view_comments', 'Can view tracker items comments', 'basic', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_create_tracker_items', 'Can create new items for trackers', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_list_trackers', 'Can list trackers', 'basic', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_modify_tracker_items', 'Can change tracker items', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_modify_tracker_items_pending', 'Can change tracker pending items', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_modify_tracker_items_closed', 'Can change tracker closed items', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tracker_view_ratings', 'Can view rating result for tracker items', 'basic', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tracker_vote_ratings', 'Can vote a rating for tracker items', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_trackers', 'Can view trackers', 'basic', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_trackers_closed', 'Can view trackers closed items', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_trackers_pending', 'Can view trackers pending items', 'editors', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_watch_trackers', 'Can watch tracker', 'registered', 'trackers');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_export_tracker', 'Can export tracker items', 'registered', 'trackers');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_wiki', 'Can admin the wiki', 'editors', 'wiki', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_assign_perm_wiki_page', 'Can assign perms to wiki pages', 'admin', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit', 'Can edit pages', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_copyrights', 'Can edit copyright notices', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_dynvar', 'Can edit dynamic variables', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_structures', 'Can create and edit structures', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_export_wiki', 'Can export wiki pages using the export feature', 'admin', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_lock', 'Can lock pages', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_minor', 'Can save as minor edit', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_remove', 'Can remove', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_rename', 'Can rename pages', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_rollback', 'Can rollback pages', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_upload_picture', 'Can upload pictures to wiki pages', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_use_as_template', 'Can use the page as a tracker template', 'basic', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view', 'Can view page/pages', 'basic', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_watch_structure', 'Can watch structure', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_admin_attachments', 'Can admin attachments to wiki pages', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_admin_ratings', 'Can add and change ratings on wiki pages', 'admin', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_attach_files', 'Can attach files to wiki pages', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_view_attachments', 'Can view wiki attachments and download', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_view_comments', 'Can view wiki comments', 'basic', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_view_history', 'Can view wiki history', 'basic', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_view_ratings', 'Can view rating of wiki pages', 'basic', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_view_source', 'Can view source of wiki pages', 'basic', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_vote_ratings', 'Can participate to rating of wiki pages', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_wiki_view_similar', 'Can view similar wiki pages', 'registered', 'wiki');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_workflow', 'Can admin workflow processes', 'admin', 'workflow', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_abort_instance', 'Can abort a process instance', 'editors', 'workflow');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_exception_instance', 'Can declare an instance as exception', 'registered', 'workflow');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_send_instance', 'Can send instances after completion', 'registered', 'workflow');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_use_workflow', 'Can execute workflow activities', 'registered', 'workflow');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_received_articles', 'Can admin received articles', 'editors', 'comm');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_received_pages', 'Can admin received pages', 'editors', 'comm');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_send_articles', 'Can send articles to other sites', 'editors', 'comm');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_sendme_articles', 'Can send articles to this site', 'registered', 'comm');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_sendme_pages', 'Can send pages to this site', 'registered', 'comm');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_send_pages', 'Can send pages to other sites', 'registered', 'comm');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_tikitests', 'Can admin the TikiTests', 'admin', 'tikitests');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_tikitests', 'Can edit TikiTests', 'editors', 'tikitests');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_play_tikitests', 'Can replay the TikiTests', 'registered', 'tikitests');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_cache_bookmarks', 'Can cache user bookmarks', 'admin', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_configure_modules', 'Can configure modules', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_create_bookmarks', 'Can create user bookmarks', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_minical', 'Can use the mini event calendar', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_notepad', 'Can use the notepad', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tasks_admin', 'Can admin public tasks', 'admin', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tasks', 'Can use tasks', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tasks_receive', 'Can receive tasks from other users', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_tasks_send', 'Can send tasks to other users', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_userfiles', 'Can upload personal files', 'registered', 'user');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_usermenu', 'Can create items in personal menu', 'registered', 'user');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_broadcast_all', 'Can broadcast messages to all user', 'admin', 'messu');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_broadcast', 'Can broadcast messages to groups', 'admin', 'messu');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_messages', 'Can use the messaging system', 'registered', 'messu');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_comments', 'Can admin comments', 'admin', 'comments', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_comments', 'Can edit all comments', 'editors', 'comments');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_post_comments', 'Can post new comments', 'registered', 'comments');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_read_comments', 'Can read comments', 'basic', 'comments');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_remove_comments', 'Can delete comments', 'editors', 'comments');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_vote_comments', 'Can vote comments', 'registered', 'comments');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_content_templates', 'Can admin content templates', 'admin', 'content templates', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_content_templates', 'Can edit content templates', 'editors', 'content templates');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_use_content_templates', 'Can use content templates', 'registered', 'content templates');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_edit_html_pages', 'Can edit HTML pages', 'editors', 'html pages');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_html_pages', 'Can view HTML pages', 'basic', 'html pages');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_list_users', 'Can list registered users', 'registered', 'community');


INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_live_support_admin', 'Admin live support system', 'admin', 'support', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_live_support', 'Can use live support system', 'basic', 'support');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_map_create', 'Can create new mapfile', 'admin', 'maps');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_map_delete', 'Can delete mapfiles', 'admin', 'maps');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_map_edit', 'Can edit mapfiles', 'editors', 'maps');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_map_view', 'Can view mapfiles', 'basic', 'maps');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_map_view_mapfiles', 'Can view contents of mapfiles', 'registered', 'maps');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_use_webmail', 'Can use webmail', 'registered', 'webmail');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_use_group_webmail', 'Can use group webmail', 'registered', 'webmail');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_group_webmail', 'Can administrate group webmail accounts', 'registered', 'webmail');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_use_personal_webmail', 'Can use personal webmail accounts', 'registered', 'webmail');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_personal_webmail', 'Can administrate personal webmail accounts', 'registered', 'webmail');



INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_plugin_viewdetail', 'Can view unapproved plugin details', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_plugin_preview', 'Can execute unapproved plugin', 'registered', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_plugin_approve', 'Can approve plugin execution', 'editors', 'wiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_trust_input', 'Trust all user inputs (no security checks)', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_backlink', 'View page backlinks', 'basic', 'wiki');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_admin_notifications', 'Can admin mail notifications', 'editors', 'mail notifications');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_invite', 'Can invite user in groups', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_delete_account', 'Can delete his own account', 'admin', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_importer', 'Can use the Tiki Importer', 'admin', 'tiki', 'y');

 
 INSERT INTO "users_permissions" ("permName","permDesc","level","type","admin") VALUES ('tiki_p_admin_categories', 'Can admin categories', 'editors', 'category', 'y');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_view_category', 'Can see the category in a listing', 'basic', 'category');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_modify_object_categories', 'Can change the categories on the object', 'editors', 'tiki');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_add_object', 'Can add objects in the category', 'editors', 'category');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_remove_object', 'Can remove objects from the category', 'editors', 'category');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_create_category', 'Can create new categories', 'admin', 'category');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_perspective_view', 'Can view the perspective', 'basic', 'perspective');


INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_group_view', 'Can view the group', 'basic', 'group');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_group_view_members', 'Can view the group members', 'basic', 'group');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_group_add_member', 'Can add group members', 'admin', 'group');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_group_remove_member', 'Can remove group members', 'admin', 'group');

INSERT INTO "users_permissions" ("permName","permDesc","level","type") VALUES ('tiki_p_group_join', 'Can join or leave the group', 'admin', 'group');


UPDATE users_permissions SET feature_check = 'feature_wiki' WHERE permName IN(
	'tiki_p_admin_wiki',
	'tiki_p_assign_perm_wiki_page',
	'tiki_p_edit',
	'tiki_p_lock',
	'tiki_p_minor',
	'tiki_p_remove',
	'tiki_p_rename',
	'tiki_p_rollback',
	'tiki_p_view'
);


UPDATE users_permissions SET feature_check = 'wiki_feature_copyrights' WHERE permName = 'tiki_p_edit_copyrights';

UPDATE users_permissions SET feature_check = 'feature_wiki_structure' WHERE permName = 'tiki_p_edit_structures';

UPDATE users_permissions SET feature_check = 'feature_wiki_structure' WHERE permName = 'tiki_p_watch_structure';

UPDATE users_permissions SET feature_check = 'feature_wiki_pictures' WHERE permName = 'tiki_p_upload_picture';

UPDATE users_permissions SET feature_check = 'feature_wiki_templates' WHERE permName = 'tiki_p_use_as_template';

UPDATE users_permissions SET feature_check = 'feature_wiki_attachments' WHERE permName = 'tiki_p_admin_attachments';

UPDATE users_permissions SET feature_check = 'feature_wiki_attachments' WHERE permName = 'tiki_p_attach_files';

UPDATE users_permissions SET feature_check = 'feature_wiki_attachments' WHERE permName = 'tiki_p_wiki_view_attachments';

UPDATE users_permissions SET feature_check = 'feature_wiki_ratings' WHERE permName = 'tiki_p_admin_ratings';

UPDATE users_permissions SET feature_check = 'feature_wiki_ratings' WHERE permName = 'tiki_p_wiki_view_ratings';

UPDATE users_permissions SET feature_check = 'feature_wiki_ratings' WHERE permName = 'tiki_p_wiki_vote_ratings';

UPDATE users_permissions SET feature_check = 'feature_wiki_comments' WHERE permName = 'tiki_p_wiki_view_comments';

UPDATE users_permissions SET feature_check = 'feature_wiki_export' WHERE permName = 'tiki_p_export_wiki';

UPDATE users_permissions SET feature_check = 'feature_history' WHERE permName = 'tiki_p_wiki_view_history';

UPDATE users_permissions SET feature_check = 'feature_wiki_attachments' WHERE permName = 'tiki_p_wiki_attach_files';

UPDATE users_permissions SET feature_check = 'feature_wiki_attachments' WHERE permName = 'tiki_p_wiki_admin_attachments';

UPDATE users_permissions SET feature_check = 'feature_wiki_ratings' WHERE permName = 'tiki_p_wiki_admin_ratings';

UPDATE users_permissions SET feature_check = 'feature_source' WHERE permName = 'tiki_p_wiki_view_source';





DROP TABLE IF EXISTS "users_usergroups";

CREATE TABLE "users_usergroups" (
  "userId" integer NOT NULL default '0',
  "groupName" varchar(255) NOT NULL default '',
  PRIMARY KEY ("userId","groupName")
);


INSERT INTO "users_groups" ("groupName","groupDesc") VALUES ('Anonymous','Public users not logged');

INSERT INTO "users_groups" ("groupName","groupDesc") VALUES ('Registered','Users logged into the system');

INSERT INTO "users_groups" ("groupName","groupDesc") VALUES ('Admins','Administrator and accounts managers.');


DROP TABLE IF EXISTS "users_users";

CREATE TABLE "users_users" (
  "userId" serial,
  "email" varchar(200) default NULL,
  "login" varchar(200) NOT NULL default '',
  "password" varchar(30) default '',
  "provpass" varchar(30) default NULL,
  "default_group" varchar(255),
  "lastLogin" bigint default NULL,
  "currentLogin" bigint default NULL,
  "registrationDate" bigint default NULL,
  "challenge" varchar(32) default NULL,
  "pass_confirm" bigint default NULL,
  "email_confirm" bigint default NULL,
  "hash" varchar(34) default NULL,
  "created" bigint default NULL,
  "avatarName" varchar(80) default NULL,
  "avatarSize" bigint default NULL,
  "avatarFileType" varchar(250) default NULL,
  "avatarData" bytea,
  "avatarLibName" varchar(200) default NULL,
  "avatarType" char(1) default NULL,
  "score" bigint NOT NULL default 0,
  "valid" varchar(32) default NULL,
  "unsuccessful_logins" bigint default 0,
  "openid_url" varchar(255) default NULL,
  "waiting" char(1) default NULL,
  PRIMARY KEY ("userId")
) ;

CREATE INDEX "_score" ON ""("score");
CREATE INDEX "_login" ON ""("login");
CREATE INDEX "_registrationDate" ON ""("registrationDate");
CREATE INDEX "_openid_url" ON ""("openid_url");

-- Administrator account
INSERT INTO "users_users" ("email","login","password","hash") VALUES ('','admin','admin','f6fdffe48c908deb0f4c3bd36c032e72');

UPDATE "users_users" SET "currentLogin"="lastLogin","registrationDate"="lastLogin";

INSERT INTO "tiki_user_preferences" ("user","prefName","value") VALUES ('admin','realName','System Administrator');

INSERT INTO "users_usergroups" ("userId","groupName") VALUES (1,'Admins');

INSERT INTO "users_grouppermissions" ("groupName","permName") VALUES ('Admins','tiki_p_admin');


DROP TABLE IF EXISTS "tiki_integrator_reps";

CREATE TABLE "tiki_integrator_reps" (
  "repID" bigserial,
  "name" varchar(255) NOT NULL default '',
  "path" varchar(255) NOT NULL default '',
  "start_page" varchar(255) NOT NULL default '',
  "css_file" varchar(255) NOT NULL default '',
  "visibility" char(1) NOT NULL default 'y',
  "cacheable" char(1) NOT NULL default 'y',
  "expiration" bigint NOT NULL default '0',
  "description" text NOT NULL,
  PRIMARY KEY ("repID")
);


INSERT INTO tiki_integrator_reps VALUES ('1','Doxygened (1.3.4) Documentation','','index.html','doxygen.css','n','y','0','Use this repository as rule source for all your repositories based on doxygened docs. To setup yours just add new repository and copy rules from this repository :)');


DROP TABLE IF EXISTS "tiki_integrator_rules";

CREATE TABLE "tiki_integrator_rules" (
  "ruleID" bigserial,
  "repID" bigint NOT NULL default '0',
  "ord" smallint NOT NULL default '0',
  "srch" bytea NOT NULL,
  "repl" bytea NOT NULL,
  "type" char(1) NOT NULL default 'n',
  "casesense" char(1) NOT NULL default 'y',
  "rxmod" varchar(20) NOT NULL default '',
  "enabled" char(1) NOT NULL default 'n',
  "description" text NOT NULL,
  PRIMARY KEY ("ruleID")
);

CREATE INDEX "_repID" ON ""("repID");

INSERT INTO tiki_integrator_rules VALUES ('1','1','1','.*<body[^>]*?>(.*?)</body.*','\1','y','n','i','y','Extract code between <body> and </body> tags');

INSERT INTO tiki_integrator_rules VALUES ('2','1','2','img src=(\"|\')(?!http://)','img src=\1{path}/','y','n','i','y','Fix image paths');

INSERT INTO tiki_integrator_rules VALUES ('3','1','3','href=(\"|\')(?!(#|(http|ftp)://))','href=\1tiki-integrator.php?repID={repID}&file=','y','n','i','y','Replace internal links to integrator. Don\'t touch an external link.');


DROP TABLE IF EXISTS "tiki_quicktags";

CREATE TABLE "tiki_quicktags" (
  "tagId" serial,
  "taglabel" varchar(255) default NULL,
  "taginsert" text,
  "tagicon" varchar(255) default NULL,
  "tagcategory" varchar(255) default NULL,
  PRIMARY KEY ("tagId")
) ;

CREATE INDEX "_tagcategory" ON ""("tagcategory");
CREATE INDEX "_taglabel" ON ""("taglabel");

-- wiki
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('table new','||r1c1|r1c2|r1c3\nr2c1|r2c2|r2c3\nr3c1|r3c2|r3c3||','pics/icons/table.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('link, external','[http://example.com|text]','pics/icons/world_link.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('link, wiki','((text))','pics/icons/page_link.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('title bar','-=text=-','pics/icons/text_padding_top.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('box','^text^','pics/icons/box.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule','---','pics/icons/page.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('dynamic variable','%text%','pics/icons/database_gear.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('list bullets', '*text', 'pics/icons/text_list_bullets.png', 'wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('list numbers', '#text', 'pics/icons/text_list_numbers.png', 'wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('quote','popup_plugin_form("quote")','pics/icons/quotes.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('code','popup_plugin_form("code")','pics/icons/page_white_code.png','wiki');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('flash','popup_plugin_form("flash")','pics/icons/page_white_actionscript.png','wiki');


-- maps
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New wms Metadata','METADATA\r\n		\"wms_name\" \"myname\"\r\n 	"wms_srs" "EPSG:4326"\r\n 	"wms_server_version" " "\r\n 	"wms_layers" "mylayers"\r\n 	"wms_request" "myrequest"\r\n 	"wms_format" " "\r\n 	"wms_time" " "\r\n END', 'pics/icons/tag_blue_add.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Class', 'CLASS\r\n EXPRESSION ()\r\n SYMBOL 0\r\n OUTLINECOLOR\r\n COLOR\r\n NAME "myclass" \r\nEND #end of class', 'pics/icons/application_add.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Projection','PROJECTION\r\n "init=epsg:4326"\r\nEND','pics/icons/image_add.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Query','#\r\n# Start of query definitions\r\n#\r\n QUERYMAP\r\n STATUS ON\r\n STYLE HILITE\r\nEND','pics/icons/database_gear.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Scalebar','#\r\n# Start of scalebar\r\n#\r\nSCALEBAR\r\n IMAGECOLOR 255 255 255\r\n STYLE 1\r\n SIZE 400 2\r\n COLOR 0 0 0\r\n UNITS KILOMETERS\r\n INTERVALS 5\r\n STATUS ON\r\nEND','pics/icons/layout_add.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Layer','LAYER\r\n NAME\r\n TYPE\r\n STATUS ON\r\n DATA "mydata"\r\nEND #end of layer', 'pics/icons/layers.png', 'maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Label','LABEL\r\n COLOR\r\n ANGLE\r\n FONT arial\r\n TYPE TRUETYPE\r\n POSITION\r\n PARTIALS TRUE\r\n SIZE 6\r\n BUFFER 0\r\n OUTLINECOLOR \r\nEND #end of label', 'pics/icons/comment_add.png', 'maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Reference','#\r\n#start of reference\r\n#\r\n REFERENCE\r\n SIZE 120 60\r\n STATUS ON\r\n EXTENT -180 -90 182 88\r\n OUTLINECOLOR 255 0 0\r\n IMAGE "myimagedata"\r\n COLOR -1 -1 -1\r\nEND','pics/icons/picture_add.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Legend','#\r\n#start of Legend\r\n#\r\n LEGEND\r\n KEYSIZE 18 12\r\n POSTLABELCACHE TRUE\r\n STATUS ON\r\nEND','pics/icons/note_add.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Web','#\r\n# Start of web interface definition\r\n#\r\nWEB\r\n TEMPLATE "myfile/url"\r\n MINSCALE 1000\r\n MAXSCALE 40000\r\n IMAGEPATH "myimagepath"\r\n IMAGEURL "mypath"\r\nEND', 'pics/icons/world_link.png', 'maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Outputformat','OUTPUTFORMAT\r\n NAME\r\n DRIVER " "\r\n MIMETYPE "myimagetype"\r\n IMAGEMODE RGB\r\n EXTENSION "png"\r\nEND','pics/icons/newspaper_go.png','maps');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('New Mapfile','#\r\n# Start of mapfile\r\n#\r\nNAME MYMAPFLE\r\n STATUS ON\r\nSIZE \r\nEXTENT\r\nUNITS \r\nSHAPEPATH " "\r\nIMAGETYPE " "\r\nFONTSET " "\r\nIMAGECOLOR -1 -1 -1\r\n\r\n#remove this text and add objects here\r\n\r\nEND # end of mapfile','pics/icons/world_add.png','maps');


-- newsletters
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('external link','[http://example.com|text|nocache]','pics/icons/world_link.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule', '---', 'pics/icons/page.png', 'newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','newsletters');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','newsletters');


-- trackers
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('table new','||r1c1|r1c2|r1c3\nr2c1|r2c2|r2c3\nr3c1|r3c2|r3c3||','pics/icons/table.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('external link','[http://example.com|text]','pics/icons/world_link.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('wiki link','((text))','pics/icons/page_link.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('title bar','-=text=-','pics/icons/text_padding_top.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('box','^text^','pics/icons/box.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule','---','pics/icons/page.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('dynamic variable','%text%','pics/icons/database_gear.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('quote','popup_plugin_form("quote")','pics/icons/quotes.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('code','popup_plugin_form("code")','pics/icons/page_white_code.png','trackers');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('flash','popup_plugin_form("flash")','pics/icons/page_white_actionscript.png','trackers');


-- blogs
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('table new','||r1c1|r1c2|r1c3\nr2c1|r2c2|r2c3\nr3c1|r3c2|r3c3||','pics/icons/table.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('external link','[http://example.com|text]','pics/icons/world_link.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('wiki link','((text))','pics/icons/page_link.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('title bar','-=text=-','pics/icons/text_padding_top.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('box','^text^','pics/icons/box.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule','---','pics/icons/page.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('dynamic variable','%text%','pics/icons/database_gear.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('quote','popup_plugin_form("quote")','pics/icons/quotes.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('code','popup_plugin_form("code")','pics/icons/page_white_code.png','blogs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('flash','popup_plugin_form("flash")','pics/icons/page_white_actionscript.png','blogs');


-- calendar
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('table new','||r1c1|r1c2|r1c3\nr2c1|r2c2|r2c3\nr3c1|r3c2|r3c3||','pics/icons/table.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('external link','[http://example.com|text]','pics/icons/world_link.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('wiki link','((text))','pics/icons/page_link.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('title bar','-=text=-','pics/icons/text_padding_top.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('box','^text^','pics/icons/box.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule','---','pics/icons/page.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('dynamic variable','%text%','pics/icons/database_gear.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','calendar');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','calendar');


-- articles
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('table new','||r1c1|r1c2|r1c3\nr2c1|r2c2|r2c3\nr3c1|r3c2|r3c3||','pics/icons/table.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('external link','[http://example.com|text]','pics/icons/world_link.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('wiki link','((text))','pics/icons/page_link.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('title bar','-=text=-','pics/icons/text_padding_top.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('box','^text^','pics/icons/box.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule','---','pics/icons/page.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('dynamic variable','%text%','pics/icons/database_gear.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('quote','popup_plugin_form("quote")','pics/icons/quotes.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('code','popup_plugin_form("code")','pics/icons/page_white_code.png','articles');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('flash','popup_plugin_form("flash")','pics/icons/page_white_actionscript.png','articles');


-- faqs
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('table new','||r1c1|r1c2|r1c3\nr2c1|r2c2|r2c3\nr3c1|r3c2|r3c3||','pics/icons/table.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('external link','[http://example.com|text]','pics/icons/world_link.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('wiki link','((text))','pics/icons/page_link.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('title bar','-=text=-','pics/icons/text_padding_top.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('box','^text^','pics/icons/box.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule','---','pics/icons/page.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('dynamic variable','%text%','pics/icons/database_gear.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','faqs');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','faqs');


-- forums
INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, bold','__text__','pics/icons/text_bold.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, italic','\'\'text\'\'','pics/icons/text_italic.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('  text, underline','===text===','pics/icons/text_underline.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('table new','||r1c1|r1c2|r1c3\nr2c1|r2c2|r2c3\nr3c1|r3c2|r3c3||','pics/icons/table.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('external link','[http://example.com|text]','pics/icons/world_link.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('wiki link','((text))','pics/icons/page_link.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading1','!text','pics/icons/text_heading_1.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading2','!!text','pics/icons/text_heading_2.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' heading3','!!!text','pics/icons/text_heading_3.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('title bar','-=text=-','pics/icons/text_padding_top.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('box','^text^','pics/icons/box.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' horizontal rule','---','pics/icons/page.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('center text','::text::','pics/icons/text_align_center.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' colored text','~~#FF0000:text~~','pics/icons/palette.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('dynamic variable','%text%','pics/icons/database_gear.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('image','popup_plugin_form("img")','pics/icons/picture.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES (' deleted','--text--','pics/icons/text_strikethrough.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('quote','popup_plugin_form("quote")','pics/icons/quotes.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('code','popup_plugin_form("code")','pics/icons/page_white_code.png','forums');

INSERT INTO "tiki_quicktags" ("taglabel","taginsert","tagicon","tagcategory") VALUES ('flash','popup_plugin_form("flash")','pics/icons/page_white_actionscript.png','forums');


-- Translated objects table
DROP TABLE IF EXISTS "tiki_translated_objects";

CREATE TABLE "tiki_translated_objects" (
  "traId" bigserial,
  "type" varchar(50) NOT NULL,
  "objId" varchar(255) NOT NULL,
  "lang" varchar(16) default NULL,
  PRIMARY KEY ("type", "objId")
);

CREATE INDEX "_traId" ON ""("traId");

DROP TABLE IF EXISTS "tiki_friends";

CREATE TABLE "tiki_friends" (
  "user" varchar(200) NOT NULL default '',
  "friend" varchar(200) NOT NULL default '',
  PRIMARY KEY ("user",friend)
);


DROP TABLE IF EXISTS "tiki_friendship_requests";

CREATE TABLE "tiki_friendship_requests" (
  "userFrom" varchar(200) NOT NULL default '',
  "userTo" varchar(200) NOT NULL default '',
  "tstamp" timestamp(3) NOT NULL,
  PRIMARY KEY ("userFrom","userTo")
);


DROP TABLE IF EXISTS "tiki_score";

CREATE TABLE "tiki_score" (
  event varchar(40) NOT NULL default '',
  score bigint NOT NULL default '0',
  expiration bigint NOT NULL default '0',
  PRIMARY KEY ("event")
);


INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('login',1,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('login_remain',2,60);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('profile_fill',10,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('profile_see',2,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('profile_is_seen',1,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('friend_new',10,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('message_receive',1,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('message_send',2,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('article_read',2,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('article_comment',5,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('article_new',20,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('article_is_read',1,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('article_is_commented',2,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('fgallery_new',10,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('fgallery_new_file',10,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('fgallery_download',5,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('fgallery_is_downloaded',5,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('igallery_new',10,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('igallery_new_img',6,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('igallery_see_img',3,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('igallery_img_seen',1,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('blog_new',20,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('blog_post',5,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('blog_read',2,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('blog_comment',2,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('blog_is_read',3,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('blog_is_commented',3,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('wiki_new',10,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('wiki_edit',5,0);

INSERT INTO "tiki_score" ("event","score","expiration") VALUES ('wiki_attach_file',3,0);


DROP TABLE IF EXISTS "tiki_users_score";

CREATE TABLE "tiki_users_score" (
  "user" char(200) NOT NULL default '',
  "event_id" char(200) NOT NULL default '',
  "expire" bigint NOT NULL default '0',
  "tstamp" timestamp(3) NOT NULL,
  PRIMARY KEY ("user","event_id")
);

CREATE INDEX "_user" ON ""(substr("user", 0, 110)substr("event_id", 0, 110),",substrexpire");

DROP TABLE IF EXISTS "tiki_file_handlers";

CREATE TABLE "tiki_file_handlers" (
  "mime_type" varchar(64) default NULL,
  "cmd" varchar(238) default NULL
);


DROP TABLE IF EXISTS "tiki_stats";

CREATE TABLE "tiki_stats" (
  "object" varchar(255) NOT NULL default '',
  "type" varchar(20) NOT NULL default '',
  "day" bigint NOT NULL default '0',
  "hits" bigint NOT NULL default '0',
  PRIMARY KEY ("object","type","day")
);


DROP TABLE IF EXISTS "tiki_events";

CREATE TABLE "tiki_events" (
  "callback_type" smallint NOT NULL default '3',
  "order" smallint NOT NULL default '50',
  "event" varchar(200) NOT NULL default '',
  "file" varchar(200) NOT NULL default '',
  "object" varchar(200) NOT NULL default '',
  "method" varchar(200) NOT NULL default '',
  PRIMARY KEY ("callback_type","order")
);


INSERT INTO "tiki_events" ("callback_type","\"order\",""event","file","object","method") VALUES ('1', '20', 'user_registers', 'lib/registration/registrationlib.php', 'registrationlib', 'callback_tikiwiki_setup_custom_fields');

INSERT INTO "tiki_events" ("event","file","object","method") VALUES ('user_registers', 'lib/registration/registrationlib.php', 'registrationlib', 'callback_tikiwiki_save_registration');

INSERT INTO "tiki_events" ("callback_type","\"order\",""event","file","object","method") VALUES ('5', '20', 'user_registers', 'lib/registration/registrationlib.php', 'registrationlib', 'callback_logslib_user_registers');

INSERT INTO "tiki_events" ("callback_type","\"order\",""event","file","object","method") VALUES ('5', '25', 'user_registers', 'lib/registration/registrationlib.php', 'registrationlib', 'callback_tikiwiki_send_email');

INSERT INTO "tiki_events" ("callback_type","\"order\",""event","file","object","method") VALUES ('5', '30', 'user_registers', 'lib/registration/registrationlib.php', 'registrationlib', 'callback_tikimail_user_registers');


DROP TABLE IF EXISTS "tiki_registration_fields";

CREATE TABLE "tiki_registration_fields" (
  "id" bigserial,
  "field" varchar(255) NOT NULL default '',
  "name" varchar(255) default NULL,
  "type" varchar(255) NOT NULL default 'text',
  "show" smallint NOT NULL default '1',
  "size" varchar(10) default '10',
  PRIMARY KEY ("id")
);


DROP TABLE IF EXISTS "tiki_actionlog_conf";

CREATE TABLE "tiki_actionlog_conf" (
  "id" bigserial,
  "action" varchar(32) NOT NULL default '',
  "objectType" varchar(32) NOT NULL default '',
  "status" char(1) default '',
  PRIMARY KEY ("action", "objectType")
);

CREATE INDEX "_" ON ""("id");

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Created', 'wiki page', 'y');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Updated', 'wiki page', 'y');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed', 'wiki page', 'y');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'wiki page', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'forum', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Posted', 'forum', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Replied', 'forum', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Updated', 'forum', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'file gallery', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'image gallery', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Uploaded', 'file gallery', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Uploaded', 'image gallery', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('*', 'category', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('*', 'login', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Posted', 'message', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Replied', 'message', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'message', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed version', 'wiki page', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed last version', 'wiki page', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Rollback', 'wiki page', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed', 'forum', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Downloaded', 'file gallery', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Posted', 'comment', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Replied', 'comment', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Updated', 'comment', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed', 'comment', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Renamed', 'wiki page', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Created', 'sheet', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Updated', 'sheet', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed', 'sheet', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'sheet', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'blog', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Posted', 'blog', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Updated', 'blog', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed', 'blog', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Removed', 'file', 'n');

INSERT INTO "tiki_actionlog_conf" ("action","objectType","status") VALUES ('Viewed', 'article', 'n');


DROP TABLE IF EXISTS "tiki_freetags";

CREATE TABLE "tiki_freetags" (
  "tagId" bigserial,
  "tag" varchar(30) NOT NULL default '',
  "raw_tag" varchar(50) NOT NULL default '',
  "lang" varchar(16) NULL,
  PRIMARY KEY ("tagId")
);


DROP TABLE IF EXISTS "tiki_freetagged_objects";

CREATE TABLE "tiki_freetagged_objects" (
  "tagId" bigserial,
  "objectId" bigint NOT NULL default 0,
  "user" varchar(200) default '',
  "created" bigint NOT NULL default '0',
  PRIMARY KEY ("tagId","user","objectId")
);

CREATE INDEX "_" ON ""("tagId");
CREATE INDEX "_" ON ""("user");
CREATE INDEX "_" ON ""("objectId");

DROP TABLE IF EXISTS "tiki_contributions";

CREATE TABLE "tiki_contributions" (
  "contributionId" bigserial,
  "name" varchar(100) default NULL,
  "description" varchar(250) default NULL,
  PRIMARY KEY ("contributionId")
);


DROP TABLE IF EXISTS "tiki_contributions_assigned";

CREATE TABLE "tiki_contributions_assigned" (
  "contributionId" bigint NOT NULL,
  "objectId" bigint NOT NULL,
  PRIMARY KEY ("objectId", "contributionId")
);


DROP TABLE IF EXISTS "tiki_webmail_contacts_ext";

CREATE TABLE "tiki_webmail_contacts_ext" (
  "contactId" bigint NOT NULL,
  "value" varchar(255) NOT NULL,
  "hidden" smallint NOT NULL,
  "fieldId" bigint NOT NULL
);

CREATE INDEX "_contactId" ON ""(\","contactId"\");

DROP TABLE IF EXISTS "tiki_webmail_contacts_fields";

CREATE TABLE "tiki_webmail_contacts_fields" (
  "user" VARCHAR( 200 ) NOT NULL ,
  "fieldname" VARCHAR( 255 ) NOT NULL ,
  "order" smallint NOT NULL default '0',
  "show" char(1) NOT NULL default 'n',
  "fieldId" bigserial,
  "flagsPublic" CHAR( 1 ) NOT NULL DEFAULT 'n',
  PRIMARY KEY ( "fieldId" ),
  INDEX ( "user" )
) ENGINE = MyISAM ;


DROP TABLE IF EXISTS "tiki_pages_translation_bits";

CREATE TABLE "tiki_pages_translation_bits" (
  "translation_bit_id" bigserial,
  "page_id" bigint NOT NULL,
  "version" integer NOT NULL,
  "source_translation_bit" bigint NULL,
  "original_translation_bit" bigint NULL,
  "flags" SET('critical') NULL DEFAULT '',
  PRIMARY KEY ("translation_bit_id"),
  KEY("page_id"),
  KEY("original_translation_bit"),
  KEY("source_translation_bit")
);


DROP TABLE IF EXISTS "tiki_pages_changes";

CREATE TABLE "tiki_pages_changes" (
  "page_id" bigint,
  "version" bigint,
  "segments_added" bigint,
  "segments_removed" bigint,
  "segments_total" bigint,
  PRIMARY KEY(page_id, version)
);


DROP TABLE IF EXISTS "tiki_minichat";

CREATE TABLE "tiki_minichat" (
  "id" bigserial,
  "channel" varchar(31),
  "ts" bigint NOT NULL,
  "user" varchar(31) default NULL,
  "nick" varchar(31) default NULL,
  "msg" varchar(255) NOT NULL,
  PRIMARY KEY ("id")
);

CREATE INDEX "_channel" ON ""(\","channel"\");

DROP TABLE IF EXISTS "tiki_profile_symbols";

CREATE TABLE "tiki_profile_symbols" (
  "domain" VARCHAR(50) NOT NULL,
  "profile" VARCHAR(50) NOT NULL,
  "object" VARCHAR(50) NOT NULL,
  "type" VARCHAR(20) NOT NULL,
  "value" VARCHAR(50) NOT NULL,
  "named" ENUM('y','n') NOT NULL,
  "creation_date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ( "domain", "profile", "object" ),
  INDEX("named")
);


DROP TABLE IF EXISTS "tiki_feature";

CREATE TABLE "tiki_feature" (
  "feature_id" mediumserial,
  "feature_name" varchar(150) NOT NULL,
  "parent_id" mediuminteger NOT NULL,
  "status" varchar(12) NOT NULL default 'active',
  "setting_name" varchar(50) default NULL,
  "feature_type" varchar(30) NOT NULL default 'feature',
  "template" varchar(50) default NULL,
  "permission" varchar(50) default NULL,
  "ordinal" mediuminteger NOT NULL default '1',
  "depends_on" mediuminteger default NULL,
  "keyword" varchar(30) default NULL,
  "tip" text NULL,
  "feature_count" mediuminteger NOT NULL default '0',
  "feature_path" varchar(20) NOT NULL default '0',
  PRIMARY KEY ("feature_id")
);


DROP TABLE IF EXISTS "tiki_schema";

CREATE TABLE "tiki_schema" (
  "patch_name" VARCHAR(100) PRIMARY KEY,
  "install_date" TIMESTAMP
);


DROP TABLE IF EXISTS "tiki_semantic_tokens";

CREATE TABLE "tiki_semantic_tokens" (
  "token" VARCHAR(15) PRIMARY KEY,
  "label" VARCHAR(25) NOT NULL,
  "invert_token" VARCHAR(15)
) ;


INSERT INTO "tiki_semantic_tokens" ("token","label") VALUES ('alias', 'Page Alias');



DROP TABLE IF EXISTS "tiki_webservice";

CREATE TABLE "tiki_webservice" (
  "service" VARCHAR(25) NOT NULL PRIMARY KEY,
  "url" VARCHAR(250),
  "body" TEXT,
  "schema_version" VARCHAR(5),
  "schema_documentation" VARCHAR(250)
) ;


DROP TABLE IF EXISTS "tiki_webservice_template";

CREATE TABLE "tiki_webservice_template" (
  "service" VARCHAR(25) NOT NULL,
  "template" VARCHAR(25) NOT NULL,
  "engine" VARCHAR(15) NOT NULL,
  "output" VARCHAR(15) NOT NULL,
  "content" TEXT NOT NULL,
  "last_modif" INT,
  PRIMARY KEY( service, template )
) ;


DROP TABLE IF EXISTS "tiki_groupalert";


CREATE TABLE "tiki_groupalert" (
  "groupName" varchar(255) NOT NULL default '',
  "objectType" varchar( 20 ) NOT NULL default '',
  "objectId" varchar(10) NOT NULL default '',
  "displayEachuser" char( 1 ) default NULL ,
  PRIMARY KEY ( "objectType", "objectId" )
) ;


DROP TABLE IF EXISTS "tiki_sent_newsletters_files";

CREATE TABLE "tiki_sent_newsletters_files" (
  "id" bigserial,
  "editionId" bigint NOT NULL,
  "name" varchar(256) NOT NULL,
  "type" varchar(64) NOT NULL,
  "size" bigint NOT NULL,
  "filename" varchar(256) NOT NULL,
  PRIMARY KEY  ("id")
);

CREATE INDEX "_editionId" ON ""(\","editionId"\");

DROP TABLE IF EXISTS "tiki_sefurl_regex_out";

CREATE TABLE "tiki_sefurl_regex_out" (
  "id" bigserial,
  "left" varchar(256) NOT NULL,
  "right" varchar(256) NULL default NULL,
  "type" varchar(32) NULL default NULL,
  "silent" char(1) NULL default 'n',
  "feature" varchar(256) NULL default NULL,
  "comment" varchar(256),
  "order" bigint NULL default 0,
  PRIMARY KEY("id"),
  UNIQUE ("left"),
  INDEX "idx1" (silent, type, feature(30))
);


INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-index.php\\?page=(.+)', '$1', 'wiki', 'feature_wiki');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-slideshow.php\\?page=(.+)', 'show:$1', '', 'feature_wiki');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-read_article.php\\?articleId=(\\d+)', 'article$1', 'article', 'feature_articles');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-browse_categories.php\\?parentId=(\\d+)', 'cat$1', 'category', 'feature_categories');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-view_blog.php\\?blogId=(\\d+)', 'blog$1', 'blog', 'feature_blogs');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-view_blog_post.php\\?postId=(\\d+)', 'blogpost$1', 'blogpost', 'feature_blogs');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-browse_image.php\\?imageId=(\\d+)', 'browseimage$1', 'image', 'feature_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-directory_browse.php\\?parent=(\\d+)', 'directory$1', 'directory', 'feature_directory');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-view_faq.php\\?faqId=(\\d+)', 'faq$1', 'faq', 'feature_faqs');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-list_file_gallery.php\\?galleryId=(\\d+)', 'file$1', 'file', 'feature_file_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-download_file.php\\?fileId=(\\d+)', 'dl$1', 'file', 'feature_file_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-download_file.php\\?fileId=(\\d+)&amp;thumbnail', 'thumbnail$1', 'thumbnail', 'feature_file_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-download_file.php\\?fileId=(\\d+)&amp;display', 'display$1', 'display', 'feature_file_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-download_file.php\\?fileId=(\\d+)&amp;preview', 'preview$1', 'preview', 'feature_file_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-view_forum.php\\?forumId=(\\d+)', 'forum$1', 'forum', 'feature_forums');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-browse_gallery.php\\?galleryId=(\\d+)', 'gallery$1', 'gallery', 'feature_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('show_image.php\\?id=(\\d+)', 'image$1', 'image', 'feature_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('show_image.php\\?id=(\\d+)&scalesize=(\\d+)', 'imagescale$1/$2', 'image', 'feature_galleries');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-newsletters.php\\?nlId=(\\d+)', 'newsletter$1', 'newsletter', 'feature_newsletters');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-take_quiz.php\\?quizId=(\\d+)', 'quiz$1', 'quiz', 'feature_quizzes');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-take_survey.php\\?surveyId=(\\d+)', 'survey$1', 'survey', 'feature_surveys');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-view_tracker.php\\?trackerId=(\\d+)', 'tracker$1', 'tracker', 'feature_trackers');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-integrator.php\\?repID=(\\d+)', 'int$1', '', 'feature_integrator');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-view_sheets.php\\?sheetId=(\\d+)', 'sheet$1', 'sheet', 'feature_sheet');

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",") VALUES ('tiki-directory_redirect.php\\?siteId=(\\d+)', 'dirlink$1', 'directory', 'feature_directory');

INSERT INTO "," ("\"left\",""\"right\",""\"comment\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php\\?calIds\\[\\]=(\\d+)\&calIds\\[\\]=(\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)', 'cal$1,$2,$3,$4,$5,$6,$7', '7', 'calendar', 'feature_calendar', 100);

INSERT INTO "," ("\"left\",""\"right\",""\"comment\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php\\?calIds\\[\\]=(\\d+)\&calIds\\[\\]=(\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)', 'cal$1,$2,$3,$4,$5,$6', '6', 'calendar', 'feature_calendar', 101);

INSERT INTO "," ("\"left\",""\"right\",""\"comment\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php\\?calIds\\[\\]=(\\d+)\&calIds\\[\\]=(\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)', 'cal$1,$2,$3,$4,$5', '5', 'calendar', 'feature_calendar', 102);

INSERT INTO "," ("\"left\",""\"right\",""\"comment\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php\\?calIds\\[\\]=(\\d+)\&calIds\\[\\]=(\\d+)\&callIds\\[\\](\\d+)\&callIds\\[\\](\\d+)', 'cal$1,$2,$3,$4', '4', 'calendar', 'feature_calendar', 103);

INSERT INTO "," ("\"left\",""\"right\",""\"comment\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php\\?calIds\\[\\]=(\\d+)\&calIds\\[\\]=(\\d+)\&callIds\\[\\](\\d+)', 'cal$1,$2,$3', '3', 'calendar', 'feature_calendar', 104);

INSERT INTO "," ("\"left\",""\"right\",""\"comment\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php\\?calIds\\[\\]=(\\d+)&calIds\\[\\]=(\\d+)', 'cal$1,$2', '2', 'calendar', 'feature_calendar', 105);

INSERT INTO "," ("\"left\",""\"right\",""\"comment\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php\\?calIds\\[\\]=(\\d+)', 'cal$1', '1', 'calendar', 'feature_calendar', 106);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-calendar.php', 'calendar', 'calendar', 'feature_calendar', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-view_articles.php', 'articles', '', 'feature_articles', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-list_blogs.php', 'blogs', '', 'feature_blogs', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-browse_categories.php', 'categories', '', 'feature_categories', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-contact.php', 'contact', '', 'feature_contact', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-directory_browse.php', 'directories', '', 'feature_directory', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-list_faqs.php', 'faqs', '', 'feature_faqs', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-file_galleries.php', 'files', '', 'feature_file_galleries', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-forums.php', 'forums', '', 'feature_forums', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-galleries.php', 'galleries', '', 'feature_galleries', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-login_scr.php', 'login', '', '', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-my_tiki.php', 'my', '', '', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-newsletters.php', 'newsletters', 'newsletter', 'feature_newsletters', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-list_quizzes.php', 'quizzes', '', 'feature_quizzes', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-stats.php', 'stats', '', 'feature_stats', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-list_surveys.php', 'surveys', '', 'feature_surveys', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-list_trackers.php', 'trackers', '', 'feature_trackers', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-mobile.php', 'mobile', '', 'feature_mobile', 200);

INSERT INTO "," ("\"left\",""\"right\",""\"type\",""\"feature\",""\"order\",") VALUES ('tiki-sheets.php', 'sheets', '', 'feature_sheet', 200);


UPDATE tiki_menu_options SET icon = 'icon-configuration48x48' WHERE name = 'Admin';

UPDATE tiki_menu_options SET icon = 'xfce4-appfinder48x48' WHERE name = 'Search';

UPDATE tiki_menu_options SET icon = 'wikipages48x48' WHERE name = 'Wiki';

UPDATE tiki_menu_options SET icon = 'blogs48x48' WHERE name = 'Blogs';

UPDATE tiki_menu_options SET icon = 'stock_select-color48x48' WHERE name = 'Image Galleries';

UPDATE tiki_menu_options SET icon = 'file-manager48x48' WHERE name = 'File Galleries';

UPDATE tiki_menu_options SET icon = 'stock_bold48x48' WHERE name = 'Articles';

UPDATE tiki_menu_options SET icon = 'stock_index48x48' WHERE name = 'Forums';

UPDATE tiki_menu_options SET icon = 'gnome-settings-font48x48' WHERE name = 'Trackers';

UPDATE tiki_menu_options SET icon = 'users48x48' WHERE name = 'Community';

UPDATE tiki_menu_options SET icon = 'stock_dialog_question48x48' WHERE name = 'FAQs';

UPDATE tiki_menu_options SET icon = 'maps48x48' WHERE name = 'Maps';

UPDATE tiki_menu_options SET icon = 'messages48x48' WHERE name = 'Newsletters';

UPDATE tiki_menu_options SET icon = 'vcard48x48' WHERE name = 'Freetags';

UPDATE tiki_menu_options SET icon = 'date48x48' WHERE name = 'Calendar' AND url = 'tiki-calendar.php';

UPDATE tiki_menu_options SET icon = 'userfiles48x48' WHERE name = 'MyTiki';

UPDATE tiki_menu_options SET icon = '' WHERE name = 'Quizzes';

UPDATE tiki_menu_options SET icon = '' WHERE name = 'Surveys';

UPDATE tiki_menu_options SET icon = '' WHERE name = 'TikiSheet';

UPDATE tiki_menu_options SET icon = '' WHERE name = 'Workflow';

UPDATE tiki_menus SET use_items_icons='y' WHERE menuId=42;


DROP TABLE IF EXISTS "tiki_plugin_security";

CREATE TABLE "tiki_plugin_security" (
  "fingerprint" VARCHAR(200) NOT NULL PRIMARY KEY,
  "status" VARCHAR(10) NOT NULL,
  "approval_by" VARCHAR(200) NULL,
  "last_update" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "last_objectType" VARCHAR(20) NOT NULL,
  "last_objectId" VARCHAR(200) NOT NULL
);

CREATE INDEX "_last_object" ON ""("last_objectType","last_objectId");

DROP TABLE IF EXISTS "tiki_user_reports";

CREATE TABLE IF NOT EXISTS "tiki_user_reports" (
  "id" bigserial,
  "user" varchar(200) COLLATE latin1_general_ci NOT NULL,
  "interval" varchar(20) COLLATE latin1_general_ci NOT NULL,
  "view" varchar(8) COLLATE latin1_general_ci NOT NULL,
  "type" varchar(5) COLLATE latin1_general_ci NOT NULL,
  "time_to_send" datetime NOT NULL,
  "always_email" smallint NOT NULL,
  "last_report" datetime NOT NULL,
  PRIMARY KEY ("id")
);


DROP TABLE IF EXISTS "tiki_user_reports_cache";

CREATE TABLE IF NOT EXISTS "tiki_user_reports_cache" (
  "id" bigserial,
  "user" varchar(200) COLLATE latin1_general_ci NOT NULL,
  "event" varchar(200) COLLATE latin1_general_ci NOT NULL,
  "data" text COLLATE latin1_general_ci NOT NULL,
  "time" datetime NOT NULL,
  PRIMARY KEY ("id")
);


DROP TABLE IF EXISTS "tiki_perspectives";

CREATE TABLE "tiki_perspectives" (
  "perspectiveId" bigserial,
  "name" varchar(100) NOT NULL,
  PRIMARY KEY( perspectiveId )
);


DROP TABLE IF EXISTS "tiki_perspective_preferences";

CREATE TABLE "tiki_perspective_preferences" (
  "perspectiveId" int NOT NULL,
  "pref" varchar(40) NOT NULL,
  "value" text,
  PRIMARY KEY( perspectiveId, pref )
);


;

