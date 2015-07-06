<?php
/**
 * Author: Ted Bowman
 * Date: 5/17/15
 * Time: 11:10 AM
 */

namespace Drupal\eform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\eform\Entity\EFormType;
use Drupal\views\Views;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\DisplayPluginCollection;
class EFormTypeController extends ControllerBase{

  /**
   * Return Submissions page for the Entityform type
   * @param \Drupal\eform\Entity\EFormType $eform_type
   */
  public function submissions_page(EFormType $eform_type, $views_display_id = NULL) {
    $view_name = $eform_type->getSubmissionsView();
    $output = array();
    if ($usable_displays = $this->getViewDisplays($view_name)) {
      if (count($usable_displays) > 1) {
        $output['submissions_links'] = $this->submissions_links($eform_type, $usable_displays);
      }
      if (empty($views_display_id) && !isset($usable_displays[$views_display_id])) {
        // Default to first display.
        $display_ids = array_keys($usable_displays);
        $views_display_id = array_shift($display_ids);
      }
      $views_output =  views_embed_view($view_name, $views_display_id, $eform_type->type);
      $output['submissions_view'] = $views_output;
    }
    else {
      // No useable displays in this View.
    }
    return $output;
  }

  protected function submissions_links(EFormType $eform_type, array $displays) {
    $links_output = array(
      '#theme' => 'links',
      '#links' => [],
      '#attributes' => ['class' => ['tabs', 'secondary']],
    );
    /** @var DisplayPluginBase $display */
    foreach ($displays as $display_id => $display) {
      $route_args = [
        'eform_type' => $eform_type->type,
        'views_display_id' => $display_id
      ];
      $url = Url::fromRoute('entity.eform_type.submissions', $route_args);
      $link = array(
        'title' => $display->getOption('title'),
        'url' => $url,
        // @todo This is not working
        '#attributes' => ['class' => ['tabs__tab']],
      );

      $links_output['#links'][] = $link;
    }
    return $links_output;
  }
  /**
   * @param $view
   *
   * @return array;
   */
  protected function getViewDisplays($view_name) {
    $view = Views::getView($view_name);
    $view->initDisplay();
    //@ todo make a list of links of views displays that are "embed"
    $displays = $view->displayHandlers;
    $useable_displays = [];
    /* @var DisplayPluginBase $display */
    foreach($displays as $key => $display) {
      if ($this->isUseableDisplay($display)) {
        $useable_displays[$key] = $display;
      }
    }
    return $useable_displays;
  }

  /**
   * Check if a View display is useable to show EForm submissions.
   *
   * @param \Drupal\views\Plugin\views\display\DisplayPluginBase $display
   *
   * @return bool
   */
  protected function isUseableDisplay(DisplayPluginBase $display) {
    // @todo Is there a better way to check the for "embed" rather than checking class?
    $class = get_class($display);
    if ($display->isEnabled() && $class == 'Drupal\views\Plugin\views\display\Embed') {
      return TRUE;
    }
    return FALSE;
}

}
