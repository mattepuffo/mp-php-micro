<?php

namespace helpers;
/**
 * @author Matteo Ferrone
 * @since 2019-11-29
 */
class ImageUpload {

  private $image;
  private $fileTmpName;
  private $fileName;
  private $fileSize;
  private $fileType;

  public function __construct($fileTmpName, $fileName, $fileSize, $fileType) {
    $this->fileTmpName = $fileTmpName;
    $this->fileName = $fileName;
    $this->fileSize = $fileSize;
    $this->fileType = $fileType;
    $this->image = new Imagick($this->fileTmpName);
  }

  public function check($typeArray, $maxSize = 1024) {
    $fileSizeMB = $this->fileSize / 1024;
    if (is_uploaded_file($this->fileTmpName)) {
      if (!in_array($this->fileType, $typeArray)) {
        return 'Il file non è tra quelli ammessi';
      } elseif ($fileSizeMB > $maxSize) {
        return 'Il file è troppo grande';
      } else {
        return TRUE;
      }
    } else {
      return 'Si è verficato un errore o non è stato inviato nessun file';
    }
  }

  public function uploadImage($finalName) {
    $this->image->writeimage($finalName);
    return 'File caricato';
  }

  public function uploadImageScale($width, $heigth, $finalName) {
    $this->image->scaleimage($width, $heigth);
    $this->image->writeimage($finalName);
    return 'File caricato';
  }

}
