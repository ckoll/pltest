<?php
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

require_once(APPPATH . 'libraries/ImageMagick.php');

function getSquareUpload($upload, $side=250) {
    if ($upload['image_type'] != 'gif') {
        $uploadPath = _getUploadPath($upload);
        $squareUploadPath = _getSquareUploadPath($upload);
        if (!file_exists($squareUploadPath)) {
            $imageMagic = new ImageMagick();
            $imageMagic->resizeAndCrop($uploadPath, $squareUploadPath, array('width'=>$side, 'height'=>$side));
        }
    }

    return _getSquareUploadUrl($upload);
}

function _getUploadPath($photo) {
    return realpath(APPPATH) . _getRelativeUploadsPath($photo);
}

function _getSquareUploadPath($photo) {
    return realpath(APPPATH) . _getRelativeSquareUploadsPath($photo);
}

function _getUploadUrl($photo) {
    return _getRelativeUploadsPath($photo);
}

function _getSquareUploadUrl($photo) {
    return _getRelativeSquareUploadsPath($photo);
}

function _getRelativeUploadsPath($photo) {
    return "/files/users/uploads/{$photo['uid']}/{$photo['id']}{$photo['rand_num']}.".$photo['image_type'];
}

function _getRelativeSquareUploadsPath($photo) {
    return "/files/users/uploads/{$photo['uid']}/{$photo['id']}{$photo['rand_num']}_square.".$photo['image_type'];
}



function getSquareDressup($dressup, $side=250) {
    $dressupPath = _getDressupPath($dressup);
    $squareDressupPath = _getSquareDressupPath($dressup);
    if (!file_exists($squareDressupPath)) {
        $imageMagic = new ImageMagick();
        $imageMagic->cropAndResizeDoll($dressupPath, $squareDressupPath, array('width'=>350, 'height'=>350, 'cwidth'=>$side, 'cheight'=>$side,'offset_left'=>0, 'offset_top'=>85));
    }

    return _getSquareDressupUrl($dressup);
}

function _getDressupPath($photo) {
    return realpath(APPPATH) . _getRelativeDressupsPath($photo);
}

function _getSquareDressupPath($photo) {
    return realpath(APPPATH) . _getRelativeSquareDressupsPath($photo);
}

function _getDressupUrl($photo) {
    return _getRelativeDressupsPath($photo);
}

function _getSquareDressupUrl($photo) {
    return _getRelativeSquareDressupsPath($photo);
}

function _getRelativeDressupsPath($photo) {
    return "/files/users/dressup/{$photo['id']}.jpg";
}

function _getRelativeSquareDressupsPath($photo) {
    return "/files/users/dressup/{$photo['id']}_square.jpg";
}