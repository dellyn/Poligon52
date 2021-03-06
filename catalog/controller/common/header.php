<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');


//		if((isset($this->request->request['route']) && $this->request->request['route'] =='common/home') || $this->request->server['REQUEST_URI'] == '/'){
//            $data['is_home'] = true;
//        }else{
//            $data['is_home'] = false;
//        }
         echo $_SERVER['REQUEST_URI'];
        if ($_SERVER['REQUEST_URI']=="/opencart/" || $_SERVER['REQUEST_URI']=="/opencart/index.php?route=common/home") {
            $data['is_home'] = true;
        }else{
            $data['is_home'] = false;
        }

        if ($_SERVER['REQUEST_URI']=="/opencart/index.php?route=product/category&path=60") {
            $data['is_catalogs'] = true;
        }else{
            $data['is_catalogs'] = false;
        }
        if ($_SERVER['REQUEST_URI']=="/opencart/index.php?route=product/category&path=61") {
            $data['is_news'] = true;
        }else{
            $data['is_news'] = false;
        } if ($_SERVER['REQUEST_URI']=="/opencart/index.php?route=product/category&path=62") {
            $data['is_post'] = true;
        }else{
            $data['is_post'] = false;
        }
        if ($_SERVER['REQUEST_URI']=="/opencart/index.php?route=product/product&path=60&product_id=53") {
            $data['is_products'] = true;
        }else{
            $data['is_products'] = false;
        }
        if ($_SERVER['REQUEST_URI']=="/opencart/index.php?route=information/information&information_id=7") {
            $data['is_dostavka'] = true;
        }else{
            $data['is_dostavka'] = false;
        }
//        if ($_SERVER['REQUEST_URI']=="/opencart/index.php?route=information/contact") {
//            $data['is_contacts'] = true;
//        }else{
//            $data['is_contacts'] = false;
//        }






		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

        $data['theme_url'] = 'catalog/view/theme/polig';

        $data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}




		$data['count_cart'] = count($this->cart->getProducts());
        $this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['telephone'] = $this->config->get('config_telephone');
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');

        $data['contacts_href'] = $this->url->link('information/contact');
        $data['services_href'] = $this->url->link('information/information','information_id=4');
        $data['catalogs_href']  = $this->url->link('product/category', 'path=60');
        $data['dostavka_href']  = $this->url->link('information/information&information_id=7');


        return $this->load->view('common/header', $data);
	}
}
