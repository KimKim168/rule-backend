<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Page;

use Image;

class PageCreate extends Component
{
    use WithFileUploads;

    public $image;
    // public $pdf;


    public $name = null;
    public $name_kh = null;
    public $link = null;
    public $order_index = 0;
    public $short_decription = null;
    public $description = null;
    public $position = null;
    public $type = null;
    public $description_kh = null;
    public $link_in_product_detail = 0;

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048', // 2MB Max
        ]);

        session()->flash('success', 'Image successfully uploaded!');
    }

    // public function updatedPdf()
    // {
    //     $this->validate([
    //         'pdf' => 'file|max:2048', // 2MB Max
    //     ]);

    //     session()->flash('success', 'PDF successfully uploaded!');
    // }

    // public function updated()
    // {
    //     $this->dispatch('livewire:updated');
    // }


    public function save()
    {
        $this->dispatch('livewire:updated');
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'short_decription' => 'nullable|string|max:255',
            'description' => 'nullable',
            'position' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'image' => 'required|file|max:2048',
            'link' => 'nullable|max:255',
            'order_index' => 'nullable',
        ]);

        // $validated['create_by_user_id'] = request()->user()->id;

        // dd($validated);

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

        // if (!empty($this->pdf)) {
        //     $filename = time() . '_' . $this->pdf->getClientOriginalName();
        //     $this->pdf->storeAs('publications', $filename, 'publicForPdf');
        //     $validated['pdf'] = $filename;
        // }

        $createdPublication = Page::create($validated);

        // dd($createdPublication);
        return redirect('admin/settings/pages')->with('success', 'Successfully Created!');

        // session()->flash('message', 'Image successfully uploaded!');
    }

    public function render()
    {
        // dd($allKeywords);
        // dump($this->selectedallKeywords);

        return view('livewire.page-create');
    }
}
