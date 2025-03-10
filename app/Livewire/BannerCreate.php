<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Banner;

use Image;

class BannerCreate extends Component
{
    use WithFileUploads;

    public $image;
    // public $pdf;

    public $name = null;
    // public $position = 'top';
    public $order_index = null;
    public $link = null;

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
            'order_index' => 'nullable|int',
            // 'position' => 'nullable|max:255',
            'link' => 'nullable|max:255',
            'image' => 'required|image|max:2048',
        ]);

        // dd($validated);

        if(!empty($this->image)){
            // $filename = time() . '_' . $this->image->getClientOriginalName();
            $filename = time() . str()->random(10) . '.' . $this->image->getClientOriginalExtension();

            $image_path = public_path('assets/images/banners/'.$filename);
            $image_thumb_path = public_path('assets/images/banners/thumb/'.$filename);
            $imageUpload = Image::make($this->image->getRealPath())->save($image_path);
            $imageUpload->resize(1280,null,function($resize){
                $resize->aspectRatio();
            })->save($image_thumb_path);
            $validated['image'] = $filename;
        }

        $createdPublication = Banner::create($validated);

        // dd($createdPublication);
        return redirect('admin/settings/banners')->with('success', 'Successfully Created!');

        // session()->flash('message', 'Image successfully uploaded!');
    }

    public function render()
    {
        // dd($allKeywords);
        // dump($this->selectedallKeywords);

        return view('livewire.banner-create');
    }
}
