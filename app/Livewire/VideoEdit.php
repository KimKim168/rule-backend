<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Video;

use Image;
use Storage;
use Illuminate\Support\Facades\File;

class VideoEdit extends Component
{
    use WithFileUploads;

    public $item;
    public $image;
    public $file;

    public $name = null;
    public $duration = null;
    public $link = null;
    public $description = null;

    public function mount($id)
    {
        $this->item = Video::findOrFail($id);

        $this->name = $this->item->name;
        $this->duration = $this->item->duration;
        $this->link = $this->item->link;
        $this->description = $this->item->description;
    }

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
            'file' => 'file|max:51200', // 2MB Max
        ]);

        session()->flash('success', 'file successfully uploaded!');
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
            'duration' => 'nullable',
            'link' => 'nullable|string|max:255',
            'description' => 'nullable',
        ]);

        foreach ($validated as $key => $value) {
            if (is_null($value) || $value === '') {
                $validated[$key] = null;
            }
        }

        if (!empty($this->image)) {
            $filename = time() . str()->random(10) . '.' . $this->image->getClientOriginalExtension();

            $image_path = public_path('assets/images/videos/' . $filename);
            $image_thumb_path = public_path('assets/images/videos/thumb/' . $filename);
            $imageUpload = Image::make($this->image->getRealPath())->save($image_path, 70);
            $imageUpload->resize(400, null, function ($resize) {
                $resize->aspectRatio();
            })->save($image_thumb_path, 70);
            $validated['image'] = $filename;

            $old_path = public_path('assets/images/videos/' . $this->item->image);
            $old_thumb_path = public_path('assets/images/videos/thumb/' . $this->item->image);
            if (File::exists($old_path)) {
                File::delete($old_path);
            }
            if (File::exists($old_thumb_path)) {
                File::delete($old_thumb_path);
            }
        } else {
            $validated['image'] = $this->item->image;
        }

        if (!empty($this->file)) {
            $directory = '';

            if (!Storage::disk('publicForVideo')->exists($directory)) {
                Storage::disk('publicForVideo')->makeDirectory($directory);
            }

            $filename = time() . str()->random(10) . '.' . $this->file->getClientOriginalExtension();
            $this->file->storeAs($directory, $filename, 'publicForVideo');
            $validated['file'] = $filename;

            $old_file = public_path('assets/videos/' . $this->item->file);
            if (File::exists($old_file)) {
                File::delete($old_file);
            }
        } else {
            $validated['file'] = $this->item->file;
        }

        $this->item->update($validated);

        return redirect('admin/videos')->with('success', 'Successfully updated!');
    }

    public function render()
    {
        return view('livewire.video-edit');
    }
}
