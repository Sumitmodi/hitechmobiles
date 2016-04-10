<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->header();
        $this->footer();
    }

    private function header()
    {
        $this->data['category_data'] = $this->db->get_where('category')->result_array();
        $this->data['slider_data'] = $this->db->get_where('slider')->result_array();
        // $this->data['hot_deals_data'] = $this->db->get_where('slider')->result_array();
        $this->data['hot_deals_data'] = $this->db->where('featured_product = 1')->get('products')->result_array();

        $product_price_data = $this->db->get_where('product_prices')->result_array();
        $product_photos_data = $this->db->get_where('product_photos')->result_array();

        $stock_data = $this->db->get_where('product_stock')->result_array();

        for ($i=0; $i < count($this->data['hot_deals_data']); $i++) { 
            for ($j=0; $j < count($product_price_data); $j++) { 
                if ($this->data['hot_deals_data'][$i]['id'] == $product_price_data[$j]['product_id']) {
                    $this->data['hot_deals_data'][$i]['cost_inc_gst'] = $product_price_data[$j]['cost_inc_gst'];
                    $this->data['hot_deals_data'][$i]['website_price'] = $product_price_data[$j]['website_price'];
                }
            }
        }

        for ($i=0; $i < count($this->data['hot_deals_data']); $i++) { 
            for ($j=0; $j < count($product_photos_data); $j++) { 
                if ($this->data['hot_deals_data'][$i]['id'] == $product_photos_data[$j]['product_id']) {
                    $this->data['hot_deals_data'][$i]['image_name'] = $product_photos_data[$j]['name'];
                    
                }
            }
        }
    }

    private function footer()
    {
        $this->data['pages'] = $this->
        db->
        select('slug_name,name')->
        order_by('createdDate', 'asc')->
        get('pages')->
        result_object();
    }

    public function home()
    {
        $this->data['slider_data'] = $this->db->get_where('slider')->result_array();
        $this->load->view('frontEnd/index', $this->data);
    }

    public function about()
    {
        $this->load->view('frontEnd/about', $this->data);
    }

    public function testimonials()
    {
        echo 'testimonials page';
    }

    public function category($slug = null)
    {
        echo 'category page.';
    }

}