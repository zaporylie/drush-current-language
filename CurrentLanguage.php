<?php

namespace Drush\Commands\drush_current_language;

use Consolidation\AnnotatedCommand\CommandData;
use Drupal\language\ConfigurableLanguageManagerInterface;
use Drush\Commands\DrushCommands;
use Drush\Drush;

/**
 * Ensures the current language is in use.
 */
class CurrentLanguageCommands extends DrushCommands {

  /**
   * Ensure current language is set.
   *
   * @hook pre-command *
   */
  public function preCommand(CommandData $commandData) {
    // Drupal must be fully bootstraped in order to use this validation.
    $boot_manager = Drush::bootstrapManager();
    if (!$boot_manager->hasBootstrapped(DRUSH_BOOTSTRAP_DRUPAL_FULL)) {
      return;
    }
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
