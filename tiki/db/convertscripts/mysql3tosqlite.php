<?php

// read file
$file="../tiki-1.8-mysql.sql";
@$fp = fopen($file,"r");
if(!$fp) echo "Error opening $file";
$data = '';
echo "reading $file: ";
while(!feof($fp)) {
  $data .= fread($fp,4096);
  echo ".";
}
fclose($fp);
echo "<br/>\n";


// split into statements

$statements=split(";",$data);

echo "<table>\n";
// step though statements
$fp=fopen($file."_to_sqlite.sql","w");
foreach ($statements as $statement)
{
  echo "<tr><td><pre>\n";
  echo $statement.";";
  echo "\n</pre></td><td><pre>\n";
  $parsed=parse($statement);
  fwrite($fp,$parsed);
  echo $parsed;
  echo "\n</pre></td></tr>\n";
}
fclose($fp);
echo "</table>\n";

function parse($stmt)
{
  // variable for statements that have to be appended
  global $poststmt;
  $poststmt="\n\n";

  //replace comments
  $stmt=preg_replace("/#/","--",$stmt);
  // drop TYPE=MyISAM and AUTO_INCREMENT=1
  $stmt=preg_replace("/TYPE=MyISAM/","",$stmt);
  $stmt=preg_replace("/AUTO_INCREMENT=1/","",$stmt);
  //postgres cannot DROP TABLE IF EXISTS
  $stmt=preg_replace("/DROP TABLE IF EXISTS/","DROP TABLE",$stmt);
  //auto_increment things
  $stmt=preg_replace("/int\(.\) NOT NULL auto_increment/","serial",$stmt);
  $stmt=preg_replace("/int\(..\) NOT NULL auto_increment/","bigserial",$stmt);
  // integer types
  $stmt=preg_replace("/int\([1-4]\)/","smallint",$stmt);
  $stmt=preg_replace("/int\([5-9]\)/","integer",$stmt);
  $stmt=preg_replace("/int\(..\)/","bigint",$stmt);
  // timestamps
  $stmt=preg_replace("/timestamp\([^\)]+\)/","timestamp(3)",$stmt);
  // blobs
  $stmt=preg_replace("/longblob|tinyblob|blob/","bytea",$stmt);
  // quote column names
  $stmt=preg_replace("/  ([a-zA-Z0-9_]+)/","  \"$1\"",$stmt);
  // quote and record table names
  $stmt=preg_replace("/(DROP TABLE |CREATE TABLE )([a-zA-Z0-9_]+)( \()*/e","record_tablename('$1','$2','$3')",$stmt);
  // unquote the PRIMARY and other Keys
  $stmt=preg_replace("/  \"(PRIMARY|KEY|FULLTEXT|UNIQUE)\"/","  $1",$stmt);
  // convert enums
  $stmt=preg_replace("/  (\"[a-zA-Z0-9_]+\") enum\(([^\)]+)\)/e","convert_enums('$1','$2')",$stmt);
  // quote column names in primary keys
  $stmt=preg_replace("/  (PRIMARY KEY)  \((.+)\),*/e","quote_prim_cols('$1','$2')",$stmt);
  // create indexes from KEY ...
  $stmt=preg_replace("/  KEY ([a-zA-Z0-9_]+) \((.+)\),*/e","create_index('$1','$2')",$stmt);
  $stmt=preg_replace("/  FULLTEXT KEY ([a-zA-Z0-9_]+) \((.+)\),*/e","create_index('$1','$2')",$stmt);
  $stmt=preg_replace("/  (UNIQUE) KEY ([a-zA-Z0-9_]+) \((.+)\),*/e","create_index('$2','$3','$1')",$stmt);
  // handle inserts
  $stmt=preg_replace("/INSERT INTO ([a-zA-Z0-9_]*).*\(([^\)]+)\) VALUES (.*)/e","do_inserts('$1','$2','$3')",$stmt);
  $stmt=preg_replace("/INSERT IGNORE INTO ([a-zA-Z0-9_]*).*\(([^\)]+)\) VALUES (.*)/e","do_inserts('$1','$2','$3')",$stmt);
  // why does i modifier not work???
  $stmt=preg_replace("/insert into ([a-zA-Z0-9_]*).*\(([^\)]+)\) values(.*)/e","do_inserts('$1','$2','$3')",$stmt);
  // the update
  $stmt=preg_replace("/update ([a-zA-Z0-9_]+) set (.*)/e","do_updates('$1','$2')",$stmt);
  return $stmt.";".$poststmt;
}

function record_tablename($stmt,$tabnam,$tail)
{
  global $table_name;
  $table_name=$tabnam;
  return($stmt."\"".$tabnam."\"".$tail);
}

function create_index($name,$content,$type="")
{
  global $table_name;
  global $poststmt;
  $poststmt.="CREATE $type INDEX \"".$table_name."_".$name."\" ON \"".$table_name."\"(";
  $cols=split(",",$content);
  $allvals="";
  foreach ($cols as $vals) {
    $vals=preg_replace("/\(.*\)/","",$vals);
    $vals=preg_replace("/([a-zA-Z0-9_]+)/","\"$1\"",$vals);
    $allvals.=$vals;
  }
  $allvals=preg_replace("/\"\"/","\",\"",$allvals);
  $poststmt.=$allvals.");\n";
}

function do_updates($tab,$content)
{
  $ret="UPDATE \"".$tab."\" SET ";
  $cols=split(",",$content);
  foreach ($cols as $vals) {
    $vals=preg_replace("/([a-zA-Z0-9_]+)=([a-zA-Z0-9_]+)/","\"$1\"=\"$2\"",$vals);
    $ret.=$vals;
  }
  $ret=preg_replace("/\"\"/","\",\"",$ret);
  return($ret);
}

function do_inserts($tab,$content,$tail)
{
  $ret="INSERT INTO \"".$tab."\" (";
  $cols=split(",",$content);
  foreach ($cols as $vals) {
    $vals=preg_replace("/ /","",$vals);
    $ret.="\"$vals\"";
  }
  $ret=preg_replace("/\"\"/","\",\"",$ret);
  $ret.=")";
  
  $tail=preg_replace("/md5\(\'(.+)\'\)/e","quotemd5('$1')",$tail);
  return $ret." VALUES ".$tail;
}

function quotemd5($a)
{ return ("'".md5($a)."'");}

function quote_prim_cols($key,$content)
{
  $ret="  $key (";
  $cols=split(",",$content);
  foreach ($cols as $vals) {
    $vals=preg_replace("/\(.*\)/","",$vals);
    $ret.="\"$vals\"";
  }
  $ret=preg_replace("/\"\"/","\",\"",$ret);
  $ret.=")";
  return $ret;
}

function convert_enums($colname,$content)
{
 $enumvals=split(",",$content);
 $isnum=true;
 $length=0;
 $colname=stripslashes($colname);
 $ret="  $colname ";
 foreach ($enumvals as $vals) {
   if (!is_int($vals)) $isnum=false;
   if (strlen($vals)>$length) $length=strlen($vals);
 }
 if ($isnum) {
   if ($length < 4) $ret.="smallint ";
   elseif ($length < 9) $ret.="integer ";
   else $ret.="bigint ";
 } else {
   $ret.="varchar($length) ";
 }
 $ret.="CHECK ($colname IN ($content))";
 return $ret;
}
?>
