<?php
/**********************************************************/
/*	@copyright	OCTemplates 2015-2019.					  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**********************************************************/

class ControllerExtensionModuleOctBannerPlus extends Controller {
	public function index($setting) {

            $data['oct_lazyload'] = false;

            $this->load->model('tool/image');

            $data['oct_lazy_image'] = $this->model_tool_image->resize($this->config->get('theme_oct_feelmart_lazyload_image') ? $this->config->get('theme_oct_feelmart_lazyload_image') : 'catalog/1lazy/lazy-image.svg', 30, 30);

			if ($this->registry->has('oct_mobiledetect')) {
		        if ($this->oct_mobiledetect->isMobile() && !$this->oct_mobiledetect->isTablet() && $this->config->get('theme_oct_feelmart_lazyload_mobile')) {
		            $data['oct_lazyload'] = true;
		        } elseif ($this->oct_mobiledetect->isTablet() && $this->config->get('theme_oct_feelmart_lazyload_tablet')) {
                    $data['oct_lazyload'] = true;
                } elseif ($this->config->get('theme_oct_feelmart_lazyload_desktop')) {
                    $data['oct_lazyload'] = true;
                }
		    } elseif ($this->config->get('theme_oct_feelmart_lazyload_desktop')) {
                $data['oct_lazyload'] = true;
            }
			
		static $module = 0;

		$this->load->model('octemplates/module/oct_banner_plus');

		$this->load->model('tool/image');

		$data['oct_banner_pluss'] = [];

		$data['position'] = isset($setting['position']) ? $setting['position'] : '';

		$results = $this->model_octemplates_module_oct_banner_plus->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE.$result['image'])) {
				$data['oct_banner_pluss'][] = [
					'title'              => $result['title'],
					'button'             => $result['button'],
					'link'               => ($result['link'] == '#' or empty($result['link'])) ? 'javascript:;' : $result['link'],
					'description'        => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
					'image'              => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),
					'background_color'   => $result['background_color'],
					'title_color'        => $result['title_color'],
					'text_color'         => $result['text_color'],
					'button_color'       => $result['button_color'],
					'button_background'  => $result['button_background'],
					'button_background_hover'  => $result['button_background_hover'],
					'button_text_hover'  => $result['button_text_hover'],
				];
			}
		}

		$data['module'] = $module++;

		return $this->load->view('octemplates/module/oct_banner_plus', $data);
	}
}