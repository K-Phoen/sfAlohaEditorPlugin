<?php

class sfWidgetFormAlohaTextarea extends sfWidgetFormTextArea
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('assets_dir', sfConfig::get('app_sfAlohaEditorPlugin_aloha_assets_dir'));
    $this->addOption('core_plugins', array(
      'Format', 'Table', 'List', 'HighlightEditables', 'Link', 'Paste',
      array('Link', 'LinkList.js'), array('Paste', 'wordpastehandler.js'),
    ));
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $aloha_js = strtr(<<<EOF
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery('#%id%').aloha();
  });
</script>
EOF
, array(
  '%id%'  => $this->generateId($name)
));

    return parent::render($name, $value, $attributes, $errors) . $aloha_js;
  }

  public function getJavaScripts()
  {
    $files = array(
      sprintf('%s/aloha.js', $this->getOption('assets_dir'))
    );

    // include "core plugins" (ie: bundled with aloha)
    foreach ($this->getOption('core_plugins', array()) as $plugin)
    {
      $plugin_name = is_array($plugin) ? $plugin[0] : $plugin;
      $file = is_array($plugin) ? $plugin[1] : 'plugin.js';

      $files[] = sprintf('%s/plugins/com.gentics.aloha.plugins.%s/%s', $this->getOption('assets_dir'), $plugin_name, $file);
    }

    return $files;
  }
}
