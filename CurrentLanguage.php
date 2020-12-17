<?php

namespace Drush\Commands\current_language;

use Consolidation\AnnotatedCommand\CommandData;
use Drupal\language\ConfigurableLanguageManagerInterface;
use Drush\Commands\DrushCommands;

/**
 * Ensures the current language is in use.
 */
class CurrentLanguage extends DrushCommands {

  /**
   * Ensure current language is set.
   *
   * @hook pre-command *
   */
  public function validateCommand(CommandData $commandData) {
    /** @var \Drupal\Core\Language\LanguageManagerInterface $languageManager */
    $languageManager = \Drupal::service('language_manager');
    /** @var \Drupal\language\LanguageNegotiatorInterface $negotiator */
    $negotiator = \Drupal::service('language_negotiator');
    $negotiator->setCurrentUser(\Drupal::currentUser());

    if ($languageManager instanceof ConfigurableLanguageManagerInterface) {
      $languageManager->setNegotiator($negotiator);
      $languageManager->setConfigOverrideLanguage($languageManager->getCurrentLanguage());
    }
    $translation = \Drupal::translation();
    $translation->setDefaultLangcode($languageManager->getCurrentLanguage()->getId());
  }

}
