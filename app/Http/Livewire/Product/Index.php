<?php

namespace App\Http\Livewire\Product;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $categories;
    public $brands;
    public $collections;
    public $openMenus;
    
    public $query;
    public $category;
    public $brand = [];
    public $collection = [];
    public $orderby;

    protected $queryString = [
        'query' => ['except' => ''],
        'category' => ['except' => ''],
        'brand' => ['except' => false],
        'collection' => ['except' => false],
        'orderby' => ['except' => '']
    ];

    public function updatingQuery($value)
    {
        if(trim($value))
        $this->resetPage();
    }

    public function updatedQuery()
    {
        //$this->query = trim($this->query);
        $this->emit('storeQuery', $this->query);
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function updatingCollection()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['query', 'category', 'brand', 'collection', 'orderby']);
        $this->setOpenMenus();
    }

    public function gotoPage($page) { 
        $this->setPage($page); 
        $this->emit('goTop');
    }

    public function previousPage($pageName) { 
        $this->gotoPage(max($this->paginators[$pageName] - 1, 1), $pageName); 
        $this->emit('goTop');
    }

    public function nextPage($pageName) { 
        $this->setPage($this->paginators[$pageName] + 1, $pageName);
        $this->emit('goTop');
    }

    public function isSetFilters()
    {
        return $this->query ||$this->category || $this->collection || $this->brand || $this->orderby;
    }

    public function isParentCategorySelected($menuCategory)
    {
        return $menuCategory->id == $this->category || $menuCategory->slug == $this->category ||
            $this->categories->where('parent_id',$menuCategory->id)->pluck('id')->contains($this->category) ||
            $this->categories->where('parent_id',$menuCategory->id)->pluck('slug')->contains($this->category);
    }

    public function setOpenMenus()
    {
        $this->openMenus = Str::of('');
        if ($this->category)
        {
            $categoryModel = Category::where('id', (int) $this->category)->orWhere('slug', $this->category)->first();
            $this->openMenus = Str::of($categoryModel->hierarchyPath())->explode('>');
        }
    }

    public function toggleCategory($category)
    {
        $this->resetPage();
        $categoryModel = Category::where('id', (int) $category)->orWhere('slug', $category)->first();
        if($this->category == $categoryModel->slug) $this->category = $categoryModel->parent ? $categoryModel->parent->slug : null;
        else $this->category = $categoryModel->slug;
        $this->setOpenMenus();
    }

    public function voiceSearch($transcript) {
        if( isset($transcript['final']) && trim($transcript['final']) ) $this->query = $transcript['final'];
    }

    public function mount()
    {
        $this->categories = Category::all();
        $this->collections = Collection::all();
        $this->brands = Brand::all();
        $this->setOpenMenus();
    }

    public function render()
    {
        $this->categories = Category::filterByProducts([
            'query' => $this->query,
            'collection' => $this->collection,
            'brand' => $this->brand,
        ])->orderBy('name')->get();

        $this->collections = Collection::filterByProducts([
            'query' => $this->query,
            'category' => $this->category,
            'brand' => $this->brand,
        ])->orderBy('name')->get();

        $this->brands = Brand::filterByProducts([
            'query' => $this->query,
            'category' => $this->category,
            'collection' => $this->collection,
        ])->orderBy('name')->get();

        return view('product.index',[
            'products' => Product::with('media')->filter([
                'query' => $this->query,
                'category' => $this->category,
                'brand' => $this->brand,
                'collection' => $this->collection
            ])->paginate(36)
        ]);
    }
}
