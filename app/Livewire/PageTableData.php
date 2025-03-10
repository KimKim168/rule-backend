<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Page;

class PageTableData extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $filter = '';

    #[Url(history: true)]
    public $position = '';


    #[Url(history: true)]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'order_index';

    #[Url(history: true)]
    public $sortDir = 'ASC';

    public function setFilter($value) {
        $this->filter = $value;
        $this->resetPage();
    }



    public function setSortBy($newSortBy) {
        if($this->sortBy == $newSortBy){
            $newSortDir = ($this->sortDir == 'DESC') ? 'ASC' : 'DESC';
            $this->sortDir = $newSortDir;
        }else{
            $this->sortBy = $newSortBy;
        }
    }
    public function delete($id) {
        $item = Page::findOrFail($id);
        $item->delete();

        session()->flash('success', 'Successfully deleted!');
    }

    // ResetPage when updated search
    public function updatedSearch() {
        $this->resetPage();
    }


    public function render() {

        $query = Page::query();

        if($this->filter != ''){
            $query->where('position', $this->filter);
        }

        if($this->search != ''){
            $query->where('name', 'LIKE', "%$this->search%")
            ->orWhere('name_kh', 'LIKE', "%$this->search%");
        }

        $items = $query->orderBy($this->sortBy, $this->sortDir)
                ->paginate($this->perPage);



        return view('livewire.page-table-data', [
            'items' => $items,
        ]);
    }


    // public function render(){

    //     $items = Page::where(function($query){
    //                             $query->where('name', 'LIKE', "%$this->search%")
    //                                 ->orWhere('name_kh', 'LIKE', "%$this->search%");
    //                         })
    //                         ->when($this->filter != '', function($query) {
    //                             $query->where('name', $this->filter);
    //                         })
    //                         ->where('position', '=', '')
    //                         ->orderBy($this->sortBy, $this->sortDir)
    //                         ->paginate($this->perPage);

    //     return view('livewire.page-table-data', [
    //         'items' => $items,
    //     ]);
    // }
}
