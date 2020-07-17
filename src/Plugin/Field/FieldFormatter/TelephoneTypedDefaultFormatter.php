<?php

namespace Drupal\telephone_typed\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Url;
use Drupal\telephone_typed\Plugin\Field\FieldType\TelephoneTypedItem;

/**
 * Plugin implementation of the 'telephone_typed_default' formatter.
 *
 * @FieldFormatter(
 *   id = "telephone_typed_default",
 *   label = @Translation("Default"),
 *   field_types = {"telephone_typed"}
 * )
 */
class TelephoneTypedDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {

      if ($item->phone_number) {
        $element[$delta]['phone_number'] = [
          '#type' => 'item',
          '#title' => $this->t('Phone Number'),
          'content' => [
            '#type' => 'link',
            '#title' => $item->phone_number,
            '#url' => Url::fromUri('tel:' . rawurlencode(preg_replace('/\s+/', '', $item->phone_number))),
          ],
        ];
      }

      if ($item->type) {
        $allowed_values = TelephoneTypedItem::allowedTypeValues();
        $element[$delta]['type'] = [
          '#type' => 'item',
          '#title' => $this->t('Type'),
          '#markup' => $allowed_values[$item->type],
        ];
      }

    }

    return $element;
  }

}
