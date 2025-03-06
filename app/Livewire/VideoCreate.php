<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Video;

use Image;

class VideoCreate extends Component
{
    use WithFileUploads;

    public $image;
    public $file;

    public $name = null;
    public $duration = null;
    public $link = null;
    public $description = null;

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048', // 2MB Max
        ]);

        session()->flash('success', 'Image successfully uploaded!');
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'file|max:51200', // 50MB Max
        ]);

        session()->flash('success', 'File successfully uploaded!');
    }

    public function updated()
    {
        $this->dispatch('livewire:updated');
    }

    public function save()
    {
        $this->dispatch('livewire:updated');
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'file' => 'nullable|file|max:20480',
            'duration' => 'nullable',
            'link' => 'nullable|string|max:255',
            'description' => 'nullable',
        ]);

        $validated['create_by_user_id'] = request()->user()->id;
        foreach ($validated as $key => $value) {
            if (is_null($value) || $value === '') {
                $validated[$key] = null;
            }
        }

        if (!empty($this->image)) {
            $filename = time() . str()->random(10) . '.' . $this->image->getClientOriginalExtension();

            $image_path = public_path('assets/images/videos/' . $filename);
            $image_thumb_path = public_path('assets/images/videos/thumb/' . $filename);
            $imageUpload = Image::make($this->image->getRealPath())->save($image_path);
            $imageUpload->resize(400, null, function ($resize) {
                $resize->aspectRatio();
            })->save($image_thumb_path);
            $validated['image'] = $filename;
        }

        if (!empty($this->file)) {
            $filename = time() . str()->random(10) . '.' . $this->file->getClientOriginalExtension();
            $this->file->storeAs('videos', $filename, 'publicForPdf');
            $validated['file'] = $filename;
        }

        Video::create($validated);

        return redirect('admin/videos')->with('success', 'Successfully uploaded!');

    }

    public function render()
    {
        return view('livewire.video-create');
    }
}
