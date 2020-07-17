<?php

namespace Drupal\telephone_typed\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\telephone_typed\Plugin\Field\FieldType\TelephoneTypedItem;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'telephone_typed' field widget.
 *
 * @FieldWidget(
 *   id = "telephone_typed",
 *   label = @Translation("Telephone Typed"),
 *   field_types = {"telephone_typed"},
 * )
 */
class TelephoneTypedWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#default_value' => isset($items[$delta]->phone_number) ? $items[$delta]->phone_number : NULL,
      '#size' => 20,
    ];

    $element['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => ['' => $this->t('- Select a value -')] + TelephoneTypedItem::allowedTypeValues(),
      '#default_value' => isset($items[$delta]->type) ? $items[$delta]->type : NULL,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
    $element['#attributes']['class'][] = 'telephone-typed-elements';
    $element['#attached']['library'][] = 'telephone_typed/telephone_typed';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['phone_number'] === '') {
        $values[$delta]['phone_number'] = NULL;
      }
      if ($value['type'] === '') {
        $values[$delta]['type'] = NULL;
      }
    }
    return $values;
  }

}
