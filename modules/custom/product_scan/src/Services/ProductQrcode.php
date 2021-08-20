<?php

namespace Drupal\product_scan\Services;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Class ProductQrcode.
 */
class ProductQrcode
{
    public function getQrcode($node)
    {
        $writer = new PngWriter();
        // Create QR code

        $qrCode = QrCode::create($node->get('body')->value)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        // Create generic logo
        $product_image = file_create_url($node->field_product_image->entity->getFileUri());
        $logo = Logo::create($product_image)
            ->setResizeToWidth(50);
        // Create generic label
        $label = Label::create($node->getTitle())
            ->setTextColor(new Color(255, 0, 0));

        $result = $writer->write($qrCode, $logo, $label);
        // Directly output the QR code
        header('Content-Type: '.$result->getMimeType());
        $directory = \Drupal::service('file_system')->realpath('public://product_qrcode');
        $file_name = $node->bundle().$node->id().'.png';
        $qrcode_path = $directory.'/'.$file_name;
        $qrcode_file_path = '/sites/default/files/product_qrcode/';
        $result->saveToFile($qrcode_path);

        return $qrcode_file_path.$file_name;
    }
}
