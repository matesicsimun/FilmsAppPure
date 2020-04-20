<?php


require_once "db/DBFilmRepository.php";

$repo = \db\DBFilmRepository::getInstance();


$result = $repo->select(1);

$result = $repo->get_genre("action");
//print_r($result);


$image_url = "db/images/antitrust.jpg";
//print_r(explode('/',mime_content_type($image_url))[1]);


//$data = ["title"=>"testni_film", "year"=>1991, "duration"=>100, "headline"=>"db/images/antitrust.jpg", "image_type"=>"jpg", "genre"=>"action"];
//$repo->add_film($data);

$movie_list = $repo->get_movies("T");

//foreach($movie_list as $movie){
//    echo $movie->__toString();
//}



//$repo->delete(57);

$img_data = $repo->get_image_data_and_type(1);
header("Content-type: " . $img_data['image_type']);
echo $img_data['image_data'];