<?php
/**
 * @file
 * Contains \Drupal\product_scan\Plugin\Block\ProductScanBlock
 */

namespace Drupal\product_scan\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ProductScanBlock' List Block.
 *
 * @Block(
 *   id = "product_scan_block",
 *   admin_label = @Translation("Product Scan Block"),
 *   category = @Translation("Blocks")
 * )
 */
class ProductScanBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        global $base_url;
        $path = '';
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node instanceof \Drupal\node\NodeInterface) {
            // You can get nid and anything else you need from the node object.
            //$nid = $node->id();
            //$typeName = $node->bundle();
            //$typeLabel = $node->getTitle();
            $productQrcode = \Drupal::service('product_scan.qrcode');
            $qrcode = $productQrcode->getQrcode($node);
            $path = $base_url.$qrcode;
        }

        $build = [];
        $build['#markup'] = '<img src ="'.$path.'">';

        return  $build;
    }

    public function getCacheMaxAge()
    {
        return 0;
    }
}
