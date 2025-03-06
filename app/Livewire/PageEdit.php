<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Page;
use Livewire\WithFileUploads;

use Image;

class PageEdit extends Component
{
    use WithFileUploads;

    public $image;

    public $item; // Variable to hold the item record for editing
    public $name;
    public $name_kh;
    public $link;
    public $order_index;
    public $type;
    public $position;
    public $description;
    public $short_decription;
    public $description_kh;
    public $link_in_product_detail;

    public function mount(Page $item)
    {
        $this->item = $item; // Initialize the $item variable with the provided item model instance
        $this->name = $item->name;
        // $this->name_kh = $item->name_kh;
        $this->link = $item->link;
        $this->type = $item->type;
        $this->description = $item->description;
        $this->position = $item->position;
        // $this->image = $item->image;
        $this->short_decription = $item->short_decription;
        $this->order_index = $item->order_index;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048', // 2MB Max
        ]);

        session()->flash('success', 'Image successfully uploaded!');
    }

    public function save()
    {
        $validated = $this->validate([
           'name' => 'required|string|max:255',
            'short_decription' => 'nullable|string|max:255',
            'description' => 'nullable',
            'position' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'link' => 'nullable|max:255',
            'order_index' => 'nullable',
        ]);

        // Update the existing item record
        if(!empty($this->image)){
            // $filename = time() . '_' . $this->image->getClientOriginalName();
            $filename = time() . str()->random(10) . '.' . $this->image->getClientOriginalExtension();

            $image_path = public_path('assets/images/pages/'.$filename);
            $image_thumb_path = public_path('assets/images/pages/thumb/'.$filename);
            $imageUpload = Image::make($this->image->getRealPath())->save($image_path);
            $imageUpload->resize(1280,null,function($resize){
                $resize->aspectRatio();
            })->save($image_thumb_path);
            $validated['image'] = $filename;
        }

        $this->item->update($validated);

        session()->flash('success', 'Item updated successfully!');

        return redirect('admin/settings/pages');
    }

    public function render()
    {
        return view('livewire.page-edit');
    }
}
