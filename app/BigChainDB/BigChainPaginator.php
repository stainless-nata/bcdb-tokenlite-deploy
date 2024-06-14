<?php
/**
 * BigChain Collection : 
 *
 */
namespace App\BigChainDB;

use Illuminate\Support\Facades\Log;

class BigChainPaginator {
    protected $query;
    protected $items = [];
    protected $total;
    protected $page;
    protected $pageSize;
    protected $baseUrl;

    public function __construct(BigChainQuery $query) {
        $this->query = $query;
    }

    public function appends($request) {
        $this->page = $request->page ?? 1;
        $this->pageSize = $request->pageSize ?? 10;
        $this->baseUrl = $request->url();
        
        $data = $this->query->pagination($this->page, $this->pageSize);
        $this->items = $data['items'];
        $this->total = $data['total'];
        return $this;
    }

    public function url($page) {

    }

    public function previousPageUrl() {

    }

    public function nextPageUrl() {

    }

    public function currentPage() {

    }

    public function lastPage() 
    {
        return 0;
    }

    public function total() {
        return 10;
    }

    public function hasPages() {
        return true;
    }

    public function getItems() {
        return $this->items;
    }
}