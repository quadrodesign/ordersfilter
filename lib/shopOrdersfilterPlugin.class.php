<?php

class shopOrdersfilterPlugin extends shopPlugin
{
    public function backendOrders()
    {
        if (!$this->getSettings('status')) {
            return false;
        }
        $view = wa()->getView();
        $plugin_model = new shopPluginModel();
        $workflow = new shopWorkflow();

        $model_settings = new waAppSettingsModel();
        $settings = $model_settings->get($key = array('shop', 'ordersfilter'));
        $is_button = $settings['is_button'] ? 1 : 0;

        $view->assign('is_button', $is_button);
        $view->assign('states', $workflow->getAvailableStates());
        $view->assign('payments', $plugin_model->listPlugins(shopPluginModel::TYPE_PAYMENT));
        $view->assign('shippings', $plugin_model->listPlugins(shopPluginModel::TYPE_SHIPPING));
        return array('sidebar_section' => $view->fetch($this->path . '/templates/actions/backend/BackendOrders.html'));
    }

    public function backendOrder()
    {
        if (!$this->getSettings('status')) {
            return false;
        }
        if (waRequest::get('hash')) {
            return array('info_section' => wa()->getView()->fetch($this->path . '/templates/actions/backend/BackendOrder.html'));
        }
        return null;
    }

    public function ordersCollection($params)
    {
        /**
         * @var shopOrdersCollection $collection
         */

        $collection = $params['collection'];
        $hash = $collection->getType();
        $filters = self::parseHash(urldecode($hash));

        $model = new shopOrderModel();

        foreach ($filters as $k => $v) {
            $key = $model->escape($k);
            $value = $model->escape($v);
            if (empty($value))
                continue;
            if (substr($key, 0, 15) == 'update_datetime') {
                $operators = array(
                    "_from" => ">=",
                    "_to" => "<="
                );
                if (array_key_exists(substr($key, 15), $operators)) {
                    $operator = $operators[substr($key, 15)];
                    $collection->addWhere("o.update_datetime" . $operator . "'" . date('Y-m-d', strtotime($value)) . "'");
                }

            } elseif (substr($key, 0, 7) == 'params.') {
                $model_params = new shopOrderParamsModel();
                $params_table_name = $model_params->getTableName();
                $collection->addJoin(
                    $params_table_name,
                    "o.id=:table.order_id AND :table.name='" . substr($key, 7) . "'",
                    ":table.value" . $this->getWhere($value)
                );
            } elseif ($model->fieldExists($key)) {
                $title[] = $key . $this->getWhere($value);
                $collection->addWhere("o." . $key . $this->getWhere($value));
            };
        }
        return true;
    }

    protected function parseHash($str)
    {
        $parse = explode('&', $str);
        $array = array();
        foreach ($parse as $key => $p) {
            $result = explode('=', $p);
            $min_parse = explode('[', $result[0]);
            if (isset($min_parse[1])) {
                $end_parse = explode(']', $min_parse[1]);
                $array[$min_parse[0]][] = $end_parse[0];
            } else {
                $array[$result[0]] = $result[1];
            }
        }
        unset($min_parse);
        unset($end_parse);
        unset($parse);
        return $array;
    }

    protected static function getWhere($var)
    {
        $where = "";
        if (is_array($var)) {
            if (count($var) > 1) {
                $where = " IN ('" . implode("','", $var) . "')";
            } else {
                $where = " = '" . $var[0] . "'";
            }
        }

        return $where;
    }
}
