<?php

class shopOrdersfilterPluginSettingsSaveController extends waJsonController {
    
    public function execute()
    {
        $plugin_id = array('shop', 'ordersfilter');
        try {
            $app_settings_model = new waAppSettingsModel();
            $settings = waRequest::post('settings');
            
            $is_button = $settings['is_button'] ? 1 : 0 ;
            
            $app_settings_model->set($plugin_id, 'is_button', $is_button);
            $app_settings_model->set($plugin_id, 'status', (int) $settings['status']);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
}