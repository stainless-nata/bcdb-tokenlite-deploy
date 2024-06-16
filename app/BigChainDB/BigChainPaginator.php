<?php
/**
 * BigChain Collection : 
 *
 */
namespace App\BigChainDB;

use Illuminate\Support\Facades\Log;

class BigChainPaginator {
    protected $query;
    protected $total;
    protected $pageCount;
    protected $page;
    protected $pageSize;
    protected $baseUrl;
    protected $items = [];
    protected $position = 0;

    public function __construct(BigChainQuery $query, $pageSize = null) {
        $this->query = $query;
        $this->pageSize = $pageSize ?? 10;
    }

    public function appends($request) {
        $this->page = $request->page ?? 1;
        $this->baseUrl = $request->url();
        $data = $this->query->pagination($this->page, $this->pageSize);       
        $this->items = $data['items'];
        $this->total = $data['total'];
        $this->pageCount = ceil($this->total / $this->pageSize);
        return $this;
    }

    public function url($page) {
        if($page < 1 || $page > $this->pageCount) return null;
        return $this->baseUrl . '?page=' . $page;
    }

    public function previousPageUrl() {
        return $this->url($this->page - 1);
    }

    public function nextPageUrl() {
        return $this->url($this->page + 1);
    }

    public function currentPage() {
        return $this->page;
    }

    public function lastPage() 
    {
        return $this->pageCount;
    }

    public function total() {
        return $this->pageCount;
    }

    public function hasPages() {
        return $this->pageCount > 1;
    }

    public function getItems() {
        return $this->items;
    }
}