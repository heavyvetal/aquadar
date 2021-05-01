<?php
class ControllerCheckoutMyNPApi extends Controller {
    public function index() {

    }

    public function getAreas () {
        $result = '<option value="0" selected>-- Выберите область --</option>';

        $this->load->model('delivery/npapi');
        $this->model_delivery_npapi->setKey('15684ba5d03bbd44783afb84650f3110');

        $areas = $this->model_delivery_npapi->getAreas();

        $this->load->model('tool/simpleapimain');
        // Костыль перевода кодов новой почты в коды опенкарта, чтобы отображать регион в заказе
        $area_codes = $this->model_tool_simpleapimain->getAreaCodes();

        foreach ($areas['data'] as $area) {
            $result .= '<option value="'.$area_codes[$area['Ref']].'">'.$area['DescriptionRu'].'</option>';
        }

        $this->response->setOutput($result);
    }

    public function getCities()
    {
        $area_name = 'Днепропетровская';
        $city_name = '';
        $result = '';

        $this->load->model('delivery/npapi');
        $this->model_delivery_npapi->setKey('15684ba5d03bbd44783afb84650f3110');

        $areas = $this->model_delivery_npapi->getAreas();

        if(isset($this->request->post['selected']) && isset($this->request->post['city'])) {

            foreach ($areas['data'] as $area) {
                if ($area['Ref'] == $_POST['selected']) {
                    $area_name = $area['DescriptionRu'];
                    break;
                }
            }

            $city_name = $this->request->post['city'];
            $cities = $this->model_delivery_npapi->getCity($city_name, $area_name, '');

            foreach ($cities['data'] as $city) {
                $result .= '<li data-value="'.$city['DescriptionRu'].'" style="cursor:pointer;">'.$city['DescriptionRu'].'</li>';
            }
        }

        $this->response->setOutput($result);

    }

}