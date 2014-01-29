<?php

include 'wp-blog-header.php';

function postwp($title,$content,$cat){

/*

3->status
4->photo
5->link
6->video

*/

if($cat=="status"){
$catt=array('3');
}
else if($cat=="photo"){
$catt=array('4');
}
else if($cat=="link"){
$catt=array('5');
}
else if($cat=="video"){
$catt=array('6');
}

$my_post = array(
  'post_title' => $title,
  'post_content' => $content,
  'post_status' => 'publish',
  'post_author' => 2,
  'post_category' => $catt,
  'post_type' => 'post'
);

wp_insert_post( $my_post );

}



/*

first we check if the user has created the post of it is of other user.

then we check the date

if all is well then we create a wp post

We need to track the following variables - 

name
type ( status , picture , link , video)
created_time


message
picture



Status ->> Message only , date

Photo ->> Message + Picture ( large (n) ) , date

Link ->> Name + Picture + link + description , date

Video ->> message , picture , link , name , description , date


*/

$data=file_get_contents("https://graph.facebook.com/me/feed?access_token=".$access-token);

$arr=json_decode($data,true);

//var_dump($arr);
//echo $arr['data']['0']['from']['id'];

foreach($arr['data'] as $dd){

if($dd['from']['id']=="650722772"){

//var_dump($dd);
$cc=explode("-",$dd['created_time']);
$cc2=explode("T",$cc[2]);

if(date(d)==$cc2[0]){





if($dd['type']=="photo"){

//echo "Photo~"."<br>";
//echo $dd['created_time']."<br>";

if(preg_match("/\_s\.jpg/",$dd['picture'])){

$dd['picture']=str_replace("_s.jpg","_n.jpg",$dd['picture']);

}

$content=$dd['message']."<br><br><img src=\"".$dd['picture']."\"></img>";

postwp("Photo Ping",$content,"photo");

}
else if($dd['type']=="status"&&$dd['message']!=''){

//echo "Status~"."<br>";
//echo $dd['created_time']."<br>";

$content=$dd['message'];

postwp("Status Ping",$content,"status");

}
else if($dd['type']=="link"){

//echo "Link~"."<br>";
//echo $dd['created_time']."<br>";

$content="<b>".$dd['name']."</b><br><br>"."<img src=\"".$dd['picture']."\"></img><br><br>".$dd['link']."<br><br>".$dd['description'];

postwp("Link Ping",$content,"link");

}
else if($dd['type']=="video"){

//echo "Video~"."<br>";
//echo $dd['created_time']."<br>";

$content="<b>".$dd['message']."</b><br><br><b>".$dd['name']."</b><br><br><img src=\"".$dd['picture']."\"></img><br><br>".$dd['link']."<br><br>".$dd['description'];

postwp("Video Ping",$content,"video");

}



}

}

}

?>