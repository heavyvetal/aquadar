<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
*/

class ModelToolSimpleApiCustom extends Model {
    public function test($str)
    {
        $values = array();

        $values[] = array(
            'id'   => 'test',
            'text' => $str
        );

        return $values;
    }

    public function getAreas($filter = '')
    {
        $values = array();

        $this->load->model('delivery/npapi');
        $this->model_delivery_npapi->setKey('15684ba5d03bbd44783afb84650f3110');

        $areas = $this->model_delivery_npapi->getAreas();

        foreach ($areas['data'] as $area) {
            $values[] = array(
                'id'   => $area['Ref'],
                'text' => $area['DescriptionRu']
            );
        }

        return $values;
    }

    public function getCities($filterFieldValue)
    {
        $values = array();
        $area_name = 'Днепропетровская';
        //!empty($filterFieldValue) ? $area_name = $filterFieldValue : $area_name = 'Днепропетровская';
        $city_name = '';

        $this->load->model('delivery/npapi');
        $this->model_delivery_npapi->setKey('15684ba5d03bbd44783afb84650f3110');

        $areas = $this->model_delivery_npapi->getAreas();

        $cities = $this->model_delivery_npapi->getCity($city_name, $area_name, '');

        foreach ($cities['data'] as $city) {
            $values[] = array(
                'id'   => $city['Ref'],
                'text' => $city['DescriptionRu']
            );
        }

        return $values;
    }

    public function example($filterFieldValue) {
        $values = array();

        $values[] = array(
            'id'   => 'my_id',
            'text' => 'my_text'
        );

        return $values;
    }

    public function checkCaptcha($value, $filter) {
        if (isset($this->session->data['captcha']) && $this->session->data['captcha'] != $value) {
            return false;
        }

        return true;
    }

    public function getYesNo($filter = '') {
        return array(
            array(
                'id'   => '1',
                'text' => $this->language->get('text_yes')
            ),
            array(
                'id'   => '0',
                'text' => $this->language->get('text_no')
            )
        );
    }
}