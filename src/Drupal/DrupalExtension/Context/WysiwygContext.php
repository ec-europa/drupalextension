<?php

namespace Drupal\DrupalExtension\Context;

/**
 * Provides step definitions to interact with the wysiwyg editor.
 */
class WysiwygContext extends RawDrupalContext {

  /**
   * Enter text in a wysiwyg instance.
   *
   * @param string $text
   *   The text to enter.
   * @param string $name
   *   The input id, name or label of the wysiwyg editor field.
   *
   * @When I enter :text in the :name text editor
   */
  public function iEnterTextInWysiwyg($text, $name) {
    $instance = $this->getInstanceByField($name);
    $this->waitUntilInstanceIsReady($instance);

    $this->getSession()->executeScript("{$instance}.setData('" . $text . "')");
  }

  /**
   * Returns the Javascript variable of a wysiwyg instance for a certain field.
   *
   * @param string $name
   *   Input id, name or label of the field.
   *
   * @return string
   *   A string representing the Javascript variable of the instance.
   */
  protected function getInstanceByField($name) {
    $field = $this->getSession()->getPage()->findField($name);

    return "CKEDITOR.instances['{$field->getAttribute('id')}']";
  }

  /**
   * Waits until a wysiwyg instance is ready in the browser.
   *
   * @param string $instance
   *   The editor instance.
   *
   * @throws \Exception
   *   Raised when the instance is never ready.
   */
  protected function waitUntilInstanceIsReady($instance) {
    $result = $this->getSession()->wait(5000, "'undefined' !== typeof {$instance} && {$instance}.status == 'ready';");

    if (!$result) {
      throw new \Exception(sprintf('The wysiwyg instance %s was never ready.', $instance));
    }
  }

}
