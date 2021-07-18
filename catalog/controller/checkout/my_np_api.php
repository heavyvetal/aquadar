<?php
class ControllerCheckoutMyNPApi extends Controller {

    private $description_language;
    private $region_default_selector;

    private $descriptions = array(
        'ru' => array(
            'description' => 'DescriptionRu',
            'region_default_selector' => '-- Выберите область --'
        ),
        'ua' => array(
            'description' => 'Description',
            'region_default_selector' => '-- Оберіть область --'
        )
    );

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->description_language = $this->descriptions[$this->language->get('code')]['description'];
        $this->region_default_selector = $this->descriptions[$this->language->get('code')]['region_default_selector'];

    }

    public function init()
    {
        $this->load->model('delivery/npapi');
        $this->model_delivery_npapi->setKey('15684ba5d03bbd44783afb84650f3110');
    }
    
    public function index() {

    }

    public function getAreas () {
        $result = '<option value="0" selected>'.$this->region_default_selector.'</option>';

        $this->init();

        $areas = $this->model_delivery_npapi->getAreas();

        $this->load->model('tool/simpleapimain');
        // Перевод кодов новой почты в коды опенкарта, чтобы отображать регион в заказе
        $area_codes = $this->model_tool_simpleapimain->getAreaCodes();

        foreach ($areas['data'] as $area) {
            $result .= '<option value="'.$area_codes[$area['Ref']].'" data-value="'.$area['Ref'].'">'.$area[$this->description_language].'</option>';
        }

        $this->response->setOutput($result);
    }

    public function getCities()
    {
        $area_name = '';
        $city_name = '';
        $result = '';

        $this->init();

        $areas = $this->model_delivery_npapi->getAreas();

        if(isset($this->request->post['selected']) && isset($this->request->post['city'])) {

            // Нету ли латинницы или цифр в запросе
            if (!preg_match('/[A-z0-9]/', $this->request->post['city'])) {
                foreach ($areas['data'] as $area) {
                    if ($area[$this->description_language] == $_POST['selected']) {
                        $area_name = $area[$this->description_language];
                        break;
                    }
                }

                $city_name = $this->request->post['city'];
                $cities = $this->model_delivery_npapi->getCity($city_name, $area_name, '');

                foreach ($cities['data'] as $city) {
                    $result .= '<li data-value="' . $city['Ref'] . '" style="cursor:pointer;">' . $city[$this->description_language] . '</li>';
                }
            }
        }

        $this->response->setOutput($result);

    }

    public function getWarehouses()
    {
        $city_ref = '';
        $result = '';

        $this->init();

        if(isset($this->request->post['selected'])) {
            $city_ref = $this->request->post['selected'];
            $warehouses = $this->model_delivery_npapi->getWarehouses($city_ref);

            foreach ($warehouses['data'] as $warehouse) {
                $result .= '<li data-value="'.$warehouse['Ref'].'" style="cursor:pointer;">'.$warehouse[$this->description_language].'</li>';
            }
        }

        $this->response->setOutput($result);
    }

}