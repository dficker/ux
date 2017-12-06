<?php

namespace Drupal\ux\Element;

use Drupal\Core\Render\Element\Submit;
use Drupal\Core\Template\Attribute;

/**
 * Provides a form submit button.
 *
 * Submit buttons are processed the same as regular buttons, except they trigger
 * the form's submit handler.
 *
 * Properties:
 * - #submit: Specifies an alternate callback for form submission when the
 *   submit button is pressed.  Use '::methodName' format or an array containing
 *   the object and method name (for example, [ $this, 'methodName'] ).
 * - #value: The text to be shown on the button.
 *
 * Usage Example:
 * @code
 * $form['actions']['submit'] = array(
 *   '#type' => 'ux_button',
 *   '#value' => $this->t('Save'),
 *   '#as_button' => TRUE,
 * );
 * @endcode
 *
 * @see \Drupal\Core\Render\Element\Button
 *
 * @FormElement("ux_button")
 */
class UxButton extends Submit {

  /**
   * {@inheritdoc}
   */
  public static function preRenderButton($element) {
    $element = parent::preRenderButton($element);

    if (isset($element['#label']) && $element['#label'] !== '') {
      $element['#attached']['library'][] = 'ux/ux.button';

      // Wrapper attributes.
      $wrapper_attributes = isset($element['#wrapper_attributes']) && is_array($element['#wrapper_attributes']) ? $element['#wrapper_attributes'] : [];
      $wrapper_attributes['class'][] = 'ux-button';
      $wrapper_attributes = new Attribute($wrapper_attributes);

      // Trigger attributes.
      $trigger_attributes = isset($element['#trigger_attributes']) && is_array($element['#trigger_attributes']) ? $element['#trigger_attributes'] : [];
      $trigger_attributes['class'][] = 'ux-button-trigger';
      if (!empty($element['#as_button'])) {
        $trigger_attributes['class'][] = 'button';
      }
      $trigger_attributes = new Attribute($trigger_attributes);

      // Hide default input.
      $element['#attributes']['class'][] = 'js-hide';

      $element['#prefix'] = '<a' . $wrapper_attributes . '>';
      $element['#suffix'] = '<span' . $trigger_attributes . '>' . $element['#label'] . '</span></a>';
    }

    return $element;
  }

}
