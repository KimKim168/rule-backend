<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\BookSubCategory as SubCategory;
use App\Models\BookCategory as Category;

class BookSubCategoryTableData extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $perPage = 10;

    #[Url(history: true)]
    public $filter = 0;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

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

    // ResetPage when updated search
    public function updatedSearch() {
        $this->resetPage();
    }

    public function delete($id)
    {
        $subCategory = SubCategory::find($id);
        $subCategory->delete();

        session()->flash('success', 'Sub-Category successfully deleted!');
    }

    public function updateStatus($id, $status) {
        $getedItem = SubCategory::findOrFail($id);
        $getedItem->update([
            'status' => $status,
        ]);

        session()->flash('success', 'Update Successfully!');
    }

     // ==========Add New SubCategory============
     public $newDdc = null;
     public $newName = null;
     public $newName_kh = null;
     public $new_category_id = null;

     public function save()
     {
         try {
             $validated = $this->validate([
                 'newName' => 'required|string|max:255|unique:sub_categories,name',
                 'newName_kh' => 'nullable|string|max:255',
                 'new_category_id' => 'required',
                 'newDdc' => 'nullable|string|max:255',
             ]);

             SubCategory::create([
                 'ddc' => $this->newDdc,
                 'name' => $this->newName,
                 'name_kh' => $this->newName_kh,
                 'category_id' => $this->new_category_id,
             ]);

             return redirect('admin/sub_categories')->with('success', 'Add new Sub-Category successfully!');

             $this->reset(['newName', 'newName_kh', 'new_category_id']);

         } catch (\Illuminate\Validation\ValidationException $e) {
             session()->flash('error', $e->validator->errors()->all());
         }
     }

     public $editId = null;
      public $ddc;
     public $name;
     public $name_kh;
     public $category_id;

     public function setEdit($id) {
        $subCategory = SubCategory::find($id);
        $this->editId = $id;
        $this->ddc = $subCategory->ddc;
        $this->name = $subCategory->name;
        $this->name_kh = $subCategory->name_kh;
        $this->category_id = $subCategory->category_id;
     }

     public function cancelUpdate() {
        $this->editId = null;
        $this->ddc = null;
        $this->name = null;
        $this->name_kh = null;
        $this->gender = null;
        $this->category_id = null;
     }

     public function update($id) {
        try {
            $validated = $this->validate([
                'name' => 'required|string|max:255',
                'name_kh' => 'nullable|string|max:255',
                'category_id' => 'required',
                'ddc' => 'nullable|string|max:255',
            ]);

            $subCategory = SubCategory::find($id);
            $subCategory->update([
                'name' => $this->name,
                'name_kh' => $this->name_kh,
                'category_id' => $this->category_id,
                'ddc' => $this->ddc,
            ]);

            session()->flash('success', 'Sub-Category successfully edited!');

            $this->reset(['name', 'name_kh', 'editId', 'ddc']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', $e->validator->errors()->all());
        }
     }

    public function render(){

        $items = SubCategory::where(function($query){
                                $query->where('name', 'LIKE', "%$this->search%")
                                    ->orWhere('name_kh', 'LIKE', "%$this->search%");
                            })
                            ->orderBy($this->sortBy, $this->sortDir)
                            ->with('category')->paginate($this->perPage);
        $categories = Category::all();
        return view('livewire.book-sub-category-table-data', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }
}
