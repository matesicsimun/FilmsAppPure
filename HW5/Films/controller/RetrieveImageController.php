<?php

namespace controller;

use InvalidArgumentException;

class RetrieveImageController extends AbstractController {

    private $id;
    private $film_repository;

    public function __construct($id, \FilmRepositoryInterface $film_repository) {
        $this->id = $id;
        $this->film_repository = $film_repository;
    }

    public function doAction() {
        $img_data = $this->film_repository->get_image_data_and_type($this->id);
        $this->show_image_from_data($img_data);
    }

    protected function doJob() {

    }

    private function show_image_from_data(array $image_data){
        $format = "Content-Type: image/".$image_data['image_type'];
        header($format);
        echo $image_data['image_data'];
    }

    function imagecreatefromfile( $filename ) {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "'.$filename.'" not found.');
        }
        switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
                break;

            case 'png':
                return imagecreatefrompng($filename);
                break;

            case 'gif':
                return imagecreatefromgif($filename);
                break;

            default:
                throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
                break;
        }
    }

    private function show_image(string $file_type, $image){
        $format = "Content-Type: image/".$file_type;
        header($format);

        switch($file_type){
            case "jpg":
            case "jpeg":
                imagejpeg($image);
                break;
            case "gif":
                imagegif($image);
                break;
            case "png":
                imagepng($image);
                break;
            default:
                imagepng($image);

        }
        imagedestroy($image);
    }
}
