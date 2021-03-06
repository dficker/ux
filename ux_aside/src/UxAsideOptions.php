<?php

namespace Drupal\ux_aside;

use Drupal\ux\UxOptionsBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Color;

/**
 * Class UxAsideOptions.
 *
 * @package Drupal\ux_aside
 */
class UxAsideOptions extends UxOptionsBase {

  /**
   * {@inheritdoc}
   */
  public function getModuleId() {
    return 'ux_aside';
  }

  /**
   * {@inheritdoc}
   */
  public function processOptions(array $options) {
    $defaults = $this->getDefaults();
    // Convert trigger icon to micon.
    if (!empty($options['trigger']['icon']) && is_string($options['trigger']['icon']) && $this->hasIconSupport()) {
      $options['trigger']['text'] = micon($options['trigger']['text'])->setIcon($options['trigger']['icon'])->setIconOnly($options['trigger']['iconOnly']);
    }
    // Convert content icon to micon.
    if (!empty($options['content']['icon']) && is_string($options['content']['icon']) && $this->hasIconSupport()) {
      $options['content']['iconText'] = micon()->setIcon($options['content']['icon'])->setIconOnly(TRUE)->render();
      $options['content']['icon'] = '';
    }
    // Convert overlay color to RGBA.
    if (!empty($options['content']['overlay']) && !empty($options['content']['overlayColor']) && Color::validateHex($options['content']['overlayColor'])) {
      // Because we convert this color to rgba we need to check if the current
      // value is actually different than the system default. If it isn't we
      // can keep it as a hex as it will be removed during diff checking.
      if ($options['content']['overlayColor'] !== $defaults['content']['overlayColor']) {
        $rgb = Color::hexToRgb($options['content']['overlayColor']);
        $options['content']['overlayColor'] = 'rgba(' . implode(',', $rgb) . ',0.4)';
      }
    }
    // Convert timeout progress bar color to RGBA.
    if (!empty($options['content']['timeoutProgressbar']) && !empty($options['content']['timeoutProgressbarColor']) && Color::validateHex($options['content']['timeoutProgressbarColor'])) {
      // Because we convert this color to rgba we need to check if the current
      // value is actually different than the system default. If it isn't we
      // can keep it as a hex as it will be removed during diff checking.
      if ($options['content']['timeoutProgressbarColor'] !== $defaults['content']['timeoutProgressbarColor']) {
        $rgb = Color::hexToRgb($options['content']['timeoutProgressbarColor']);
        $options['content']['timeoutProgressbarColor'] = 'rgba(' . implode(',', $rgb) . ',0.5)';
      }
    }
    // Convert width to int if only numbers exist.
    if (!empty($options['content']['width'])) {
      if (is_numeric($options['content']['width'])) {
        $options['content']['width'] = (int) $options['content']['width'];
      }
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function optionsMerge(array $options, $is_processed = FALSE) {
    $options = parent::optionsMerge($options, $is_processed);
    // Strange issue where checkboxes that return TRUE are not being saved when
    // submitted via AJAX. As a result, we turn them to strings 'true' and
    // convert them here.
    if (isset($options['content'])) {
      foreach ($options['content'] as &$value) {
        if ($value === 'true') {
          $value = TRUE;
        }
      }
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function optionsForm(array $defaults = []) {

    $form = [
      '#type' => 'details',
      '#title' => $this->t('Aside Options'),
      '#open' => TRUE,
    ];

    $form['trigger'] = [
      '#type' => 'details',
      '#title' => $this->t('Trigger'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];

    $form['trigger']['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text'),
      '#description' => $this->t('The text to be placed within the link that will trigger the aside element.'),
      '#default_value' => $defaults['trigger']['text'],
      '#required' => TRUE,
    ];

    if ($this->hasIconSupport()) {
      $form['trigger']['icon'] = [
        '#type' => 'micon',
        '#title' => $this->t('Icon'),
        '#default_value' => $defaults['trigger']['icon'],

      ];
      $form['trigger']['iconOnly'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Icon Only'),
        '#default_value' => $defaults['trigger']['iconOnly'],
      ];
    }

    $form['content'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Content'),
      '#tree' => TRUE,
    ];

    $form['content']['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Width'),
      '#description' => $this->t('Fixed width of the aside. You can use %, px, em or cm. If not using a measure unity, PX will be assumed as measurement unit.'),
      '#default_value' => $defaults['content']['width'],
      '#required' => TRUE,
    ];

    // Header settings.
    $form['content']['header'] = [
      '#type' => 'details',
      '#title' => $this->t('Header'),
      '#process' => [[get_class(), 'processParents']],
    ];

    $form['content']['header']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t("Title in aside's header."),
      '#default_value' => $defaults['content']['title'],
    ];

    $form['content']['header']['subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sub-title'),
      '#description' => $this->t("Caption below aside's title."),
      '#default_value' => $defaults['content']['subtitle'],
    ];

    if ($this->hasIconSupport()) {
      $form['content']['header']['icon'] = [
        '#type' => 'micon',
        '#title' => $this->t('Icon'),
        '#default_value' => $defaults['content']['icon'],

      ];
      $form['content']['header']['iconText'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Icon Text'),
        '#default_value' => $defaults['content']['iconText'],
      ];
    }

    $form['content']['header']['closeButton'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display close button in the header.'),
      '#default_value' => $defaults['content']['closeButton'],
      '#return_value' => 'true',
    ];

    // Position settings.
    $form['content']['position'] = [
      '#type' => 'details',
      '#title' => $this->t('Position and Fullscreen'),
      '#process' => [[get_class(), 'processParents']],
    ];

    $form['content']['position']['attachTop'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Attach to top of page'),
      '#default_value' => $defaults['content']['attachTop'],
      '#return_value' => 'true',
      '#attributes' => [
        'class' => ['ux-aside-attach-top'],
      ],
      '#states' => [
        'disabled' => [
          ['.ux-aside-attach-bottom' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-left' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-right' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-open-fullscreen' => ['checked' => TRUE]],
        ],
      ],
    ];

    $form['content']['position']['attachBottom'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Attach to bottom of page'),
      '#default_value' => $defaults['content']['attachBottom'],
      '#return_value' => 'true',
      '#attributes' => [
        'class' => ['ux-aside-attach-bottom'],
      ],
      '#states' => [
        'disabled' => [
          ['.ux-aside-attach-top' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-left' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-right' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-open-fullscreen' => ['checked' => TRUE]],
        ],
      ],
    ];

    $form['content']['position']['attachLeft'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Attach to left of page'),
      '#default_value' => $defaults['content']['attachLeft'],
      '#return_value' => 'true',
      '#attributes' => [
        'class' => ['ux-aside-attach-left'],
      ],
      '#states' => [
        'disabled' => [
          ['.ux-aside-attach-top' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-bottom' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-right' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-open-fullscreen' => ['checked' => TRUE]],
        ],
      ],
    ];

    $form['content']['position']['attachRight'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Attach to right of page'),
      '#default_value' => $defaults['content']['attachRight'],
      '#return_value' => 'true',
      '#attributes' => [
        'class' => ['ux-aside-attach-right'],
      ],
      '#states' => [
        'disabled' => [
          ['.ux-aside-attach-top' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-bottom' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-left' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-open-fullscreen' => ['checked' => TRUE]],
        ],
      ],
    ];

    $form['content']['position']['openFullscreen'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Force to open aside in fullscreen.'),
      '#default_value' => $defaults['content']['openFullscreen'],
      '#return_value' => 'true',
      '#attributes' => [
        'class' => ['ux-aside-open-fullscreen'],
      ],
      '#states' => [
        'disabled' => [
          ['.ux-aside-attach-top' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-bottom' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-left' => ['checked' => TRUE]],
          ['or'],
          ['.ux-aside-attach-right' => ['checked' => TRUE]],
        ],
      ],
    ];

    $form['content']['position']['fullscreen'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show a button in aside header to allow full screen expanding.'),
      '#default_value' => $defaults['content']['fullscreen'],
      '#return_value' => 'true',
      '#states' => [
        'disabled' => [
          '.ux-aside-open-fullscreen' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['content']['position']['bodyOverflow'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Force overflow hidden in the document when opening the aside, closing the aside, overflow will be restored.'),
      '#default_value' => $defaults['content']['bodyOverflow'],
      '#return_value' => 'true',
    ];

    // Position settings.
    $form['content']['style'] = [
      '#type' => 'details',
      '#title' => $this->t('Style and Colors'),
      '#process' => [[get_class(), 'processParents']],
    ];

    $form['content']['style']['theme'] = [
      '#type' => 'select',
      '#title' => $this->t('Theme'),
      '#options' => [
        'light' => $this->t('Light'),
        'dark' => $this->t('Dark'),
      ],
      '#empty_option' => $this->t('- None -'),
      '#default_value' => $defaults['content']['theme'],
    ];

    $form['content']['style']['overlay'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable background overlay.'),
      '#default_value' => $defaults['content']['overlay'],
      '#return_value' => 'true',
    ];

    $form['content']['style']['borderBottom'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable border bottom.'),
      '#default_value' => $defaults['content']['borderBottom'],
      '#return_value' => 'true',
    ];

    $form['content']['style']['padding'] = [
      '#type' => 'number',
      '#title' => $this->t('Padding'),
      '#field_suffix' => 'px',
      '#description' => $this->t('Aside inner margin.'),
      '#default_value' => $defaults['content']['padding'],
    ];

    $form['content']['style']['radius'] = [
      '#type' => 'number',
      '#title' => $this->t('Radius'),
      '#field_suffix' => 'px',
      '#description' => $this->t('Border-radius that will be applied in aside.'),
      '#default_value' => $defaults['content']['radius'],
    ];

    $form['content']['style']['headerColor'] = [
      '#type' => 'color',
      '#title' => $this->t('Header color'),
      '#default_value' => $defaults['content']['headerColor'],
    ];

    if ($this->hasIconSupport()) {
      $form['content']['style']['iconColor'] = [
        '#type' => 'color',
        '#title' => $this->t('Icon color'),
        '#default_value' => $defaults['content']['iconColor'],
      ];
    }

    $form['content']['style']['overlayColor'] = [
      '#type' => 'color',
      '#title' => $this->t('Overlay color'),
      '#default_value' => $defaults['content']['overlayColor'],
    ];

    $form['content']['style']['timeoutProgressbarColor'] = [
      '#type' => 'color',
      '#title' => $this->t('Timeout progress bar color'),
      '#default_value' => $defaults['content']['timeoutProgressbarColor'],
    ];

    // Transition settings.
    $form['content']['transition'] = [
      '#type' => 'details',
      '#title' => $this->t('Transitions'),
      '#process' => [[get_class(), 'processParents']],
    ];

    $form['content']['transition']['transitionIn'] = [
      '#type' => 'select',
      '#title' => $this->t('Transition In'),
      '#description' => $this->t('Aside opening default transition.'),
      '#options' => [
        'comingIn' => $this->t('Coming In'),
        'bounceInDown' => $this->t('Bounce In Down'),
        'bounceInUp' => $this->t('Bounce In Up'),
        'fadeInDown' => $this->t('Fade In Down'),
        'fadeInUp' => $this->t('Fade In Up'),
        'fadeInLeft' => $this->t('Fade In Left'),
        'fadeInRight' => $this->t('Fade In Right'),
        'flipInX' => $this->t('Flip In X'),
      ],
      '#default_value' => $defaults['content']['transitionIn'],
    ];

    $form['content']['transition']['transitionOut'] = [
      '#type' => 'select',
      '#title' => $this->t('Transition Out'),
      '#description' => $this->t('Aside opening default transition.'),
      '#options' => [
        'comingOut' => $this->t('Coming Out'),
        'bounceOutDown' => $this->t('Bounce Out Down'),
        'bounceOutUp' => $this->t('Bounce Out Up'),
        'fadeOutDown' => $this->t('Fade Out Down'),
        'fadeOutUp' => $this->t('Fade Out Up'),
        'fadeOutLeft' => $this->t('Fade Out Left'),
        'fadeOutRight' => $this->t('Fade Out Right'),
        'flipOutX' => $this->t('Flip Out X'),
      ],
      '#default_value' => $defaults['content']['transitionOut'],
    ];

    // Timeout settings.
    $form['content']['timeout'] = [
      '#type' => 'details',
      '#title' => $this->t('Auto-open and Timeout'),
      '#process' => [[get_class(), 'processParents']],
    ];

    $form['content']['timeout']['autoOpen'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('If true, the aside opens automatically with any user action.'),
      '#default_value' => $defaults['content']['autoOpen'],
      '#return_value' => 'true',
    ];

    $form['content']['timeout']['timeout'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Timeout'),
      '#description' => $this->t('Amount in milliseconds to close the aside or false to disable.'),
      '#default_value' => $defaults['content']['timeout'],
    ];

    $form['content']['timeout']['timeoutProgressbar'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable timeout progress bar.'),
      '#default_value' => $defaults['content']['timeoutProgressbar'],
      '#return_value' => 'true',
    ];

    // Timeout settings.
    $form['content']['extras'] = [
      '#type' => 'details',
      '#title' => $this->t('Extras'),
      '#process' => [[get_class(), 'processParents']],
    ];

    // Extra settings.
    $form['content']['extras']['focusInput'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto-focus on first input'),
      '#description' => $this->t('Will be ignored if the input does not contain any form fields.'),
      '#default_value' => $defaults['content']['focusInput'],
      '#return_value' => 'true',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function processParents(&$element, FormStateInterface $form_state, &$complete_form) {
    array_pop($element['#parents']);
    return $element;
  }

}
