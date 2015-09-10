<?php

class shopOrdersfilterPluginSettingsAction extends waViewAction
{
    
    public function execute()
    {
        $model_settings = new waAppSettingsModel();
        $settings = $model_settings->get($key = array('shop', 'ordersfilter')); 
        
        $this->view->assign('settings', $settings);
    }       
}
