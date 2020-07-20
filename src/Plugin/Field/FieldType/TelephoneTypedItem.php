<?php

namespace Drupal\telephone_typed\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'telephone_typed' field type.
 *
 * @FieldType(
 *   id = "telephone_typed",
 *   label = @Translation("Telephone Typed"),
 *   category = @Translation("General"),
 *   default_widget = "telephone_typed",
 *   default_formatter = "telephone_typed_default"
 * )
 */
class TelephoneTypedItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->value !== NULL) {
      return FALSE;
    }
    elseif ($this->type !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Number'));
    $properties['type'] = DataDefinition::create('string')
      ->setLabel(t('Type'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    $options['value']['NotBlank'] = [];

    $options['type']['AllowedValues'] = array_keys(TelephoneTypedItem::allowedTypeValues());

    $options['type']['NotBlank'] = [];

    $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
    $constraints[] = $constraint_manager->create('ComplexData', $options);
    // @todo Add more constraints here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'value' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'type' => [
        'type' => 'varchar',
        'length' => 255,
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {

    $random = new Random();

    $values['value'] = mt_rand(pow(10, 8), pow(10, 9) - 1);

    $values['type'] = array_rand(self::allowedTypeValues());

    return $values;
  }

  /**
   * Returns allowed values for 'type' sub-field.
   *
   * @return array
   *   The list of allowed values.
   */
  public static function allowedTypeValues() {
    return [
      'land_line' => t('Land Line'),
      'mobile' => t('Mobile'),
      'fax' => t('Fax'),
    ];
  }

}
